import os
os.chdir(os.path.dirname(os.path.abspath(__file__)))
import re, json, sys, html
sys.path.insert(0,'.')
from services_data import S
from services_extra import X, TESTI
for _s in S:
    _x=X[_s['slug']]; _s['desc']=_x['desc']; _s['faq']=_x['faq']; _s.update({k:_x[k] for k in ('cmp','defs','testi','team','ref')})
UPDATED=('Actualizado: septiembre de 2026','Updated: September 2026'); DATE='2026-09-25'
BASE='https://flmarketingmanagement.com'
tpl=open('flmm.tpl.html').read()
CSS=re.search(r'<style>(.*?)</style>',tpl,re.S).group(1)
EXTRA='''
/* Páginas de servicio */
.crumbs{font:400 12px var(--mono);color:var(--muted);display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:2rem}
.crumbs a:hover{color:var(--ink)}
.sv-hero{padding:clamp(3.5rem,8vw,6.5rem) 0 clamp(3rem,6vw,4.5rem)}
.sv-hero h1{font-size:clamp(2.4rem,5.6vw,4.8rem);max-width:16ch;margin-top:0}
.sv-lead{margin:2rem 0 0;max-width:62ch;font-size:clamp(1.05rem,1.5vw,1.25rem);color:var(--ink-2)}
.sv-hero .ctas{margin-top:2.25rem}
.sv-img{margin:0}
.sv-img img{display:block;width:100%;height:auto}
@media (prefers-color-scheme:dark){:root:not([data-theme="light"]) .sv-img{border-radius:18px;overflow:hidden}}
.sv-media{aspect-ratio:21/9;border:1px dashed var(--line);border-radius:18px;display:grid;place-items:center;color:var(--muted);font:400 12px var(--mono);background:var(--surface)}
.sv-media .sig{width:90px;color:var(--line)}
.inc{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem}
.inc div{padding:1.75rem;border:1px solid var(--line);border-radius:18px;background:var(--bg)}
.inc .n{font:400 12px var(--mono);color:var(--muted)}
.inc h3{font-size:1.3rem;margin:2rem 0 .5rem;letter-spacing:-.02em}
.inc p{margin:0;color:var(--ink-2);font-size:15px}
.who{list-style:none;margin:0;padding:0;border-top:1px solid var(--line)}
.who li{padding:1.4rem 0;border-bottom:1px solid var(--line);font-size:clamp(1.1rem,1.8vw,1.4rem);letter-spacing:-.01em;display:flex;gap:1.25rem}
.who li::before{content:"";flex:none;width:8px;height:8px;margin-top:.6em;border-radius:50%;background:var(--pink)}
.faq{border-top:1px solid var(--line)}
.faq details{border-bottom:1px solid var(--line)}
.faq summary{list-style:none;cursor:pointer;padding:1.5rem 0;display:flex;justify-content:space-between;gap:1.5rem;font-size:clamp(1.1rem,1.8vw,1.35rem);font-weight:500;letter-spacing:-.015em}
.faq summary::-webkit-details-marker{display:none}
.faq summary::after{content:"+";font:400 22px/1 var(--mono);color:var(--muted);transition:transform .25s}
.faq details[open] summary::after{transform:rotate(45deg);color:var(--pink)}
.faq details p{margin:0 0 1.6rem;color:var(--ink-2);max-width:70ch}
.rel{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.rel a{padding:1.75rem;border:1px solid var(--line);border-radius:18px;display:flex;justify-content:space-between;align-items:center;gap:1rem;font-weight:500;font-size:1.15rem;letter-spacing:-.015em;transition:border-color .25s}
.rel a:hover{border-color:var(--ink)}
.rel .arr{transition:transform .25s}.rel a:hover .arr{transform:translateX(3px)}
.sec-head>div:only-child{grid-column:1/-1}
.sec-head>div:only-child h2{max-width:24ch}
.sec-head h2{font-size:clamp(2rem,3.8vw,3.1rem)}
.updated{margin:1.25rem 0 0;font:400 12px var(--mono);color:var(--muted)}
.tbl-wrap{overflow-x:auto;border:1px solid var(--line);border-radius:18px;background:var(--bg)}
.cmp{width:100%;border-collapse:collapse;min-width:620px;font-size:15px}
.cmp th,.cmp td{text-align:left;padding:1.1rem 1.5rem;border-bottom:1px solid var(--line);vertical-align:top}
.cmp tr:last-child th,.cmp tr:last-child td{border-bottom:0}
.cmp thead th{font:500 12px var(--mono);text-transform:uppercase;letter-spacing:.06em;color:var(--muted)}
.cmp tbody th{font-weight:500;width:22%}
.cmp td{color:var(--ink-2)}
.defs{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin:0}
.defs div{padding:1.5rem 1.75rem;border:1px solid var(--line);border-radius:18px}
.defs dt{font-weight:500;font-size:1.15rem;letter-spacing:-.015em;margin-bottom:.5rem}
.defs dd{margin:0;color:var(--ink-2);font-size:15px}
.ref{margin:1.5rem 0 0;font-size:14px;color:var(--muted)}
.ref a{text-decoration:underline;text-underline-offset:3px}
.ref a:hover{color:var(--pink)}
.proof{display:grid;grid-template-columns:1.4fr 1fr;gap:clamp(2rem,5vw,4rem);align-items:center}
.quote{margin:0}
.quote blockquote{margin:0;font-size:clamp(1.3rem,2.4vw,1.9rem);line-height:1.35;letter-spacing:-.02em;font-weight:400}
.quote figcaption{margin-top:1.5rem;display:grid;gap:.2rem;font-size:15px;color:var(--ink-2)}
.quote figcaption strong{color:var(--ink);font-weight:500}
.experts{padding:clamp(1.5rem,3vw,2rem);border:1px solid var(--line);border-radius:18px;background:var(--bg)}
.experts ul{list-style:none;margin:1.25rem 0;padding:0;display:grid;gap:.9rem}
.experts li{display:flex;align-items:center;gap:.8rem;font-weight:500}
.avatar.sm{width:36px;height:36px;margin:0;font-size:12px;flex:none}
.experts .more{font-size:14px;color:var(--ink-2)}
.experts .more:hover{color:var(--pink)}
@media (max-width:1000px){.inc{grid-template-columns:1fr 1fr}}
@media (max-width:860px){.proof,.defs{grid-template-columns:1fr}}
@media (max-width:760px){.inc,.rel{grid-template-columns:1fr}.sv-media{aspect-ratio:4/3}}
'''
SIG=open('sig.b64').read().strip()
SEAL=open('ama-seal.b64').read().strip()
AMAT=open('ama-text.b64').read().strip()
by={s['slug']:s for s in S}

