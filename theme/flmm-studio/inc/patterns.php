<?php
/**
 * Patrones editables del theme.
 *
 * Cada sección se arma con una función que recibe el idioma y los datos. Así los mismos
 * constructores sirven para los patrones del editor (con contenido de muestra en EN y ES)
 * y para el cargador de contenido (tools/build-content.php) con los datos reales de src/.
 * El resultado es marcado de bloques core, editable en el editor.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Utilidades de marcado de bloques
 * ---------------------------------------------------------------------- */

/**
 * Texto de un par EN/ES, listo para HTML (escapa "&" sueltos y filtra etiquetas).
 *
 * @param array|string $pair Par array( 'en' => , 'es' => ) o texto.
 * @param string       $lang Idioma.
 * @return string
 */
function flmm_tx( $pair, $lang ) {
	$text = is_array( $pair ) ? ( isset( $pair[ $lang ] ) ? $pair[ $lang ] : reset( $pair ) ) : (string) $pair;
	$text = preg_replace( '/&(?![a-zA-Z]+;|#[0-9]+;|#x[0-9a-fA-F]+;)/', '&amp;', $text );
	return wp_kses( $text, array( 'strong' => array(), 'em' => array(), 'a' => array( 'href' => array(), 'target' => array(), 'rel' => array() ), 'br' => array(), 'mark' => array( 'class' => array(), 'style' => array() ), 'span' => array( 'class' => array() ) ) );
}

/**
 * Comentario de apertura de un bloque.
 *
 * @param string $name  Nombre sin "core/".
 * @param array  $attrs Atributos.
 * @param bool   $void  Bloque sin contenido.
 * @return string
 */
function flmm_bo( $name, $attrs = array(), $void = false ) {
	$json = $attrs ? ' ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) : '';
	return '<!-- wp:' . $name . $json . ( $void ? ' /-->' : ' -->' );
}

/**
 * Clases de un bloque a partir de sus atributos.
 *
 * @param string $base  Clase base, por ejemplo 'wp-block-group'.
 * @param array  $attrs Atributos.
 * @return string
 */
function flmm_bclass( $base, $attrs ) {
	$classes = array_filter( array( $base, isset( $attrs['align'] ) ? 'align' . $attrs['align'] : '', isset( $attrs['className'] ) ? $attrs['className'] : '' ) );
	return implode( ' ', $classes );
}

/**
 * Bloque Grupo.
 *
 * @param string $inner Contenido.
 * @param array  $attrs Atributos (className, align, tagName, anchor, layout).
 * @return string
 */
function flmm_group( $inner, $attrs = array() ) {
	$tag    = isset( $attrs['tagName'] ) ? $attrs['tagName'] : 'div';
	$anchor = isset( $attrs['anchor'] ) ? ' id="' . esc_attr( $attrs['anchor'] ) . '"' : '';
	return flmm_bo( 'group', $attrs ) . '<' . $tag . $anchor . ' class="' . esc_attr( flmm_bclass( 'wp-block-group', $attrs ) ) . '">' . $inner . '</' . $tag . '>' . '<!-- /wp:group -->';
}

/**
 * Bloque Párrafo.
 *
 * @param string $html  Contenido HTML.
 * @param string $class Clase.
 * @return string
 */
function flmm_p( $html, $class = '' ) {
	$attrs = $class ? array( 'className' => $class ) : array();
	return flmm_bo( 'paragraph', $attrs ) . '<p' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '>' . $html . '</p><!-- /wp:paragraph -->';
}

/**
 * Bloque Encabezado.
 *
 * @param int    $level Nivel.
 * @param string $html  Contenido HTML.
 * @param string $class Clase.
 * @param string $anchor Ancla.
 * @return string
 */
function flmm_h( $level, $html, $class = '', $anchor = '' ) {
	$attrs = array();
	if ( 2 !== $level ) {
		$attrs['level'] = $level;
	}
	if ( $anchor ) {
		$attrs['anchor'] = $anchor;
	}
	if ( $class ) {
		$attrs['className'] = $class;
	}
	return flmm_bo( 'heading', $attrs ) . '<h' . $level . ( $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '' ) . ' class="' . esc_attr( trim( 'wp-block-heading ' . $class ) ) . '">' . $html . '</h' . $level . '><!-- /wp:heading -->';
}

/**
 * Bloque Botones.
 *
 * @param array  $buttons Lista de array( texto, url, estilo ('fill'|'ghost'), externo ).
 * @param string $class   Clase del contenedor.
 * @return string
 */
