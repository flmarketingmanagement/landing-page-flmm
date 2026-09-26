<?php
/**
 * Campos extra del perfil de autor: cargo y biografía en ES, LinkedIn y foto.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Campos editables: meta => etiqueta.
 */
function flmm_user_fields() {
	return array(
		'flmm_role'      => 'Cargo (EN)',
		'flmm_role_es'   => 'Cargo (ES)',
		'description_es' => 'Biografía (ES)',
		'flmm_linkedin'  => 'URL de LinkedIn',
		'flmm_photo'     => 'ID de la foto en Medios (opcional)',
	);
}

/**
 * Lee un campo del autor en el idioma actual. En ES usa la variante _es si tiene valor.
 *
 * @param int    $user_id Usuario.
 * @param string $field   'description' o 'flmm_role'.
 * @return string
 */
function flmm_author_field( $user_id, $field ) {
	if ( 'es' === flmm_lang() ) {
		$es = get_the_author_meta( $field . '_es', $user_id );
		if ( $es ) {
			return $es;
		}
	}
	return (string) get_the_author_meta( $field, $user_id );
}

/**
 * Avatar del autor: su foto si la tiene, o sus iniciales.
 *
 * @param int    $user_id Usuario.
 * @param string $size    '' o 'sm'.
 * @return string
 */
function flmm_author_avatar( $user_id, $size = '' ) {
	$class = 'flmm-avatar' . ( $size ? ' flmm-avatar--' . $size : '' );
	$photo = (int) get_the_author_meta( 'flmm_photo', $user_id );
	if ( $photo ) {
		$img = wp_get_attachment_image( $photo, 'thumbnail', false, array( 'alt' => '', 'loading' => 'lazy' ) );
		if ( $img ) {
			return '<span class="' . esc_attr( $class ) . '">' . $img . '</span>';
		}
	}
	return '<span class="' . esc_attr( $class ) . '" aria-hidden="true">' . esc_html( flmm_initials( get_the_author_meta( 'display_name', $user_id ) ) ) . '</span>';
}

/**
 * Pinta los campos en el perfil.
 *
 * @param WP_User $user Usuario.
 */
function flmm_user_fields_form( $user ) {
	?>
	<h2>FLMM Studio</h2>
	<table class="form-table" role="presentation">
		<?php foreach ( flmm_user_fields() as $key => $label ) : ?>
			<tr>
				<th><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
				<td>
					<?php if ( 'description_es' === $key ) : ?>
						<textarea id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" rows="5" class="large-text"><?php echo esc_textarea( get_the_author_meta( $key, $user->ID ) ); ?></textarea>
					<?php else : ?>
						<input id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( $key, $user->ID ) ); ?>">
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php
	wp_nonce_field( 'flmm_user_fields', 'flmm_user_fields_nonce' );
}
add_action( 'show_user_profile', 'flmm_user_fields_form' );
add_action( 'edit_user_profile', 'flmm_user_fields_form' );

/**
 * Guarda los campos.
 *
 * @param int $user_id Usuario.
 */
function flmm_user_fields_save( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}
	if ( ! isset( $_POST['flmm_user_fields_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['flmm_user_fields_nonce'] ), 'flmm_user_fields' ) ) {
		return;
	}
	foreach ( array_keys( flmm_user_fields() ) as $key ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$value = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( 'flmm_linkedin' === $key ) {
			$value = esc_url_raw( $value );
		} elseif ( 'flmm_photo' === $key ) {
			$value = absint( $value );
		} elseif ( 'description_es' === $key ) {
			$value = wp_kses_post( $value );
		} else {
			$value = sanitize_text_field( $value );
		}
		update_user_meta( $user_id, $key, $value );
	}
}
add_action( 'personal_options_update', 'flmm_user_fields_save' );
add_action( 'edit_user_profile_update', 'flmm_user_fields_save' );

/**
 * Expone los campos en la API REST (para cargarlos por API o desde el editor).
 */
function flmm_register_user_meta() {
	foreach ( array_keys( flmm_user_fields() ) as $key ) {
		register_meta(
			'user',
			$key,
			array(
				'type'          => 'flmm_photo' === $key ? 'integer' : 'string',
				'single'        => true,
				'show_in_rest'  => true,
				'auth_callback' => static function ( $allowed, $meta_key, $object_id ) {
					return current_user_can( 'edit_user', $object_id );
				},
			)
		);
	}
}
add_action( 'init', 'flmm_register_user_meta' );
