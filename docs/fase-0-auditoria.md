# Fase 0: auditoría de flmarketingmanagement.com

Fecha: 2026-09-26. Solo lectura, sin cambios en el sitio.
Fuente: API de WordPress.com (MCP). El contenedor no tiene acceso HTTP directo al dominio, por lo que no se revisó el HTML renderizado (scripts inyectados por plugins, robots.txt, sitemap).

## 1. Estado del sitio

| Elemento | Valor |
|---|---|
| Sitio | FL Marketing Management, blog ID 226031076, plan Atomic |
| Theme activo | Idea Flow (Superb Themes), block theme. Inactivos: Twenty Twenty-Four, Twenty Twenty-Two |
| Portada | `show_on_front = posts`. El home vive en la plantilla personalizada `idea-flow//front-page` (no es una página) |
| Enlaces permanentes | `/blog/%postname%/` |
| Idioma del sitio | es (el contenido está casi todo en EN) |
| Plugin SEO | Rank Math SEO 1.0.279 |
| Formularios | Jetpack Forms (bloque `jetpack/contact-form` multistep) |
| Usuarios | Alejandro Lovera (administrador), anairam359 (editor) |
| Respaldo | Jetpack Backup activo. Último respaldo correcto: 2026-09-25 17:34. 253 respaldos desde 2026-01-17 |
| Staging | No existe |

### Plugins

Activos: Akismet, Crowdsignal Dashboard, Elementor 4.3.2, Gravatar Enhanced, GTM4WP 2.0.3, Gutenberg 24.0, Jetpack, Layout Grid, Page Optimize, Rank Math SEO, Superb Addons 4.2.1, WordPress.com Site Migration File Shim, WPCode Lite 2.3.9.
Inactivos: Classic Editor, Crowdsignal Forms.

### Elementor

Ninguna página usa Elementor. Solo existe el "Kit por defecto" (elementor_library 1763). Se puede desactivar en la fase 8 sin riesgo.
Superb Addons sí se usa (patrones y clases `superbthemes-*` en páginas, header y footer). Layout Grid no se usa.

## 2. Páginas publicadas (18)

| ID | URL | Título actual | Idioma | Destino |
|---|---|---|---|---|
| 179 | /performance-marketing/ | Performance Marketing \| Data-Driven Growth – FL Marketing Management | EN | Servicio |
| 228 | /seo-aeo/ | Maximize Your Brand's Visibility with SEO and AEO | EN | Servicio |
| 1472 | /aeo-content/ | How AEO Content Enhances Brand Visibility in AI Searches | EN | Servicio |
| 224 | /consulting/ | Personalized Digital Marketing Consulting for Sustainable Growth | EN | Servicio |
| 219 | /design-branding/ | Graphic Design and Branding Servicesc | EN | Servicio |
| 234 | /website/ | Ecommerce Website Development \| Shopify Online Store Experts | EN | Servicio |
| 216 | /analytics/ | Data Analytics Services for Business Growth | EN | Servicio |
| 536 | /ai-assistants/ | Enhance Efficiency with AI Assistants for Business | EN | Servicio |
| 1665 | /about/ | About us | EN | Empresa |
| 591 | /digitales-sin-fronteras/ | Digitales Sin Fronteras | ES/EN mixto | Empresa |
| 32 | /podcast/ | Marketing Today Podcast | EN | Empresa |
| 55 | /privacy-policy/ | FL Marketing Management Privacy Policy Overview | EN | Empresa |
| 74 | /contact-us/ | How to Effectively Describe Your Project | ES/EN mixto | Empresa |
| 1604 | /blog/ | Mastering Digital Marketing Strategies | EN | Índice del blog |
| 1613 | /digital-marketing-services/ | AI-Driven Solutions for Performance Marketing Success | EN | Redirigir |
| 1563 | /performance-marketing-strategies-for-2026-using-ai-to-maximize-b2b-roi/ | How AI Transforms B2B Performance Marketing in 2026 | EN | Redirigir (duplica el post 1568) |
| 231 | /ecommerce/ | Optimized E-commerce Solutions... | EN | Redirigir (duplica /website/) |
| 240 | /website-monetization/ | Maximize Your Website's Revenue... | EN | Redirigir (legacy) |

