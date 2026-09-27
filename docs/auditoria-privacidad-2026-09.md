# Auditoría de privacidad: flmarketingmanagement.com (27 de septiembre de 2026)

Hecha con la skill `privacy-compliance` (`.claude/skills/privacy-compliance/`).

## Resumen
El sitio bloquea bien el seguimiento: no se carga nada de analítica ni de publicidad antes de aceptar ni después de rechazar, y Google Consent Mode v2 arranca con las 4 señales en `denied`. Los problemas estaban en la información. La política de privacidad no nombraba todos los servicios (Jetpack Stats, los pings sin cookies de Google), el formulario no tenía aviso de privacidad y la política de cookies de Complianz no lista Meta ni OpenAI. Hallazgos: 0 críticos, 2 altos, 4 medios y 3 bajos. Se corrigieron 5 en el código (PR #20); 4 dependen de la configuración de Complianz o de datos del dueño.

## Alcance y evidencia
- **Marcos:**
  - GDPR/ePrivacy como estándar, porque el banner está configurado para la UE y hay visitantes de varios países.
  - CAN-SPAM y TCPA como referencia si se hace email o SMS de marketing.
  - CCPA y leyes estatales de EE. UU.: no aplican por umbrales (ingresos menores a USD 26,6 M y menos de 100.000 consumidores por estado).
  - Florida FDBR: no aplica (exige más de USD 1.000 M de ingresos más criterios adicionales).
  - Leyes de Latinoamérica (Chile 21.719 desde el 1-dic-2026, México LFPDPPP 2025, Colombia, Argentina): se cubren con el estándar GDPR, salvo requisitos formales listados abajo.
- **Páginas revisadas:** home EN y ES, /privacy-policy/, /es/politica-de-privacidad/, /cookie-policy/, /es/politica-de-cookies/, el formulario de contacto, el banner y el chat.
- **Evidencia técnica:** `scan_consent.js` en producción (4 sesiones, HTTP 200 en todas).

| Sesión | Seguimiento | Cookies de seguimiento | Consent Mode |
|---|---|---|---|
| Sin interactuar | ninguno (solo pings sin cookies de Google, `gcs=G100`) | ninguna | default: ad_storage, analytics_storage, ad_user_data y ad_personalization en `denied` |
| Tras aceptar | Google Analytics, Meta Pixel y Jetpack Stats (entre varias corridas) | `_ga`, `_ga_FX9HN9DRRN` | update a `granted` |
| Tras rechazar | ninguno (pings sin cookies) | ninguna | se mantiene `denied` |
| Con GPC | ninguno | ninguna | `denied` |

Banner: "Accept" y "Deny" en la primera capa, mismo tamaño (155 x 45 px). No verificable desde este entorno: la llegada del píxel de OpenAI, porque el dominio queda bloqueado por la red, aunque está configurado en GTM con consentimiento de marketing.

## Hallazgos
| # | Severidad | Área | Hallazgo | Evidencia | Corrección |
|---|---|---|---|---|---|
| 1 | Alta | Política de cookies | La política generada por Complianz no lista Meta Pixel, el píxel de OpenAI ni Jetpack Stats. | Texto de /cookie-policy/: no aparecen Meta, OpenAI ni Jetpack. | Complianz > Asistente > Servicios: agregar Meta (Facebook Pixel), OpenAI y WordPress.com Stats, y regenerar. **Pendiente del dueño.** |
| 2 | Alta | Formulario | No había aviso de privacidad junto al formulario (información en el momento de la recolección). | HTML del home: sin enlace a la política en el formulario. | Se agregó "Usamos tus datos solo para responder tu mensaje. Revisa nuestra política de privacidad." (EN y ES). **Corregido.** |
| 3 | Media | Política de cookies | Dice que las cookies de publicidad "no perfilan el comportamiento", pero Meta Pixel puede usarse para audiencias. | Texto 5.3 de /cookie-policy/. | En el asistente de Complianz, indicar que se usan píxeles de publicidad para medir campañas y crear audiencias (o confirmar que no se crean audiencias). **Pendiente del dueño.** |
| 4 | Media | Política de privacidad | No mencionaba Jetpack Stats ni los pings sin cookies de Google (Consent Mode avanzado). | Escáner: `stats.wp.com` tras aceptar; `gcs=G100` antes del consentimiento. | Se agregaron ambos en EN y ES. **Corregido.** |
| 5 | Media | Multilingüe | El banner en inglés mostraba 2 descripciones en español (Preferencias y Estadísticas). | Texto del banner en /cookie-policy/. | Traducciones agregadas a Polylang. **Corregido.** |
| 6 | Media | Multilingüe | La política de cookies en inglés muestra la fecha "27 de September de 2026". | Encabezado de /cookie-policy/. | Ajustes > Generales > Formato de fecha: "F j, Y" (o personalizado por idioma). **Pendiente del dueño.** |
| 7 | Baja | Chat | El chat no decía en la política que es automatizado ni advertía sobre datos sensibles. | Política anterior. | Se agregó en la sección de proveedores (n8n). **Corregido.** |
| 8 | Baja | Banner | "Accept" tiene fondo oscuro y "Deny" fondo claro; mismo tamaño y misma capa. | Escáner: estilos de los botones. | Aceptable. Si se quiere el criterio más estricto de CNIL, usar el mismo estilo en ambos. |
| 9 | Baja | Política de privacidad | Sin dirección postal. | Texto de la política. | Solo es obligatoria si se envían emails comerciales (CAN-SPAM) o para el aviso mexicano. Ver preguntas. |

## Correcciones aplicadas (PR #20)
- Política de privacidad EN y ES:
  - proveedores reales, bases legales, transferencias, conservación, derechos, menores;
  - Jetpack Stats y los pings sin cookies;
  - aviso de que el chat es automatizado.
- Aviso de privacidad junto al formulario de contacto (EN y ES).
- Banner: traducción de 2 textos.
- Artículos de métricas: la cifra de G2 corregida.

## Preguntas para el dueño
1. ¿Se envían o se enviarán emails comerciales (newsletter)? Si es así, hace falta una dirección postal en cada email y conviene ponerla en la política.
2. ¿Los píxeles de Meta y OpenAI se usan para crear audiencias (retargeting) o solo para medir conversiones? Define la respuesta del punto 3.
3. ¿Cuánto tiempo se guardan los mensajes del formulario y del chat (n8n)? Hoy la política usa un criterio general, sin plazo exacto.
4. ¿Hay clientes o campañas dirigidas a México? Si es así, conviene un aviso de privacidad en formato mexicano (integral y simplificado).
5. Si el negocio crece sobre los umbrales de la CCPA (USD 26,6 M de ingresos o 100.000 consumidores de California), habrá que agregar "Do Not Sell or Share" y respetar GPC.

## Aviso
Revisión técnica y de buenas prácticas; no reemplaza asesoría legal.
