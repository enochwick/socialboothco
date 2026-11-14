<?php
/**
 * SEO: page titles and meta descriptions, local business structured data, one H1 per page,
 * and keeping leftover template content (demo blog posts, the filler Get a Quote page) out of
 * search. Works with Yoast SEO's hooks, with a plain fallback when Yoast is off.
 *
 * Copy here only applies where a page has no description of its own in Yoast, except the
 * homepage, whose stored description was too long for search results.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Titles and descriptions by page slug.
 */
function sbc_seo_pages() {
	return array(
		'front'                     => array(
			'title'       => 'Photo Booth Rental in Dallas–Fort Worth | Social Booth Co',
			'description' => 'Modern photo booth rental in Dallas–Fort Worth with custom overlays, premium backdrops and instant prints for weddings, parties and corporate events.',
		),
		'experience'                => array(
			'title'       => 'How Our Photo Booth Experience Works | Social Booth Co',
			'description' => 'See how a Social Booth Co photo booth works, from booking to instant sharing. Custom overlays, studio lighting and an attendant at every DFW event.',
		),
		'gallery'                   => array(
			'title'       => 'Photo Booth Gallery: DFW Weddings & Events | Social Booth Co',
			'description' => 'Real photos from Social Booth Co events across Dallas–Fort Worth: weddings, quinceañeras, corporate parties, prints, backdrops and booth setups.',
		),
		'faq'                       => array(
			'title'       => 'Photo Booth Rental FAQs | Social Booth Co Dallas–Fort Worth',
			'description' => 'Answers about booking a photo booth in DFW: space needed, deposits, setup time, prints, online galleries and the areas we serve.',
		),
		'corporate-holiday-parties' => array(
			'description' => 'Photo booth for office holiday parties in Dallas–Fort Worth. Your logo on every photo, instant sharing and hands-off setup. Packages from $495.',
		),
		'agreement'                 => array(
			'title'       => 'Photo Booth Rental Agreement & Terms | Social Booth Co',
			'description' => 'The rental agreement and terms of service for Social Booth Co photo booth bookings in Dallas–Fort Worth, including deposits and cancellations.',
		),
		'check-availability'        => array(
			'title'       => 'Check Your Event Date | Social Booth Co Photo Booth',
			'description' => 'Check if your event date is open and book a Social Booth Co photo booth for your wedding, party or corporate event in Dallas–Fort Worth.',
		),
	);
}

function sbc_seo_current() {
	if ( is_front_page() ) {
		return sbc_seo_pages()['front'];
	}
	if ( is_page() ) {
		if ( sbc_is_city_page() ) {
			$city = sbc_city_name();
			return array(
				'description' => sprintf( 'Photo booth rental in %s by Social Booth Co: instant sharing, custom overlays and studio lighting for weddings, parties and corporate events. From $495.', $city ),
			);
		}
		$slug  = get_post_field( 'post_name', get_queried_object_id() );
		$pages = sbc_seo_pages();
		return isset( $pages[ $slug ] ) ? $pages[ $slug ] : array();
	}
	return array();
}

// Yoast: description (fill empty ones; replace the homepage's over-long one).
add_filter(
	'wpseo_metadesc',
	function ( $desc ) {
		$seo = sbc_seo_current();
		if ( empty( $seo['description'] ) ) {
			return $desc;
		}
		if ( '' === trim( (string) $desc ) || ( is_front_page() && strlen( $desc ) > 160 ) ) {
			return $seo['description'];
		}
		return $desc;
	}
);
add_filter(
	'wpseo_opengraph_desc',
	function ( $desc ) {
		$seo = sbc_seo_current();
		return ( '' === trim( (string) $desc ) && ! empty( $seo['description'] ) ) ? $seo['description'] : $desc;
	}
);

// Yoast: titles for pages still on the generic "Page - Social Booth Co" pattern.
add_filter(
	'wpseo_title',
	function ( $title ) {
		$seo = sbc_seo_current();
		if ( empty( $seo['title'] ) ) {
			return $title;
		}
		$generic = get_the_title( get_queried_object_id() ) . ' - ' . get_bloginfo( 'name' );
		return ( wp_strip_all_tags( html_entity_decode( $title ) ) === wp_strip_all_tags( html_entity_decode( $generic ) ) || '' === $title ) ? $seo['title'] : $title;
	}
);

// Without Yoast: same title and description.
add_filter(
	'pre_get_document_title',
	function ( $title ) {
		if ( defined( 'WPSEO_VERSION' ) ) {
			return $title;
		}
		$seo = sbc_seo_current();
		return ! empty( $seo['title'] ) ? $seo['title'] : $title;
	}
);
add_action(
	'wp_head',
	function () {
		if ( defined( 'WPSEO_VERSION' ) ) {
			return;
		}
		$seo = sbc_seo_current();
		if ( ! empty( $seo['description'] ) ) {
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( $seo['description'] ) );
		}
	},
	2
);

