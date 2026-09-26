<?php
/**
 * Textos del theme en EN (origen) y ES.
 *
 * El idioma sale de Polylang cuando está activo y, si no, del locale del sitio.
 * Cada texto se registra en Polylang (Idiomas > Traducciones) para poder editarlo
 * sin tocar código; si allí no tiene traducción, se usa el diccionario de abajo.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Idioma actual: 'en' o 'es'.
 */
function flmm_lang() {
	if ( function_exists( 'pll_current_language' ) ) {
		$lang = pll_current_language( 'slug' );
		if ( $lang ) {
			return 'es' === $lang ? 'es' : 'en';
		}
	}
	return 0 === strpos( determine_locale(), 'es' ) ? 'es' : 'en';
}

/**
 * Diccionario EN => ES de los textos del theme.
 */
function flmm_strings() {
	static $strings = null;
	if ( null !== $strings ) {
		return $strings;
	}
	$strings = array(
		// Marca y navegación.
		'Boutique digital agency'            => 'Agencia digital boutique',
		'Main navigation'                    => 'Navegación principal',
		'Menu'                               => 'Menú',
		'Close menu'                         => 'Cerrar menú',
		'Services'                           => 'Servicios',
		'Platforms'                          => 'Plataformas',
		'Method'                             => 'Método',
		'Team'                               => 'Equipo',
		'Blog'                               => 'Blog',
		'Contact'                            => 'Contacto',
		'Let’s talk'                         => 'Hablemos',
		'Language'                           => 'Idioma',
		'Skip to content'                    => 'Saltar al contenido',
		'Home'                               => 'Inicio',
		'Breadcrumb'                         => 'Ruta de navegación',
		// Footer.
		'Navigation'                         => 'Navegación',
		'Company'                            => 'Empresa',
		'Social'                             => 'Redes',
		'About us'                           => 'Sobre nosotros',
		'Privacy Policy'                     => 'Política de privacidad',
		'Send me an email'                   => 'Envíame un mail',
		'AMA Professional Certified Marketer, Marketing Management' => 'AMA Professional Certified Marketer, Marketing Management',
		// Contacto.
		'Ready to grow?'                     => '¿Listo para crecer?',
		'Let’s talk.'                        => 'Conversemos.',
		'Name'                               => 'Nombre',
		'Email'                              => 'Email',
		'How can we help?'                   => '¿En qué te ayudamos?',
		'Send'                               => 'Enviar',
		'or reach me by'                     => 'o escríbeme por',
		'Talk to our agent, Marky Digital, to learn more about what we do.' => 'Habla con nuestro agente, Marky Digital, para conocer más a fondo lo que hacemos.',
		'Mail'                               => 'Mail',
		'Telegram'                           => 'Telegram',
		'Please fill in all three fields.'   => 'Completa los tres campos, por favor.',
		'Opening your email…'                => 'Abriendo tu correo…',
		'Website inquiry: '                  => 'Contacto web: ',
		'Message sent'                       => 'Mensaje enviado',
		'Services of interest'               => 'Servicios de interés',
		'Select one or more services'        => 'Elige uno o más servicios',
		'%d selected'                        => '%d seleccionados',
		'Custom platforms'                   => 'Plataformas a medida',
		'Thanks! We received your message and will reply soon.' => '¡Gracias! Recibimos tu mensaje y te responderemos pronto.',
		// Servicios.
		'Updated: %s'                        => 'Actualizado: %s',
		'Get in touch'                       => 'Escríbenos',
		'FAQ'                                => 'Preguntas frecuentes',
		// Blog.
		'Ideas for growing with data and AI.' => 'Ideas para crecer con datos e IA.',
		'Articles on performance marketing, SEO, AEO and artificial intelligence, written by our team from what we see in real accounts.' => 'Artículos sobre performance marketing, SEO, AEO e inteligencia artificial, escritos por nuestro equipo a partir de lo que vemos en cuentas reales.',
		'All'                                => 'Todos',
		'Filter by category'                 => 'Filtrar por categoría',
		'Read article'                       => 'Leer artículo',
		'%d min read'                        => '%d min de lectura',
		'Updated %s'                         => 'Actualizado el %s',
		'In this article'                    => 'En este artículo',
		'Table of contents'                  => 'Índice',
		'Need help with this?'               => '¿Necesitas ayuda con esto?',
		'We review your account and tell you what is holding back your growth.' => 'Revisamos tu cuenta y te decimos qué está frenando tu crecimiento.',
		'Share'                              => 'Compartir',
		'Copy link'                          => 'Copiar link',
		'Copied!'                            => '¡Copiado!',
		'Keep reading'                       => 'Sigue leyendo',
		'Related articles'                   => 'Artículos relacionados',
		'Previous'                           => 'Anterior',
		'Next'                               => 'Siguiente',
		'Newer articles'                     => 'Artículos más recientes',
		'Older articles'                     => 'Artículos anteriores',
		'No articles yet.'                   => 'Aún no hay artículos.',
		'Category'                           => 'Categoría',
		// Búsqueda y 404.
		'Search'                             => 'Buscar',
		'Search results'                     => 'Resultados de búsqueda',
		'Results for “%s”'                   => 'Resultados para “%s”',
		'Nothing matched your search. Try different words.' => 'No encontramos resultados. Prueba con otras palabras.',
		'Page not found'                     => 'Página no encontrada',
		'The page you are looking for does not exist or was moved.' => 'La página que buscas no existe o cambió de dirección.',
		'Back to home'                       => 'Volver al inicio',
	);
	return $strings;
}

