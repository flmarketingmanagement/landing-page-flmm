<?php
/**
 * Datos estructurados (JSON-LD).
 *
 * Para no duplicar schema, el modo automático detecta el plugin SEO: si existe (Rank Math,
 * Yoast, SEOPress o AIOSEO), el theme solo emite lo que el plugin no genera a partir del
 * contenido: FAQPage (bloques Detalles) y Service (páginas con la plantilla Servicio).
 * Se cambia en Apariencia > FLMM Studio o con el filtro 'flmm_schema_types'.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Nombre del plugin SEO activo, o cadena vacía.
 */
function flmm_seo_plugin() {
	if ( defined( 'RANK_MATH_VERSION' ) || class_exists( 'RankMath' ) ) {
		return 'Rank Math';
	}
	if ( defined( 'WPSEO_VERSION' ) ) {
		return 'Yoast SEO';
	}
	if ( defined( 'SEOPRESS_VERSION' ) ) {
		return 'SEOPress';
	}
	if ( defined( 'AIOSEO_VERSION' ) ) {
		return 'All in One SEO';
	}
	return '';
}

/**
 * Tipos de schema que emite el theme con la configuración actual.
 *
 * @return string[]
 */
function flmm_schema_types() {
	$all  = array( 'Organization', 'WebSite', 'BreadcrumbList', 'Service', 'FAQPage', 'BlogPosting', 'Person' );
	$mode = flmm_option( 'schema_mode' );
	if ( 'off' === $mode ) {
		$types = array();
	} elseif ( 'all' === $mode ) {
		$types = $all;
	} else {
		$types = flmm_seo_plugin() ? array( 'Service', 'FAQPage' ) : $all;
	}
	return (array) apply_filters( 'flmm_schema_types', $types, $mode );
}

/**
 * ID de la organización. Coincide con el que usa Rank Math para poder enlazarlos.
 */
function flmm_org_id() {
	return home_url( '/#organization' );
}

/**
 * Preguntas y respuestas de los bloques Detalles del contenido.
 *
 * @param string $content Contenido con bloques.
 * @return array Lista de array( pregunta, respuesta ).
 */
function flmm_faq_from_content( $content ) {
	$faq   = array();
	$stack = parse_blocks( $content );
	while ( $stack ) {
		$block = array_shift( $stack );
		if ( 'core/details' === $block['blockName'] ) {
			$html = $block['innerHTML'];
			if ( preg_match( '#<summary[^>]*>(.*?)</summary>#s', $html, $m ) ) {
				$question = trim( wp_strip_all_tags( $m[1] ) );
				$answer   = '';
				foreach ( $block['innerBlocks'] as $inner ) {
					$answer .= ' ' . render_block( $inner );
				}
				$answer = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $answer ) ) );
				if ( $question && $answer ) {
					$faq[] = array( $question, $answer );
				}
			}
			continue;
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			$stack = array_merge( $block['innerBlocks'], $stack );
		}
	}
	return $faq;
}

/**
 * Arma el grafo de la vista actual.
 *
 * @return array
 */
