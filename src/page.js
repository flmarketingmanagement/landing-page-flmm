const I18N=__I18N__;
const META=__META__;
const MSG={es:{fill:'Completa los tres campos, por favor.',open:'Abriendo tu correo…',subj:'Contacto web: '},en:{fill:'Please fill in all three fields.',open:'Opening your email…',subj:'Website inquiry: '}};
let LANG='en';
function setLang(l){LANG=l;document.documentElement.lang=l;
 document.querySelectorAll('[data-i18n]').forEach(el=>{const v=I18N[el.dataset.i18n];if(v)el.innerHTML=v[l]});
 document.querySelectorAll('.lang button').forEach(b=>b.setAttribute('aria-pressed',b.dataset.lang===l));
 document.title=META[l].t;document.querySelector('meta[name=description]').content=META[l].d;
 try{localStorage.setItem('flmm-lang',l)}catch(e){}}
document.querySelectorAll('.lang button').forEach(b=>b.addEventListener('click',()=>setLang(b.dataset.lang)));
try{if(localStorage.getItem('flmm-lang')==='es')setLang('es')}catch(e){}
const h=document.querySelector('header');
addEventListener('scroll',()=>h.classList.toggle('scrolled',scrollY>8),{passive:true});
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}}),{threshold:.12});
document.querySelectorAll('.rv').forEach(el=>io.observe(el));
const f=document.getElementById('form'),msg=f.querySelector('.form-msg');
f.addEventListener('submit',e=>{e.preventDefault();
 if(!f.checkValidity()){msg.textContent=MSG[LANG].fill;return}
 const d=new FormData(f);const body=`${d.get('mensaje')}\n\n${d.get('nombre')} · ${d.get('email')}`;
 location.href='mailto:hello@flmarketingmanagement.com?subject='+encodeURIComponent(MSG[LANG].subj+d.get('nombre'))+'&body='+encodeURIComponent(body);
 msg.textContent=MSG[LANG].open;});