/**
 * Traduce un texto del theme al idioma actual.
 *
 * @param string $text Texto en inglés.
 * @return string
 */
function flmm__( $text ) {
	if ( 'es' !== flmm_lang() ) {
		return $text;
	}
	if ( function_exists( 'pll__' ) ) {
		$translated = pll__( $text );
		if ( $translated !== $text ) {
			return $translated;
		}
	}
	$strings = flmm_strings();
	return isset( $strings[ $text ] ) ? $strings[ $text ] : $text;
}

/**
 * Imprime un texto traducido y escapado.
 *
 * @param string $text Texto en inglés.
 */
function flmm_e( $text ) {
	echo esc_html( flmm__( $text ) );
}

/**
 * Registra los textos en Polylang para editarlos desde el escritorio.
 */
function flmm_register_pll_strings() {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}
	foreach ( array_keys( flmm_strings() ) as $text ) {
		pll_register_string( 'flmm-' . md5( $text ), $text, 'FLMM Studio', strlen( $text ) > 60 );
	}
}
add_action( 'init', 'flmm_register_pll_strings' );

/**
 * URL de inicio en el idioma actual.
 *
 * @param string $path Ruta relativa, por ejemplo '#services'.
 * @return string
 */
function flmm_home_url( $path = '' ) {
	$home = function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
	return trailingslashit( $home ) . ltrim( $path, '/' );
}

/**
 * URL de una página por su slug (en inglés), en el idioma actual si existe traducción.
 *
 * @param string $slug Slug de la página en inglés.
 * @return string
 */
function flmm_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		$id = $page->ID;
		if ( function_exists( 'pll_get_post' ) ) {
			$translated = pll_get_post( $id );
			if ( $translated ) {
				$id = $translated;
			}
		}
		return get_permalink( $id );
	}
	return home_url( '/' . trailingslashit( $slug ) );
}

/**
 * Formatea una fecha según el idioma actual.
 *
 * @param string|int $date Fecha (string o timestamp).
 * @param string     $format 'long' (26 de septiembre de 2026) o 'month' (septiembre de 2026).
 * @return string
 */
function flmm_date( $date, $format = 'long' ) {
	$ts     = is_numeric( $date ) ? (int) $date : strtotime( $date );
	$es     = array( 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre' );
	$en     = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
	$month  = (int) gmdate( 'n', $ts ) - 1;
	$year   = gmdate( 'Y', $ts );
	$day    = (int) gmdate( 'j', $ts );
	if ( 'es' === flmm_lang() ) {
		return 'month' === $format ? $es[ $month ] . ' de ' . $year : $day . ' de ' . $es[ $month ] . ' de ' . $year;
	}
	return 'month' === $format ? $en[ $month ] . ' ' . $year : $en[ $month ] . ' ' . $day . ', ' . $year;
}
