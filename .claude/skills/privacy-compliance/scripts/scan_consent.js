#!/usr/bin/env node
/**
 * Escáner de consentimiento de cookies.
 *
 * Abre la URL en cuatro sesiones limpias: sin interactuar, tras "Aceptar", tras "Rechazar"
 * y con la señal Global Privacy Control (GPC) sin interactuar. En cada una registra cookies,
 * solicitudes a dominios de seguimiento conocidos y el estado de Google Consent Mode.
 *
 * Uso:
 *   node scan_consent.js https://ejemplo.com [--out informe.json] [--wait 6000]
 *
 * Requisitos: Playwright (paquete "playwright" o "playwright-core") y Chromium.
 *   Variables opcionales: PLAYWRIGHT_MODULE (ruta al paquete), CHROMIUM_PATH (ejecutable).
 */
'use strict';

const fs = require('fs');

function loadPlaywright() {
	const candidates = [process.env.PLAYWRIGHT_MODULE, 'playwright', 'playwright-core', '/tmp/flmmtools/node_modules/playwright'].filter(Boolean);
	for (const c of candidates) {
		try { return require(c); } catch (e) { /* siguiente */ }
	}
	console.error('No se encontró Playwright. Instálalo (npm i playwright-core) o define PLAYWRIGHT_MODULE.');
	process.exit(2);
}

