<?php
/**
 * Cabecera del blog y de los archivos: título y filtros por categoría (enlaces).
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

$flmm_is_term = is_category() || is_tag();
$flmm_title   = $flmm_is_term ? single_term_title( '', false ) : flmm__( 'Ideas for growing with data and AI.' );
$flmm_desc    = $flmm_is_term ? wp_strip_all_tags( term_description() ) : flmm__( 'Articles on performance marketing, SEO, AEO and artificial intelligence, written by our team from what we see in real accounts.' );
if ( is_author() ) {
	$flmm_title = get_the_author_meta( 'display_name', get_queried_object_id() );
	$flmm_desc  = flmm_author_field( get_queried_object_id(), 'description' );
}
$flmm_cats = get_categories(
	array(
		'hide_empty' => true,
		'exclude'    => array( (int) get_option( 'default_category' ) ),
	)
);
$flmm_current = is_category() ? get_queried_object_id() : 0;
?>
<div class="flmm-blog-hero">
	<p class="flmm-label flmm-rv"><?php flmm_e( 'Blog' ); ?></p>
	<h1 class="flmm-rv"><?php echo esc_html( $flmm_title ); ?></h1>
	<?php if ( $flmm_desc ) : ?>
		<p class="flmm-blog-hero__lead flmm-rv"><?php echo esc_html( $flmm_desc ); ?></p>
	<?php endif; ?>
	<?php if ( $flmm_cats ) : ?>
		<nav class="flmm-chips flmm-rv" aria-label="<?php echo esc_attr( flmm__( 'Filter by category' ) ); ?>">
			<a class="flmm-chip" href="<?php echo esc_url( flmm_blog_url() ); ?>"<?php echo is_home() ? ' aria-current="page"' : ''; ?>><?php flmm_e( 'All' ); ?></a>
			<?php foreach ( $flmm_cats as $flmm_cat ) : ?>
				<a class="flmm-chip" href="<?php echo esc_url( get_category_link( $flmm_cat ) ); ?>"<?php echo $flmm_current === $flmm_cat->term_id ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $flmm_cat->name ); ?></a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>
</div>
