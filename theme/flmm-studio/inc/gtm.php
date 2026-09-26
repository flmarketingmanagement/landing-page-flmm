<?php
/**
 * Google Tag Manager.
 *
 * Modo automático: si GTM4WP está activo, el theme no carga GTM para no duplicarlo.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * ¿Hay otro plugin cargando GTM?
 */
function flmm_gtm_plugin_active() {
	return defined( 'GTM4WP_VERSION' ) || function_exists( 'gtm4wp_wp_header_begin' );
}

/**
 * ¿Debe el theme imprimir GTM?
 */
function flmm_gtm_should_load() {
	$id   = flmm_option( 'gtm_id' );
	$mode = flmm_option( 'gtm_mode' );
	if ( ! $id || 'off' === $mode ) {
		return false;
	}
	if ( 'auto' === $mode && flmm_gtm_plugin_active() ) {
		return false;
	}
	return (bool) apply_filters( 'flmm_gtm_should_load', true );
}

/**
 * Script de GTM en el head, lo más arriba posible.
 */
function flmm_gtm_head() {
	if ( ! flmm_gtm_should_load() ) {
		return;
	}
	$id = flmm_option( 'gtm_id' );
	?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo esc_js( $id ); ?>');</script>
<!-- End Google Tag Manager -->
	<?php
}
add_action( 'wp_head', 'flmm_gtm_head', 1 );

/**
 * Respaldo sin JS, justo después de abrir el body.
 */
function flmm_gtm_body() {
	if ( ! flmm_gtm_should_load() ) {
		return;
	}
	printf(
		'<noscript><iframe src="%s" height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript>' . "\n",
		esc_url( 'https://www.googletagmanager.com/ns.html?id=' . flmm_option( 'gtm_id' ) )
	);
}
add_action( 'wp_body_open', 'flmm_gtm_body', 1 );
