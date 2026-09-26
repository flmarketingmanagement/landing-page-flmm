<?php
/**
 * Funciones de apoyo para las plantillas y los bloques dinámicos.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Logo: firma dentro de un círculo. La firma es una máscara CSS que toma el color del texto.
 *
 * @return string
 */
function flmm_mark() {
	return '<span class="flmm-mark" aria-hidden="true"><span class="flmm-sig"></span></span>';
}

/**
 * Bloque de marca (logo + nombre).
 *
 * @param bool $with_text Mostrar el nombre.
 * @return string
 */
function flmm_brand( $with_text = true ) {
	$text = $with_text
		? '<span class="flmm-brand__txt">FL Marketing Management<small>' . esc_html( flmm__( 'Boutique digital agency' ) ) . '</small></span>'
		: '';
	return sprintf(
		'<a class="flmm-brand" href="%s" aria-label="FL Marketing Management">%s%s</a>',
		esc_url( flmm_home_url() ),
		flmm_mark(),
		$text
	);
}

/**
 * URL del índice del blog en el idioma actual.
 *
 * @return string
 */
function flmm_blog_url() {
	$id = (int) get_option( 'page_for_posts' );
	if ( $id ) {
		if ( function_exists( 'pll_get_post' ) ) {
			$translated = pll_get_post( $id );
			$id         = $translated ? $translated : $id;
		}
		return get_permalink( $id );
	}
	return 'es' === flmm_lang() ? home_url( '/es/blog/' ) : home_url( '/blog/' );
}

/**
 * Enlace a una sección del home. En el propio home queda como ancla corta.
 *
 * @param string $anchor Ancla sin #.
 * @return string
 */
function flmm_section_url( $anchor ) {
	return ( is_front_page() ? '' : flmm_home_url() ) . '#' . $anchor;
}

/**
 * Enlaces de un menú clásico, o los enlaces por defecto si la ubicación está vacía.
 * Polylang asigna un menú distinto por idioma a cada ubicación.
 *
 * @param string $location Ubicación del menú.
 * @param array  $defaults Lista de array( etiqueta, url ).
 * @return array Lista de array( 'label' => , 'url' => , 'external' => , 'current' => ).
 */
function flmm_menu_links( $location, $defaults ) {
	$links     = array();
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations[ $location ] ) ) {
		$items = wp_get_nav_menu_items( $locations[ $location ] );
		if ( $items ) {
			foreach ( $items as $item ) {
				if ( (int) $item->menu_item_parent ) {
					continue;
				}
				$links[] = array(
					'label'    => $item->title,
					'url'      => $item->url,
					'external' => '_blank' === $item->target,
					'current'  => 'post_type' === $item->type && is_singular() && (int) $item->object_id === get_queried_object_id(),
				);
			}
			return $links;
		}
	}
	foreach ( $defaults as $default ) {
		$links[] = array(
			'label'    => $default[0],
			'url'      => $default[1],
			'external' => ! empty( $default[2] ),
			'current'  => false,
		);
	}
	return $links;
}

/**
 * Pinta una lista de enlaces.
 *
 * @param array $links Resultado de flmm_menu_links().
 * @return string
 */
function flmm_render_links( $links ) {
	$html = '';
	foreach ( $links as $link ) {
		$html .= sprintf(
			'<a href="%s"%s%s>%s</a>',
			esc_url( $link['url'] ),
			$link['external'] ? ' target="_blank" rel="noopener"' : '',
			$link['current'] ? ' aria-current="page"' : '',
			esc_html( $link['label'] )
		);
	}
	return $html;
}

/**
 * Selector de idioma EN / ES.
 *
 * @return string
 */
function flmm_language_switcher() {
	$items = array();
	if ( function_exists( 'pll_the_languages' ) ) {
		$languages = pll_the_languages(
			array(
				'raw'                    => 1,
				'hide_if_empty'          => 0,
				'hide_if_no_translation' => 0,
			)
		);
		foreach ( (array) $languages as $language ) {
			$items[] = array(
				'code'    => strtoupper( substr( $language['slug'], 0, 2 ) ),
				'url'     => $language['url'],
				'current' => ! empty( $language['current_lang'] ),
				'name'    => $language['name'],
				'locale'  => str_replace( '_', '-', $language['locale'] ),
			);
		}
	}
	if ( ! $items ) {
		$items = array(
			array( 'code' => 'EN', 'url' => home_url( '/' ), 'current' => 'en' === flmm_lang(), 'name' => 'English', 'locale' => 'en' ),
			array( 'code' => 'ES', 'url' => '', 'current' => 'es' === flmm_lang(), 'name' => 'Español', 'locale' => 'es' ),
		);
	}
	$html = '<nav class="flmm-lang" aria-label="' . esc_attr( flmm__( 'Language' ) ) . '">';
	foreach ( $items as $item ) {
		if ( $item['current'] ) {
			$html .= sprintf( '<span aria-current="true" lang="%s" title="%s">%s</span>', esc_attr( $item['locale'] ), esc_attr( $item['name'] ), esc_html( $item['code'] ) );
		} elseif ( $item['url'] ) {
			$html .= sprintf( '<a href="%s" hreflang="%s" lang="%s" title="%s">%s</a>', esc_url( $item['url'] ), esc_attr( $item['locale'] ), esc_attr( $item['locale'] ), esc_attr( $item['name'] ), esc_html( $item['code'] ) );
		} else {
			$html .= sprintf( '<span aria-disabled="true" lang="%s" title="%s">%s</span>', esc_attr( $item['locale'] ), esc_attr( $item['name'] ), esc_html( $item['code'] ) );
		}
	}
	return $html . '</nav>';
}

