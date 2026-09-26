# Contexto para Claude Code

## Marca
- Colores: carbón `#303030`, rosa `#ff78a8` (solo acentos), fondo hueso `#F4F2EE`.
- Tipografía: Geist (texto y títulos), Geist Mono (etiquetas y datos).
- Logo: firma manuscrita dentro de un círculo (`assets/brand/`). Se usa como máscara CSS para colorearla.
- Estilo: sobrio y neutro. Sin gradientes llamativos ni cursivas de colores.

## Reglas de contenido
- Idiomas: EN por defecto, ES alternativo. Todo texto visible debe existir en ambos.
- Español neutro (no rioplatense). Nunca usar raya larga (—).
- No inventar datos: años de experiencia, certificaciones o métricas solo si están confirmados.
- Años de experiencia del equipo: se muestran a todos o a ninguno (hoy: a ninguno).

## SEO / AEO
- Cada página: un H1 con la palabra clave, primer párrafo de 40 a 60 palabras que responde "qué es".
- FAQ con respuestas de 40 a 60 palabras, títulos H2 con la palabra clave.
- Datos estructurados JSON-LD: Organization, Service, BreadcrumbList, FAQPage, BlogPosting según el tipo.
- Title de hasta 60 caracteres, meta description de 140 a 160.

## Decisiones tomadas
- Hero: "Marketing changed. So did we." / "El marketing cambió. Nosotros también."
- Contacto: formulario corto + "Envíame un mail" + Marky Digital (agente, t.me/marky_digital_bot?start=web). Telegram personal (t.me/aleloveeee) queda en el footer. Email: hello@flmarketingmanagement.com.
- El chat de n8n que se inyecta en el sitio lo agregó el dueño: se mantiene.
- Equipo en carrusel. Felipe Ríos Barraza figura como advisor.
- Las URLs de servicios existentes se mantienen para no perder posicionamiento.

## Sitio y entornos
- Producción: flmarketingmanagement.com (blog ID 226031076). Staging: staging-a94a-flmarketingmanagement.wpcomstaging.com (blog ID 257600917).
- Trabajar siempre en staging; producción solo con aprobación explícita.
- Plugins relevantes: Rank Math (SEO), GTM4WP (carga GTM-N3SW2MJ4), Jetpack (formularios), WPCode (fragmento 1656 "Telegram").
- Informes: docs/fase-0-auditoria.md, docs/fases-3-7.md, docs/fase-8-lanzamiento.md. Pendientes: docs/pendientes.md.
- Ramas: `staging` despliega solo al sitio de staging; `main` despliega solo a producción al fusionar un PR. Trabajar en ramas y abrir PR a `main`.
- Imágenes de la migración: si se reemplaza una, usar un nombre de archivo nuevo (la migración no vuelve a subir un archivo con el mismo nombre).

## Theme flmm-studio
- Textos visibles del theme: usar `flmm__( 'Texto en inglés' )` y agregar la traducción en `inc/i18n.php`.
- Secciones nuevas: agregar un constructor en `inc/patterns.php` (recibe idioma y datos) y registrarlo en `flmm_pattern_list()`.
- Al subir imágenes, usar nombres que no coincidan con slugs de páginas (por ejemplo `consulting-illustration.webp`): un adjunto con el slug `consulting` le quita la URL a la página.
- URLs en español: slugs propios (Polylang gratis no comparte slugs). Mapa en `inc/migrate/data.php` (`flmm_mig_es_slugs()`).
- Polylang 3.7+ guarda sus opciones al final de la petición: cambiarlas con `PLL()->options->set()`, no con `update_option()`.
- El theme no declara `title-tag`: el título lo pone la plantilla de bloques con el filtro de Rank Math (si no, sale duplicado).
- Schema: Rank Math emite Organization, WebSite, WebPage, BreadcrumbList, BlogPosting y Person; el theme solo Service y FAQPage.
