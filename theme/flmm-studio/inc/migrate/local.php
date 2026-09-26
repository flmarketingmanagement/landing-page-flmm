<?php
/**
 * Páginas locales (Florida y Lakeland) en EN y ES.
 *
 * Datos confirmados: base en Florida, equipo senior bilingüe, experiencia en 17+ países
 * y tres clientes en Florida (se muestran solo por su dominio, sin resultados).
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Slugs de las páginas locales (EN => ES).
 */
function flmm_mig_local_slugs() {
	return array(
		'marketing-agency-florida'   => 'agencia-de-marketing-florida',
		'digital-marketing-lakeland' => 'marketing-digital-lakeland',
	);
}

/**
 * Área que atiende cada página (schema Service > areaServed).
 *
 * @param string $slug Slug EN.
 * @return array
 */
function flmm_mig_local_area( $slug ) {
	$florida = array( '@type' => 'State', 'name' => 'Florida', 'containedInPlace' => array( '@type' => 'Country', 'name' => 'United States' ) );
	if ( 'digital-marketing-lakeland' === $slug ) {
		return array( '@type' => 'City', 'name' => 'Lakeland', 'containedInPlace' => $florida );
	}
	return $florida;
}

/**
 * Ilustración de cada página local (clave de flmm_mig_media()).
 *
 * @param string $slug Slug EN.
 * @return string
 */
function flmm_mig_local_image_key( $slug ) {
	return 'digital-marketing-lakeland' === $slug ? 'seo-aeo' : 'performance-marketing';
}

/**
 * URL de una página local (EN o ES). Se arma con los slugs para que las dos páginas se enlacen desde la primera ejecución.
 *
 * @param string $slug Slug EN.
 * @param string $lang Idioma.
 * @return string
 */
function flmm_mig_local_url( $slug, $lang ) {
	$map = flmm_mig_local_slugs();
	return 'es' === $lang ? home_url( '/es/' . $map[ $slug ] . '/' ) : home_url( '/' . $slug . '/' );
}

/**
 * Tarjetas numeradas (mismo diseño que About).
 *
 * @param array $items Lista de array( título, texto ).
 * @return string
 */
function flmm_mig_local_cards( $items ) {
	$html = '';
	foreach ( $items as $i => $item ) {
		$html .= flmm_group(
			flmm_p( sprintf( '%02d', $i + 1 ), 'flmm-n' ) . flmm_h( 3, $item[0] ) . flmm_p( $item[1] ),
			array( 'className' => 'flmm-inc__item flmm-rv', 'layout' => array( 'type' => 'default' ) )
		);
	}
	return flmm_group( $html, array( 'className' => 'flmm-inc', 'layout' => array( 'type' => 'default' ) ) );
}

/**
 * Lista con viñetas.
 *
 * @param array $items Elementos (HTML permitido).
 * @return string
 */
function flmm_mig_local_list( $items ) {
	$li = '';
	foreach ( $items as $item ) {
		$li .= flmm_bo( 'list-item' ) . '<li>' . $item . '</li><!-- /wp:list-item -->';
	}
	return flmm_bo( 'list', array( 'className' => 'is-style-dots flmm-who' ) ) . '<ul class="wp-block-list is-style-dots flmm-who">' . $li . '</ul><!-- /wp:list -->';
}

/**
 * Enlaces a los clientes de Florida.
 *
 * @return array
 */
function flmm_mig_local_clients() {
	$out = array();
	foreach ( array( 'https://arthouseboca.com/' => 'arthouseboca.com', 'https://www.habiaunavezunafoto.com/' => 'habiaunavezunafoto.com', 'https://www.hart.live/' => 'hart.live' ) as $url => $label ) {
		$out[] = '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html( $label ) . '</a>';
	}
	return $out;
}

/**
 * Datos de cada página local por idioma.
 *
 * @param string $slug Slug EN.
 * @param string $lang Idioma.
 * @return array( title, h1, lead, sections, faq_title, faq )
 */
