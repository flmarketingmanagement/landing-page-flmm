<?php
/**
 * Lateral del artículo: llamado a contacto y compartir.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

$flmm_url   = get_permalink( $post_id );
$flmm_title = wp_strip_all_tags( get_the_title( $post_id ) );
?>
<aside class="flmm-aside">
	<div class="flmm-aside__box">
		<p class="flmm-aside__title"><?php flmm_e( 'Need help with this?' ); ?></p>
		<p><?php flmm_e( 'We review your account and tell you what is holding back your growth.' ); ?></p>
		<a class="flmm-btn flmm-btn--primary flmm-btn--sm" href="#contact"><?php flmm_e( 'Get in touch' ); ?> <?php echo flmm_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	</div>
	<div class="flmm-aside__box">
		<p class="flmm-label"><?php flmm_e( 'Share' ); ?></p>
		<div class="flmm-share">
			<a href="<?php echo esc_url( 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $flmm_url ) ); ?>" target="_blank" rel="noopener">LinkedIn</a>
			<a href="<?php echo esc_url( 'https://twitter.com/intent/tweet?url=' . rawurlencode( $flmm_url ) . '&text=' . rawurlencode( $flmm_title ) ); ?>" target="_blank" rel="noopener">X</a>
			<a href="<?php echo esc_url( 'https://wa.me/?text=' . rawurlencode( $flmm_title . ' ' . $flmm_url ) ); ?>" target="_blank" rel="noopener">WhatsApp</a>
			<button type="button" data-flmm-copy="<?php echo esc_url( $flmm_url ); ?>"><?php flmm_e( 'Copy link' ); ?></button>
		</div>
	</div>
</aside>
