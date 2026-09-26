import os
os.chdir(os.path.dirname(os.path.abspath(__file__)))
import re, json, html, base64, math
BASE='https://flmarketingmanagement.com'
tpl=open('flmm.tpl.html').read()
CSS=re.search(r'<style>(.*?)</style>',tpl,re.S).group(1)
SIG=open('sig.b64').read().strip()
SEAL=open('ama-seal.b64').read().strip()
AMAT=open('ama-text.b64').read().strip()
M=json.load(open('blog/meta.json'))
b64=lambda p: base64.b64encode(open(p,'rb').read()).decode()
# pares EN/ES
POSTS=[
 dict(en='performance-marketing-metrics',es='metricas-de-performance-marketing-b2b-mas-alla-del-cpc',cat=('Performance','Performance'),
      t=('Métricas de performance marketing B2B más allá del CPC','B2B performance marketing metrics beyond CPC'),
      ex=('El CPC y el CTR ya no explican el performance B2B. La jerarquía de métricas 2026 que conecta la inversión con los ingresos.','CPC and CTR no longer explain B2B performance. The 2026 metric hierarchy that connects spend to revenue.')),
 dict(en='marketing-dictionary-ai-aeo-llm',es='terminos-marketing-ia-2026',cat=('AEO','AEO'),
      t=('Términos de marketing con IA 2026: AEO, LLM y SEO','AI marketing terms 2026: AEO, LLM &amp; SEO glossary'),
      ex=('Las definiciones que todo líder B2B necesita en 2026: AEO, LLM Share of Voice, entropía y atribución con IA.','The definitions every B2B leader needs in 2026: AEO, LLM share of voice, entropy and AI attribution.')),
 dict(en='ai-shannon-entropy',es='ia-y-entropia-de-shannon-en-performance-marketing-b2b',cat=('IA','AI'),
      t=('IA y entropía de Shannon en performance marketing B2B','AI &amp; Shannon entropy in B2B performance marketing'),
      ex=('Por qué más datos pueden dañar tu marketing B2B y cómo la IA reduce el ruido para decidir con señales claras.','Why more data can hurt B2B marketing, and how AI reduces noise so you can decide with clear signals.')),
]
MONTHS_ES=['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre']
MONTHS_EN=['January','February','March','April','May','June','July','August','September','October','November','December']
def fdate(iso,l):
    y,m,d=iso[:10].split('-'); m=int(m)-1
    return f'{int(d)} de {MONTHS_ES[m]} de {y}' if l=='es' else f'{MONTHS_EN[m]} {int(d)}, {y}'
def rt(slug): return max(1,round(M[slug]['words']/220))

