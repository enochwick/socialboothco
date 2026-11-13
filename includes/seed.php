<?php
/**
 * One-time import of the categories used by the old Premium Addons gallery
 * (attachment ID => category slugs, in the original display order).
 */

defined( 'ABSPATH' ) || exit;

// Runs once if activation happened without the hook firing (e.g. activated by a deploy tool).
add_action(
	'admin_init',
	function () {
		if ( ! get_option( 'sbcg_seeded' ) && current_user_can( 'manage_options' ) ) {
			sbcg_seed();
		}
	}
);

function sbcg_seed() {
	if ( get_option( 'sbcg_seeded' ) ) {
		return;
	}

	foreach ( sbcg_default_terms() as $slug => $name ) {
		if ( ! term_exists( $slug, SBCG_TAX ) ) {
			wp_insert_term( $name, SBCG_TAX, array( 'slug' => $slug ) );
		}
	}

	$order = array();
	foreach ( sbcg_seed_items() as $item ) {
		list( $id, $slugs ) = $item;
		if ( 'attachment' !== get_post_type( $id ) ) {
			continue;
		}
		$term_ids = array();
		foreach ( $slugs as $slug ) {
			$term = get_term_by( 'slug', $slug, SBCG_TAX );
			if ( $term ) {
				$term_ids[] = (int) $term->term_id;
			}
		}
		wp_set_object_terms( $id, $term_ids, SBCG_TAX, true );
		$order[] = $id;
	}

	update_option( 'sbcg_order', $order, false );
	update_option( 'sbcg_seeded', SBCG_VERSION, false );
}

