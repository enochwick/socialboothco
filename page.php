<?php
/**
 * Any other page. Elementor-built pages (Agreement, Thank you, forms) render their own
 * content full width; regular pages get a title band and readable text column.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	if ( sbc_is_elementor_page() ) :
		?>
		<div class="builder-content"><?php the_content(); ?></div>
		<?php
	else :
		sbc_page_intro( get_the_title() );
		?>
		<div class="wrap prose"><?php the_content(); ?></div>
		<?php
	endif;
endwhile;

get_footer();
