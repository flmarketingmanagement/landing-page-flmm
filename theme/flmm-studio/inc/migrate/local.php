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
	return 'digital-marketing-lakeland' === $slug ? 'post-lakeland-agency' : 'post-florida-agency';
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
			'title' => $t( 'SEO Services in Lakeland, FL', 'Servicios de SEO en Lakeland, Florida' ),
			'h1'    => $t( 'SEO services in Lakeland, FL for local businesses', 'Servicios de SEO en Lakeland, Florida, para negocios locales' ),
			'alt'   => $t( 'SEO services in Lakeland: a local store, a phone and a checklist connected to a pin on a city map with a lake', 'Servicios de SEO en Lakeland: una tienda local, un celular y una lista conectados a un punto en el mapa de una ciudad con un lago' ),
			'lead'  => $t(
				'<strong>FL Marketing Management</strong> offers SEO services in Lakeland, Florida, plus digital marketing for local businesses: local SEO and AEO so you show up on Google, Google Maps and AI answers, Google and Meta Ads, websites and analytics. One senior, bilingual team plans the work, runs it and reports on the leads and sales each channel brings.',
				'<strong>FL Marketing Management</strong> ofrece servicios de SEO en Lakeland, Florida, y marketing digital para negocios locales: SEO local y AEO para aparecer en Google, Google Maps y en las respuestas de la IA, además de Google Ads, Meta Ads, sitios web y analítica. Un equipo senior y bilingüe planifica, ejecuta y reporta los leads y ventas de cada canal.'
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
					'label' => $t( 'Guide', 'Guía' ),
					'title' => $t( 'How do SEO services in Lakeland work?', '¿Cómo funcionan los servicios de SEO en Lakeland?' ),
					'guide' => array(
						$t( 'Local SEO in Lakeland is less about ranking for one big keyword and more about showing up for dozens of specific searches: a service plus a neighborhood, "near me" searches on a phone and questions people now ask AI assistants. Our SEO services in Lakeland work in three layers that build on each other.', 'El SEO local en Lakeland no se trata de posicionar una sola palabra clave, sino de aparecer en decenas de búsquedas concretas: un servicio más un barrio, búsquedas "cerca de mí" desde el celular y preguntas que hoy la gente le hace a los asistentes de IA. Nuestros servicios de SEO en Lakeland trabajan en tres capas que se apoyan entre sí.' ),
						array(
							array( $t( 'Your Google Business Profile', 'Tu Perfil de Empresa de Google' ), $t( 'It is often the first thing a Lakeland customer sees. We complete categories, services, service areas, hours and photos, answer reviews and publish updates, following the <a href="https://support.google.com/business/" target="_blank" rel="noreferrer noopener">Google Business Profile guidelines</a>. A complete, active profile helps you appear in the map results for Lakeland and the rest of Polk County.', 'Suele ser lo primero que ve un cliente de Lakeland. Completamos categorías, servicios, zonas de atención, horarios y fotos, respondemos reseñas y publicamos novedades, siguiendo las <a href="https://support.google.com/business/" target="_blank" rel="noreferrer noopener">pautas del Perfil de Empresa de Google</a>. Un perfil completo y activo ayuda a aparecer en los resultados del mapa en Lakeland y el resto del condado de Polk.' ) ),
							array( $t( 'Service and location pages', 'Páginas de servicios y ubicación' ), $t( 'Each main service gets a page that answers what customers ask before they call: what is included, how long it takes, which areas you cover and how to get a quote. Clear answers in the first lines help Google and AI assistants understand and cite your business, and they help visitors decide faster.', 'Cada servicio principal tiene una página que responde lo que los clientes preguntan antes de llamar: qué incluye, cuánto tarda, qué zonas cubres y cómo pedir una cotización. Las respuestas claras en las primeras líneas ayudan a Google y a los asistentes de IA a entender y citar tu negocio, y ayudan a las visitas a decidir más rápido.' ) ),
							array( $t( 'Reviews, mentions and measurement', 'Reseñas, menciones y medición' ), $t( 'Reviews and local mentions, such as chambers of commerce, partners and local media, build trust with people and with search engines. We connect Search Console, GA4 and call tracking so you can see which searches bring calls, forms and sales, and we adjust the plan every month based on that data.', 'Las reseñas y las menciones locales, como cámaras de comercio, socios y medios de la zona, generan confianza en las personas y en los buscadores. Conectamos Search Console, GA4 y el seguimiento de llamadas para que veas qué búsquedas traen llamadas, formularios y ventas, y ajustamos el plan cada mes según esos datos.' ) ),
						),
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
			'faq_title' => $t( 'SEO services in Lakeland: frequently asked questions', 'Servicios de SEO en Lakeland: preguntas frecuentes' ),
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
		'alt'   => $t( 'Performance marketing agency in Florida: the state map connected to campaign results, audiences and planning', 'Agencia de performance marketing en Florida: el mapa del estado conectado a resultados, audiencias y planificación de campañas' ),
		'lead'  => $t(
			'<strong>FL Marketing Management</strong> is a performance marketing agency in Florida. We plan and run Google, Meta, TikTok and LinkedIn campaigns, SEO and AEO, and analytics for Florida businesses that need measurable growth, with one senior team that works in English and Spanish and reports on revenue, not vanity metrics.',
			'<strong>FL Marketing Management</strong> es una agencia de performance marketing en Florida. Planificamos y ejecutamos campañas en Google, Meta, TikTok y LinkedIn, SEO y AEO, y analítica para empresas de Florida que necesitan un crecimiento medible, con un solo equipo senior que trabaja en inglés y en español y reporta ingresos, no métricas de vanidad.'
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
				'label' => $t( 'Guide', 'Guía' ),
				'title' => $t( 'How does a performance marketing agency in Florida plan your budget?', '¿Cómo planifica tu presupuesto una agencia de performance marketing en Florida?' ),
				'guide' => array(
					$t( 'A good performance marketing agency in Florida does not start with ads. It starts with your numbers: how much a customer is worth, how much you can pay to acquire one and how long you can wait for the return. With that, every channel gets a clear job and a budget that can be defended.', 'Una buena agencia de performance marketing en Florida no empieza por los anuncios. Empieza por tus números: cuánto vale un cliente, cuánto puedes pagar para conseguirlo y cuánto tiempo puedes esperar el retorno. Con eso, cada canal tiene una tarea clara y un presupuesto que se puede defender.' ),
					array(
						array( $t( 'Capture demand first', 'Primero, capturar la demanda' ), $t( 'Search campaigns on Google reach people who are already looking for what you sell in Tampa, Orlando, Miami or Lakeland. They usually get the first share of the budget because intent is high, and we track them with <a href="https://support.google.com/google-ads/" target="_blank" rel="noreferrer noopener">Google Ads conversion tracking</a> tied to real leads and sales.', 'Las campañas de búsqueda en Google llegan a personas que ya buscan lo que vendes en Tampa, Orlando, Miami o Lakeland. Suelen recibir la primera parte del presupuesto porque la intención es alta, y las medimos con el <a href="https://support.google.com/google-ads/" target="_blank" rel="noreferrer noopener">seguimiento de conversiones de Google Ads</a> ligado a leads y ventas reales.' ) ),
						array( $t( 'Then create demand', 'Luego, crear demanda' ), $t( 'Meta, TikTok and LinkedIn reach people before they search. Here we test creative and audiences in small steps and scale only what brings qualified customers at a cost that works. SEO and AEO run in parallel, because they build visibility that keeps working when the ads stop.', 'Meta, TikTok y LinkedIn llegan a las personas antes de que busquen. Aquí probamos creatividades y audiencias en pasos pequeños y escalamos solo lo que trae clientes calificados a un costo que funciona. El SEO y el AEO avanzan en paralelo, porque construyen una visibilidad que sigue funcionando cuando se apagan los anuncios.' ) ),
						array( $t( 'Plan for a bilingual market', 'Planificar para un mercado bilingüe' ), $t( 'Many Florida customers search and buy in Spanish. We write campaigns in both languages from the start, split budgets and reports by language and compare results, so you know where each dollar performs best instead of guessing.', 'Muchos clientes de Florida buscan y compran en español. Escribimos las campañas en los dos idiomas desde el inicio, separamos presupuestos y reportes por idioma y comparamos resultados, para que sepas dónde rinde mejor cada dólar en lugar de adivinar.' ) ),
					),
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
		'faq_title' => $t( 'Performance marketing agency in Florida: FAQ', 'Agencia de performance marketing en Florida: preguntas frecuentes' ),
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
			. '<figure class="wp-block-image size-full flmm-sv-img flmm-rv"><img src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( ! empty( $d['alt'] ) ? $d['alt'] : $image['alt'] ) . '" class="wp-image-' . (int) $image['id'] . '"/></figure><!-- /wp:image -->';
	}
	$html = flmm_group( $hero, array( 'align' => 'full', 'className' => 'flmm-sv-top flmm-about-top', 'layout' => array( 'type' => 'constrained' ) ) );

	foreach ( $d['sections'] as $i => $s ) {
		if ( ! empty( $s['guide'] ) ) {
			$inner = flmm_p( $s['guide'][0] );
			foreach ( $s['guide'][1] as $item ) {
				$inner .= flmm_h( 3, $item[0] ) . flmm_p( $item[1] );
			}
			$html .= flmm_section( flmm_sec_head( $s['label'], $s['title'] ) . flmm_group( $inner, array( 'className' => 'flmm-guide flmm-rv', 'layout' => array( 'type' => 'default' ) ) ), 1 === $i % 2 ? 'is-surface' : '' );
			continue;
		}
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