function flmm_buttons( $buttons, $class = 'flmm-ctas' ) {
	$inner = '';
	foreach ( $buttons as $b ) {
		$style  = isset( $b[2] ) && 'ghost' === $b[2] ? 'is-style-ghost' : '';
		$attrs  = $style ? array( 'className' => $style ) : array();
		$target = ! empty( $b[3] ) ? ' target="_blank" rel="noreferrer noopener"' : '';
		if ( $target ) {
			$attrs['linkTarget'] = '_blank';
			$attrs['rel']        = 'noreferrer noopener';
		}
		$inner .= flmm_bo( 'button', $attrs ) . '<div class="' . esc_attr( trim( 'wp-block-button ' . $style ) ) . '"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $b[1] ) . '"' . $target . '>' . $b[0] . '</a></div><!-- /wp:button -->';
	}
	return flmm_bo( 'buttons', array( 'className' => $class ) ) . '<div class="wp-block-buttons ' . esc_attr( $class ) . '">' . $inner . '</div><!-- /wp:buttons -->';
}

/**
 * Grupo de tags (etiquetas en píldora).
 *
 * @param array  $tags Lista de pares o textos.
 * @param string $lang Idioma.
 * @return string
 */
function flmm_tags( $tags, $lang ) {
	if ( ! $tags ) {
		return '';
	}
	$inner = '';
	foreach ( $tags as $tag ) {
		$inner .= flmm_p( flmm_tx( $tag, $lang ), 'is-style-tag' );
	}
	return flmm_group(
		$inner,
		array(
			'className' => 'flmm-tags',
			'layout'    => array( 'type' => 'flex', 'flexWrap' => 'wrap' ),
		)
	);
}

/**
 * Cabecera de sección: etiqueta, H2 y texto opcional.
 *
 * @param string $label Etiqueta.
 * @param string $title Título H2.
 * @param string $text  Texto a la derecha.
 * @param string $anchor Ancla del H2.
 * @return string
 */
function flmm_sec_head( $label, $title, $text = '', $anchor = '' ) {
	$left = flmm_group( flmm_p( $label, 'is-style-label' ) . flmm_h( 2, $title, '', $anchor ), array( 'className' => 'flmm-sec-head__main', 'layout' => array( 'type' => 'default' ) ) );
	$right = $text ? flmm_p( $text, 'flmm-sec-head__text' ) : '';
	return flmm_group( $left . $right, array( 'className' => 'flmm-sec-head' . ( $text ? '' : ' is-single' ), 'layout' => array( 'type' => 'default' ) ) );
}

/**
 * Sección a ancho completo con contenido limitado al ancho del sitio.
 *
 * @param string $inner  Contenido.
 * @param string $class  Clases extra ('is-surface' para fondo gris).
 * @param string $anchor Ancla.
 * @return string
 */
function flmm_section( $inner, $class = '', $anchor = '' ) {
	$attrs = array(
		'tagName'   => 'section',
		'align'     => 'full',
		'className' => trim( 'flmm-section ' . $class ),
		'layout'    => array( 'type' => 'constrained' ),
	);
	if ( $anchor ) {
		$attrs = array( 'tagName' => 'section', 'anchor' => $anchor ) + $attrs;
	}
	return flmm_group( $inner, $attrs );
}

/**
 * Datos de muestra de los patrones.
 *
 * @param string $file 'home' o 'service-sample'.
 * @return array
 */
function flmm_pattern_data( $file ) {
	static $cache = array();
	if ( ! isset( $cache[ $file ] ) ) {
		$path           = FLMM_DIR . '/inc/pattern-data/' . $file . '.json';
		$cache[ $file ] = file_exists( $path ) ? json_decode( (string) file_get_contents( $path ), true ) : array(); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}
	return $cache[ $file ];
}

/**
 * URL de un servicio en el idioma dado.
 *
 * @param string $slug Slug en inglés.
 * @param string $lang Idioma.
 * @param array  $urls Mapa opcional slug => array( 'en' => , 'es' => ).
 * @return string
 */
