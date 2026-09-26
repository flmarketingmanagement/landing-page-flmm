<?php
/**
 * Consent Mode v2 con Complianz + GTM4WP.
 *
 * La plantilla "Complianz" de GTM (Consent Initialization) llama a window.addConsentUpdateListener
 * y window.addRevokeListener. Complianz solo las publica cuando él mismo inserta GTM; aquí GTM lo
 * carga GTM4WP, así que el theme las publica en el <head>, antes del contenedor. Mismo comportamiento
 * que templates/statistics/google-tag-manager-consent-mode-template.js de Complianz.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Imprime los listeners de consentimiento antes de GTM.
 */
function flmm_consent_listeners() {
	if ( ! defined( 'CMPLZ_VERSION' ) || ! apply_filters( 'flmm_consent_listeners', true ) ) {
		return;
	}
	?>
<script id="flmm-consent-listeners">
(function(w,d){
	if (w.addConsentUpdateListener) { return; }
	var consentListeners = [], revokeListeners = [];
	function setCookie(name, value) {
		if (typeof w.cmplz_set_cookie === 'function') { w.cmplz_set_cookie(name, value, false); return; }
		d.cookie = 'cmplz_' + name.replace(/^cmplz_/, '') + '=' + value + ';path=/;max-age=' + (365 * 86400) + ';SameSite=Lax';
	}
	function has(list, item) { return Array.isArray(list) && list.indexOf(item) !== -1; }
	w.addConsentUpdateListener = function (cb) { consentListeners.push(cb); };
	w.addRevokeListener = function (cb) { revokeListeners.push(cb); };
	d.addEventListener('cmplz_fire_categories', function (e) {
		var c = (e.detail && e.detail.categories) || [];
		var consent = {
			security_storage: 'granted',
			functionality_storage: 'granted',
			personalization_storage: has(c, 'preferences') ? 'granted' : 'denied',
			analytics_storage: has(c, 'statistics') ? 'granted' : 'denied',
			ad_storage: has(c, 'marketing') ? 'granted' : 'denied',
			ad_user_data: has(c, 'marketing') ? 'granted' : 'denied',
			ad_personalization: has(c, 'marketing') ? 'granted' : 'denied'
		};
		var granted = [];
		for (var k in consent) { if (consent[k] === 'granted') { granted.push(k); } }
		setCookie('cmplz_consent_mode', granted.join(','));
		consentListeners.forEach(function (cb) { cb(consent); });
	});
	d.addEventListener('cmplz_revoke', function () {
		setCookie('cmplz_consent_mode', 'revoked');
		revokeListeners.forEach(function (cb) { cb(); });
	});
})(window, document);
</script>
	<?php
}
add_action( 'wp_head', 'flmm_consent_listeners', 0 );
