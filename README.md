# FL Marketing Management: rediseño del sitio

Rediseño de [flmarketingmanagement.com](https://flmarketingmanagement.com) (WordPress.com, plan Atomic).
Sitio bilingüe (EN por defecto, ES alternativo), estética sobria y enfoque SEO + AEO.

## Estructura

```
design/          Prototipos HTML finales (abrir en el navegador)
  index.html       Home
  *.html           10 páginas de servicio
  blog.html        Índice del blog
  blog-post.html   Plantilla de artículo
src/             Generadores y contenido de las páginas
  services_data.py   Contenido base de servicios (EN/ES)
  services_extra.py  Comparativas, conceptos, FAQ, testimonios y especialistas
  build_services.py  Genera design/<servicio>.html
  build_blog.py      Genera design/blog.html y design/blog-post.html
  flmm.tpl.html      Plantilla del home (fuente de estilos compartidos)
  export_content.py  Exporta el contenido a content.json y home.json (theme y cargador)
assets/
  images/          Ilustraciones de servicios (fondo #F4F2EE)
  brand/           Logo (firma) y sello AMA PCM
theme/flmm-studio/ Block theme de WordPress (ver abajo)
docs/              Informes por fase y capturas comparativas
```

## Regenerar las páginas

```bash
cd src
python3 build_services.py
python3 build_blog.py
```

Requiere Python 3. Las páginas quedan en `design/`.

## Theme `flmm-studio`

Block theme fiel a `design/`. Estructura:

```
theme/flmm-studio/
  style.css, readme.txt, screenshot.png
  theme.json         Paleta, Geist y Geist Mono locales, tamaños fluidos, espaciados, radios
  functions.php      Carga los módulos de inc/
  templates/         front-page, page, page-service, page-landing, single, home, archive, search, 404, index
  parts/             header y footer (cada uno contiene un bloque dinámico)
  inc/
    i18n.php         Textos del theme EN/ES: flmm__() usa Polylang y un diccionario de respaldo
    blocks.php       Bloques dinámicos flmm/* (header, footer, contacto, migas, índice, autor, relacionados...)
    render/          Plantilla PHP de cada bloque dinámico
    patterns.php     Constructores de secciones (home y servicio) y registro de patrones EN y ES
    pattern-data/    Datos de muestra de los patrones (generados por src/export_content.py)
    schema.php       JSON-LD configurable para no duplicar con Rank Math
    gtm.php          GTM-N3SW2MJ4; en modo automático no se carga si GTM4WP está activo
    settings.php     Apariencia > FLMM Studio (GTM, schema, email, Telegram, redes)
    user-fields.php  Cargo, biografía ES, LinkedIn y foto del autor
  assets/css/theme.css  Estilos que theme.json no cubre (componentes, modo oscuro, animaciones)
  assets/js/theme.js    Header, menú móvil, carrusel del equipo, índice del artículo, copiar enlace
```

Decisiones:

- **Idiomas:** header, footer, contacto y piezas del artículo son bloques dinámicos que se pintan en PHP en cada visita con el idioma activo. El contenido de cada página vive en WordPress, una página por idioma enlazada en Polylang.
- **Menús:** ubicaciones clásicas (`primary`, `footer-nav`, `footer-company`) para que Polylang gratis asigne un menú por idioma. Sin menú asignado se usan los enlaces del diseño.
- **Patrones:** cada sección existe en EN y ES en el insertor (categorías "FLMM: ..."). Los mismos constructores PHP generan el contenido real en la fase 4.
- **Schema:** en modo automático, con Rank Math activo el theme solo emite `Service` (plantilla Servicio) y `FAQPage` (bloques Detalles).
- **Theme Check:** pasa salvo un aviso esperado: registra bloques propios (`register_block_type`), algo reservado a plugins solo para themes del directorio de WordPress.org.

### Probar el theme en local

Se usó WordPress 6.8 con SQLite, `php -S` y Playwright (Chromium en `/opt/pw-browsers`).
Capturas comparativas diseño vs theme (1440 y 390 px) en `docs/fase-1/`.

## Despliegue (GitHub Deployments de WordPress.com)

El workflow `.github/workflows/wpcom.yml` valida el theme (sintaxis PHP, JSON, archivos obligatorios y rayas largas) y sube **solo** `theme/flmm-studio` como artefacto `wpcom`. WordPress.com lo copia en `/wp-content/themes/flmm-studio`.

| Entorno | Sitio | Rama | Despliegue |
|---|---|---|---|
| Staging | staging-a94a-flmarketingmanagement.wpcomstaging.com | `staging` | Automático en cada push que cambie el theme |
| Producción | flmarketingmanagement.com | `main` | Manual, solo con aprobación |

### Conectar el repositorio (una vez por sitio)

1. En wordpress.com/sites abre el sitio (primero **staging**) y entra en **Configuración del servidor** o **Alojamiento** > **GitHub Deployments** (Despliegues de GitHub).
2. Haz clic en **Conectar repositorio** y autoriza la app de WordPress.com en GitHub para `flmarketingmanagement/landing-page-flmm`.
3. Completa:
   - **Rama:** `staging` en el sitio de staging, `main` en producción
   - **Directorio de destino:** `/wp-content/themes/flmm-studio`
   - **Despliegues automáticos:** activado en staging, desactivado en producción
   - **Modo de despliegue:** Avanzado, con el workflow `.github/workflows/wpcom.yml`
4. Guarda y lanza el primer despliegue con **Desplegar ahora**.
5. Repite en producción con despliegues automáticos **desactivados** (fase 8).

### Flujo de trabajo

1. Los cambios se hacen en una rama (`feature/...`).
2. Para probarlos, se llevan a la rama `staging`: GitHub Actions valida el theme y WordPress.com lo despliega en staging.
3. Tras revisar staging, se abre un pull request a `main`. Al fusionarlo, el despliegue a producción se lanza a mano desde GitHub Deployments.

Si una validación falla, el workflow no genera el artefacto y no se despliega nada. El historial queda en la pestaña Actions de GitHub y en el registro de despliegues de WordPress.com.

## Contenido y migración

- El contenido vive en WordPress, una página por idioma enlazada en Polylang (EN sin prefijo, ES en `/es/` con slugs en español).
- **Herramientas > Migración FLMM** (en `theme/flmm-studio/inc/migrate/`) aplica el contenido nuevo en once pasos repetibles: idiomas, imágenes, servicios, páginas de empresa, home, blog, autor, menús, SEO de Rank Math y redirecciones. Se quita del theme después del lanzamiento.
- Los textos de servicios salen de `src/services_data.py` y `src/services_extra.py` (exportados a `src/content.json` con `python3 src/export_content.py`). Los de las páginas de empresa están en `inc/migrate/data.php`.

### Cómo editar contenido

- **Páginas y servicios:** se editan en el editor de bloques. Cada sección es un grupo con bloques core (títulos, párrafos, tablas, bloques Detalles para la FAQ). Para agregar una sección, usa el insertor de patrones: categorías "FLMM: Home", "FLMM: Servicio" y "FLMM: Artículo", cada patrón en EN y ES.
- **Traducciones:** en la lista de páginas, la columna de Polylang enlaza cada página con su traducción.
- **Menús:** Apariencia > Menús, un menú por idioma para Header, Footer navegación y Footer empresa.
- **Textos del header, footer y contacto:** Idiomas > Traducciones (grupo FLMM Studio).
- **Email, Telegram, redes, GTM y schema:** Apariencia > FLMM Studio.
- **Autor:** Usuarios > Perfil, sección FLMM Studio (cargo y biografía en español, LinkedIn y foto).
- **SEO:** Rank Math en cada página (título, descripción e imagen OG).

## Documentación

- `docs/fase-0-auditoria.md`: auditoría y redirecciones.
- `docs/fase-1/`: capturas comparativas diseño vs theme.
- `docs/fases-3-7.md`: migración, formulario, SEO y QA.
- `docs/fase-8-lanzamiento.md`: checklist de lanzamiento.
- `docs/pendientes.md`: datos y decisiones por confirmar.

## Próximos pasos

1. Conectar GitHub Deployments en staging, activar el theme y ejecutar la migración (ver `docs/fases-3-7.md`).
2. Lanzamiento con aprobación (ver `docs/fase-8-lanzamiento.md`).

El contenido de las páginas vive en WordPress (base de datos); el repositorio guarda el código del theme y el diseño.
