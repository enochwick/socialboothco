<?php
/**
 * Editable site content. Every value can be changed under Appearance → Customize → Social Booth Co.
 * The defaults below are the current live copy, so the theme looks complete the moment it's activated.
 *
 * List fields use one item per line with parts separated by " | ".
 */

defined( 'ABSPATH' ) || exit;

function sbc_defaults() {
	return array(
		// Contact.
		'phone'            => '+1 469 269 2793',
		'email'            => 'memories@socialboothco.com',
		'areas'            => 'Dallas, Fort Worth, Plano, Frisco, McKinney, Arlington, Irving, Garland, Richardson, Carrollton and Denton.',
		'about'            => 'A modern photo booth experience designed to bring energy, connection and unforgettable moments to your event.',
		'instagram'        => '',
		'facebook'         => '',

		// Booking (Amelia).
		'booking_category' => '1',
		'booking_fallback' => '/check-availability/',

		// Home: hero.
		'hero_tagline'     => 'Serving the Dallas–Fort Worth Metroplex',
		'hero_title'       => 'The photo booth your guests won’t stop talking about.',
		'hero_lede'        => 'Interactive photo booth experiences for Dallas–Fort Worth events, with instant sharing, custom designs and studio lighting. Packages from $495.',
		'hero_photos'      => '2915, 2916, 2877, 2400, 2507, 2882, 2883, 2856, 2540, 2881, 2861, 2876',

		// Home: features.
		'features_title'   => 'A photo booth experience, not just a booth.',
		'features'         => "Instant sharing | Photos, GIFs and videos reach guests by text, email or AirDrop, so they can share the fun right away.\nCustom design | Every event gets a photo overlay designed around your theme, colors or brand.\nProfessional lighting | Studio-quality light keeps every photo bright, flattering and camera-ready.\nLive event gallery | Every shot lands in an online gallery you can view, share and download after the event.",

		// Home: packages.
		'packages_title'   => 'Choose your photo booth experience.',
		'packages_intro'   => 'Simple packages, elevated experiences. Check your date and we’ll send a custom quote for your celebration.',
		'packages_note'    => 'Every package includes an attendant, professional lighting, a custom overlay, setup and breakdown.',
		'pkg_price_label'  => 'Starting at',
		// Wording matches the original site's packages.
		'pkg1_name'        => 'The Social Booth',
		'pkg1_price'       => '$495',
		'pkg1_best'        => 'Best for: intimate events, birthday parties, showers, and casual celebrations',
		'pkg1_features'    => "3 Hours of Booth Experience\nPhotos, GIFs, and Boomerangs\nInstant share via Email, SMS, and AirDrop\nLive Event Gallery\nCustom Photo Overlay (Names, Date, Branding)\nProfessional Lighting\nBooth Attendant\nSetup and Breakdown",
		'pkg1_note'        => '*Instant prints available as a $150 add-on',
		'pkg2_name'        => 'The Party Booth',
		'pkg2_price'       => '$650',
		'pkg2_best'        => 'Best for: Weddings, larger parties, and events where guests want instant prints',
		'pkg2_features'    => "Everything in The Social Booth, plus:\nUnlimited Instant Prints\nStandard Backdrop Included\nFully Custom-Branded Photo & Print Design\nVideo Capture\nProp Collection",
		'pkg2_note'        => '*Add-ons Available.',
		'pkg3_name'        => 'Premier Experience',
		'pkg3_price'       => '$950',
		'pkg3_best'        => 'Best for: Weddings, corporate events, and premium celebrations',
		'pkg3_features'    => "Everything in The Party Booth, plus:\n5 Hours of Booth Experience\nPremium Backdrop Upgrade (Sequin or Arch)\nGuest Book Experience\nSlow-motion Video\nPremium Event Customization\nPriority Planning Support",
		'pkg3_note'        => '*Add-ons Available.',
		'pkg_popular'      => '2',
		// Offers shown below the packages. One per line: Title | Details. {holiday_year} becomes the
		// upcoming holiday season's year (this year until October 1, then next year).
		'offers'           => "Weekday & Sunday events | 10% off any package for events Monday–Thursday or Sunday.\nHoliday party early bird | Book your {holiday_year} holiday party by October 1 and save 10%.",
		'upgrades'         => "Instant prints | $150 | Unlimited instant prints for The Social Booth.\nPremium backdrop | From $95 | Sequin walls, arches and luxury textures.\nExtra booth time | $120/hr | Keep the booth running so guests can capture even more.\nGuest book experience | $125 | Guests add prints and messages to a keepsake book you take home. Included with Premier Experience.\nCustom event branding | Ask us | Fully customized overlays, templates and screen experiences.\nGuest survey insights | Ask us | Collect guest feedback and engagement data at corporate activations.",

		// Home: events.
		'events_title'     => 'Photo booths for weddings, corporate events and parties',
		'events_intro'     => 'Whether it’s 50 guests or 500, we bring the energy.',
		'event1'           => 'Weddings | Romantic, timeless, unforgettable.',
		'event1_photo'     => 2540,
		'event2'           => 'Corporate events | Branded, professional, engaging.',
		'event2_photo'     => 2881,
		'event3'           => 'Birthday parties | Fun, high-energy, share-worthy.',
		'event3_photo'     => 2915,
		'event4'           => 'Graduations and private celebrations | Milestone moments, proud smiles.',
		'event4_photo'     => 2304,

		// Home: moments from our events (photo grid; carousel on phones).
		'moments_title'    => 'Moments from our events',
		'moments_photos'   => '4981, 5072, 5074, 5075, 4976, 5077, 5079, 5081',

		// Reviews (Thumbtack). Shown below the packages on the homepage and on pages that list packages.
		'reviews_title'    => 'Want to hear from past clients?',
		'reviews_text'     => 'See our reviews on Thumbtack.',
		'reviews_url'      => 'https://www.thumbtack.com/tx/frisco/photo-booth-rentals/social-booth-co/service/573233401389367325',

		// FAQ (used on the FAQ page; the first four also appear on the homepage).
		'faq'              => "What types of events do you service?\nWe provide photo booth experiences for weddings, corporate events, private parties, quinceañeras, brand activations and special celebrations throughout the Dallas–Fort Worth area. If your event isn’t on that list, email us at memories@socialboothco.com and we’ll make it happen.\n\nHow far in advance should I book?\nWe recommend booking 3–6 weeks in advance, especially during peak wedding and holiday seasons. Reach out anytime and we’ll check availability for your date.\n\nHow much space do you need?\nOur setup is compact and flexible. A space of about 8×8 feet gives guests room to pose comfortably.\n\nWhat is required to reserve a date?\nA signed agreement and a deposit secure your event date. The remaining balance is due before the event.\n\nDo you require a deposit to book?\nYes. A 30% deposit reserves your event date, with the remaining balance due 7 days before the event.\n\nDo you provide an attendant?\nYes. A professional booth attendant handles setup, helps guests and keeps everything running smoothly throughout your event.\n\nDo you offer printing?\nYes. Instant photo printing is available as an add-on or included with select packages. Guests receive high-quality prints within seconds.\n\nCan you customize the photo design?\nAbsolutely. We create custom overlays and photo templates that match your event theme, wedding design or brand identity.\n\nWhat types of photos can guests take?\nPhotos, GIFs, boomerangs and short videos, so there’s something fun for every guest.\n\nIs there an online gallery after the event?\nYes. After the event you’ll get access to the complete digital gallery, where every photo, GIF and video can be viewed and downloaded.\n\nDo you travel outside Dallas–Fort Worth?\nWe currently serve Dallas, Fort Worth and the surrounding DFW areas, and we’re excited to expand to more locations soon.\n\nHow long does setup take?\nWe typically arrive 45–60 minutes before the event to set up and test the booth.",

		// Closing call to action (all pages).
		'closing_title'    => 'Secure your date before it’s gone.',
		'closing_text'     => 'Tell us about your event and we’ll confirm availability within 24 hours.',
	);
}

