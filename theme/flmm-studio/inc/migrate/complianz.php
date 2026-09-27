<?php
/**
 * Banner de cookies (Complianz) en EN y ES: traducciones de Polylang y política de cookies en español.
 *
 * Complianz registra sus textos en Polylang (grupo "complianz"). El texto base es el que está
 * guardado en Complianz; se cargan las dos direcciones (base EN o base ES) para que funcione
 * en cualquiera de los dos casos.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Textos del banner: array( EN, ES ).
 */
function flmm_mig_cookie_strings() {
	return array(
		array( 'Manage consent', 'Gestionar consentimiento' ),
		array( 'Manage cookie consent', 'Gestionar el consentimiento de cookies' ),
		array( 'Accept', 'Aceptar' ),
		array( 'Deny', 'Denegar' ),
		array( 'View preferences', 'Ver preferencias' ),
		array( 'Save preferences', 'Guardar preferencias' ),
		array( 'Functional', 'Funcionales' ),
		array( 'Statistics', 'Estadísticas' ),
		array( 'Statistics (anonymous)', 'Estadísticas (anónimas)' ),
		array( 'Marketing', 'Marketing' ),
		array( 'Preferences', 'Preferencias' ),
		array( 'Cookie Policy', 'Política de cookies' ),
		array( 'Privacy Policy', 'Política de privacidad' ),
		array(
			'We use cookies to understand how the site is used and to improve your experience. You can accept all cookies, deny the non-essential ones or choose your preferences. Denying consent may affect some features.',
			'Usamos cookies para entender cómo se usa el sitio y mejorar tu experiencia. Puedes aceptar todas las cookies, rechazar las no esenciales o elegir tus preferencias. No dar el consentimiento puede afectar algunas funciones.',
		),
		array(
			'The technical storage or access is strictly necessary to provide a service you explicitly requested or to transmit a communication over an electronic network.',
			'El almacenamiento o acceso técnico es estrictamente necesario para prestar un servicio que solicitaste de forma explícita o para transmitir una comunicación a través de una red electrónica.',
		),
		array(
			'The technical storage or access used exclusively for statistical purposes.',
			'El almacenamiento o acceso técnico que se usa exclusivamente con fines estadísticos.',
		),
		array(
			'The technical storage or access is required to create user profiles to send advertising, or to track the user across websites for similar marketing purposes.',
			'El almacenamiento o acceso técnico es necesario para crear perfiles de usuario para enviar publicidad o para rastrear al usuario en uno o varios sitios web con fines de marketing similares.',
		),
		// Textos por defecto de Complianz en español que quedaron como base (preferencias y estadísticas anónimas).
		array(
			'The technical storage or access is necessary for the legitimate purpose of storing preferences that are not requested by the subscriber or user.',
			'El almacenamiento o acceso técnico es necesario para la finalidad legítima de almacenar preferencias no solicitadas por el abonado o usuario.',
		),
		array(
			'The technical storage or access that is used exclusively for anonymous statistical purposes. Without a subpoena, voluntary compliance on the part of your Internet Service Provider, or additional records from a third party, information stored or retrieved for this purpose alone cannot usually be used to identify you.',
			'El almacenamiento o acceso técnico que se utiliza exclusivamente con fines estadísticos anónimos. Sin un requerimiento, el cumplimiento voluntario por parte de tu proveedor de servicios de Internet, o los registros adicionales de un tercero, la información almacenada o recuperada sólo para este propósito no se puede utilizar para identificarte.',
		),
		// Textos por defecto de Complianz en español (por si quedan como base).
		array(
			'Para ofrecer las mejores experiencias, utilizamos tecnologías como las cookies para almacenar y/o acceder a la información del dispositivo. El consentimiento de estas tecnologías nos permitirá procesar datos como el comportamiento de navegación o las identificaciones únicas en este sitio. No consentir o retirar el consentimiento, puede afectar negativamente a ciertas características y funciones.',
			'Para ofrecer las mejores experiencias, utilizamos tecnologías como las cookies para almacenar y/o acceder a la información del dispositivo. El consentimiento de estas tecnologías nos permitirá procesar datos como el comportamiento de navegación o las identificaciones únicas en este sitio. No consentir o retirar el consentimiento puede afectar negativamente a ciertas características y funciones.',
			'We use cookies to understand how the site is used and to improve your experience. You can accept all cookies, deny the non-essential ones or choose your preferences. Denying consent may affect some features.',
		),
	);
}

