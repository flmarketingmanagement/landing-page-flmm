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
assets/
  images/          Ilustraciones de servicios (fondo #F4F2EE)
  brand/           Logo (firma) y sello AMA PCM
```

## Regenerar las páginas

```bash
cd src
python3 build_services.py
python3 build_blog.py
```

Requiere Python 3. Las páginas quedan en `design/`.

## Próximo paso: theme de WordPress

1. Convertir `design/` en un block theme (`theme/flmm-studio/`).
2. Conectar el repositorio con WordPress.com mediante **GitHub Deployments** (Hosting → GitHub Deployments),
   destino `/wp-content/themes/flmm-studio`, despliegue manual en producción.
3. Crear las versiones en español con URLs propias (`/es/...`) para que Google las indexe.

El contenido de las páginas vive en WordPress (base de datos); el repositorio guarda el código del theme y el diseño.