function flmm_mig_local_data( $slug, $lang ) {
	$es      = 'es' === $lang;
	$t       = static function ( $en, $es_text ) use ( $es ) {
		return $es ? $es_text : $en;
	};
	$base    = $es ? home_url( '/es/' ) : home_url( '/' );
	$svc     = static function ( $key ) use ( $es ) {
		$map = flmm_mig_es_slugs();
		return $es ? home_url( '/es/' . $map[ $key ] . '/' ) : home_url( '/' . $key . '/' );
	};
	$link    = static function ( $url, $text ) {
		return '<a href="' . esc_url( $url ) . '">' . $text . '</a>';
	};
	$clients = flmm_mig_local_clients();

	if ( 'digital-marketing-lakeland' === $slug ) {
		$florida = flmm_mig_local_url( 'marketing-agency-florida', $lang );
		return array(
			'title' => $t( 'SEO & Digital Marketing Services in Lakeland, FL', 'SEO y marketing digital en Lakeland, Florida' ),
			'h1'    => $t( 'SEO and digital marketing services in Lakeland, FL', 'Servicios de SEO y marketing digital en Lakeland, Florida' ),
			'lead'  => $t(
				'<strong>FL Marketing Management</strong> offers SEO and digital marketing services for businesses in Lakeland, Florida: local SEO and AEO so you show up on Google, Google Maps and AI answers, plus Google and Meta Ads, websites and analytics. One senior, bilingual team plans the work, runs it and reports on the leads and sales each channel brings.',
				'<strong>FL Marketing Management</strong> ofrece servicios de SEO y marketing digital para empresas de Lakeland, Florida: SEO local y AEO para aparecer en Google, Google Maps y en las respuestas de la IA, además de Google Ads y Meta Ads, sitios web y analítica. Un equipo senior y bilingüe planifica, ejecuta y reporta los leads y las ventas que trae cada canal.'
			),
			'sections' => array(
				array(
					'label' => $t( 'Services', 'Servicios' ),
					'title' => $t( 'What we do for Lakeland businesses.', 'Qué hacemos por las empresas de Lakeland.' ),
					'text'  => $t( 'Local customers search on Google, ask AI assistants and compare options on their phone. We cover each step, from being found to turning the visit into a lead or a sale.', 'Los clientes locales buscan en Google, preguntan a asistentes de IA y comparan opciones en el celular. Cubrimos cada paso, desde aparecer hasta convertir la visita en un lead o una venta.' ),
					'cards' => array(
						array( $t( 'Local SEO', 'SEO local' ), $t( 'Google Business Profile, local pages, technical SEO and content for the searches your customers make in Lakeland and Polk County.', 'Perfil de Empresa de Google, páginas locales, SEO técnico y contenido para las búsquedas que hacen tus clientes en Lakeland y el condado de Polk.' ) ),
						array( $t( 'AEO', 'AEO' ), $t( 'Content structured so ChatGPT, Gemini and Google AI Overviews can cite your business. ', 'Contenido estructurado para que ChatGPT, Gemini y los AI Overviews de Google citen tu negocio. ' ) . $link( $svc( 'seo-aeo' ), $t( 'SEO + AEO', 'SEO + AEO' ) ) ),
						array( $t( 'Google Ads and Meta Ads', 'Google Ads y Meta Ads' ), $t( 'Campaigns for Lakeland and nearby areas, optimized for calls, forms and sales. ', 'Campañas para Lakeland y sus alrededores, optimizadas para llamadas, formularios y ventas. ' ) . $link( $svc( 'performance-marketing' ), $t( 'Performance marketing', 'Performance marketing' ) ) ),
						array( $t( 'Websites and e-commerce', 'Sitios web y e-commerce' ), $t( 'Fast sites built to convert, on WordPress or Shopify. ', 'Sitios rápidos pensados para convertir, en WordPress o Shopify. ' ) . $link( $svc( 'website' ), $t( 'Websites', 'Sitios web' ) ) ),
						array( $t( 'Analytics', 'Analítica' ), $t( 'GA4, Google Tag Manager and dashboards to know which channel brings each customer. ', 'GA4, Google Tag Manager y dashboards para saber qué canal trae cada cliente. ' ) . $link( $svc( 'analytics' ), $t( 'Analytics', 'Analítica' ) ) ),
					),
				),
				array(
					'label' => $t( 'Why us', 'Por qué nosotros' ),
					'title' => $t( 'A Florida team, not a call center.', 'Un equipo en Florida, no un call center.' ),
					'text'  => $t( 'You work directly with the senior people who plan and run your marketing.', 'Trabajas directamente con las personas senior que planifican y ejecutan tu marketing.' ),
					'list'  => array(
						$t( 'Based in Florida, in your time zone.', 'Con base en Florida, en tu misma zona horaria.' ),
						$t( 'Bilingual team: campaigns and content in English and Spanish.', 'Equipo bilingüe: campañas y contenido en inglés y en español.' ),
						$t( 'Experience with B2B and B2C clients in more than 17 countries.', 'Experiencia con clientes B2B y B2C en más de 17 países.' ),
						$t( 'Clear reports tied to leads and sales, not vanity metrics.', 'Reportes claros ligados a leads y ventas, no a métricas de vanidad.' ),
					),
				),
				array(
					'label' => $t( 'Clients', 'Clientes' ),
					'title' => $t( 'Florida businesses we have worked with.', 'Empresas de Florida con las que hemos trabajado.' ),
					'text'  => $florida ? $t( 'We also work with businesses across the state. ', 'También trabajamos con empresas de todo el estado. ' ) . $link( $florida, $t( 'Marketing agency in Florida', 'Agencia de marketing en Florida' ) ) : '',
					'list'  => $clients,
				),
			),
			'faq_title' => $t( 'SEO marketing services in Lakeland: FAQ', 'Preguntas frecuentes sobre SEO y marketing digital en Lakeland' ),
			'faq'       => array(
				array(
					$t( 'What do SEO marketing services in Lakeland include?', '¿Qué incluyen los servicios de SEO y marketing digital en Lakeland?' ),
					$t( 'They include a technical review of your website, keyword research for the searches people make in Lakeland, optimized service and location pages, your Google Business Profile, content that answers customer questions, and AEO so AI assistants can cite you. We also set up measurement to connect rankings and traffic with calls, forms and sales.', 'Incluyen una revisión técnica de tu sitio, investigación de las búsquedas que se hacen en Lakeland, páginas de servicios y ubicación optimizadas, tu Perfil de Empresa de Google, contenido que responde las preguntas de tus clientes y AEO para que la IA te cite. También configuramos la medición para conectar posiciones y tráfico con llamadas, formularios y ventas.' ),
				),
				array(
					$t( 'How long does local SEO take to show results?', '¿Cuánto tarda el SEO local en dar resultados?' ),
					$t( 'Technical fixes and Google Business Profile improvements can show impact within weeks. Rankings for competitive local searches usually take several months, depending on your website, your reviews and your competitors. While SEO builds, Google Ads can bring leads from day one, so many Lakeland businesses combine both.', 'Las correcciones técnicas y las mejoras en el Perfil de Empresa de Google pueden notarse en semanas. Posicionar búsquedas locales competitivas suele tomar varios meses, según tu sitio, tus reseñas y tu competencia. Mientras el SEO avanza, Google Ads puede traer leads desde el primer día, por eso muchas empresas de Lakeland combinan ambos.' ),
				),
				array(
					$t( 'Do you only offer SEO?', '¿Solo ofrecen SEO?' ),
					$t( 'No. Besides SEO and AEO, we run Google Ads, Meta Ads, TikTok and LinkedIn campaigns, build websites and online stores, set up analytics and automate marketing tasks with AI. You can hire one service or have one team handle your whole digital marketing plan.', 'No. Además de SEO y AEO, gestionamos campañas en Google Ads, Meta Ads, TikTok y LinkedIn, creamos sitios web y tiendas online, configuramos la analítica y automatizamos tareas de marketing con IA. Puedes contratar un solo servicio o que un mismo equipo lleve todo tu plan de marketing digital.' ),
				),
				array(
					$t( 'Can you market my Lakeland business in Spanish?', '¿Pueden hacer marketing en español para mi negocio en Lakeland?' ),
					$t( 'Yes. Our team works in English and Spanish, so we can create campaigns, landing pages and content for Spanish-speaking customers in Lakeland and the rest of Florida, and measure each language separately in your reports to see where your budget performs best.', 'Sí. Nuestro equipo trabaja en inglés y en español, así que podemos crear campañas, páginas y contenido para clientes hispanohablantes en Lakeland y el resto de Florida, y medir cada idioma por separado para ver dónde rinde mejor tu inversión.' ),
				),
			),
		);
	}

	$lakeland = flmm_mig_local_url( 'digital-marketing-lakeland', $lang );
	return array(
		'title' => $t( 'Performance Marketing Agency in Florida', 'Agencia de performance marketing en Florida' ),
		'h1'    => $t( 'Performance marketing agency in Florida', 'Agencia de performance marketing en Florida' ),
		'lead'  => $t(
			'<strong>FL Marketing Management</strong> is a Florida-based performance marketing agency. We plan and run Google, Meta, TikTok and LinkedIn campaigns, SEO and AEO, and analytics for Florida businesses that need measurable growth, with one senior team that works in English and Spanish and reports on revenue, not vanity metrics.',
			'<strong>FL Marketing Management</strong> es una agencia de performance marketing con base en Florida. Planificamos y ejecutamos campañas en Google, Meta, TikTok y LinkedIn, SEO y AEO, y analítica para empresas de Florida que necesitan un crecimiento medible, con un solo equipo senior que trabaja en inglés y en español y reporta ingresos, no métricas de vanidad.'
		),
		'sections' => array(
			array(
				'label' => $t( 'Services', 'Servicios' ),
				'title' => $t( 'What we do for Florida businesses.', 'Qué hacemos por las empresas de Florida.' ),
				'text'  => $t( 'Every channel is planned around one goal: more customers at a cost that makes sense for your business.', 'Cada canal se planifica con un objetivo: más clientes a un costo que tenga sentido para tu negocio.' ),
				'cards' => array(
					array( $t( 'Performance marketing', 'Performance marketing' ), $t( 'Google, Meta, TikTok and LinkedIn campaigns optimized for ROAS, CPA and revenue. ', 'Campañas en Google, Meta, TikTok y LinkedIn optimizadas por ROAS, CPA e ingresos. ' ) . $link( $svc( 'performance-marketing' ), $t( 'See service', 'Ver servicio' ) ) ),
					array( $t( 'SEO + AEO', 'SEO + AEO' ), $t( 'Visibility on Google and in answers from ChatGPT, Gemini and Perplexity. ', 'Visibilidad en Google y en las respuestas de ChatGPT, Gemini y Perplexity. ' ) . $link( $svc( 'seo-aeo' ), $t( 'See service', 'Ver servicio' ) ) ),
					array( $t( 'Analytics and tracking', 'Analítica y medición' ), $t( 'GA4, Google Tag Manager and dashboards you can trust. ', 'GA4, Google Tag Manager y dashboards confiables. ' ) . $link( $svc( 'analytics' ), $t( 'See service', 'Ver servicio' ) ) ),
					array( $t( 'Websites and e-commerce', 'Sitios web y e-commerce' ), $t( 'Sites and online stores built to convert. ', 'Sitios y tiendas online pensados para convertir. ' ) . $link( $svc( 'website' ), $t( 'See service', 'Ver servicio' ) ) ),
					array( $t( 'AI automation', 'Automatización con IA' ), $t( 'AI assistants and workflows that save your team hours. ', 'Asistentes de IA y flujos que le ahorran horas a tu equipo. ' ) . $link( $svc( 'ai-assistants' ), $t( 'See service', 'Ver servicio' ) ) ),
				),
			),
			array(
				'label' => $t( 'Why us', 'Por qué nosotros' ),
				'title' => $t( 'A senior Florida team that speaks your customers’ language.', 'Un equipo senior en Florida que habla el idioma de tus clientes.' ),
				'text'  => $t( 'Florida is a bilingual market. We plan, write and measure campaigns in English and Spanish.', 'Florida es un mercado bilingüe. Planificamos, escribimos y medimos campañas en inglés y en español.' ),
				'list'  => array(
					$t( 'Based in Florida, working in your time zone.', 'Con base en Florida, en tu misma zona horaria.' ),
					$t( 'One senior team for strategy, media, content and data.', 'Un solo equipo senior para estrategia, medios, contenido y datos.' ),
					$t( 'Experience with B2B and B2C clients in more than 17 countries.', 'Experiencia con clientes B2B y B2C en más de 17 países.' ),
					$t( 'Reports tied to revenue, leads and customer acquisition cost.', 'Reportes ligados a ingresos, leads y costo de adquisición.' ),
				),
			),
			array(
				'label' => $t( 'Clients', 'Clientes' ),
				'title' => $t( 'Florida businesses we have worked with.', 'Empresas de Florida con las que hemos trabajado.' ),
				'text'  => $t( 'We work remotely with businesses anywhere in Florida, from Lakeland and Tampa to Orlando, Miami and Boca Raton.', 'Trabajamos de forma remota con empresas de cualquier parte de Florida, desde Lakeland y Tampa hasta Orlando, Miami y Boca Raton.' ) . ( $lakeland ? ' ' . $link( $lakeland, $t( 'Digital marketing in Lakeland', 'Marketing digital en Lakeland' ) ) : '' ),
				'list'  => $clients,
			),
		),
		'faq_title' => $t( 'Florida performance marketing agency: FAQ', 'Preguntas frecuentes sobre nuestra agencia de performance marketing en Florida' ),
		'faq'       => array(
			array(
				$t( 'What does a performance marketing agency in Florida do?', '¿Qué hace una agencia de performance marketing en Florida?' ),
				$t( 'It plans, launches and optimizes paid and organic channels around measurable results: leads, sales, CPA and ROAS. For Florida businesses that usually means Google Ads for high-intent searches, Meta and TikTok for demand, SEO and AEO for long-term visibility, and clean analytics to know what each channel returns.', 'Planifica, lanza y optimiza canales pagados y orgánicos en función de resultados medibles: leads, ventas, CPA y ROAS. Para una empresa de Florida suele significar Google Ads para búsquedas con intención de compra, Meta y TikTok para generar demanda, SEO y AEO para visibilidad a largo plazo y una analítica ordenada para saber qué retorna cada canal.' ),
			),
			array(
				$t( 'Do you only work with businesses in Florida?', '¿Solo trabajan con empresas de Florida?' ),
				$t( 'No. We are based in Florida and work with businesses across the state, but our clients also include B2B and B2C companies in the rest of the United States and Latin America. We have experience in more than 17 countries, always with the same senior team and remote collaboration.', 'No. Tenemos base en Florida y trabajamos con empresas de todo el estado, pero también con compañías B2B y B2C del resto de Estados Unidos y de Latinoamérica. Tenemos experiencia en más de 17 países, siempre con el mismo equipo senior y trabajo remoto.' ),
			),
			array(
				$t( 'Can you run campaigns in Spanish for Florida customers?', '¿Pueden hacer campañas en español para clientes de Florida?' ),
				$t( 'Yes. Our team is bilingual, so we create ads, landing pages and content in English and Spanish, and we split budgets and reports by language. That way you can reach Florida’s Spanish-speaking customers with messages written for them, not machine translations of your English campaigns.', 'Sí. Nuestro equipo es bilingüe, así que creamos anuncios, páginas y contenido en inglés y en español, y separamos presupuestos y reportes por idioma. Así llegas a los clientes hispanohablantes de Florida con mensajes escritos para ellos, no con traducciones automáticas de tus campañas en inglés.' ),
			),
			array(
				$t( 'How do you measure results?', '¿Cómo miden los resultados?' ),
				$t( 'We measure what matters to your business: sales, qualified leads, cost per acquisition and return on ad spend. Before launching, we check that GA4, Google Tag Manager and conversion tracking are set up correctly, and then we report every month with clear numbers and the next actions we recommend.', 'Medimos lo que importa para tu negocio: ventas, leads calificados, costo por adquisición y retorno de la inversión publicitaria. Antes de lanzar, revisamos que GA4, Google Tag Manager y el seguimiento de conversiones estén bien configurados, y luego reportamos cada mes con números claros y las siguientes acciones que recomendamos.' ),
			),
		),
	);
}

