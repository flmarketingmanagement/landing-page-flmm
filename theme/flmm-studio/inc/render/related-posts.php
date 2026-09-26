<?php
/**
 * Artículos relacionados: misma categoría, mismo idioma, sin el actual.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

$flmm_post = get_post( $post_id );
if ( ! $flmm_post ) {
	return;
}
$flmm_cat  = flmm_primary_category( $flmm_post );
$flmm_args = array(
	'post_type'           => 'post',
	'posts_per_page'      => 2,
	'post__not_in'        => array( $flmm_post->ID ),
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);
if ( function_exists( 'pll_get_post_language' ) ) {
	$flmm_args['lang'] = pll_get_post_language( $flmm_post->ID );
}
$flmm_query = new WP_Query( $flmm_cat ? array_merge( $flmm_args, array( 'cat' => $flmm_cat->term_id ) ) : $flmm_args );
if ( $flmm_query->post_count < 2 ) {
	$flmm_query = new WP_Query( $flmm_args );
}
if ( ! $flmm_query->have_posts() ) {
	return;
}
?>
<section class="flmm-section is-surface flmm-related-wrap">
<div class="flmm-wrap flmm-related">
	<div class="flmm-sec-head">
		<div>
			<p class="flmm-label"><?php flmm_e( 'Keep reading' ); ?></p>
			<h2><?php flmm_e( 'Related articles' ); ?></h2>
		</div>
	</div>
	<div class="flmm-related__grid">
		<?php
		while ( $flmm_query->have_posts() ) :
			$flmm_query->the_post();
			$flmm_rcat = flmm_primary_category();
			?>
			<article class="flmm-card">
				<?php if ( has_post_thumbnail() ) : ?>
					<a class="flmm-card__ph" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
						<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
					</a>
				<?php endif; ?>
				<div class="flmm-meta">
					<?php if ( $flmm_rcat ) : ?>
						<span class="flmm-meta__cat"><?php echo esc_html( $flmm_rcat->name ); ?></span>
					<?php endif; ?>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( flmm_date( get_post_field( 'post_date' ) ) ); ?></time>
				</div>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			</article>
			<?php
		endwhile;
		wp_reset_postdata();
		?>
	</div>
</div>
</section>
