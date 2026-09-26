<?php
/**
 * Contenido de la página 404: ilustración por idioma, acciones y búsqueda.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

$flmm_404_lang = 'es' === flmm_lang() ? 'es' : 'en';
$flmm_404_alt  = 'es' === $flmm_404_lang
	? 'Error 404: un gorila con gorro rosa espera junto a una computadora antigua. Parece que esta página se fue de vacaciones. Volvamos al inicio y sigamos explorando.'
	: 'Error 404: a gorilla in a pink beanie waits next to an old computer. This page took a vacation. Let’s get you back on track.';
?>
<div class="flmm-404">
	<h1 class="screen-reader-text"><?php flmm_e( 'Page not found' ); ?></h1>
	<a class="flmm-404__art" href="<?php echo esc_url( flmm_home_url() ); ?>">
		<img src="<?php echo esc_url( FLMM_URI . '/assets/images/404-' . $flmm_404_lang . '.webp' ); ?>" alt="<?php echo esc_attr( $flmm_404_alt ); ?>" width="1536" height="1024" fetchpriority="high" decoding="async">
	</a>
	<div class="flmm-ctas">
		<a class="flmm-btn flmm-btn--primary" href="<?php echo esc_url( flmm_home_url() ); ?>"><?php flmm_e( 'Back to home' ); ?> <?php echo flmm_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		<a class="flmm-btn flmm-btn--ghost" href="<?php echo esc_url( flmm_blog_url() ); ?>"><?php flmm_e( 'Blog' ); ?></a>
	</div>
	<form class="flmm-search" role="search" method="get" action="<?php echo esc_url( flmm_home_url() ); ?>">
		<label class="screen-reader-text" for="flmm-s404"><?php flmm_e( 'Search' ); ?></label>
		<input id="flmm-s404" type="search" name="s">
		<button class="flmm-btn flmm-btn--ghost" type="submit"><?php flmm_e( 'Search' ); ?></button>
	</form>
</div>