/**
 * Iniciales para el avatar.
 *
 * @param string $name Nombre completo.
 * @return string
 */
function flmm_initials( $name ) {
	$parts    = preg_split( '/\s+/', trim( $name ) );
	$initials = '';
	foreach ( array_slice( $parts, 0, 2 ) as $part ) {
		$initials .= mb_substr( $part, 0, 1 );
	}
	return mb_strtoupper( $initials );
}

/**
 * Minutos de lectura (220 palabras por minuto).
 *
 * @param int|WP_Post|null $post Entrada.
 * @return int
 */
function flmm_reading_time( $post = null ) {
	$post  = get_post( $post );
	$words = $post ? str_word_count( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ) ) : 0;
	return max( 1, (int) round( $words / 220 ) );
}

/**
 * Primera categoría de una entrada.
 *
 * @param int|WP_Post|null $post Entrada.
 * @return WP_Term|null
 */
function flmm_primary_category( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return null;
	}
	if ( class_exists( 'RankMath\Helper' ) ) {
		$primary = get_post_meta( $post->ID, 'rank_math_primary_category', true );
		if ( $primary ) {
			$term = get_term( (int) $primary, 'category' );
			if ( $term && ! is_wp_error( $term ) ) {
				return $term;
			}
		}
	}
	$terms = get_the_category( $post->ID );
	return $terms ? $terms[0] : null;
}

/**
 * Flecha decorativa.
 *
 * @param string $char Carácter.
 * @return string
 */
function flmm_arrow( $char = '→' ) {
	return '<span class="flmm-arr" aria-hidden="true">' . $char . '</span>';
}

/**
 * Elementos de la ruta de navegación de la vista actual.
 *
 * @return array Lista de array( 'name' => , 'url' => ).
 */
function flmm_breadcrumb_items() {
	$items = array(
		array(
			'name' => flmm__( 'Home' ),
			'url'  => flmm_home_url(),
		),
	);
	if ( is_front_page() ) {
		return $items;
	}
	if ( is_singular( 'post' ) ) {
		$items[]  = array( 'name' => flmm__( 'Blog' ), 'url' => flmm_blog_url() );
		$category = flmm_primary_category();
		if ( $category ) {
			$items[] = array( 'name' => $category->name, 'url' => get_category_link( $category ) );
		}
		$items[] = array( 'name' => wp_strip_all_tags( get_the_title() ), 'url' => get_permalink() );
	} elseif ( is_page() ) {
		if ( 'page-service' === get_page_template_slug() ) {
			$items[] = array( 'name' => flmm__( 'Services' ), 'url' => flmm_home_url( '#services' ) );
		}
		foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $ancestor ) {
			$items[] = array( 'name' => wp_strip_all_tags( get_the_title( $ancestor ) ), 'url' => get_permalink( $ancestor ) );
		}
		$items[] = array( 'name' => wp_strip_all_tags( get_the_title() ), 'url' => get_permalink() );
	} elseif ( is_home() ) {
		$items[] = array( 'name' => flmm__( 'Blog' ), 'url' => flmm_blog_url() );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$items[] = array( 'name' => flmm__( 'Blog' ), 'url' => flmm_blog_url() );
		$items[] = array( 'name' => single_term_title( '', false ), 'url' => get_term_link( get_queried_object() ) );
	} elseif ( is_search() ) {
		$items[] = array( 'name' => flmm__( 'Search results' ), 'url' => '' );
	} elseif ( is_author() ) {
		$items[] = array( 'name' => flmm__( 'Blog' ), 'url' => flmm_blog_url() );
		$items[] = array( 'name' => get_the_author_meta( 'display_name', get_queried_object_id() ), 'url' => get_author_posts_url( get_queried_object_id() ) );
	}
	return $items;
}
