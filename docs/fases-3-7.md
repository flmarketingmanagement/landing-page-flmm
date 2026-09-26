# Fases 3 a 7: configuración, contenido, formulario, SEO y QA

Fecha: 2026-09-26. Todo se probó en una réplica local (WordPress 7.0, Polylang 3.8, Rank Math 1.0.279, Jetpack 16.3) con la misma estructura de páginas y entradas que producción. En staging falta desplegar el theme y ejecutar la migración (ver "Pasos en staging").

## Qué quedó hecho

### Staging
- Sitio creado: staging-a94a-flmarketingmanagement.wpcomstaging.com (blog ID 257600917).
- Polylang 3.8.9 instalado y activo.

### Migración (Herramientas > Migración FLMM)
Once pasos que se pueden repetir sin duplicar contenido:

| Paso | Resultado |
|---|---|
| 1. Idiomas | EN por defecto sin prefijo, ES en /es/, idioma por directorio, sin redirección por navegador, medios traducibles |
| 2. Idioma del contenido existente | Todo queda en EN salvo las 3 entradas en español |
| 3. Imágenes | 11 ilustraciones con nombres descriptivos (`consulting-illustration.webp`...) y texto alternativo EN y ES |
| 4. Servicios | 10 páginas EN (8 existentes con la misma URL + growth-hacking y marketing-automation) y sus 10 traducciones ES enlazadas, con plantilla Servicio e imagen destacada |
| 5. Empresa | About, Digitales Sin Fronteras, Marketing Today Podcast, Privacy Policy y Contact en EN y ES, con el contenido actual corregido |
| 6. Home y blog | Home y Blog como páginas (portada y página de entradas) en EN y ES |
| 7. Blog | 3 pares EN/ES enlazados, categorías Performance, AEO y AI/IA, TL;DR y Puntos clave con estilo propio, enlaces rotos corregidos |
| 8. Autor | Biografía, cargo y LinkedIn de Alejandro Lovera en EN y ES |
| 9. Menús | Header, Footer navegación y Footer empresa, uno por idioma |
| 10. SEO | Título, descripción, keyword e imagen OG por página en Rank Math; organización, BlogPosting y migas de pan |
| 11. Redirecciones | 15 redirecciones 301 en Rank Math; 4 páginas antiguas pasan a borrador |

### URLs en español

| EN | ES |
|---|---|
| / | /es/ |
| /consulting/ | /es/estrategia-de-marketing/ |
| /performance-marketing/ | /es/marketing-de-performance/ |
| /seo-aeo/ | /es/posicionamiento-seo-aeo/ |
| /aeo-content/ | /es/contenido-aeo/ |
| /design-branding/ | /es/contenido-creativo-multimedia/ |
| /website/ | /es/ecommerce-y-sitios-web/ |
| /growth-hacking/ | /es/estrategias-growth-hacking/ |
| /analytics/ | /es/analitica-de-marketing/ |
| /ai-assistants/ | /es/asistentes-de-ia/ |
| /marketing-automation/ | /es/automatizacion-de-marketing/ |
| /about/ | /es/sobre-nosotros/ |
| /digitales-sin-fronteras/ | /es/podcast-digitales-sin-fronteras/ |
| /podcast/ | /es/podcast-marketing-today/ |
| /privacy-policy/ | /es/politica-de-privacidad/ |
| /contact-us/ | /es/contacto/ |
| /blog/ | /es/articulos/ |
| /blog/{entrada}/ | /es/blog/{entrada}/ |

Las 3 entradas en español cambian de /blog/x/ a /es/blog/x/; hay redirección 301 para cada una.

### Redirecciones 301
Las 10 aprobadas en la fase 0, más: las 3 entradas en español a su URL con /es/ y las 2 categorías antiguas (ai-performance-marketing a performance, marketing-strategy-ai a aeo).

### Formulario (fase 5)
- Jetpack Forms con nombre, email y mensaje, en EN y ES, dentro de la sección de contacto de todas las páginas.
- Envío a hello@flmarketingmanagement.com (ya no copia al Gmail personal), mensaje de confirmación en pantalla, antispam con Akismet.
- Botones "Envíame un mail" y Telegram (t.me/aleloveeee) junto al formulario y en el footer.
- Probado en local: el envío se guarda en Respuestas del formulario y muestra la confirmación. El correo real se prueba en staging.

