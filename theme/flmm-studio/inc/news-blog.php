<?php
/**
 * Blog desde noticias (n8n).
 *
 * Endpoints REST para el flujo de n8n que convierte noticias en artículos EN/ES:
 * - GET  flmm/v1/news/inventory          Artículos existentes (para no repetir temas).
 * - POST flmm/v1/news/posts              Valida y crea el par EN/ES en borrador. Con dry_run solo valida.
 * - POST flmm/v1/news/posts/{id}/image   Asigna la imagen destacada (ID de medio subido a /wp/v2/media).
 * - POST flmm/v1/news/posts/{id}/approve Programa (o publica si la hora ya pasó) el par EN/ES.
 * - POST flmm/v1/news/posts/{id}/reject  Envía el par a la papelera.
 *
 * Autenticación: contraseña de aplicación del autor (Basic). Requiere permiso publish_posts.
 *
 * @package flmm-studio
 */

defined( 'ABSPATH' ) || exit;

add_action( 'rest_api_init', 'flmm_news_routes' );

/**
 * Registra las rutas.
 */
function flmm_news_routes() {
	$perm = static function () {
		return current_user_can( 'publish_posts' );
	};
	register_rest_route( 'flmm/v1', '/news/inventory', array( 'methods' => 'GET', 'callback' => 'flmm_news_inventory', 'permission_callback' => $perm ) );
	register_rest_route( 'flmm/v1', '/news/posts', array( 'methods' => 'POST', 'callback' => 'flmm_news_create', 'permission_callback' => $perm ) );
	foreach ( array( 'image', 'approve', 'reject' ) as $action ) {
		register_rest_route(
			'flmm/v1',
			'/news/posts/(?P<id>\d+)/' . $action,
			array( 'methods' => 'POST', 'callback' => 'flmm_news_' . $action, 'permission_callback' => $perm )
		);
	}
}

/**
 * Carga las funciones de la migración (render de artículos, categorías, SEO).
 */
function flmm_news_load() {
	require_once FLMM_DIR . '/inc/migrate/migrate.php';
}

/**
 * Inventario de artículos (todos los estados salvo papelera), en inglés.
 *
 * @return WP_REST_Response
 */
function flmm_news_inventory() {
	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => array( 'publish', 'future', 'draft', 'pending' ),
			'posts_per_page' => 300,
			'lang'           => 'en',
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
	$out = array();
	foreach ( $posts as $p ) {
		$out[] = array(
			'id'      => $p->ID,
			'title'   => $p->post_title,
			'keyword' => (string) get_post_meta( $p->ID, 'rank_math_focus_keyword', true ),
			'status'  => $p->post_status,
			'date'    => $p->post_date_gmt,
			'url'     => 'publish' === $p->post_status ? get_permalink( $p ) : '',
			'source'  => (string) get_post_meta( $p->ID, '_flmm_news_source', true ),
		);
	}
	return rest_ensure_response( $out );
}

/**
 * Texto plano en minúsculas.
 *
 * @param string $s HTML.
 * @return string
 */
function flmm_news_plain( $s ) {
	return mb_strtolower( wp_strip_all_tags( (string) $s ) );
}

/**
 * Cuenta palabras.
 *
 * @param string $s HTML.
 * @return int
 */
function flmm_news_words( $s ) {
	$t = trim( wp_strip_all_tags( (string) $s ) );
	return '' === $t ? 0 : count( preg_split( '/\s+/u', $t ) );
}

/**
 * Valida un artículo con las mismas reglas que src/blog_posts.py.
 *
 * @param array $p Artículo (cat, en, es, publish_gmt).
 * @return string[] Problemas encontrados.
 */
