<?php
/**
 * /experience/ — copy from the original Experience page.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$sbc_steps = sbc_experience_steps();
$sbc_step_icons = array(
	'calendar' => '<rect x="3.5" y="5" width="17" height="15.5" rx="3"/><path d="M8 3v4M16 3v4M3.5 10h17M9 15l2 2 4-4"/>',
	'pen'      => '<path d="M14.5 5.5l4 4L9 19H5v-4z"/><path d="M12.5 7.5l4 4"/>',
	'tool'     => '<circle cx="12" cy="9" r="5"/><path d="M12 14v7M8.5 21h7M12 6.5v2.5l1.5 1"/>',
	'camera'   => '<path d="M14.5 4.5h-5L7.5 7H5a2 2 0 0 0-2 2v8.5a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2.5z"/><circle cx="12" cy="13" r="3.5"/>',
	'send'     => '<path d="M21 3 10.5 13.5"/><path d="M21 3l-6.5 18-4-7.5L3 9.5z"/>',
	'check'    => '<circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.7 2.7L16.5 9.5"/>',
);
?>

<section class="exp-hero">
	<div class="wrap">
		<div class="exp-hero__text">
			<?php sbc_animated_title( __( 'The experience, elevated.', 'socialboothco' ) ); ?>
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
				<li><?php esc_html_e( 'Availability confirmed within a couple of hours', 'socialboothco' ); ?></li>
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
		<?php
		// Bento: a mix of photos from our Thumbtack profile (bundled in the theme) and the Media Library.
		$sbc_ev    = get_template_directory_uri() . '/assets/img/events/';
		$sbc_bento = array(
			array( 'file' => array( 'morning-props-640.jpg', 'morning-props-1024.jpg', 640, 853 ), 'alt' => __( 'Kids in matching blue shirts posing with props at the photo booth', 'socialboothco' ), 'tag' => __( 'Pose', 'socialboothco' ) ),
			array( 'file' => array( 'morning-print-strip-380.jpg', 'morning-print-strip-600.jpg', 380, 1140 ), 'alt' => __( 'A printed photo booth strip from the event', 'socialboothco' ), 'tag' => __( 'Share', 'socialboothco' ) ),
			array( 'id' => 2915 ),
			array( 'file' => array( 'booth-tap-to-start-640.jpg', 'booth-tap-to-start-1024.jpg', 640, 640 ), 'alt' => __( 'The Social Booth Co booth showing “Tap to start”', 'socialboothco' ), 'tag' => __( 'Tap', 'socialboothco' ) ),
			array( 'id' => 2916 ),
			array( 'file' => array( 'morning-frame-640.jpg', 'morning-frame-1024.jpg', 640, 853 ), 'alt' => __( 'Guests posing with a frame prop at the booth', 'socialboothco' ) ),
			array( 'id' => 2882 ),
			array( 'id' => 2856 ),
		);
		?>
		<div class="bento">
			<?php foreach ( $sbc_bento as $sbc_bi => $sbc_tile ) : ?>
				<?php
				if ( isset( $sbc_tile['id'] ) ) {
					$sbc_img = sbc_image( $sbc_tile['id'], 'medium_large', array( 'sizes' => '(min-width: 900px) 25vw, 50vw' ) );
				} else {
					list( $sbc_small, $sbc_large, $sbc_w, $sbc_h ) = $sbc_tile['file'];
					$sbc_img = sprintf(
						'<img src="%1$s" srcset="%1$s %3$dw, %2$s %4$dw" sizes="(min-width: 900px) %5$s, 60vw" width="%3$d" height="%6$d" alt="%7$s" loading="lazy" decoding="async">',
						esc_url( $sbc_ev . $sbc_small ),
						esc_url( $sbc_ev . $sbc_large ),
						(int) $sbc_w,
						(int) round( $sbc_w * 1.6 ),
						0 === $sbc_bi ? '50vw' : '25vw',
						(int) $sbc_h,
						esc_attr( $sbc_tile['alt'] )
					);
				}
				if ( ! $sbc_img ) {
					continue;
				}
				?>
				<figure class="bento__item bento__item--<?php echo (int) $sbc_bi + 1; ?>">
					<?php echo $sbc_img; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts / wp_get_attachment_image. ?>
					<?php if ( ! empty( $sbc_tile['tag'] ) ) : ?>
						<span class="bento__tag"><?php echo esc_html( $sbc_tile['tag'] ); ?></span>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>
		<p class="more-link"><a class="btn btn--line" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'See the full gallery', 'socialboothco' ); ?></a></p>
	</div>
</section>

<?php
sbc_closing();
get_footer();
