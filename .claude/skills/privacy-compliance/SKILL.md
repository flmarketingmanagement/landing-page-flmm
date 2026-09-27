---
name: privacy-compliance
description: Auditoría de privacidad y cumplimiento legal de sitios web y landing pages. Revisa política de privacidad, política de cookies, banner de consentimiento (Complianz, Cookiebot, OneTrust, etc.), Google Consent Mode v2, GTM, píxeles (Meta, Google Ads, LinkedIn, TikTok, OpenAI), formularios, chat con IA, email marketing y SMS, frente a GDPR/ePrivacy, UK GDPR, CCPA/CPRA y leyes estatales de EE. UU. (incluida Florida FDBR y FTSA), COPPA, CAN-SPAM, TCPA, LGPD (Brasil), Chile (Ley 19.628 y 21.719), México (LFPDPPP 2025), Colombia, Argentina y Perú. Entrega una matriz de hallazgos por severidad con correcciones concretas y textos listos para pegar. Úsala siempre que el usuario pida revisar, redactar o actualizar una política de privacidad o de cookies, configurar o auditar un banner de cookies o Consent Mode, preguntar si un sitio "cumple" con alguna ley de datos, agregar un píxel o un chat con IA, o lanzar formularios, newsletters o SMS, aunque no mencione "compliance" ni "legal".
---

# Auditoría de privacidad y cumplimiento

Esta skill convierte una revisión legal difusa en un proceso verificable: primero se reúne evidencia real de lo que hace el sitio (qué se carga, qué cookies se crean, cuándo), después se compara con lo que dicen las políticas y con lo que exige cada ley aplicable, y al final se entregan hallazgos priorizados con su corrección.

La idea central: **la política debe describir lo que el sitio hace de verdad, y el sitio debe hacer lo que la política promete.** La mayoría de los problemas reales son desalineaciones entre esas dos cosas (un píxel que no aparece en la política, un banner que dice "rechazar" pero el píxel igual se dispara, una política de cookies que no lista los servicios).

No es asesoría legal. Deja claro en el informe que es una revisión técnica y de buenas prácticas, y recomienda revisión de un abogado para decisiones de riesgo alto. Aun así, sé concreto: el valor está en los hallazgos verificables y en las correcciones listas para aplicar.

## Flujo de trabajo

### 1. Alcance: a quién aplica cada ley

Antes de auditar, define qué marcos aplican. Pregunta o deduce del sitio:

- **Dónde está la empresa** y **dónde están los visitantes/clientes** (el idioma, la moneda, los países mencionados y los datos de Search Console o Analytics ayudan).
- **Qué datos se recogen**: formularios, chat, newsletter, SMS, pagos, cuentas de usuario, datos sensibles (salud, finanzas, menores).
- **Tamaño del negocio**: varias leyes de EE. UU. solo aplican sobre umbrales de ingresos o de volumen de datos.

Lee `references/frameworks.md` para decidir qué marcos aplican y qué exige cada uno. Si el sitio apunta a la UE o usa un banner configurado para la UE, aplica GDPR/ePrivacy como estándar más exigente: cumplirlo cubre la mayor parte de las demás leyes.

### 2. Evidencia técnica (no te quedes solo con el texto)

Ejecuta el escáner incluido cuando tengas navegador (Playwright/Chromium):

```bash
node .claude/skills/privacy-compliance/scripts/scan_consent.js https://ejemplo.com [--out informe.json]
```

El script abre la página tres veces (sin interactuar, tras "Aceptar" y tras "Rechazar") y registra: cookies creadas, solicitudes a dominios de seguimiento conocidos, estado de Google Consent Mode (`gtag('consent', ...)` en el dataLayer) y si el banner muestra "Rechazar" al mismo nivel que "Aceptar". Lee su salida como evidencia; lo importante es:

- **Antes del consentimiento** no deberían cargarse píxeles de publicidad ni cookies de analítica (salvo Consent Mode avanzado sin cookies, que se documenta como tal).
- **Tras rechazar**, lo mismo.
- **Tras aceptar**, deberían cargarse, y cada uno debe estar listado en la política de cookies.