EXTRA='''
.crumbs{font:400 12px var(--mono);color:var(--muted);display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.75rem}
.crumbs a:hover{color:var(--ink)}
.blog-hero{padding:clamp(3.5rem,8vw,6rem) 0 clamp(2rem,4vw,3rem)}
.blog-hero h1{font-size:clamp(2.8rem,6.5vw,5.6rem);margin-top:1.5rem}
.blog-hero p{max-width:56ch;margin:1.5rem 0 0;color:var(--ink-2);font-size:clamp(1.05rem,1.5vw,1.2rem)}
.chips{display:flex;gap:.5rem;flex-wrap:wrap;margin-top:2.25rem}
.chip{all:unset;cursor:pointer;height:38px;padding:0 1rem;border:1px solid var(--line);border-radius:999px;display:inline-flex;align-items:center;font-size:14px;color:var(--ink-2);transition:all .25s}
.chip:hover{border-color:var(--ink)}
.chip[aria-pressed="true"]{background:var(--ink);color:var(--bg);border-color:var(--ink)}
.chip:focus-visible{outline:2px solid var(--pink);outline-offset:2px}
.ph img,.post-hero img{filter:grayscale(1) contrast(1.05);transition:filter .5s ease,transform .6s ease}
.card:hover .ph img{filter:none;transform:scale(1.03)}
.post-hero:hover img{filter:none}
.featured{display:grid;grid-template-columns:1.25fr 1fr;gap:clamp(1.5rem,4vw,3.5rem);align-items:center;padding-bottom:clamp(3rem,6vw,4.5rem);border-bottom:1px solid var(--line)}
.featured .ph{border-radius:18px;overflow:hidden;aspect-ratio:16/10}
.featured h2{font-size:clamp(1.8rem,3.4vw,2.8rem);margin:.9rem 0 1rem}
.featured p{color:var(--ink-2);margin:0 0 1.5rem}
.meta{display:flex;gap:.9rem;flex-wrap:wrap;align-items:center;font:400 12px var(--mono);color:var(--muted);text-transform:uppercase;letter-spacing:.06em}
.meta .cat{color:var(--ink);display:inline-flex;align-items:center;gap:.45rem}
.meta .cat::before{content:"";width:6px;height:6px;border-radius:50%;background:var(--pink)}
.ph img{display:block;width:100%;height:100%;object-fit:cover}
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:clamp(1.25rem,2.5vw,2rem);padding-top:clamp(2.5rem,5vw,3.5rem)}
.card{display:flex;flex-direction:column;gap:.9rem}
.card .ph{border-radius:14px;overflow:hidden;aspect-ratio:16/10}
.card h3{font-size:1.35rem;letter-spacing:-.02em;line-height:1.2}
.card p{margin:0;color:var(--ink-2);font-size:15px}
.card:hover h3{color:var(--ink)}
.more-link{font-weight:500;font-size:15px;display:inline-flex;gap:.4rem}
.card .more-link{margin-top:auto}
.card[hidden],.featured[hidden]{display:none}
@media (max-width:900px){.featured{grid-template-columns:1fr}.grid{grid-template-columns:1fr 1fr}}
@media (max-width:600px){.grid{grid-template-columns:1fr}}
/* Artículo */
.post-head{padding:clamp(3rem,7vw,5rem) 0 2rem}
.post-head h1{font-size:clamp(2.3rem,5.2vw,4.4rem);max-width:20ch;margin:1.25rem 0 1.5rem}
.byline{display:flex;align-items:center;gap:.8rem;font-size:15px}
.byline strong{font-weight:500}
.byline small{display:block;color:var(--muted);font-size:13px}
.post-hero{margin:0 0 clamp(2.5rem,5vw,4rem);border-radius:18px;overflow:hidden;aspect-ratio:16/8}
.post-hero img{display:block;width:100%;height:100%;object-fit:cover}
.post-layout{display:grid;grid-template-columns:220px minmax(0,1fr) 220px;gap:clamp(2rem,4vw,4rem);align-items:start}
.toc{position:sticky;top:110px;font-size:14px}
.toc p{margin:0 0 1rem}
.toc ol{list-style:none;margin:0;padding:0;display:grid;gap:.65rem;border-left:1px solid var(--line)}
.toc a{display:block;padding-left:1rem;margin-left:-1px;border-left:1px solid transparent;color:var(--muted);line-height:1.4;transition:color .2s,border-color .2s}
.toc a:hover,.toc a.on{color:var(--ink);border-color:var(--pink)}
.side{position:sticky;top:110px;display:grid;gap:1rem}
.side .box{padding:1.25rem;border:1px solid var(--line);border-radius:14px;font-size:14px}
.side .box p{margin:.5rem 0 1rem;color:var(--ink-2)}
.side .btn{height:40px;font-size:14px}
.share{display:flex;gap:.5rem;flex-wrap:wrap}
.share a,.share button{all:unset;cursor:pointer;font:400 12px var(--mono);padding:.45rem .7rem;border:1px solid var(--line);border-radius:999px;color:var(--ink-2)}
.share a:hover,.share button:hover{border-color:var(--ink);color:var(--ink)}
.prose{font-size:18px;line-height:1.75;color:var(--ink-2);max-width:68ch}
.prose>*:first-child{margin-top:0}
.prose h2{font-size:clamp(1.6rem,2.6vw,2.1rem);color:var(--ink);margin:3rem 0 1rem;line-height:1.15;scroll-margin-top:100px}
.prose h3{font-size:1.3rem;color:var(--ink);margin:2rem 0 .6rem;letter-spacing:-.02em;line-height:1.25}
.prose p{margin:0 0 1.2rem}
.prose strong{color:var(--ink);font-weight:500}
.prose a{color:var(--ink);text-decoration:underline;text-decoration-color:var(--pink);text-underline-offset:3px}
.prose ul,.prose ol{padding-left:1.2rem;margin:0 0 1.4rem}
.prose li{margin:.35rem 0}
.prose li::marker{color:var(--pink)}
.prose table{width:100%;border-collapse:collapse;font-size:15px;margin:1.5rem 0;display:block;overflow-x:auto}
.prose th,.prose td{border-bottom:1px solid var(--line);padding:.7rem .9rem;text-align:left;vertical-align:top}
.prose th{font:500 12px var(--mono);text-transform:uppercase;letter-spacing:.05em;color:var(--muted)}
.prose .tldr{padding:1.5rem 1.75rem;border:1px solid var(--line);border-radius:14px;background:var(--surface);color:var(--ink);font-size:17px;margin-bottom:2.5rem}
.prose .tldr strong{font:500 12px var(--mono);text-transform:uppercase;letter-spacing:.08em;display:block;margin-bottom:.5rem;color:var(--muted)}
.prose .takeaways{padding:1.75rem 2rem;border-radius:14px;background:var(--dark);color:var(--on-dark);margin:3rem 0 2rem}
.prose .takeaways h2{color:var(--on-dark);margin-top:0;font-size:1.4rem}
.prose .takeaways li{color:var(--on-dark-2)}
.author{display:grid;grid-template-columns:64px 1fr;gap:1.25rem;padding:1.75rem;border:1px solid var(--line);border-radius:18px;margin-top:3rem;max-width:68ch}
.author .avatar{width:64px;height:64px;margin:0}
.author h3{font-size:1.15rem;margin:0}
.author .role{margin:.2rem 0 .75rem;font:400 12px var(--mono);color:var(--muted);text-transform:uppercase;letter-spacing:.06em}
.author p{margin:0;color:var(--ink-2);font-size:15px}
.author a{font-size:14px;font-weight:500;display:inline-block;margin-top:.75rem}
[data-l]{display:none}
html[lang="en"] [data-l="en"],html[lang="es"] [data-l="es"]{display:block}
html[lang="en"] span[data-l="en"],html[lang="es"] span[data-l="es"]{display:inline}
html[lang="en"] .card[data-l="en"],html[lang="es"] .card[data-l="es"]{display:flex}
html[lang="en"] .featured[data-l="en"],html[lang="es"] .featured[data-l="es"]{display:grid}
@media (max-width:1100px){.post-layout{grid-template-columns:200px minmax(0,1fr)}.side{display:none}}
@media (max-width:820px){.post-layout{grid-template-columns:1fr}.toc{position:static;padding:1.25rem;border:1px solid var(--line);border-radius:14px}.prose{font-size:17px}.post-hero{aspect-ratio:16/10}}
'''

