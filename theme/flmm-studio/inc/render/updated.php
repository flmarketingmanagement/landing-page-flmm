<?php
/**
 * "Actualizado: septiembre de 2026" con la fecha de modificación de la página.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

$flmm_post = get_post( $post_id );
if ( ! $flmm_post ) {
	return;
}
?>
<p class="flmm-updated"><time datetime="<?php echo esc_attr( get_post_modified_time( 'Y-m-d', false, $flmm_post ) ); ?>"><?php echo esc_html( sprintf( flmm__( 'Updated: %s' ), flmm_date( $flmm_post->post_modified, 'month' ) ) ); ?></time></p>
