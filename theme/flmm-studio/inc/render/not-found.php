<?php
/**
 * Contenido de la página 404.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="flmm-404">
	<p class="flmm-label">404</p>
	<h1><?php flmm_e( 'Page not found' ); ?></h1>
	<p class="flmm-blog-hero__lead"><?php flmm_e( 'The page you are looking for does not exist or was moved.' ); ?></p>
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
