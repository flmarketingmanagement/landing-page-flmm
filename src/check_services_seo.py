"""Valida services_seo: largos, palabra clave y reglas de estilo. Uso: python3 check_services_seo.py"""
import re, json, sys, os
os.chdir(os.path.dirname(os.path.abspath(__file__)))
from services_seo import SEO
from services_data import S
from services_extra import X
W = lambda s: len(re.sub('<[^>]+>', '', s).split())
low = lambda s: re.sub('<[^>]+>', '', s).replace('&amp;', '&').lower()
ok = True
for s in S:
    slug = s['slug']
    o = SEO.get(slug)
    if not o:
        print(slug, 'SIN DATOS'); ok = False; continue
    x = X[slug]
    for i, lang in enumerate(('es', 'en')):
        kw = o['kw'][i].lower()
        probs = []
        title = o['title'][i]; desc = o['desc'][i]; lead = o['lead'][i]
        if len(title.replace('&amp;', '&')) > 60: probs.append('title %d' % len(title))
        if kw not in low(title): probs.append('kw no está en title')
        if not low(title).startswith(kw): probs.append('title no empieza con kw')
        if not 140 <= len(desc) <= 160: probs.append('desc %d' % len(desc))
        if kw not in low(desc): probs.append('kw no está en desc')
        if not 40 <= W(lead) <= 60: probs.append('lead %d' % W(lead))
        if kw not in ' '.join(low(lead).split()[:25]): probs.append('kw no está al inicio del lead')
        if kw not in low(o['h1'][i]): probs.append('kw no está en h1')
        heads = [o['h_inc'][i], o['h_faq'][i], o['guide']['h2'][i]]
        if sum(kw in low(h) for h in heads) < 2: probs.append('kw en menos de 2 H2')
        g = o['guide']
        gtext = ' '.join([g['intro'][i]] + [it[2 + i] for it in g['items']])
        gw = W(gtext)
        if not 260 <= gw <= 380: probs.append('guía %d palabras' % gw)
        n = low(gtext).count(kw)
        if not 1 <= n <= 3: probs.append('kw %d veces en guía' % n)
        if len(g['items']) < 3: probs.append('guía con menos de 3 H3')
        blob = json.dumps(o, ensure_ascii=False)
        if '—' in blob or '–' in blob: probs.append('raya')
        if re.search(r'\b(podés|tenés|querés|sabés|hacé|revisá|mirá|fijate)\b', blob): probs.append('voseo')
        if not (o.get('ref') or x.get('ref')): probs.append('sin enlace externo')
        print(slug, lang, 'OK' if not probs else probs)
        ok = ok and not probs
sys.exit(0 if ok else 1)