function flmm_schema_graph() {
	$types = flmm_schema_types();
	if ( ! $types || is_404() ) {
		return array();
	}
	$graph = array();
	$lang  = 'es' === flmm_lang() ? 'es' : 'en';
	$home  = home_url( '/' );

	if ( in_array( 'Organization', $types, true ) ) {
		$graph[] = array(
			'@type'        => array( 'Organization', 'ProfessionalService' ),
			'@id'          => flmm_org_id(),
			'name'         => 'FL Marketing Management',
			'legalName'    => 'FL Marketing Management, LLC',
			'url'          => $home,
			'email'        => flmm_option( 'email' ),
			'logo'         => FLMM_URI . '/assets/brand/sig.png',
			'sameAs'       => array_values( array_filter( array( flmm_option( 'linkedin' ), flmm_option( 'instagram' ) ) ) ),
			'contactPoint' => array(
				'@type'             => 'ContactPoint',
				'contactType'       => 'sales',
				'email'             => flmm_option( 'email' ),
				'availableLanguage' => array( 'en', 'es' ),
			),
		);
	}
	if ( in_array( 'WebSite', $types, true ) ) {
		$graph[] = array(
			'@type'      => 'WebSite',
			'@id'        => $home . '#website',
			'url'        => $home,
			'name'       => 'FL Marketing Management',
			'inLanguage' => array( 'en', 'es' ),
			'publisher'  => array( '@id' => flmm_org_id() ),
		);
	}
	if ( in_array( 'BreadcrumbList', $types, true ) && ! is_front_page() ) {
		$list = array();
		foreach ( flmm_breadcrumb_items() as $i => $item ) {
			$entry = array(
				'@type'    => 'ListItem',
				'position' => $i + 1,
				'name'     => $item['name'],
			);
			if ( $item['url'] ) {
				$entry['item'] = $item['url'];
			}
			$list[] = $entry;
		}
		if ( count( $list ) > 1 ) {
			$graph[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => $list );
		}
	}

	if ( is_singular() ) {
		$post = get_queried_object();
		$url  = get_permalink( $post );

		if ( in_array( 'Service', $types, true ) && 'page-service' === get_page_template_slug( $post ) ) {
			$description = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 40, '' );
			$graph[]     = array(
				'@type'             => 'Service',
				'@id'               => $url . '#service',
				'name'              => wp_strip_all_tags( get_the_title( $post ) ),
				'serviceType'       => wp_strip_all_tags( get_the_title( $post ) ),
				'url'               => $url,
				'description'       => $description,
				'provider'          => array( '@id' => flmm_org_id() ),
				'areaServed'        => array( 'US', 'CL', 'CO', 'MX', 'CR' ),
				'availableLanguage' => array( 'en', 'es' ),
				'inLanguage'        => $lang,
			);
		}
		if ( in_array( 'FAQPage', $types, true ) ) {
			$faq = flmm_faq_from_content( $post->post_content );
			if ( $faq ) {
				$graph[] = array(
					'@type'      => 'FAQPage',
					'@id'        => $url . '#faq',
					'inLanguage' => $lang,
					'mainEntity' => array_map(
						static function ( $qa ) {
							return array(
								'@type'          => 'Question',
								'name'           => $qa[0],
								'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $qa[1] ),
							);
						},
						$faq
					),
				);
			}
		}
		if ( is_singular( 'post' ) ) {
			$author_id = (int) $post->post_author;
			$person    = array(
				'@type'    => 'Person',
				'@id'      => $home . '#/schema/person/' . $author_id,
				'name'     => get_the_author_meta( 'display_name', $author_id ),
				'url'      => get_author_posts_url( $author_id ),
				'jobTitle' => flmm_author_field( $author_id, 'flmm_role' ),
			);
			$linkedin  = get_the_author_meta( 'flmm_linkedin', $author_id );
			if ( $linkedin ) {
				$person['sameAs'] = array( $linkedin );
			}
			if ( in_array( 'Person', $types, true ) ) {
				$graph[] = $person;
			}
			if ( in_array( 'BlogPosting', $types, true ) ) {
				$category = flmm_primary_category( $post );
				$article  = array(
					'@type'            => 'BlogPosting',
					'@id'              => $url . '#article',
					'headline'         => wp_strip_all_tags( get_the_title( $post ) ),
					'description'      => get_the_excerpt( $post ),
					'url'              => $url,
					'mainEntityOfPage' => $url,
					'datePublished'    => get_post_time( 'c', false, $post ),
					'dateModified'     => get_post_modified_time( 'c', false, $post ),
					'inLanguage'       => $lang,
					'wordCount'        => str_word_count( wp_strip_all_tags( $post->post_content ) ),
					'author'           => array( '@id' => $person['@id'] ),
					'publisher'        => array( '@id' => flmm_org_id() ),
				);
				if ( has_post_thumbnail( $post ) ) {
					$article['image'] = get_the_post_thumbnail_url( $post, 'full' );
				}
				if ( $category ) {
					$article['articleSection'] = $category->name;
				}
				$graph[] = $article;
			}
		}
	}
	return (array) apply_filters( 'flmm_schema_graph', $graph );
}

/**
 * Imprime el JSON-LD.
 */
function flmm_print_schema() {
	$graph = flmm_schema_graph();
	if ( ! $graph ) {
		return;
	}
	echo '<script type="application/ld+json" class="flmm-schema">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}
add_action( 'wp_head', 'flmm_print_schema', 30 );