/**
 * Leftover template content: the demo blog posts and their archives redirect to the homepage,
 * and the filler Get a Quote page redirects to Check Availability. Reversible: set the filter
 * `sbc_blog_enabled` to true when a real blog starts.
 */
function sbc_blog_enabled() {
	return (bool) apply_filters( 'sbc_blog_enabled', false );
}

add_action(
	'template_redirect',
	function () {
		if ( is_admin() || is_customize_preview() || is_preview() ) {
			return;
		}
		if ( is_page( 'get-a-quote' ) ) {
			wp_safe_redirect( home_url( '/check-availability/' ), 301, 'Social Booth Co theme' );
			exit;
		}
		if ( ! sbc_blog_enabled() && ( is_singular( 'post' ) || is_category() || is_tag() || is_author() || is_date() || ( is_home() && ! is_front_page() ) ) ) {
			wp_safe_redirect( home_url( '/' ), 301, 'Social Booth Co theme' );
			exit;
		}
	}
);

/**
 * Utility pages that shouldn't appear in search: the post-booking thank-you page and
 * WordPress's default sample page.
 */
function sbc_noindex_slugs() {
	return array( 'thank-you', 'sample-page' );
}

add_filter(
	'wpseo_robots',
	function ( $robots ) {
		return ( is_page( sbc_noindex_slugs() ) || is_page( 'get-a-quote' ) ) ? 'noindex, follow' : $robots;
	}
);
add_filter(
	'wp_robots',
	function ( $robots ) {
		if ( ! defined( 'WPSEO_VERSION' ) && is_page( sbc_noindex_slugs() ) ) {
			$robots['noindex'] = true;
			$robots['follow']  = true;
		}
		return $robots;
	}
);

// Keep them out of Yoast's sitemaps (also Elementor's header/footer templates).
add_filter(
	'wpseo_sitemap_exclude_post_type',
	function ( $excluded, $post_type ) {
		if ( 'elementor-hf' === $post_type ) {
			return true;
		}
		return ( 'post' === $post_type && ! sbc_blog_enabled() ) ? true : $excluded;
	},
	10,
	2
);
add_filter(
	'wpseo_sitemap_exclude_taxonomy',
	function ( $excluded, $taxonomy ) {
		return ( in_array( $taxonomy, array( 'category', 'post_tag', 'post_format' ), true ) && ! sbc_blog_enabled() ) ? true : $excluded;
	},
	10,
	2
);
add_filter(
	'wpseo_sitemap_exclude_author',
	function ( $users ) {
		return sbc_blog_enabled() ? $users : array();
	}
);
add_filter(
	'wpseo_exclude_from_sitemap_by_post_ids',
	function ( $ids ) {
		foreach ( array_merge( array( 'get-a-quote' ), sbc_noindex_slugs() ) as $slug ) {
			$page = get_page_by_path( $slug );
			if ( $page ) {
				$ids[] = (int) $page->ID;
			}
		}
		return $ids;
	}
);

/**
 * One H1 per page. Elementor-built pages whose content starts at H2 (Agreement) get their
 * first heading promoted; Check Availability, which has no heading at all, gets one that is
 * read by search engines and screen readers without changing the design.
 */
add_filter(
	'the_content',
	function ( $content ) {
		if ( is_admin() || ! is_page() || is_front_page() || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}
		// Theme-drawn pages already print the page title as the H1.
		if ( ! sbc_is_elementor_page() || preg_match( '#<h1[\s>]#i', $content ) ) {
			return $content;
		}
		if ( is_page( 'check-availability' ) ) {
			return '<h1 class="screen-reader-text">' . esc_html__( 'Check your event date', 'socialboothco' ) . '</h1>' . $content;
		}
		if ( preg_match( '#<h2([^>]*)>(.*?)</h2>#s', $content, $m, PREG_OFFSET_CAPTURE ) ) {
			$full = $m[0][0];
			$h1   = '<h1' . $m[1][0] . '>' . $m[2][0] . '</h1>';
			return substr_replace( $content, $h1, $m[0][1], strlen( $full ) );
		}
		return $content;
	},
	30
);

/**
 * Local business structured data on the homepage and city pages (a service-area business
 * across Dallas–Fort Worth). No rating markup: Google ignores self-hosted review stars.
 */
add_action(
	'wp_head',
	function () {
		if ( ! is_front_page() && ! sbc_is_city_page() ) {
			return;
		}
		$areas = array_filter( array_map( 'trim', preg_split( '/,|\band\b/', str_replace( '.', '', sbc_mod( 'areas' ) ) ) ) );
		$data  = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'LocalBusiness',
			'@id'         => home_url( '/#localbusiness' ),
			'name'        => get_bloginfo( 'name' ),
			'description' => sbc_seo_pages()['front']['description'],
			'url'         => home_url( '/' ),
			'telephone'   => sbc_mod( 'phone' ),
			'email'       => sbc_mod( 'email' ),
			'image'       => get_template_directory_uri() . '/screenshot.png',
			'logo'        => get_template_directory_uri() . '/assets/img/logo-dark.png',
			'priceRange'  => '$495–$950',
			'address'     => array(
				'@type'           => 'PostalAddress',
				'addressRegion'   => 'TX',
				'addressCountry'  => 'US',
			),
			'areaServed'  => array_map(
				function ( $city ) {
					return array( '@type' => 'City', 'name' => $city . ', TX' );
				},
				array_values( $areas )
			),
			'sameAs'      => array_values( array_filter( array( sbc_mod( 'reviews_url' ), sbc_mod( 'instagram' ), sbc_mod( 'facebook' ) ) ) ),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	},
	20
);

