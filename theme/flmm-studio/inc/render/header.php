<?php
/**
 * Header: logo, menú, selector de idioma y botón de contacto.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

$flmm_links = flmm_menu_links(
	'primary',
	array(
		array( flmm__( 'Services' ), flmm_section_url( 'services' ) ),
		array( flmm__( 'Platforms' ), flmm_section_url( 'platforms' ) ),
		array( flmm__( 'Team' ), flmm_section_url( 'team' ) ),
		array( flmm__( 'Blog' ), flmm_blog_url() ),
		array( flmm__( 'Contact' ), '#contact' ),
	)
);
?>
<div class="flmm-header" data-flmm-header>
	<div class="flmm-wrap flmm-nav">
		<?php echo flmm_brand(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<nav class="flmm-links" id="flmm-menu" aria-label="<?php echo esc_attr( flmm__( 'Main navigation' ) ); ?>">
			<?php echo flmm_render_links( $flmm_links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</nav>
		<div class="flmm-nav-end">
			<?php echo flmm_language_switcher(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<a class="flmm-btn flmm-btn--primary flmm-nav-cta" href="#contact"><?php flmm_e( 'Let’s talk' ); ?> <?php echo flmm_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<button type="button" class="flmm-menu-toggle" aria-controls="flmm-menu" aria-expanded="false" data-label-open="<?php echo esc_attr( flmm__( 'Menu' ) ); ?>" data-label-close="<?php echo esc_attr( flmm__( 'Close menu' ) ); ?>">
				<span class="screen-reader-text"><?php flmm_e( 'Menu' ); ?></span>
				<span class="flmm-menu-toggle__bars" aria-hidden="true"></span>
			</button>
		</div>
	</div>
</div>
