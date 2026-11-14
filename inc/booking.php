<?php
/**
 * Amelia booking popups.
 *
 * Any element with the class `sbc-book` opens Amelia's step-by-step booking form for the
 * booking category; `sbc-consult` opens the consultation category. Both are Amelia's own
 * popups (trigger + in_dialog), so nothing else is needed. The buttons are real links to
 * the fallback page, so they still work if Amelia is deactivated.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_footer', 'sbc_booking_forms', 5 );
function sbc_booking_forms() {
	if ( ! shortcode_exists( 'ameliastepbooking' ) ) {
		return;
	}
	// Elementor canvas pages (e.g. Check Availability) already carry their own form.
	if ( is_page_template( 'elementor_canvas' ) ) {
		return;
	}

	$forms = array(
		'sbc-book'    => (int) sbc_mod( 'booking_category' ),
		'sbc-consult' => (int) sbc_mod( 'consult_category' ),
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
	$on = shortcode_exists( 'ameliastepbooking' ) && ! is_page_template( 'elementor_canvas' );
	echo '<script>window.sbcBooking=' . ( $on ? 'true' : 'false' ) . ';</script>';
}

function sbc_booking_url() {
	$url = sbc_mod( 'booking_fallback' );
	return 0 === strpos( $url, 'http' ) ? $url : home_url( $url );
}

function sbc_consult_url() {
	return 'mailto:' . antispambot( sbc_mod( 'email' ) ) . '?subject=' . rawurlencode( 'Photo booth consultation' );
}