/**
 * City landing pages (template "City landing page"): the city comes from the page title,
 * e.g. "Photo Booth Rental in Frisco" → Frisco.
 */
function sbc_is_city_page() {
	return is_page() && 'templates/city-landing.php' === get_page_template_slug( get_queried_object_id() );
}

function sbc_city_name( $post_id = 0 ) {
	$title = get_the_title( $post_id ? $post_id : get_queried_object_id() );
	if ( preg_match( '/\bin\s+(.+)$/i', $title, $m ) ) {
		return trim( preg_replace( '/,?\s*(TX|Texas)$/i', '', $m[1] ) );
	}
	return 'Dallas–Fort Worth';
}

/**
 * Local neighborhoods and landmarks shown as chips in a city page's hero. Add a city by
 * extending this list (key = lowercase city name). Unknown cities fall back to the other
 * cities in "Areas served".
 */
function sbc_city_spots( $city ) {
	$spots = array(
		'frisco'      => array( 'The Star', 'Frisco Square', 'Stonebriar', 'Starwood', 'Phillips Creek Ranch' ),
		'plano'       => array( 'Legacy West', 'Historic Downtown Plano', 'Willow Bend', 'Shops at Legacy', 'Granite Park' ),
		'mckinney'    => array( 'Historic Downtown', 'Adriatica Village', 'Craig Ranch', 'Stonebridge Ranch', 'Tucker Hill' ),
		'dallas'      => array( 'Uptown', 'Deep Ellum', 'Bishop Arts', 'Design District', 'Downtown' ),
		'fort worth'  => array( 'Sundance Square', 'The Stockyards', 'Near Southside', 'Cultural District', 'Clearfork' ),
		'arlington'   => array( 'Entertainment District', 'Downtown Arlington', 'Viridian', 'UT Arlington' ),
		'irving'      => array( 'Las Colinas', 'Mandalay Canal', 'Valley Ranch', 'Irving Convention Center' ),
		'richardson'  => array( 'CityLine', 'Galatyn Park', 'Canyon Creek', 'Downtown Richardson' ),
		'denton'      => array( 'Denton Square', 'Rayzor Ranch', 'UNT', 'TWU' ),
		'allen'       => array( 'Watters Creek', 'Twin Creeks', 'Allen Station', 'Montgomery Farm' ),
		'prosper'     => array( 'Windsong Ranch', 'Downtown Prosper', 'Star Trail', 'Gates of Prosper' ),
		'southlake'   => array( 'Southlake Town Square', 'Timarron', 'Carillon', 'Westlake' ),
		'carrollton'  => array( 'Downtown Carrollton', 'Castle Hills', 'Josey Ranch', 'Indian Creek' ),
		'garland'     => array( 'Downtown Garland', 'Firewheel', 'Lake Ray Hubbard', 'Duck Creek' ),
	);
	$key = strtolower( trim( $city ) );
	if ( isset( $spots[ $key ] ) ) {
		$list = $spots[ $key ];
	} else {
		$areas = array_filter( array_map( 'trim', preg_split( '/,|\band\b/', str_replace( '.', '', sbc_mod( 'areas' ) ) ) ) );
		$list  = array_values(
			array_filter(
				$areas,
				function ( $a ) use ( $key ) {
					return strtolower( $a ) !== $key;
				}
			)
		);
		$list = array_slice( $list, 0, 6 );
	}
	return apply_filters( 'sbc_city_spots', $list, $city );
}

/**
 * Photos for a city page's photo-strip print: the page's featured image first (if set), then
 * homepage hero photos, rotated by city so each city page shows a different set.
 */
function sbc_city_photos( $city, $count = 3 ) {
	$pool = array();
	foreach ( array_filter( array_map( 'absint', explode( ',', (string) sbc_mod( 'hero_photos' ) ) ) ) as $id ) {
		if ( wp_attachment_is_image( $id ) && ! in_array( $id, sbc_removed_photo_ids(), true ) ) {
			$pool[] = $id;
		}
	}
	if ( $pool ) {
		$offset = abs( crc32( strtolower( $city ) ) ) % count( $pool );
		$pool   = array_merge( array_slice( $pool, $offset ), array_slice( $pool, 0, $offset ) );
	}
	$thumb = get_post_thumbnail_id();
	if ( $thumb ) {
		$pool = array_values( array_diff( $pool, array( $thumb ) ) );
		array_unshift( $pool, (int) $thumb );
	}
	return array_slice( $pool, 0, $count );
}
