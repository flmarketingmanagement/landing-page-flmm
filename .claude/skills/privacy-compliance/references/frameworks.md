# Marcos legales: qué exige cada uno

Resumen operativo para decidir qué aplica y qué revisar. Última revisión de datos: septiembre de 2026. Si un umbral o una fecha decide la conclusión, verifícalo con una búsqueda web y cita la fuente.

## Índice
1. Cómo decidir qué aplica
2. Unión Europea: GDPR y ePrivacy (y Reino Unido)
3. Estados Unidos: leyes estatales de privacidad (CCPA/CPRA y otras, incluida Florida)
4. Estados Unidos: COPPA, CAN-SPAM, TCPA y Florida FTSA
5. Latinoamérica: Brasil, Chile, México, Colombia, Argentina, Perú
6. Plataformas: Google (Consent Mode v2, EU User Consent Policy) y Meta

---

## 1. Cómo decidir qué aplica

| Pregunta | Si la respuesta es sí |
|---|---|
| ¿Hay visitantes o clientes en la UE/EEE o Reino Unido, o se ofrecen servicios allí? | GDPR + ePrivacy (UK GDPR + PECR). Banner opt-in obligatorio para cookies no esenciales. |
| ¿La empresa supera umbrales de leyes estatales de EE. UU.? | CCPA/CPRA u otras (ver sección 3). Si no, igual conviene un aviso claro y respetar opt-outs. |
| ¿Envía emails comerciales a EE. UU.? | CAN-SPAM. |
| ¿Envía SMS o hace llamadas de marketing a EE. UU.? | TCPA (y FTSA si hay números de Florida). |
| ¿Puede recoger datos de menores de 13 (EE. UU.) o 16 (UE)? | COPPA / consentimiento parental GDPR. |
| ¿Trata datos de personas en Brasil, Chile, México, Colombia, Argentina o Perú? | La ley local correspondiente (sección 5). |
| ¿Usa Google Ads/Analytics con usuarios del EEE? | Consent Mode v2 y EU User Consent Policy de Google. |

Regla práctica: si el sitio cumple GDPR (consentimiento previo, información completa, derechos, proveedores y transferencias), queda cubierta la mayor parte de lo que piden las demás leyes. Lo que GDPR no cubre y hay que agregar: el "Do Not Sell or Share" y GPC de EE. UU., CAN-SPAM, TCPA y los requisitos formales de cada país latinoamericano (por ejemplo, el "aviso de privacidad" mexicano).

---

## 2. Unión Europea: GDPR y ePrivacy

**Aplica** a quien trata datos de personas en la UE/EEE, si ofrece bienes o servicios allí o monitorea su comportamiento (analítica y publicidad cuentan como monitoreo). Reino Unido: UK GDPR y PECR, prácticamente iguales.

**Cookies (ePrivacy, art. 5.3):** consentimiento previo, libre, específico, informado e inequívoco para toda cookie o tecnología de seguimiento que no sea estrictamente necesaria. Implica:
- Nada de analítica ni publicidad antes de aceptar (Consent Mode avanzado envía pings sin cookies; documéntalo en la política).
- "Rechazar" tan fácil como "Aceptar" (misma capa, mismo peso visual). Varias autoridades (CNIL, AEPD, EDPB) sancionan la ausencia de rechazo en la primera capa.
- Sin casillas premarcadas; granularidad por finalidad.
- Retirar el consentimiento tan fácil como darlo (enlace permanente "Gestionar consentimiento").
- Registro del consentimiento (fecha, versión, categorías).

**Información obligatoria (arts. 13 y 14):** identidad y contacto del responsable; contacto del DPO si existe; finalidades y base legal de cada una; intereses legítimos si se usan; destinatarios o categorías de destinatarios; transferencias internacionales y garantías (cláusulas contractuales tipo, Data Privacy Framework UE-EE. UU.); plazo de conservación o criterios; derechos (acceso, rectificación, supresión, oposición, limitación, portabilidad, retirar consentimiento); derecho a reclamar ante una autoridad; si el dato es obligatorio y consecuencias; decisiones automatizadas y perfiles, si existen.

**Representante en la UE (art. 27):** si la empresa no tiene establecimiento en la UE y el tratamiento no es ocasional, debe designar representante. Para empresas pequeñas fuera de la UE con tratamiento ocasional suele no aplicar; márcalo como pregunta para el dueño.

