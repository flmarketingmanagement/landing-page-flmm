"""Exporta el contenido de src/ a JSON para el theme y el cargador de contenido de WordPress."""
import os, json, re, sys
os.chdir(os.path.dirname(os.path.abspath(__file__)))
sys.path.insert(0, '.')
from services_data import S
from services_extra import X, TESTI

def pair(t):
    return {'es': t[0], 'en': t[1]}

import html
KEEP = ('performance-marketing', 'seo-aeo', 'growth-hacking')
def nm(s, i, cap=False):
    v = re.sub('<[^>]+>', '', html.unescape(s['name'][i]))
    if cap: return v[0].upper() + v[1:]
    if s['slug'] in KEEP or v[:2].isupper(): return v
    return v[0].lower() + v[1:]

def headings(s):
    es, en = nm(s, 0), nm(s, 1)
    return {
        'inc': {'es': f'¿Qué incluye nuestro servicio de {es}?', 'en': f'What’s included in our {en} service?'},
        'defs': {'es': f'Conceptos clave de {es}', 'en': f'{nm(s, 1, True)}: key concepts'},
        'method': {'es': f'¿Cómo funciona nuestro proceso de {es}?', 'en': f'How does our {en} process work?'},
        'who': {'es': f'¿Para quién es el servicio de {es}?', 'en': f'Who is our {en} service for?'},
        'why': {'es': f'¿Por qué elegirnos para {es}?', 'en': f'Why choose us for {en}?'},
        'faq': {'es': f'Preguntas frecuentes sobre {es}', 'en': f'{nm(s, 1, True)} FAQ'},
    }

def cmp(c):
    out = {'title': pair(c[0]), 'col1': pair(c[1]), 'col2': pair(c[2]), 'rows': []}
    if len(c) > 4:
        out['col3'] = pair(c[4])
    for r in c[3]:
        row = {'label': {'es': r[0], 'en': r[1]}, 'c1': {'es': r[2], 'en': r[3]}, 'c2': {'es': r[4], 'en': r[5]}}
        if len(r) > 6:
            row['c3'] = {'es': r[6], 'en': r[7]}
        out['rows'].append(row)
    return out

services = []
for s in S:
    x = X[s['slug']]
    services.append({
        'slug': s['slug'], 'n': s.get('n', ''),
        'name': pair(s['name']), 'title': pair(s['title']), 'desc': pair(x['desc']),
        'h1': pair(s['h1']), 'lead': pair(s['lead']),
        'inc': [{'title': {'es': a, 'en': b}, 'text': {'es': c, 'en': d}} for a, b, c, d in s['inc']],
        'who': [pair(w) for w in s['who']],
        'faq': [{'q': {'es': a, 'en': b}, 'a': {'es': c, 'en': d}} for a, b, c, d in x['faq']],
        'cmp': cmp(x['cmp']),
        'defs': [{'term': {'es': a, 'en': b}, 'def': {'es': c, 'en': d}} for a, b, c, d in x['defs']],
        'ref': ({'label': pair(x['ref'][0]), 'url': x['ref'][1]} if x['ref'] else None),
        'headings': headings(s), 'testi': x['testi'], 'team': list(x['team']), 'rel': list(s['rel']),
    })
testimonials = {k: {'quote': {'es': v[0], 'en': v[1]}, 'name': v[2], 'role': {'es': v[3][0], 'en': v[3][1]}} for k, v in TESTI.items()}
json.dump({'services': services, 'testimonials': testimonials}, open('content.json', 'w'), ensure_ascii=False, indent=1)
print(len(services), 'servicios')

