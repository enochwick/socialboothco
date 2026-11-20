<?php
/**
 * /contact/ — contact details plus a form that emails the team (handled in inc/contact.php).
 * Links can pick the topic with ?topic=booking|rental|other.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$sbc_topics = sbc_contact_topics();
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- display-only query args.
$sbc_topic = isset( $_GET['topic'] ) ? sanitize_key( wp_unslash( $_GET['topic'] ) ) : '';
$sbc_topic = isset( $sbc_topics[ $sbc_topic ] ) ? $sbc_topic : 'booking';
$sbc_sent  = isset( $_GET['sent'] ) ? sanitize_key( wp_unslash( $_GET['sent'] ) ) : '';
// phpcs:enable
$sbc_notices = array(
	'missing' => __( 'Please add your name, a valid email, your phone number and a message, then send it again.', 'socialboothco' ),
	'limit'   => __( 'You’ve sent a few messages already. Give us a call or email us directly and we’ll get right back to you.', 'socialboothco' ),
	'error'   => __( 'Something went wrong sending your message. Please email or call us directly.', 'socialboothco' ),
);
?>

<?php sbc_page_intro( __( 'Let’s talk.', 'socialboothco' ), __( 'Questions about a booking, renting our gear or anything else? Send us a note and we’ll get back to you soon.', 'socialboothco' ) ); ?>

<section class="sec sec--tight">
	<div class="wrap faq-split">
		<aside class="faq-contact">
			<h2 class="h3"><?php esc_html_e( 'Reach us directly', 'socialboothco' ); ?></h2>
			<p><?php esc_html_e( 'Prefer to call or email? We’re here for that too.', 'socialboothco' ); ?></p>
			<ul>
				<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', sbc_mod( 'phone' ) ) ); ?>"><?php echo esc_html( sbc_mod( 'phone' ) ); ?></a></li>
				<li><a href="mailto:<?php echo esc_attr( antispambot( sbc_mod( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( sbc_mod( 'email' ) ) ); ?></a></li>
			</ul>
			<?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', 'berry' ); ?>
		</aside>

		<div class="contact-form-wrap" id="contact-form">
			<?php if ( '1' === $sbc_sent ) : ?>
				<div class="contact-done" role="status">
					<h2 class="h3"><?php esc_html_e( 'Thanks, your message is on its way.', 'socialboothco' ); ?></h2>
					<p><?php esc_html_e( 'We’ll reply to the email you gave us, usually within a day.', 'socialboothco' ); ?></p>
					<a class="btn btn--line" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'socialboothco' ); ?></a>
				</div>
			<?php else : ?>
				<?php if ( isset( $sbc_notices[ $sbc_sent ] ) ) : ?>
					<p class="contact-error" role="alert"><?php echo esc_html( $sbc_notices[ $sbc_sent ] ); ?></p>
				<?php endif; ?>
				<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="sbc_contact">
					<input type="hidden" name="sbc_t" value="" data-contact-time>
					<div class="contact-hp" aria-hidden="true">
						<label for="sbc-c-website">Website</label>
						<input type="text" id="sbc-c-website" name="website" tabindex="-1" autocomplete="off">
					</div>

					<div class="field field--full">
						<label for="sbc-c-topic"><?php esc_html_e( 'What’s this about?', 'socialboothco' ); ?></label>
						<select id="sbc-c-topic" name="topic" data-contact-topic>
							<?php foreach ( $sbc_topics as $sbc_key => $sbc_label ) : ?>
								<option value="<?php echo esc_attr( $sbc_key ); ?>" <?php selected( $sbc_topic, $sbc_key ); ?>><?php echo esc_html( $sbc_label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<?php
					// Follow-up menu for the chosen topic. The other one stays hidden and disabled, so it isn't sent; theme.js swaps them.
					$sbc_menus = array(
						'booking' => array( 'package', __( 'Which package?', 'socialboothco' ), sbc_contact_packages() ),
						'rental'  => array( 'gear', __( 'Which gear?', 'socialboothco' ), sbc_contact_gear() ),
					);
					foreach ( $sbc_menus as $sbc_key => $sbc_menu ) :
						$sbc_on = $sbc_key === $sbc_topic;
						?>
						<div class="field field--full" data-contact-for="<?php echo esc_attr( $sbc_key ); ?>"<?php echo $sbc_on ? '' : ' hidden'; ?>>
							<label for="sbc-c-<?php echo esc_attr( $sbc_menu[0] ); ?>"><?php echo esc_html( $sbc_menu[1] ); ?></label>
							<select id="sbc-c-<?php echo esc_attr( $sbc_menu[0] ); ?>" name="<?php echo esc_attr( $sbc_menu[0] ); ?>"<?php echo $sbc_on ? '' : ' disabled'; ?>>
								<?php foreach ( $sbc_menu[2] as $sbc_option ) : ?>
									<option value="<?php echo esc_attr( $sbc_option ); ?>"><?php echo esc_html( $sbc_option ); ?></option>
								<?php endforeach; ?>
								<option value="<?php echo esc_attr( sbc_contact_unsure( $sbc_key ) ); ?>"><?php echo esc_html( sbc_contact_unsure( $sbc_key ) ); ?></option>
							</select>
						</div>
					<?php endforeach; ?>
					<div class="field">
						<label for="sbc-c-name"><?php esc_html_e( 'Name', 'socialboothco' ); ?></label>
						<input type="text" id="sbc-c-name" name="name" autocomplete="name" required maxlength="120">
					</div>
					<div class="field">
						<label for="sbc-c-email"><?php esc_html_e( 'Email', 'socialboothco' ); ?></label>
						<input type="email" id="sbc-c-email" name="email" autocomplete="email" required>
					</div>
					<div class="field">
						<label for="sbc-c-phone"><?php esc_html_e( 'Phone', 'socialboothco' ); ?></label>
						<input type="tel" id="sbc-c-phone" name="phone" autocomplete="tel" required minlength="7" maxlength="40">
					</div>
					<div class="field">
						<label for="sbc-c-date"><?php esc_html_e( 'Event date', 'socialboothco' ); ?> <span class="opt"><?php esc_html_e( '(optional)', 'socialboothco' ); ?></span></label>
						<input type="date" id="sbc-c-date" name="event_date">
					</div>
					<div class="field field--full">
						<label for="sbc-c-message"><?php esc_html_e( 'Message', 'socialboothco' ); ?></label>
						<textarea id="sbc-c-message" name="message" rows="6" required maxlength="5000" placeholder="<?php esc_attr_e( 'Tell us about your event, or which gear you’d like to rent and for when.', 'socialboothco' ); ?>"></textarea>
					</div>
					<div class="field--full">
						<button class="btn btn--berry" type="submit"><?php esc_html_e( 'Send message', 'socialboothco' ); ?></button>
					</div>
				</form>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
sbc_closing();
get_footer();
