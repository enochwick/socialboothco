<?php
/**
 * /experience/ — copy from the original Experience page.
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Title, text, when it happens (from the site's own copy and FAQ), icon.
$sbc_steps = array(
	array( __( 'Secure your date', 'socialboothco' ), __( 'Send your inquiry and we’ll confirm availability within 24 hours. Once approved, your date is officially reserved.', 'socialboothco' ), __( 'Within 24 hours', 'socialboothco' ), 'calendar' ),
	array( __( 'Design and planning', 'socialboothco' ), __( 'We create a custom overlay for your event style. You review and approve, and we finalize every detail, including the print layout if selected.', 'socialboothco' ), __( 'Before your event', 'socialboothco' ), 'pen' ),
	array( __( 'Event setup', 'socialboothco' ), __( 'We arrive early for full setup, calibration and testing, so everything is ready before guests arrive.', 'socialboothco' ), __( '45–60 min before', 'socialboothco' ), 'tool' ),
	array( __( 'Event time', 'socialboothco' ), __( 'Guests tap, pose and share. Photos are delivered instantly, and printed on site if your package includes prints.', 'socialboothco' ), __( 'During your event', 'socialboothco' ), 'camera' ),
	array( __( 'Instant delivery', 'socialboothco' ), __( 'Guests get their digital photos right away. You receive a complete online gallery after the event to view and download.', 'socialboothco' ), __( 'Instantly', 'socialboothco' ), 'send' ),
	array( __( 'Teardown', 'socialboothco' ), __( 'We pack up discreetly and leave the space exactly as we found it.', 'socialboothco' ), __( 'After your event', 'socialboothco' ), 'check' ),
);
$sbc_step_icons = array(
	'calendar' => '<rect x="3.5" y="5" width="17" height="15.5" rx="3"/><path d="M8 3v4M16 3v4M3.5 10h17M9 15l2 2 4-4"/>',
	'pen'      => '<path d="M14.5 5.5l4 4L9 19H5v-4z"/><path d="M12.5 7.5l4 4"/>',
	'tool'     => '<circle cx="12" cy="9" r="5"/><path d="M12 14v7M8.5 21h7M12 6.5v2.5l1.5 1"/>',
	'camera'   => '<path d="M14.5 4.5h-5L7.5 7H5a2 2 0 0 0-2 2v8.5a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2.5z"/><circle cx="12" cy="13" r="3.5"/>',
	'send'     => '<path d="M21 3 10.5 13.5"/><path d="M21 3l-6.5 18-4-7.5L3 9.5z"/>',
	'check'    => '<circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.7 2.7L16.5 9.5"/>',
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

<section class="sec sec--white hiw-pin" data-hiw-pin>
	<div class="hiw-sticky">
	<div class="wrap hiw">
		<div class="hiw-intro">
			<h2 class="h2"><?php esc_html_e( 'How it works.', 'socialboothco' ); ?></h2>
			<p class="intro"><?php esc_html_e( 'A seamless experience from booking to breakdown. You enjoy the party; we handle every step.', 'socialboothco' ); ?></p>
			<ul class="hiw-facts">
				<li><?php esc_html_e( 'Availability confirmed within 24 hours', 'socialboothco' ); ?></li>
				<li><?php esc_html_e( 'Setup done before guests arrive', 'socialboothco' ); ?></li>
				<li><?php esc_html_e( 'Online gallery after every event', 'socialboothco' ); ?></li>
			</ul>
			<?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', 'berry' ); ?>
		</div>

		<div class="hiw-viewport">
		<ol class="hiw-list">
			<?php foreach ( $sbc_steps as $sbc_n => $sbc_step ) : ?>
				<li class="hiw-step">
					<span class="hiw-node" aria-hidden="true">
						<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?php echo $sbc_step_icons[ $sbc_step[3] ]; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></svg>
					</span>
					<div class="hiw-card">
						<div class="hiw-meta">
							<span class="hiw-num">
								<?php
								/* translators: %d: step number */
								printf( esc_html__( 'Step %d', 'socialboothco' ), (int) $sbc_n + 1 );
								?>
							</span>
							<span class="hiw-when"><?php echo esc_html( $sbc_step[2] ); ?></span>
						</div>
						<h3><?php echo esc_html( $sbc_step[0] ); ?></h3>
						<p><?php echo esc_html( $sbc_step[1] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
		</div>
	</div>
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
				$sbc_img = sbc_image( $sbc_id, 'medium_large', array( 'sizes' => '(min-width: 900px) 25vw, 50vw' ) );
				if ( $sbc_img ) {
					echo '<figure class="photo-grid__item">' . $sbc_img . '</figure>'; // phpcs:ignore WordPress.Security.EscapeOutput
				}
			}
			?>
		</div>
		<p class="more-link"><a class="btn btn--line" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'See the full gallery', 'socialboothco' ); ?></a></p>
	</div>
</section>

<?php
sbc_closing();
get_footer();
