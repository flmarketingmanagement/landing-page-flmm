<?php
/**
 * Artículos nuevos del blog (blog-posts.json, generado desde src/blog_posts.py).
 *
 * Crea o actualiza cada par EN/ES. Si la fecha de publicación es futura, el artículo queda
 * programado; si ya se publicó, conserva su estado y su fecha.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Datos de los artículos.
 *
 * @return array
 */
function flmm_mig_new_posts() {
	static $posts = null;
	if ( null === $posts ) {
		$posts = json_decode( (string) file_get_contents( __DIR__ . '/blog-posts.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$posts = is_array( $posts ) ? $posts : array();
	}
	return $posts;
}

/**
 * URL de un artículo nuevo por su clave.
 *
 * @param string $key  Clave.
 * @param string $lang Idioma.
 * @return string
 */
function flmm_mig_new_post_url( $key, $lang ) {
	foreach ( flmm_mig_new_posts() as $p ) {
		if ( $p['key'] === $key ) {
			return 'es' === $lang ? home_url( '/es/blog/' . $p['es']['slug'] . '/' ) : home_url( '/blog/' . $p['en']['slug'] . '/' );
		}
	}
	return '';
}

/**
 * Reemplaza los marcadores de enlaces internos.
 *
 * @param string $html HTML.
 * @param string $lang Idioma.
 * @return string
 */
function flmm_mig_new_post_links( $html, $lang ) {
	$es_slugs = flmm_mig_es_slugs();
	$map      = array(
		'{seo-aeo}' => 'es' === $lang ? home_url( '/es/' . $es_slugs['seo-aeo'] . '/' ) : home_url( '/seo-aeo/' ),
		'{florida}' => function_exists( 'flmm_mig_local_url' ) ? flmm_mig_local_url( 'marketing-agency-florida', $lang ) : home_url( '/' ),
	);
	if ( function_exists( 'flmm_mig_local_url' ) ) {
		$map['{lakeland}'] = flmm_mig_local_url( 'digital-marketing-lakeland', $lang );
	}
	// Páginas de servicios: {service:slug}.
	foreach ( flmm_mig_service_urls() as $slug => $urls ) {
		$map[ '{service:' . $slug . '}' ] = $urls[ $lang ];
	}
	$html = strtr( $html, $map );
	// Enlaces a otros artículos nuevos: solo si ya están publicados; si no, queda el texto sin enlace.
	return preg_replace_callback(
		'/<a href="\{post:([a-z0-9-]+)\}">(.*?)<\/a>/',
		static function ( $m ) use ( $lang ) {
			return flmm_mig_new_post_is_live( $m[1] ) ? '<a href="' . esc_url( flmm_mig_new_post_url( $m[1], $lang ) ) . '">' . $m[2] . '</a>' : $m[2];
		},
		$html
	);
}

/**
 * ¿El artículo nuevo ya está publicado?
 *
 * @param string $key Clave.
 * @return bool
 */
function flmm_mig_new_post_is_live( $key ) {
	foreach ( flmm_mig_new_posts() as $p ) {
		if ( $p['key'] === $key ) {
			return strtotime( $p['publish_gmt'] . ' UTC' ) <= time();
		}
	}
	return false;
}

/**
 * Lista en bloques.
 *
 * @param array $items   Elementos.
 * @param bool  $ordered Numerada.
 * @return string
 */
function flmm_mig_new_post_list( $items, $ordered = false ) {
	$li = '';
	foreach ( $items as $item ) {
		$li .= flmm_bo( 'list-item' ) . '<li>' . $item . '</li><!-- /wp:list-item -->';
	}
	$tag = $ordered ? 'ol' : 'ul';
	return flmm_bo( 'list', $ordered ? array( 'ordered' => true ) : array() ) . '<' . $tag . ' class="wp-block-list">' . $li . '</' . $tag . '><!-- /wp:list -->';
}

/**
 * Contenido en bloques de un artículo.
 *
 * @param array  $d    Datos del idioma.
 * @param string $lang Idioma.
 * @return string
 */
function flmm_mig_new_post_content( $d, $lang ) {
	$html  = flmm_group( flmm_p( '<strong>TL;DR</strong>' ) . flmm_p( $d['tldr'] ), array( 'className' => 'is-style-tldr', 'layout' => array( 'type' => 'default' ) ) );
	$html .= flmm_p( $d['intro'] );
	foreach ( $d['body'] as $block ) {
		switch ( $block[0] ) {
			case 'h2':
				$html .= flmm_h( 2, $block[1] );
				break;
			case 'h3':
				$html .= flmm_h( 3, $block[1] );
				break;
			case 'ul':
			case 'ol':
				$html .= flmm_mig_new_post_list( $block[1], 'ol' === $block[0] );
				break;
			default:
				$html .= flmm_p( $block[1] );
		}
	}
	if ( ! empty( $d['ref'] ) ) {
		$html .= flmm_p( ( 'es' === $lang ? 'Referencia:' : 'Reference:' ) . ' <a href="' . esc_url( $d['ref'][1] ) . '" target="_blank" rel="noreferrer noopener">' . esc_html( $d['ref'][0] ) . '</a>', 'flmm-ref' );
	}
	$html .= flmm_h( 2, $d['faq_title'] );
	foreach ( $d['faq'] as $qa ) {
		$html .= flmm_h( 3, $qa[0] ) . flmm_p( $qa[1] );
	}
	$html .= flmm_group(
		flmm_h( 2, 'es' === $lang ? 'Puntos clave' : 'Key takeaways' ) . flmm_mig_new_post_list( $d['takeaways'] ),
		array( 'className' => 'is-style-takeaways', 'layout' => array( 'type' => 'default' ) )
	);
	return flmm_mig_new_post_links( $html, $lang );
}

/**
 * Paso de la migración: crea, actualiza y programa los artículos nuevos.
 */
function flmm_mig_step_newposts() {
	$user   = flmm_mig_author_user();
	$author = $user ? $user->ID : 0;
	foreach ( flmm_mig_new_posts() as $p ) {
		$cat = flmm_mig_category( $p['cat'] );
		$ids = array();
		foreach ( array( 'en', 'es' ) as $lang ) {
			$d     = $p[ $lang ];
			$found = get_posts( array( 'post_type' => 'post', 'name' => $d['slug'], 'post_status' => array( 'publish', 'future', 'draft', 'pending', 'private' ), 'posts_per_page' => 1, 'lang' => '' ) );
			$post  = $found ? $found[0] : null;
			$data  = array(
				'post_type'      => 'post',
				'post_title'     => $d['title'],
				'post_name'      => $d['slug'],
				'post_content'   => flmm_mig_new_post_content( $d, $lang ),
				'post_excerpt'   => $d['seo'][1],
				'comment_status' => 'closed',
			);
			if ( $author ) {
				$data['post_author'] = $author;
			}
			if ( ! $post || 'publish' !== $post->post_status ) {
				// Sin publicar: fecha y estado desde el JSON (programado si la fecha es futura).
				$gmt                   = $p['publish_gmt'];
				$data['post_date_gmt'] = $gmt;
				$data['post_date']     = get_date_from_gmt( $gmt );
				$data['post_status']   = strtotime( $gmt . ' UTC' ) > time() ? 'future' : 'publish';
				$data['edit_date']     = true;
			}
			// Ya publicado: wp_update_post conserva su estado y su fecha.
			if ( $post ) {
				$data['ID'] = $post->ID;
				$id         = wp_update_post( wp_slash( $data ), true );
			} else {
				$id = wp_insert_post( wp_slash( $data ), true );
			}
			if ( is_wp_error( $id ) ) {
				flmm_mig_log( 'Artículo ' . $d['slug'] . ': ' . $id->get_error_message(), 'error' );
				continue;
			}
			pll_set_post_language( $id, $lang );
			if ( ! empty( $cat[ $lang ] ) ) {
				wp_set_post_categories( $id, array( $cat[ $lang ] ), false );
			}
			$img = flmm_mig_image( $p['image'], $lang );
			if ( $img ) {
				set_post_thumbnail( $id, $img['id'] );
			}
			flmm_mig_set_seo( $id, $d['seo'] );
			$ids[ $lang ] = $id;
		}
		if ( count( $ids ) === 2 ) {
			pll_save_post_translations( $ids );
			flmm_mig_log( sprintf( '%s: EN #%d (%s) y ES #%d, %s %s UTC.', $p['en']['slug'], $ids['en'], get_post_status( $ids['en'] ), $ids['es'], 'future' === get_post_status( $ids['en'] ) ? 'programado para' : 'fecha', $p['publish_gmt'] ) );
		}
	}
}
