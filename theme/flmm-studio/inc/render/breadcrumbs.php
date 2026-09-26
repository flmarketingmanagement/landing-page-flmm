<?php
/**
 * Migas de pan visibles (el schema BreadcrumbList lo genera el plugin SEO o inc/schema.php).
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

if ( is_home() ) {
	return;
}
$flmm_items = flmm_breadcrumb_items();
// En el artículo, el título ya es el H1: la ruta termina en la categoría.
if ( is_singular( 'post' ) && count( $flmm_items ) > 2 ) {
	array_pop( $flmm_items );
}
if ( count( $flmm_items ) < 2 ) {
	return;
}
?>
<nav class="flmm-crumbs" aria-label="<?php echo esc_attr( flmm__( 'Breadcrumb' ) ); ?>">
	<?php
	$flmm_last = count( $flmm_items ) - 1;
	foreach ( $flmm_items as $flmm_i => $flmm_item ) {
		if ( ( $flmm_i === $flmm_last && ! is_singular( 'post' ) ) || empty( $flmm_item['url'] ) ) {
			printf( '<span%s>%s</span>', $flmm_i === $flmm_last ? ' aria-current="page"' : '', esc_html( $flmm_item['name'] ) );
		} else {
			printf( '<a href="%s">%s</a>%s', esc_url( $flmm_item['url'] ), esc_html( $flmm_item['name'] ), $flmm_i === $flmm_last ? '' : '<span aria-hidden="true">/</span>' );
		}
	}
	?>
</nav>