### SEO y AEO (fase 6)
- Títulos de hasta 60 caracteres y descripciones de 140 a 160 en EN y ES (se corrigieron 3 títulos de src/ que pasaban de 60).
- Schema sin duplicados: Rank Math emite Organization, WebSite, WebPage, BreadcrumbList, BlogPosting y Person; el theme agrega Service (servicios) y FAQPage (servicios y artículos). Verificado: cada tipo aparece una sola vez por página.
- hreflang EN/ES recíproco y x-default a la versión en inglés.
- Sitemap de Rank Math con ambos idiomas.
- robots.txt permite OAI-SearchBot, ChatGPT-User, Claude-SearchBot, Claude-User, PerplexityBot y Perplexity-User. En staging no se toca (sigue bloqueado para buscadores).
- /llms.txt con la descripción de la agencia y los enlaces a servicios, páginas y artículos en ambos idiomas.
- Imagen Open Graph: la ilustración de cada servicio.

### QA local (fase 7)

| Revisión | Resultado |
|---|---|
| Paridad visual 1440 y 390 px | Igual al diseño; sin desbordes horizontales en ninguna página |
| Selector de idioma | En todas las páginas; cada página enlaza a su traducción |
| Enlaces internos | 47 páginas recorridas, 0 errores 404 |
| Redirecciones | Las 15 responden 301 al destino correcto |
| Formulario, carrusel, FAQ, índice del artículo | Funcionan |
| Bloques en el editor | Todas las páginas y entradas migradas son bloques válidos |
| Textos alternativos | Todas las imágenes tienen alt |
| Lighthouse mobile (con compresión) | Home 92/100/96/100; Servicio 96/100/96/100; Artículo 89/100/96/100; Home ES 95/100/96/100 (rendimiento, accesibilidad, buenas prácticas, SEO) |
| GTM | Con GTM4WP activo, el theme no lo duplica; sin GTM4WP, el theme carga GTM-N3SW2MJ4 |

El artículo queda en 89 de rendimiento en local por la imagen destacada sin CDN. En WordPress.com las imágenes pasan por su CDN con tamaños adaptados; hay que confirmarlo en staging.

Correcciones aplicadas durante la QA: título duplicado con Rank Math, alineación de páginas de empresa, margen de la imagen del artículo, animación de entrada que retrasaba el LCP, canonical del blog en español, estilo del formulario de Jetpack.

## Pasos en staging (pendientes)

1. Activar GitHub Actions en el repositorio (Settings > Actions > General > Allow all actions).
2. Conectar el staging en GitHub Deployments con la rama `staging`, destino `/wp-content/themes/flmm-studio`, despliegue automático y workflow `.github/workflows/wpcom.yml`.
3. Activar el theme FLMM Studio en staging.
4. Ejecutar Herramientas > Migración FLMM > Ejecutar todos los pasos.
5. Dar acceso de red a este entorno para staging y producción, para repetir la QA sobre el sitio real (capturas, Lighthouse, Rich Results).

## QA en staging (2026-09-26, theme 1.0.6)

| Revisión | Resultado |
|---|---|
| Sitemap | 46 URLs, todas responden 200 |
| Enlaces internos | 63 revisados, 0 rotos |
| Redirecciones | Las 15 responden 301 al destino correcto (200) |
| Títulos y descripciones | 1 `<title>` por página, hasta 60 caracteres; descripciones de 140 a 160 |
| H1 | Uno por página |
| hreflang | 40 páginas con EN, ES y x-default recíprocos |
| Canonical | Correcto en todas |
| Schema | Sin tipos duplicados; Service y FAQPage en servicios, BlogPosting y FAQPage en artículos |
| Desborde horizontal | Ninguno en 1440 y 390 px |
| Lighthouse mobile | Home 96/100/96, Servicio 94/100/96, Artículo 94/100/96, Contacto ES 98/100/96 (rendimiento, accesibilidad, buenas prácticas). SEO 61 a 69 solo porque staging está en noindex |
| robots.txt | `Disallow: /` en staging (esperado); en producción se agregan los bots de IA |
| /llms.txt | Responde 200 con servicios en EN y ES |

Observaciones:
- Las 6 páginas de categoría no tenían meta description: se agregaron en la migración (paso 10, theme 1.0.7).
- Los artículos muestran los botones de compartir y "Me gusta" de Jetpack: se mantienen.
- En la página se inyecta un chat de n8n (cdn.jsdelivr.net/npm/@n8n/chat) que no viene del theme; probablemente de GTM o de un fragmento. Lo agregó el dueño: se mantiene.
- Los errores de consola vistos en la QA venían del límite de peticiones de WP.com (429) durante las pruebas, no del sitio.
