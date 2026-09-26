<?php
/**
 * Ajustes del theme (Apariencia > FLMM Studio).
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Valores por defecto.
 */
function flmm_default_options() {
	return array(
		'gtm_id'      => 'GTM-N3SW2MJ4',
		'gtm_mode'    => 'auto',
		'schema_mode' => 'auto',
		'email'       => 'hello@flmarketingmanagement.com',
		'telegram'    => 'https://t.me/aleloveeee',
		'agent_url'   => '',
		'linkedin'    => 'https://www.linkedin.com/company/fl-marketing-management/',
		'instagram'   => 'https://www.instagram.com/flmarketingmanagement/',
	);
}

/**
 * Lee una opción del theme.
 *
 * @param string $key Clave.
 * @return string
 */
function flmm_option( $key ) {
	$options = wp_parse_args( (array) get_option( 'flmm_options', array() ), flmm_default_options() );
	$value   = isset( $options[ $key ] ) ? $options[ $key ] : '';
	return apply_filters( 'flmm_option_' . $key, $value );
}

/**
 * Limpia los valores guardados.
 *
 * @param array $input Valores enviados.
 * @return array
 */
function flmm_sanitize_options( $input ) {
	$defaults = flmm_default_options();
	$input    = (array) $input;
	$clean    = array();

	$gtm             = isset( $input['gtm_id'] ) ? strtoupper( trim( $input['gtm_id'] ) ) : '';
	$clean['gtm_id'] = preg_match( '/^GTM-[A-Z0-9]+$/', $gtm ) ? $gtm : '';

	$clean['gtm_mode']    = isset( $input['gtm_mode'] ) && in_array( $input['gtm_mode'], array( 'auto', 'on', 'off' ), true ) ? $input['gtm_mode'] : $defaults['gtm_mode'];
	$clean['schema_mode'] = isset( $input['schema_mode'] ) && in_array( $input['schema_mode'], array( 'auto', 'all', 'off' ), true ) ? $input['schema_mode'] : $defaults['schema_mode'];
	$clean['email']       = isset( $input['email'] ) && is_email( $input['email'] ) ? sanitize_email( $input['email'] ) : $defaults['email'];

	foreach ( array( 'telegram', 'agent_url', 'linkedin', 'instagram' ) as $key ) {
		$clean[ $key ] = isset( $input[ $key ] ) ? esc_url_raw( trim( $input[ $key ] ) ) : $defaults[ $key ];
	}
	return $clean;
}

/**
 * Registra la opción y la página.
 */
function flmm_register_settings() {
	register_setting(
		'flmm_options',
		'flmm_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'flmm_sanitize_options',
			'default'           => flmm_default_options(),
		)
	);
}
add_action( 'admin_init', 'flmm_register_settings' );

/**
 * Agrega la página al menú Apariencia.
 */
function flmm_add_settings_page() {
	add_theme_page( 'FLMM Studio', 'FLMM Studio', 'edit_theme_options', 'flmm-studio', 'flmm_render_settings_page' );
}
add_action( 'admin_menu', 'flmm_add_settings_page' );

/**
 * Pinta la página de ajustes.
 */
function flmm_render_settings_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$o      = wp_parse_args( (array) get_option( 'flmm_options', array() ), flmm_default_options() );
	$plugin = flmm_seo_plugin();
	?>
	<div class="wrap">
		<h1>FLMM Studio</h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'flmm_options' ); ?>
			<h2>Google Tag Manager</h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="flmm-gtm-id">ID del contenedor</label></th>
					<td><input id="flmm-gtm-id" class="regular-text" name="flmm_options[gtm_id]" value="<?php echo esc_attr( $o['gtm_id'] ); ?>" placeholder="GTM-XXXXXXX"></td>
				</tr>
				<tr>
					<th scope="row"><label for="flmm-gtm-mode">Carga</label></th>
					<td>
						<select id="flmm-gtm-mode" name="flmm_options[gtm_mode]">
							<option value="auto" <?php selected( $o['gtm_mode'], 'auto' ); ?>>Automática: no cargar si GTM4WP está activo</option>
							<option value="on" <?php selected( $o['gtm_mode'], 'on' ); ?>>Siempre desde el theme</option>
							<option value="off" <?php selected( $o['gtm_mode'], 'off' ); ?>>Desactivada</option>
						</select>
						<p class="description">Estado actual: <?php echo flmm_gtm_should_load() ? 'el theme carga GTM.' : 'el theme no carga GTM.'; ?></p>
					</td>
				</tr>
			</table>
			<h2>Datos estructurados</h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="flmm-schema-mode">JSON-LD del theme</label></th>
					<td>
						<select id="flmm-schema-mode" name="flmm_options[schema_mode]">
							<option value="auto" <?php selected( $o['schema_mode'], 'auto' ); ?>>Automático: si hay plugin SEO, solo FAQPage y Service</option>
							<option value="all" <?php selected( $o['schema_mode'], 'all' ); ?>>Completo desde el theme</option>
							<option value="off" <?php selected( $o['schema_mode'], 'off' ); ?>>Desactivado</option>
						</select>
						<p class="description">Plugin SEO detectado: <?php echo $plugin ? esc_html( $plugin ) : 'ninguno'; ?>. Tipos que emite el theme ahora: <?php echo esc_html( implode( ', ', flmm_schema_types() ) ?: 'ninguno' ); ?>.</p>
					</td>
				</tr>
			</table>
			<h2>Contacto y redes</h2>
			<table class="form-table" role="presentation">
				<tr><th scope="row"><label for="flmm-email">Email</label></th><td><input id="flmm-email" class="regular-text" name="flmm_options[email]" value="<?php echo esc_attr( $o['email'] ); ?>"></td></tr>
				<tr><th scope="row"><label for="flmm-telegram">Telegram</label></th><td><input id="flmm-telegram" class="regular-text" name="flmm_options[telegram]" value="<?php echo esc_attr( $o['telegram'] ); ?>"></td></tr>
				<tr><th scope="row"><label for="flmm-agent">Marky Digital (Agent)</label></th><td><input id="flmm-agent" class="regular-text" name="flmm_options[agent_url]" value="<?php echo esc_attr( $o['agent_url'] ); ?>" placeholder="https://t.me/..."><p class="description">Enlace del agente. Se muestra en la columna Contacto del footer cuando tiene valor.</p></td></tr>
				<tr><th scope="row"><label for="flmm-linkedin">LinkedIn</label></th><td><input id="flmm-linkedin" class="regular-text" name="flmm_options[linkedin]" value="<?php echo esc_attr( $o['linkedin'] ); ?>"></td></tr>
				<tr><th scope="row"><label for="flmm-instagram">Instagram</label></th><td><input id="flmm-instagram" class="regular-text" name="flmm_options[instagram]" value="<?php echo esc_attr( $o['instagram'] ); ?>"></td></tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
