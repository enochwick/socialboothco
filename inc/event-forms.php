<?php
/**
 * Event customization forms (sent to clients after they book).
 *
 * These pages were Elementor Canvas pages with no site header or footer. The theme now draws them
 * itself (templates/event-form.php): the usual header, page intro, the Forminator form restyled to
 * match the site, and a side card with what we use the answers for. The form itself, its emails and
 * its saved entries stay in Forminator, unchanged.
 *
 * To give another form page the same treatment, add its slug and Forminator form ID here.
 */

defined( 'ABSPATH' ) || exit;

function sbc_event_form_pages() {
	return array(
		'event-customization-form-premium' => 4709,
	);
}

/**
 * Forminator form ID for the current page, or 0 when it isn't one of the event form pages.
 */
function sbc_event_form_id() {
	if ( ! is_page() ) {
		return 0;
	}
	$slug  = get_post_field( 'post_name', get_queried_object_id() );
	$pages = sbc_event_form_pages();
	return isset( $pages[ $slug ] ) ? (int) $pages[ $slug ] : 0;
}

/**
 * True when the theme draws the current page as an event form. If Forminator is off, the page
 * falls back to its Elementor version.
 */
function sbc_is_event_form_page() {
	return sbc_event_form_id() && shortcode_exists( 'forminator_form' );
}

/**
 * The pages still carry the Elementor Canvas template setting, which Elementor applies on
 * template_include. Run later so the theme's template wins. Turning the theme off (or removing the
 * slug above) brings the Elementor version straight back.
 */
add_filter( 'template_include', 'sbc_event_form_template', 99 );
function sbc_event_form_template( $template ) {
	if ( ! sbc_is_event_form_page() ) {
		return $template;
	}
	$file = get_template_directory() . '/templates/event-form.php';
	return file_exists( $file ) ? $file : $template;
}