import base64, os
IMG_ALT={
 'consulting':'Marketing strategy: chess pieces and a pink growth path toward a clear goal',
 'performance-marketing':'Performance marketing: scattered audiences focused into a single conversion',
 'seo-aeo':'SEO and AEO: content connected to search engines and AI answers',
 'aeo-content':'AEO content: an article feeding answers in ChatGPT, Claude, Google, Gemini and Perplexity',
 'design-branding':'Creative and multimedia content: design, typography, video and audio assets',
 'website':'E-commerce website: product page, mobile store, cart and checkout',
 'growth-hacking':'Growth hacking: a rocket rising over growth metrics and experiments',
 'analytics':'Marketing analytics: scattered data turned into a clear trend through a lens',
 'ai-assistants':'AI assistant: scattered messages processed into organized, actionable outputs',
 'marketing-automation':'Marketing automation: tools like Google, Meta, Slack and Shopify connected through one hub',
}
def media(s):
    p=f"img_{s['slug']}.webp"
    if os.path.exists(p):
        b=base64.b64encode(open(p,'rb').read()).decode()
        alt=IMG_ALT.get(s['slug'],('',''))[1]
        return f'<figure class="sv-img rv"><img src="data:image/webp;base64,{b}" alt="{alt}" width="1600" height="837" loading="eager" decoding="async"></figure>'
    name=re.sub('<[^>]+>','',html.unescape(s['name'][1]))
    return f'<figure class="sv-media rv" style="margin:0" aria-label="{name}"><span class="sig"></span><!-- TODO: imagen del servicio (alt: {name}) --></figure>'
