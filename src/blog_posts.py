"""Artículos nuevos del blog (EN y ES). Genera theme/flmm-studio/inc/migrate/blog-posts.json.

Bloques: ["p", html], ["h2", texto], ["h3", texto], ["ul", [items]], ["ol", [items]].
Enlaces internos con marcadores que el migrador reemplaza por la URL del idioma:
{seo-aeo}, {florida}, {post:clave}.
"""
import json, os, re

POSTS = []

# ---------------------------------------------------------------------------
# 1. SEO vs AEO vs GEO
# ---------------------------------------------------------------------------
POSTS.append({
    'key': 'seo-aeo-geo',
    'publish_gmt': '2026-09-29 13:00:00',
    'cat': 'aeo',
    'image': 'seo-aeo',
    'en': {
        'slug': 'seo-vs-aeo-vs-geo',
        'title': 'SEO vs AEO vs GEO: Differences and How to Apply Them by Industry',
        'seo': ['SEO vs AEO vs GEO: Differences and Uses by Industry',
                'SEO, AEO and GEO explained: what each one optimizes, how they overlap and how to apply them in SaaS, e-commerce, local services and professional firms.',
                'seo vs aeo vs geo'],
        'tldr': 'SEO gets you ranked on Google, AEO gets your content quoted as a direct answer and GEO gets your brand mentioned by generative AI. They share one foundation: useful content, a clean technical structure and a trustworthy brand. What changes by industry is where you put the effort first.',
        'intro': 'SEO, AEO and GEO are three ways to be found online. SEO (Search Engine Optimization) improves your ranking in search results. AEO (Answer Engine Optimization) structures content so assistants can quote it as an answer. GEO (Generative Engine Optimization) works on how AI models perceive and mention your brand when they write a response.',
        'body': [
            ['h2', 'What SEO, AEO and GEO optimize'],
            ['ul', [
                '<strong>SEO</strong>: rankings and clicks from Google and other search engines. It works on keywords, technical health, content quality and links.',
                '<strong>AEO</strong>: being the answer. It works on direct, well-structured responses, FAQs and structured data, so assistants and AI Overviews can quote a specific passage.',
                '<strong>GEO</strong>: being recommended. It works on the signals generative models use to describe a category: mentions across the web, reviews, consistent facts and topical authority.',
            ]],
            ['p', 'In practice the three overlap. A page that ranks well, answers clearly and belongs to a brand that is mentioned elsewhere has the best chance of appearing both in classic results and in AI answers.'],
            ['h2', 'How they fit together'],
            ['p', 'Think of them as layers. SEO is the base: if search engines cannot crawl, index and understand your site, AI tools that rely on those indexes will not find it either. AEO sits on top of SEO and shapes your content into answers. GEO extends beyond your website to the reputation that models learn from.'],
            ['p', 'That is why we do not treat them as separate projects. The same research tells you which questions your buyers ask, which pages should answer them and which external sources shape how your category is described.'],
            ['h2', 'How to apply SEO, AEO and GEO by industry'],
            ['h3', 'SaaS and B2B software'],
            ['p', 'Buyers compare tools, ask for alternatives and check integrations. Prioritize comparison pages, use-case pages and clear documentation (AEO), plus presence on review sites and industry roundups that assistants tend to cite (GEO). Keep pricing, features and integrations consistent everywhere. We go deeper in <a href="{post:aeo-saas}">AEO for SaaS</a>.'],
            ['h3', 'E-commerce'],
            ['p', 'Most revenue still starts with product and category searches, so technical SEO and product data come first. Add buying guides and FAQs that answer questions about sizes, materials, shipping and returns. For GEO, reviews and mentions in comparison articles influence which brands assistants suggest.'],
            ['h3', 'Local services'],
            ['p', 'For clinics, contractors, law firms or local agencies, start with your Google Business Profile, local pages and reviews. Then answer the questions people ask before hiring, such as prices, timelines and process, in plain language. Consistent name, address and phone data across directories supports GEO.'],
            ['h3', 'Professional and regulated services'],
            ['p', 'In finance, health or legal services, trust signals matter most. Show who wrote each piece and why they are qualified, cite your sources and keep content up to date. AI tools are more cautious with these topics, so clear and verifiable information is what gets referenced.'],
            ['h2', 'Where to start'],
            ['ol', [
                'Fix the technical base: indexing, speed, internal links and structured data.',
                'List the real questions your customers ask in sales calls, chats and emails.',
                'Answer each one on the right page, with a short direct answer in the first lines and the detail below.',
                'Strengthen your brand outside your site: reviews, directories, partners and industry media.',
                'Measure both worlds: rankings and clicks in Search Console, and mentions of your brand in AI answers for your key questions.',
            ]],
            ['p', 'If you want a team to run this for you, our <a href="{seo-aeo}">AEO and SEO services</a> cover the full process. For Florida businesses, see our <a href="{florida}">performance marketing agency in Florida</a>.'],
        ],
        'faq_title': 'Frequently asked questions',
        'faq': [
            ['Is GEO replacing SEO?', 'No. Most generative AI tools still rely on search indexes and web content to build their answers, so a site that is hard to crawl or understand is also hard for AI to use. GEO adds a layer on top of SEO: it focuses on how your brand is described and recommended, not only on rankings.'],
            ['What is the difference between AEO and GEO?', 'AEO focuses on your own content: structuring pages so assistants can quote a precise answer. GEO looks at the wider picture of how generative models perceive your brand, including mentions on other websites, reviews and consistent facts. Both aim for visibility in AI answers, so most strategies combine them.'],
            ['Which one should a small business start with?', 'Start with SEO basics and your Google Business Profile, because they support everything else. Then add AEO by answering the most common customer questions clearly on your service pages. GEO comes naturally as you collect reviews and get mentioned in local directories, partner sites and industry media.'],
        ],
        'takeaways': [
            'SEO is the foundation: without it, AEO and GEO have little to work with.',
            'AEO turns your pages into clear answers that assistants can quote.',
            'GEO works on how AI models describe and recommend your brand beyond your site.',
            'The right order depends on your industry and on how your buyers search.',
        ],
    },
    'es': {
        'slug': 'seo-aeo-geo-diferencias',
        'title': 'SEO vs AEO vs GEO: diferencias y cómo aplicarlos según tu industria',
        'seo': ['SEO vs AEO vs GEO: diferencias y usos por industria',
                'SEO, AEO y GEO explicados: qué optimiza cada uno, en qué se cruzan y cómo aplicarlos en SaaS, e-commerce, servicios locales y servicios profesionales.',
                'SEO vs AEO vs GEO'],
        'tldr': 'El SEO te posiciona en Google, el AEO hace que tu contenido sea citado como respuesta directa y el GEO logra que la IA generativa mencione tu marca. Comparten una misma base: contenido útil, una estructura técnica limpia y una marca confiable. Lo que cambia según la industria es por dónde empezar.',
        'intro': 'SEO, AEO y GEO son tres formas de ser encontrado en internet. El SEO (Search Engine Optimization) mejora tu posición en los resultados de búsqueda. El AEO (Answer Engine Optimization) estructura el contenido para que los asistentes lo citen como respuesta. El GEO (Generative Engine Optimization) trabaja en cómo los modelos de IA perciben y mencionan tu marca.',
        'body': [
            ['h2', 'Qué optimizan el SEO, el AEO y el GEO'],
            ['ul', [
                '<strong>SEO</strong>: posiciones y clics en Google y otros buscadores. Trabaja palabras clave, salud técnica, calidad del contenido y enlaces.',
                '<strong>AEO</strong>: ser la respuesta. Trabaja respuestas directas y bien estructuradas, preguntas frecuentes y datos estructurados, para que los asistentes y los AI Overviews citen un fragmento concreto.',
                '<strong>GEO</strong>: ser recomendado. Trabaja las señales que los modelos generativos usan para describir una categoría: menciones en la web, reseñas, datos coherentes y autoridad temática.',
            ]],
            ['p', 'En la práctica, los tres se cruzan. Una página que posiciona bien, responde con claridad y pertenece a una marca mencionada en otros sitios tiene más posibilidades de aparecer tanto en los resultados clásicos como en las respuestas de la IA.'],
            ['h2', 'Cómo se complementan'],
            ['p', 'Piénsalos como capas. El SEO es la base: si los buscadores no pueden rastrear, indexar y entender tu sitio, las herramientas de IA que dependen de esos índices tampoco lo encontrarán. El AEO se apoya en el SEO y convierte tu contenido en respuestas. El GEO va más allá de tu sitio, hacia la reputación de la que aprenden los modelos.'],
            ['p', 'Por eso no los trabajamos como proyectos separados. La misma investigación te dice qué preguntas hacen tus compradores, qué páginas deben responderlas y qué fuentes externas definen cómo se describe tu categoría.'],
            ['h2', 'Cómo aplicar SEO, AEO y GEO según tu industria'],
            ['h3', 'SaaS y software B2B'],
            ['p', 'Los compradores comparan herramientas, buscan alternativas y revisan integraciones. Prioriza páginas comparativas, páginas por caso de uso y documentación clara (AEO), además de presencia en sitios de reseñas y rankings del sector que los asistentes suelen citar (GEO). Mantén precios, funciones e integraciones coherentes en todos lados. Lo profundizamos en <a href="{post:aeo-saas}">AEO para SaaS</a>.'],
            ['h3', 'E-commerce'],
            ['p', 'La mayor parte de las ventas sigue empezando con búsquedas de productos y categorías, así que el SEO técnico y los datos de producto van primero. Suma guías de compra y preguntas frecuentes sobre tallas, materiales, envíos y devoluciones. Para el GEO, las reseñas y las menciones en artículos comparativos influyen en qué marcas sugieren los asistentes.'],
            ['h3', 'Servicios locales'],
            ['p', 'Para clínicas, contratistas, estudios jurídicos o agencias locales, empieza por tu Perfil de Empresa de Google, las páginas locales y las reseñas. Luego responde en lenguaje simple lo que la gente pregunta antes de contratar, como precios, plazos y proceso. Tener el mismo nombre, dirección y teléfono en todos los directorios ayuda al GEO.'],
            ['h3', 'Servicios profesionales y regulados'],
            ['p', 'En finanzas, salud o servicios legales, las señales de confianza son lo más importante. Muestra quién escribió cada contenido y por qué está calificado, cita tus fuentes y mantén la información al día. Las herramientas de IA son más cautelosas con estos temas, así que lo que se cita es la información clara y verificable.'],
            ['h2', 'Por dónde empezar'],
            ['ol', [
                'Ordena la base técnica: indexación, velocidad, enlaces internos y datos estructurados.',
                'Haz una lista de las preguntas reales que tus clientes hacen en llamadas de venta, chats y correos.',
                'Responde cada una en la página adecuada, con una respuesta corta en las primeras líneas y el detalle más abajo.',
                'Fortalece tu marca fuera de tu sitio: reseñas, directorios, socios y medios del sector.',
                'Mide los dos mundos: posiciones y clics en Search Console, y menciones de tu marca en las respuestas de la IA para tus preguntas clave.',
            ]],
            ['p', 'Si quieres que un equipo lo haga por ti, nuestros <a href="{seo-aeo}">servicios de AEO y SEO</a> cubren todo el proceso. Si tu empresa está en Florida, revisa nuestra <a href="{florida}">agencia de performance marketing en Florida</a>.'],
        ],
        'faq_title': 'Preguntas frecuentes',
        'faq': [
            ['¿El GEO reemplaza al SEO?', 'No. La mayoría de las herramientas de IA generativa todavía se apoya en índices de búsqueda y contenido web para armar sus respuestas, así que un sitio difícil de rastrear o de entender también es difícil de usar para la IA. El GEO suma una capa sobre el SEO: se enfoca en cómo se describe y se recomienda tu marca.'],
            ['¿Qué diferencia hay entre AEO y GEO?', 'El AEO se enfoca en tu propio contenido: estructurar páginas para que los asistentes citen una respuesta precisa. El GEO mira el panorama completo de cómo los modelos generativos perciben tu marca, incluidas las menciones en otros sitios, las reseñas y los datos coherentes. Ambos buscan visibilidad en la IA, por eso conviene combinarlos.'],
            ['¿Con cuál debería empezar una pequeña empresa?', 'Empieza por lo básico del SEO y tu Perfil de Empresa de Google, porque sostienen todo lo demás. Luego suma AEO respondiendo con claridad las preguntas más comunes de tus clientes en tus páginas de servicios. El GEO llega de forma natural cuando juntas reseñas y apareces en directorios locales, sitios de socios y medios del sector.'],
        ],
        'takeaways': [
            'El SEO es la base: sin él, el AEO y el GEO tienen poco con qué trabajar.',
            'El AEO convierte tus páginas en respuestas claras que los asistentes pueden citar.',
            'El GEO trabaja cómo los modelos de IA describen y recomiendan tu marca fuera de tu sitio.',
            'El orden correcto depende de tu industria y de cómo buscan tus compradores.',
        ],
    },
})

