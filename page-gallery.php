<?php
/**
 * /gallery/ — uses the Social Booth Co Gallery plugin when it's active.
 */

defined( 'ABSPATH' ) || exit;

get_header();

sbc_page_intro( __( 'Real moments. Real reactions. Real memories.', 'socialboothco' ), __( 'Weddings, quinceañeras, birthdays and corporate nights across DFW, plus the prints, backdrops and setups your guests will step into.', 'socialboothco' ) );
?>

<div class="gallery-body">
	<?php
	if ( shortcode_exists( 'sbc_gallery' ) ) {
		echo do_shortcode( '[sbc_gallery hero="no" includes="no"]' );
	} else {
		while ( have_posts() ) {
			the_post();
			the_content();
		}
	}
	?>
</div>

<?php
sbc_closing();
get_footer();