Si no hay navegador, usa `curl` sobre el HTML para listar scripts de terceros y revisa el contenedor de GTM si el usuario puede exportarlo. Indica en el informe qué no pudiste verificar.

Reúne también: el texto de la política de privacidad y de cookies (cada idioma), los formularios (campos, casillas, textos junto al botón), el pie de página (enlaces legales, "Gestionar consentimiento") y cualquier chat o widget.

### 3. Revisión contra los checklists

Usa `references/checklists.md`. Tiene listas para: política de privacidad, política de cookies, banner y Consent Mode, formularios y email/SMS, chat con IA y automatizaciones, sitios multilingües, y menores. Marca cada punto como cumple / no cumple / no aplica / no verificable, con la evidencia.

Presta atención especial a las desalineaciones:
- Cada servicio que carga el sitio (del escáner y del HTML) debe aparecer en la política de privacidad (como proveedor) y en la de cookies (con sus cookies).
- Lo que la política dice ("no hacemos perfiles", "no vendemos datos", "solo con consentimiento") debe ser cierto según la evidencia.
- Las versiones en cada idioma deben decir lo mismo y estar completas en su idioma (textos del banner mezclados son un hallazgo).

### 4. Hallazgos y severidad

Clasifica cada hallazgo:

| Severidad | Criterio | Ejemplos |
|---|---|---|
| **Crítica** | Tratamiento sin base legal o que contradice lo informado; riesgo de sanción directa | Píxel de publicidad que se dispara antes del consentimiento o tras rechazar; política inexistente; datos de menores sin control |
| **Alta** | Falta un elemento obligatorio de la ley aplicable | Proveedores o finalidades no informados; sin forma de retirar el consentimiento; sin "Do Not Sell/Share" cuando aplica CCPA; sin dirección postal en emails comerciales |
| **Media** | Información incompleta, imprecisa o difícil de usar | Plazos de conservación vagos; banner sin "Rechazar" en la primera capa; enlaces legales rotos; textos en el idioma equivocado |
| **Baja** | Buenas prácticas y claridad | Fecha de vigencia desactualizada; redacción confusa; falta de índice |

### 5. Correcciones

Para cada hallazgo da la corrección concreta: el texto exacto a agregar (usa `references/templates.md`, adaptado a los proveedores reales del sitio, sin inventar datos como direcciones, plazos o nombres de delegados), el ajuste de configuración (por ejemplo, el paso del asistente del plugin de cookies o el disparador de GTM) o el cambio de código. Si el sitio se genera desde código (theme, migración, CMS headless), edita el origen, no solo el CMS.

No inventes: si no sabes la dirección postal, el plazo de conservación exacto o si la empresa "vende" datos, deja un marcador visible (`[CONFIRMAR: ...]`) y agrégalo a una lista de preguntas para el dueño.

## Formato del informe

Usa esta estructura, en el idioma del usuario:

```
# Auditoría de privacidad: [sitio] ([fecha])

## Resumen
[3 a 5 líneas: estado general, marcos aplicados, hallazgos por severidad (N críticos, N altos...)]

## Alcance y evidencia
- Marcos: [...] y por qué
- Páginas revisadas: [...]
- Evidencia técnica: [escáner sí/no, qué se verificó y qué no]

## Hallazgos
| # | Severidad | Área | Hallazgo | Evidencia | Corrección |
|---|---|---|---|---|---|

## Correcciones aplicadas (si se editó código o contenido)

## Preguntas para el dueño
[datos que no se pueden inventar: dirección, plazos, venta de datos, DPO, etc.]

## Aviso
Revisión técnica y de buenas prácticas; no reemplaza asesoría legal.
```

## Recursos

- `references/frameworks.md`: qué exige cada ley, umbrales de aplicación y diferencias clave. Léelo en el paso 1.
- `references/checklists.md`: listas de verificación por componente. Léelo en el paso 3.
- `references/templates.md`: cláusulas EN/ES listas para adaptar. Léelo en el paso 5.
- `scripts/scan_consent.js`: escáner de consentimiento con Playwright. Úsalo en el paso 2.

Las leyes cambian. Si una fecha o umbral es decisivo para la conclusión, verifícalo con una búsqueda web y cita la fuente en el informe.
