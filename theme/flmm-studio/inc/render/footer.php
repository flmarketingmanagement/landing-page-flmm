<?php
/**
 * Footer: marca, columnas Navegación, Empresa, Contacto y Redes, y sello AMA.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

$flmm_nav = flmm_menu_links(
	'footer-nav',
	array(
		array( flmm__( 'Services' ), flmm_section_url( 'services' ) ),
		array( flmm__( 'Platforms' ), flmm_section_url( 'platforms' ) ),
		array( flmm__( 'Method' ), flmm_section_url( 'method' ) ),
		array( flmm__( 'Team' ), flmm_section_url( 'team' ) ),
		array( flmm__( 'Blog' ), flmm_blog_url() ),
	)
);
$flmm_company = flmm_menu_links(
	'footer-company',
	array(
		array( flmm__( 'About us' ), flmm_page_url( 'about' ) ),
		array( 'Digitales Sin Fronteras', flmm_page_url( 'digitales-sin-fronteras' ) ),
		array( 'Marketing Today Podcast', flmm_page_url( 'podcast' ) ),
		array( flmm__( 'Privacy Policy' ), flmm_page_url( 'privacy-policy' ) ),
	)
);
$flmm_email    = flmm_option( 'email' );
$flmm_telegram = flmm_option( 'telegram' );
$flmm_agent    = flmm_option( 'agent_url' );
$flmm_social   = array_filter(
	array(
		'LinkedIn'  => flmm_option( 'linkedin' ),
		'Instagram' => flmm_option( 'instagram' ),
	)
);
?>
<div class="flmm-footer">
	<div class="flmm-wrap">
		<div class="flmm-foot">
			<div class="flmm-foot__brand"><?php echo flmm_brand(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<nav aria-label="<?php echo esc_attr( flmm__( 'Navigation' ) ); ?>">
				<p class="flmm-foot__title"><?php flmm_e( 'Navigation' ); ?></p>
				<?php echo flmm_render_links( $flmm_nav ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</nav>
			<nav aria-label="<?php echo esc_attr( flmm__( 'Company' ) ); ?>">
				<p class="flmm-foot__title"><?php flmm_e( 'Company' ); ?></p>
				<?php echo flmm_render_links( $flmm_company ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</nav>
			<div>
				<p class="flmm-foot__title"><?php flmm_e( 'Contact' ); ?></p>
				<a href="<?php echo esc_url( 'mailto:' . $flmm_email ); ?>"><?php flmm_e( 'Send me an email' ); ?></a>
				<?php if ( $flmm_telegram ) : ?>
					<a href="<?php echo esc_url( $flmm_telegram ); ?>" target="_blank" rel="noopener">Telegram</a>
				<?php endif; ?>
				<?php if ( $flmm_agent ) : ?>
					<a href="<?php echo esc_url( $flmm_agent ); ?>" target="_blank" rel="noopener">Marky Digital (Agent)</a>
				<?php endif; ?>
			</div>
			<div>
				<p class="flmm-foot__title"><?php flmm_e( 'Social' ); ?></p>
				<?php foreach ( $flmm_social as $flmm_name => $flmm_url ) : ?>
					<a href="<?php echo esc_url( $flmm_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $flmm_name ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="flmm-legal">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> FL Marketing Management, LLC</span>
			<a class="flmm-ama" href="https://www.ama.org/pcm-professional-certified-marketer/" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( flmm__( 'AMA Professional Certified Marketer, Marketing Management' ) ); ?>">
				<img class="flmm-ama__seal" src="<?php echo esc_url( FLMM_URI . '/assets/brand/ama-seal.png' ); ?>" alt="" width="54" height="54" loading="lazy" decoding="async">
				<img class="flmm-ama__text" src="<?php echo esc_url( FLMM_URI . '/assets/brand/ama-text.png' ); ?>" alt="AMA PCM, Marketing Management" width="160" height="44" loading="lazy" decoding="async">
			</a>
		</div>
	</div>
</div>
