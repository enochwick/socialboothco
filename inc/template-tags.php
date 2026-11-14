<?php
/**
 * Small rendering helpers shared by templates.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Booking button. $kind is 'book' or 'consult'; $style is a .btn modifier.
 */
function sbc_button( $label, $kind = 'book', $style = 'berry', $extra_class = '' ) {
	$href  = 'consult' === $kind ? sbc_consult_url() : sbc_booking_url();
	$class = trim( sprintf( 'btn btn--%s sbc-%s %s', $style, $kind, $extra_class ) );
	printf( '<a class="%s" href="%s">%s</a>', esc_attr( $class ), esc_url( $href ), esc_html( $label ) );
}

/**
 * Attachment image by ID with a sensible size; prints nothing if the image is missing.
 */
function sbc_image( $id, $size = 'large', $attr = array() ) {
	$id = (int) $id;
	if ( ! $id || ! wp_attachment_is_image( $id ) ) {
		return '';
	}
	$attr = wp_parse_args( $attr, array( 'loading' => 'lazy', 'decoding' => 'async' ) );
	return wp_get_attachment_image( $id, $size, false, $attr );
}

function sbc_logo( $variant = 'dark' ) {
	if ( 'dark' === $variant && has_custom_logo() ) {
		$id = (int) get_theme_mod( 'custom_logo' );
		echo wp_get_attachment_image( $id, 'medium', false, array( 'class' => 'logo-img', 'alt' => get_bloginfo( 'name' ), 'loading' => 'eager' ) );
		return;
	}
	// Bundled brand files: black wordmark for light backgrounds, white for dark.
	$src = get_template_directory_uri() . '/assets/img/' . ( 'light' === $variant ? 'logo-light.png' : 'logo-dark.png' );
	printf( '<img class="logo-img" src="%s" alt="%s" width="168" height="32">', esc_url( $src ), esc_attr( get_bloginfo( 'name' ) ) );
}

/**
 * Closing call to action shared by every theme page.
 */
function sbc_closing() {
	?>
	<section class="closing">
		<div class="wrap">
			<h2><?php echo esc_html( sbc_mod( 'closing_title' ) ); ?></h2>
			<div class="closing-row">
				<p><?php echo esc_html( sbc_mod( 'closing_text' ) ); ?></p>
				<div class="actions">
					<?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', 'berry' ); ?>
					<?php sbc_button( __( 'Book a consultation', 'socialboothco' ), 'consult', 'line-light' ); ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * FAQ list as native disclosure widgets.
 */
function sbc_faq_list( $limit = 0 ) {
	$items = sbc_faq_items();
	if ( $limit ) {
		$items = array_slice( $items, 0, $limit );
	}
	echo '<div class="faq-list">';
	foreach ( $items as $item ) {
		printf(
			'<details><summary>%s</summary><div class="faq-a">%s</div></details>',
			esc_html( $item[0] ),
			wp_kses_post( wpautop( make_clickable( esc_html( $item[1] ) ) ) )
		);
	}
	echo '</div>';
}

/**
 * Page intro band used by inner pages.
 */
function sbc_page_intro( $title, $lede = '' ) {
	?>
	<header class="page-intro">
		<div class="wrap">
			<h1><?php echo esc_html( $title ); ?></h1>
			<?php if ( $lede ) : ?>
				<p class="lede"><?php echo esc_html( $lede ); ?></p>
			<?php endif; ?>
		</div>
	</header>
	<?php
}
