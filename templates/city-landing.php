<?php
/**
 * Template Name: City landing page
 *
 * For pages like "Photo Booth Rental in Frisco" or "Wedding Photo Booth Rental in Dallas–Fort Worth".
 * The H1 is the page title; the city comes from the words after "in". Write two or three
 * sentences unique to the city in the page editor: they appear under the hero and keep each
 * city page distinct for search. Packages, reviews, photos and FAQs are shared with the homepage.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$sbc_city = sbc_city_name();
?>

<?php
$sbc_title  = get_the_title();
$sbc_spots  = sbc_city_spots( $sbc_city );
$sbc_strip  = sbc_city_photos( $sbc_city, 3 );
$sbc_short  = trim( preg_replace( '/–.*$/u', '', $sbc_city ) ); // "Dallas–Fort Worth" → "Dallas" for the stamp.
$sbc_letter = function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $sbc_short ) : strtoupper( $sbc_short );
?>

<header class="city-hero">
	<p class="city-hero__ghost" aria-hidden="true"><?php echo esc_html( $sbc_letter ); ?></p>
	<div class="wrap city-hero__grid">
		<div class="city-hero__copy">
			<p class="mq-tag city-hero__tag">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/></svg>
				<?php
				/* translators: %s: city */
				printf( esc_html__( 'Now booking in %s, TX', 'socialboothco' ), esc_html( $sbc_city ) );
				?>
			</p>
			<h1 class="city-hero__title" aria-label="<?php echo esc_attr( $sbc_title ); ?>">
				<?php
				// The city name gets the accent color.
				$sbc_city_words = preg_split( '/\s+/u', $sbc_city );
				foreach ( preg_split( '/\s+/u', trim( $sbc_title ) ) as $sbc_i => $sbc_word ) {
					$sbc_is_city = in_array( trim( $sbc_word, ',.' ), $sbc_city_words, true );
					printf( '<span class="mq-word%s" style="--i:%d" aria-hidden="true">%s</span> ', $sbc_is_city ? ' is-city' : '', (int) $sbc_i, esc_html( $sbc_word ) );
				}
				?>
			</h1>
			<p class="lede city-hero__lede">
			<?php
			$sbc_lede = false !== stripos( $sbc_city, 'dallas' )
				/* translators: %s: city */
				? __( 'Modern photo booth experiences for weddings, parties and corporate events across %s. Instant sharing, custom overlays and an attendant at every event.', 'socialboothco' )
				/* translators: %s: city */
				: __( 'Modern photo booth experiences for weddings, parties and corporate events in %s and across Dallas–Fort Worth. Instant sharing, custom overlays and an attendant at every event.', 'socialboothco' );
			printf( esc_html( $sbc_lede ), esc_html( $sbc_city ) );
			?>
			</p>
			<div class="actions city-hero__actions">
				<?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', 'berry' ); ?>
				<a class="btn btn--line" href="#pricing"><?php esc_html_e( 'Compare packages', 'socialboothco' ); ?></a>
			</div>
			<?php if ( $sbc_spots ) : ?>
				<div class="city-hero__spots">
					<p class="city-hero__spots-label"><?php esc_html_e( 'Booths popping up around', 'socialboothco' ); ?></p>
					<ul>
						<?php foreach ( $sbc_spots as $sbc_n => $sbc_spot ) : ?>
							<li style="--i:<?php echo (int) $sbc_n; ?>"><?php echo esc_html( $sbc_spot ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $sbc_strip ) : ?>
			<div class="city-hero__art" aria-hidden="true">
				<div class="city-strip">
					<?php foreach ( $sbc_strip as $sbc_n => $sbc_id ) : ?>
						<div class="city-strip__shot">
							<?php echo sbc_image( $sbc_id, 'medium_large', array( 'alt' => '', 'sizes' => '(min-width: 1000px) 260px, 180px', 'loading' => 'eager', 'fetchpriority' => 0 === $sbc_n ? 'high' : 'auto' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</div>
					<?php endforeach; ?>
					<div class="city-strip__caption">
						<span class="city-strip__city"><?php echo esc_html( $sbc_letter ); ?></span>
						<span class="city-strip__brand"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
					</div>
				</div>
				<?php sbc_stamp( $sbc_letter . ' · TEXAS · PHOTO BOOTH', 'city-stamp' ); ?>
			</div>
		<?php endif; ?>
	</div>
</header>

<?php
// The page's own text (the city-specific sentences) becomes the intro of the features section.
$sbc_local = '';
while ( have_posts() ) :
	the_post();
	if ( '' !== trim( get_the_content() ) ) {
		$sbc_local = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
	}
endwhile;

get_template_part( 'parts/features', null, array( 'intro' => $sbc_local ) );
?>

<section class="sec" id="pricing">
	<div class="wrap">
		<h2 class="h2">
			<?php
			/* translators: %s: city */
			printf( esc_html__( 'Photo booth packages in %s', 'socialboothco' ), esc_html( $sbc_city ) );
			?>
		</h2>
		<p class="intro"><?php echo esc_html( sbc_mod( 'packages_intro' ) ); ?></p>
		<?php get_template_part( 'parts/packages' ); ?>
	</div>
</section>

<?php
sbc_reviews_section();
get_template_part( 'parts/events' );
get_template_part( 'parts/faq-preview' );
sbc_closing();
get_footer();
