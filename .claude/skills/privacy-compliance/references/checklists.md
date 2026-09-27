# Checklists por componente

Marca cada punto: cumple / no cumple / no aplica / no verificable, con evidencia (URL, captura, salida del escáner, texto citado). Entre paréntesis: marco que lo exige principalmente.

## Índice
1. Política de privacidad
2. Política de cookies
3. Banner y Consent Mode
4. Etiquetas y píxeles (GTM)
5. Formularios, email y SMS
6. Chat con IA, asistentes y automatizaciones
7. Sitios multilingües
8. Menores
9. Pie de página y accesibilidad de la información

---

## 1. Política de privacidad
- [ ] Existe, enlazada desde todas las páginas (pie) y desde cada formulario. (Todos)
- [ ] Identifica al responsable: razón social, país, forma de contacto; dirección postal si la ley la exige o si se hace email marketing. (GDPR, LFPDPPP, CAN-SPAM)
- [ ] Lista los datos que se recogen por canal: formulario, chat, email, navegación, píxeles. (Todos)
- [ ] Finalidades concretas y, para cada una, su base legal (consentimiento, contrato o medidas precontractuales, interés legítimo, obligación legal). (GDPR, LGPD, 21.719)
- [ ] Distingue finalidades que requieren consentimiento de las que no. (LFPDPPP)
- [ ] Nombra a los proveedores o categorías de destinatarios, coherente con lo que carga el sitio: hosting, formularios, analítica, publicidad, chat, CRM, email. (GDPR, CCPA)
- [ ] Transferencias internacionales y garantías. (GDPR, LGPD, Ley 25.326)
- [ ] Plazo de conservación o criterios para definirlo. (GDPR, CCPA)
- [ ] Derechos del titular y cómo ejercerlos (canal, plazo de respuesta), incluido retirar el consentimiento y reclamar ante la autoridad. (Todos)
- [ ] "Venta" o "sharing" de datos: declarado con honestidad; si hay publicidad con retargeting y aplica CCPA, enlace "Do Not Sell or Share" y respeto de GPC. (CCPA y estados)
- [ ] Decisiones automatizadas o perfiles: declarados si existen. (GDPR, 21.719)
- [ ] Seguridad: medidas en términos generales, sin prometer seguridad absoluta. (Todos)
- [ ] Menores: declaración de edad mínima. (COPPA, GDPR)
- [ ] Fecha de vigencia o última actualización, reciente. (CCPA exige revisión anual)
- [ ] Lo que dice es cierto según la evidencia técnica (sin "no usamos cookies" si hay cookies, sin "no hacemos perfiles" si hay retargeting). (Todos)

## 2. Política de cookies
- [ ] Existe, enlazada desde el banner y el pie. (ePrivacy)
- [ ] Explica qué son las cookies y tecnologías similares (píxeles, almacenamiento local). (ePrivacy)
- [ ] Clasifica por categoría (necesarias, preferencias, estadísticas, marketing). (ePrivacy)
- [ ] Lista cada servicio y sus cookies con proveedor, finalidad y duración; coincide con el escáner. (ePrivacy)
- [ ] Explica cómo cambiar o retirar el consentimiento (enlace "Gestionar consentimiento"). (ePrivacy, GDPR)
- [ ] Si se usa Consent Mode avanzado, explica los pings sin cookies. (Google EU User Consent Policy)
- [ ] Coherente con lo que responde el plugin de cookies (por ejemplo, si dice que la publicidad no crea perfiles, verificar que sea cierto).
- [ ] Fechas y textos en el idioma de la página.

