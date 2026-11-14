<?php
/**
 * Homepage. Content comes from Appearance → Customize → Social Booth Co.
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Headline words animate in one by one. {1}/{2} were photo markers in an earlier version; drop them if still saved.
$sbc_title = trim( preg_replace( '/\s+/', ' ', str_replace( array( '{1}', '{2}' ), '', sbc_mod( 'hero_title' ) ) ) );
$sbc_words = '' === $sbc_title ? array() : explode( ' ', $sbc_title );

// Scrolling photo strip: valid images only, in the order given.
$sbc_photos = array();
foreach ( array_filter( array_map( 'absint', explode( ',', (string) sbc_mod( 'hero_photos' ) ) ) ) as $sbc_id ) {
	if ( wp_attachment_is_image( $sbc_id ) ) {
		$sbc_photos[] = $sbc_id;
	}
}
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
		<div class="mq-strip" aria-hidden="true">
			<div class="mq-track">
				<?php
				// Two identical sets make the loop seamless; the second set is purely visual.
				for ( $sbc_set = 0; $sbc_set < 2; $sbc_set++ ) {
					foreach ( $sbc_photos as $sbc_n => $sbc_id ) {
						echo '<div class="mq-card' . ( $sbc_n % 2 ? ' mq-card--alt' : '' ) . '">'; // Tilt by position within the set, so both copies match.
						echo sbc_image( // phpcs:ignore WordPress.Security.EscapeOutput
							$sbc_id,
							'medium_large',
							array(
								'alt'     => '',
								'sizes'   => '200px',
								'loading' => 0 === $sbc_set && $sbc_n < 6 ? 'eager' : 'lazy',
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

<section class="sec sec--white">
	<div class="wrap">
		<h2 class="h2"><?php echo esc_html( sbc_mod( 'features_title' ) ); ?></h2>
		<div class="feat">
			<?php foreach ( sbc_lines( 'features', 2 ) as $sbc_f ) : ?>
				<div>
					<h3><?php echo esc_html( $sbc_f[0] ); ?></h3>
					<p><?php echo esc_html( $sbc_f[1] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="sec" id="pricing">
	<div class="wrap">
		<h2 class="h2"><?php echo esc_html( sbc_mod( 'packages_title' ) ); ?></h2>
		<p class="intro"><?php echo esc_html( sbc_mod( 'packages_intro' ) ); ?></p>
		<?php get_template_part( 'parts/packages' ); ?>
	</div>
</section>

<section class="sec sec--white">
	<div class="wrap">
		<h2 class="h2"><?php echo esc_html( sbc_mod( 'events_title' ) ); ?></h2>
		<p class="intro"><?php echo esc_html( sbc_mod( 'events_intro' ) ); ?></p>
		<div class="ev">
			<?php
			for ( $sbc_i = 1; $sbc_i <= 4; $sbc_i++ ) :
				$sbc_ev = array_pad( array_map( 'trim', explode( '|', sbc_mod( "event{$sbc_i}" ) ) ), 2, '' );
				if ( '' === $sbc_ev[0] ) {
					continue;
				}
				?>
				<figure class="ev-card ev-card--<?php echo (int) $sbc_i; ?>">
					<?php echo sbc_image( sbc_mod( "event{$sbc_i}_photo" ), 'large', array( 'alt' => '', 'sizes' => '(min-width: 900px) 40vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<figcaption>
						<h3><?php echo esc_html( $sbc_ev[0] ); ?></h3>
						<?php if ( $sbc_ev[1] ) : ?>
							<p><?php echo esc_html( $sbc_ev[1] ); ?></p>
						<?php endif; ?>
					</figcaption>
				</figure>
			<?php endfor; ?>
		</div>
	</div>
</section>

<section class="sec">
	<div class="wrap faq-split">
		<div>
			<h2 class="h2"><?php esc_html_e( 'Good to know.', 'socialboothco' ); ?></h2>
			<p class="intro">
				<?php esc_html_e( 'Quick answers before you book.', 'socialboothco' ); ?>
				<a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'Read all FAQs', 'socialboothco' ); ?></a>
			</p>
		</div>
		<?php sbc_faq_list( 4 ); ?>
	</div>
</section>

<?php
sbc_closing();
get_footer();
