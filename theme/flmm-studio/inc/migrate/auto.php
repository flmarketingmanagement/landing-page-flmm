<?php
/**
 * Migración automática: cuando se despliega una versión nueva del theme, ejecuta todos los
 * pasos de la migración en segundo plano (WP-Cron), sin entrar al admin.
 *
 * Los pasos son idempotentes. Ojo: el contenido de las páginas que crea la migración se
 * vuelve a escribir desde el código en cada versión nueva; los cambios hechos a mano en esas
 * páginas se pierden. Para desactivarla: add_filter( 'flmm_auto_migrate', '__return_false' ).
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Programa la migración si la versión del theme cambió desde la última ejecución.
 */
function flmm_auto_migrate_maybe_schedule() {
	if ( ! apply_filters( 'flmm_auto_migrate', true ) || get_option( 'flmm_mig_version' ) === FLMM_VERSION ) {
		return;
	}
	if ( ! function_exists( 'PLL' ) || get_transient( 'flmm_mig_running' ) || wp_next_scheduled( 'flmm_auto_migrate' ) ) {
		return;
	}
	wp_schedule_single_event( time(), 'flmm_auto_migrate' );
}
add_action( 'init', 'flmm_auto_migrate_maybe_schedule', 99 );

/**
 * Ejecuta todos los pasos como administrador.
 */
function flmm_auto_migrate_run() {
	if ( get_option( 'flmm_mig_version' ) === FLMM_VERSION || get_transient( 'flmm_mig_running' ) ) {
		return;
	}
	set_transient( 'flmm_mig_running', 1, 15 * MINUTE_IN_SECONDS );
	@set_time_limit( 600 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

	// Mismo contexto que el botón del admin: usuario administrador y sin filtros kses.
	$admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'orderby' => 'ID', 'fields' => 'ID' ) );
	if ( $admins ) {
		wp_set_current_user( (int) $admins[0] );
	}
	kses_remove_filters();

	require_once FLMM_DIR . '/inc/migrate/migrate.php';
	$log    = flmm_mig_run( array_keys( flmm_mig_steps() ) );
	$errors = count( array_filter( $log, static function ( $line ) {
		return 'error' === $line[0];
	} ) );
	update_option( 'flmm_migration_last_log', $log, false );
	update_option( 'flmm_mig_auto_status', array( 'version' => FLMM_VERSION, 'time' => time(), 'errors' => $errors ), false );
	// Se marca como hecha aunque haya errores, para no repetirla en cada visita; el registro queda en el admin.
	update_option( 'flmm_mig_version', FLMM_VERSION, false );
	delete_transient( 'flmm_mig_running' );

	// Segunda limpieza de la caché de idiomas en una petición nueva.
	wp_schedule_single_event( time() + MINUTE_IN_SECONDS, 'flmm_auto_migrate_clean' );
}
add_action( 'flmm_auto_migrate', 'flmm_auto_migrate_run' );

/**
 * Limpieza posterior (caché de Polylang, reglas de URL y llms.txt).
 */
function flmm_auto_migrate_clean() {
	require_once FLMM_DIR . '/inc/migrate/migrate.php';
	flmm_mig_deferred_clean();
}
add_action( 'flmm_auto_migrate_clean', 'flmm_auto_migrate_clean' );

/**
 * Cuando se publica un artículo programado, se regeneran los artículos nuevos para activar
 * los enlaces internos que apuntaban a él.
 *
 * @param int $post_id Artículo publicado.
 */
function flmm_auto_refresh_new_posts( $post_id ) {
	if ( 'post' !== get_post_type( $post_id ) || ! function_exists( 'PLL' ) ) {
		return;
	}
	require_once FLMM_DIR . '/inc/migrate/migrate.php';
	$slugs = array();
	foreach ( flmm_mig_new_posts() as $p ) {
		$slugs[] = $p['en']['slug'];
		$slugs[] = $p['es']['slug'];
	}
	if ( ! in_array( get_post_field( 'post_name', $post_id ), $slugs, true ) ) {
		return;
	}
	remove_action( 'publish_future_post', 'flmm_auto_refresh_new_posts', 20 );
	kses_remove_filters();
	flmm_mig_step_newposts();
}
add_action( 'publish_future_post', 'flmm_auto_refresh_new_posts', 20 );