function flmm_news_validate( $p ) {
	$probs = array();
	if ( empty( $p['cat'] ) || ! isset( flmm_mig_categories()[ $p['cat'] ] ) ) {
		$probs[] = 'cat debe ser una de: ' . implode( ', ', array_keys( flmm_mig_categories() ) );
	}
	if ( empty( $p['publish_gmt'] ) || ! strtotime( $p['publish_gmt'] . ' UTC' ) ) {
		$probs[] = 'publish_gmt inválido (formato YYYY-MM-DD HH:MM:SS)';
	}
	if ( wp_json_encode( $p ) && false !== strpos( wp_json_encode( $p, JSON_UNESCAPED_UNICODE ), "\u{2014}" ) ) {
		$probs[] = 'tiene raya larga: usa punto, coma o dos puntos';
	}
	foreach ( array( 'en', 'es' ) as $lang ) {
		$d = isset( $p[ $lang ] ) ? $p[ $lang ] : null;
		if ( ! is_array( $d ) ) {
			$probs[] = "$lang: falta";
			continue;
		}
		$missing = false;
		foreach ( array( 'slug', 'title', 'seo', 'tldr', 'intro', 'body', 'faq_title', 'faq', 'takeaways', 'ref', 'image_alt' ) as $k ) {
			if ( empty( $d[ $k ] ) ) {
				$probs[] = "$lang: falta $k";
				$missing = true;
			}
		}
		if ( $missing || ! is_array( $d['seo'] ) || count( $d['seo'] ) < 3 ) {
			continue;
		}
		list( $t, $desc, $kw ) = $d['seo'];
		$kw = mb_strtolower( $kw );
		if ( mb_strlen( $t ) > 60 ) {
			$probs[] = "$lang: title SEO tiene " . mb_strlen( $t ) . ' caracteres (máx. 60)';
		}
		if ( mb_strlen( $desc ) < 140 || mb_strlen( $desc ) > 160 ) {
			$probs[] = "$lang: description tiene " . mb_strlen( $desc ) . ' caracteres (140 a 160)';
		}
		$iw = flmm_news_words( $d['intro'] );
		if ( $iw < 40 || $iw > 60 ) {
			$probs[] = "$lang: intro tiene $iw palabras (40 a 60)";
		}
		foreach ( $d['faq'] as $qa ) {
			$w = flmm_news_words( $qa[1] );
			if ( $w < 40 || $w > 60 ) {
				$probs[] = "$lang: respuesta FAQ \"" . mb_substr( $qa[0], 0, 40 ) . "\" tiene $w palabras (40 a 60)";
			}
		}
		foreach ( array( 'title SEO' => $t, 'description' => $desc, 'intro' => $d['intro'], 'faq_title' => $d['faq_title'], 'slug' => str_replace( '-', ' ', $d['slug'] ) ) as $label => $txt ) {
			if ( false === strpos( flmm_news_plain( $txt ), $kw ) ) {
				$probs[] = "$lang: la keyword \"$kw\" no está en $label";
			}
		}
		$subs = array();
		$text = array( $d['tldr'], $d['intro'] );
		foreach ( $d['body'] as $b ) {
			if ( in_array( $b[0], array( 'h2', 'h3' ), true ) ) {
				$subs[] = $b[1];
			}
			$text[] = is_array( $b[1] ) ? implode( ' ', $b[1] ) : $b[1];
		}
		$has_sub = false;
		foreach ( $subs as $h ) {
			$has_sub = $has_sub || false !== strpos( flmm_news_plain( $h ), $kw );
		}
		if ( ! $has_sub ) {
			$probs[] = "$lang: la keyword no está en ningún H2/H3 del cuerpo";
		}
		$start = 'en' === $lang ? 'frequently asked questions' : 'preguntas frecuentes';
		if ( 0 !== strpos( flmm_news_plain( $d['faq_title'] ), $start ) ) {
			$probs[] = "$lang: faq_title debe empezar con \"$start\"";
		}
		$text[] = $d['faq_title'];
		foreach ( $d['faq'] as $qa ) {
			$text[] = $qa[0] . ' ' . $qa[1];
		}
		$text  = array_merge( $text, $d['takeaways'] );
		$full  = implode( ' ', $text );
		$total = flmm_news_words( $full );
		$n     = substr_count( flmm_news_plain( $full ), $kw );
		$dens  = $total ? 100 * $n / $total : 0;
		if ( $total < 600 ) {
			$probs[] = "$lang: tiene $total palabras (mínimo 600)";
		}
		if ( $dens < 0.6 || $dens > 2.0 ) {
			$probs[] = sprintf( '%s: densidad de keyword %.2f%% (%d veces en %d palabras; objetivo 0,6 a 2)', $lang, $dens, $n, $total );
		}
		if ( ! is_array( $d['ref'] ) || 0 !== strpos( (string) $d['ref'][1], 'https://' ) ) {
			$probs[] = "$lang: ref debe ser [etiqueta, url https]";
		}
		if ( 'es' === $lang && preg_match( '/\b(querés|tenés|podés|sabés|hacé|mirá|fijate|definí|vos|che|laburo)\b/iu', $full ) ) {
			$probs[] = 'es: tiene voseo o rioplatense; usa español neutro con tú';
		}
	}
	return $probs;
}

