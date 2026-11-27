# Pendientes

Datos o decisiones que no se inventaron y hay que confirmar.

## Contenido

1. **Equipo:** fotos de las 13 personas (en proceso). LinkedIn es opcional: el dueño evalúa no enlazarlo.
2. **Años de experiencia del equipo:** mostrarlos a todos o a ninguno (hoy: a ninguno).
3. **Trayectoria del home:** confirmar "14+ años en marketing digital". Países: confirmado, 17+ países con experiencia y clientes B2B y B2C.
4. ~~**Testimonios de clientes**~~ confirmados (RMPartes.cl, Gig y PolariGlass).
5. **Reseñas del podcast:** autorización de Daniel Goncalves y Pablo Pozarski (se tradujeron al español).
6. **Vero Design:** confirmar "más de 200 marcas en más de 7 países" (página de contenido creativo y su meta description).
7. ~~**Estadísticas de los artículos**~~ verificadas: 6sense 2025 Buyer Experience Report (94% de los compradores usa LLM al comprar) y G2 2026 Buyer Behavior Report (61% usa o planea usar agentes de IA al comprar software; se corrigió la frase que decía "buscadores con IA").
8. **Digitales Sin Fronteras:** Felipe Ríos Barraza figura como co-conductor del podcast. Se quitaron los cargos en empresas (Fintual y Galgo) porque podían estar desactualizados. Confirmar si se agregan cargos actuales.
9. **Política de privacidad:** actualizada en septiembre de 2026 (banner, GA4, píxeles de Meta y OpenAI, n8n, Jetpack, Telegram, derechos, bases y conservación). Sigue siendo recomendable una revisión legal. En Complianz faltan Meta Pixel y OpenAI en la lista de servicios.
10. ~~**Entradas de servicio**~~ todas tienen entre 40 y 60 palabras (validado en `check_services_seo.py`).

11b. **Clientes de Florida:** las páginas de Florida y Lakeland listan arthouseboca.com, habiaunavezunafoto.com y hart.live solo por su dominio, sin descripción ni resultados. Si hay autorización, se pueden agregar el nombre, el servicio y un resultado confirmado.

## Decisiones

11. **Bot de Telegram:** decidido. Se quita el botón flotante (desactivar el fragmento 1656 de WPCode en staging y producción) y se enlaza "Marky Digital (Agent)" en la columna Contacto del footer. Enlace: https://t.me/marky_digital_bot?start=web. También reemplaza a Telegram junto al formulario de contacto. Fragmento desactivado en staging y en producción.
12. ~~**Copia del formulario**~~ decidido: solo a hello@flmarketingmanagement.com.
13. ~~**Plugins viejos**~~ el dueño los eliminó.
14. ~~**Correos del formulario en spam**~~ en producción llegan a la bandeja de entrada.

## Accesos

15. ~~**GitHub Actions**~~ activo.
16. ~~**GitHub Deployments**~~ staging (rama `staging`) y producción (rama `main`, despliegue automático al fusionar).
17. ~~**Acceso de red**~~ activo para staging y producción.
18. ~~**Search Console**~~ sitemap enviado.
19. **Herramienta de migración:** se mantiene; la migración automática la usa en cada versión.

## Blog desde noticias (n8n)
- Workflows: "📰 Blog noticias: generar" (lunes, miércoles y viernes 9:00 Florida) y "📰 Blog noticias: acciones" (botones de Telegram y formulario de imagen).
- Al aprobar se publica al instante. Sin imagen usa una genérica de la categoría; la imagen se puede subir o cambiar también con el post publicado.
- Rechazar manda el par EN/ES a la papelera. Si la validación falla, Claude corrige el post una vez.
- Publicar 2 o más posts el mismo día está bien.
- Registro: Sheet "Noticias por dia", columnas estado/post en Hoja 1 y pestaña Blog.
- Endpoints del tema: inc/news-blog.php (flmm/v1/news/...). Autenticación con contraseña de aplicación del autor (credencial n8n "Wordpress FLMM").
- Bot "⚡️ Buscador de Noticias": Telegram en HTML con texto escapado y sin cortar el envío si un mensaje falla (error del 24-nov corregido).

## SEO general (auditoría 27-nov-2026)
- Tema 1.3.3: títulos de categorías, SEO de la política de cookies, enlaces antiguos, sitemap de Rank Math al publicar, sin sitemaps ni "Me gusta" de Jetpack, páginas sin schema Article.
- Revisar en GSC que solo quede el sitemap de Rank Math (sitemap_index.xml).
