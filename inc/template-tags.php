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

/**
 * The original Social Booth Co logo (booth mark + "socialbooth" wordmark), bundled with the theme.
 * Always used, so an old "Site logo" setting (e.g. the SOCIAL BOOTH CO wordmark) can't replace it.
 */
function sbc_logo( $variant = 'dark' ) {
	// Black version for light backgrounds, white version for dark.
	$src = get_template_directory_uri() . '/assets/img/' . ( 'light' === $variant ? 'logo-light.png' : 'logo-dark.png' );
	$alt = 'light' === $variant ? '' : get_bloginfo( 'name' ); // The white copy is a visual swap only.
	printf( '<img class="logo-img logo-img--%s" src="%s" alt="%s" width="196" height="38">', esc_attr( $variant ), esc_url( $src ), esc_attr( $alt ) );
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

/**
 * Small line icons used by the homepage feature cards.
 */
function sbc_icon( $name ) {
	$paths = array(
		'send'    => '<path d="M21 3 10.5 13.5"/><path d="M21 3l-6.5 18-4-7.5L3 9.5z"/>',
		'pen'     => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
		'light'   => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
		'gallery' => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
		'spark'   => '<path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9z"/>',
	);
	$path = isset( $paths[ $name ] ) ? $paths[ $name ] : $paths['spark'];
	echo '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $path . '</svg>'; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG.
}

/**
 * Reviews call-out with the Thumbtack logo, five stars and a link to the profile.
 */
function sbc_get_reviews_section() {
	$url = sbc_mod( 'reviews_url' );
	if ( ! $url ) {
		return '';
	}
	$img = get_template_directory_uri() . '/assets/img/';
	$star = '<svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 2.5l2.9 6.1 6.6.8-4.9 4.6 1.3 6.6L12 17.3l-5.9 3.3 1.3-6.6-4.9-4.6 6.6-.8z"/></svg>';
	ob_start();
	?>
	<section class="sbc-reviews" aria-labelledby="sbc-reviews-title">
		<div class="wrap">
			<div class="reviews-card">
				<span class="reviews-glow" aria-hidden="true"></span>
				<div class="reviews-copy">
					<div class="reviews-stars" role="img" aria-label="<?php esc_attr_e( '5 out of 5 stars', 'socialboothco' ); ?>"><?php echo str_repeat( $star, 5 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></div>
					<h2 id="sbc-reviews-title" class="reviews-title"><?php echo esc_html( sbc_mod( 'reviews_title' ) ); ?></h2>
					<p class="reviews-text"><?php echo esc_html( sbc_mod( 'reviews_text' ) ); ?></p>
					<a class="btn btn--dark reviews-btn" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
						<?php esc_html_e( 'Read our reviews', 'socialboothco' ); ?>
						<span class="screen-reader-text"><?php esc_html_e( '(opens Thumbtack in a new tab)', 'socialboothco' ); ?></span>
					</a>
				</div>
				<a class="reviews-badge" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Social Booth Co on Thumbtack (opens in a new tab)', 'socialboothco' ); ?>">
					<img class="reviews-mark" src="<?php echo esc_url( $img . 'thumbtack-mark.svg' ); ?>" alt="" width="56" height="56">
					<span class="reviews-badge-label"><?php esc_html_e( 'Reviewed on', 'socialboothco' ); ?></span>
					<img class="reviews-wordmark" src="<?php echo esc_url( $img . 'thumbtack-wordmark.svg' ); ?>" alt="Thumbtack" width="154" height="22">
					<span class="reviews-badge-stars" aria-hidden="true"><?php echo str_repeat( $star, 5 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</a>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function sbc_reviews_section() {
	echo sbc_get_reviews_section(); // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts.
}
add_shortcode( 'sbc_reviews', 'sbc_get_reviews_section' );

/**
 * Pages whose content lists the packages (e.g. /corporate-holiday-parties/) get the reviews
 * section right after the packages section, unless it's already placed with [sbc_reviews].
 */
add_filter( 'the_content', 'sbc_insert_reviews_after_packages', 20 );
function sbc_insert_reviews_after_packages( $content ) {
	if ( is_front_page() || ! is_singular( 'page' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	if ( false !== strpos( $content, 'sbc-reviews' ) ) {
		return $content;
	}
	$plans = strpos( $content, 'class="plans"' );
	if ( false === $plans ) {
		return $content;
	}
	$end = strpos( $content, '</section>', $plans );
	if ( false === $end ) {
		return $content;
	}
	$end += strlen( '</section>' );
	return substr( $content, 0, $end ) . sbc_get_reviews_section() . substr( $content, $end );
}

/**
 * "Moments from our events": photo grid on desktop, swipeable carousel with a peek on phones.
 */
function sbc_moments() {
	$ids = array();
	foreach ( array_filter( array_map( 'absint', explode( ',', (string) sbc_mod( 'moments_photos' ) ) ) ) as $id ) {
		if ( wp_attachment_is_image( $id ) ) {
			$ids[] = $id;
		}
	}
	if ( ! $ids ) {
		return;
	}
	?>
	<div class="moments">
		<div class="moments-head">
			<h3 id="sbc-moments-title" class="moments-title"><?php echo esc_html( sbc_mod( 'moments_title' ) ); ?></h3>
			<a class="btn btn--text" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'See the full gallery', 'socialboothco' ); ?></a>
		</div>
		<ul class="moments-track" role="list">
			<?php foreach ( $ids as $id ) : ?>
				<li class="moments-item">
					<?php echo sbc_image( $id, 'medium_large', array( 'alt' => __( 'Guests at a Social Booth Co photo booth event in Dallas–Fort Worth', 'socialboothco' ), 'sizes' => '(min-width: 900px) 25vw, 80vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}