function sbcg_seed_items() {
	return array(
		array( 2376, array( 'parties' ) ), // Salsa-2-Launch-Marketing-Materials-Captures-088.jpeg
		array( 2311, array( 'prints' ) ), // SocialBoothCo-03.jpg
		array( 2312, array( 'parties' ) ), // SocialBoothCo-06-scaled.jpeg
		array( 2483, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-3.jpg
		array( 2314, array( 'parties' ) ), // SocialBoothCo-011-scaled.jpeg
		array( 2363, array( 'prints' ) ), // Salsa-Overlays-Optimize.gif
		array( 2315, array( 'parties' ) ), // SocialBoothCo-013-scaled.jpeg
		array( 2304, array( 'weddings' ) ), // SocialBoothCo22-scaled.jpeg
		array( 2316, array( 'parties' ) ), // SocialBoothCo-017.jpeg
		array( 2375, array( 'weddings' ) ), // SalsaPrintTemplates001.jpg
		array( 2390, array( 'corporate' ) ), // Salsa-2-Launch-Marketing-Materials-Professional-Photos-173-scaled.jpeg
		array( 2395, array( 'corporate' ) ), // SalsaPromoHighRes356-scaled.jpeg
		array( 2396, array( 'corporate' ) ), // SalsaPromoHighRes394.jpeg
		array( 2400, array( 'parties' ) ), // SocialBothCo-089-1-scaled.jpeg
		array( 2403, array( 'parties' ) ), // SalsaPromoHighRes431-scaled.jpg
		array( 2402, array( 'parties' ) ), // Salsa-2-Launch-Marketing-Materials-Captures-050-scaled.jpeg
		array( 2415, array( 'backdrops' ) ), // BreezeProduct.jpg
		array( 2447, array( 'prints' ) ), // Marketing-Material-Photos-March-2022-Web-Res-47.jpg
		array( 2425, array( 'backdrops' ) ), // FruityHighRes12-scaled.jpeg
		array( 2438, array( 'backdrops' ) ), // WatermelonHighRes018-scaled.jpeg
		array( 2437, array( 'backdrops' ) ), // WatermelonHighRes003-scaled.jpeg
		array( 2462, array( 'prints' ) ), // 2019Q4-Salsa-Overlays_Mock-Up-17-scaled.jpg
		array( 2432, array( 'backdrops' ) ), // SwirlHighRes15-scaled.jpeg
		array( 2446, array( 'prints', 'weddings' ) ), // Marketing-Material-Photos-March-2022-Web-Res-43.jpg
		array( 2451, array( 'prints' ) ), // Marketing-Material-Photos-March-2022-Web-Res-23.jpg
		array( 2464, array( 'prints' ) ), // 2020Q3-Salsa-Overlays_Mock-Up-3.jpg
		array( 2449, array( 'prints' ) ), // Marketing-Material-Photos-March-2022-Web-Res-29.jpg
		array( 2450, array( 'prints' ) ), // Marketing-Material-Photos-March-2022-Web-Res-17.jpg
		array( 2419, array( 'backdrops' ) ), // ChromaProduct.jpg
		array( 2467, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-1.jpg
		array( 2452, array( 'prints', 'weddings' ) ), // Marketing-Material-Photos-March-2022-Web-Res-57.jpg
		array( 2421, array( 'backdrops' ) ), // DowntownHighRes005-scaled.jpeg
		array( 2454, array( 'prints' ) ), // Spring-Mock-Up-3-scaled.jpg
		array( 2453, array( 'prints' ) ), // Spring-Mock-Up-2-scaled.jpg
		array( 2417, array( 'backdrops' ) ), // BubblegumHighRes5-scaled.jpeg
		array( 2455, array( 'prints' ) ), // Super-Bowl-Mock-Up-GIF.gif
		array( 2463, array( 'prints' ) ), // 2020Q2-Salsa-Overlays_Mock-Up-7-scaled.jpg
		array( 2426, array( 'backdrops' ) ), // FruityHighRes17.jpeg
		array( 2468, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-4.jpg
		array( 2465, array( 'prints' ) ), // 2020Q3-Salsa-Overlays_Mock-Up-1.jpg
		array( 2469, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-6.jpg
		array( 2470, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-7.jpg
		array( 2471, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-13.jpg
		array( 2472, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-15.jpg
		array( 2473, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-17.jpg
		array( 2474, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-19.jpg
		array( 2475, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-20.jpg
		array( 2476, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-16.jpg
		array( 2477, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-12.jpg
		array( 2478, array( 'prints' ) ), // 2020Q4-Salsa-Overlays_Mock-Up-5-scaled.jpg
		array( 2479, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-14.jpg
		array( 2484, array( 'prints' ) ), // Salsa-Print-Templates_Mock-Up-9.jpg
		array( 2500, array( 'setups' ) ), // SalsaPromoHighRes147-scaled.jpeg
		array( 2507, array( 'setups' ) ), // SalsaPromoHighRes427-scaled.jpg
		array( 2495, array( 'setups' ) ), // Salsa-2-Launch-Marketing-Materials-Professional-Photos-162-scaled.jpeg
		array( 2504, array( 'setups' ) ), // SalsaPromoHighRes316-scaled.jpeg
		array( 2506, array( 'setups' ) ), // SalsaPromoHighRes425-scaled.jpg
		array( 2505, array( 'setups' ) ), // SalsaPromoHighRes361-scaled.jpeg
		array( 2503, array( 'setups' ) ), // SalsaPromoHighRes276-scaled.jpeg
		array( 2508, array( 'setups' ) ), // Social-Booth-Co-1-scaled.jpg
		array( 2497, array( 'setups' ) ), // Salsa-2-Launch-Marketing-Materials-Professional-Photos-204.jpeg
		array( 2496, array( 'setups' ) ), // Salsa-2-Launch-Marketing-Materials-Professional-Photos-195.jpeg
		array( 2502, array( 'setups' ) ), // SalsaPromoHighRes245-scaled.jpeg
		array( 2501, array( 'setups' ) ), // SalsaPromoHighRes181-scaled.jpeg
		array( 2499, array( 'setups' ) ), // SalsaPromoHighRes002-scaled.jpeg
		array( 2498, array( 'setups' ) ), // Salsa-2-Launch-Marketing-Materials-Professional-Photos-207.jpeg
		array( 2494, array( 'setups' ) ), // Salsa-2-Launch-Marketing-Materials-Professional-Photos-124-scaled.jpeg
		array( 2493, array( 'setups' ) ), // Salsa-2-Launch-Marketing-Materials-Professional-Photos-044-scaled.jpeg
		array( 2492, array( 'setups' ) ), // PBSCO-BW_web-106.jpg
		array( 2540, array( 'weddings' ) ), // SalsaPromoHighRes206-scaled.jpeg
		array( 2541, array( 'quinceanera' ) ), // SalsaPromoHighRes152-scaled.jpeg
		array( 2542, array( 'quinceanera' ) ), // SalsaPromoHighRes153-scaled.jpeg
		array( 2543, array( 'quinceanera' ) ), // SalsaPromoHighRes154-1.jpeg
		array( 2545, array( 'quinceanera' ) ), // SalsaPromoHighRes156-scaled.jpeg
		array( 2546, array( 'quinceanera', 'setups', 'backdrops' ) ), // SalsaPromoHighRes157-scaled.jpeg
	);
}
