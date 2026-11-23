<?php
/**
 * Amelia booking popups.
 *
 * Any element with the class `sbc-book` (every "Check your date" button) opens Amelia's
 * step-by-step booking form for the booking category, as Amelia's own popup. The buttons are
 * real links to the fallback page, so they still work if Amelia is deactivated.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_footer', 'sbc_booking_forms', 5 );
function sbc_booking_forms() {
	if ( ! shortcode_exists( 'ameliastepbooking' ) ) {
		return;
	}
	// Elementor canvas pages (e.g. Check Availability) already carry their own form.
	if ( is_page_template( 'elementor_canvas' ) && ! sbc_is_theme_layout() ) {
		return;
	}

	$forms = array(
		'sbc-book' => (int) sbc_mod( 'booking_category' ),
	);

	echo '<div class="sbc-amelia" hidden>';
	foreach ( $forms as $trigger => $category ) {
		$shortcode = sprintf( '[ameliastepbooking trigger="%s" trigger_type="class" in_dialog="1"%s]', $trigger, $category ? ' category="' . $category . '"' : '' );
		echo do_shortcode( $shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput -- Amelia output.
	}
	echo '</div>';
}

/**
 * Tell the front end whether Amelia popups are available, so booking links only skip
 * navigation when a popup will actually open.
 */
add_action( 'wp_footer', 'sbc_booking_flag', 4 );
function sbc_booking_flag() {
	$on = shortcode_exists( 'ameliastepbooking' ) && ! ( is_page_template( 'elementor_canvas' ) && ! sbc_is_theme_layout() );
	echo '<script>window.sbcBooking=' . ( $on ? 'true' : 'false' ) . ';</script>';
}

function sbc_booking_url() {
	$url = sbc_mod( 'booking_fallback' );
	return 0 === strpos( $url, 'http' ) ? $url : home_url( $url );
}

function sbc_consult_url() {
	return 'mailto:' . antispambot( sbc_mod( 'email' ) ) . '?subject=' . rawurlencode( 'Photo booth consultation' );
}

/**
 * One-time Amelia update (November 2026). The homepage moved The Social Booth to $495 for 3 hours,
 * but Amelia keeps its own copy of each package, so the booking popup still offered it at $450 for
 * 2 hours. This brings Amelia in line once. Only rows still on the old values are touched, so
 * anything changed in Amelia → Services afterwards is never overwritten.
 */
add_action( 'init', 'sbc_sync_amelia_pricing_2026' );
function sbc_sync_amelia_pricing_2026() {
	if ( get_option( 'sbc_amelia_pricing_2026_11' ) ) {
		return;
	}
	global $wpdb;
	$services  = $wpdb->prefix . 'amelia_services';
	$providers = $wpdb->prefix . 'amelia_providers_to_services';
	if ( $services !== $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $services ) ) ) {
		return; // Amelia isn't installed (yet); try again on a later request.
	}
	$name = sbc_mod( 'pkg1_name' ) ? sbc_mod( 'pkg1_name' ) : 'The Social Booth';

	// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table names come from $wpdb->prefix.
	$wpdb->query( $wpdb->prepare( "UPDATE {$services} SET price = %f WHERE name = %s AND price = %f", 495, $name, 450 ) );
	$wpdb->query( $wpdb->prepare( "UPDATE {$services} SET duration = %d WHERE name = %s AND duration = %d", 3 * HOUR_IN_SECONDS, $name, 2 * HOUR_IN_SECONDS ) );
	// Amelia can also hold a per-employee price for each service, which wins in the booking form.
	if ( $providers === $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $providers ) ) ) {
		$wpdb->query( $wpdb->prepare( "UPDATE {$providers} p INNER JOIN {$services} s ON s.id = p.serviceId SET p.price = %f WHERE s.name = %s AND p.price = %f", 495, $name, 450 ) );
	}
	// phpcs:enable

	update_option( 'sbc_amelia_pricing_2026_11', 1, false );
	do_action( 'litespeed_purge_all' ); // Cached pages pick up the new price straight away.
}
