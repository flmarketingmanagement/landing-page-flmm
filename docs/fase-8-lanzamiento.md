# Fase 8: lanzamiento (requiere aprobación explícita)

**Estado: lanzado el 2026-09-26.** Producción con FLMM Studio 1.1.2, Polylang y la migración completa. QA en vivo: 46 URLs sin errores, 15 redirecciones 301, hreflang recíproco en 40 páginas, sin schema duplicado, robots.txt con bots de IA, sitemap y llms.txt. Formulario probado (llegó a la bandeja de entrada).

Lecciones: activar Polylang antes de ejecutar la migración (la 1.0.8 ahora se detiene con un aviso) y, si `/es/` muestra el blog, editar el idioma Español y guardar (la 1.0.9 limpia la caché sola).

Nada de esta lista se ejecuta sin la aprobación del dueño del sitio.

## Método recomendado

Instalar el theme en producción y ejecutar allí la misma migración que en staging, en lugar de copiar la base de datos de staging a producción. Así no se pierden los cambios que haya en producción desde que se creó el staging (mensajes del formulario, comentarios, estadísticas).

## Checklist

1. **Respaldo:** confirmar en Jetpack Backup que hay un respaldo de producción de ese mismo día y que terminó sin errores.
2. **Código:** fusionar el pull request en `main`.
3. **Conectar producción** en GitHub Deployments con la rama `main`, destino `/wp-content/themes/flmm-studio` y despliegue **manual**. Lanzar el despliegue.
4. **Polylang:** instalarlo y activarlo en producción.
5. **Activar** el theme FLMM Studio.
6. **Migración:** Herramientas > Migración FLMM > Ejecutar todos los pasos. Revisar el registro (sin errores en rojo).
7. **Verificar en vivo:**
   - Home, 3 servicios, un artículo y contacto en EN y ES.
   - Selector de idioma y hreflang.
   - Envío de prueba del formulario (debe llegar a hello@flmarketingmanagement.com).
   - Las 15 redirecciones.
   - Schema en el Rich Results Test y en validator.schema.org (home, un servicio y un artículo).
   - GTM: modo vista previa de Tag Manager y evento en GA4 en tiempo real.
   - robots.txt, /sitemap_index.xml y /llms.txt.
8. **Limpieza** (después de confirmar que ninguna página depende de ellos):
   - Desactivar y eliminar Elementor y su "Kit por defecto".
   - Desactivar Superb Addons y Layout Grid (no los usa el nuevo contenido).
   - Borrar las plantillas personalizadas del theme anterior (Idea Flow).
   - Desactivar el fragmento de WPCode "Telegram" (1656). El chat de n8n se mantiene.
9. **Search Console:** enviar /sitemap_index.xml y pedir la indexación de home (EN y ES), los 10 servicios y los 3 artículos.
10. **Después del lanzamiento:** quitar la herramienta de migración del theme (`inc/migrate/`) en una versión nueva.

## Si algo sale mal

- Volver al theme Idea Flow desde Apariencia > Temas (el contenido nuevo queda guardado, pero se verá con el theme anterior).
- Restaurar el respaldo de Jetpack del mismo día si hace falta volver al estado anterior completo.