/**
 * Get one content value.
 */
function sbc_mod( $key ) {
	$defaults = sbc_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	$value    = get_theme_mod( 'sbc_' . $key, $default );
	$value    = ( '' === $value || null === $value ) ? $default : $value;
	return is_string( $value ) ? sbc_replace_old_phone( $value ) : $value;
}

/**
 * Split a "one item per line, parts separated by |" field into arrays.
 */
function sbc_lines( $key, $parts = 2 ) {
	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) sbc_mod( $key ) ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$cells = array_map( 'trim', explode( '|', $line ) );
		$rows[] = array_pad( array_slice( $cells, 0, $parts ), $parts, '' );
	}
	return $rows;
}

/**
 * FAQ: blocks separated by a blank line; first line is the question, the rest is the answer.
 */
function sbc_faq_items() {
	$items  = array();
	$blocks = preg_split( '/\R\s*\R/', trim( (string) sbc_mod( 'faq' ) ) );
	foreach ( $blocks as $block ) {
		$lines    = preg_split( '/\R/', trim( $block ) );
		$question = trim( array_shift( $lines ) );
		$answer   = trim( implode( "\n", $lines ) );
		if ( '' !== $question && '' !== $answer ) {
			$items[] = array( $question, $answer );
		}
	}
	return $items;
}

