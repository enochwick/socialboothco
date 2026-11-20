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

/** Package names for the booking menu, from Customizer → Packages and prices (the weekday card only if it's on). */
function sbc_contact_packages() {
	return array_values( array_filter( array_map( 'trim', array( sbc_mod( 'pkg1_name' ), sbc_mod( 'pkg2_name' ), sbc_mod( 'pkg3_name' ), sbc_mod( 'pkg4_name' ) ) ) ) );
}

/** Gear names for the rental menu, from Customizer → Homepage: gear. */
function sbc_contact_gear() {
	$names = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) sbc_mod( 'gear_items' ) ) as $line ) {
		$name = trim( strtok( $line, '|' ) );
		if ( '' !== $name ) {
			$names[] = $name;
		}
	}
	return $names;
}

/** "Not sure" choices at the end of each menu. */
function sbc_contact_unsure( $topic ) {
	return 'rental' === $topic ? __( 'Not sure yet, help me choose', 'socialboothco' ) : __( 'Not sure yet, help me pick a package', 'socialboothco' );
}

/**
 * Add "Contact" to the end of the main menu (header and mobile) when the menu assigned under
 * Appearance → Menus doesn't already link to the contact page. Remove it by adding your own link.
 */
add_filter( 'wp_nav_menu_items', 'sbc_contact_menu_item', 10, 2 );
function sbc_contact_menu_item( $items, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location || false !== strpos( $items, '/contact/' ) ) {
		return $items;
	}
	$current = is_page( 'contact' );
	return $items . sprintf(
		'<li class="menu-item menu-item-contact%s"><a href="%s"%s>%s</a></li>',
		$current ? ' current-menu-item' : '',
		esc_url( home_url( '/contact/' ) ),
		$current ? ' aria-current="page"' : '',
		esc_html__( 'Contact', 'socialboothco' )
	);
}

/** Earliest event date the form accepts: three days from today, in the site's time zone. */
function sbc_contact_min_date() {
	return wp_date( 'Y-m-d', time() + 3 * DAY_IN_SECONDS );
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

	// Which package or gear they're asking about. Only values from the menus are kept.
	$choice = '';
	if ( 'booking' === $topic || 'rental' === $topic ) {
		$options = array_merge( 'rental' === $topic ? sbc_contact_gear() : sbc_contact_packages(), array( sbc_contact_unsure( $topic ) ) );
		$picked  = $field( 'booking' === $topic ? 'package' : 'gear' );
		$choice  = in_array( $picked, $options, true ) ? $picked : '';
	}
	// phpcs:enable

	if ( '' === $name || ! is_email( $email ) || strlen( preg_replace( '/\D/', '', $phone ) ) < 7 || '' === trim( $message ) ) {
		$back( 'missing' );
	}
	// Event date must be at least three days out (the browser enforces it too). One day of slack
	// covers visitors in other time zones.
	if ( $date && ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) || $date < wp_date( 'Y-m-d', time() + 2 * DAY_IN_SECONDS ) ) ) {
		$back( 'date' );
	}

	$ip_key = 'sbc_contact_' . md5( ( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '' ) . wp_salt( 'nonce' ) );
	$count  = (int) get_transient( $ip_key );
	if ( $count >= 5 ) {
		$back( 'limit' );
	}
	set_transient( $ip_key, $count + 1, HOUR_IN_SECONDS );

	// Readable extras for the email: "(323) 961-3319" and "Tuesday, November 17, 2026".
	$digits     = preg_replace( '/\D/', '', $phone );
	$phone_nice = preg_match( '/^1?(\d{3})(\d{3})(\d{4})$/', $digits, $m ) ? "($m[1]) $m[2]-$m[3]" : $phone;
	$date_time  = $date ? strtotime( $date ) : false;
	$date_nice  = $date_time ? wp_date( 'l, F j, Y', $date_time, new DateTimeZone( 'UTC' ) ) : $date;

	$email_data = array(
		'topic'      => $topics[ $topic ],
		'name'       => $name,
		'email'      => $email,
		'phone'      => $phone_nice,
		'tel'        => ( 10 === strlen( $digits ) ? '+1' : '+' ) . $digits,
		'choice'     => $choice ? array( 'rental' === $topic ? 'Gear' : 'Package', $choice ) : null,
		'date'       => $date_nice,
		'message'    => $message,
	);

	$text = implode(
		"\n",
		array_filter(
			array(
				'New message from the website: ' . $topics[ $topic ],
				'',
				'Name: ' . $name,
				'Email: ' . $email,
				'Phone: ' . $phone_nice,
				$choice ? $email_data['choice'][0] . ': ' . $choice : null,
				$date_nice ? 'Event date: ' . $date_nice : null,
				'',
				'Message:',
				$message,
				'',
				'Sent from the contact page on ' . home_url( '/' ) . '. Reply to this email to answer ' . $name . ' directly.',
			),
			function ( $line ) {
				return null !== $line; // Keep the blank spacer lines, drop unanswered optional fields.
			}
		)
	);
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'Reply-To: ' . str_replace( array( "\r", "\n", '<', '>', ',', '"' ), '', $name ) . ' <' . $email . '>',
	);
	$subject = implode( ' · ', array_filter( array( sprintf( 'New %s inquiry from %s', 'booking' === $topic ? 'booking' : ( 'rental' === $topic ? 'rental' : 'website' ), $name ), $choice, $date_time ? wp_date( 'M j', $date_time, new DateTimeZone( 'UTC' ) ) : '' ) ) );

	// Plain-text copy for mail apps that don't show HTML.
	$alt = function ( $mailer ) use ( $text ) {
		$mailer->AltBody = $text; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	};
	add_action( 'phpmailer_init', $alt );
	$sent = wp_mail( sbc_mod( 'email' ), $subject, sbc_contact_email_html( $email_data ), $headers );
	remove_action( 'phpmailer_init', $alt );

	$back( $sent ? 1 : 'error' );
}