def chrome(T, main, title, desc, canon, ld, alt, extra_js=''):
    head=f'''<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{title[1]}</title>
<meta name="description" content="{desc[1]}">
<link rel="canonical" href="{canon}">
{alt}
<meta name="robots" content="index,follow">
<meta property="og:site_name" content="FL Marketing Management">
<meta property="og:url" content="{canon}">
<meta property="og:title" content="{title[1]}">
<meta property="og:description" content="{desc[1]}">
<meta name="twitter:card" content="summary_large_image">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@300..700&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">
<script type="application/ld+json">{json.dumps(ld,ensure_ascii=False)}</script>
<style>{CSS.replace('{{SIG}}',SIG)}{EXTRA}</style>
</head>
<body>
<header id="top">
  <div class="wrap nav">
    <a class="brand" href="{BASE}/" aria-label="FL Marketing Management"><span class="mark"><span class="sig"></span></span><span class="txt">FL Marketing Management<small>Boutique digital agency</small></span></a>
    <nav class="links">
      {T('Servicios','Services','a',f' href="{BASE}/#services"')}
      {T('Plataformas','Platforms','a',f' href="{BASE}/#platforms"')}
      {T('Equipo','Team','a',f' href="{BASE}/#team"')}
      <a href="{BASE}/blog/" aria-current="page">Blog</a>
      {T('Contacto','Contact','a',' href="#contact"')}
    </nav>
    <div class="nav-end">
      <div class="lang" role="group" aria-label="Language"><button type="button" data-lang="en" aria-pressed="true">EN</button><button type="button" data-lang="es" aria-pressed="false">ES</button></div>
      <a class="btn btn-primary" href="#contact">{T('Hablemos','Let’s talk')} <span class="arr">→</span></a>
    </div>
  </div>
</header>
'''
    foot=f'''
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
    return head+main+foot

def run_js(d, meta, extra=''):
    js=open('page.js').read().replace('__I18N__',json.dumps(d,ensure_ascii=False)).replace('__META__',json.dumps(meta,ensure_ascii=False))
    js=js.replace("try{localStorage.setItem('flmm-lang',l)}catch(e){}}","try{localStorage.setItem('flmm-lang',l)}catch(e){};if(window.onLang)onLang(l)}")
    return '<script>'+extra+js+'</script>\n</body>\n</html>\n'

def mkT():
    d={}; c=[0]
    def T(es,en,tag='span',attrs=''):
        c[0]+=1; k=f'k{c[0]}'; d[k]={'es':es,'en':en}
        return f'<{tag}{attrs} data-i18n="{k}">{en}</{tag}>'
    return d,T

# ---------- ÍNDICE ----------
def index():
    d,T=mkT()
    def card(p,l,featured=False):
        slug=p[l]; li=0 if l=='es' else 1
        url=f'{BASE}/blog/{slug}/'
        img=b64(f'blog/{"hi" if featured else "th"}_{slug}.webp')
        date=fdate(M[slug]['pub'],l); rd=f'{rt(slug)} min'
        cat=p['cat'][li]
        more='Leer artículo' if l=='es' else 'Read article'
        if featured:
            return f'''<article class="featured rv" data-l="{l}" data-cat="{p['cat'][1]}">
        <a class="ph" href="{url}"><img src="data:image/webp;base64,{img}" alt="{html.escape(re.sub('<[^>]+>','',html.unescape(p['t'][li])))}" loading="eager"></a>
        <div><div class="meta"><span class="cat">{cat}</span><span>{date}</span><span>{rd}</span></div>
        <h2><a href="{url}">{p['t'][li]}</a></h2><p>{p['ex'][li]}</p>
        <a class="more-link" href="{url}">{more} <span class="arr">→</span></a></div>
      </article>'''
        return f'''<article class="card rv" data-l="{l}" data-cat="{p['cat'][1]}">
        <a class="ph" href="{url}"><img src="data:image/webp;base64,{img}" alt="{html.escape(re.sub('<[^>]+>','',html.unescape(p['t'][li])))}" loading="lazy"></a>
        <div class="meta"><span class="cat">{cat}</span><span>{date}</span></div>
        <h3><a href="{url}">{p['t'][li]}</a></h3><p>{p['ex'][li]}</p>
        <a class="more-link" href="{url}">{more} <span class="arr">→</span></a>
      </article>'''
    feat=''.join(card(POSTS[0],l,True) for l in ('en','es'))
    cards=''.join(card(p,l) for p in POSTS[1:] for l in ('en','es'))
    cats=['Performance','AEO','AI']
    chips=f'<button type="button" class="chip" data-f="all" aria-pressed="true">{T("Todos","All")}</button>'+''.join(f'<button type="button" class="chip" data-f="{c}" aria-pressed="false">{T("IA" if c=="AI" else c, c)}</button>' for c in cats)
    main=f'''<main>
  <div class="blog-hero"><div class="wrap">
    <span class="label rv">Blog</span>
    {T('Ideas para crecer con datos e IA.','Ideas for growing with data and AI.','h1',' class="rv"')}
    {T('Artículos sobre performance marketing, SEO, AEO e inteligencia artificial, escritos por nuestro equipo a partir de lo que vemos en cuentas reales.','Articles on performance marketing, SEO, AEO and artificial intelligence, written by our team from what we see in real accounts.','p',' class="rv"')}
    <div class="chips rv" role="group" aria-label="Filter">{chips}</div>
  </div></div>
  <section style="padding-top:0"><div class="wrap">
    {feat}
    <div class="grid">{cards}</div>
  </div></section>
