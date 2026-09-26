<?php
/**
 * Cabecera de resultados de búsqueda.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="flmm-blog-hero">
	<p class="flmm-label"><?php flmm_e( 'Search' ); ?></p>
	<h1><?php echo esc_html( sprintf( flmm__( 'Results for “%s”' ), get_search_query() ) ); ?></h1>
	<form class="flmm-search" role="search" method="get" action="<?php echo esc_url( flmm_home_url() ); ?>">
		<label class="screen-reader-text" for="flmm-s"><?php flmm_e( 'Search' ); ?></label>
		<input id="flmm-s" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>">
		<button class="flmm-btn flmm-btn--primary" type="submit"><?php flmm_e( 'Search' ); ?></button>
	</form>
	<?php if ( ! have_posts() ) : ?>
		<p class="flmm-blog-hero__lead"><?php flmm_e( 'Nothing matched your search. Try different words.' ); ?></p>
	<?php endif; ?>
</div>
