<?php
/**
 * FLMM Studio: arranque del theme.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

define( 'FLMM_VERSION', '1.0.4' );
define( 'FLMM_DIR', get_template_directory() );
define( 'FLMM_URI', get_template_directory_uri() );

require_once FLMM_DIR . '/inc/i18n.php';
require_once FLMM_DIR . '/inc/settings.php';
require_once FLMM_DIR . '/inc/setup.php';
require_once FLMM_DIR . '/inc/template-tags.php';
require_once FLMM_DIR . '/inc/blocks.php';
require_once FLMM_DIR . '/inc/patterns.php';
require_once FLMM_DIR . '/inc/user-fields.php';
require_once FLMM_DIR . '/inc/gtm.php';
require_once FLMM_DIR . '/inc/schema.php';
require_once FLMM_DIR . '/inc/seo.php';

if ( is_admin() ) {
	require_once FLMM_DIR . '/inc/migrate/migrate.php';
}
