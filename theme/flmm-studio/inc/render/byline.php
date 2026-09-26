<?php
/**
 * Autor y fecha de actualización bajo el título del artículo.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

$flmm_post = get_post( $post_id );
if ( ! $flmm_post ) {
	return;
}
$flmm_author = (int) $flmm_post->post_author;
$flmm_name   = get_the_author_meta( 'display_name', $flmm_author );
$flmm_role   = flmm_author_field( $flmm_author, 'flmm_role' );
?>
<div class="flmm-byline">
	<?php echo flmm_author_avatar( $flmm_author, 'sm' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<div>
		<a class="flmm-byline__name" href="<?php echo esc_url( get_author_posts_url( $flmm_author ) ); ?>" rel="author"><?php echo esc_html( $flmm_name ); ?></a>
		<small>
			<?php
			echo esc_html( $flmm_role ? $flmm_role . ' · ' : '' );
			printf( '<time datetime="%s">%s</time>', esc_attr( get_post_modified_time( 'c', false, $flmm_post ) ), esc_html( sprintf( flmm__( 'Updated %s' ), flmm_date( $flmm_post->post_modified ) ) ) );
			?>
		</small>
	</div>
</div>
