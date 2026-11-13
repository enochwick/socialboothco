<?php
/**
 * [sbc_gallery] shortcode and asset loading.
 *
 * Attributes:
 *   hero="yes|no"        Show the hero with photo strips.
 *   includes="yes|no"    Show the "Every booking includes" section.
 *   per_page="24"        Photos shown before "View More Moments".
 *   book_modal="c63d6a3" Elementor/UAEL modal ID the "Check Your Date" button opens (the header's Amelia popup).
 *   book_url="/#pricing" Fallback link if that popup isn't on the page.
 *   hero_ids="1,2,3"     Six attachment IDs for the hero photo strips.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'sbcg_register_assets' );
add_shortcode( 'sbc_gallery', 'sbcg_shortcode' );

function sbcg_register_assets() {
	$css = SBCG_DIR . '/assets/sbc-gallery.css';
	$js  = SBCG_DIR . '/assets/sbc-gallery.js';

	wp_register_style( 'sbc-gallery', plugins_url( 'assets/sbc-gallery.css', SBCG_FILE ), array(), SBCG_VERSION . '.' . filemtime( $css ) );
	wp_register_script( 'sbc-gallery', plugins_url( 'assets/sbc-gallery.js', SBCG_FILE ), array(), SBCG_VERSION . '.' . filemtime( $js ), true );

	// Load CSS in <head> when the current page uses the shortcode (avoids a flash of unstyled content).
	if ( is_singular() ) {
		$post_id = get_queried_object_id();
		$content = (string) get_post_field( 'post_content', $post_id );
		$el_data = (string) get_post_meta( $post_id, '_elementor_data', true );
		if ( false !== strpos( $content, '[sbc_gallery' ) || false !== strpos( $el_data, 'sbc_gallery' ) ) {
			wp_enqueue_style( 'sbc-gallery' );
		}
	}
}

/**
 * All attachments with a Gallery Category: newly tagged photos first (newest upload first),
 * then the original curated order.
 */
function sbcg_get_items() {
	$posts = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 500,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
			'tax_query'      => array(
				array(
					'taxonomy' => SBCG_TAX,
					'operator' => 'EXISTS',
				),
			),
		)
	);

	$position = array_flip( array_map( 'intval', (array) get_option( 'sbcg_order', array() ) ) );
	$fresh    = array();
	$curated  = array();

	foreach ( $posts as $post ) {
		$item = sbcg_build_item( $post );
		if ( ! $item ) {
			continue;
		}
		if ( isset( $position[ $post->ID ] ) ) {
			$curated[ $position[ $post->ID ] ] = $item;
		} else {
			$fresh[] = $item;
		}
	}
	ksort( $curated );

	return array_merge( $fresh, array_values( $curated ) );
}

function sbcg_build_item( $post ) {
	$terms = get_the_terms( $post->ID, SBCG_TAX );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return null;
	}

	$meta   = wp_get_attachment_metadata( $post->ID );
	$width  = isset( $meta['width'] ) ? (int) $meta['width'] : 0;
	$height = isset( $meta['height'] ) ? (int) $meta['height'] : 0;
	$is_gif = 'image/gif' === get_post_mime_type( $post );

	// Resized GIFs lose their animation, so GIFs always use the original file.
	$full = $is_gif ? wp_get_attachment_url( $post->ID ) : wp_get_attachment_image_url( $post->ID, '2048x2048' );
	if ( ! $full ) {
		return null;
	}

	$labels = wp_list_pluck( $terms, 'name' );
	$alt    = trim( (string) get_post_meta( $post->ID, '_wp_attachment_image_alt', true ) );
	if ( '' === $alt ) {
		$alt = sprintf( 'Social Booth Co. photo booth – %s', implode( ', ', $labels ) );
	}

	return array(
		'id'     => $post->ID,
		'slugs'  => wp_list_pluck( $terms, 'slug' ),
		'labels' => $labels,
		'alt'    => $alt,
		'full'   => $full,
		'size'   => $is_gif ? 'full' : 'large',
		'wide'   => $width && $height && $width > $height * 1.2,
	);
}

/**
 * Filter buttons: default order first, then any extra categories alphabetically. Empty ones are skipped.
 */
function sbcg_get_filters( $items ) {
	$counts = array();
	foreach ( $items as $item ) {
		foreach ( $item['slugs'] as $slug ) {
			$counts[ $slug ] = isset( $counts[ $slug ] ) ? $counts[ $slug ] + 1 : 1;
		}
	}

	$terms = get_terms(
		array(
			'taxonomy'   => SBCG_TAX,
			'hide_empty' => false,
			'orderby'    => 'name',
		)
	);
	if ( is_wp_error( $terms ) ) {
		return array();
	}

	$rank    = array_flip( array_keys( sbcg_default_terms() ) );
	$filters = array();
	foreach ( $terms as $term ) {
		if ( empty( $counts[ $term->slug ] ) ) {
			continue;
		}
		$filters[] = array(
			'slug'  => $term->slug,
			'name'  => $term->name,
			'count' => $counts[ $term->slug ],
			'rank'  => isset( $rank[ $term->slug ] ) ? $rank[ $term->slug ] : 100,
		);
	}
	usort(
		$filters,
		function ( $a, $b ) {
			return $a['rank'] === $b['rank'] ? strcasecmp( $a['name'], $b['name'] ) : $a['rank'] - $b['rank'];
		}
	);

	return $filters;
}

function sbcg_get_hero_ids( $attr, $items ) {
	$ids = array_filter( array_map( 'intval', explode( ',', $attr ) ) );
	$ids = array_values(
		array_filter(
			$ids,
			function ( $id ) {
				return 'attachment' === get_post_type( $id );
			}
		)
	);

	// Top up from the gallery if any are missing.
	foreach ( $items as $item ) {
		if ( count( $ids ) >= 6 ) {
			break;
		}
		if ( 'full' !== $item['size'] && ! in_array( $item['id'], $ids, true ) ) {
			$ids[] = $item['id'];
		}
	}

	return array_slice( $ids, 0, 6 );
}

function sbcg_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'hero'       => 'yes',
			'includes'   => 'yes',
			'per_page'   => 24,
			'book_modal' => 'c63d6a3',
			'book_url'   => '/#pricing',
			'hero_ids'   => '2312,2304,2314,2541,2315,2400',
		),
		$atts,
		'sbc_gallery'
	);

	wp_enqueue_style( 'sbc-gallery' );
	wp_enqueue_script( 'sbc-gallery' );

	$items    = sbcg_get_items();
	$filters  = sbcg_get_filters( $items );
	$per_page = max( 4, (int) $atts['per_page'] );
	$hero_ids = 'yes' === $atts['hero'] ? sbcg_get_hero_ids( $atts['hero_ids'], $items ) : array();

	ob_start();
	include SBCG_DIR . '/templates/gallery.php';
	return ob_get_clean();
}