/**
 * Guarda las traducciones de Polylang para EN y ES.
 *
 * @return int Cantidad de textos cargados.
 */
function flmm_mig_cookie_translations() {
	if ( ! class_exists( 'PLL_MO' ) || ! class_exists( 'Translation_Entry' ) ) {
		return 0;
	}
	$pairs = array( 'en' => array(), 'es' => array() );
	foreach ( flmm_mig_cookie_strings() as $s ) {
		$en = isset( $s[2] ) ? $s[2] : $s[0];
		// Base EN: EN => EN, ES => ES. Base ES: EN => EN, ES => ES.
		$pairs['en'][ $s[0] ] = $en;
		$pairs['es'][ $s[0] ] = $s[1];
		$pairs['en'][ $s[1] ] = $en;
		$pairs['es'][ $s[1] ] = $s[1];
	}
	$count = 0;
	foreach ( $pairs as $slug => $map ) {
		$lang = PLL()->model->get_language( $slug );
		if ( ! $lang ) {
			continue;
		}
		$mo = new PLL_MO();
		$mo->import_from_db( $lang );
		foreach ( $map as $source => $translation ) {
			$mo->add_entry( new Translation_Entry( array( 'singular' => $source, 'translations' => array( $translation ) ) ) );
			++$count;
		}
		$mo->export_to_db( $lang );
	}
	return $count;
}

/**
 * Página de la política de cookies generada por Complianz (EN) y su traducción ES.
 *
 * @return array( 'en' => id, 'es' => id )
 */
function flmm_mig_cookie_pages() {
	global $wpdb;
	$ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'page' AND post_status = 'publish' AND ( post_content LIKE '%cmplz-document%' OR post_content LIKE '%complianz/document%' ) ORDER BY ID ASC" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	if ( ! $ids ) {
		return array();
	}
	$en = 0;
	foreach ( $ids as $id ) {
		$lang = pll_get_post_language( (int) $id );
		if ( ! $lang || 'en' === $lang ) {
			$en = (int) $id;
			break;
		}
	}
	if ( ! $en ) {
		return array();
	}
	if ( ! pll_get_post_language( $en ) ) {
		pll_set_post_language( $en, 'en' );
	}
	$es = (int) pll_get_post( $en, 'es' );
	$post = get_post( $en );
	$data = array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'post_title'     => 'Política de cookies',
		'post_name'      => 'politica-de-cookies',
		'post_content'   => $post->post_content,
		'comment_status' => 'closed',
	);
	if ( $es ) {
		$data['ID'] = $es;
		wp_update_post( wp_slash( $data ) );
	} else {
		$es = (int) wp_insert_post( wp_slash( $data ) );
	}
	if ( ! $es ) {
		return array( 'en' => $en );
	}
	pll_set_post_language( $es, 'es' );
	pll_save_post_translations( array( 'en' => $en, 'es' => $es ) );
	return array( 'en' => $en, 'es' => $es );
}

/**
 * Paso de la migración: traducciones del banner y política de cookies ES.
 */
function flmm_mig_step_cookies() {
	if ( ! defined( 'CMPLZ_VERSION' ) && ! function_exists( 'cmplz_get_option' ) ) {
		flmm_mig_log( 'Complianz no está activo: se omite el banner de cookies.', 'warn' );
		flmm_mig_state( 'cookies', array() );
		return;
	}
	$count = flmm_mig_cookie_translations();
	$pages = flmm_mig_cookie_pages();
	flmm_mig_state( 'cookies', $pages );
	if ( ! empty( $pages['es'] ) ) {
		flmm_mig_log( sprintf( 'Banner de cookies: %d traducciones EN/ES. Política de cookies: %s | %s', $count, get_permalink( $pages['en'] ), get_permalink( $pages['es'] ) ) );
	} else {
		flmm_mig_log( sprintf( 'Banner de cookies: %d traducciones EN/ES. No encontré la política de cookies de Complianz.', $count ), 'warn' );
	}
}
