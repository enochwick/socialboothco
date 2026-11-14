<?php
/**
 * /experience/ — copy from the original Experience page.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$sbc_steps = array(
	array( __( 'Secure your date', 'socialboothco' ), __( 'Send your inquiry and we’ll confirm availability within 24 hours. Once approved, your date is officially reserved.', 'socialboothco' ) ),
	array( __( 'Design and planning', 'socialboothco' ), __( 'We create a custom overlay for your event style. You review and approve, and we finalize every detail, including the print layout if selected.', 'socialboothco' ) ),
	array( __( 'Event setup', 'socialboothco' ), __( 'We arrive early for full setup, calibration and testing, so everything is ready before guests arrive.', 'socialboothco' ) ),
	array( __( 'Event time', 'socialboothco' ), __( 'Guests tap, pose and share. Photos are delivered instantly, and printed on site if your package includes prints.', 'socialboothco' ) ),
	array( __( 'Instant delivery', 'socialboothco' ), __( 'Guests get their digital photos right away. You receive a complete online gallery after the event to view and download.', 'socialboothco' ) ),
	array( __( 'Teardown', 'socialboothco' ), __( 'We pack up discreetly and leave the space exactly as we found it.', 'socialboothco' ) ),
);
$sbc_grid  = array( 2915, 2916, 2883, 2877, 2881, 2882, 2856, 2861 );
?>

<section class="exp-hero">
	<div class="wrap">
		<div class="exp-hero__text">
			<h1><?php esc_html_e( 'The experience, elevated.', 'socialboothco' ); ?></h1>
			<p class="lede"><?php esc_html_e( 'A modern photo booth designed to entertain guests and capture unforgettable moments, from the first tap on the screen to the instant share.', 'socialboothco' ); ?></p>
			<div class="actions">
				<?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', 'berry' ); ?>
				<a class="btn btn--line" href="<?php echo esc_url( home_url( '/#pricing' ) ); ?>"><?php esc_html_e( 'View packages', 'socialboothco' ); ?></a>
			</div>
		</div>
		<div class="exp-hero__photo">
			<?php echo sbc_image( 4991, 'large', array( 'alt' => __( 'Guests with props crowding into the photo booth', 'socialboothco' ), 'loading' => 'eager', 'sizes' => '(min-width: 1000px) 45vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>

<section class="sec sec--white">
	<div class="wrap">
		<h2 class="h2"><?php esc_html_e( 'How it works.', 'socialboothco' ); ?></h2>
		<p class="intro"><?php esc_html_e( 'A seamless experience from booking to breakdown.', 'socialboothco' ); ?></p>
		<ol class="steps">
			<?php foreach ( $sbc_steps as $sbc_step ) : ?>
				<li>
					<h3><?php echo esc_html( $sbc_step[0] ); ?></h3>
					<p><?php echo esc_html( $sbc_step[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<section class="sec">
	<div class="wrap story">
		<h2 class="h2"><?php esc_html_e( 'We don’t just set up a booth. We create a moment.', 'socialboothco' ); ?></h2>
		<div class="story-text">
			<p><?php esc_html_e( 'From the first tap on the screen to the instant share, every interaction is designed to feel seamless, elevated and effortless.', 'socialboothco' ); ?></p>
			<p><?php esc_html_e( 'Whether it’s a wedding celebration or a corporate activation, our photo booth becomes a highlight of the event. Clean design, professional lighting and instant delivery.', 'socialboothco' ); ?></p>
			<p class="story-sign"><?php esc_html_e( 'It’s modern. It’s social. It’s memorable.', 'socialboothco' ); ?></p>
		</div>
	</div>
</section>

<section class="sec sec--white">
	<div class="wrap">
		<h2 class="h2"><?php esc_html_e( 'Tap. Pose. Share.', 'socialboothco' ); ?></h2>
		<p class="intro"><?php esc_html_e( 'An effortless photo booth your guests will use all night.', 'socialboothco' ); ?></p>
		<div class="photo-grid">
			<?php
			foreach ( $sbc_grid as $sbc_id ) {
				echo sbc_image( $sbc_id, 'medium_large', array( 'sizes' => '(min-width: 900px) 25vw, 50vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
			?>
		</div>
		<p class="more-link"><a class="btn btn--line" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'See the full gallery', 'socialboothco' ); ?></a></p>
	</div>
</section>

<?php
sbc_closing();
get_footer();
