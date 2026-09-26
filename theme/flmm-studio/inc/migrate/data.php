<?php
/**
 * Datos de la migración: slugs en español, SEO, textos de páginas de empresa,
 * imágenes, blog y redirecciones.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Slugs en español de cada página (slug EN => slug ES).
 */
function flmm_mig_es_slugs() {
	return array(
		'home'                    => 'inicio',
		'blog'                    => 'articulos',
		'consulting'              => 'estrategia-de-marketing',
		'performance-marketing'   => 'marketing-de-performance',
		'seo-aeo'                 => 'posicionamiento-seo-aeo',
		'aeo-content'             => 'contenido-aeo',
		'design-branding'         => 'contenido-creativo-multimedia',
		'website'                 => 'ecommerce-y-sitios-web',
		'growth-hacking'          => 'estrategias-growth-hacking',
		'analytics'               => 'analitica-de-marketing',
		'ai-assistants'           => 'asistentes-de-ia',
		'marketing-automation'    => 'automatizacion-de-marketing',
		'about'                   => 'sobre-nosotros',
		'digitales-sin-fronteras' => 'podcast-digitales-sin-fronteras',
		'podcast'                 => 'podcast-marketing-today',
		'privacy-policy'          => 'politica-de-privacidad',
		'contact-us'              => 'contacto',
	);
}

/**
 * Títulos SEO que corrigen los de src/ que pasan de 60 caracteres.
 */
function flmm_mig_title_overrides() {
	return array(
		'growth-hacking'       => array(
			'en' => 'Growth Hacking: Acquisition & Retention | FL Marketing',
			'es' => 'Growth hacking: adquisición y retención | FL Marketing',
		),
		'marketing-automation' => array(
			'es' => 'Automatización de marketing y Martech | FL Marketing',
		),
	);
}

/**
 * Texto alternativo de las ilustraciones (EN y ES).
 */
function flmm_mig_media() {
	return array(
		'consulting'            => array( 'Marketing strategy: chess pieces and a pink growth path toward a clear goal', 'Estrategia de marketing: piezas de ajedrez y un camino rosa de crecimiento hacia un objetivo claro' ),
		'performance-marketing' => array( 'Performance marketing: scattered audiences focused into a single conversion', 'Performance marketing: audiencias dispersas que se enfocan en una sola conversión' ),
		'seo-aeo'               => array( 'SEO and AEO: content connected to search engines and AI answers', 'SEO y AEO: contenido conectado a buscadores y a respuestas de IA' ),
		'aeo-content'           => array( 'AEO content: an article feeding answers in ChatGPT, Claude, Google, Gemini and Perplexity', 'Contenido AEO: un artículo que alimenta respuestas en ChatGPT, Claude, Google, Gemini y Perplexity' ),
		'design-branding'       => array( 'Creative and multimedia content: design, typography, video and audio assets', 'Contenido creativo y multimedia: diseño, tipografía, video y audio' ),
		'website'               => array( 'E-commerce website: product page, mobile store, cart and checkout', 'Sitio de e-commerce: página de producto, tienda móvil, carrito y pago' ),
		'growth-hacking'        => array( 'Growth hacking: a rocket rising over growth metrics and experiments', 'Growth hacking: un cohete que sube sobre métricas de crecimiento y experimentos' ),
		'analytics'             => array( 'Marketing analytics: scattered data turned into a clear trend through a lens', 'Analítica de marketing: datos dispersos que una lupa convierte en una tendencia clara' ),
		'ai-assistants'         => array( 'AI assistant: scattered messages processed into organized, actionable outputs', 'Asistente de IA: mensajes dispersos procesados en resultados ordenados y accionables' ),
		'marketing-automation'  => array( 'Marketing automation: tools like Google, Meta, Slack and Shopify connected through one hub', 'Automatización de marketing: herramientas como Google, Meta, Slack y Shopify conectadas en un solo centro' ),
		'privacy-policy'        => array( 'Privacy policy: a shield protecting personal data', 'Política de privacidad: un escudo que protege los datos personales' ),
	);
}

/**
 * SEO de páginas que no son servicios: slug EN => array( lang => array( title, description, keyword ) ).
 */
