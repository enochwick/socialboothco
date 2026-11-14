<?php
/**
 * Fallback for posts, archives and search.
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_singular() ) :
	while ( have_posts() ) :
		the_post();
		sbc_page_intro( get_the_title() );
		?>
		<article class="wrap prose"><?php the_content(); ?></article>
		<?php
	endwhile;
else :
	sbc_page_intro( is_search() ? sprintf( /* translators: %s: search terms */ __( 'Results for “%s”', 'socialboothco' ), get_search_query() ) : wp_strip_all_tags( get_the_archive_title() ? get_the_archive_title() : __( 'Latest', 'socialboothco' ) ) );
	?>
	<div class="wrap prose">
		<?php if ( have_posts() ) : ?>
			<ul class="post-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php endwhile; ?>
			</ul>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing here yet.', 'socialboothco' ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the homepage', 'socialboothco' ); ?></a></p>
		<?php endif; ?>
	</div>
	<?php
endif;

get_footer();