function sbc_packages() {
	$packages = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$features = array_filter( array_map( 'trim', preg_split( '/\R/', (string) sbc_mod( "pkg{$i}_features" ) ) ) );
		$packages[] = array(
			'name'     => sbc_mod( "pkg{$i}_name" ),
			'price'    => sbc_mod( "pkg{$i}_price" ),
			'best'     => sbc_mod( "pkg{$i}_best" ),
			'features' => array_values( $features ),
			'note'     => sbc_mod( "pkg{$i}_note" ),
			'popular'  => (string) $i === (string) sbc_mod( 'pkg_popular' ),
		);
	}
	return $packages;
}

/**
 * Photos removed from the site everywhere (Media Library IDs): homepage, gallery and page content.
 * 5076 = social-booth-co-event-05.
 */
function sbc_removed_photo_ids() {
	return array( 5076 );
}

/**
 * The old 323 number becomes the current one, wherever it still appears (saved settings,
 * page content built in WordPress or Elementor).
 */
function sbc_replace_old_phone( $text ) {
	if ( false === strpos( $text, '323' ) ) {
		return $text;
	}
	$text = preg_replace( '/tel:\+?1?\D{0,2}323\D{0,2}961\D{0,2}5519/', 'tel:+14692692793', $text );
	return preg_replace( '/(?<!\d)(?:\+?1[\s.\-]?)?\(?323\)?[\s.\-]?961[\s.\-]?5519(?!\d)/', '+1 469 269 2793', $text );
}

/**
 * One-time pricing update (November 2026): 3-hour starter at $495, unlimited prints in
 * The Party Booth, guest book in Premier. Saved Customizer values for these fields would
 * otherwise keep showing the old packages, so they're cleared once and the new defaults apply.
 * Edits made in the Customizer after this keep working as usual.
 */
add_action( 'after_setup_theme', 'sbc_migrate_pricing_2026' );
function sbc_migrate_pricing_2026() {
	if ( get_option( 'sbc_pricing_2026_11' ) ) {
		return;
	}
	foreach ( array( 'pkg1_price', 'pkg1_features', 'pkg1_note', 'pkg2_features', 'pkg3_features', 'upgrades' ) as $key ) {
		remove_theme_mod( 'sbc_' . $key );
	}
	$lede = get_theme_mod( 'sbc_hero_lede' );
	if ( is_string( $lede ) && false !== strpos( $lede, '$450' ) ) {
		set_theme_mod( 'sbc_hero_lede', str_replace( '$450', '$495', $lede ) );
	}
	update_option( 'sbc_pricing_2026_11', 1, false );
}

/**
 * Year of the upcoming holiday season for the early-bird offer: this year until October 1,
 * then next year.
 */
function sbc_holiday_year() {
	$year = (int) wp_date( 'Y' );
	return (int) wp_date( 'md' ) <= 1001 ? $year : $year + 1;
}
