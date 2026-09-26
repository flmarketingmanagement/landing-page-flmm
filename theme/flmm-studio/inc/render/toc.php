<?php
/**
 * Índice lateral fijo. El script lo arma con los H2 del artículo.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;
?>
<nav class="flmm-toc" aria-label="<?php echo esc_attr( flmm__( 'Table of contents' ) ); ?>" data-flmm-toc hidden>
	<p class="flmm-label"><?php flmm_e( 'In this article' ); ?></p>
	<ol></ol>
</nav>
