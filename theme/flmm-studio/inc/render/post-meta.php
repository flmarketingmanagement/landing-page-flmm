<?php
/**
 * Categoría, fecha y minutos de lectura del artículo.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

$flmm_post = get_post( $post_id );
if ( ! $flmm_post ) {
	return;
}
$flmm_cat = flmm_primary_category( $flmm_post );
?>
<div class="flmm-meta">
	<?php if ( $flmm_cat ) : ?>
		<a class="flmm-meta__cat" href="<?php echo esc_url( get_category_link( $flmm_cat ) ); ?>"><?php echo esc_html( $flmm_cat->name ); ?></a>
	<?php endif; ?>
	<time datetime="<?php echo esc_attr( get_post_time( 'c', false, $flmm_post ) ); ?>"><?php echo esc_html( flmm_date( $flmm_post->post_date ) ); ?></time>
	<span><?php echo esc_html( sprintf( flmm__( '%d min read' ), flmm_reading_time( $flmm_post ) ) ); ?></span>
</div>