</main>'''
    title=('Blog de marketing digital, SEO, AEO e IA | FL Marketing','Digital Marketing, SEO, AEO &amp; AI Blog | FL Marketing')
    desc=('Artículos sobre performance marketing, SEO, AEO e inteligencia artificial para marcas B2B y e-commerce, escritos por el equipo de FL Marketing Management.',
          'Articles on performance marketing, SEO, AEO and artificial intelligence for B2B and e-commerce brands, written by the FL Marketing Management team.')
    canon=f'{BASE}/blog/'
    ld={"@context":"https://schema.org","@graph":[
      {"@type":"Blog","@id":canon+"#blog","url":canon,"name":"FL Marketing Management Blog","inLanguage":["en","es"],"publisher":{"@id":BASE+"/#org"},
       "blogPost":[{"@type":"BlogPosting","headline":re.sub('<[^>]+>','',html.unescape(p['t'][1])),"url":f"{BASE}/blog/{p['en']}/","datePublished":M[p['en']]['pub'],"author":{"@id":BASE+"/#founder"}} for p in POSTS]},
      {"@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":BASE+"/"},{"@type":"ListItem","position":2,"name":"Blog","item":canon}]},
      {"@type":"Organization","@id":BASE+"/#org","name":"FL Marketing Management","url":BASE+"/"}]}
    extra='''
