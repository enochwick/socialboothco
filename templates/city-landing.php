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

<header class="page-intro city-intro">
	<div class="wrap">
		<?php sbc_animated_title( get_the_title() ); ?>
		<p class="lede">
			<?php
			$sbc_lede = false !== stripos( $sbc_city, 'dallas' )
				/* translators: %s: city */
				? __( 'Modern photo booth experiences for weddings, parties and corporate events across %s. Instant sharing, custom overlays and an attendant at every event.', 'socialboothco' )
				/* translators: %s: city */
				: __( 'Modern photo booth experiences for weddings, parties and corporate events in %s and across Dallas–Fort Worth. Instant sharing, custom overlays and an attendant at every event.', 'socialboothco' );
			printf( esc_html( $sbc_lede ), esc_html( $sbc_city ) );
			?>
		</p>
		<div class="actions">
			<?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', 'berry' ); ?>
			<a class="btn btn--line" href="#pricing"><?php esc_html_e( 'Compare packages', 'socialboothco' ); ?></a>
		</div>
	</div>
</header>

<?php
while ( have_posts() ) :
	the_post();
	if ( '' !== trim( get_the_content() ) ) :
		?>
		<section class="sec sec--tight city-local">
			<div class="wrap prose"><?php the_content(); ?></div>
		</section>
		<?php
	endif;
endwhile;

get_template_part( 'parts/features' );
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