/**
 * Contenido de una página local.
 *
 * @param string $slug Slug EN.
 * @param string $lang Idioma.
 * @return array( title, content, template )
 */
function flmm_mig_local_page( $slug, $lang ) {
	$es   = 'es' === $lang;
	$d    = flmm_mig_local_data( $slug, $lang );
	$hero = flmm_bo( 'flmm/breadcrumbs', array(), true )
		. flmm_group(
			flmm_p( 'digital-marketing-lakeland' === $slug ? 'Lakeland, FL' : 'Florida', 'is-style-label' )
			. flmm_h( 1, $d['h1'], 'flmm-sv-hero__title flmm-rv' )
			. flmm_p( $d['lead'], 'flmm-sv-hero__lead flmm-rv' )
			. flmm_buttons(
				array(
					array( ( $es ? 'Escríbenos' : 'Get in touch' ) . ' <span class="flmm-arr">→</span>', '#contact' ),
					array( $es ? 'Ver servicios' : 'See services', home_url( $es ? '/es/#services' : '/#services' ), 'ghost' ),
				),
				'flmm-ctas flmm-rv'
			),
			array( 'className' => 'flmm-sv-hero', 'layout' => array( 'type' => 'default' ) )
		);
	$image = function_exists( 'flmm_mig_image' ) ? flmm_mig_image( flmm_mig_local_image_key( $slug ), $lang ) : false;
	if ( $image ) {
		$hero .= flmm_bo( 'image', array( 'id' => (int) $image['id'], 'sizeSlug' => 'full', 'linkDestination' => 'none', 'className' => 'flmm-sv-img flmm-rv' ) )
			. '<figure class="wp-block-image size-full flmm-sv-img flmm-rv"><img src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( $image['alt'] ) . '" class="wp-image-' . (int) $image['id'] . '"/></figure><!-- /wp:image -->';
	}
	$html = flmm_group( $hero, array( 'align' => 'full', 'className' => 'flmm-sv-top flmm-about-top', 'layout' => array( 'type' => 'constrained' ) ) );

	foreach ( $d['sections'] as $i => $s ) {
		$body  = ! empty( $s['cards'] ) ? flmm_mig_local_cards( $s['cards'] ) : flmm_mig_local_list( $s['list'] );
		$html .= flmm_section( flmm_sec_head( $s['label'], $s['title'], $s['text'] ) . $body, 1 === $i % 2 ? 'is-surface' : '' );
	}

	$faq = array();
	foreach ( $d['faq'] as $qa ) {
		$faq[] = array( 'q' => array( $lang => $qa[0] ), 'a' => array( $lang => $qa[1] ) );
	}
	$html .= flmm_pattern_service_faq( $lang, array( 'faq' => $faq, 'headings' => array( 'faq' => array( $lang => $d['faq_title'] ) ) ) );

	$seo = flmm_mig_page_seo();
	return array( 'title' => $d['title'], 'content' => $html, 'template' => 'page-landing', 'excerpt' => isset( $seo[ $slug ][ $lang ][1] ) ? $seo[ $slug ][ $lang ][1] : '' );
}