def page(s):
    d={}; c=[0]
    def T(es,en,tag='span',attrs=''):
        c[0]+=1; k=f'k{c[0]}'; d[k]={'es':es,'en':en}
        return f'<{tag}{attrs} data-i18n="{k}">{en}</{tag}>'
    url=f'{BASE}/{s["slug"]}/'
    strip=lambda x: re.sub('<[^>]+>','',html.unescape(x))
    inc=''.join(f'<div class="rv"><span class="n">0{i+1}</span>{T(a,b,"h3")}{T(c_,d_,"p")}</div>' for i,(a,b,c_,d_) in enumerate(s['inc']))
    who=''.join(T(a,b,'li',' class="rv"') for a,b in s['who'])
    faq=''.join(f'<details class="rv"{" open" if i==0 else ""}><summary>{T(q,qe)}</summary>{T(a,ae,"p")}</details>' for i,(q,qe,a,ae) in enumerate(s['faq']))
    KEEP=('performance-marketing','seo-aeo','growth-hacking')
    def nm(i,cap=False):
        v=strip(s['name'][i])
        if cap: return v[0].upper()+v[1:]
        if s['slug'] in KEEP or v[:2].isupper(): return v
        return v[0].lower()+v[1:]
    cmp=''.join(f'<tr><th scope="row">{T(a,b)}</th><td>{T(c_,d_)}</td><td>{T(e,f)}</td></tr>' for a,b,c_,d_,e,f in s['cmp'][3])
    defs=''.join(f'<div class="rv"><dt>{T(a,b)}</dt><dd>{T(c_,d_)}</dd></div>' for a,b,c_,d_ in s['defs'])
    ref=(f'<p class="ref rv">{T("Referencia:","Reference:")} <a href="{s["ref"][1]}" target="_blank" rel="noopener">{T(s["ref"][0][0],s["ref"][0][1])}</a></p>') if s['ref'] else ''
    INI={'María Silvia Andueza':'MA','Luz Muñoz Pereira':'LP','Felipe Ríos Barraza':'FR'}
    ini=lambda n: INI.get(n,''.join(p[0] for p in n.split()[:2]))
    experts=''.join(f'<li><span class="avatar sm">{ini(n)}</span>{n}</li>' for n in s['team'])
    rel=''.join(f'<a class="rv" href="{BASE}/{r}/">{T(by[r]["name"][0],by[r]["name"][1])}<span class="arr">→</span></a>' for r in s['rel'])
    body=f'''<header id="top">
  <div class="wrap nav">
    <a class="brand" href="{BASE}/" aria-label="FL Marketing Management">
      <span class="mark"><span class="sig"></span></span>
      <span class="txt">FL Marketing Management<small>Boutique digital agency</small></span>
    </a>
    <nav class="links">
      {T('Servicios','Services','a',f' href="{BASE}/#services"')}
      {T('Plataformas','Platforms','a',f' href="{BASE}/#platforms"')}
      {T('Equipo','Team','a',f' href="{BASE}/#team"')}
      <a href="{BASE}/blog/">Blog</a>
      {T('Contacto','Contact','a',' href="#contact"')}
    </nav>
    <div class="nav-end">
      <div class="lang" role="group" aria-label="Language"><button type="button" data-lang="en" aria-pressed="true">EN</button><button type="button" data-lang="es" aria-pressed="false">ES</button></div>
      <a class="btn btn-primary" href="#contact">{T('Hablemos','Let’s talk')} <span class="arr">→</span></a>
    </div>
  </div>
</header>

<main>
  <div class="sv-hero"><div class="wrap">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{BASE}/">{T('Inicio','Home')}</a><span>/</span><a href="{BASE}/#services">{T('Servicios','Services')}</a><span>/</span>{T(s['name'][0],s['name'][1])}</nav>
    {T(s['h1'][0],s['h1'][1],'h1',' class="rv"')}
    {T(s['lead'][0],s['lead'][1],'p',' class="sv-lead rv"')}
    <p class="updated rv"><time datetime="{DATE}">{T(UPDATED[0],UPDATED[1])}</time></p>
    <div class="ctas rv">
      <a class="btn btn-primary" href="#contact">{T('Escríbenos','Get in touch')} <span class="arr">→</span></a>
      <a class="btn btn-ghost" href="#faq">{T('Preguntas frecuentes','FAQ')}</a>
    </div>
  </div></div>

  <div class="wrap">{media(s)}</div>

  <section>
    <div class="wrap">
      <div class="sec-head rv">
        <div>{T('Qué incluye','What’s included','span',' class="label"')}{T(f'¿Qué incluye nuestro servicio de {nm(0)}?',f'What’s included in our {nm(1)} service?','h2')}</div>
      </div>
      <div class="inc">{inc}</div>
    </div>
  </section>

  <section class="method">
    <div class="wrap">
      <div class="sec-head rv"><div>{T('Comparativa','Comparison','span',' class="label"')}{T(s['cmp'][0][0],s['cmp'][0][1],'h2')}</div></div>
      <div class="tbl-wrap rv"><table class="cmp">
        <thead><tr><th scope="col"></th><th scope="col">{T(s['cmp'][1][0],s['cmp'][1][1])}</th><th scope="col">{T(s['cmp'][2][0],s['cmp'][2][1])}</th></tr></thead>
        <tbody>{cmp}</tbody>
      </table></div>
    </div>
  </section>

  <section>
    <div class="wrap">
      <div class="sec-head rv"><div>{T('Conceptos clave','Key concepts','span',' class="label"')}{T(f'Conceptos clave de {nm(0)}',f'{nm(1,True)}: key concepts','h2')}</div></div>
      <dl class="defs">{defs}</dl>
      {ref}
    </div>
  </section>

  <section class="method">
    <div class="wrap">
      <div class="sec-head rv"><div>{T('Método','Method','span',' class="label"')}{T(f'¿Cómo funciona nuestro proceso de {nm(0)}?',f'How does our {nm(1)} process work?','h2')}</div>
      {T('Un proceso claro para que siempre sepas qué se hace, por qué y con qué resultado.','A clear process so you always know what we do, why, and what it delivers.','p')}</div>
      <div class="steps rv">
        <div class="step"><span class="n">01</span>{T('Diagnóstico','Diagnosis','h3')}{T('Auditamos cuentas, tracking y datos para entender dónde está el dinero y dónde se pierde.','We audit accounts, tracking and data to find where the money is and where it leaks.','p')}</div>
        <div class="step"><span class="n">02</span>{T('Estrategia','Strategy','h3')}{T('Definimos objetivos, canales y presupuesto con metas concretas por etapa.','We set goals, channels and budget with clear targets for each stage.','p')}</div>
        <div class="step"><span class="n">03</span>{T('Ejecución','Execution','h3')}{T('Implementamos, medimos y optimizamos cada semana con reportes claros.','We implement, measure and optimize every week with clear reports.','p')}</div>
      </div>
    </div>
  </section>

  <section>
    <div class="wrap">
      <div class="sec-head rv"><div>{T('Para quién','Who it’s for','span',' class="label"')}{T(f'¿Para quién es el servicio de {nm(0)}?',f'Who is our {nm(1)} service for?','h2')}</div></div>
      <ul class="who">{who}</ul>
    </div>
  </section>

  <section class="method">
    <div class="wrap">
      <div class="sec-head rv"><div>{T('Por qué nosotros','Why us','span',' class="label"')}{T(f'¿Por qué elegirnos para {nm(0)}?',f'Why choose us for {nm(1)}?','h2')}</div></div>
      <div class="proof">
        <figure class="quote rv">
          {T(TESTI[s['testi']][0],TESTI[s['testi']][1],'blockquote')}
          <figcaption><strong>{TESTI[s['testi']][2]}</strong>{T(TESTI[s['testi']][3][0],TESTI[s['testi']][3][1])}</figcaption>
        </figure>
        <div class="experts rv">
          {T('Especialistas en este servicio','Specialists in this service','p',' class="label"')}
          <ul>{experts}</ul>
          {T('Ver todo el equipo →','See the full team →','a',f' class="more" href="{BASE}/#team"')}
        </div>
      </div>
    </div>
  </section>

  <section id="faq">
    <div class="wrap">
      <div class="sec-head rv"><div>{T('FAQ','FAQ','span',' class="label"')}{T(f'Preguntas frecuentes sobre {nm(0)}',f'{nm(1,True)} FAQ','h2')}</div></div>
      <div class="faq">{faq}</div>
    </div>
  </section>

  <section class="method">
    <div class="wrap">
      <div class="sec-head rv"><div>{T('Relacionados','Related','span',' class="label"')}{T('Otros servicios','Other services','h2')}</div></div>
      <div class="rel">{rel}</div>
    </div>
  </section>
</main>
'''
    # contacto + footer (copiado del home, con claves propias)
    body+=f'''
<div class="dark" id="contact">
  <div class="cta"><div class="wrap"><div class="contact">
    <div class="contact-main">
      <span class="label rv">{T('Contacto','Contact')}</span>
      {T('¿Listo para crecer? <span>Conversemos.</span>','Ready to grow? <span>Let’s talk.</span>','h2',' class="rv"')}
      <form class="form rv" id="form" novalidate>
        <label>{T('Nombre','Name')}<input name="nombre" autocomplete="name" required></label>
        <label><span>Email</span><input name="email" type="email" autocomplete="email" required></label>
        <label class="full">{T('¿En qué te ayudamos?','How can we help?')}<textarea name="mensaje" rows="2" required></textarea></label>
        <div class="full actions">
          <button class="btn btn-primary" type="submit">{T('Enviar','Send')} <span class="arr">→</span></button>
          {T('o escríbeme por','or reach me by','span',' class="or"')}
          <a class="btn btn-ghost" href="mailto:hello@flmarketingmanagement.com">Mail</a>
          <a class="btn btn-ghost" href="https://t.me/aleloveeee" target="_blank" rel="noopener">Telegram</a>
        </div>
        <p class="form-msg full" role="status" aria-live="polite"></p>
      </form>
    </div>
    <span class="sig bigsig" aria-hidden="true"></span>
  </div></div></div>
  <footer><div class="wrap">
    <div class="foot">
      <div><a class="brand" href="{BASE}/"><span class="mark"><span class="sig"></span></span><span>FL Marketing Management<small>Boutique digital agency</small></span></a></div>
      <div>{T('Navegación','Navigation','p',' class="fh"')}{T('Servicios','Services','a',f' href="{BASE}/#services"')}{T('Plataformas','Platforms','a',f' href="{BASE}/#platforms"')}{T('Equipo','Team','a',f' href="{BASE}/#team"')}<a href="{BASE}/blog/">Blog</a></div>
      <div>{T('Empresa','Company','p',' class="fh"')}{T('Sobre nosotros','About us','a',f' href="{BASE}/about/"')}<a href="{BASE}/digitales-sin-fronteras/">Digitales Sin Fronteras</a><a href="{BASE}/podcast/">Marketing Today Podcast</a>{T('Política de privacidad','Privacy Policy','a',f' href="{BASE}/privacy-policy/"')}</div>
      <div>{T('Contacto','Contact','p',' class="fh"')}{T('Envíame un mail','Send me an email','a',' href="mailto:hello@flmarketingmanagement.com"')}<a href="https://t.me/aleloveeee" target="_blank" rel="noopener">Telegram</a></div>
      <div>{T('Redes','Social','p',' class="fh"')}<a href="https://www.linkedin.com/company/fl-marketing-management/" target="_blank" rel="noopener">LinkedIn</a><a href="https://www.instagram.com/flmarketingmanagement/" target="_blank" rel="noopener">Instagram</a></div>
    </div>
    <div class="legal"><span>© 2026 FL Marketing Management, LLC</span>
      <a class="ama" href="https://www.ama.org/pcm-professional-certified-marketer/" target="_blank" rel="noopener" aria-label="AMA Professional Certified Marketer, Marketing Management"><img class="ama-seal" src="data:image/png;base64,{SEAL}" alt=""><img class="ama-text" src="data:image/png;base64,{AMAT}" alt="AMA PCM, Marketing Management"></a>
    </div>
  </div></footer>
</div>
'''
    ld={"@context":"https://schema.org","@graph":[
      {"@type":"Service","@id":url+"#service","name":strip(s['name'][1]),"serviceType":strip(s['name'][1]),"url":url,"description":strip(s['lead'][1]),
       "provider":{"@id":BASE+"/#org"},"areaServed":["US","CL","CO","MX","CR"],"availableLanguage":["en","es"]},
      {"@type":"WebPage","@id":url,"url":url,"name":strip(s['title'][1]),"inLanguage":["en","es"],"isPartOf":{"@id":BASE+"/#website"},"datePublished":DATE,"dateModified":DATE,"about":{"@id":url+"#service"},"breadcrumb":{"@id":url+"#breadcrumb"}},
      {"@type":"BreadcrumbList","@id":url+"#breadcrumb","itemListElement":[
        {"@type":"ListItem","position":1,"name":"Home","item":BASE+"/"},
        {"@type":"ListItem","position":2,"name":"Services","item":BASE+"/#services"},
        {"@type":"ListItem","position":3,"name":strip(s['name'][1]),"item":url}]},
      {"@type":"FAQPage","@id":url+"#faq","mainEntity":[{"@type":"Question","name":q,"acceptedAnswer":{"@type":"Answer","text":a}} for (_,q,_,a) in s['faq']]},
      {"@type":["Organization","ProfessionalService"],"@id":BASE+"/#org","name":"FL Marketing Management","url":BASE+"/","email":"hello@flmarketingmanagement.com",
       "sameAs":["https://www.linkedin.com/company/fl-marketing-management/","https://www.instagram.com/flmarketingmanagement/"]}]}
    js=open('page.js').read().replace('__I18N__',json.dumps(d,ensure_ascii=False)).replace('__META__',json.dumps({'es':{'t':strip(s['title'][0]),'d':s['desc'][0]},'en':{'t':strip(s['title'][1]),'d':s['desc'][1]}},ensure_ascii=False))
    head=f'''<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{s['title'][1]}</title>
<meta name="description" content="{s['desc'][1]}">
<link rel="canonical" href="{url}">
<link rel="alternate" hreflang="en" href="{url}">
<link rel="alternate" hreflang="es" href="{BASE}/es/{s['slug']}/">
<link rel="alternate" hreflang="x-default" href="{url}">
<meta name="robots" content="index,follow">
<meta property="og:type" content="website">
<meta property="og:locale" content="en_US">
<meta property="og:locale:alternate" content="es_LA">
<meta property="og:site_name" content="FL Marketing Management">
<meta property="og:url" content="{url}">
<meta property="og:title" content="{s['title'][1]}">
<meta property="og:description" content="{s['desc'][1]}">
<meta property="og:image" content="https://i0.wp.com/flmarketingmanagement.com/wp-content/uploads/2026/01/Image-Social.png">
<meta name="twitter:card" content="summary_large_image">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@300..700&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">
<script type="application/ld+json">{json.dumps(ld,ensure_ascii=False)}</script>
<style>{CSS.replace('{{SIG}}',SIG)}{EXTRA}</style>
</head>
<body>
'''
    return head+body+'<script>'+js+'</script>\n</body>\n</html>\n'

import os
os.makedirs('../design',exist_ok=True)
for s in S:
    open(f'../design/{s["slug"]}.html','w').write(page(s))
print('ok')