function applyFilter(f){document.querySelectorAll('.chip').forEach(c=>c.setAttribute('aria-pressed',c.dataset.f===f));
 document.querySelectorAll('.card,.featured').forEach(el=>{el.hidden=!(f==='all'||el.dataset.cat===f)})}
document.querySelectorAll('.chip').forEach(c=>c.addEventListener('click',()=>applyFilter(c.dataset.f)));
'''
    out=chrome(T,main,title,desc,canon,ld,f'<link rel="alternate" hreflang="en" href="{canon}">\n<link rel="alternate" hreflang="es" href="{BASE}/es/blog/">')+run_js(d,{'es':{'t':title[0],'d':desc[0]},'en':{'t':html.unescape(title[1]),'d':desc[1]}},extra)
    open('../design/blog.html','w').write(out)

# ---------- ARTÍCULO ----------
def slugify(t): 
    import unicodedata
    t=unicodedata.normalize('NFKD',t).encode('ascii','ignore').decode().lower()
    return re.sub(r'[^a-z0-9]+','-',t).strip('-')
def prep(h):
    h=re.sub(r'<p><strong>TL;DR:</strong>\s*(.*?)</p>',r'<div class="tldr"><strong>TL;DR</strong>\1</div>',h,1,flags=re.S)
    h=re.sub(r'(<h2>(Key takeaways|Puntos clave)</h2>\s*<ul>.*?</ul>)',r'<div class="takeaways">\1</div>',h,1,flags=re.S)
    def hid(m):
        t=re.sub('<[^>]+>','',m.group(1)); return f'<h2 id="{slugify(t)}">{m.group(1)}</h2>'
    h=re.sub(r'<h2>(.*?)</h2>',hid,h)
    return h
def post(p):
    d,T=mkT()
    en,es=p['en'],p['es']
    body_en=prep(open(f'blog/{en}.clean.html').read())
    body_es=prep(open(f'blog/{es}.clean.html').read())
    img=b64(f'blog/hi_{en}.webp')
    canon=f'{BASE}/blog/{en}/'
    rel=[q for q in POSTS if q is not p]
    relh=''.join(f'''<article class="card" data-l="{l}"><a class="ph" href="{BASE}/blog/{q[l]}/"><img src="data:image/webp;base64,{b64(f'blog/th_{q[l]}.webp')}" alt="{html.escape(re.sub('<[^>]+>','',html.unescape(q['t'][0 if l=='es' else 1])))}" loading="lazy"></a>
        <div class="meta"><span class="cat">{q['cat'][0 if l=='es' else 1]}</span><span>{fdate(M[q[l]]['pub'],l)}</span></div>
        <h3><a href="{BASE}/blog/{q[l]}/">{q['t'][0 if l=='es' else 1]}</a></h3></article>''' for q in rel for l in ('en','es'))
    main=f'''<main>
  <div class="post-head"><div class="wrap">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{BASE}/">{T('Inicio','Home')}</a><span>/</span><a href="{BASE}/blog/">Blog</a><span>/</span>{T(p['cat'][0],p['cat'][1])}</nav>
    <div class="meta rv"><span class="cat">{T(p['cat'][0],p['cat'][1])}</span><span data-l="en">{fdate(M[en]['pub'],'en')}</span><span data-l="es">{fdate(M[es]['pub'],'es')}</span><span>{T(f'{rt(es)} min de lectura',f'{rt(en)} min read')}</span></div>
    {T(p['t'][0],p['t'][1],'h1',' class="rv"')}
    <div class="byline rv"><span class="avatar sm">AL</span><div><strong>Alejandro Lovera</strong><small>{T('Fundador, FL Marketing Management · Actualizado el '+fdate(M[en]['mod'],'es'),'Founder, FL Marketing Management · Updated '+fdate(M[en]['mod'],'en'))}</small></div></div>
  </div></div>
  <div class="wrap">
    <figure class="post-hero rv"><img src="data:image/webp;base64,{img}" alt="{html.escape(re.sub('<[^>]+>','',html.unescape(p['t'][1])))}" width="1400" height="875"></figure>
    <div class="post-layout">
      <aside class="toc" aria-label="Table of contents">{T('En este artículo','In this article','p',' class="label"')}<ol id="toc"></ol></aside>
      <article>
        <div class="prose" data-l="en" lang="en">{body_en}</div>
        <div class="prose" data-l="es" lang="es">{body_es}</div>
        <div class="author">
          <span class="avatar">AL</span>
          <div><h3>Alejandro Lovera</h3>
          {T('Fundador · Estrategia y performance','Founder · Strategy &amp; performance','p',' class="role"')}
          {T('Publicista con másteres en Big Data &amp; Business Intelligence e IA Empresarial, AMA PCM® y ganador de un Effie. Más de 14 años gestionando marketing para marcas en EE. UU. y Latinoamérica.','Advertising professional with master’s degrees in Big Data &amp; Business Intelligence and Business AI, AMA PCM® and Effie winner. 14+ years managing marketing for brands in the US and Latin America.','p')}
          <a href="https://www.linkedin.com/in/alejandro-lovera/" target="_blank" rel="noopener">LinkedIn ↗</a></div>
        </div>
      </article>
      <aside class="side">
        <div class="box">{T('¿Necesitas ayuda con esto?','Need help with this?','strong')}{T('Revisamos tu cuenta y te decimos qué métricas están frenando tu crecimiento.','We review your account and tell you which metrics are holding back your growth.','p')}<a class="btn btn-primary" href="#contact">{T('Escríbenos','Get in touch')} <span class="arr">→</span></a></div>
        <div class="box">{T('Compartir','Share','p',' class="label"')}<div class="share"><a href="https://www.linkedin.com/sharing/share-offsite/?url={canon}" target="_blank" rel="noopener">LinkedIn</a><a href="https://twitter.com/intent/tweet?url={canon}" target="_blank" rel="noopener">X</a><button type="button" id="copy">{T('Copiar link','Copy link')}</button></div></div>
      </aside>
    </div>
  </div>
  <section class="method" style="margin-top:clamp(3rem,6vw,5rem)"><div class="wrap">
    <div class="sec-head rv"><div>{T('Sigue leyendo','Keep reading','span',' class="label"')}{T('Artículos relacionados','Related articles','h2')}</div></div>
    <div class="grid" style="grid-template-columns:repeat(2,1fr);padding-top:0">{relh}</div>
  </div></section>