function flmm_mig_page_seo() {
	return array(
		'home'                    => array(
			'en' => array( 'FL Marketing Management | Performance & AI Marketing', 'Boutique digital marketing agency for the US and Latin America: performance, SEO + AEO, e-commerce, analytics and AI automation with measurable results.', 'digital marketing agency' ),
			'es' => array( 'FL Marketing Management | Agencia de marketing digital', 'Agencia boutique de marketing digital para EE. UU. y Latinoamérica: performance, SEO + AEO, e-commerce, analítica y automatización con IA medibles.', 'agencia de marketing digital' ),
		),
		'blog'                    => array(
			'en' => array( 'Digital Marketing, SEO, AEO & AI Blog | FL Marketing', 'Articles on performance marketing, SEO, AEO and artificial intelligence for B2B and e-commerce brands, written by the FL Marketing Management team.', 'digital marketing blog' ),
			'es' => array( 'Blog de marketing digital, SEO, AEO e IA | FL Marketing', 'Artículos sobre performance marketing, SEO, AEO e inteligencia artificial para marcas B2B y e-commerce, escritos por el equipo de FL Marketing Management.', 'blog de marketing digital' ),
		),
		'about'                   => array(
			'en' => array( 'About FL Marketing Management | Digital Marketing Agency', 'Meet FL Marketing Management, a Florida-based boutique consultancy for performance marketing, AI automation, analytics and SEO + AEO that builds growth systems.', 'FL Marketing Management' ),
			'es' => array( 'Sobre FL Marketing Management | Agencia de marketing', 'Conoce FL Marketing Management, consultora boutique con base en Florida en performance marketing, automatización con IA, analítica y SEO + AEO para crecer.', 'FL Marketing Management' ),
		),
		'digitales-sin-fronteras' => array(
			'en' => array( 'Digitales Sin Fronteras: Digital Marketing Podcast', 'Digitales Sin Fronteras is a podcast on digital marketing and growth, with interviews, case studies and trends, hosted by Alejandro Lovera and Felipe Ríos.', 'Digitales Sin Fronteras' ),
			'es' => array( 'Digitales Sin Fronteras: podcast de marketing digital', 'Digitales Sin Fronteras es un podcast sobre marketing digital y growth, con entrevistas, casos y tendencias, conducido por Alejandro Lovera y Felipe Ríos.', 'Digitales Sin Fronteras' ),
		),
		'podcast'                 => array(
			'en' => array( 'Marketing Today Podcast | FL Marketing Management', 'Marketing Today is a podcast on digital marketing trends and strategy, with expert interviews, campaign case studies and practical lessons for growing brands.', 'Marketing Today podcast' ),
			'es' => array( 'Podcast Marketing Today | FL Marketing Management', 'Marketing Today es un podcast sobre tendencias y estrategia de marketing digital, con entrevistas a expertos, casos de campañas y aprendizajes para marcas.', 'podcast Marketing Today' ),
		),
		'privacy-policy'          => array(
			'en' => array( 'Privacy Policy | FL Marketing Management', 'Read how FL Marketing Management LLC collects, uses and protects the personal data you share through our website, contact form and email, and your choices.', 'privacy policy' ),
			'es' => array( 'Política de privacidad | FL Marketing Management', 'Conoce cómo FL Marketing Management LLC recopila, usa y protege los datos personales que compartes en nuestro sitio, formulario y correo, y tus opciones.', 'política de privacidad' ),
		),
		'contact-us'              => array(
			'en' => array( 'Contact FL Marketing Management | Let’s Talk', 'Contact FL Marketing Management to discuss your project, growth goals or marketing challenges. Use the short form, write to us by email or reach us on Telegram.', 'contact FL Marketing Management' ),
			'es' => array( 'Contacto | FL Marketing Management', 'Contacta a FL Marketing Management para conversar sobre tu proyecto, tus metas de crecimiento o tus desafíos de marketing: usa el formulario, correo o Telegram.', 'contacto FL Marketing Management' ),
		),
	);
}

/**
 * Entrada del hero del home (primer párrafo de 40 a 60 palabras que responde qué es).
 */
function flmm_mig_home_lead() {
	return array(
		'en' => 'FL Marketing Management is a boutique digital marketing agency for brands in the US and Latin America. One senior team covers strategy, performance marketing, SEO and AEO, content, e-commerce, analytics and AI automation, and measures every action against revenue instead of vanity metrics.',
		'es' => 'FL Marketing Management es una agencia boutique de marketing digital para marcas en EE. UU. y Latinoamérica. Un solo equipo senior cubre estrategia, performance marketing, SEO y AEO, contenido, e-commerce, analítica y automatización con IA, y mide cada acción contra los ingresos y no contra métricas de vanidad.',
	);
}

/**
 * Blog: pares EN/ES, categoría y SEO.
 */