# ---------------------------------------------------------------------------
# 2. Qué son los servicios de AEO
# ---------------------------------------------------------------------------
POSTS.append({
    'key': 'aeo-services',
    'publish_gmt': '2026-10-06 13:00:00',
    'cat': 'aeo',
    'image': 'aeo-content',
    'en': {
        'slug': 'what-are-aeo-services',
        'title': 'What Are AEO Services? What They Include and How to Choose a Provider',
        'seo': ['What Are AEO Services and How to Choose a Provider',
                'What AEO services include, how they differ from SEO, which deliverables to expect and the questions to ask before hiring an answer engine optimization provider.',
                'aeo services'],
        'tldr': 'AEO services help your brand appear as the answer in ChatGPT, Gemini, Perplexity and Google AI Overviews. A good provider combines research, content, technical work and measurement, and builds on solid SEO instead of replacing it.',
        'intro': 'AEO services (Answer Engine Optimization) are the work of making a brand easy for AI assistants and answer engines to understand, quote and recommend. They include research on the questions buyers ask, restructuring pages into clear answers, structured data, brand signals across the web and tracking how often AI tools mention you.',
        'body': [
            ['h2', 'What AEO services include'],
            ['ul', [
                '<strong>AI visibility audit</strong>: how assistants describe your brand and your competitors today for the questions that matter to your business.',
                '<strong>Question research</strong>: the real questions buyers ask, taken from sales calls, support tickets, search data and communities.',
                '<strong>Content restructuring</strong>: pages that answer each question in the first lines and then add detail, examples and FAQs.',
                '<strong>Technical work</strong>: structured data, clean HTML, crawlable pages and files such as llms.txt that help AI tools read your site.',
                '<strong>Brand and entity signals</strong>: consistent facts about your company across your site, profiles, directories and partner sites.',
                '<strong>Measurement</strong>: tracking mentions and citations in AI answers alongside rankings and traffic.',
            ]],
            ['h2', 'How AEO services differ from SEO'],
            ['p', 'SEO aims for rankings and clicks. AEO aims for your content to be used as the answer, sometimes without a click. The work overlaps: both need technical health and useful content. The difference is in the format of the content and in what you measure.'],
            ['p', 'That is why AEO works best on top of good SEO. If search engines cannot crawl and understand your site, AI tools that depend on those indexes will struggle too. We explain the full picture in <a href="{post:seo-aeo-geo}">SEO vs AEO vs GEO</a>.'],
            ['h2', 'What deliverables to expect'],
            ['p', 'A clear AEO engagement produces tangible work, not only reports. Expect a prioritized list of questions and target pages, rewritten or new pages, structured data implemented on your site, a plan for brand mentions outside your site and a monthly view of how your presence in AI answers is changing.'],
            ['h2', 'How to choose an AEO provider'],
            ['ul', [
                'Ask how they measure AI visibility and how often they report it.',
                'Check that they also cover technical SEO, not only content.',
                'Ask for examples of pages they restructured and the reasoning behind each change.',
                'Be wary of guaranteed placements: no one controls what AI models answer.',
                'Make sure they understand your industry and the questions your buyers ask.',
            ]],
            ['p', 'If you are comparing providers, see how we approach it in our <a href="{seo-aeo}">AEO and SEO services</a>.'],
        ],
        'faq_title': 'Frequently asked questions',
        'faq': [
            ['How long do AEO services take to show results?', 'Technical fixes and restructured pages can be picked up within weeks, but a consistent presence in AI answers usually builds over months. It depends on your starting point, your competition and how often AI tools refresh their sources. That is why a good provider tracks progress every month instead of promising fixed dates.'],
            ['Can a small business benefit from AEO services?', 'Yes. Small businesses often answer niche questions better than large competitors. Clear service pages, a complete Google Business Profile, genuine reviews and a short FAQ with real customer questions are affordable steps that help assistants recommend you for local and specific searches.'],
            ['Do AEO services replace SEO?', 'No. AEO builds on SEO. Assistants and AI Overviews rely on content they can crawl and understand, so technical SEO, site structure and quality content remain the base. AEO adds the answer format, structured data and brand signals that make your content easier to quote.'],
        ],
        'takeaways': [
            'AEO services make your brand easy for AI assistants to quote and recommend.',
            'They combine research, content, technical work and measurement.',
            'Good AEO builds on SEO; it does not replace it.',
            'Choose a provider that measures AI visibility and avoids guarantees.',
        ],
    },
    'es': {
        'slug': 'que-son-los-servicios-de-aeo',
        'title': '¿Qué son los servicios de AEO? Qué incluyen y cómo elegir proveedor',
        'seo': ['Servicios de AEO: qué incluyen y cómo elegir proveedor',
                'Qué incluyen los servicios de AEO, en qué se diferencian del SEO, qué entregables esperar y qué preguntar antes de contratar un proveedor de AEO para tu marca.',
                'servicios de AEO'],
        'tldr': 'Los servicios de AEO ayudan a que tu marca aparezca como respuesta en ChatGPT, Gemini, Perplexity y los AI Overviews de Google. Un buen proveedor combina investigación, contenido, trabajo técnico y medición, y se apoya en un SEO sólido en lugar de reemplazarlo.',
        'intro': 'Los servicios de AEO (Answer Engine Optimization) son el trabajo de lograr que los asistentes de IA y los motores de respuesta entiendan, citen y recomienden una marca. Incluyen investigar las preguntas de los compradores, reestructurar páginas como respuestas claras, datos estructurados, señales de marca en la web y medir cuántas veces te menciona la IA.',
        'body': [
            ['h2', 'Qué incluyen los servicios de AEO'],
            ['ul', [
                '<strong>Auditoría de visibilidad en IA</strong>: cómo describen hoy los asistentes a tu marca y a tu competencia en las preguntas que importan para tu negocio.',
                '<strong>Investigación de preguntas</strong>: las preguntas reales de los compradores, tomadas de llamadas de venta, tickets de soporte, datos de búsqueda y comunidades.',
                '<strong>Reestructuración de contenido</strong>: páginas que responden cada pregunta en las primeras líneas y luego suman detalle, ejemplos y preguntas frecuentes.',
                '<strong>Trabajo técnico</strong>: datos estructurados, HTML limpio, páginas rastreables y archivos como llms.txt que ayudan a la IA a leer tu sitio.',
                '<strong>Señales de marca y entidad</strong>: datos coherentes sobre tu empresa en tu sitio, perfiles, directorios y sitios de socios.',
                '<strong>Medición</strong>: seguimiento de menciones y citas en las respuestas de la IA, junto con posiciones y tráfico.',
            ]],
            ['h2', 'En qué se diferencian del SEO'],
            ['p', 'El SEO busca posiciones y clics. El AEO busca que tu contenido se use como respuesta, a veces sin que haya un clic. El trabajo se cruza: ambos necesitan salud técnica y contenido útil. La diferencia está en el formato del contenido y en lo que se mide.'],
            ['p', 'Por eso el AEO funciona mejor sobre un buen SEO. Si los buscadores no pueden rastrear y entender tu sitio, a las herramientas de IA que dependen de esos índices también les costará. Lo explicamos completo en <a href="{post:seo-aeo-geo}">SEO vs AEO vs GEO</a>.'],
            ['h2', 'Qué entregables esperar'],
            ['p', 'Un buen trabajo de AEO produce resultados concretos, no solo informes. Espera una lista priorizada de preguntas y páginas objetivo, páginas reescritas o nuevas, datos estructurados implementados en tu sitio, un plan de menciones de marca fuera de tu sitio y una vista mensual de cómo cambia tu presencia en las respuestas de la IA.'],
            ['h2', 'Cómo elegir un proveedor de AEO'],
            ['ul', [
                'Pregunta cómo miden la visibilidad en la IA y cada cuánto la reportan.',
                'Revisa que también cubran el SEO técnico, no solo el contenido.',
                'Pide ejemplos de páginas que hayan reestructurado y el porqué de cada cambio.',
                'Desconfía de las ubicaciones garantizadas: nadie controla lo que responden los modelos de IA.',
                'Asegúrate de que entiendan tu industria y las preguntas de tus compradores.',
            ]],
            ['p', 'Si estás comparando proveedores, mira cómo lo trabajamos en nuestros <a href="{seo-aeo}">servicios de AEO y SEO</a>.'],
        ],
        'faq_title': 'Preguntas frecuentes',
        'faq': [
            ['¿Cuánto tardan los servicios de AEO en dar resultados?', 'Las correcciones técnicas y las páginas reestructuradas pueden notarse en semanas, pero una presencia constante en las respuestas de la IA suele construirse en meses. Depende de tu punto de partida, tu competencia y cada cuánto las herramientas de IA actualizan sus fuentes. Por eso un buen proveedor mide el avance cada mes en lugar de prometer fechas.'],
            ['¿Una pequeña empresa puede aprovechar los servicios de AEO?', 'Sí. Las pequeñas empresas suelen responder mejor que las grandes las preguntas de nicho. Páginas de servicios claras, un Perfil de Empresa de Google completo, reseñas reales y una sección breve con las preguntas de tus clientes son pasos accesibles que ayudan a que los asistentes te recomienden en búsquedas locales y específicas.'],
            ['¿Los servicios de AEO reemplazan al SEO?', 'No. El AEO se apoya en el SEO. Los asistentes y los AI Overviews usan contenido que pueden rastrear y entender, así que el SEO técnico, la estructura del sitio y el contenido de calidad siguen siendo la base. El AEO suma el formato de respuesta, los datos estructurados y las señales de marca que facilitan que te citen.'],
        ],
        'takeaways': [
            'Los servicios de AEO hacen que la IA pueda citar y recomendar tu marca con facilidad.',
            'Combinan investigación, contenido, trabajo técnico y medición.',
            'Un buen AEO se apoya en el SEO; no lo reemplaza.',
            'Elige un proveedor que mida la visibilidad en la IA y no prometa garantías.',
        ],
    },
})

