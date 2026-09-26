<?php
/**
 * Soportes, menús, recursos, estilos de bloque y categorías de patrones.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Soportes del theme y ubicaciones de menú.
 *
 * Los menús son clásicos para que Polylang (versión gratuita) permita uno por idioma.
 */
function flmm_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	remove_theme_support( 'core-block-patterns' );
	add_editor_style( 'assets/css/theme.css' );

	register_nav_menus(
		array(
			'primary'        => 'Header',
			'footer-nav'     => 'Footer: Navegación',
			'footer-company' => 'Footer: Empresa',
		)
	);
}
add_action( 'after_setup_theme', 'flmm_setup' );

/**
 * CSS y JS del frontend.
 */
function flmm_enqueue_assets() {
	wp_enqueue_style( 'flmm-theme', FLMM_URI . '/assets/css/theme.css', array(), FLMM_VERSION );
	wp_enqueue_script( 'flmm-theme', FLMM_URI . '/assets/js/theme.js', array(), FLMM_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_localize_script(
		'flmm-theme',
		'flmmTheme',
		array(
			'lang'    => flmm_lang(),
			'email'   => flmm_option( 'email' ),
			'copied'  => flmm__( 'Copied!' ),
			'fill'    => flmm__( 'Please fill in all three fields.' ),
			'opening' => flmm__( 'Opening your email…' ),
			'subject' => flmm__( 'Website inquiry: ' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'flmm_enqueue_assets' );

/**
 * Precarga de la tipografía principal.
 */
function flmm_preload_fonts() {
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( FLMM_URI . '/assets/fonts/Geist-Variable.woff2' )
	);
}
add_action( 'wp_head', 'flmm_preload_fonts', 2 );

/**
 * Marca el documento cuando hay JS, para que las animaciones no oculten contenido sin JS.
 */
function flmm_js_class() {
	echo "<script>document.documentElement.classList.add('js')</script>\n";
}
add_action( 'wp_head', 'flmm_js_class', 0 );

/**
 * Categorías de patrones y estilos de bloque.
 */
function flmm_register_block_styles() {
	register_block_pattern_category( 'flmm-home', array( 'label' => 'FLMM: Home' ) );
	register_block_pattern_category( 'flmm-service', array( 'label' => 'FLMM: Servicio' ) );
	register_block_pattern_category( 'flmm-page', array( 'label' => 'FLMM: Páginas' ) );
	register_block_pattern_category( 'flmm-article', array( 'label' => 'FLMM: Artículo' ) );

	register_block_style( 'core/button', array( 'name' => 'ghost', 'label' => 'Contorno' ) );
	register_block_style( 'core/group', array( 'name' => 'tldr', 'label' => 'TL;DR' ) );
	register_block_style( 'core/group', array( 'name' => 'takeaways', 'label' => 'Puntos clave' ) );
	register_block_style( 'core/group', array( 'name' => 'card', 'label' => 'Tarjeta' ) );
	register_block_style( 'core/paragraph', array( 'name' => 'label', 'label' => 'Etiqueta' ) );
	register_block_style( 'core/paragraph', array( 'name' => 'tag', 'label' => 'Tag' ) );
	register_block_style( 'core/table', array( 'name' => 'comparison', 'label' => 'Comparativa' ) );
	register_block_style( 'core/list', array( 'name' => 'dots', 'label' => 'Puntos rosa' ) );
}
add_action( 'init', 'flmm_register_block_styles' );

/**
 * Carga la hoja del theme también dentro del editor de bloques (iframe).
 */
function flmm_editor_assets() {
	wp_enqueue_style( 'flmm-editor', FLMM_URI . '/assets/css/editor.css', array(), FLMM_VERSION );
}
add_action( 'enqueue_block_editor_assets', 'flmm_editor_assets' );
