<?php
/**
 * Sección de contacto: formulario corto + "Envíame un mail" + Telegram.
 *
 * Usa Jetpack Forms si está activo (envío por el servidor, antispam con Akismet).
 * Si no, muestra el formulario del diseño, que abre el correo del visitante.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

$flmm_email    = flmm_option( 'email' );
$flmm_telegram = flmm_option( 'telegram' );
$flmm_jetpack  = WP_Block_Type_Registry::get_instance()->is_registered( 'jetpack/contact-form' );

$flmm_actions = sprintf(
	'<div class="flmm-contact__alt"><span class="flmm-contact__or">%1$s</span><a class="flmm-btn flmm-btn--ghost" href="%2$s">%3$s</a>%4$s</div>',
	esc_html( flmm__( 'or reach me by' ) ),
	esc_url( 'mailto:' . $flmm_email ),
	esc_html( flmm__( 'Mail' ) ),
	$flmm_telegram ? sprintf( '<a class="flmm-btn flmm-btn--ghost" href="%s" target="_blank" rel="noopener">Telegram</a>', esc_url( $flmm_telegram ) ) : ''
);
?>
<div class="flmm-contact" id="contact">
	<div class="flmm-wrap flmm-contact__grid">
		<div class="flmm-contact__main">
			<p class="flmm-label flmm-rv"><?php flmm_e( 'Contact' ); ?></p>
			<h2 class="flmm-contact__title flmm-rv"><?php flmm_e( 'Ready to grow?' ); ?> <span><?php flmm_e( 'Let’s talk.' ); ?></span></h2>
			<?php if ( $flmm_jetpack ) : ?>
				<div class="flmm-form flmm-form--jetpack flmm-rv">
					<?php
					$flmm_form = sprintf(
						'<!-- wp:jetpack/contact-form %1$s --><div class="wp-block-jetpack-contact-form">'
						. '<!-- wp:jetpack/field-name %2$s /-->'
						. '<!-- wp:jetpack/field-email %3$s /-->'
						. '<!-- wp:jetpack/field-textarea %4$s /-->'
						. '<!-- wp:jetpack/button %5$s /-->'
						. '</div><!-- /wp:jetpack/contact-form -->',
						wp_json_encode(
							array(
								'to'                    => $flmm_email,
								'subject'               => flmm__( 'Website inquiry: ' ) . get_bloginfo( 'name' ),
								'customThankyou'        => 'message',
								'customThankyouMessage' => flmm__( 'Thanks! We received your message and will reply soon.' ),
								'className'             => 'flmm-jetpack-form',
							)
						),
						wp_json_encode( array( 'label' => flmm__( 'Name' ), 'required' => true, 'width' => 50 ) ),
						wp_json_encode( array( 'label' => flmm__( 'Email' ), 'required' => true, 'width' => 50 ) ),
						wp_json_encode( array( 'label' => flmm__( 'How can we help?' ), 'required' => true ) ),
						wp_json_encode( array( 'element' => 'button', 'text' => flmm__( 'Send' ) . ' →', 'className' => 'flmm-form__submit' ) )
					);
					echo do_blocks( apply_filters( 'flmm_contact_form_markup', $flmm_form ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $flmm_actions; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>
			<?php else : ?>
				<form class="flmm-form flmm-form--mailto flmm-rv" data-flmm-mailto novalidate>
					<label><span><?php flmm_e( 'Name' ); ?></span><input name="name" autocomplete="name" required></label>
					<label><span><?php flmm_e( 'Email' ); ?></span><input name="email" type="email" autocomplete="email" required></label>
					<label class="flmm-form__full"><span><?php flmm_e( 'How can we help?' ); ?></span><textarea name="message" rows="2" required></textarea></label>
					<div class="flmm-form__full flmm-form__actions">
						<button class="flmm-btn flmm-btn--primary" type="submit"><?php flmm_e( 'Send' ); ?> <?php echo flmm_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
						<?php echo $flmm_actions; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<p class="flmm-form__msg flmm-form__full" role="status" aria-live="polite"></p>
				</form>
			<?php endif; ?>
		</div>
		<span class="flmm-sig flmm-contact__sig" aria-hidden="true"></span>
	</div>
</div>
