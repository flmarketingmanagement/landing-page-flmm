<?php
/**
 * Migración del sitio al nuevo diseño (Herramientas > Migración FLMM).
 *
 * Cada paso es idempotente: se puede ejecutar varias veces sin duplicar contenido.
 * Pensado para correr primero en staging. Se elimina del theme cuando termine el lanzamiento.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/data.php';

/**
 * Pasos disponibles: clave => array( título, función ).
 */
function flmm_mig_steps() {
	return array(
		'languages' => array( '1. Idiomas (Polylang: EN sin prefijo, ES en /es/)', 'flmm_mig_step_languages' ),
		'assign'    => array( '2. Asignar inglés al contenido existente', 'flmm_mig_step_assign' ),
		'media'     => array( '3. Subir ilustraciones con texto alternativo EN y ES', 'flmm_mig_step_media' ),
		'services'  => array( '4. Servicios (10 páginas EN y ES con plantilla Servicio)', 'flmm_mig_step_services' ),
		'company'   => array( '5. Páginas de empresa (About, podcasts, privacidad, contacto)', 'flmm_mig_step_company' ),
		'home'      => array( '6. Home y blog (portada, página de entradas)', 'flmm_mig_step_home' ),
		'posts'     => array( '7. Blog: idiomas, pares EN/ES, categorías y correcciones', 'flmm_mig_step_posts' ),
		'author'    => array( '8. Autor: biografía EN y ES', 'flmm_mig_step_author' ),
		'menus'     => array( '9. Menús en ambos idiomas', 'flmm_mig_step_menus' ),
		'seo'       => array( '10. SEO (Rank Math): títulos, descripciones, imagen OG y ajustes', 'flmm_mig_step_seo' ),
		'redirects' => array( '11. Redirecciones 301 y páginas antiguas a borrador', 'flmm_mig_step_redirects' ),
	);
}

/**
 * Registro de la ejecución.
 *
 * @param string $msg Mensaje.
 * @param string $type ok|warn|error.
 */
function flmm_mig_log( $msg, $type = 'ok' ) {
	$GLOBALS['flmm_mig_log'][] = array( $type, $msg );
}

/**
 * Mapa guardado entre pasos (IDs creados).
 *
 * @param string $key   Clave.
 * @param mixed  $value Valor (si se omite, lee).
 * @return mixed
 */
function flmm_mig_state( $key, $value = null ) {
	$state = (array) get_option( 'flmm_migration_state', array() );
	if ( null === $value ) {
		return isset( $state[ $key ] ) ? $state[ $key ] : null;
	}
	$state[ $key ] = $value;
	update_option( 'flmm_migration_state', $state, false );
	return $value;
}

/**
 * Lee una opción de Polylang.
 *
 * @param string $key Clave.
 * @return mixed
 */
function flmm_mig_pll_get( $key ) {
	if ( function_exists( 'PLL' ) && isset( PLL()->options ) && is_object( PLL()->options ) && method_exists( PLL()->options, 'get' ) ) {
		return PLL()->options->get( $key );
	}
	$options = (array) get_option( 'polylang', array() );
	return isset( $options[ $key ] ) ? $options[ $key ] : null;
}

/**
 * Guarda una opción de Polylang. Desde Polylang 3.7 las opciones viven en un objeto
 * que se guarda al final de la petición, así que hay que usar su API.
 *
 * @param string $key   Clave.
 * @param mixed  $value Valor.
 */
function flmm_mig_pll_set( $key, $value ) {
	if ( function_exists( 'PLL' ) && isset( PLL()->options ) && is_object( PLL()->options ) && method_exists( PLL()->options, 'set' ) ) {
		$result = PLL()->options->set( $key, $value );
		if ( is_wp_error( $result ) && $result->has_errors() ) {
			flmm_mig_log( 'Polylang (' . $key . '): ' . $result->get_error_message(), 'warn' );
		}
		return;
	}
	$options         = (array) get_option( 'polylang', array() );
	$options[ $key ] = $value;
	update_option( 'polylang', $options );
}

/**
 * ¿Polylang listo?
 */
function flmm_mig_has_pll() {
	return function_exists( 'pll_set_post_language' ) && function_exists( 'PLL' ) && PLL()->model->get_language( 'es' ) && PLL()->model->get_language( 'en' );
}

/**
 * Busca una página por slug e idioma.
 *
 * @param string $slug Slug.
 * @param string $lang Idioma o ''.
 * @return WP_Post|null
 */
function flmm_mig_find_page( $slug, $lang = '' ) {
	$args  = array( 'post_type' => 'page', 'name' => $slug, 'post_status' => array( 'publish', 'draft', 'private' ), 'posts_per_page' => 1, 'lang' => $lang );
	$posts = get_posts( $args );
	return $posts ? $posts[0] : null;
}

/**
 * Crea o actualiza una página.
 *
 * @param array    $data     Campos de wp_insert_post.
 * @param int|null $existing ID existente.
 * @return int
 */
function flmm_mig_save_page( $data, $existing = null ) {
	$data = array_merge( array( 'post_type' => 'page', 'post_status' => 'publish' ), $data );
	if ( $existing ) {
		$data['ID'] = $existing;
		$id         = wp_update_post( wp_slash( $data ), true );
	} else {
		$id = wp_insert_post( wp_slash( $data ), true );
	}
	if ( is_wp_error( $id ) ) {
		flmm_mig_log( 'Error al guardar "' . $data['post_title'] . '": ' . $id->get_error_message(), 'error' );
		return 0;
	}
	if ( isset( $data['page_template'] ) ) {
		update_post_meta( $id, '_wp_page_template', $data['page_template'] );
	}
	return (int) $id;
}

