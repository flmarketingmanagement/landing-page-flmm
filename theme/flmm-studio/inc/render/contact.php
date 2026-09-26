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
					$flmm_req  = 'es' === flmm_lang() ? '(Obligatorio)' : '(Required)';
					$flmm_json = static function ( $data ) {
						return wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
					};
					$flmm_field = static function ( $type, $label, $input = array(), $attrs = array() ) use ( $flmm_json, $flmm_req ) {
						return '<!-- wp:jetpack/field-' . $type . ' ' . $flmm_json( array_merge( array( 'required' => true ), $attrs ) ) . ' --><div>'
							. '<!-- wp:jetpack/label ' . $flmm_json( array( 'label' => $label, 'requiredText' => $flmm_req ) ) . ' /-->'
							. '<!-- wp:jetpack/input' . ( $input ? ' ' . $flmm_json( $input ) : '' ) . ' /-->'
							. '</div><!-- /wp:jetpack/field-' . $type . ' -->';
					};
					$flmm_form = '<!-- wp:jetpack/contact-form ' . $flmm_json(
						array(
							'to'                    => $flmm_email,
							'subject'               => flmm__( 'Website inquiry: ' ) . get_bloginfo( 'name' ),
							'confirmationType'      => 'text',
							'customThankyou'        => 'message',
							'customThankyouHeading' => flmm__( 'Message sent' ),
							'customThankyouMessage' => flmm__( 'Thanks! We received your message and will reply soon.' ),
							'jetpackCRM'            => false,
							'submitButtonText'      => flmm__( 'Send' ) . ' →',
							'className'             => 'flmm-jetpack-form',
						)
					) . ' --><div class="wp-block-jetpack-contact-form">'
						. $flmm_field( 'name', flmm__( 'Name' ), array(), array( 'width' => 50 ) )
						. $flmm_field( 'email', flmm__( 'Email' ), array( 'type' => 'email' ), array( 'width' => 50 ) )
						. flmm_contact_services_field()
						. $flmm_field( 'textarea', flmm__( 'How can we help?' ), array( 'type' => 'textarea' ) )
						. '</div><!-- /wp:jetpack/contact-form -->';
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