# ---------------------------------------------------------------------------
# 3. Gemini y AI Overviews
# ---------------------------------------------------------------------------
POSTS.append({
    'key': 'gemini-ai-overviews',
    'publish_gmt': '2026-10-13 13:00:00',
    'cat': 'aeo',
    'image': 'ai-assistants',
    'en': {
        'slug': 'show-up-in-gemini-and-ai-overviews',
        'title': 'How to Show Up in Gemini and Google AI Overviews',
        'seo': ['How to Show Up in Gemini and Google AI Overviews',
                'How Gemini and Google AI Overviews choose their sources and the practical steps to make your content eligible: technical SEO, direct answers and trust signals.',
                'gemini aeo'],
        'tldr': 'Gemini and AI Overviews build on Google Search. There is no special tag or form to get in: you need a site Google can read, pages that answer questions directly, clear structure and signals that your brand is trustworthy.',
        'intro': 'Showing up in Gemini and Google AI Overviews means having your content selected as a source when Google’s AI writes an answer. Both draw on Google’s index, so the base is solid SEO. On top of that you need direct answers, clear structure, structured data and consistent signals that your brand is trustworthy.',
        'body': [
            ['h2', 'How Gemini and AI Overviews choose sources'],
            ['p', 'Google does not publish a formula, but its guidance for site owners is consistent: AI features use content from its index and follow the same quality principles as Search. Pages that are crawlable, helpful, well structured and backed by credible sources are the ones that tend to be selected.'],
            ['p', 'There is no extra markup or submission that guarantees inclusion. The work is the same good SEO, with more attention to answering specific questions clearly.'],
            ['h2', 'Step 1: make sure Google can read your site'],
            ['ul', [
                'Check in Search Console that your key pages are indexed.',
                'Keep pages fast and stable on mobile.',
                'Link important pages from your menu and from related content.',
                'Put key information in text, not only inside images or PDFs.',
                'Do not block Googlebot or the resources your pages need in robots.txt.',
            ]],
            ['h2', 'Step 2: answer the question in the first lines'],
            ['p', 'Use the question as a heading and answer it right below in two or three sentences. Then add the detail: steps, examples, lists or a short table. This answer-first format helps Google extract a precise passage and also helps readers who scan.'],
            ['h2', 'Step 3: add structure and context'],
            ['p', 'Use clear H2 and H3 headings, structured data for your organization, services, articles and FAQs, visible author information and an updated date on content that changes. Structure does not guarantee inclusion, but it removes doubt about what each page is about.'],
            ['h2', 'Step 4: build trust beyond your site'],
            ['p', 'Keep your company facts consistent in your Google Business Profile, directories and social profiles, collect genuine reviews and earn mentions in industry media. These signals help Google understand who you are and why your content is reliable.'],
            ['h2', 'How to track your presence'],
            ['p', 'Search Console includes traffic from AI features inside the standard Web performance report, so watch the queries and pages that gain impressions. Complement it by checking your key questions in Gemini and AI Overviews every month and noting which sources appear. If you want a team to handle it, see our <a href="{seo-aeo}">AEO and SEO services</a> and our guide on <a href="{post:aeo-services}">what AEO services include</a>.'],
        ],
        'faq_title': 'Frequently asked questions',
        'faq': [
            ['Can I pay to appear in AI Overviews?', 'No. Organic inclusion in AI Overviews and Gemini answers cannot be bought. Ads are a separate system with their own placements and rules. To appear as an organic source, your pages need to be indexed, helpful and clear about the question they answer, like any other result in Google Search.'],
            ['Does structured data guarantee a place in AI Overviews?', 'No. Structured data helps Google understand your pages, but it does not guarantee inclusion. It works best combined with content that answers questions directly, a site that is easy to crawl and a brand with consistent information across the web. Think of it as a clarity signal, not a shortcut.'],
            ['Is optimizing for Gemini different from optimizing for ChatGPT?', 'The principles are the same: clear answers, good structure and trust. The difference is the source. Gemini and AI Overviews lean on Google’s index, while ChatGPT search uses its own crawler, OAI-SearchBot, and other search providers. Allow those crawlers in robots.txt if you want to appear in both.'],
        ],
        'takeaways': [
            'Gemini and AI Overviews build on Google’s index: SEO comes first.',
            'Answer each question in the first lines, then add detail.',
            'Structured data and author information add clarity, not guarantees.',
            'Track AI visibility in Search Console and with monthly checks.',
        ],
    },
    'es': {
        'slug': 'como-aparecer-en-gemini-y-ai-overviews',
        'title': 'Cómo aparecer en Gemini y en los AI Overviews de Google',
        'seo': ['Cómo aparecer en Gemini y en los AI Overviews de Google',
                'Cómo eligen sus fuentes Gemini y los AI Overviews de Google y los pasos prácticos para que tu contenido califique: SEO técnico, respuestas directas y confianza.',
                'aparecer en Gemini'],
        'tldr': 'Gemini y los AI Overviews se apoyan en la Búsqueda de Google. No existe una etiqueta ni un formulario especial para entrar: necesitas un sitio que Google pueda leer, páginas que respondan de forma directa, una estructura clara y señales de que tu marca es confiable.',
        'intro': 'Aparecer en Gemini y en los AI Overviews de Google significa que tu contenido sea elegido como fuente cuando la IA de Google escribe una respuesta. Ambos usan el índice de Google, así que la base es un SEO sólido. Además necesitas respuestas directas, una estructura clara, datos estructurados y señales coherentes de que tu marca es confiable.',
        'body': [
            ['h2', 'Cómo eligen sus fuentes Gemini y los AI Overviews'],
            ['p', 'Google no publica una fórmula, pero su guía para dueños de sitios es coherente: las funciones de IA usan contenido de su índice y siguen los mismos principios de calidad que la Búsqueda. Las páginas rastreables, útiles, bien estructuradas y respaldadas por fuentes creíbles son las que suelen elegirse.'],
            ['p', 'No hay un marcado extra ni un envío que garantice la inclusión. El trabajo es el mismo buen SEO, con más atención a responder preguntas concretas con claridad.'],
            ['h2', 'Paso 1: asegúrate de que Google pueda leer tu sitio'],
            ['ul', [
                'Revisa en Search Console que tus páginas clave estén indexadas.',
                'Mantén las páginas rápidas y estables en el celular.',
                'Enlaza las páginas importantes desde el menú y desde contenido relacionado.',
                'Pon la información clave en texto, no solo dentro de imágenes o PDF.',
                'No bloquees a Googlebot ni los recursos que necesitan tus páginas en el robots.txt.',
            ]],
            ['h2', 'Paso 2: responde la pregunta en las primeras líneas'],
            ['p', 'Usa la pregunta como título y respóndela justo debajo en dos o tres oraciones. Después suma el detalle: pasos, ejemplos, listas o una tabla corta. Este formato de respuesta primero ayuda a Google a extraer un fragmento preciso y también a quienes leen en diagonal.'],
            ['h2', 'Paso 3: suma estructura y contexto'],
            ['p', 'Usa títulos H2 y H3 claros, datos estructurados para tu organización, servicios, artículos y preguntas frecuentes, información visible del autor y una fecha de actualización en el contenido que cambia. La estructura no garantiza la inclusión, pero elimina dudas sobre de qué trata cada página.'],
            ['h2', 'Paso 4: construye confianza fuera de tu sitio'],
            ['p', 'Mantén los datos de tu empresa iguales en tu Perfil de Empresa de Google, directorios y redes sociales, junta reseñas reales y consigue menciones en medios del sector. Estas señales ayudan a Google a entender quién eres y por qué tu contenido es confiable.'],
            ['h2', 'Cómo medir tu presencia'],
            ['p', 'Search Console incluye el tráfico de las funciones de IA dentro del informe de rendimiento web, así que revisa qué consultas y páginas ganan impresiones. Compleméntalo revisando cada mes tus preguntas clave en Gemini y en los AI Overviews y anotando qué fuentes aparecen. Si quieres que un equipo lo haga, mira nuestros <a href="{seo-aeo}">servicios de AEO y SEO</a> y nuestra guía sobre <a href="{post:aeo-services}">qué incluyen los servicios de AEO</a>.'],
        ],
        'faq_title': 'Preguntas frecuentes',
        'faq': [
            ['¿Puedo pagar para aparecer en los AI Overviews?', 'No. La inclusión orgánica en los AI Overviews y en las respuestas de Gemini no se puede comprar. Los anuncios son un sistema aparte, con sus propias ubicaciones y reglas. Para aparecer como fuente orgánica, tus páginas deben estar indexadas, ser útiles y dejar clara la pregunta que responden, como cualquier otro resultado de Google.'],
            ['¿Los datos estructurados garantizan un lugar en los AI Overviews?', 'No. Los datos estructurados ayudan a Google a entender tus páginas, pero no garantizan la inclusión. Funcionan mejor junto con contenido que responde de forma directa, un sitio fácil de rastrear y una marca con información coherente en la web. Piensa en ellos como una señal de claridad, no como un atajo.'],
            ['¿Optimizar para Gemini es distinto que para ChatGPT?', 'Los principios son los mismos: respuestas claras, buena estructura y confianza. La diferencia está en la fuente. Gemini y los AI Overviews se apoyan en el índice de Google, mientras que la búsqueda de ChatGPT usa su propio rastreador, OAI-SearchBot, y otros proveedores. Permite esos rastreadores en tu robots.txt para aparecer en ambos.'],
        ],
        'takeaways': [
            'Gemini y los AI Overviews se apoyan en el índice de Google: el SEO va primero.',
            'Responde cada pregunta en las primeras líneas y luego suma el detalle.',
            'Los datos estructurados y la información del autor suman claridad, no garantías.',
            'Mide tu visibilidad en la IA con Search Console y revisiones mensuales.',
        ],
    },
})