**Encargados (art. 28):** contrato con cada proveedor que trata datos por cuenta de la empresa (hosting, formularios, CRM, email, chat, automatizaciones). Los grandes proveedores lo ofrecen como DPA en sus términos.

**Sanciones:** hasta 20 M EUR o 4 % de la facturación global.

---

## 3. Estados Unidos: leyes estatales de privacidad

No hay ley federal general. Más de 15 estados tienen leyes integrales; la mayoría aplica solo sobre umbrales.

**California (CCPA/CPRA)** aplica a empresas con fines de lucro que hacen negocios en California y cumplen al menos uno:
- Ingresos brutos anuales globales sobre USD 26.625.000 (umbral ajustado por inflación desde 2025; se ajusta cada año impar).
- Compran, venden o comparten datos de 100.000 o más consumidores u hogares de California.
- Obtienen 50 % o más de sus ingresos de vender o compartir datos personales.

Si aplica: aviso en el momento de la recolección; política con categorías de datos, fuentes, finalidades, terceros, conservación y derechos (saber, eliminar, corregir, oponerse a la venta o al "sharing" para publicidad conductual cross-context, limitar datos sensibles); enlace "Do Not Sell or Share My Personal Information" si hay venta o sharing (los píxeles de publicidad con retargeting suelen contar como "sharing"); respetar la señal Global Privacy Control (GPC); dos métodos para ejercer derechos; actualización anual de la política.

**Otros estados** (Virginia, Colorado, Connecticut, Utah, Texas, Oregon, Montana, Iowa, Delaware, New Hampshire, New Jersey, Tennessee, Minnesota, Maryland, Indiana, Kentucky, Rhode Island, Nebraska, entre otros): umbrales típicos de 100.000 consumidores del estado (o 25.000 si hay venta de datos); Texas y Nebraska no tienen umbral de volumen pero excluyen pequeñas empresas según la definición de la SBA. Exigen: aviso de privacidad, derechos de acceso, corrección, eliminación, portabilidad y opt-out de publicidad dirigida, venta y perfilamiento; consentimiento opt-in para datos sensibles; varios exigen reconocer GPC (Colorado, Connecticut, Texas, Oregon, Montana, New Jersey, entre otros).

**Florida Digital Bill of Rights (FDBR, SB 262, vigente desde el 1 de julio de 2024):** su parte general solo aplica a "controllers" con ingresos globales de más de USD 1.000 millones que además cumplan uno de: 50 % de ingresos por publicidad online, operar un parlante inteligente con asistente de voz, u operar una tienda de apps con 250.000 aplicaciones o más. Para la gran mayoría de empresas de Florida **no aplica**; dilo explícitamente en el informe. Florida sí tiene reglas de notificación de brechas (Fla. Stat. 501.171) que aplican a cualquier empresa que maneje datos personales de residentes.

**Regla práctica para pymes de EE. UU. bajo umbral:** aunque no apliquen, conviene publicar qué datos se recogen, con quién se comparten, no vender datos y ofrecer un correo para solicitudes; los clientes corporativos y las plataformas publicitarias (Google, Meta) igual exigen una política clara.

---

## 4. Estados Unidos: COPPA, CAN-SPAM, TCPA y Florida FTSA

**COPPA:** sitios dirigidos a menores de 13 años o que saben que recogen datos de ellos necesitan consentimiento verificable de los padres. Un sitio B2B debe declarar que no está dirigido a menores y no recoger sus datos a sabiendas.

**CAN-SPAM (email comercial):** no usar encabezados ni asuntos engañosos; identificar el mensaje como publicidad cuando corresponda; incluir una **dirección postal física válida** del remitente; ofrecer una forma clara de darse de baja y procesarla en un máximo de 10 días hábiles; responsabilidad también por lo que envían terceros en nombre de la empresa. No exige opt-in previo, pero GDPR y las leyes latinoamericanas sí.

**TCPA (llamadas y SMS de marketing):** para mensajes o llamadas de marketing con sistemas automatizados o voces pregrabadas a celulares se requiere **consentimiento previo expreso por escrito** (firma electrónica válida, con una divulgación clara y sin condicionar la compra); respetar el registro Do Not Call; horarios permitidos; opt-out con palabras como STOP. Multas por mensaje: USD 500 a 1.500.

