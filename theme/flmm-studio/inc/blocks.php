<?php
/**
 * Bloques dinámicos del theme.
 *
 * Header, footer, contacto y las piezas del artículo se renderizan en PHP en cada visita,
 * así siguen el idioma activo aunque estén dentro de plantillas compartidas.
 * En el editor se muestran con una vista previa del servidor.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lista de bloques: nombre => título.
 */
function flmm_dynamic_blocks() {
	return array(
		'header'        => 'FLMM: Header',
		'footer'        => 'FLMM: Footer',
		'contact'       => 'FLMM: Contacto',
		'breadcrumbs'   => 'FLMM: Migas de pan',
		'updated'       => 'FLMM: Fecha de actualización',
		'post-meta'     => 'FLMM: Datos del artículo',
		'byline'        => 'FLMM: Autor (línea)',
		'toc'           => 'FLMM: Índice del artículo',
		'article-aside' => 'FLMM: Lateral del artículo',
		'author-box'    => 'FLMM: Biografía del autor',
		'related-posts' => 'FLMM: Artículos relacionados',
		'blog-hero'     => 'FLMM: Cabecera del blog',
		'not-found'     => 'FLMM: Página no encontrada',
		'search-hero'   => 'FLMM: Cabecera de búsqueda',
	);
}

/**
 * Registra los bloques y el script del editor.
 */
function flmm_register_dynamic_blocks() {
	wp_register_script(
		'flmm-blocks-editor',
		FLMM_URI . '/assets/js/editor-blocks.js',
		array( 'wp-blocks', 'wp-element', 'wp-server-side-render', 'wp-block-editor' ),
		FLMM_VERSION,
		true
	);
	wp_add_inline_script( 'flmm-blocks-editor', 'window.flmmBlocks=' . wp_json_encode( flmm_dynamic_blocks() ) . ';', 'before' );

	foreach ( flmm_dynamic_blocks() as $slug => $title ) {
		register_block_type(
			'flmm/' . $slug,
			array(
				'api_version'     => 3,
				'title'           => $title,
				'category'        => 'theme',
				'editor_script'   => 'flmm-blocks-editor',
				'supports'        => array( 'html' => false, 'multiple' => true, 'reusable' => false ),
				'uses_context'    => array( 'postId', 'postType' ),
				'render_callback' => static function ( $attributes, $content, $block ) use ( $slug ) {
					$file = FLMM_DIR . '/inc/render/' . $slug . '.php';
					if ( ! file_exists( $file ) ) {
						return '';
					}
					$post_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
					ob_start();
					include $file;
					return ob_get_clean();
				},
			)
		);
	}
}
add_action( 'init', 'flmm_register_dynamic_blocks' );

/**
 * Ajustes de bloques core dentro de las plantillas del theme, para que sigan el idioma activo:
 * fecha en formato del idioma, texto de "Leer artículo" y mensaje sin resultados.
 *
 * @param string $html  HTML del bloque.
 * @param array  $block Bloque.
 * @return string
 */
function flmm_localize_core_blocks( $html, $block ) {
	$class = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';
	if ( 'core/post-date' === $block['blockName'] && false !== strpos( $class, 'flmm-date' ) ) {
		$post_id = get_the_ID();
		if ( $post_id ) {
			return sprintf(
				'<div class="wp-block-post-date flmm-date"><time datetime="%s">%s</time></div>',
				esc_attr( get_post_time( 'c', false, $post_id ) ),
				esc_html( flmm_date( get_post_field( 'post_date', $post_id ) ) )
			);
		}
	}
	if ( 'core/read-more' === $block['blockName'] && false !== strpos( $class, 'flmm-more' ) ) {
		return preg_replace( '#>[^<]*(<span class="screen-reader-text">)#', '>' . esc_html( flmm__( 'Read article' ) ) . ' →$1', $html, 1 );
	}
	if ( 'core/paragraph' === $block['blockName'] && false !== strpos( $class, 'flmm-no-results' ) ) {
		return '<p class="flmm-no-results">' . esc_html( flmm__( 'No articles yet.' ) ) . '</p>';
	}
	return $html;
}
add_filter( 'render_block', 'flmm_localize_core_blocks', 10, 2 );