# Datos del home (desde el diccionario I18N del diseño) y muestra de servicio para los patrones del theme.
tpl = open('flmm.tpl.html').read()
I = json.loads(re.search(r'const I18N=(\{.*?\});\n', tpl, re.S).group(1))
t = lambda k: {'es': I[k]['es'], 'en': I[k]['en']}
same = lambda v: {'es': v, 'en': v}
home = {
    'hero': {'title': {'es': 'El marketing cambió.', 'en': 'Marketing changed.'}, 'soft': {'es': 'Nosotros también.', 'en': 'So did we.'},
             'cta1': {'es': 'Escríbenos', 'en': 'Get in touch'}, 'cta2': t('t8')},
    'services': {'label': t('t9'), 'title': t('t10'), 'text': t('t11'), 'items': [
        {'slug': 'consulting', 'name': t('t12'), 'text': t('t13'), 'tags': [t('t29'), t('t30')]},
        {'slug': 'performance-marketing', 'name': same('Performance Marketing'), 'text': t('t14'), 'tags': [same('Google'), same('Meta'), same('TikTok'), same('LinkedIn'), same('ChatGPT Ads'), {'es': 'y más', 'en': 'and more'}]},
        {'slug': 'seo-aeo', 'name': same('SEO + AEO'), 'text': t('t15'), 'tags': [t('t31'), t('t32')]},
        {'slug': 'aeo-content', 'name': t('t16'), 'text': t('t17'), 'tags': [same('ChatGPT'), same('Gemini'), same('Perplexity')]},
        {'slug': 'design-branding', 'name': t('t18'), 'text': t('t19'), 'tags': [t('t33'), same('Video'), t('t34')]},
        {'slug': 'website', 'name': t('t20'), 'text': t('t21'), 'tags': [same('Shopify'), same('Wix'), same('WordPress')]},
        {'slug': 'growth-hacking', 'name': same('Growth Hacking'), 'text': t('t22'), 'tags': [t('t35'), t('t36')]},
        {'slug': 'analytics', 'name': t('t23'), 'text': t('t24'), 'tags': [same('GA4'), same('GTM'), same('Dashboards')]},
        {'slug': 'ai-assistants', 'name': t('t25'), 'text': t('t26'), 'tags': [t('t37'), t('t38')]},
        {'slug': 'marketing-automation', 'name': t('t27'), 'text': t('t28'), 'tags': [same('CRM'), same('Email'), t('t39')]},
    ]},
    'platforms': {'label': t('t40'), 'title': t('t41'), 'text': t('t42'), 'items': [
        {'n': t('t43'), 'name': same('Zircca'), 'text': t('t44'), 'tags': [same('SaaS'), same('Web app')], 'url': 'https://zircca.com', 'link': same('zircca.com')},
        {'n': t('t45'), 'name': same('Guilietta'), 'text': t('t46'), 'tags': [same('Web'), same('iOS'), same('Android'), t('t47')], 'url': 'https://app.guilietta.com/', 'link': same('app.guilietta.com')},
        {'n': t('t48'), 'name': t('t49'), 'text': t('t50'), 'tags': [], 'url': '#contact', 'link': {'es': 'Hablemos', 'en': 'Let’s talk'}, 'dark': True},
    ]},
    'method': {'label': t('t52'), 'title': t('t53'), 'text': t('t54'), 'steps': [
        {'title': t('t55'), 'text': t('t56')}, {'title': t('t57'), 'text': t('t58')}, {'title': t('t59'), 'text': t('t60')}]},
    'team': {'label': t('t102'), 'title': t('t103'), 'text': t('t104')},
    'results': {'label': t('t61'), 'title': t('t62'), 'text': t('t63'), 'stats': [
        {'value': same('14+'), 'text': t('t64')}, {'value': same('17+'), 'text': t('t65')},
        {'value': same('Effie'), 'text': t('t66')}, {'value': same('PCM®'), 'text': same('Professional Certified Marketer, AMA')}]},
}
# Equipo: nombre, rol y tags desde el HTML del diseño.
team = []
for m in re.finditer(r'<article class="member">(.*?)</article>', tpl, re.S):
    b = m.group(1)
    name = re.search(r'<h3>(.*?)</h3>', b).group(1)
    role_k = re.search(r'class="role" data-i18n="(t\d+)"', b).group(1)
    tags = []
    for tm in re.finditer(r'<span class="tag"(?: data-i18n="(t\d+)")?>(.*?)</span>', b):
        tags.append(t(tm.group(1)) if tm.group(1) else same(tm.group(2)))
    team.append({'name': name, 'role': t(role_k), 'tags': tags})
home['team']['members'] = team
json.dump(home, open('home.json', 'w'), ensure_ascii=False, indent=1)
theme_data = os.path.join('..', 'theme', 'flmm-studio', 'inc', 'pattern-data')
os.makedirs(theme_data, exist_ok=True)
json.dump(home, open(os.path.join(theme_data, 'home.json'), 'w'), ensure_ascii=False, indent=1)
sample = dict(services[0]); sample['testimonial'] = testimonials.get(sample['testi'])
json.dump(sample, open(os.path.join(theme_data, 'service-sample.json'), 'w'), ensure_ascii=False, indent=1)
print(len(team), 'personas en el equipo')