function flmm_service_url( $slug, $lang, $urls = array() ) {
	if ( isset( $urls[ $slug ][ $lang ] ) ) {
		return $urls[ $slug ][ $lang ];
	}
	$page = get_page_by_path( $slug );
	if ( $page && function_exists( 'pll_get_post' ) ) {
		$translated = pll_get_post( $page->ID, $lang );
		if ( $translated ) {
			return get_permalink( $translated );
		}
	}
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

/* -------------------------------------------------------------------------
 * Home
 * ---------------------------------------------------------------------- */

/**
 * Hero del home.
 *
 * @param string $lang Idioma.
 * @param array  $d    Datos (home.hero).
 * @return string
 */
function flmm_pattern_hero( $lang, $d = null ) {
	$d     = $d ? $d : flmm_pattern_data( 'home' )['hero'];
	$title = flmm_tx( $d['title'], $lang ) . ' <mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-muted-color">' . flmm_tx( $d['soft'], $lang ) . '</mark>';
	$inner = flmm_h( 1, $title, 'flmm-hero__title flmm-rv' )
		. ( ! empty( $d['lead'] ) ? flmm_p( flmm_tx( $d['lead'], $lang ), 'flmm-hero__lead flmm-rv' ) : '' )
		. flmm_buttons(
			array(
				array( flmm_tx( $d['cta1'], $lang ) . ' <span class="flmm-arr">→</span>', '#contact' ),
				array( flmm_tx( $d['cta2'], $lang ), '#services', 'ghost' ),
			),
			'flmm-ctas flmm-rv'
		);
	$class = 'flmm-hero';
	// Con imagen: texto a la izquierda e ilustración a la derecha.
	if ( ! empty( $d['image']['id'] ) ) {
		$img   = $d['image'];
		$inner = flmm_group( $inner, array( 'className' => 'flmm-hero__text', 'layout' => array( 'type' => 'default' ) ) )
			. flmm_bo( 'image', array( 'id' => (int) $img['id'], 'sizeSlug' => 'full', 'linkDestination' => 'none', 'className' => 'flmm-hero__art flmm-rv' ) )
			. '<figure class="wp-block-image size-full flmm-hero__art flmm-rv"><img src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $img['alt'] ) . '" class="wp-image-' . (int) $img['id'] . '" fetchpriority="high"/></figure><!-- /wp:image -->';
		$inner = flmm_group( $inner, array( 'className' => 'flmm-hero__grid', 'layout' => array( 'type' => 'default' ) ) );
		$class .= ' flmm-hero--art';
	}
	return flmm_group(
		$inner,
		array(
			'align'     => 'full',
			'className' => $class,
			'layout'    => array( 'type' => 'constrained' ),
		)
	);
}

/**
 * Lista numerada de servicios con tags.
 *
 * @param string $lang Idioma.
 * @param array  $d    Datos (home.services).
 * @param array  $urls Mapa de URLs de servicios.
 * @return string
 */
function flmm_pattern_services( $lang, $d = null, $urls = array() ) {
	$d    = $d ? $d : flmm_pattern_data( 'home' )['services'];
	$rows = '';
	foreach ( $d['items'] as $i => $item ) {
		$link  = '<a href="' . esc_url( flmm_service_url( $item['slug'], $lang, $urls ) ) . '">' . flmm_tx( $item['name'], $lang ) . '</a>';
		$rows .= flmm_group(
			flmm_p( sprintf( '%02d', $i + 1 ), 'flmm-n' )
			. flmm_h( 3, $link )
			. flmm_p( flmm_tx( $item['text'], $lang ), 'flmm-svc__text' )
			. flmm_tags( $item['tags'], $lang ),
			array( 'className' => 'flmm-svc__row flmm-rv', 'layout' => array( 'type' => 'default' ) )
		);
	}
	return flmm_section(
		flmm_sec_head( flmm_tx( $d['label'], $lang ), flmm_tx( $d['title'], $lang ), flmm_tx( $d['text'], $lang ) )
		. flmm_group( $rows, array( 'className' => 'flmm-svc', 'layout' => array( 'type' => 'default' ) ) ),
		'',
		'services'
	);
}

/**
 * Plataformas a medida (casos).
 *
 * @param string $lang Idioma.
 * @param array  $d    Datos (home.platforms).
 * @return string
 */
function flmm_pattern_platforms( $lang, $d = null ) {
	$d     = $d ? $d : flmm_pattern_data( 'home' )['platforms'];
	$cards = '';
	foreach ( $d['items'] as $item ) {
		$external = 0 === strpos( $item['url'], 'http' );
		$arrow    = $external ? '↗' : '→';
		$link     = '<a href="' . esc_url( $item['url'] ) . '"' . ( $external ? ' target="_blank" rel="noreferrer noopener"' : '' ) . '>' . flmm_tx( $item['link'], $lang ) . ' <span class="flmm-arr">' . $arrow . '</span></a>';
		$cards   .= flmm_group(
			flmm_p( flmm_tx( $item['n'], $lang ), 'flmm-n' )
			. flmm_h( 3, flmm_tx( $item['name'], $lang ) )
			. flmm_p( flmm_tx( $item['text'], $lang ) )
			. flmm_tags( $item['tags'], $lang )
			. flmm_p( $link, 'flmm-plat__go' ),
			array( 'className' => 'flmm-plat flmm-rv' . ( ! empty( $item['dark'] ) ? ' is-dark' : '' ), 'layout' => array( 'type' => 'default' ) )
		);
	}
	return flmm_section(
		flmm_sec_head( flmm_tx( $d['label'], $lang ), flmm_tx( $d['title'], $lang ), flmm_tx( $d['text'], $lang ) )
		. flmm_group( $cards, array( 'className' => 'flmm-plats', 'layout' => array( 'type' => 'default' ) ) ),
		'is-surface',
		'platforms'
	);
}

/**
 * Método en tres pasos.
 *
 * @param string $lang   Idioma.
 * @param array  $d      Datos (home.method).
 * @param string $title  Título alternativo (servicios).
 * @param string $anchor Ancla.
 * @param string $class  Clase extra de la sección.
 * @return string
 */
function flmm_pattern_method( $lang, $d = null, $title = '', $anchor = 'method', $class = '' ) {
	$d     = $d ? $d : flmm_pattern_data( 'home' )['method'];
	$steps = '';
	foreach ( $d['steps'] as $i => $step ) {
		$steps .= flmm_group(
			flmm_p( sprintf( '%02d', $i + 1 ), 'flmm-n' ) . flmm_h( 3, flmm_tx( $step['title'], $lang ) ) . flmm_p( flmm_tx( $step['text'], $lang ) ),
			array( 'className' => 'flmm-step', 'layout' => array( 'type' => 'default' ) )
		);
	}
	return flmm_section(
		flmm_sec_head( flmm_tx( $d['label'], $lang ), $title ? $title : flmm_tx( $d['title'], $lang ), flmm_tx( $d['text'], $lang ) )
		. flmm_group( $steps, array( 'className' => 'flmm-steps flmm-rv', 'layout' => array( 'type' => 'default' ) ) ),
		$class,
		$anchor
	);
}

/**
 * Equipo en carrusel. Las fotos y los LinkedIn quedan pendientes: se muestran iniciales.
 *
 * @param string $lang Idioma.
 * @param array  $d    Datos (home.team).
 * @return string
 */
function flmm_pattern_team( $lang, $d = null ) {
	$d       = $d ? $d : flmm_pattern_data( 'home' )['team'];
	$members = '';
	foreach ( $d['members'] as $m ) {
		$members .= flmm_group(
			flmm_p( esc_html( flmm_initials( $m['name'] ) ), 'flmm-avatar' )
			. flmm_h( 3, esc_html( $m['name'] ) )
			. flmm_p( flmm_tx( $m['role'], $lang ), 'flmm-member__role' )
			. flmm_tags( $m['tags'], $lang ),
			array( 'tagName' => 'article', 'className' => 'flmm-member', 'layout' => array( 'type' => 'default' ) )
		);
	}
	return flmm_section(
		flmm_sec_head( flmm_tx( $d['label'], $lang ), flmm_tx( $d['title'], $lang ), flmm_tx( $d['text'], $lang ) )
		. flmm_group( $members, array( 'className' => 'flmm-team', 'layout' => array( 'type' => 'default' ) ) ),
		'is-surface',
		'team'
	);
}

/**
 * Trayectoria (cifras).
 *
 * @param string $lang Idioma.
 * @param array  $d    Datos (home.results).
 * @return string
 */
function flmm_pattern_results( $lang, $d = null ) {
	$d     = $d ? $d : flmm_pattern_data( 'home' )['results'];
	$stats = '';
	foreach ( $d['stats'] as $stat ) {
		$stats .= flmm_group(
			flmm_p( flmm_tx( $stat['value'], $lang ), 'flmm-stat__value' ) . flmm_p( flmm_tx( $stat['text'], $lang ), 'flmm-stat__text' ),
			array( 'className' => 'flmm-stat', 'layout' => array( 'type' => 'default' ) )
		);
	}
	return flmm_section(
		flmm_sec_head( flmm_tx( $d['label'], $lang ), flmm_tx( $d['title'], $lang ), flmm_tx( $d['text'], $lang ) )
		. flmm_group( $stats, array( 'className' => 'flmm-stats flmm-rv', 'layout' => array( 'type' => 'default' ) ) ),
		'',
		'results'
	);
}

/**
 * Bloque de contacto (dinámico: formulario + mail + Telegram).
 */
function flmm_pattern_contact() {
	return flmm_bo( 'flmm/contact', array(), true );
}

/* -------------------------------------------------------------------------
 * Servicio
 * ---------------------------------------------------------------------- */

/**
 * Hero del servicio: H1, entrada (40-60 palabras), fecha de actualización y botones.
 *
 * @param string $lang  Idioma.
 * @param array  $s     Datos del servicio.
 * @param array  $image Imagen opcional array( 'id' => , 'url' => , 'alt' => ).
 * @return string
 */
function flmm_pattern_service_hero( $lang, $s = null, $image = null ) {
	$s     = $s ? $s : flmm_pattern_data( 'service-sample' );
	$inner = flmm_h( 1, flmm_tx( $s['h1'], $lang ), 'flmm-sv-hero__title flmm-rv' )
		. flmm_p( flmm_tx( $s['lead'], $lang ), 'flmm-sv-hero__lead flmm-rv' )
		. flmm_bo( 'flmm/updated', array(), true )
		. flmm_buttons(
			array(
				array( ( 'es' === $lang ? 'Escríbenos' : 'Get in touch' ) . ' <span class="flmm-arr">→</span>', '#contact' ),
				array( 'es' === $lang ? 'Preguntas frecuentes' : 'FAQ', '#faq', 'ghost' ),
			),
			'flmm-ctas flmm-rv'
		);
	$hero = flmm_group( $inner, array( 'className' => 'flmm-sv-hero', 'layout' => array( 'type' => 'default' ) ) );
	if ( null === $image ) {
		$image = array(
			'id'  => 0,
			'url' => FLMM_URI . '/assets/images/pattern-sample.webp',
			'alt' => 'es' === $lang ? 'Estrategia de marketing: piezas de ajedrez y un camino rosa de crecimiento hacia un objetivo claro' : 'Marketing strategy: chess pieces and a pink growth path toward a clear goal',
		);
	}
	if ( $image ) {
		$attrs = array( 'sizeSlug' => 'full', 'linkDestination' => 'none', 'className' => 'flmm-sv-img flmm-rv' );
		$idcls = '';
		if ( ! empty( $image['id'] ) ) {
			$attrs = array( 'id' => (int) $image['id'] ) + $attrs;
			$idcls = ' class="wp-image-' . (int) $image['id'] . '"';
		}
		$hero .= flmm_bo( 'image', $attrs ) . '<figure class="wp-block-image size-full flmm-sv-img flmm-rv"><img src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( $image['alt'] ) . '"' . $idcls . '/></figure><!-- /wp:image -->';
	}
	return flmm_group( $hero, array( 'align' => 'full', 'className' => 'flmm-sv-top', 'layout' => array( 'type' => 'constrained' ) ) );
}

/**
 * Qué incluye.
 *
 * @param string $lang Idioma.
 * @param array  $s    Datos del servicio.
 * @return string
 */
function flmm_pattern_service_included( $lang, $s = null ) {
	$s     = $s ? $s : flmm_pattern_data( 'service-sample' );
	$cards = '';
	foreach ( $s['inc'] as $i => $item ) {
		$cards .= flmm_group(
			flmm_p( sprintf( '%02d', $i + 1 ), 'flmm-n' ) . flmm_h( 3, flmm_tx( $item['title'], $lang ) ) . flmm_p( flmm_tx( $item['text'], $lang ) ),
			array( 'className' => 'flmm-inc__item flmm-rv', 'layout' => array( 'type' => 'default' ) )
		);
	}
	return flmm_section(
		flmm_sec_head( 'es' === $lang ? 'Qué incluye' : 'What’s included', flmm_tx( $s['headings']['inc'], $lang ) )
		. flmm_group( $cards, array( 'className' => 'flmm-inc', 'layout' => array( 'type' => 'default' ) ) )
	);
}

/**
 * Comparativa en tabla.
 *
 * @param string $lang Idioma.
 * @param array  $s    Datos del servicio.
 * @return string
 */
function flmm_pattern_service_comparison( $lang, $s = null ) {
	$s    = $s ? $s : flmm_pattern_data( 'service-sample' );
	$c    = $s['cmp'];
	$rows = '';
	foreach ( $c['rows'] as $row ) {
		$rows .= '<tr><td>' . flmm_tx( $row['label'], $lang ) . '</td><td>' . flmm_tx( $row['c1'], $lang ) . '</td><td>' . flmm_tx( $row['c2'], $lang ) . '</td>'
			. ( isset( $row['c3'] ) ? '<td>' . flmm_tx( $row['c3'], $lang ) . '</td>' : '' ) . '</tr>';
	}
	$col3 = isset( $c['col3'] ) ? '<th>' . flmm_tx( $c['col3'], $lang ) . '</th>' : '';
	$table = flmm_bo( 'table', array( 'hasFixedLayout' => false, 'className' => 'is-style-comparison flmm-rv' ) )
		. '<figure class="wp-block-table is-style-comparison flmm-rv"><table><thead><tr><th></th><th>' . flmm_tx( $c['col1'], $lang ) . '</th><th>' . flmm_tx( $c['col2'], $lang ) . '</th>' . $col3 . '</tr></thead><tbody>' . $rows . '</tbody></table></figure><!-- /wp:table -->';
	return flmm_section( flmm_sec_head( 'es' === $lang ? 'Comparativa' : 'Comparison', flmm_tx( $c['title'], $lang ) ) . $table, 'is-surface' );
}

/**
 * Conceptos clave (definiciones).
 *
 * @param string $lang Idioma.
 * @param array  $s    Datos del servicio.
 * @return string
 */
function flmm_pattern_service_concepts( $lang, $s = null ) {
	$s    = $s ? $s : flmm_pattern_data( 'service-sample' );
	$defs = '';
	foreach ( $s['defs'] as $def ) {
		$defs .= flmm_group(
			flmm_h( 3, flmm_tx( $def['term'], $lang ) ) . flmm_p( flmm_tx( $def['def'], $lang ) ),
			array( 'className' => 'flmm-def flmm-rv', 'layout' => array( 'type' => 'default' ) )
		);
	}
	$ref = '';
	if ( ! empty( $s['ref'] ) ) {
		$ref = flmm_p( ( 'es' === $lang ? 'Referencia:' : 'Reference:' ) . ' <a href="' . esc_url( $s['ref']['url'] ) . '" target="_blank" rel="noreferrer noopener">' . flmm_tx( $s['ref']['label'], $lang ) . '</a>', 'flmm-ref' );
	}
	return flmm_section(
		flmm_sec_head( 'es' === $lang ? 'Conceptos clave' : 'Key concepts', flmm_tx( $s['headings']['defs'], $lang ) )
		. flmm_group( $defs, array( 'className' => 'flmm-defs', 'layout' => array( 'type' => 'default' ) ) )
		. $ref
	);
}

/**
 * Guía del servicio: texto de profundidad con subtítulos (opcional).
 *
 * @param string $lang Idioma.
 * @param array  $s    Datos del servicio.
 * @return string
 */
function flmm_pattern_service_guide( $lang, $s = null ) {
	$s = $s ? $s : flmm_pattern_data( 'service-sample' );
	if ( empty( $s['guide'] ) ) {
		return '';
	}
	$g    = $s['guide'];
	$body = flmm_p( flmm_tx( $g['intro'], $lang ) );
	foreach ( $g['items'] as $item ) {
		$body .= flmm_h( 3, flmm_tx( $item['h'], $lang ) ) . flmm_p( flmm_tx( $item['p'], $lang ) );
	}
	return flmm_section(
		flmm_sec_head( flmm_tx( $g['label'], $lang ), flmm_tx( $g['h2'], $lang ) )
		. flmm_group( $body, array( 'className' => 'flmm-guide flmm-rv', 'layout' => array( 'type' => 'default' ) ) )
	);
}

/**
 * Método del servicio (mismos tres pasos del home, con título propio).
 *
 * @param string $lang Idioma.
 * @param array  $s    Datos del servicio.
 * @return string
 */
function flmm_pattern_service_method( $lang, $s = null ) {
	$s = $s ? $s : flmm_pattern_data( 'service-sample' );
	return flmm_pattern_method( $lang, null, flmm_tx( $s['headings']['method'], $lang ), '', 'is-surface' );
}

/**
 * Para quién.
 *
 * @param string $lang Idioma.
 * @param array  $s    Datos del servicio.
 * @return string
 */
function flmm_pattern_service_audience( $lang, $s = null ) {
	$s     = $s ? $s : flmm_pattern_data( 'service-sample' );
	$items = '';
	foreach ( $s['who'] as $who ) {
		$items .= flmm_bo( 'list-item' ) . '<li>' . flmm_tx( $who, $lang ) . '</li><!-- /wp:list-item -->';
	}
	$list = flmm_bo( 'list', array( 'className' => 'is-style-dots flmm-who' ) ) . '<ul class="wp-block-list is-style-dots flmm-who">' . $items . '</ul><!-- /wp:list -->';
	return flmm_section( flmm_sec_head( 'es' === $lang ? 'Para quién' : 'Who it’s for', flmm_tx( $s['headings']['who'], $lang ) ) . $list );
}

/**
 * Por qué nosotros: testimonio y especialistas.
 *
 * @param string $lang        Idioma.
 * @param array  $s           Datos del servicio.
 * @param array  $testimonial Testimonio array( quote, name, role ) o null.
 * @return string
 */
function flmm_pattern_service_why( $lang, $s = null, $testimonial = null ) {
	$s           = $s ? $s : flmm_pattern_data( 'service-sample' );
	$testimonial = $testimonial ? $testimonial : ( isset( $s['testimonial'] ) ? $s['testimonial'] : null );
	$quote       = '';
	if ( $testimonial ) {
		$quote = flmm_bo( 'quote', array( 'className' => 'flmm-quote flmm-rv' ) ) . '<blockquote class="wp-block-quote flmm-quote flmm-rv">'
			. flmm_p( flmm_tx( $testimonial['quote'], $lang ) )
			. '<cite><strong>' . esc_html( $testimonial['name'] ) . '</strong><br>' . flmm_tx( $testimonial['role'], $lang ) . '</cite></blockquote><!-- /wp:quote -->';
	}
	$experts = '';
	foreach ( $s['team'] as $name ) {
		$experts .= flmm_group(
			flmm_p( esc_html( flmm_initials( $name ) ), 'flmm-avatar flmm-avatar--sm' ) . flmm_p( esc_html( $name ), 'flmm-expert__name' ),
			array( 'className' => 'flmm-expert', 'layout' => array( 'type' => 'flex', 'flexWrap' => 'nowrap' ) )
		);
	}
	$box = flmm_group(
		flmm_p( 'es' === $lang ? 'Especialistas en este servicio' : 'Specialists in this service', 'is-style-label' )
		. flmm_group( $experts, array( 'className' => 'flmm-experts__list', 'layout' => array( 'type' => 'default' ) ) )
		. flmm_p( '<a href="' . esc_url( flmm_home_url( '#team' ) ) . '">' . ( 'es' === $lang ? 'Ver todo el equipo' : 'See the full team' ) . ' →</a>', 'flmm-experts__more' ),
		array( 'className' => 'flmm-experts flmm-rv', 'layout' => array( 'type' => 'default' ) )
	);
	return flmm_section(
		flmm_sec_head( 'es' === $lang ? 'Por qué nosotros' : 'Why us', flmm_tx( $s['headings']['why'], $lang ) )
		. flmm_group( $quote . $box, array( 'className' => 'flmm-proof' . ( $quote ? '' : ' no-quote' ), 'layout' => array( 'type' => 'default' ) ) ),
		'is-surface'
	);
}

/**
 * Preguntas frecuentes desplegables (bloques Detalles).
 *
 * @param string $lang Idioma.
 * @param array  $s    Datos del servicio.
 * @return string
 */
function flmm_pattern_service_faq( $lang, $s = null ) {
	$s     = $s ? $s : flmm_pattern_data( 'service-sample' );
	$items = '';
	foreach ( $s['faq'] as $i => $qa ) {
		$open   = 0 === $i;
		$items .= flmm_bo( 'details', $open ? array( 'showContent' => true ) : array() )
			. '<details class="wp-block-details"' . ( $open ? ' open' : '' ) . '><summary>' . flmm_tx( $qa['q'], $lang ) . '</summary>'
			. flmm_p( flmm_tx( $qa['a'], $lang ) )
			. '</details><!-- /wp:details -->';
	}
	return flmm_section(
		flmm_sec_head( 'FAQ', flmm_tx( $s['headings']['faq'], $lang ) )
		. flmm_group( $items, array( 'className' => 'flmm-faq', 'layout' => array( 'type' => 'default' ) ) ),
		'',
		'faq'
	);
}

/**
 * Servicios relacionados.
 *
 * @param string $lang  Idioma.
 * @param array  $items Lista de array( 'slug' => , 'name' => par ).
 * @param array  $urls  Mapa de URLs.
 * @return string
 */
function flmm_pattern_service_related( $lang, $items = null, $urls = array() ) {
	if ( null === $items ) {
		$home  = flmm_pattern_data( 'home' )['services']['items'];
		$items = array_slice( $home, 1, 3 );
	}
	$links = '';
	foreach ( $items as $item ) {
		$links .= flmm_p( '<a href="' . esc_url( flmm_service_url( $item['slug'], $lang, $urls ) ) . '">' . flmm_tx( $item['name'], $lang ) . ' <span class="flmm-arr">→</span></a>', 'flmm-rel__item flmm-rv' );
	}
	return flmm_section(
		flmm_sec_head( 'es' === $lang ? 'Relacionados' : 'Related', 'es' === $lang ? 'Otros servicios' : 'Other services' )
		. flmm_group( $links, array( 'className' => 'flmm-rel', 'layout' => array( 'type' => 'default' ) ) ),
		'is-surface'
	);
}

/* -------------------------------------------------------------------------
 * Páginas y artículo
 * ---------------------------------------------------------------------- */

/**
 * TL;DR para artículos.
 *
 * @param string $lang Idioma.
 * @return string
 */
function flmm_pattern_tldr( $lang ) {
	return flmm_group(
		flmm_p( '<strong>TL;DR</strong>' ) . flmm_p( 'es' === $lang ? 'Resume aquí la respuesta principal del artículo en dos o tres frases.' : 'Summarize the main answer of the article in two or three sentences.' ),
		array( 'className' => 'is-style-tldr', 'layout' => array( 'type' => 'default' ) )
	);
}

/**
 * Puntos clave para artículos.
 *
 * @param string $lang Idioma.
 * @return string
 */
function flmm_pattern_takeaways( $lang ) {
	$items = '';
	foreach ( array( 1, 2, 3 ) as $n ) {
		$items .= flmm_bo( 'list-item' ) . '<li>' . ( 'es' === $lang ? 'Punto clave ' : 'Key takeaway ' ) . $n . '</li><!-- /wp:list-item -->';
	}
	return flmm_group(
		flmm_h( 2, 'es' === $lang ? 'Puntos clave' : 'Key takeaways' ) . flmm_bo( 'list' ) . '<ul class="wp-block-list">' . $items . '</ul><!-- /wp:list -->',
		array( 'className' => 'is-style-takeaways', 'layout' => array( 'type' => 'default' ) )
	);
}

/* -------------------------------------------------------------------------
 * Registro
 * ---------------------------------------------------------------------- */

/**
 * Lista de patrones: slug => array( título, categoría, constructor ).
 */
function flmm_pattern_list() {
	return array(
		'hero'                => array( 'Home: hero', 'flmm-home', 'flmm_pattern_hero' ),
		'services'            => array( 'Home: servicios', 'flmm-home', 'flmm_pattern_services' ),
		'platforms'           => array( 'Home: plataformas a medida', 'flmm-home', 'flmm_pattern_platforms' ),
		'method'              => array( 'Home: método', 'flmm-home', 'flmm_pattern_method' ),
		'team'                => array( 'Home: equipo (carrusel)', 'flmm-home', 'flmm_pattern_team' ),
		'results'             => array( 'Home: trayectoria', 'flmm-home', 'flmm_pattern_results' ),
		'service-hero'        => array( 'Servicio: hero', 'flmm-service', 'flmm_pattern_service_hero' ),
		'service-included'    => array( 'Servicio: qué incluye', 'flmm-service', 'flmm_pattern_service_included' ),
		'service-comparison'  => array( 'Servicio: comparativa', 'flmm-service', 'flmm_pattern_service_comparison' ),
		'service-concepts'    => array( 'Servicio: conceptos clave', 'flmm-service', 'flmm_pattern_service_concepts' ),
		'service-method'      => array( 'Servicio: método', 'flmm-service', 'flmm_pattern_service_method' ),
		'service-audience'    => array( 'Servicio: para quién', 'flmm-service', 'flmm_pattern_service_audience' ),
		'service-why'         => array( 'Servicio: por qué nosotros', 'flmm-service', 'flmm_pattern_service_why' ),
		'service-faq'         => array( 'Servicio: preguntas frecuentes', 'flmm-service', 'flmm_pattern_service_faq' ),
		'service-related'     => array( 'Servicio: relacionados', 'flmm-service', 'flmm_pattern_service_related' ),
		'article-tldr'        => array( 'Artículo: TL;DR', 'flmm-article', 'flmm_pattern_tldr' ),
		'article-takeaways'   => array( 'Artículo: puntos clave', 'flmm-article', 'flmm_pattern_takeaways' ),
	);
}

/**
 * Página de servicio completa.
 *
 * @param string $lang Idioma.
 * @return string
 */
function flmm_pattern_service_page( $lang ) {
	return flmm_pattern_service_hero( $lang )
		. flmm_pattern_service_included( $lang )
		. flmm_pattern_service_comparison( $lang )
		. flmm_pattern_service_concepts( $lang )
		. flmm_pattern_service_method( $lang )
		. flmm_pattern_service_audience( $lang )
		. flmm_pattern_service_why( $lang )
		. flmm_pattern_service_faq( $lang )
		. flmm_pattern_service_related( $lang );
}

/**
 * Home completo.
 *
 * @param string $lang Idioma.
 * @return string
 */
function flmm_pattern_home_page( $lang ) {
	return flmm_pattern_hero( $lang )
		. flmm_pattern_services( $lang )
		. flmm_pattern_platforms( $lang )
		. flmm_pattern_method( $lang )
		. flmm_pattern_team( $lang )
		. flmm_pattern_results( $lang );
}

/**
 * Registra los patrones en EN y ES.
 */
function flmm_register_patterns() {
	$names = array( 'en' => 'EN', 'es' => 'ES' );
	foreach ( $names as $lang => $label ) {
		foreach ( flmm_pattern_list() as $slug => $pattern ) {
			register_block_pattern(
				'flmm-studio/' . $slug . '-' . $lang,
				array(
					'title'      => $pattern[0] . ' (' . $label . ')',
					'categories' => array( $pattern[1] ),
					'content'    => call_user_func( $pattern[2], $lang ),
					'keywords'   => array( 'flmm', $lang ),
				)
			);
		}
		register_block_pattern(
			'flmm-studio/page-home-' . $lang,
			array(
				'title'      => 'Página: home completo (' . $label . ')',
				'categories' => array( 'flmm-page' ),
				'content'    => flmm_pattern_home_page( $lang ),
				'postTypes'  => array( 'page' ),
				'blockTypes' => array( 'core/post-content' ),
			)
		);
		register_block_pattern(
			'flmm-studio/page-service-' . $lang,
			array(
				'title'         => 'Página: servicio completo (' . $label . ')',
				'categories'    => array( 'flmm-page' ),
				'content'       => flmm_pattern_service_page( $lang ),
				'postTypes'     => array( 'page' ),
				'blockTypes'    => array( 'core/post-content' ),
				'templateTypes' => array( 'page-service' ),
			)
		);
	}
}
/**
 * Los patrones solo hacen falta en el escritorio y en la API REST (editor de bloques),
 * no en el frontend: se registran ahí para no sumar consultas a cada visita.
 */
function flmm_maybe_register_patterns() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;
	flmm_register_patterns();
}
add_action( 'rest_api_init', 'flmm_maybe_register_patterns' );
add_action( 'admin_init', 'flmm_maybe_register_patterns' );