function flmm_mig_posts() {
	return array(
		array(
			'cat' => 'performance',
			'en'  => array( 'id' => 1591, 'slug' => 'performance-marketing-metrics', 'seo' => array( 'B2B Performance Marketing Metrics Beyond CPC (2026)', 'Which B2B performance marketing metrics drive growth in 2026 beyond CPC and CTR: a three-layer hierarchy that connects ad spend with pipeline and revenue.', 'B2B performance marketing metrics' ) ),
			'es'  => array( 'id' => 1844, 'slug' => 'metricas-de-performance-marketing-b2b-mas-alla-del-cpc', 'seo' => array( 'Métricas de performance marketing B2B más allá del CPC', 'El CPC y el CTR ya no explican el performance B2B. Conoce la jerarquía de métricas 2026 que conecta la inversión con los ingresos y alimenta a la IA.', 'métricas de performance marketing B2B' ) ),
		),
		array(
			'cat' => 'aeo',
			'en'  => array( 'id' => 1597, 'slug' => 'marketing-dictionary-ai-aeo-llm', 'seo' => array( 'AI Marketing Terms 2026: AEO, LLM & SEO Glossary', 'A practical 2026 glossary of AI marketing terms for B2B leaders: AEO, LLM share of voice, marketing entropy, signal-based marketing and AI attribution.', 'AI marketing terms' ) ),
			'es'  => array( 'id' => 1835, 'slug' => 'terminos-marketing-ia-2026', 'seo' => array( 'Términos de marketing con IA 2026: AEO, LLM y SEO', 'Los términos de marketing con IA que todo líder B2B necesita en 2026: AEO, LLM Share of Voice, entropía y atribución con IA. Definiciones claras y directas.', 'términos de marketing con IA' ) ),
		),
		array(
			'cat' => 'ai',
			'en'  => array( 'id' => 1568, 'slug' => 'ai-shannon-entropy', 'seo' => array( 'AI & Shannon Entropy in B2B Performance Marketing', 'How AI and Shannon entropy reduce noise in B2B performance marketing, so teams and algorithms decide with cleaner signals, faster learning and higher ROI.', 'Shannon entropy marketing' ) ),
			'es'  => array( 'id' => 1838, 'slug' => 'ia-y-entropia-de-shannon-en-performance-marketing-b2b', 'seo' => array( 'IA y entropía de Shannon en performance marketing B2B', 'La entropía de Shannon explica por qué más datos dañan el marketing B2B. Descubre cómo la IA reduce el ruido, afina las señales y mejora el ROI.', 'entropía de Shannon en marketing' ) ),
		),
	);
}

/**
 * Categorías nuevas del blog: clave => array( EN => array( nombre, slug ), ES => array( nombre, slug ) ).
 */
function flmm_mig_categories() {
	return array(
		'performance' => array( 'en' => array( 'Performance', 'performance' ), 'es' => array( 'Performance', 'performance-es' ) ),
		'aeo'         => array( 'en' => array( 'AEO', 'aeo' ), 'es' => array( 'AEO', 'aeo-es' ) ),
		'ai'          => array( 'en' => array( 'AI', 'ai' ), 'es' => array( 'IA', 'ia' ) ),
	);
}

/**
 * Autor: datos del perfil.
 */
function flmm_mig_author() {
	return array(
		'id'             => 242854117,
		'description'    => 'Advertising professional with master’s degrees in Big Data & Business Intelligence and Business AI, AMA PCM® and Effie Award winner. He leads FL Marketing Management and manages marketing for brands in the US and Latin America.',
		'description_es' => 'Publicista con másteres en Big Data & Business Intelligence e IA Empresarial, AMA PCM® y ganador de un Effie. Dirige FL Marketing Management y gestiona marketing para marcas en EE. UU. y Latinoamérica.',
		'flmm_role'      => 'Founder · Strategy & performance',
		'flmm_role_es'   => 'Fundador · Estrategia y performance',
		'flmm_linkedin'  => 'https://www.linkedin.com/in/alejandro-lovera/',
	);
}

/**
 * Redirecciones 301 aprobadas en la fase 0: origen (sin barra inicial) => destino (ruta o URL).
 */
function flmm_mig_redirects() {
	return array(
		'about-us'                   => '/about/',
		'contact'                    => '/contact-us/',
		'digital-marketing-services' => '/',
		'performance-marketing-strategies-for-2026-using-ai-to-maximize-b2b-roi' => '/blog/ai-shannon-entropy/',
		'ecommerce'                  => '/website/',
		'website-monetization'       => '/website/',
		'performance'                => '/performance-marketing/',
		'search-engine-optimization' => '/seo-aeo/',
		'blog/metricas-performance-marketing-b2b' => '/es/blog/metricas-de-performance-marketing-b2b-mas-alla-del-cpc/',
		'blog/ia-entropia-shannon-marketing'      => '/es/blog/ia-y-entropia-de-shannon-en-performance-marketing-b2b/',
		'blog/metricas-de-performance-marketing-b2b-mas-alla-del-cpc' => '/es/blog/metricas-de-performance-marketing-b2b-mas-alla-del-cpc/',
		'blog/ia-y-entropia-de-shannon-en-performance-marketing-b2b'  => '/es/blog/ia-y-entropia-de-shannon-en-performance-marketing-b2b/',
		'blog/terminos-marketing-ia-2026'                             => '/es/blog/terminos-marketing-ia-2026/',
		'blog/category/ai-performance-marketing'                      => '/blog/category/performance/',
		'blog/category/marketing-strategy-ai'                         => '/blog/category/aeo/',
	);
}

/**
 * Páginas antiguas que pasan a borrador después de crear su redirección.
 */
function flmm_mig_retired_pages() {
	return array( 'digital-marketing-services', 'performance-marketing-strategies-for-2026-using-ai-to-maximize-b2b-roi', 'ecommerce', 'website-monetization' );
}

/* -------------------------------------------------------------------------
 * Páginas de empresa (contenido actual corregido, en EN y ES)
 * ---------------------------------------------------------------------- */