const args = process.argv.slice(2);
const url = args.find(a => /^https?:\/\//.test(a));
if (!url) {
	console.error('Uso: node scan_consent.js https://ejemplo.com [--out informe.json] [--wait 6000]');
	process.exit(1);
}
const outIdx = args.indexOf('--out');
const outFile = outIdx > -1 ? args[outIdx + 1] : null;
const waitIdx = args.indexOf('--wait');
const WAIT = waitIdx > -1 ? parseInt(args[waitIdx + 1], 10) : 6000;

// Dominios de seguimiento (publicidad o analítica). GTM se trata aparte porque es solo el contenedor.
const TRACKERS = [
	['Google Analytics', /google-analytics\.com|analytics\.google\.com|\/g\/collect/],
	['Google Ads / DoubleClick', /doubleclick\.net|googleadservices\.com|googlesyndication\.com|google\.com\/pagead|\/ccm\/collect/],
	['Meta Pixel', /connect\.facebook\.net|facebook\.com\/tr/],
	['LinkedIn Insight', /snap\.licdn\.com|px\.ads\.linkedin\.com/],
	['TikTok Pixel', /analytics\.tiktok\.com/],
	['Microsoft Ads (UET)', /bat\.bing\.com/],
	['Microsoft Clarity', /clarity\.ms/],
	['Hotjar', /hotjar\.com|hotjar\.io/],
	['X / Twitter Ads', /static\.ads-twitter\.com|analytics\.twitter\.com|ads-api\.x\.com/],
	['Pinterest Tag', /ct\.pinterest\.com|s\.pinimg\.com\/ct/],
	['Reddit Pixel', /redditstatic\.com\/ads|alb\.reddit\.com/],
	['OpenAI Ads', /openai\.com\/(ads|pixel|tr)|ads\.openai\.com|oaiusercontent|chatgpt\.com\/ads/],
	['Snap Pixel', /sc-static\.net\/scevent/],
	['WordPress.com / Jetpack Stats', /stats\.wp\.com|pixel\.wp\.com/],
	['HubSpot', /js\.hs-analytics\.net|track\.hubspot\.com|js\.hs-scripts\.com/],
];
const CONTAINERS = [['Google Tag Manager', /googletagmanager\.com\/gtm\.js/], ['gtag.js', /googletagmanager\.com\/gtag\/js/]];
const TRACKING_COOKIES = /^(_ga|_gid|_gat|_gcl_|_fbp|_fbc|_ttp|_tt_|li_|lidc|bcookie|_uet|_clck|_clsk|_hj|_pin_|_rdt_|__hs|hubspotutk|IDE|test_cookie|NID|MUID)/;

const ACCEPT_SEL = ['.cmplz-accept', '#CybotCookiebotDialogBodyLevelButtonLevelOptinAllowAll', '#CybotCookiebotDialogBodyButtonAccept', '#onetrust-accept-btn-handler', '.cky-btn-accept', '#cookie_action_close_header', '.cc-allow', '[data-cookiefirst-action="accept"]', '.iubenda-cs-accept-btn', '#didomi-notice-agree-button'];
const DENY_SEL = ['.cmplz-deny', '#CybotCookiebotDialogBodyButtonDecline', '#onetrust-reject-all-handler', '.cky-btn-reject', '#cookie_action_close_header_reject', '.cc-deny', '[data-cookiefirst-action="reject"]', '.iubenda-cs-reject-btn', '#didomi-notice-disagree-button'];
const ACCEPT_TXT = /^(accept|accept all|allow all|agree|i agree|aceptar|aceptar todo|aceptar todas|permitir todo|acepto|aceitar|aceitar todos)$/i;
const DENY_TXT = /^(deny|reject|reject all|decline|refuse|denegar|rechazar|rechazar todo|rechazar todas|no acepto|recusar|rejeitar)$/i;

function classify(reqUrl) {
	// Google con Consent Mode: gcs=G100 (o G1-- con 0 en ad y analytics) indica un ping sin cookies por consentimiento denegado.
	const gcs = (reqUrl.match(/[?&]gcs=(G1[0-9-]{2})/) || [])[1];
	if (gcs && /google-analytics\.com|analytics\.google\.com|\/g\/collect|doubleclick\.net|googleadservices\.com|\/ccm\/collect/.test(reqUrl)) {
		const denied = gcs === 'G100' || /^G1[0-]{2}$/.test(gcs);
		if (denied) return { type: 'cookieless', name: 'Google (ping sin cookies, ' + gcs + ')' };
	}
	for (const [name, re] of TRACKERS) if (re.test(reqUrl)) return { type: 'tracker', name };
	for (const [name, re] of CONTAINERS) if (re.test(reqUrl)) return { type: 'container', name };
	return null;
}

async function clickConsent(page, selectors, textRe) {
	// Espera a que el banner aparezca (algunos plugins lo muestran con retraso).
	await page.waitForSelector(ACCEPT_SEL.concat(DENY_SEL).join(', '), { state: 'visible', timeout: 15000 }).catch(() => {});
	for (const sel of selectors) {
		// Algunos plugins repiten la clase en capas ocultas: se usa el primer elemento visible.
		for (const el of await page.$$(sel)) {
			if (await el.isVisible().catch(() => false)) {
				await el.click().catch(() => {});
				return sel;
			}
		}
	}
	const buttons = await page.$$('button, a[role="button"], [role="button"]');
	for (const b of buttons) {
		const txt = ((await b.innerText().catch(() => '')) || '').trim();
		if (textRe.test(txt) && await b.isVisible().catch(() => false)) {
			await b.click().catch(() => {});
			return 'texto: ' + txt;
		}
	}
	return null;
}

async function bannerInfo(page) {
	return page.evaluate(({ acc, den }) => {
		const vis = el => { if (!el) return false; const r = el.getBoundingClientRect(); const s = getComputedStyle(el); return r.width > 0 && r.height > 0 && s.visibility !== 'hidden' && s.display !== 'none'; };
		const find = (sels, re) => {
			for (const s of sels) { const el = document.querySelector(s); if (vis(el)) return el; }
			return [...document.querySelectorAll('button, a[role="button"], [role="button"]')].find(b => vis(b) && new RegExp(re, 'i').test(b.innerText.trim())) || null;
		};
		const a = find(acc.sels, acc.re);
		const d = find(den.sels, den.re);
		const style = el => el ? { text: el.innerText.trim(), bg: getComputedStyle(el).backgroundColor, color: getComputedStyle(el).color, w: Math.round(el.getBoundingClientRect().width), h: Math.round(el.getBoundingClientRect().height) } : null;
		const consent = (window.dataLayer || []).filter(e => e && e[0] === 'consent').map(e => ({ cmd: e[1], state: e[2] }));
		// Consent Mode interno de Google (lo usan las plantillas de GTM de Complianz, Cookiebot, etc.).
		const ics = window.google_tag_data && window.google_tag_data.ics && window.google_tag_data.ics.entries;
		if (ics && !consent.some(c => c.cmd === 'default')) {
			const st = {};
			Object.keys(ics).forEach(k => { const v = ics[k]; if (v && typeof v.default !== 'undefined') st[k] = v.default === true || v.default === 'granted' ? 'granted' : 'denied'; });
			if (Object.keys(st).length) consent.push({ cmd: 'default', state: st, source: 'google_tag_data' });
		}
		return { acceptButton: style(a), denyButton: style(d), consentMode: consent, lang: document.documentElement.lang };
	}, { acc: { sels: ACCEPT_SEL, re: ACCEPT_TXT.source }, den: { sels: DENY_SEL, re: DENY_TXT.source } });
}

async function run(browser, mode) {
	const ctxOpts = { ignoreHTTPSErrors: true, viewport: { width: 1366, height: 900 } };
	if (mode === 'gpc') ctxOpts.extraHTTPHeaders = { 'Sec-GPC': '1' };
	const ctx = await browser.newContext(ctxOpts);
	if (mode === 'gpc') await ctx.addInitScript(() => Object.defineProperty(navigator, 'globalPrivacyControl', { get: () => true }));
	const page = await ctx.newPage();
	const hits = [];
	page.on('request', r => { const c = classify(r.url()); if (c) hits.push({ ...c, url: r.url().slice(0, 160), phase: 'before' }); });
	let status = 0;
	for (let attempt = 1; ; attempt++) {
		try {
			const resp = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 60000 });
			status = resp ? resp.status() : 0;
			if ((status === 429 || status >= 500) && attempt < 4) throw new Error('HTTP ' + status);
			break;
		} catch (e) {
			if (attempt >= 4) throw e;
			await new Promise(r => setTimeout(r, 10000 * attempt)); // límite de peticiones o red inestable
		}
	}
	await page.waitForTimeout(WAIT);
	await page.waitForSelector(ACCEPT_SEL.concat(DENY_SEL).join(', '), { state: 'visible', timeout: 20000 }).catch(() => {});
	const banner = await bannerInfo(page);
	let clicked = null;
	if (mode === 'accept' || mode === 'deny') {
		clicked = await clickConsent(page, mode === 'accept' ? ACCEPT_SEL : DENY_SEL, mode === 'accept' ? ACCEPT_TXT : DENY_TXT);
		hits.forEach(h => { h.phase = 'before'; });
		page.removeAllListeners('request');
		page.on('request', r => { const c = classify(r.url()); if (c) hits.push({ ...c, url: r.url().slice(0, 160), phase: 'after' }); });
		await page.waitForTimeout(WAIT);
		// Segunda página para comprobar que la elección persiste.
		await page.reload({ waitUntil: 'domcontentloaded' }).catch(() => {});
		await page.waitForTimeout(WAIT / 2);
	}
	const cookies = (await ctx.cookies()).map(c => ({ name: c.name, domain: c.domain, tracking: TRACKING_COOKIES.test(c.name), expires: c.expires > 0 ? new Date(c.expires * 1000).toISOString().slice(0, 10) : 'sesión' }));
	const consentAfter = await page.evaluate(() => (window.dataLayer || []).filter(e => e && e[0] === 'consent').map(e => ({ cmd: e[1], state: e[2] }))).catch(() => []);
	await ctx.close();
	return { mode, status, clicked, banner, consentAfter, cookies, requests: hits };
}

