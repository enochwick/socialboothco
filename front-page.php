<?php
/**
 * Homepage. Content comes from Appearance → Customize → Social Booth Co.
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Headline words animate in one by one. {1}/{2} were photo markers in an earlier version; drop them if still saved.
$sbc_title = trim( preg_replace( '/\s+/', ' ', str_replace( array( '{1}', '{2}' ), '', sbc_mod( 'hero_title' ) ) ) );
$sbc_words = '' === $sbc_title ? array() : explode( ' ', $sbc_title );

// Scrolling photo strip: valid images only, in the order given. The strip is drawn twice for a
// seamless loop, so it's capped: 16 photos already run wider than any screen, and more just
// makes one huge moving layer that phones can't keep in memory (photos blank out and flicker).
$sbc_photos = array();
foreach ( array_filter( array_map( 'absint', explode( ',', (string) sbc_mod( 'hero_photos' ) ) ) ) as $sbc_id ) {
	if ( wp_attachment_is_image( $sbc_id ) ) {
		$sbc_photos[] = $sbc_id;
	}
}
$sbc_photos = array_slice( $sbc_photos, 0, (int) apply_filters( 'sbc_hero_photo_limit', 16 ) );
?>

<section class="mq-hero" data-marquee>
	<div class="wrap mq-hero__content">
		<?php if ( sbc_mod( 'hero_tagline' ) ) : ?>
			<p class="mq-tag"><?php echo esc_html( sbc_mod( 'hero_tagline' ) ); ?></p>
		<?php endif; ?>
		<h1 class="mq-title" aria-label="<?php echo esc_attr( $sbc_title ); ?>">
			<?php foreach ( $sbc_words as $sbc_i => $sbc_word ) : ?>
				<span class="mq-word" style="--i:<?php echo (int) $sbc_i; ?>" aria-hidden="true"><?php echo esc_html( $sbc_word ); ?></span>
			<?php endforeach; ?>
		</h1>
		<p class="lede mq-lede"><?php echo esc_html( sbc_mod( 'hero_lede' ) ); ?></p>
		<div class="actions mq-actions">
			<?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', 'berry' ); ?>
			<a class="btn btn--line" href="#pricing"><?php esc_html_e( 'Compare packages', 'socialboothco' ); ?></a>
		</div>
	</div>

	<?php if ( $sbc_photos ) : ?>
		<?php sbc_stamp( 'SOCIAL BOOTH CO · DFW METROPLEX', 'hero-stamp' ); ?>
		<div class="mq-strip" aria-hidden="true">
			<div class="mq-track">
				<?php
				// Two identical sets make the loop seamless; the second set is purely visual.
				for ( $sbc_set = 0; $sbc_set < 2; $sbc_set++ ) {
					foreach ( $sbc_photos as $sbc_n => $sbc_id ) {
						echo '<div class="mq-card' . ( $sbc_n % 2 ? ' mq-card--alt' : '' ) . '">'; // Tilt by position within the set, so both copies match.
						// Cards are ~250px wide on phones, ~330px on desktop. All load up front (the second
						// set reuses the same files, so it costs nothing): lazy photos inside a moving strip
						// pop in late. Only the first few on screen get priority; sync decoding stops Safari
						// from flashing them blank when the layer repaints.
						echo sbc_image( // phpcs:ignore WordPress.Security.EscapeOutput
							$sbc_id,
							'medium_large',
							array(
								'alt'           => '',
								'sizes'         => '(max-width: 700px) 250px, 330px',
								'loading'       => 'eager',
								'decoding'      => 'sync',
								'fetchpriority' => 0 === $sbc_set && $sbc_n < 4 ? 'high' : 'low',
							)
						);
						echo '</div>';
					}
				}
				?>
			</div>
		</div>
		<button class="mq-toggle" type="button" data-marquee-toggle>
			<span class="screen-reader-text" data-label-pause><?php esc_html_e( 'Pause photo animation', 'socialboothco' ); ?></span>
			<span class="screen-reader-text" data-label-play hidden><?php esc_html_e( 'Play photo animation', 'socialboothco' ); ?></span>
			<svg class="mq-ico-pause" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><rect x="6" y="5" width="4" height="14" rx="1"/><rect x="14" y="5" width="4" height="14" rx="1"/></svg>
			<svg class="mq-ico-play" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13a1 1 0 0 0 1.5.86l10.5-6.5a1 1 0 0 0 0-1.72L9.5 4.64A1 1 0 0 0 8 5.5z"/></svg>
		</button>
	<?php endif; ?>
</section>

<?php get_template_part( 'parts/features' ); ?>

<section class="sec" id="pricing">
	<div class="wrap">
		<h2 class="h2"><?php echo esc_html( sbc_mod( 'packages_title' ) ); ?></h2>
		<p class="intro"><?php echo esc_html( sbc_mod( 'packages_intro' ) ); ?></p>
		<?php get_template_part( 'parts/packages' ); ?>
	</div>
</section>

<?php sbc_reviews_section(); ?>

<?php get_template_part( 'parts/events' ); ?>

<?php get_template_part( 'parts/backdrops' ); ?>

<?php get_template_part( 'parts/gear' ); ?>

<?php get_template_part( 'parts/faq-preview' ); ?>

<?php
sbc_closing();
get_footer();