/**
 * Crea o actualiza el par EN/ES de una página y los enlaza en Polylang.
 *
 * @param string $slug    Slug EN.
 * @param array  $en      Campos EN.
 * @param array  $es      Campos ES (post_name se toma del mapa de slugs).
 * @return array( 'en' => id, 'es' => id )
 */
function flmm_mig_page_pair( $slug, $en, $es ) {
	$slugs    = flmm_mig_es_slugs();
	$en_page  = flmm_mig_find_page( $slug );
	$en_id    = flmm_mig_save_page( array_merge( $en, array( 'post_name' => $slug ) ), $en_page ? $en_page->ID : null );
	if ( ! $en_id ) {
		return array();
	}
	pll_set_post_language( $en_id, 'en' );
	$existing = pll_get_post( $en_id, 'es' );
	if ( ! $existing ) {
		$found    = flmm_mig_find_page( $slugs[ $slug ] );
		$existing = $found ? $found->ID : 0;
	}
	$es_id = flmm_mig_save_page( array_merge( $es, array( 'post_name' => $slugs[ $slug ] ) ), $existing ? $existing : null );
	if ( ! $es_id ) {
		return array( 'en' => $en_id );
	}
	pll_set_post_language( $es_id, 'es' );
	pll_save_post_translations( array( 'en' => $en_id, 'es' => $es_id ) );
	$es_slug = get_post_field( 'post_name', $es_id );
	if ( $es_slug !== $slugs[ $slug ] ) {
		flmm_mig_log( 'La página ES de ' . $slug . ' quedó con el slug "' . $es_slug . '" (el previsto está ocupado).', 'warn' );
	}
	flmm_mig_log( sprintf( '%s: EN #%d %s | ES #%d %s', $slug, $en_id, get_permalink( $en_id ), $es_id, get_permalink( $es_id ) ) );
	return array( 'en' => $en_id, 'es' => $es_id );
}

/* -------------------------------------------------------------------------
 * Pasos
 * ---------------------------------------------------------------------- */

/**
 * 1. Idiomas.
 */
function flmm_mig_step_languages() {
	if ( ! function_exists( 'PLL' ) ) {
		flmm_mig_log( 'Polylang no está activo. Instálalo y actívalo primero.', 'error' );
		return;
	}
	$model = PLL()->model;
	if ( ! $model->get_language( 'en' ) ) {
		$r = $model->add_language( array( 'name' => 'English', 'slug' => 'en', 'locale' => 'en_US', 'rtl' => false, 'term_group' => 0, 'flag' => 'us' ) );
		flmm_mig_log( is_wp_error( $r ) ? 'Error al crear EN: ' . $r->get_error_message() : 'Idioma EN creado.', is_wp_error( $r ) ? 'error' : 'ok' );
	}
	if ( ! $model->get_language( 'es' ) ) {
		$r = $model->add_language( array( 'name' => 'Español', 'slug' => 'es', 'locale' => 'es_ES', 'rtl' => false, 'term_group' => 1, 'flag' => 'es' ) );
		flmm_mig_log( is_wp_error( $r ) ? 'Error al crear ES: ' . $r->get_error_message() : 'Idioma ES creado.', is_wp_error( $r ) ? 'error' : 'ok' );
	}
	foreach ( array( 'default_lang' => 'en', 'force_lang' => 1, 'hide_default' => true, 'rewrite' => true, 'redirect_lang' => true, 'browser' => false, 'media_support' => true ) as $key => $value ) {
		flmm_mig_pll_set( $key, $value );
	}
	if ( method_exists( $model, 'clean_languages_cache' ) ) {
		$model->clean_languages_cache();
	}
	delete_transient( 'pll_languages_list' );
	flush_rewrite_rules();
	flmm_mig_log( 'Polylang configurado: inglés por defecto sin prefijo, español en /es/, idioma por directorio, sin redirección por navegador, medios traducibles.' );
}

/**
 * 2. Asignar inglés a todo lo que no tiene idioma.
 */
function flmm_mig_step_assign() {
	if ( ! flmm_mig_has_pll() ) {
		flmm_mig_log( 'Primero ejecuta el paso de idiomas.', 'error' );
		return;
	}
	$count = 0;
	$posts = get_posts( array( 'post_type' => array( 'post', 'page', 'attachment', 'wp_block' ), 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'lang' => '' ) );
	$es    = wp_list_pluck( flmm_mig_posts(), 'es' );
	$es    = wp_list_pluck( $es, 'id' );
	foreach ( $posts as $id ) {
		if ( ! pll_get_post_language( $id ) ) {
			pll_set_post_language( $id, in_array( (int) $id, $es, true ) ? 'es' : 'en' );
			++$count;
		}
	}
	$terms = get_terms( array( 'taxonomy' => array( 'category', 'post_tag' ), 'hide_empty' => false, 'lang' => '' ) );
	foreach ( (array) $terms as $term ) {
		if ( ! pll_get_term_language( $term->term_id ) ) {
			pll_set_term_language( $term->term_id, 'en' );
			++$count;
		}
	}
	flmm_mig_log( $count . ' elementos sin idioma quedaron en inglés (las 3 entradas del blog en español quedaron en ES).' );
}