/**
 * Lista con viñetas.
 *
 * @param array $items Elementos (HTML).
 * @return string
 */
function flmm_mig_list( $items ) {
	$html = '';
	foreach ( $items as $item ) {
		$html .= flmm_bo( 'list-item' ) . '<li>' . $item . '</li><!-- /wp:list-item -->';
	}
	return flmm_bo( 'list' ) . '<ul class="wp-block-list">' . $html . '</ul><!-- /wp:list -->';
}

/**
 * Video de YouTube incrustado.
 *
 * @param string $url URL del video.
 * @return string
 */
function flmm_mig_youtube( $url ) {
	return flmm_bo( 'embed', array( 'url' => $url, 'type' => 'video', 'providerNameSlug' => 'youtube', 'responsive' => true, 'className' => 'wp-embed-aspect-16-9 wp-has-aspect-ratio' ) )
		. '<figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">' . "\n" . esc_url( $url ) . "\n" . '</div></figure><!-- /wp:embed -->';
}

/**
 * Contenido de las páginas de empresa.
 *
 * @param string $slug Slug EN.
 * @param string $lang Idioma.
 * @return array( 'title' => , 'content' => )
 */
function flmm_mig_company_page( $slug, $lang ) {
	$es    = 'es' === $lang;
	$email = flmm_option( 'email' );
	switch ( $slug ) {
		case 'about':
			return array(
				'title'    => $es ? 'Sobre FL Marketing Management' : 'About FL Marketing Management',
				'content'  => flmm_mig_about_page( $lang ),
				'template' => 'page-landing',
			);

		case 'digitales-sin-fronteras':
			$links = flmm_buttons(
				array(
					array( 'Spotify', 'https://open.spotify.com/show/4r10YDa5qSMSGOZUbO2wFj', 'ghost', true ),
					array( 'YouTube', 'https://www.youtube.com/@DigitalesSinFronteras', 'ghost', true ),
					array( 'Instagram', 'https://instagram.com/digitalessinfronteras', 'ghost', true ),
					array( 'TikTok', 'https://www.tiktok.com/@digitalessinfronteras', 'ghost', true ),
				)
			);
			$video = flmm_mig_youtube( 'https://www.youtube.com/watch?v=QcqCwNv-b8k' );
			if ( $es ) {
				return array(
					'title'   => 'Digitales Sin Fronteras: podcast de marketing digital',
					'content' => flmm_p( '<strong>Digitales Sin Fronteras</strong> es un podcast sobre las tendencias y estrategias actuales del marketing digital y su impacto en la industria. Lo conducen Alejandro Lovera y Felipe Ríos Barraza, dos profesionales latinos que trabajan desde EE. UU. y España, y hace públicas las conversaciones que normalmente ocurren en privado entre colegas.' )
						. $video . $links
						. flmm_p( 'Entrevistamos a expertos en marketing, emprendedores, creadores de contenido y otros referentes de la industria para profundizar en las últimas tendencias del sector.' )
						. flmm_p( 'También mostramos casos de campañas exitosas y cómo se desarrollaron, y comentamos noticias relevantes del marketing y las redes sociales, junto con las nuevas tendencias en tecnología y publicidad digital.' )
						. flmm_p( 'El tono es profesional y educativo, pero cercano y fácil de seguir para cualquier persona interesada en aprender marketing. A través de conversaciones entretenidas y directas, exploramos el lado humano del marketing digital y del growth, con experiencias personales, desafíos y aprendizajes de profesionales que están en terreno todos los días.' )
						. flmm_p( 'Digitales Sin Fronteras busca entregar ideas valiosas sobre cómo las empresas y las marcas pueden aprovechar estas tendencias para crecer.' )
						. flmm_p( '<strong>Síguenos en @digitalessinfronteras</strong>' ),
				);
			}
			return array(
				'title'   => 'Digitales Sin Fronteras: Digital Marketing Podcast',
				'content' => flmm_p( '<strong>Digitales Sin Fronteras</strong> (Digitals Without Borders) is a podcast on current trends and strategies in digital marketing and how they shape the industry. It is hosted by Alejandro Lovera and Felipe Ríos Barraza, two Latino professionals working from the US and Spain, and it makes public the conversations that usually happen privately between colleagues.' )
					. $video . $links
					. flmm_p( 'We interview marketing experts, successful entrepreneurs, creators and other industry leaders to dig into the latest trends in the sector.' )
					. flmm_p( 'We also showcase case studies of successful marketing campaigns and how they were built, and cover relevant news in marketing and social media, as well as emerging trends in technology and digital advertising.' )
					. flmm_p( 'The tone is professional and educational, yet engaging and accessible to anyone interested in learning about marketing. Through entertaining and straightforward conversations, we explore the human side of digital marketing and growth, sharing personal experiences, challenges and lessons from professionals who are in the field every day.' )
					. flmm_p( 'Digitales Sin Fronteras aims to provide valuable insights on how businesses and brands can leverage these trends for growth and success.' )
					. flmm_p( '<strong>Follow us at @digitalessinfronteras</strong>' ),
			);

		case 'podcast':
			$links  = flmm_buttons(
				array(
					array( 'Spotify', 'https://open.spotify.com/show/1CDdfGMtj3ybNcO6psDRtH', 'ghost', true ),
					array( 'YouTube', 'https://www.youtube.com/@Marketing.Today.Podcast', 'ghost', true ),
					array( 'Instagram', 'https://www.instagram.com/marketing.today.podcast/', 'ghost', true ),
					array( 'TikTok', 'https://www.tiktok.com/@marketing.today.podcast', 'ghost', true ),
				)
			);
			$video  = flmm_mig_youtube( 'https://youtu.be/HBmBbz4hcUY' );
			$review = static function ( $stars, $text, $name, $label ) {
				return flmm_bo( 'quote', array( 'className' => 'flmm-review' ) ) . '<blockquote class="wp-block-quote flmm-review">'
					. flmm_p( '<span aria-hidden="true">' . str_repeat( '★', $stars ) . '</span><span class="screen-reader-text">' . esc_html( $label ) . '</span>', 'flmm-review__stars' )
					. flmm_p( $text ) . '<cite>' . esc_html( $name ) . '</cite></blockquote><!-- /wp:quote -->';
			};
			if ( $es ) {
				return array(
					'title'   => 'Podcast Marketing Today',
					'content' => flmm_p( '<strong>Marketing Today</strong> es un podcast sobre las tendencias y estrategias actuales del marketing digital y cómo impactan en la industria. Conversamos con expertos, emprendedores y referentes del sector, revisamos casos de campañas exitosas y compartimos aprendizajes prácticos para que las marcas crezcan.' )
						. $video . $links
						. flmm_p( 'Incluye entrevistas con expertos en marketing, emprendedores exitosos, creadores de contenido y otros líderes de la industria, además de casos de campañas y cómo se desarrollaron.' )
						. flmm_p( 'El podcast también aborda noticias relevantes del marketing y las redes sociales, y las tendencias emergentes en tecnología y publicidad digital.' )
						. flmm_p( 'El tono es profesional y educativo, pero cercano y accesible para cualquier persona interesada en aprender marketing. El objetivo es entregar ideas útiles sobre cómo las empresas y las marcas pueden aprovecharlas para crecer.' )
						. flmm_h( 2, 'Lo que dicen nuestros oyentes' )
						. $review( 4, 'Es uno de mis podcasts de marketing favoritos: explica y aterriza las ideas sin tanto tecnicismo. Me gustaría que fuera más frecuente y con más invitados emprendedores.', 'Daniel Goncalves', '4 de 5 estrellas' )
						. $review( 5, 'Me ha servido mucho para aprender marketing digital. Tengo una empresa pequeña y, con todo lo que hay que aprender al emprender, no consideraba el marketing una prioridad. También tuve el gusto de participar en un episodio para contar mi punto de vista y mis comienzos en el marketing digital como emprendedor.', 'Pablo Pozarski', '5 de 5 estrellas' ),
				);
			}
			return array(
				'title'   => 'Marketing Today Podcast',
				'content' => flmm_p( '<strong>Marketing Today</strong> is a podcast about current trends and strategies in digital marketing and how they impact the industry. We talk with experts, entrepreneurs and industry leaders, review successful campaign case studies and share practical lessons that help brands grow.' )
					. $video . $links
					. flmm_p( 'It features interviews with marketing experts, successful entrepreneurs, creators and other industry leaders, plus case studies of successful campaigns and how they were developed.' )
					. flmm_p( 'The podcast also covers relevant news in marketing and social media, as well as emerging trends in technology and digital advertising.' )
					. flmm_p( 'The tone is professional and educational, yet engaging and accessible to anyone interested in learning about marketing. The goal is to share useful insights on how businesses and brands can leverage these trends for growth.' )
					. flmm_h( 2, 'What our listeners say' )
					. $review( 4, 'It is one of my favorite marketing podcasts: it explains ideas and makes them concrete without too much jargon. I would like it to be more frequent and with more entrepreneurs as guests.', 'Daniel Goncalves', '4 out of 5 stars' )
					. $review( 5, 'It has helped me a lot to learn about digital marketing. I run a small company and, with everything there is to learn as an entrepreneur, I did not consider marketing a priority. I also had the pleasure of joining one of the episodes to share my point of view and my beginnings in digital marketing as an entrepreneur.', 'Pablo Pozarski', '5 out of 5 stars' ),
			);

		case 'privacy-policy':
			$mail = '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
			if ( $es ) {
				return array(
					'title'   => 'Política de privacidad',
					'content' => flmm_p( 'Esta política de privacidad explica qué información puede recopilar <strong>FL Marketing Management LLC</strong> («nosotros») cuando visitas flmarketingmanagement.com, cómo la usamos y protegemos, y qué opciones tienes sobre ella. Se aplica al sitio web, al formulario de contacto y a los mensajes que nos envías por correo.' )
						. flmm_p( 'Fecha de vigencia: enero de 2026.', 'flmm-updated' )
						. flmm_h( 2, 'Información que recopilamos' )
						. flmm_mig_list( array( '<strong>Datos personales:</strong> cuando visitas el sitio o nos escribes por correo (' . $mail . ') o por el formulario, podemos recopilar tu nombre, tu correo electrónico y cualquier otra información que decidas compartir.', '<strong>Datos de uso:</strong> podemos recopilar automáticamente información sobre tu interacción con el sitio, como tu dirección IP, el tipo de navegador y las páginas que visitas.', '<strong>Contacto y activación de funciones:</strong> al contactarnos por el formulario, el correo o el teléfono, aceptas la activación de la función o el servicio que solicitas. Esto incluye, entre otras, consultas sobre nuestros productos, servicios o funciones adicionales. Usamos esa información solo para los fines descritos en esta política.' ) )
						. flmm_h( 2, 'Cookies y analítica' )
						. flmm_p( 'Usamos Google Tag Manager para cargar herramientas de medición que pueden usar cookies o tecnologías similares. Nos ayudan a entender cómo se usa el sitio y a mejorarlo. Puedes bloquear o borrar las cookies desde la configuración de tu navegador.' )
						. flmm_h( 2, 'Cómo usamos tu información' )
						. flmm_mig_list( array( 'Para responder tus consultas y prestar los servicios que solicitas.', 'Para personalizar tu experiencia en el sitio.', 'Para mejorar el sitio y nuestros servicios a partir de tus comentarios.' ) )
						. flmm_h( 2, 'Seguridad' )
						. flmm_p( 'Tomamos medidas razonables para proteger la información recopilada contra el acceso o la divulgación no autorizados. Sin embargo, ningún método de transmisión por internet ni de almacenamiento electrónico es completamente seguro.' )
						. flmm_h( 2, 'Tus opciones' )
						. flmm_p( 'Puedes elegir no entregar cierta información, aunque eso puede limitar tu acceso a algunos servicios o funciones del sitio.' )
						. flmm_h( 2, 'Contacto' )
						. flmm_p( 'Si tienes preguntas o inquietudes sobre esta política, escríbenos a ' . $mail . '.' )
						. flmm_h( 2, 'Cambios a esta política' )
						. flmm_p( 'Podemos actualizar esta política de privacidad. Publicaremos cualquier cambio en esta página con una nueva fecha de vigencia.' ),
				);
			}
			return array(
				'title'   => 'Privacy Policy',
				'content' => flmm_p( 'This privacy policy explains what information <strong>FL Marketing Management LLC</strong> ("we", "us" or "our") may collect when you visit flmarketingmanagement.com, how we use and protect it, and the choices you have. It applies to the website, the contact form and the messages you send us by email.' )
					. flmm_p( 'Effective date: January 2026.', 'flmm-updated' )
					. flmm_h( 2, 'Information we collect' )
					. flmm_mig_list( array( '<strong>Personal information:</strong> when you visit our website or contact us by email (' . $mail . ') or through the form, we may collect your name, email address and any other information you choose to provide.', '<strong>Usage data:</strong> we may automatically collect information about your interactions with our website, including your IP address, browser type and the pages you visit.', '<strong>Contact and activation of features:</strong> by contacting us through our web form, email or phone, you agree to the activation of the feature or service you request. This includes, but is not limited to, inquiries about our products, services or any additional functionality. We use the information provided solely for the purposes described in this policy.' ) )
					. flmm_h( 2, 'Cookies and analytics' )
					. flmm_p( 'We use Google Tag Manager to load measurement tools that may use cookies or similar technologies. They help us understand how the website is used and improve it. You can block or delete cookies in your browser settings.' )
					. flmm_h( 2, 'How we use your information' )
					. flmm_mig_list( array( 'To respond to your inquiries and provide the services you request.', 'To personalize your experience on our website.', 'To improve our website and services based on your feedback.' ) )
					. flmm_h( 2, 'Security' )
					. flmm_p( 'We take reasonable measures to protect the information collected from unauthorized access or disclosure. However, no method of transmission over the internet or electronic storage is completely secure.' )
					. flmm_h( 2, 'Your choices' )
					. flmm_p( 'You can choose not to provide certain information, but it may limit your ability to access certain services or features on our website.' )
					. flmm_h( 2, 'Contact us' )
					. flmm_p( 'If you have any questions or concerns about this privacy policy, please contact us at ' . $mail . '.' )
					. flmm_h( 2, 'Changes to this policy' )
					. flmm_p( 'We may update this privacy policy from time to time. Any changes will be posted on this page with an updated effective date.' ),
			);

		case 'contact-us':
			$buttons = flmm_buttons(
				array(
					array( ( $es ? 'Envíame un mail' : 'Send me an email' ) . ' <span class="flmm-arr">→</span>', 'mailto:' . $email ),
					array( 'Telegram', flmm_option( 'telegram' ), 'ghost', true ),
				)
			);
			if ( $es ) {
				return array(
					'title'   => 'Contacto',
					'content' => flmm_p( 'Contacta a <strong>FL Marketing Management</strong> para conversar sobre tu proyecto, tus metas de crecimiento o cómo nuestros servicios de performance, SEO y AEO pueden ayudar a tu negocio a ganar visibilidad y autoridad. Usa el formulario de abajo, escríbenos por correo o háblanos por Telegram.' )
						. flmm_p( 'Sé lo más específico posible para que podamos darte una respuesta clara y precisa. Si eres freelancer o partner, menciónalo en tu mensaje.' )
						. $buttons,
				);
			}
			return array(
				'title'   => 'Contact',
				'content' => flmm_p( 'Contact <strong>FL Marketing Management</strong> to discuss your project, your growth goals or how our performance, SEO and AEO services can help your business gain visibility and authority. Use the form below, send us an email or reach us on Telegram.' )
					. flmm_p( 'Be as specific as possible so we can give you a clear and accurate response. If you are a freelancer or partner, please mention it in your message.' )
					. $buttons,
			);
	}
	return array( 'title' => '', 'content' => '' );
}