</main>'''
    title=(html.unescape(M[es]['title'].replace(' - FL Marketing Management',' | FL Marketing')), 'B2B Performance Marketing Metrics Beyond CPC | FL Marketing')
    desc=(M[es]['desc'],M[en]['desc'])
    faq_en=re.findall(r'<h3>([^<]*\?)</h3>\s*<p>(.*?)</p>',body_en)
    ld={"@context":"https://schema.org","@graph":[
      {"@type":"BlogPosting","@id":canon+"#article","headline":re.sub('<[^>]+>','',html.unescape(p['t'][1])),"description":desc[1],"url":canon,"mainEntityOfPage":canon,
       "datePublished":M[en]['pub'],"dateModified":M[en]['mod'],"inLanguage":"en","image":M[en]['img'],"wordCount":M[en]['words'],
       "author":{"@type":"Person","@id":BASE+"/#founder","name":"Alejandro Lovera","url":"https://www.linkedin.com/in/alejandro-lovera/","jobTitle":"Founder"},
       "publisher":{"@id":BASE+"/#org"},"articleSection":p['cat'][1]},
      {"@type":"FAQPage","mainEntity":[{"@type":"Question","name":q,"acceptedAnswer":{"@type":"Answer","text":re.sub('<[^>]+>','',a)}} for q,a in faq_en]},
      {"@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":BASE+"/"},{"@type":"ListItem","position":2,"name":"Blog","item":BASE+"/blog/"},{"@type":"ListItem","position":3,"name":re.sub('<[^>]+>','',html.unescape(p['t'][1])),"item":canon}]},
      {"@type":"Organization","@id":BASE+"/#org","name":"FL Marketing Management","url":BASE+"/"}]}
    alt=f'<link rel="alternate" hreflang="en" href="{canon}">\n<link rel="alternate" hreflang="es" href="{BASE}/blog/{es}/">\n<meta property="og:type" content="article">\n<meta property="article:published_time" content="{M[en]["pub"]}">\n<meta property="og:image" content="{M[en]["img"]}">'
    extra='''
