<?php
/**
 * SEO y AEO del theme: robots.txt, llms.txt, hreflang x-default y redirecciones de respaldo.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * robots.txt: permite a los rastreadores de búsqueda con IA y declara el sitemap.
 * En sitios no públicos (staging) no toca nada, para mantener el bloqueo.
 *
 * @param string $output Contenido actual.
 * @param bool   $public Si el sitio es público.
 * @return string
 */
function flmm_robots_txt( $output, $public ) {
	if ( ! $public ) {
		return $output;
	}
	$bots = array( 'OAI-SearchBot', 'ChatGPT-User', 'Claude-SearchBot', 'Claude-User', 'PerplexityBot', 'Perplexity-User' );
	$add  = '';
	foreach ( $bots as $bot ) {
		if ( false === stripos( $output, 'User-agent: ' . $bot ) ) {
			$add .= "\nUser-agent: {$bot}\nAllow: /\n";
		}
	}
	if ( $add ) {
		$output = rtrim( $output ) . "\n\n# Buscadores con IA" . $add;
	}
	if ( false === stripos( $output, 'Sitemap:' ) ) {
		$sitemap = defined( 'RANK_MATH_VERSION' ) ? home_url( '/sitemap_index.xml' ) : home_url( '/wp-sitemap.xml' );
		$output .= "\nSitemap: " . $sitemap . "\n";
	}
	return $output;
}
add_filter( 'robots_txt', 'flmm_robots_txt', 99, 2 );

/**
 * hreflang x-default apuntando a la versión en inglés.
 *
 * @param array $hreflangs Idioma => URL.
 * @return array
 */
function flmm_hreflang_x_default( $hreflangs ) {
	if ( isset( $hreflangs['en'] ) && ! isset( $hreflangs['x-default'] ) ) {
		$hreflangs['x-default'] = $hreflangs['en'];
	}
	return $hreflangs;
}
add_filter( 'pll_rel_hreflang_attributes', 'flmm_hreflang_x_default' );

/**
 * Contenido de /llms.txt: descripción de la agencia y enlaces a servicios, páginas y artículos.
 *
 * @return string
 */
function flmm_llms_txt() {
	$cached = get_transient( 'flmm_llms_txt' );
	if ( false !== $cached ) {
		return $cached;
	}
	$lines   = array();
	$lines[] = '# FL Marketing Management';
	$lines[] = '';
	$lines[] = '> Boutique digital marketing agency for brands in the US and Latin America. One senior team covers strategy, performance marketing, SEO and AEO, content, e-commerce, analytics and AI automation, measured against revenue. Site in English (default) and Spanish (/es/). Contact: ' . flmm_option( 'email' ) . '.';
	$lines[] = '';

	$sections = array(
		'Services'  => array( 'post_type' => 'page', 'meta_key' => '_wp_page_template', 'meta_value' => 'page-service' ),
		'Company'   => array( 'post_type' => 'page', 'post_name__in' => array( 'about', 'digitales-sin-fronteras', 'podcast', 'contact-us', 'privacy-policy' ) ),
		'Articles'  => array( 'post_type' => 'post' ),
	);
	foreach ( $sections as $title => $args ) {
		$posts = get_posts( array_merge( array( 'post_status' => 'publish', 'posts_per_page' => 50, 'orderby' => 'menu_order title', 'order' => 'ASC', 'lang' => 'en' ), $args ) );
		if ( ! $posts ) {
			continue;
		}
		$lines[] = '## ' . $title;
		$lines[] = '';
		foreach ( $posts as $post ) {
			$desc = get_post_meta( $post->ID, 'rank_math_description', true );
			if ( ! $desc ) {
				$desc = has_excerpt( $post ) ? get_the_excerpt( $post ) : '';
			}
			$line = '- [' . wp_strip_all_tags( get_the_title( $post ) ) . '](' . get_permalink( $post ) . ')' . ( $desc ? ': ' . wp_strip_all_tags( $desc ) : '' );
			if ( function_exists( 'pll_get_post' ) ) {
				$es = pll_get_post( $post->ID, 'es' );
				if ( $es && 'publish' === get_post_status( $es ) ) {
					$line .= ' (ES: ' . get_permalink( $es ) . ')';
				}
			}
			$lines[] = $line;
		}
		$lines[] = '';
	}
	$output = implode( "\n", $lines );
	set_transient( 'flmm_llms_txt', $output, 12 * HOUR_IN_SECONDS );
	return $output;
}

/**
 * Sirve /llms.txt.
 */
function flmm_serve_llms_txt() {
	if ( ! apply_filters( 'flmm_llms_txt_enabled', true ) || empty( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}
	$path = wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH );
	if ( '/llms.txt' !== $path ) {
		return;
	}
	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Robots-Tag: noindex' );
	echo flmm_llms_txt(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit;
}
add_action( 'init', 'flmm_serve_llms_txt', 99 );

/**
 * Vacía la caché de llms.txt al guardar contenido.
 */
function flmm_flush_llms_txt() {
	delete_transient( 'flmm_llms_txt' );
}
add_action( 'save_post', 'flmm_flush_llms_txt' );

/**
 * Redirecciones de respaldo si Rank Math no está disponible (lo activa la migración).
 */
function flmm_theme_redirects() {
	if ( ! get_option( 'flmm_theme_redirects' ) || ( ! function_exists( 'flmm_mig_redirects' ) && ! file_exists( FLMM_DIR . '/inc/migrate/data.php' ) ) ) {
		return;
	}
	if ( ! function_exists( 'flmm_mig_redirects' ) ) {
		require_once FLMM_DIR . '/inc/migrate/data.php';
	}
	$path = trim( (string) wp_parse_url( esc_url_raw( wp_unslash( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '' ) ), PHP_URL_PATH ), '/' );
	$map  = flmm_mig_redirects();
	if ( isset( $map[ $path ] ) ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'flmm_theme_redirects', 1 );

/**
 * Canonical del índice del blog en español: Rank Math puede sumarle el código de idioma
 * dos veces (/es/articulos/es/). Se fija a la URL de la página de entradas traducida.
 *
 * @param string $canonical URL canónica.
 * @return string
 */
function flmm_blog_canonical( $canonical ) {
	if ( is_home() && ! is_front_page() ) {
		$url   = flmm_blog_url();
		$paged = (int) get_query_var( 'paged' );
		return $paged > 1 ? trailingslashit( $url ) . 'page/' . $paged . '/' : $url;
	}
	return $canonical;
}
add_filter( 'rank_math/frontend/canonical', 'flmm_blog_canonical' );