/**
 * The team's notification email. Table layout and inline styles, so it looks right in Gmail,
 * Apple Mail and Outlook; brand colours and fonts fall back to system ones where web fonts aren't loaded.
 */
function sbc_contact_email_html( $d ) {
	$e     = function ( $v ) {
		return esc_html( $v );
	};
	$font  = "font-family:Poppins,-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;";
	$label = $font . 'padding:14px 0;border-bottom:1px solid #eee8e9;color:#5f5f67;font-size:13px;width:120px;vertical-align:top;';
	$value = $font . 'padding:14px 0;border-bottom:1px solid #eee8e9;color:#222225;font-size:15px;font-weight:600;vertical-align:top;';
	$link  = 'color:#9d1a4f;text-decoration:none;';

	$rows = array(
		array( 'Phone', '<a href="tel:' . esc_attr( $d['tel'] ) . '" style="' . $link . '">' . $e( $d['phone'] ) . '</a>' ),
		array( 'Email', '<a href="mailto:' . esc_attr( $d['email'] ) . '" style="' . $link . '">' . $e( $d['email'] ) . '</a>' ),
	);
	if ( $d['choice'] ) {
		array_unshift( $rows, array( $d['choice'][0], $e( $d['choice'][1] ) ) );
	}
	if ( $d['date'] ) {
		array_splice( $rows, $d['choice'] ? 1 : 0, 0, array( array( 'Event date', $e( $d['date'] ) ) ) );
	}
	$table = '';
	foreach ( $rows as $row ) {
		$table .= '<tr><td style="' . $label . '">' . $row[0] . '</td><td style="' . $value . '">' . $row[1] . '</td></tr>';
	}

	$first   = strtok( $d['name'], ' ' );
	$reply   = 'mailto:' . $d['email'] . '?subject=' . rawurlencode( 'Re: your Social Booth Co inquiry' );
	$btn     = $font . 'display:inline-block;padding:13px 24px;border-radius:999px;font-size:14px;font-weight:600;text-decoration:none;';
	$preview = $d['topic'] . ( $d['choice'] ? ' · ' . $d['choice'][1] : '' ) . ( $d['date'] ? ' · ' . $d['date'] : '' );

	return '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="color-scheme" content="light"><title>' . $e( $d['topic'] ) . '</title></head>'
		. '<body style="margin:0;padding:0;background:#f7f2f2;">'
		. '<div style="display:none;max-height:0;overflow:hidden;opacity:0;">' . $e( $preview ) . '</div>'
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f7f2f2;"><tr><td align="center" style="padding:32px 16px;">'
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;">'

		// Brand line.
		. '<tr><td style="' . $font . 'padding:0 4px 16px;font-size:13px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#222225;">Social Booth Co</td></tr>'

		// Card.
		. '<tr><td style="background:#ffffff;border-radius:20px;overflow:hidden;">'
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0">'
		. '<tr><td style="background:#222225;padding:28px 32px;border-radius:20px 20px 0 0;">'
		. '<span style="' . $font . 'display:inline-block;padding:6px 12px;border-radius:999px;background:#9d1a4f;color:#ffffff;font-size:12px;font-weight:600;">' . $e( $d['topic'] ) . '</span>'
		. '<div style="' . $font . 'margin-top:16px;color:#ffffff;font-size:26px;font-weight:700;letter-spacing:-.02em;line-height:1.2;">New message from ' . $e( $d['name'] ) . '</div>'
		. '<div style="' . $font . 'margin-top:6px;color:#c9c6cc;font-size:14px;">Sent from the contact page on socialboothco.com</div>'
		. '</td></tr>'

		// Quick actions.
		. '<tr><td style="padding:28px 32px 4px;">'
		. '<a href="' . esc_attr( $reply ) . '" style="' . $btn . 'background:#9d1a4f;color:#ffffff;margin:0 8px 8px 0;">Reply to ' . $e( $first ) . '</a>'
		. '<a href="tel:' . esc_attr( $d['tel'] ) . '" style="' . $btn . 'background:#ffffff;color:#222225;border:1.5px solid #222225;margin:0 0 8px;">Call ' . $e( $d['phone'] ) . '</a>'
		. '</td></tr>'

		// Details.
		. '<tr><td style="padding:12px 32px 0;"><table role="presentation" width="100%" cellpadding="0" cellspacing="0">' . $table . '</table></td></tr>'

		// Message.
		. '<tr><td style="padding:24px 32px 32px;">'
		. '<div style="' . $font . 'color:#5f5f67;font-size:13px;margin-bottom:8px;">Message</div>'
		. '<div style="' . $font . 'background:#f7f2f2;border-left:4px solid #9d1a4f;border-radius:12px;padding:18px 20px;color:#222225;font-size:15px;line-height:1.6;">' . nl2br( $e( $d['message'] ) ) . '</div>'
		. '</td></tr>'
		. '</table></td></tr>'

		// Footer.
		. '<tr><td style="' . $font . 'padding:18px 4px 0;color:#8a8790;font-size:12px;line-height:1.5;">Hit reply to answer ' . $e( $first ) . ' directly. This message came from the form at socialboothco.com/contact.</td></tr>'
		. '</table></td></tr></table></body></html>';
}