function buildToc(){const l=document.documentElement.lang;const pr=document.querySelector('.prose[data-l="'+l+'"]');const t=document.getElementById('toc');t.innerHTML='';
 pr.querySelectorAll('h2[id]').forEach(h=>{if(h.closest('.takeaways'))return;const li=document.createElement('li');li.innerHTML='<a href="#'+h.id+'">'+h.textContent+'</a>';t.appendChild(li)});
 const links=[...t.querySelectorAll('a')];const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){links.forEach(a=>a.classList.toggle('on',a.getAttribute('href')==='#'+e.target.id))}}),{rootMargin:'-20% 0px -70% 0px'});
 pr.querySelectorAll('h2[id]').forEach(h=>obs.observe(h))}
window.onLang=()=>buildToc();
document.addEventListener('DOMContentLoaded',buildToc);
document.getElementById('copy').addEventListener('click',e=>{try{navigator.clipboard.writeText(location.href)}catch(x){};e.target.textContent=document.documentElement.lang==='es'?'¡Copiado!':'Copied!'});
'''
    out=chrome(T,main,title,desc,canon,ld,alt)+run_js(d,{'es':{'t':title[0],'d':desc[0]},'en':{'t':title[1],'d':desc[1]}},extra)
    open('../design/blog-post.html','w').write(out)

index(); post(POSTS[0]); print('ok')
