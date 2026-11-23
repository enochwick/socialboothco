<?php
/**
 * Event customization form pages (see inc/event-forms.php). Not a selectable page template;
 * the theme switches to it by page slug.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$sbc_form_id = sbc_event_form_id();
$sbc_steps   = array(
	array( 'pen', __( 'Your event name and colors', 'socialboothco' ), __( 'Go on your photo overlays, so every print matches the day.', 'socialboothco' ) ),
	array( 'gallery', __( 'Inspiration and invitation', 'socialboothco' ), __( 'Help us match your fonts, colors and overall style.', 'socialboothco' ) ),
	array( 'check', __( 'Contact and venue', 'socialboothco' ), __( 'Let us lock in setup and coordinate the final details with you.', 'socialboothco' ) ),
);
?>

<?php sbc_page_intro( __( 'Customize your booth.', 'socialboothco' ), __( 'Tell us about your event and we’ll tailor the booth and your photo designs to match. It only takes 2⁠–⁠3 minutes.', 'socialboothco' ) ); ?>

<section class="sec sec--tight">
	<div class="wrap faq-split event-form-split">
		<aside class="faq-contact event-form-aside">
			<h2 class="h3"><?php esc_html_e( 'What we use it for', 'socialboothco' ); ?></h2>
			<ul class="event-form-steps">
				<?php foreach ( $sbc_steps as $sbc_step ) : ?>
					<li>
						<span class="event-form-icon" aria-hidden="true"><?php sbc_icon( $sbc_step[0] ); ?></span>
						<span><strong><?php echo esc_html( $sbc_step[1] ); ?></strong> <?php echo esc_html( $sbc_step[2] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="event-form-help">
				<p><?php esc_html_e( 'Questions while you fill it out? We’re happy to help.', 'socialboothco' ); ?></p>
				<ul>
					<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', sbc_mod( 'phone' ) ) ); ?>"><?php echo esc_html( sbc_mod( 'phone' ) ); ?></a></li>
					<li><a href="mailto:<?php echo esc_attr( antispambot( sbc_mod( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( sbc_mod( 'email' ) ) ); ?></a></li>
				</ul>
			</div>
		</aside>

		<div class="event-form">
			<?php echo do_shortcode( '[forminator_form id="' . (int) $sbc_form_id . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput -- Forminator output. ?>
		</div>
	</div>
</section>

<?php
get_footer();