/**
 * Crea (o valida con dry_run) el par EN/ES en borrador.
 *
 * @param WP_REST_Request $req Petición.
 * @return WP_REST_Response|WP_Error
 */
function flmm_news_create( WP_REST_Request $req ) {
	flmm_news_load();
	$p     = $req->get_json_params();
	$probs = flmm_news_validate( (array) $p );
	if ( $probs || ! empty( $p['dry_run'] ) ) {
		return rest_ensure_response( array( 'ok' => ! $probs, 'problems' => $probs ) );
	}
	$cat = flmm_mig_category( $p['cat'] );
	$ids = array();
	foreach ( array( 'en', 'es' ) as $lang ) {
		$d  = $p[ $lang ];
		$id = wp_insert_post(
			wp_slash(
				array(
					'post_type'      => 'post',
					'post_status'    => 'draft',
					'post_author'    => get_current_user_id(),
					'post_title'     => $d['title'],
					'post_name'      => sanitize_title( $d['slug'] ),
					'post_content'   => flmm_mig_new_post_content( $d, $lang ),
					'post_excerpt'   => $d['seo'][1],
					'comment_status' => 'closed',
				)
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			foreach ( $ids as $done ) {
				wp_delete_post( $done, true );
			}
			return $id;
		}
		pll_set_post_language( $id, $lang );
		if ( ! empty( $cat[ $lang ] ) ) {
			wp_set_post_categories( $id, array( $cat[ $lang ] ), false );
		}
		flmm_mig_set_seo( $id, $d['seo'] );
		update_post_meta( $id, '_flmm_news_image_alt', sanitize_text_field( $d['image_alt'] ) );
		update_post_meta( $id, '_flmm_news_source', esc_url_raw( $d['ref'][1] ) );
		update_post_meta( $id, '_flmm_news_slot', sanitize_text_field( $p['publish_gmt'] ) );
		$ids[ $lang ] = $id;
	}
	pll_save_post_translations( $ids );
	return rest_ensure_response(
		array(
			'ok'      => true,
			'id'      => $ids['en'],
			'es_id'   => $ids['es'],
			'preview' => array(
				'en' => get_preview_post_link( $ids['en'] ),
				'es' => get_preview_post_link( $ids['es'] ),
			),
			'edit'    => admin_url( 'post.php?action=edit&post=' . $ids['en'] ),
		)
	);
}

/**
 * IDs EN y ES del par a partir del ID inglés.
 *
 * @param int $id ID EN.
 * @return array|WP_Error
 */
function flmm_news_pair( $id ) {
	$id = (int) $id;
	if ( 'post' !== get_post_type( $id ) || 'en' !== pll_get_post_language( $id ) ) {
		return new WP_Error( 'flmm_news_not_found', 'Artículo EN no encontrado.', array( 'status' => 404 ) );
	}
	$es = (int) pll_get_post( $id, 'es' );
	return array_filter( array( 'en' => $id, 'es' => $es ) );
}

/**
 * Asigna la imagen destacada: el medio subido queda en inglés y se crea su copia en español.
 *
 * @param WP_REST_Request $req Petición (media_id).
 * @return WP_REST_Response|WP_Error
 */
function flmm_news_image( WP_REST_Request $req ) {
	flmm_news_load();
	$pair  = flmm_news_pair( $req['id'] );
	$media = (int) $req->get_param( 'media_id' );
	if ( is_wp_error( $pair ) ) {
		return $pair;
	}
	if ( 'attachment' !== get_post_type( $media ) ) {
		return new WP_Error( 'flmm_news_media', 'media_id no es un medio.', array( 'status' => 400 ) );
	}
	$att = array( 'en' => $media );
	if ( ! empty( $pair['es'] ) ) {
		$att['es'] = (int) pll_get_post( $media, 'es' );
		if ( ! $att['es'] ) {
			$src        = get_post( $media );
			$att['es']  = wp_insert_attachment(
				array(
					'post_mime_type' => $src->post_mime_type,
					'guid'           => $src->guid,
					'post_title'     => $src->post_title,
					'post_status'    => 'inherit',
				),
				get_attached_file( $media, true )
			);
			wp_update_attachment_metadata( $att['es'], wp_get_attachment_metadata( $media ) );
		}
	}
	foreach ( $att as $lang => $aid ) {
		pll_set_post_language( $aid, $lang );
		$alt = get_post_meta( $pair[ $lang ], '_flmm_news_image_alt', true );
		if ( $alt ) {
			update_post_meta( $aid, '_wp_attachment_image_alt', $alt );
		}
		set_post_thumbnail( $pair[ $lang ], $aid );
		flmm_mig_set_seo( $pair[ $lang ], array( get_post_meta( $pair[ $lang ], 'rank_math_title', true ), get_post_meta( $pair[ $lang ], 'rank_math_description', true ), get_post_meta( $pair[ $lang ], 'rank_math_focus_keyword', true ) ) );
	}
	if ( count( $att ) === 2 ) {
		pll_save_post_translations( $att );
	}
	return rest_ensure_response( array( 'ok' => true, 'media' => $att ) );
}

/**
 * Imagen de respaldo por categoría si el artículo no tiene imagen al aprobarlo.
 *
 * @param string $lang Idioma.
 * @param int    $id   ID del artículo.
 * @return int
 */
function flmm_news_fallback_image( $lang, $id ) {
	$map  = array(
		'aeo'         => 'post-aeo-services',
		'ai'          => 'post-seo-aeo-geo',
		'performance' => 'post-plan-2027',
		'local'       => 'post-florida-agency',
	);
	$cats = wp_get_post_categories( $id, array( 'fields' => 'slugs' ) );
	$key  = 'post-seo-aeo-geo';
	foreach ( flmm_mig_categories() as $ck => $def ) {
		if ( in_array( $def[ $lang ][1], $cats, true ) && isset( $map[ $ck ] ) ) {
			$key = $map[ $ck ];
		}
	}
	$img = flmm_mig_image( $key, $lang );
	return $img ? (int) $img['id'] : 0;
}

/**
 * Aprueba: programa el par a la hora acordada o lo publica ya si esa hora pasó.
 *
 * @param WP_REST_Request $req Petición.
 * @return WP_REST_Response|WP_Error
 */
function flmm_news_approve( WP_REST_Request $req ) {
	flmm_news_load();
	$pair = flmm_news_pair( $req['id'] );
	if ( is_wp_error( $pair ) ) {
		return $pair;
	}
	$slot = (string) get_post_meta( $pair['en'], '_flmm_news_slot', true );
	$ts   = $slot ? strtotime( $slot . ' UTC' ) : 0;
	$out  = array( 'ok' => true );
	foreach ( $pair as $lang => $pid ) {
		if ( ! get_post_thumbnail_id( $pid ) ) {
			$fb = flmm_news_fallback_image( $lang, $pid );
			if ( $fb ) {
				set_post_thumbnail( $pid, $fb );
				$out['fallback_image'] = true;
			}
		}
		$data = array( 'ID' => $pid );
		if ( $ts > time() ) {
			$data['post_status']   = 'future';
			$data['post_date_gmt'] = gmdate( 'Y-m-d H:i:s', $ts );
			$data['post_date']     = get_date_from_gmt( $data['post_date_gmt'] );
			$data['edit_date']     = true;
		} else {
			$data['post_status']   = 'publish';
			$data['post_date_gmt'] = gmdate( 'Y-m-d H:i:s' );
			$data['post_date']     = get_date_from_gmt( $data['post_date_gmt'] );
			$data['edit_date']     = true;
		}
		wp_update_post( $data );
		$out[ $lang ] = array( 'status' => get_post_status( $pid ), 'date_gmt' => $data['post_date_gmt'], 'url' => get_permalink( $pid ) );
	}
	return rest_ensure_response( $out );
}

/**
 * Rechaza: envía el par a la papelera.
 *
 * @param WP_REST_Request $req Petición.
 * @return WP_REST_Response|WP_Error
 */
function flmm_news_reject( WP_REST_Request $req ) {
	$pair = flmm_news_pair( $req['id'] );
	if ( is_wp_error( $pair ) ) {
		return $pair;
	}
	foreach ( $pair as $pid ) {
		wp_trash_post( $pid );
	}
	return rest_ensure_response( array( 'ok' => true ) );
}