function summarize(r) {
	const trackers = phase => [...new Set(r.requests.filter(h => h.type === 'tracker' && (!phase || h.phase === phase)).map(h => h.name))];
	const tcookies = [...new Set(r.cookies.filter(c => c.tracking).map(c => c.name))];
	return { trackers: trackers(), trackersAfterClick: trackers('after'), trackingCookies: tcookies };
}

(async () => {
	const pw = loadPlaywright();
	const exe = process.env.CHROMIUM_PATH || (fs.existsSync('/opt/pw-browsers/chromium') ? '/opt/pw-browsers/chromium' : undefined);
	const browser = await pw.chromium.launch(exe ? { executablePath: exe } : {});
	const results = {};
	for (const mode of ['none', 'accept', 'deny', 'gpc']) {
		try { results[mode] = await run(browser, mode); } catch (e) { results[mode] = { mode, error: String(e).split('\n')[0] }; }
		await new Promise(r => setTimeout(r, 8000)); // pausa entre sesiones para no gatillar límites de peticiones
	}
	await browser.close();

	const findings = [];
	const none = results.none.requests ? summarize(results.none) : null;
	const deny = results.deny.requests ? summarize(results.deny) : null;
	const acc = results.accept.requests ? summarize(results.accept) : null;
	const gpc = results.gpc.requests ? summarize(results.gpc) : null;
	if (none && (none.trackers.length || none.trackingCookies.length)) findings.push({ severity: 'CRÍTICA', text: 'Seguimiento antes del consentimiento', trackers: none.trackers, cookies: none.trackingCookies });
	if (deny && (deny.trackersAfterClick.length || deny.trackingCookies.length)) findings.push({ severity: 'CRÍTICA', text: 'Seguimiento después de rechazar', trackers: deny.trackersAfterClick, cookies: deny.trackingCookies });
	if (results.none.banner && !results.none.banner.acceptButton) findings.push({ severity: 'ALTA', text: 'No se detectó un botón de aceptar visible (¿no hay banner o usa otro plugin?)' });
	if (results.none.banner && results.none.banner.acceptButton && !results.none.banner.denyButton) findings.push({ severity: 'MEDIA', text: 'No hay botón de rechazar visible en la primera capa' });
	const cm = (results.accept.banner && results.accept.banner.consentMode && results.accept.banner.consentMode.length ? results.accept.banner.consentMode : (results.none.banner ? results.none.banner.consentMode : [])) || [];
	const def = cm.find(c => c.cmd === 'default');
	if (!def) findings.push({ severity: 'MEDIA', text: 'No se detectó gtag("consent","default") en el dataLayer (Consent Mode v2). Puede cargarse de otra forma; verificar con Tag Assistant.' });
	else {
		const need = ['ad_storage', 'analytics_storage', 'ad_user_data', 'ad_personalization'];
		const missing = need.filter(k => !(k in (def.state || {})));
		if (missing.length) findings.push({ severity: 'MEDIA', text: 'Consent Mode default sin: ' + missing.join(', ') });
		const granted = need.filter(k => def.state && def.state[k] === 'granted');
		if (granted.length) findings.push({ severity: 'ALTA', text: 'Consent Mode default en granted antes de elegir: ' + granted.join(', ') + ' (revisar si la región lo permite)' });
	}
	if (acc && !acc.trackersAfterClick.length && !acc.trackers.length) findings.push({ severity: 'INFO', text: 'Tras aceptar no se detectaron etiquetas de seguimiento (¿GTM sin etiquetas, bloqueadas o dominios no listados?)' });
	if (gpc && none && gpc.trackers.length > none.trackers.length) findings.push({ severity: 'INFO', text: 'Con GPC activo se cargó más seguimiento que sin él' });
	const anyCookieless = ['none', 'deny', 'gpc'].some(m => results[m].requests && results[m].requests.some(h => h.type === 'cookieless'));
	if (anyCookieless) findings.push({ severity: 'INFO', text: 'Google recibe pings sin cookies antes del consentimiento o tras rechazar (Consent Mode avanzado). Es válido si la política de cookies lo explica.' });

	const report = { url, date: new Date().toISOString(), findings, summary: { none, accept: acc, deny, gpc }, results };
	if (outFile) fs.writeFileSync(outFile, JSON.stringify(report, null, 2));

	console.log('# Escaneo de consentimiento: ' + url);
	for (const m of ['none', 'accept', 'deny', 'gpc']) {
		const r = results[m];
		if (r.error) { console.log(`\n## ${m}: error ${r.error}`); continue; }
		const s = summarize(r);
		console.log(`\n## ${ { none: 'Sin interactuar', accept: 'Tras aceptar', deny: 'Tras rechazar', gpc: 'Con GPC, sin interactuar' }[m] } (HTTP ${r.status})${r.clicked ? ' (clic: ' + r.clicked + ')' : (m === 'accept' || m === 'deny' ? ' (sin clic: no se encontró el botón)' : '')}`);
		console.log('- Seguimiento detectado: ' + (s.trackers.join(', ') || 'ninguno'));
		console.log('- Cookies de seguimiento: ' + (s.trackingCookies.join(', ') || 'ninguna'));
		console.log('- Contenedores: ' + ([...new Set(r.requests.filter(h => h.type === 'container').map(h => h.name))].join(', ') || 'ninguno'));
		const cl = [...new Set(r.requests.filter(h => h.type === 'cookieless').map(h => h.name))];
		if (cl.length) console.log('- Pings sin cookies (Consent Mode avanzado): ' + cl.join(', '));
		const upd = (r.consentAfter || []).filter(c => c.cmd === 'update').pop();
		if (upd) console.log('- Consent Mode update: ' + JSON.stringify(upd.state));
	}
	const b = results.none.banner || {};
	console.log('\n## Banner');
	console.log('- Aceptar: ' + JSON.stringify(b.acceptButton));
	console.log('- Rechazar: ' + JSON.stringify(b.denyButton));
	console.log('- Consent Mode default: ' + JSON.stringify((b.consentMode || []).find(c => c.cmd === 'default') || null));
	console.log('\n## Hallazgos');
	if (!findings.length) console.log('- Sin hallazgos automáticos. Revisa igual las políticas con los checklists.');
	findings.forEach(f => console.log(`- [${f.severity}] ${f.text}${f.trackers && f.trackers.length ? ': ' + f.trackers.join(', ') : ''}${f.cookies && f.cookies.length ? ' | cookies: ' + f.cookies.join(', ') : ''}`));
})();
