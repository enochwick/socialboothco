<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="page-intro page-intro--404">
	<div class="wrap">
		<h1><?php esc_html_e( 'This page stepped out of the booth.', 'socialboothco' ); ?></h1>
		<p class="lede"><?php esc_html_e( 'The link may be old or mistyped. Try one of these instead.', 'socialboothco' ); ?></p>
		<div class="actions">
			<a class="btn btn--berry" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the homepage', 'socialboothco' ); ?></a>
			<a class="btn btn--line" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'See the gallery', 'socialboothco' ); ?></a>
		</div>
	</div>
</section>

<?php
get_footer();
