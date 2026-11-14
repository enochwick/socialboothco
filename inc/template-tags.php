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
			<?php sbc_animated_title( $title ); ?>
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
		'check'   => '<circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.7 2.7L16.5 9.5"/>',
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
	if ( false !== strpos( $content, 'class="sbc-reviews"' ) ) {
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
		<h3 id="sbc-moments-title" class="moments-title"><?php echo esc_html( sbc_mod( 'moments_title' ) ); ?></h3>
		<ul class="moments-track" role="list">
			<?php
			foreach ( $ids as $id ) :
				// Cropping a wide photo into the tall 4:5 tile needs a proportionally wider file to stay sharp.
				$meta  = wp_get_attachment_metadata( $id );
				$ratio = ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) ? max( 1, ( $meta['width'] / $meta['height'] ) / 0.8 ) : 1;
				$sizes = sprintf( '(min-width: 1000px) %1$dvw, (min-width: 700px) %2$dvw, %3$dvw', ceil( 25 * $ratio ), ceil( 33 * $ratio ), ceil( 78 * $ratio ) );
				?>
				<li class="moments-item">
					<?php echo sbc_image( $id, 'large', array( 'alt' => __( 'Guests at a Social Booth Co photo booth event in Dallas–Fort Worth', 'socialboothco' ), 'sizes' => $sizes ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<p class="moments-more"><a class="btn btn--line" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'See the full gallery', 'socialboothco' ); ?></a></p>
	</div>
	<?php
}

/**
 * Clean up content built in WordPress or Elementor (e.g. /corporate-holiday-parties/):
 * current phone number, no consultation buttons, no removed photos, no duplicate reviews line,
 * and feature lists shown as the same cards as the homepage.
 */
add_filter( 'the_content', 'sbc_clean_page_content', 15 );
function sbc_clean_page_content( $content ) {
	if ( is_admin() || '' === $content ) {
		return $content;
	}
	$content = sbc_replace_old_phone( $content );

	// Consultation buttons/links (only "Check your date" stays). A paragraph that exists only for
	// the consultation link ("Not sure which package fits? …") goes with it.
	$content = preg_replace( '#<p\b[^>]*>(?:(?!</p>).)*?\bsbc-consult\b(?:(?!</p>).)*?</p>#s', '', $content );
	$content = preg_replace( '#<a\b[^>]*\bsbc-consult\b[^>]*>.*?</a>#s', '', $content );

	// The old one-line "Want to hear from past clients?" link; the full reviews section replaces it.
	$content = preg_replace( '#<p\b[^>]*class="[^"]*\bupgrades-help\b[^"]*\bsbc-reviews\b[^"]*"[^>]*>.*?</p>#s', '', $content );

	// Photos removed site-wide.
	foreach ( sbc_removed_photo_ids() as $id ) {
		$file = get_attached_file( $id );
		if ( ! $file ) {
			continue;
		}
		$stem    = preg_quote( pathinfo( preg_replace( '/-scaled(?=\.\w+$)/', '', $file ), PATHINFO_FILENAME ), '#' );
		$content = preg_replace( '#<figure\b[^>]*>(?:(?!</figure>).)*?' . $stem . '(?:-\d+x\d+)?(?:-scaled)?\.\w+(?:(?!</figure>).)*?</figure>#s', '', $content );
	}

	// Feature lists (<div class="feat"><div><h3>…</h3><p>…</p></div>…) become homepage-style cards.
	if ( false !== strpos( $content, 'class="feat"' ) ) {
		$content = preg_replace_callback(
			'#<div class="feat">(.*?)</div>(?:\s*</p>)?\s*</div>(?:\s*</p>)?\s*</section>#s',
			function ( $m ) {
				$n     = 0;
				$cards = preg_replace_callback(
					'#<div>\s*<h3>(.*?)</h3>\s*<p>(.*?)</p>(?:\s*</p>)*\s*</div>#s',
					function ( $c ) use ( &$n ) {
						$n++;
						$title = wp_strip_all_tags( $c[1] );
						$icon  = 'spark';
						foreach ( array( 'logo' => 'pen', 'design' => 'pen', 'brand' => 'pen', 'shar' => 'send', 'light' => 'light', 'gallery' => 'gallery', 'hands' => 'check', 'planner' => 'check', 'setup' => 'check' ) as $word => $name ) {
							if ( false !== stripos( $title, $word ) ) {
								$icon = $name;
								break;
							}
						}
						ob_start();
						sbc_icon( $icon );
						$svg = ob_get_clean();
						return '<article class="feat-card feat-card--' . ( ( $n - 1 ) % 4 + 1 ) . '"><span class="feat-icon" aria-hidden="true">' . $svg . '</span><h3>' . $c[1] . '</h3><p>' . $c[2] . '</p></article>';
					},
					$m[1]
				);
				return '<div class="feat">' . $cards . '</div></div></section>';
			},
			$content
		);
	}
	return $content;
}

/**
 * Gallery plugin: hide photos removed site-wide.
 */
add_filter(
	'sbcg_items',
	function ( $items ) {
		$removed = sbc_removed_photo_ids();
		return array_values(
			array_filter(
				$items,
				function ( $item ) use ( $removed ) {
					return ! in_array( (int) $item['id'], $removed, true );
				}
			)
		);
	}
);

/**
 * Hero strip and Moments: drop removed photos even if they're still listed in the Customizer.
 */
add_filter(
	'theme_mod_sbc_hero_photos',
	'sbc_strip_removed_ids'
);
add_filter(
	'theme_mod_sbc_moments_photos',
	'sbc_strip_removed_ids'
);
function sbc_strip_removed_ids( $value ) {
	if ( ! is_string( $value ) || '' === $value ) {
		return $value;
	}
	$ids = array_filter( array_map( 'absint', explode( ',', $value ) ) );
	return implode( ', ', array_diff( $ids, sbc_removed_photo_ids() ) );
}

/**
 * Page title whose words rise in one by one (same intro as the homepage hero).
 */
function sbc_animated_words( $text ) {
	$out = '';
	foreach ( preg_split( '/\s+/u', trim( $text ) ) as $i => $word ) {
		$out .= '<span class="mq-word" style="--i:' . (int) $i . '" aria-hidden="true">' . esc_html( $word ) . '</span> ';
	}
	return trim( $out );
}

function sbc_animated_title( $title, $class = '' ) {
	printf(
		'<h1%s aria-label="%s">%s</h1>',
		$class ? ' class="' . esc_attr( $class ) . '"' : '',
		esc_attr( $title ),
		sbc_animated_words( $title ) // phpcs:ignore WordPress.Security.EscapeOutput -- escaped per word.
	);
}

/**
 * Hero titles inside page content (e.g. the corporate page) get the same word-by-word intro.
 */
add_filter( 'the_content', 'sbc_animate_content_hero_title', 16 );
function sbc_animate_content_hero_title( $content ) {
	if ( is_admin() || false === strpos( $content, 'sbc-hero' ) ) {
		return $content;
	}
	return preg_replace_callback(
		'#<h1([^>]*)>([^<]+)</h1>#',
		function ( $m ) {
			$text = trim( html_entity_decode( $m[2], ENT_QUOTES, 'UTF-8' ) );
			if ( false !== strpos( $m[1], 'aria-label' ) ) {
				return $m[0];
			}
			return '<h1' . $m[1] . ' aria-label="' . esc_attr( $text ) . '">' . sbc_animated_words( $text ) . '</h1>';
		},
		$content,
		1
	);
}
