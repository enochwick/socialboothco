<?php
/**
 * Homepage. Content comes from Appearance → Customize → Social Booth Co.
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Headline with up to two inline photos at {1} and {2}.
$sbc_title = esc_html( sbc_mod( 'hero_title' ) );
foreach ( array( 1 => 'pill', 2 => 'pill pill--tall' ) as $sbc_n => $sbc_class ) {
	$sbc_img   = sbc_image( sbc_mod( "hero_photo_{$sbc_n}" ), 'medium', array( 'alt' => '', 'loading' => 'eager', 'sizes' => '160px' ) );
	$sbc_pill  = $sbc_img ? '<span class="' . $sbc_class . '" aria-hidden="true">' . $sbc_img . '</span>' : '';
	$sbc_title = str_replace( '{' . $sbc_n . '}', $sbc_pill, $sbc_title );
}
$sbc_title = trim( preg_replace( '/\s+/', ' ', $sbc_title ) );
?>

<section class="hero">
	<div class="wrap">
		<h1 class="hero-title"><?php echo $sbc_title; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above; images from wp_get_attachment_image. ?></h1>
		<div class="hero-foot">
			<p class="lede"><?php echo esc_html( sbc_mod( 'hero_lede' ) ); ?></p>
			<div class="actions">
				<?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', 'berry' ); ?>
				<a class="btn btn--line" href="#pricing"><?php esc_html_e( 'Compare packages', 'socialboothco' ); ?></a>
			</div>
		</div>
	</div>
</section>

<div class="wrap reel">
	<?php
	for ( $sbc_i = 1; $sbc_i <= 4; $sbc_i++ ) {
		echo sbc_image( sbc_mod( "reel_{$sbc_i}" ), 'medium_large', array( 'sizes' => '(min-width: 900px) 25vw, 50vw', 'loading' => 'eager' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
	}
	?>
</div>

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