/**
 * Sube una imagen del theme a la biblioteca si no existe (por nombre de archivo).
 *
 * @param string $file Nombre del archivo en inc/migrate/media.
 * @param string $alt  Texto alternativo.
 * @param string $title Título.
 * @return int
 */
function flmm_mig_upload( $file, $alt, $title ) {
	$found = get_posts( array( 'post_type' => 'attachment', 'posts_per_page' => 1, 'meta_key' => '_flmm_source', 'meta_value' => $file, 'lang' => 'en', 'fields' => 'ids' ) );
	if ( $found ) {
		return (int) $found[0];
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	$tmp = wp_tempnam( $file );
	copy( __DIR__ . '/media/' . $file, $tmp );
	$id = media_handle_sideload( array( 'name' => $file, 'tmp_name' => $tmp ), 0, $title );
	if ( is_wp_error( $id ) ) {
		flmm_mig_log( 'No se pudo subir ' . $file . ': ' . $id->get_error_message(), 'error' );
		return 0;
	}
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	update_post_meta( $id, '_flmm_source', $file );
	return (int) $id;
}

/**
 * Traducción ES de un adjunto (mismo archivo, texto alternativo en español).
 *
 * @param int    $en_id Adjunto EN.
 * @param string $alt   Texto alternativo ES.
 * @param string $title Título ES.
 * @return int
 */
function flmm_mig_media_translation( $en_id, $alt, $title ) {
	$es_id = pll_get_post( $en_id, 'es' );
	if ( ! $es_id ) {
		$src   = get_post( $en_id );
		$es_id = wp_insert_attachment(
			array(
				'post_title'     => $title,
				'post_mime_type' => $src->post_mime_type,
				'guid'           => $src->guid,
				'post_status'    => 'inherit',
			),
			get_attached_file( $en_id )
		);
		update_post_meta( $es_id, '_wp_attachment_metadata', get_post_meta( $en_id, '_wp_attachment_metadata', true ) );
		update_post_meta( $es_id, '_wp_attached_file', get_post_meta( $en_id, '_wp_attached_file', true ) );
		pll_set_post_language( $es_id, 'es' );
		pll_save_post_translations( array( 'en' => $en_id, 'es' => $es_id ) );
	}
	update_post_meta( $es_id, '_wp_attachment_image_alt', $alt );
	return (int) $es_id;
}

/**
 * 3. Medios.
 */
function flmm_mig_step_media() {
	if ( ! flmm_mig_has_pll() ) {
		flmm_mig_log( 'Primero ejecuta el paso de idiomas.', 'error' );
		return;
	}
	$map = array();
	foreach ( flmm_mig_media() as $key => $alts ) {
		$file = $key . '-illustration.webp';
		$en   = flmm_mig_upload( $file, $alts[0], $alts[0] );
		if ( ! $en ) {
			continue;
		}
		pll_set_post_language( $en, 'en' );
		$es          = flmm_mig_media_translation( $en, $alts[1], $alts[1] );
		$map[ $key ] = array( 'en' => $en, 'es' => $es );
		flmm_mig_log( sprintf( '%s: EN #%d, ES #%d (%s)', $file, $en, $es, wp_get_attachment_url( $en ) ) );
	}
	flmm_mig_state( 'media', $map );
}

/**
 * Imagen de un servicio para el hero.
 *
 * @param string $key  Clave de medio.
 * @param string $lang Idioma.
 * @return array|false
 */
function flmm_mig_image( $key, $lang ) {
	$map = (array) flmm_mig_state( 'media' );
	if ( empty( $map[ $key ][ $lang ] ) ) {
		return false;
	}
	$id = (int) $map[ $key ][ $lang ];
	return array( 'id' => $id, 'url' => wp_get_attachment_url( $id ), 'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ) );
}

/**
 * Datos de servicios (content.json).
 */
function flmm_mig_services_data() {
	static $data = null;
	if ( null === $data ) {
		$data = json_decode( (string) file_get_contents( __DIR__ . '/content.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}
	return $data;
}

/**
 * Mapa de URLs de servicios por idioma (para enlaces del home y relacionados).
 */
function flmm_mig_service_urls() {
	$slugs = flmm_mig_es_slugs();
	$urls  = array();
	foreach ( flmm_mig_services_data()['services'] as $s ) {
		$urls[ $s['slug'] ] = array(
			'en' => home_url( '/' . $s['slug'] . '/' ),
			'es' => home_url( '/es/' . $slugs[ $s['slug'] ] . '/' ),
		);
	}
	return $urls;
}

/**
 * Contenido completo de un servicio.
 *
 * @param array  $s    Datos.
 * @param string $lang Idioma.
 * @return string
 */
function flmm_mig_service_content( $s, $lang ) {
	$data = flmm_mig_services_data();
	$urls = flmm_mig_service_urls();
	$by   = array();
	foreach ( $data['services'] as $x ) {
		$by[ $x['slug'] ] = $x;
	}
	$related = array();
	foreach ( $s['rel'] as $r ) {
		if ( isset( $by[ $r ] ) ) {
			$related[] = array( 'slug' => $r, 'name' => $by[ $r ]['name'] );
		}
	}
	$testimonial = isset( $data['testimonials'][ $s['testi'] ] ) ? $data['testimonials'][ $s['testi'] ] : null;
	return flmm_pattern_service_hero( $lang, $s, flmm_mig_image( $s['slug'], $lang ) )
		. flmm_pattern_service_included( $lang, $s )
		. flmm_pattern_service_comparison( $lang, $s )
		. flmm_pattern_service_concepts( $lang, $s )
		. flmm_pattern_service_method( $lang, $s )
		. flmm_pattern_service_audience( $lang, $s )
		. flmm_pattern_service_why( $lang, $s, $testimonial )
		. flmm_pattern_service_faq( $lang, $s )
		. flmm_pattern_service_related( $lang, $related, $urls );
}

/**
 * 4. Servicios.
 */
function flmm_mig_step_services() {
	if ( ! flmm_mig_has_pll() ) {
		flmm_mig_log( 'Primero ejecuta el paso de idiomas.', 'error' );
		return;
	}
	$ids = array();
	foreach ( flmm_mig_services_data()['services'] as $s ) {
		$fields = array();
		foreach ( array( 'en', 'es' ) as $lang ) {
			$fields[ $lang ] = array(
				'post_title'    => html_entity_decode( wp_strip_all_tags( $s['name'][ $lang ] ), ENT_QUOTES ),
				'post_content'  => flmm_mig_service_content( $s, $lang ),
				'post_excerpt'  => $s['desc'][ $lang ],
				'page_template' => 'page-service',
				'comment_status' => 'closed',
			);
		}
		$pair = flmm_mig_page_pair( $s['slug'], $fields['en'], $fields['es'] );
		foreach ( $pair as $lang => $id ) {
			$img = flmm_mig_image( $s['slug'], $lang );
			if ( $img ) {
				set_post_thumbnail( $id, $img['id'] );
			}
		}
		$ids[ $s['slug'] ] = $pair;
	}
	flmm_mig_state( 'services', $ids );
}

/**
 * 5. Páginas de empresa.
 */
function flmm_mig_step_company() {
	if ( ! flmm_mig_has_pll() ) {
		flmm_mig_log( 'Primero ejecuta el paso de idiomas.', 'error' );
		return;
	}
	$ids = array();
	foreach ( array( 'about', 'digitales-sin-fronteras', 'podcast', 'privacy-policy', 'contact-us' ) as $slug ) {
		$en   = flmm_mig_company_page( $slug, 'en' );
		$es   = flmm_mig_company_page( $slug, 'es' );
		$pair = flmm_mig_page_pair(
			$slug,
			array( 'post_title' => $en['title'], 'post_content' => $en['content'], 'page_template' => isset( $en['template'] ) ? $en['template'] : '', 'comment_status' => 'closed' ),
			array( 'post_title' => $es['title'], 'post_content' => $es['content'], 'page_template' => isset( $es['template'] ) ? $es['template'] : '', 'comment_status' => 'closed' )
		);
		if ( 'privacy-policy' === $slug ) {
			foreach ( $pair as $lang => $id ) {
				$img = flmm_mig_image( 'privacy-policy', $lang );
				if ( $img ) {
					set_post_thumbnail( $id, $img['id'] );
				}
			}
		}
		if ( 'about' === $slug && ! empty( $pair['en'] ) && ! empty( $pair['es'] ) ) {
			$thumb = get_post_thumbnail_id( $pair['en'] );
			if ( $thumb ) {
				set_post_thumbnail( $pair['es'], $thumb );
			}
		}
		$ids[ $slug ] = $pair;
	}
	flmm_mig_state( 'company', $ids );
}

/**
 * 6. Home y blog.
 */
function flmm_mig_step_home() {
	if ( ! flmm_mig_has_pll() ) {
		flmm_mig_log( 'Primero ejecuta el paso de idiomas.', 'error' );
		return;
	}
	$urls    = flmm_mig_service_urls();
	$lead    = flmm_mig_home_lead();
	$home    = flmm_pattern_data( 'home' );
	$content = array();
	foreach ( array( 'en', 'es' ) as $lang ) {
		$hero           = $home['hero'];
		$hero['lead']   = array( $lang => $lead[ $lang ] );
		$content[ $lang ] = flmm_pattern_hero( $lang, $hero )
			. flmm_pattern_services( $lang, $home['services'], $urls )
			. flmm_pattern_platforms( $lang, $home['platforms'] )
			. flmm_pattern_method( $lang, $home['method'] )
			. flmm_pattern_team( $lang, $home['team'] )
			. flmm_pattern_results( $lang, $home['results'] );
	}
	$pair = flmm_mig_page_pair(
		'home',
		array( 'post_title' => 'Home', 'post_content' => $content['en'], 'comment_status' => 'closed' ),
		array( 'post_title' => 'Inicio', 'post_content' => $content['es'], 'comment_status' => 'closed' )
	);
	$blog = flmm_mig_page_pair(
		'blog',
		array( 'post_title' => 'Blog', 'post_content' => '', 'page_template' => '', 'comment_status' => 'closed' ),
		array( 'post_title' => 'Blog', 'post_content' => '', 'page_template' => '', 'comment_status' => 'closed' )
	);
	if ( ! empty( $pair['en'] ) && ! empty( $blog['en'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $pair['en'] );
		update_option( 'page_for_posts', $blog['en'] );
		flmm_mig_log( 'Portada: página Home (#' . $pair['en'] . '). Entradas: página Blog (#' . $blog['en'] . ').' );
	}
	if ( function_exists( 'PLL' ) && method_exists( PLL()->model, 'clean_languages_cache' ) ) {
		PLL()->model->clean_languages_cache();
	}
	delete_transient( 'pll_languages_list' );
	flush_rewrite_rules();
	flmm_mig_state( 'home', array( 'home' => $pair, 'blog' => $blog ) );
}

/**
 * Obtiene o crea una categoría con su traducción.
 *
 * @param string $key Clave.
 * @return array( 'en' => term_id, 'es' => term_id )
 */
function flmm_mig_category( $key ) {
	$def = flmm_mig_categories()[ $key ];
	$ids = array();
	foreach ( array( 'en', 'es' ) as $lang ) {
		$term = get_term_by( 'slug', $def[ $lang ][1], 'category' );
		if ( ! $term ) {
			$r = wp_insert_term( $def[ $lang ][0], 'category', array( 'slug' => $def[ $lang ][1] ) );
			if ( is_wp_error( $r ) ) {
				flmm_mig_log( 'Categoría ' . $def[ $lang ][0] . ': ' . $r->get_error_message(), 'error' );
				continue;
			}
			$ids[ $lang ] = (int) $r['term_id'];
		} else {
			$ids[ $lang ] = (int) $term->term_id;
		}
		pll_set_term_language( $ids[ $lang ], $lang );
	}
	if ( count( $ids ) === 2 ) {
		pll_save_term_translations( $ids );
	}
	return $ids;
}

/**
 * Ajustes del contenido de una entrada: TL;DR, puntos clave, enlaces rotos y raya larga.
 *
 * @param string $content Contenido.
 * @return string
 */
function flmm_mig_fix_post_content( $content ) {
	// TL;DR: el primer párrafo que empieza con "TL;DR" pasa a un grupo con el estilo TL;DR.
	if ( false === strpos( $content, 'is-style-tldr' ) ) {
		$content = preg_replace_callback(
			'#<!-- wp:paragraph -->\s*<p><strong>TL;DR:?</strong>:?\s*(.*?)</p>\s*<!-- /wp:paragraph -->#s',
			static function ( $m ) {
				return flmm_group( flmm_p( '<strong>TL;DR</strong>' ) . flmm_p( trim( $m[1] ) ), array( 'className' => 'is-style-tldr', 'layout' => array( 'type' => 'default' ) ) );
			},
			$content,
			1
		);
	}
	// Puntos clave: el H2 y la lista siguiente pasan a un grupo con el estilo de puntos clave.
	if ( false === strpos( $content, 'is-style-takeaways' ) ) {
		$content = preg_replace_callback(
			'#(<!-- wp:heading -->\s*<h2 class="wp-block-heading">(?:Key takeaways|Puntos clave)</h2>\s*<!-- /wp:heading -->)\s*(<!-- wp:list -->.*?<!-- /wp:list -->)#s',
			static function ( $m ) {
				return flmm_group( $m[1] . $m[2], array( 'className' => 'is-style-takeaways', 'layout' => array( 'type' => 'default' ) ) );
			},
			$content,
			1
		);
	}
	// Enlaces internos rotos de las entradas en español.
	$content = preg_replace( '#https?://[^"/]+/blog/metricas-performance-marketing-b2b/#', home_url( '/es/blog/metricas-de-performance-marketing-b2b-mas-alla-del-cpc/' ), $content );
	$content = preg_replace( '#https?://[^"/]+/blog/ia-entropia-shannon-marketing/#', home_url( '/es/blog/ia-y-entropia-de-shannon-en-performance-marketing-b2b/' ), $content );
	// Raya larga.
	$dash    = "\u{2014}";
	$content = str_replace( array( ' ' . $dash . ' ', $dash ), array( ', ', ', ' ), $content );
	return $content;
}

/**
 * 7. Blog.
 */
function flmm_mig_step_posts() {
	if ( ! flmm_mig_has_pll() ) {
		flmm_mig_log( 'Primero ejecuta el paso de idiomas.', 'error' );
		return;
	}
	$user   = flmm_mig_author_user();
	$author = $user ? $user->ID : 0;
	foreach ( flmm_mig_posts() as $pair ) {
		$cat = flmm_mig_category( $pair['cat'] );
		$ids = array();
		foreach ( array( 'en', 'es' ) as $lang ) {
			$post = get_post( $pair[ $lang ]['id'] );
			if ( ! $post || 'post' !== $post->post_type ) {
				$found = get_posts( array( 'post_type' => 'post', 'name' => $pair[ $lang ]['slug'], 'post_status' => 'any', 'posts_per_page' => 1, 'lang' => '' ) );
				$post  = $found ? $found[0] : null;
			}
			if ( ! $post ) {
				flmm_mig_log( 'No encontré la entrada ' . $pair[ $lang ]['slug'], 'warn' );
				continue;
			}
			pll_set_post_language( $post->ID, $lang );
			$update = array(
				'ID'           => $post->ID,
				'post_content' => flmm_mig_fix_post_content( $post->post_content ),
			);
			if ( $author ) {
				$update['post_author'] = $author;
			}
			wp_update_post( wp_slash( $update ) );
			if ( ! empty( $cat[ $lang ] ) ) {
				wp_set_post_categories( $post->ID, array( $cat[ $lang ] ), false );
			}
			$ids[ $lang ] = $post->ID;
		}
		if ( count( $ids ) === 2 ) {
			pll_save_post_translations( $ids );
			flmm_mig_log( sprintf( 'Par enlazado: %s (EN #%d) y %s (ES #%d), categoría %s.', get_permalink( $ids['en'] ), $ids['en'], get_permalink( $ids['es'] ), $ids['es'], $pair['cat'] ) );
		}
	}
}

/**
 * Usuario de Alejandro Lovera (por ID, nombre visible o login).
 *
 * @return WP_User|false
 */
function flmm_mig_author_user() {
	$user = get_user_by( 'id', flmm_mig_author()['id'] );
	if ( ! $user ) {
		$found = get_users( array( 'search' => 'Alejandro Lovera', 'search_columns' => array( 'display_name' ), 'number' => 1 ) );
		$user  = $found ? $found[0] : get_user_by( 'login', 'marketingtodaypodcast' );
	}
	return $user;
}

/**
 * 8. Autor.
 */
function flmm_mig_step_author() {
	$a    = flmm_mig_author();
	$user = flmm_mig_author_user();
	if ( ! $user ) {
		flmm_mig_log( 'No encontré el usuario de Alejandro Lovera.', 'error' );
		return;
	}
	foreach ( array( 'description', 'description_es', 'flmm_role', 'flmm_role_es', 'flmm_linkedin' ) as $key ) {
		update_user_meta( $user->ID, $key, $a[ $key ] );
	}
	flmm_mig_log( 'Biografía, cargo y LinkedIn de Alejandro Lovera cargados en EN y ES.' );
}

/**
 * Crea o reemplaza un menú clásico.
 *
 * @param string $name  Nombre.
 * @param array  $items Lista de array( etiqueta, url ) o array( etiqueta, page_id ).
 * @return int
 */
function flmm_mig_menu( $name, $items ) {
	$menu = wp_get_nav_menu_object( $name );
	$id   = $menu ? $menu->term_id : wp_create_nav_menu( $name );
	if ( is_wp_error( $id ) ) {
		flmm_mig_log( 'Menú ' . $name . ': ' . $id->get_error_message(), 'error' );
		return 0;
	}
	foreach ( (array) wp_get_nav_menu_items( $id ) as $old ) {
		wp_delete_post( $old->ID, true );
	}
	foreach ( $items as $i => $item ) {
		$args = array( 'menu-item-title' => $item[0], 'menu-item-status' => 'publish', 'menu-item-position' => $i + 1 );
		if ( is_int( $item[1] ) ) {
			$args += array( 'menu-item-type' => 'post_type', 'menu-item-object' => 'page', 'menu-item-object-id' => $item[1] );
		} else {
			$args += array( 'menu-item-type' => 'custom', 'menu-item-url' => $item[1] );
		}
		wp_update_nav_menu_item( $id, 0, $args );
	}
	return (int) $id;
}

/**
 * 9. Menús.
 */
function flmm_mig_step_menus() {
	if ( ! flmm_mig_has_pll() ) {
		flmm_mig_log( 'Primero ejecuta el paso de idiomas.', 'error' );
		return;
	}
	$company = (array) flmm_mig_state( 'company' );
	$home    = (array) flmm_mig_state( 'home' );
	$labels  = array(
		'en' => array( 'Services', 'Platforms', 'Method', 'Team', 'Blog', 'Contact', 'About us', 'Privacy Policy' ),
		'es' => array( 'Servicios', 'Plataformas', 'Método', 'Equipo', 'Blog', 'Contacto', 'Sobre nosotros', 'Política de privacidad' ),
	);
	$locations = array();
	foreach ( array( 'en', 'es' ) as $lang ) {
		$l    = $labels[ $lang ];
		$base = 'es' === $lang ? home_url( '/es/' ) : home_url( '/' );
		$blog = ! empty( $home['blog'][ $lang ] ) ? (int) $home['blog'][ $lang ] : $base . 'blog/';
		$page = static function ( $slug ) use ( $company, $lang, $base ) {
			return ! empty( $company[ $slug ][ $lang ] ) ? (int) $company[ $slug ][ $lang ] : $base;
		};
		$locations['primary'][ $lang ]        = flmm_mig_menu( 'Header (' . strtoupper( $lang ) . ')', array( array( $l[0], $base . '#services' ), array( $l[1], $base . '#platforms' ), array( $l[3], $base . '#team' ), array( $l[4], $blog ), array( $l[5], '#contact' ) ) );
		$locations['footer-nav'][ $lang ]     = flmm_mig_menu( 'Footer navegación (' . strtoupper( $lang ) . ')', array( array( $l[0], $base . '#services' ), array( $l[1], $base . '#platforms' ), array( $l[2], $base . '#method' ), array( $l[3], $base . '#team' ), array( $l[4], $blog ) ) );
		$locations['footer-company'][ $lang ] = flmm_mig_menu( 'Footer empresa (' . strtoupper( $lang ) . ')', array( array( $l[6], $page( 'about' ) ), array( 'Digitales Sin Fronteras', $page( 'digitales-sin-fronteras' ) ), array( 'Marketing Today Podcast', $page( 'podcast' ) ), array( $l[7], $page( 'privacy-policy' ) ) ) );
	}
	$nav_menus                      = (array) flmm_mig_pll_get( 'nav_menus' );
	$nav_menus[ get_stylesheet() ] = $locations;
	flmm_mig_pll_set( 'nav_menus', $nav_menus );
	$theme_locations = get_theme_mod( 'nav_menu_locations', array() );
	foreach ( $locations as $loc => $by_lang ) {
		$theme_locations[ $loc ] = $by_lang['en'];
		foreach ( $by_lang as $lang => $menu_id ) {
			if ( $menu_id ) {
				pll_set_term_language( $menu_id, $lang );
			}
		}
	}
	set_theme_mod( 'nav_menu_locations', $theme_locations );
	flmm_mig_log( 'Menús creados y asignados por idioma: Header, Footer navegación y Footer empresa.' );
}

/**
 * Guarda el SEO de Rank Math de un contenido.
 *
 * @param int   $id  ID.
 * @param array $seo array( título, descripción, keyword ).
 */
function flmm_mig_set_seo( $id, $seo ) {
	update_post_meta( $id, 'rank_math_title', $seo[0] );
	update_post_meta( $id, 'rank_math_description', $seo[1] );
	update_post_meta( $id, 'rank_math_focus_keyword', $seo[2] );
	$thumb = get_post_thumbnail_id( $id );
	if ( $thumb ) {
		update_post_meta( $id, 'rank_math_facebook_image', wp_get_attachment_url( $thumb ) );
		update_post_meta( $id, 'rank_math_facebook_image_id', $thumb );
	}
	update_post_meta( $id, 'rank_math_twitter_use_facebook', 'on' );
	$title_len = mb_strlen( $seo[0] );
	$desc_len  = mb_strlen( $seo[1] );
	if ( $title_len > 60 || $desc_len < 140 || $desc_len > 160 ) {
		flmm_mig_log( sprintf( '#%d: título %d caracteres, descripción %d (objetivo: 60 y 140 a 160).', $id, $title_len, $desc_len ), 'warn' );
	}
}

/**
 * 10. SEO.
 */
function flmm_mig_step_seo() {
	if ( ! defined( 'RANK_MATH_VERSION' ) ) {
		flmm_mig_log( 'Rank Math no está activo: se omiten los metadatos SEO.', 'warn' );
		return;
	}
	$overrides = flmm_mig_title_overrides();
	$services  = (array) flmm_mig_state( 'services' );
	foreach ( flmm_mig_services_data()['services'] as $s ) {
		foreach ( array( 'en', 'es' ) as $lang ) {
			if ( empty( $services[ $s['slug'] ][ $lang ] ) ) {
				continue;
			}
			$title = isset( $overrides[ $s['slug'] ][ $lang ] ) ? $overrides[ $s['slug'] ][ $lang ] : html_entity_decode( $s['title'][ $lang ], ENT_QUOTES );
			flmm_mig_set_seo( $services[ $s['slug'] ][ $lang ], array( $title, html_entity_decode( $s['desc'][ $lang ], ENT_QUOTES ), html_entity_decode( wp_strip_all_tags( $s['name'][ $lang ] ), ENT_QUOTES ) ) );
		}
	}
	$pages = array_merge( (array) flmm_mig_state( 'company' ), (array) flmm_mig_state( 'home' ) );
	foreach ( flmm_mig_page_seo() as $slug => $by_lang ) {
		foreach ( $by_lang as $lang => $seo ) {
			if ( ! empty( $pages[ $slug ][ $lang ] ) ) {
				flmm_mig_set_seo( $pages[ $slug ][ $lang ], $seo );
			}
		}
	}
	foreach ( flmm_mig_posts() as $pair ) {
		foreach ( array( 'en', 'es' ) as $lang ) {
			if ( get_post( $pair[ $lang ]['id'] ) ) {
				flmm_mig_set_seo( $pair[ $lang ]['id'], $pair[ $lang ]['seo'] );
			}
		}
	}
	// Ajustes generales: organización, artículo como BlogPosting y migas de pan en el schema.
	$titles = (array) get_option( 'rank-math-options-titles', array() );
	$titles = array_merge(
		$titles,
		array(
			'knowledgegraph_type'           => 'company',
			'knowledgegraph_name'           => 'FL Marketing Management',
			'website_name'                  => 'FL Marketing Management',
			'pt_post_default_rich_snippet'  => 'article',
			'pt_post_default_article_type'  => 'BlogPosting',
			'social_additional_profiles'    => flmm_option( 'linkedin' ) . "\n" . flmm_option( 'instagram' ),
		)
	);
	update_option( 'rank-math-options-titles', $titles );
	$general                = (array) get_option( 'rank-math-options-general', array() );
	$general['breadcrumbs'] = 'on';
	update_option( 'rank-math-options-general', $general );
	flmm_mig_log( 'Rank Math: títulos, descripciones, keyword e imagen OG por página; organización, BlogPosting y migas de pan configurados.' );
}

/**
 * 11. Redirecciones.
 */
function flmm_mig_step_redirects() {
	global $wpdb;
	$created = 0;
	$use_rm  = class_exists( 'RankMath\\Redirections\\Redirection' );
	if ( $use_rm ) {
		$modules = (array) get_option( 'rank_math_modules', array() );
		if ( ! in_array( 'redirections', $modules, true ) ) {
			$modules[] = 'redirections';
			update_option( 'rank_math_modules', $modules );
		}
	}
	$table = $wpdb->prefix . 'rank_math_redirections';
	if ( $use_rm && $table !== $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) && method_exists( 'RankMath\\Installer', 'create_tables' ) ) { // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		\RankMath\Installer::create_tables( array( 'redirections' ) );
		flmm_mig_log( 'Tablas de redirecciones de Rank Math creadas.' );
	}
	$has_table = $use_rm && $table === $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	foreach ( flmm_mig_redirects() as $from => $to ) {
		$to = 0 === strpos( $to, 'http' ) ? $to : home_url( $to );
		if ( $has_table ) {
			$exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table} WHERE sources LIKE %s", '%"' . $wpdb->esc_like( $from ) . '"%' ) ); // phpcs:ignore WordPress.DB
			if ( $exists ) {
				continue;
			}
			\RankMath\Redirections\Redirection::from(
				array(
					'sources'     => array( array( 'pattern' => $from, 'comparison' => 'exact' ) ),
					'url_to'      => $to,
					'header_code' => '301',
					'status'      => 'active',
				)
			)->save();
			++$created;
		}
	}
	if ( ! $has_table ) {
		flmm_mig_log( 'Rank Math (módulo de redirecciones) no está disponible: el theme aplica las redirecciones por su cuenta.', 'warn' );
		update_option( 'flmm_theme_redirects', 1 );
	} else {
		delete_option( 'flmm_theme_redirects' );
		flmm_mig_log( $created . ' redirecciones 301 creadas en Rank Math (las existentes no se duplican).' );
	}
	foreach ( flmm_mig_retired_pages() as $slug ) {
		$page = flmm_mig_find_page( $slug );
		if ( $page && 'publish' === $page->post_status ) {
			wp_update_post( array( 'ID' => $page->ID, 'post_status' => 'draft' ) );
			flmm_mig_log( 'Página antigua /' . $slug . '/ pasada a borrador.' );
		}
	}
}

/* -------------------------------------------------------------------------
 * Pantalla de administración
 * ---------------------------------------------------------------------- */

/**
 * Agrega la página a Herramientas.
 */
function flmm_mig_menu_page() {
	add_management_page( 'Migración FLMM', 'Migración FLMM', 'manage_options', 'flmm-migration', 'flmm_mig_render' );
}
add_action( 'admin_menu', 'flmm_mig_menu_page' );

/**
 * Ejecuta los pasos pedidos.
 *
 * @param string[] $keys Pasos.
 * @return array Registro.
 */
function flmm_mig_run( $keys ) {
	$GLOBALS['flmm_mig_log'] = array();
	$steps                   = flmm_mig_steps();
	foreach ( $keys as $key ) {
		if ( isset( $steps[ $key ] ) ) {
			flmm_mig_log( $steps[ $key ][0], 'step' );
			call_user_func( $steps[ $key ][1] );
		}
	}
	// Al final: caché de idiomas de Polylang, reglas de URL y caché de llms.txt.
	if ( function_exists( 'PLL' ) && method_exists( PLL()->model, 'clean_languages_cache' ) ) {
		PLL()->model->clean_languages_cache();
	}
	delete_transient( 'pll_languages_list' );
	delete_transient( 'flmm_llms_txt' );
	flush_rewrite_rules();
	return $GLOBALS['flmm_mig_log'];
}

/**
 * Pinta la pantalla.
 */
function flmm_mig_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$log = array();
	if ( isset( $_POST['flmm_mig_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['flmm_mig_nonce'] ), 'flmm_mig' ) ) {
		$step = isset( $_POST['step'] ) ? sanitize_key( $_POST['step'] ) : '';
		$keys = 'all' === $step ? array_keys( flmm_mig_steps() ) : array( $step );
		@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$log = flmm_mig_run( $keys );
		update_option( 'flmm_migration_last_log', $log, false );
	}
	?>
	<div class="wrap">
		<h1>Migración FLMM</h1>
		<p>Aplica el nuevo contenido bilingüe. Cada paso se puede repetir sin duplicar nada. Úsalo primero en staging.</p>
		<p>Requisitos: theme FLMM Studio activo, Polylang activo y, para el paso de SEO y redirecciones, Rank Math.</p>
		<form method="post">
			<?php wp_nonce_field( 'flmm_mig', 'flmm_mig_nonce' ); ?>
			<p><button class="button button-primary button-hero" name="step" value="all">Ejecutar todos los pasos</button></p>
			<table class="widefat striped" style="max-width:760px">
				<?php foreach ( flmm_mig_steps() as $key => $step ) : ?>
					<tr><td><?php echo esc_html( $step[0] ); ?></td><td style="text-align:right"><button class="button" name="step" value="<?php echo esc_attr( $key ); ?>">Ejecutar</button></td></tr>
				<?php endforeach; ?>
			</table>
		</form>
		<?php if ( $log ) : ?>
			<h2>Resultado</h2>
			<ul style="font-family:monospace;max-width:960px">
				<?php
				foreach ( $log as $line ) {
					$color = array( 'ok' => '#1d7a36', 'warn' => '#9a6700', 'error' => '#b32d2e', 'step' => '#1d2327' );
					printf( '<li style="color:%s;%s">%s</li>', esc_attr( $color[ $line[0] ] ), 'step' === $line[0] ? 'font-weight:600;margin-top:1em' : '', esc_html( $line[1] ) );
				}
				?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
}