Nuevas a crear: /growth-hacking/ y /marketing-automation/.

## 3. Blog (6 entradas, 3 pares EN/ES)

| Tema | EN | ES | Categoría actual |
|---|---|---|---|
| Glosario IA | 1597 /blog/marketing-dictionary-ai-aeo-llm/ | 1835 /blog/terminos-marketing-ia-2026/ | Marketing Strategy & AI |
| Métricas B2B | 1591 /blog/performance-marketing-metrics/ | 1844 /blog/metricas-de-performance-marketing-b2b-mas-alla-del-cpc/ | AI Performance Marketing |
| Entropía de Shannon | 1568 /blog/ai-shannon-entropy/ | 1838 /blog/ia-y-entropia-de-shannon-en-performance-marketing-b2b/ | AI Performance Marketing |

Todas son del autor Alejandro Lovera. Categorías actuales: AI Performance Marketing (4), Marketing Strategy & AI (2), Sin categoría (0).

## 4. Menús (navegación de bloques)

| ID | Nombre | Uso | Problemas |
|---|---|---|---|
| 1467 | Menú Cabecera | Header y footer del theme: Home, Blog, Contact (/contact-us/) | |
| 1458 | Menú | Footer final, columna About | |
| 1659 | Menú 3 | Footer final, columna Services | "AI Assistants" apunta a `#`; error "AEO Contect" |
| 244, 199, 188, 5 | Menús antiguos | Sin uso aparente | Enlazan a /contact/, /performance/, /search-engine-optimization/, /ecommerce/, /website-monetization/ |

## 5. Integraciones y terceros

- **Google Tag Manager:** se carga con el plugin GTM4WP. No se ve el ID del contenedor desde la API; hay que confirmar en Ajustes > Google Tag Manager que es GTM-N3SW2MJ4. Cuando el theme incluya GTM, hay que desactivar GTM4WP (o viceversa) para no cargarlo dos veces.
- **WPCode Lite:** activo. La API no permite leer sus snippets. Puede contener scripts (Telegram, píxeles). **PENDIENTE:** revisarlos en WPCode > Code Snippets.
- **Bot de Telegram:** no aparece en páginas, entradas, plantillas ni template parts. Lo más probable es que esté en WPCode o en GTM. **PENDIENTE:** confirmar dónde está.
- **Embeds:** YouTube y enlaces de Spotify, Instagram y TikTok en /podcast/ y /digitales-sin-fronteras/. No hay otros scripts en el contenido.
- **Formulario del home:** Jetpack multistep de 7 campos. Envía a hello@ y a un Gmail personal. Tiene pasos en ES y campos en EN, el botón dice "Previews", Email aparece dos veces y el teléfono es obligatorio.

## 6. Errores detectados

1. **/analytics/:** párrafo sobrante "This translation maintains the essence and structure of your original text..." y dos párrafos vacíos.
2. **/consulting/:** párrafo sobrante "This translation maintains the core message and structure of your original text...".
3. **/design-branding/:** título con errata "Servicesc". Además cita cifras de un tercero sin verificar ("more than 6 years", "200 brands", "7+ countries") y usa raya larga.
4. **Footer (patrón sincronizado "Footer final", ID 1581, usado en el home):** el primer icono de LinkedIn apunta a https://www.facebook.com/fl.marketing.management. También hay un footer duplicado dentro del contenido de las páginas 1604, 1563, 1472, 536 y 179, con el mismo error y cinco enlaces `#` de relleno ("Meet Our Writers", "Our Story", etc.).
5. **Home:** enlaza a /about-us/, que no existe (404).
6. **CTA equivocado:** "Build high-performing websites" aparece en /aeo-content/, /ai-assistants/ y /performance-marketing/.
7. **H1 ausente o incorrecto:** en /seo-aeo/, /consulting/, /website/, /digital-marketing-services/ y en 1563. En /about/ dice "Florida Marketing Management".
8. **/website/:** el slug es genérico pero el contenido habla solo de ecommerce y Shopify.
9. **1563:** repite dos veces todas sus secciones.
10. **/digitales-sin-fronteras/:** presenta a Felipe Ríos Barraza como co-host. Según CLAUDE.md figura como advisor. **PENDIENTE:** confirmar el rol correcto en esta página.
11. **Rayas largas (—):** en /about/, /website/, /design-branding/, /podcast/ y 1563.
12. **Posts ES con enlaces internos rotos:**
    - En 1835 y 1838: /blog/metricas-performance-marketing-b2b/
    - En 1835 y 1844: /blog/ia-entropia-shannon-marketing/