**Florida Telephone Solicitation Act (FTSA):** versión estatal del TCPA, aplicable a llamadas y mensajes de ventas a números de Florida; tras la reforma de 2023 incluye un plazo de 15 días para corregir después de un aviso de opt-out ignorado. Mismo criterio: consentimiento previo expreso por escrito.

---

## 5. Latinoamérica

**Brasil, LGPD (Lei 13.709/2018):** muy similar a GDPR. Bases legales (consentimiento, contrato, interés legítimo, etc.); política con finalidad, forma y duración del tratamiento, identificación y contacto del controlador, uso compartido, derechos del titular; nombrar un **encarregado (DPO)** y publicar su contacto (la ANPD flexibilizó este requisito para agentes de pequeño porte); cookies no esenciales con consentimiento según la guía de la ANPD. Sanciones hasta 2 % de la facturación en Brasil, con tope de 50 M BRL por infracción.

**Chile:** la Ley 19.628 (1999) sigue vigente hasta que entre en vigor la **Ley 21.719**, publicada el 13 de diciembre de 2024 y vigente desde el **1 de diciembre de 2026**, que crea la Agencia de Protección de Datos Personales. La 21.719 sigue el modelo GDPR: bases de licitud, deber de información, derechos ARCO más portabilidad y oposición a decisiones automatizadas, deber de seguridad y de notificación de brechas, modelo de prevención de infracciones voluntario. Multas de hasta 20.000 UTM o, para reincidencia grave, hasta 4 % de los ingresos anuales.

**México:** nueva **Ley Federal de Protección de Datos Personales en Posesión de los Particulares**, publicada en el DOF el 20 de marzo de 2025 y vigente desde el 21 de marzo de 2025. La autoridad pasó del INAI a la Secretaría Anticorrupción y Buen Gobierno. Exige un **aviso de privacidad** (integral y simplificado) que identifique al responsable, los datos tratados (señalando los sensibles), las finalidades distinguiendo las que requieren consentimiento de las que no, los mecanismos para ejercer derechos ARCO y para revocar el consentimiento, y el uso de cookies y tecnologías similares.

**Colombia (Ley 1581 de 2012 y Decreto 1377 de 2013):** autorización previa, expresa e informada del titular; **política de tratamiento de la información** con responsable, finalidades, derechos, área para peticiones y procedimiento; registro de bases de datos ante la SIC si se cumplen los umbrales de activos.

**Argentina (Ley 25.326):** consentimiento libre, expreso e informado; información sobre finalidad, destinatarios, responsable y derechos; inscripción de bases de datos ante la AAIP; transferencias internacionales solo a países adecuados o con garantías.

**Perú (Ley 29733 y su reglamento de 2024):** consentimiento previo, informado, expreso e inequívoco; política de privacidad; registro de bancos de datos ante la Autoridad Nacional de Protección de Datos Personales; el nuevo reglamento agrega oficial de datos para ciertos casos y notificación de incidentes.

---

## 6. Plataformas

**Google Consent Mode v2:** desde marzo de 2024 Google exige, para usuarios del EEE y Reino Unido, enviar las señales `ad_storage`, `analytics_storage`, `ad_user_data` y `ad_personalization`, con valor por defecto `denied` antes del consentimiento y actualización tras la elección. Sin esto se pierden audiencias y conversiones en Google Ads. Modo básico: las etiquetas no se cargan hasta el consentimiento. Modo avanzado: se envían pings sin cookies; debe explicarse en la política de cookies. La EU User Consent Policy de Google obliga a obtener consentimiento y a informar el uso de datos por Google (enlazar "Cómo usa Google los datos": https://policies.google.com/technologies/partner-sites).

**Meta (Pixel y Conversions API):** los Términos de Business Tools exigen aviso claro del uso de cookies y píxeles y consentimiento donde la ley lo pida; Conversions API envía datos desde el servidor y también debe respetar la elección del usuario.

**Otros píxeles publicitarios** (LinkedIn Insight, TikTok, OpenAI, Microsoft Clarity, Hotjar): mismo tratamiento que Meta: categoría marketing o estadística, solo con consentimiento, listados en ambas políticas.