/**
 * Página About con las secciones del diseño (hero, tarjetas, lista, equipo y trayectoria).
 *
 * @param string $lang Idioma.
 * @return string
 */
function flmm_mig_about_page( $lang ) {
	$es   = 'es' === $lang;
	$t    = static function ( $en, $es_text ) use ( $es ) {
		return $es ? $es_text : $en;
	};
	$home = flmm_pattern_data( 'home' );
	$card = static function ( $i, $title, $text ) {
		return flmm_group(
			flmm_p( sprintf( '%02d', $i ), 'flmm-n' ) . flmm_h( 3, $title ) . flmm_p( $text ),
			array( 'className' => 'flmm-inc__item flmm-rv', 'layout' => array( 'type' => 'default' ) )
		);
	};
	$cards = static function ( $items ) use ( $card ) {
		$html = '';
		foreach ( $items as $i => $item ) {
			$html .= $card( $i + 1, $item[0], $item[1] );
		}
		return flmm_group( $html, array( 'className' => 'flmm-inc', 'layout' => array( 'type' => 'default' ) ) );
	};

	// Hero.
	$hero = flmm_bo( 'flmm/breadcrumbs', array(), true )
		. flmm_group(
			flmm_p( $t( 'About us', 'Sobre nosotros' ), 'is-style-label' )
			. flmm_h( 1, $t( 'About FL Marketing Management', 'Sobre FL Marketing Management' ), 'flmm-sv-hero__title flmm-rv' )
			. flmm_p(
				$t(
					'<strong>FL Marketing Management</strong> is a Florida-based boutique digital consultancy that helps businesses grow through performance marketing, AI automation and data-driven strategy. We build scalable digital systems that improve efficiency, reduce costs and drive measurable growth, with clear execution instead of buzzwords or generic tactics.',
					'<strong>FL Marketing Management</strong> es una consultora digital boutique con base en Florida que ayuda a las empresas a crecer con performance marketing, automatización con IA y estrategia basada en datos. Construimos sistemas digitales escalables que mejoran la eficiencia, reducen costos e impulsan un crecimiento medible, con ejecución clara y sin tácticas genéricas.'
				),
				'flmm-sv-hero__lead flmm-rv'
			)
			. flmm_buttons(
				array(
					array( $t( 'Get in touch', 'Escríbenos' ) . ' <span class="flmm-arr">→</span>', '#contact' ),
					array( $t( 'See services', 'Ver servicios' ), home_url( $es ? '/es/#services' : '/#services' ), 'ghost' ),
				),
				'flmm-ctas flmm-rv'
			),
			array( 'className' => 'flmm-sv-hero', 'layout' => array( 'type' => 'default' ) )
		);
	$html = flmm_group( $hero, array( 'align' => 'full', 'className' => 'flmm-sv-top flmm-about-top', 'layout' => array( 'type' => 'constrained' ) ) );

	// Qué hacemos.
	$html .= flmm_section(
		flmm_sec_head( $t( 'What we do', 'Qué hacemos' ), $t( 'Marketing as a predictable growth engine.', 'El marketing como un motor de crecimiento predecible.' ), $t( 'We partner with companies that want to modernize their digital operations. Every solution is built for efficiency, scalability and real business impact.', 'Trabajamos con empresas que quieren modernizar su operación digital. Cada solución se diseña para la eficiencia, la escalabilidad y el impacto real en el negocio.' ) )
		. $cards(
			array(
				array( $t( 'Performance marketing', 'Performance marketing' ), $t( 'Google, Meta, Reddit, TikTok and LinkedIn.', 'Google, Meta, Reddit, TikTok y LinkedIn.' ) ),
				array( $t( 'AI assistants and automation', 'Asistentes de IA y automatización' ), $t( 'Workflows with n8n, APIs and LLMs.', 'Flujos con n8n, APIs y LLMs.' ) ),
				array( $t( 'Analytics and tracking', 'Analítica y medición' ), $t( 'GA4, BigQuery and dashboards.', 'GA4, BigQuery y dashboards.' ) ),
				array( $t( 'AI SEO and AEO', 'SEO con IA y AEO' ), $t( 'Visibility in Google and AI search engines.', 'Visibilidad en Google y en buscadores con IA.' ) ),
				array( $t( 'Websites and funnels', 'Sitios web y embudos' ), $t( 'Built for conversion from the first visit.', 'Pensados para convertir desde la primera visita.' ) ),
			)
		),
		'',
		'what-we-do'
	);

	// Cómo trabajamos.
	$html .= flmm_section(
		flmm_sec_head( $t( 'How we work', 'Cómo trabajamos' ), $t( 'A senior extension of your team.', 'Una extensión senior de tu equipo.' ), $t( 'We design and implement solutions that reduce operational friction, improve decision-making and let teams scale without increasing headcount.', 'Diseñamos e implementamos soluciones que reducen la fricción operativa, mejoran la toma de decisiones y permiten escalar sin sumar personas al equipo.' ) )
		. $cards(
			array(
				array( $t( 'Strategy first', 'Primero la estrategia' ), $t( 'Execution comes second, with clear goals.', 'La ejecución viene después, con metas claras.' ) ),
				array( $t( 'Data over assumptions', 'Datos antes que suposiciones' ), $t( 'Decisions backed by reliable measurement.', 'Decisiones respaldadas por una medición confiable.' ) ),
				array( $t( 'Automation over manual work', 'Automatización antes que trabajo manual' ), $t( 'Less repetitive work, more time for what matters.', 'Menos trabajo repetitivo y más tiempo para lo importante.' ) ),
				array( $t( 'Long-term systems', 'Sistemas de largo plazo' ), $t( 'Not short-term hacks.', 'No atajos de corto plazo.' ) ),
			)
		),
		'is-surface'
	);

	// Con quién trabajamos.
	$who = '';
	foreach ( array(
		array( 'Better performance from paid media', 'Mejor rendimiento de sus medios pagados' ),
		array( 'Clear and reliable analytics', 'Analítica clara y confiable' ),
		array( 'Smarter use of AI and automation', 'Un uso más inteligente de la IA y la automatización' ),
		array( 'Stronger digital foundations for growth', 'Bases digitales más sólidas para crecer' ),
	) as $item ) {
		$who .= flmm_bo( 'list-item' ) . '<li>' . $t( $item[0], $item[1] ) . '</li><!-- /wp:list-item -->';
	}
	$html .= flmm_section(
		flmm_sec_head( $t( 'Who we work with', 'Con quién trabajamos' ), $t( 'Startups, SMBs and growing companies.', 'Startups, pymes y empresas en crecimiento.' ), $t( 'Across the US and international markets, for teams that need:', 'En EE. UU. y otros mercados, para equipos que necesitan:' ) )
		. flmm_bo( 'list', array( 'className' => 'is-style-dots flmm-who' ) ) . '<ul class="wp-block-list is-style-dots flmm-who">' . $who . '</ul><!-- /wp:list -->'
	);

	// Forma de pensar.
	$html .= flmm_section(
		flmm_sec_head( $t( 'Our mindset', 'Nuestra forma de pensar' ), $t( 'Efficiency, clarity and leverage.', 'Eficiencia, claridad y apalancamiento.' ), $t( 'Marketing is not just about visibility. Our goal is to help companies build digital systems that compound over time.', 'El marketing no se trata solo de visibilidad. Nuestro objetivo es ayudar a las empresas a construir sistemas digitales que rinden más con el tiempo.' ) )
		. $cards(
			array(
				array( $t( 'Spend smarter', 'Invertir mejor' ), $t( 'Budget where it moves revenue.', 'Presupuesto donde mueve los ingresos.' ) ),
				array( $t( 'Move faster', 'Avanzar más rápido' ), $t( 'Short cycles of testing and learning.', 'Ciclos cortos de prueba y aprendizaje.' ) ),
				array( $t( 'Decide better', 'Decidir mejor' ), $t( 'Fewer, cleaner signals.', 'Menos señales y más limpias.' ) ),
				array( $t( 'Compound over time', 'Rendir con el tiempo' ), $t( 'Systems that keep improving.', 'Sistemas que siguen mejorando.' ) ),
			)
		),
		'is-surface'
	);

	// Equipo y trayectoria (mismos datos del home).
	$html .= flmm_pattern_team( $lang, $home['team'] );
	$results         = $home['results'];
	$results['text'] = array(
		'en' => flmm_tx( $results['text'], 'en' ) . ' Based in Florida, USA, working remotely with teams worldwide.',
		'es' => flmm_tx( $results['text'], 'es' ) . ' Con base en Florida, EE. UU., y trabajo remoto con equipos de todo el mundo.',
	);
	$html .= flmm_pattern_results( $lang, $results );
	return $html;
}