13. **/privacy-policy/:** el email aparece dentro de un `<a>` sin href y no menciona cookies ni analítica (GTM).
14. **/seo-aeo/:** las respuestas de la FAQ tienen 10 a 20 palabras (la regla pide 40 a 60).
15. **/blog/:** el título "Mastering Digital Marketing Strategies" no corresponde a un índice. La página coexiste con la base de permalinks /blog/ sin estar asignada como página de entradas.

## 7. Redirecciones 301 propuestas

| Origen | Destino | Motivo |
|---|---|---|
| /about-us/ | /about/ | Enlazada desde el home, no existe |
| /contact/ | /contact-us/ | Usada en menús antiguos. Se mantiene /contact-us/ por SEO |
| /digital-marketing-services/ | / | Resumen de servicios, lo reemplaza la sección de servicios del home |
| /performance-marketing-strategies-for-2026-using-ai-to-maximize-b2b-roi/ | /blog/ai-shannon-entropy/ | Artículo duplicado publicado como página |
| /ecommerce/ | /website/ | Duplicado |
| /website-monetization/ | /website/ | Servicio legacy sin equivalente |
| /performance/ | /performance-marketing/ | URL antigua en menús |
| /search-engine-optimization/ | /seo-aeo/ | URL antigua en menús |
| /blog/metricas-performance-marketing-b2b/ | /blog/metricas-de-performance-marketing-b2b-mas-alla-del-cpc/ | Enlace roto en posts ES (también se corrige el enlace) |
| /blog/ia-entropia-shannon-marketing/ | /blog/ia-y-entropia-de-shannon-en-performance-marketing-b2b/ | Enlace roto en posts ES (también se corrige el enlace) |

Se crean con el módulo de redirecciones de Rank Math (fase 6). Las páginas redirigidas pasan a borrador después de crear la redirección.

## 8. Staging y respaldo

- **Respaldo:** confirmado. Jetpack Backup está activo y el último respaldo del 2026-09-25 terminó sin errores.
- **Staging:** no existe y la API disponible no permite crearlo. Pasos para crearlo:
  1. Entra en wordpress.com/sites y abre flmarketingmanagement.com.
  2. Ve a la pestaña **Staging site** (o Hosting > Staging site) y haz clic en **Add staging site**.
  3. Cuando termine, el sitio aparecerá como `staging-XXXX-flmarketingmanagement.wpcomstaging.com`. Avísame y lo verifico.

## 9. PENDIENTES acumulados

- Crear el sitio de staging.
- Confirmar el ID de GTM en GTM4WP y revisar los snippets de WPCode.
- Ubicar el bot de Telegram y decidir qué hacer con él.
- Confirmar el rol de Felipe Ríos Barraza en /digitales-sin-fronteras/.
- Verificar o quitar las cifras de Vero Design en /design-branding/ y las estadísticas citadas en los posts (6sense, G2).
- Decidir si el formulario sigue enviando copia al Gmail personal.
- Permitir el acceso de red del entorno a flmarketingmanagement.com (y al dominio de staging) para las capturas, Lighthouse y la verificación de HTML.
- Fotos y LinkedIn del equipo, testimonios con autorización y años de experiencia (todos o ninguno).