# ---------------------------------------------------------------------------
# 4. AEO para SaaS
# ---------------------------------------------------------------------------
POSTS.append({
    'key': 'aeo-saas',
    'publish_gmt': '2026-10-20 13:00:00',
    'cat': 'aeo',
    'image': 'marketing-automation',
    'en': {
        'slug': 'aeo-for-saas',
        'title': 'AEO for SaaS: How to Get Your Product Cited by ChatGPT',
        'seo': ['AEO for SaaS: How to Get Your Product Cited by ChatGPT',
                'AEO for SaaS explained: how to get your product mentioned and cited by ChatGPT, Gemini and Perplexity when buyers compare tools, alternatives and integrations.',
                'aeo for saas'],
        'tldr': 'SaaS buyers use AI assistants to shortlist and compare tools. AEO for SaaS makes sure your product appears in those answers with accurate facts, through comparison and use-case pages, crawlable docs, consistent data and presence on the review sites assistants cite.',
        'intro': 'AEO for SaaS is the practice of making your product easy for AI assistants to find, understand and recommend when buyers research software. It focuses on the questions SaaS buyers ask most, such as alternatives, comparisons, integrations and pricing, and on keeping facts about your product consistent across your site, docs and review platforms.',
        'body': [
            ['h2', 'Why AI answers matter for SaaS'],
            ['p', 'Software buyers increasingly ask assistants to explain a category, suggest tools for a use case or compare two products. If your product is missing from those answers, or described with outdated information, you can lose the deal before the buyer ever visits your site.'],
            ['h2', 'The questions your content should answer'],
            ['ul', [
                'Best [category] tools for [use case or company size].',
                '[Your product] vs [competitor].',
                '[Competitor] alternatives.',
                'Does [your product] integrate with [tool]?',
                'How much does [your product] cost?',
                'How to [job to be done] with [your product].',
            ]],
            ['h2', 'Pages that AI assistants can use'],
            ['ul', [
                '<strong>Comparison pages</strong>: honest side-by-side comparisons with the competitors buyers actually consider.',
                '<strong>Alternatives pages</strong>: why teams switch to you and who you are the best fit for.',
                '<strong>Use-case pages</strong>: one page per job to be done or industry, with concrete workflows.',
                '<strong>Integration pages</strong>: one page per key integration, explaining what it does.',
                '<strong>Pricing</strong>: plans and limits written in text, not only in images or scripts.',
                '<strong>Docs and changelog</strong>: public and crawlable, so assistants use current information.',
            ]],
            ['h2', 'Signals outside your website'],
            ['p', 'Assistants learn about software from many sources: review platforms such as G2 and Capterra, app marketplaces, partner directories, communities and industry articles. Keep your positioning, features and pricing consistent in all of them and ask satisfied customers to leave reviews that mention the use cases you want to be known for.'],
            ['h2', 'How to measure progress'],
            ['ul', [
                'Keep a list of prompts buyers would use and check them every month in ChatGPT, Gemini and Perplexity.',
                'Record whether you are mentioned, how you are described and which competitors appear.',
                'Watch referral traffic from AI tools in GA4.',
                'Add "AI assistant" as an option in your "How did you hear about us?" field.',
            ]],
            ['p', 'For the bigger picture, read <a href="{post:seo-aeo-geo}">SEO vs AEO vs GEO</a>. If you want a team to run it, see our <a href="{seo-aeo}">AEO and SEO services</a>.'],
        ],
        'faq_title': 'Frequently asked questions',
        'faq': [
            ['Should SaaS companies block AI crawlers?', 'It depends on the crawler. Blocking search and answer crawlers, such as OAI-SearchBot or PerplexityBot, removes you from those answers, which usually hurts discovery. Crawlers that collect training data are a separate decision. Many SaaS companies allow search crawlers and decide on training crawlers based on their content and legal policy.'],
            ['How long does AEO take for a SaaS product?', 'Pages and documentation changes can be picked up within weeks, but a steady presence in AI answers usually takes months. Categories with many established competitors take longer. Measure progress monthly with the same set of prompts, so you can see which pages and mentions are moving the needle.'],
            ['Do comparison pages need to name competitors?', 'Yes, if you want to appear when buyers compare. Assistants answer questions like "X vs Y" with sources that address that comparison directly. Keep these pages fair and factual, update them when products change and explain who each option is best for. Misleading comparisons damage trust with buyers and with AI tools.'],
        ],
        'takeaways': [
            'SaaS buyers use AI assistants to shortlist and compare tools.',
            'Comparison, alternatives, use-case and integration pages give assistants material to cite.',
            'Keep product facts consistent on your site, docs and review platforms.',
            'Track your mentions monthly with a fixed set of prompts.',
        ],
    },
    'es': {
        'slug': 'aeo-para-saas',
        'title': 'AEO para SaaS: cómo lograr que ChatGPT cite tu producto',
        'seo': ['AEO para SaaS: cómo lograr que ChatGPT cite tu producto',
                'AEO para SaaS: cómo lograr que ChatGPT, Gemini y Perplexity citen tu producto cuando los compradores comparan herramientas, alternativas e integraciones.',
                'AEO para SaaS'],
        'tldr': 'Los compradores de SaaS usan asistentes de IA para armar su lista y comparar herramientas. El AEO para SaaS asegura que tu producto aparezca en esas respuestas con datos correctos, con páginas comparativas y por caso de uso, documentación rastreable, datos coherentes y presencia en los sitios de reseñas que citan los asistentes.',
        'intro': 'El AEO para SaaS es la práctica de lograr que los asistentes de IA encuentren, entiendan y recomienden tu producto cuando alguien investiga software. Se enfoca en las preguntas más comunes de los compradores de SaaS, como alternativas, comparaciones, integraciones y precios, y en mantener los datos de tu producto coherentes en tu sitio, documentación y plataformas de reseñas.',
        'body': [
            ['h2', 'Por qué las respuestas de la IA importan en SaaS'],
            ['p', 'Cada vez más compradores de software piden a los asistentes que expliquen una categoría, sugieran herramientas para un caso de uso o comparen dos productos. Si tu producto no aparece en esas respuestas, o aparece con información desactualizada, puedes perder la venta antes de que el comprador visite tu sitio.'],
            ['h2', 'Las preguntas que tu contenido debe responder'],
            ['ul', [
                'Mejores herramientas de [categoría] para [caso de uso o tamaño de empresa].',
                '[Tu producto] vs [competidor].',
                'Alternativas a [competidor].',
                '¿[Tu producto] se integra con [herramienta]?',
                '¿Cuánto cuesta [tu producto]?',
                'Cómo [tarea] con [tu producto].',
            ]],
            ['h2', 'Páginas que los asistentes de IA pueden usar'],
            ['ul', [
                '<strong>Páginas comparativas</strong>: comparaciones honestas con los competidores que los compradores realmente consideran.',
                '<strong>Páginas de alternativas</strong>: por qué los equipos se cambian a tu producto y para quién es la mejor opción.',
                '<strong>Páginas por caso de uso</strong>: una página por tarea o industria, con flujos de trabajo concretos.',
                '<strong>Páginas de integraciones</strong>: una página por integración clave, explicando qué hace.',
                '<strong>Precios</strong>: planes y límites escritos en texto, no solo en imágenes o scripts.',
                '<strong>Documentación y changelog</strong>: públicos y rastreables, para que los asistentes usen información actual.',
            ]],
            ['h2', 'Señales fuera de tu sitio'],
            ['p', 'Los asistentes aprenden sobre software desde muchas fuentes: plataformas de reseñas como G2 y Capterra, marketplaces de aplicaciones, directorios de socios, comunidades y artículos del sector. Mantén tu posicionamiento, funciones y precios iguales en todas ellas y pide a tus clientes satisfechos reseñas que mencionen los casos de uso por los que quieres ser conocido.'],
            ['h2', 'Cómo medir el avance'],
            ['ul', [
                'Arma una lista de prompts que usarían tus compradores y revísala cada mes en ChatGPT, Gemini y Perplexity.',
                'Anota si te mencionan, cómo te describen y qué competidores aparecen.',
                'Revisa en GA4 el tráfico que llega desde herramientas de IA.',
                'Suma "Asistente de IA" como opción en el campo "¿Cómo nos conociste?".',
            ]],
            ['p', 'Para ver el panorama completo, lee <a href="{post:seo-aeo-geo}">SEO vs AEO vs GEO</a>. Si quieres que un equipo lo haga, mira nuestros <a href="{seo-aeo}">servicios de AEO y SEO</a>.'],
        ],
        'faq_title': 'Preguntas frecuentes',
        'faq': [
            ['¿Las empresas SaaS deberían bloquear los rastreadores de IA?', 'Depende del rastreador. Bloquear los rastreadores de búsqueda y respuesta, como OAI-SearchBot o PerplexityBot, te saca de esas respuestas, lo que suele afectar tu visibilidad. Los rastreadores que recopilan datos de entrenamiento son otra decisión. Muchas empresas SaaS permiten los de búsqueda y deciden sobre los de entrenamiento según su contenido y su política legal.'],
            ['¿Cuánto tarda el AEO en un producto SaaS?', 'Los cambios en páginas y documentación pueden notarse en semanas, pero una presencia estable en las respuestas de la IA suele tomar meses. Las categorías con muchos competidores consolidados tardan más. Mide el avance cada mes con la misma lista de prompts para ver qué páginas y menciones están generando resultados.'],
            ['¿Las páginas comparativas deben nombrar a los competidores?', 'Sí, si quieres aparecer cuando los compradores comparan. Los asistentes responden preguntas como "X vs Y" con fuentes que tratan esa comparación de forma directa. Mantén estas páginas justas y basadas en hechos, actualízalas cuando los productos cambien y explica para quién es mejor cada opción. Las comparaciones engañosas dañan la confianza.'],
        ],
        'takeaways': [
            'Los compradores de SaaS usan asistentes de IA para armar su lista y comparar herramientas.',
            'Las páginas comparativas, de alternativas, por caso de uso y de integraciones le dan material a la IA.',
            'Mantén los datos del producto coherentes en tu sitio, documentación y reseñas.',
            'Mide tus menciones cada mes con una lista fija de prompts.',
        ],
    },
})


def words(html):
    return len(re.sub('<[^>]+>', '', html).split())


def check():
    ok = True
    for p in POSTS:
        for lang in ('en', 'es'):
            d = p[lang]
            t, desc, _ = d['seo']
            probs = []
            if len(t) > 60: probs.append(f'title {len(t)}')
            if not 140 <= len(desc) <= 160: probs.append(f'desc {len(desc)}')
            iw = words(d['intro'])
            if not 40 <= iw <= 60: probs.append(f'intro {iw}')
            for q, a in d['faq']:
                w = words(a)
                if not 40 <= w <= 60: probs.append(f'faq {w} "{q[:30]}"')
            blob = json.dumps(d, ensure_ascii=False)
            if '—' in blob: probs.append('raya larga')
            print(p['key'], lang, 'OK' if not probs else probs)
            ok = ok and not probs
    return ok


if __name__ == '__main__':
    os.chdir(os.path.dirname(os.path.abspath(__file__)))
    good = check()
    out = '../theme/flmm-studio/inc/migrate/blog-posts.json'
    json.dump(POSTS, open(out, 'w'), ensure_ascii=False, indent=1)
    print('escrito', out, 'con errores' if not good else '')
