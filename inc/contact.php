<?php
/**
 * Contact page (/contact/): creates the page once, and handles its form.
 *
 * The form posts to admin-post.php and emails the team (Customizer → Contact email), with
 * Reply-To set to the visitor. No nonce: pages are fully cached by LiteSpeed, so nonces would
 * go stale. Spam is held back by a hidden honeypot field, a minimum fill time and a per-visitor
 * hourly limit instead.
 */

defined( 'ABSPATH' ) || exit;

/** Topics in the form's "What's this about?" menu. Keys are what links pass in ?topic=. */
function sbc_contact_topics() {
	return array(
		'booking' => __( 'Booking a photo booth', 'socialboothco' ),
		'rental'  => __( 'Renting our gear', 'socialboothco' ),
		'other'   => __( 'Something else', 'socialboothco' ),
	);
}

/** Link to the contact page, optionally with a topic picked in the form. */
function sbc_contact_url( $topic = '' ) {
	$url = home_url( '/contact/' );
	return $topic ? add_query_arg( 'topic', $topic, $url ) . '#contact-form' : $url;
}

/** Create the Contact page the first time an admin loads the dashboard (the theme draws its content). */
add_action( 'admin_init', 'sbc_contact_create_page' );
function sbc_contact_create_page() {
	if ( get_option( 'sbc_contact_page_created' ) || ! current_user_can( 'publish_pages' ) ) {
		return;
	}
	if ( ! get_page_by_path( 'contact' ) ) {
		wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => 'Contact',
				'post_name'   => 'contact',
			)
		);
	}
	update_option( 'sbc_contact_page_created', 1, false );
}

add_action( 'admin_post_nopriv_sbc_contact', 'sbc_contact_submit' );
add_action( 'admin_post_sbc_contact', 'sbc_contact_submit' );
function sbc_contact_submit() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- see the note at the top of this file.
	$field = function ( $key, $textarea = false ) {
		$value = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		return $textarea ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
	};
	$topics  = sbc_contact_topics();
	$topic   = $field( 'topic' );
	$topic   = isset( $topics[ $topic ] ) ? $topic : 'other';
	$back    = function ( $status ) use ( $topic ) {
		wp_safe_redirect( add_query_arg( array( 'sent' => $status, 'topic' => $topic ), home_url( '/contact/' ) ) . '#contact-form' );
		exit;
	};

	// Bots: the honeypot is filled, or the form came back faster than a person could type it.
	$elapsed = $field( 'sbc_t' ); // Seconds on the page before sending, filled in by theme.js.
	if ( '' !== $field( 'website' ) || ( '' !== $elapsed && (int) $elapsed < 3 ) ) {
		$back( 1 ); // Look successful so they don't retry.
	}

	$name    = mb_substr( $field( 'name' ), 0, 120 );
	$email   = sanitize_email( $field( 'email' ) );
	$phone   = mb_substr( $field( 'phone' ), 0, 40 );
	$date    = mb_substr( $field( 'event_date' ), 0, 40 );
	$message = mb_substr( $field( 'message', true ), 0, 5000 );
	// phpcs:enable

	if ( '' === $name || ! is_email( $email ) || '' === trim( $message ) ) {
		$back( 'missing' );
	}

	$ip_key = 'sbc_contact_' . md5( ( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '' ) . wp_salt( 'nonce' ) );
	$count  = (int) get_transient( $ip_key );
	if ( $count >= 5 ) {
		$back( 'limit' );
	}
	set_transient( $ip_key, $count + 1, HOUR_IN_SECONDS );

	$body = implode(
		"\n",
		array_filter(
			array(
				'Topic: ' . $topics[ $topic ],
				'Name: ' . $name,
				'Email: ' . $email,
				$phone ? 'Phone: ' . $phone : null,
				$date ? 'Event date: ' . $date : null,
				'',
				$message,
				'',
				'Sent from the contact page on ' . home_url( '/' ),
			),
			function ( $line ) {
				return null !== $line; // Keep the blank spacer lines, drop unanswered optional fields.
			}
		)
	);
	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . str_replace( array( "\r", "\n", '<', '>' ), '', $name ) . ' <' . $email . '>',
	);
	$subject = sprintf( 'Website contact: %s from %s', $topics[ $topic ], $name );

	$back( wp_mail( sbc_mod( 'email' ), $subject, $body, $headers ) ? 1 : 'error' );
}
