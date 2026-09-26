<?php
/**
 * Biografía del autor al final del artículo.
 *
 * Toma el nombre, el cargo (flmm_role), la biografía y el LinkedIn del perfil del usuario.
 * En español usa los campos con sufijo _es si existen.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

$flmm_post = get_post( $post_id );
if ( ! $flmm_post ) {
	return;
}
$flmm_author   = (int) $flmm_post->post_author;
$flmm_bio      = flmm_author_field( $flmm_author, 'description' );
$flmm_role     = flmm_author_field( $flmm_author, 'flmm_role' );
$flmm_linkedin = get_the_author_meta( 'flmm_linkedin', $flmm_author );
?>
<div class="flmm-author">
	<?php echo flmm_author_avatar( $flmm_author ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<div>
		<p class="flmm-author__name"><?php echo esc_html( get_the_author_meta( 'display_name', $flmm_author ) ); ?></p>
		<?php if ( $flmm_role ) : ?>
			<p class="flmm-author__role"><?php echo esc_html( $flmm_role ); ?></p>
		<?php endif; ?>
		<?php if ( $flmm_bio ) : ?>
			<p class="flmm-author__bio"><?php echo wp_kses_post( $flmm_bio ); ?></p>
		<?php endif; ?>
		<?php if ( $flmm_linkedin ) : ?>
			<a class="flmm-author__link" href="<?php echo esc_url( $flmm_linkedin ); ?>" target="_blank" rel="noopener me">LinkedIn <span aria-hidden="true">↗</span></a>
		<?php endif; ?>
	</div>
</div>