## 3. Banner y Consent Mode
- [ ] Aparece en la primera visita, antes de cualquier etiqueta no esencial. (ePrivacy)
- [ ] "Aceptar", "Rechazar" y "Preferencias" en la primera capa, con el mismo peso visual. (EDPB, CNIL, AEPD)
- [ ] Sin casillas premarcadas; categorías granulares. (ePrivacy)
- [ ] Enlace permanente para cambiar la elección ("Gestionar consentimiento"). (GDPR art. 7.3)
- [ ] Consent Mode v2: `default` con las 4 señales en `denied` (en regiones que lo requieren) antes de cargar etiquetas; `update` tras la elección. (Google)
- [ ] La elección persiste entre páginas y visitas, y se puede revocar.
- [ ] El banner no tapa el enlace a las políticas ni bloquea la navegación de forma abusiva.
- [ ] Respeta la señal GPC si aplica CCPA u otros estados. (CCPA y estados)
- [ ] Textos completos y en el idioma de la página. (Transparencia)

## 4. Etiquetas y píxeles (GTM)
- [ ] Ninguna etiqueta de publicidad ni de analítica con cookies se dispara antes del consentimiento ni tras rechazar (verificar con el escáner o Tag Assistant). (ePrivacy)
- [ ] Cada etiqueta tiene "consentimiento adicional requerido" o un disparador condicionado a la categoría correcta.
- [ ] Eventos de conversión (formulario enviado, lead) solo se envían con consentimiento de marketing.
- [ ] No se envían datos personales en claro (email, teléfono, nombre) a plataformas publicitarias salvo con hashing y consentimiento (Enhanced Conversions, Advanced Matching).
- [ ] Todo lo que carga GTM aparece en ambas políticas.

## 5. Formularios, email y SMS
- [ ] Solo se piden los datos necesarios (minimización). (GDPR)
- [ ] Aviso junto al botón de envío con enlace a la política ("Al enviar aceptas..." o "Tratamos tus datos para responder..."). (GDPR, CCPA aviso en la recolección, LFPDPPP aviso simplificado)
- [ ] Casilla separada y no premarcada para newsletter o marketing, si existe. (GDPR, LGPD, Ley 1581)
- [ ] Emails comerciales: remitente real, asunto no engañoso, dirección postal física, baja en un clic procesada en 10 días hábiles. (CAN-SPAM)
- [ ] SMS o llamadas de marketing: consentimiento previo expreso por escrito con divulgación clara, STOP para darse de baja, registro del consentimiento. (TCPA, FTSA)
- [ ] Copias del formulario solo a buzones controlados por la empresa; si hay copias a cuentas personales, documentarlo o eliminarlo.
- [ ] Protección antispam que no dependa de rastreo sin informar (si se usa reCAPTCHA, informarlo).

## 6. Chat con IA, asistentes y automatizaciones
- [ ] El usuario sabe que habla con un asistente automatizado y no con una persona. (Transparencia; leyes de bots como la de California SB 1001 para fines comerciales)
- [ ] La política nombra al proveedor que procesa los mensajes (plataforma de chat, orquestador como n8n, proveedor del modelo de IA) y la finalidad.
- [ ] Se informa si las conversaciones se guardan, por cuánto tiempo y si se usan para entrenar modelos (normalmente no deberían con APIs empresariales; confirmarlo).
- [ ] No se piden datos sensibles por el chat; hay advertencia si el chat puede recibirlos.
- [ ] Canales externos (Telegram, WhatsApp, Messenger): se informa que aplican también sus términos.

## 7. Sitios multilingües
- [ ] Cada idioma tiene su política completa y equivalente (no solo traducciones parciales). (Transparencia)
- [ ] El banner y las políticas se muestran en el idioma de la página, sin frases mezcladas.
- [ ] Los enlaces del pie apuntan a la versión del mismo idioma.

## 8. Menores
- [ ] Declaración de que el sitio no está dirigido a menores (13 en EE. UU., 16 como referencia en la UE) si es B2B o adulto. (COPPA, GDPR art. 8)
- [ ] Si puede haber menores: consentimiento parental verificable. (COPPA)

## 9. Pie de página y accesibilidad de la información
- [ ] Enlaces a privacidad, cookies y "Gestionar consentimiento" visibles en todas las páginas.
- [ ] Enlaces legales responden 200 (sin 404 ni redirecciones rotas).
- [ ] Nombre legal de la empresa en el pie coherente con la política.
- [ ] Políticas legibles: títulos claros, índice si son largas, lenguaje simple.
