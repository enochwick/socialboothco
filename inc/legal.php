<?php
/**
 * Agreement page (/agreement/, built in Elementor). Its text was pasted in with small <h5> section
 * titles, underlined bold paragraphs as subheads, an unlinked table of contents and stray "###".
 * This tidies the output (the saved content is untouched): section titles become h2 with anchors,
 * subheads become h3, the contents list links to each section. Styles live in theme.css (.sbc-legal).
 */

defined( 'ABSPATH' ) || exit;

function sbc_is_legal_page() {
	return is_page( 'agreement' );
}

add_filter( 'body_class', 'sbc_legal_body_class' );
function sbc_legal_body_class( $classes ) {
	if ( sbc_is_legal_page() ) {
		$classes[] = 'sbc-legal';
	}
	return $classes;
}

add_filter( 'the_content', 'sbc_legal_content', 20 );
function sbc_legal_content( $content ) {
	if ( is_admin() || ! sbc_is_legal_page() || '' === $content ) {
		return $content;
	}
	return sbc_legal_format( $content );
}

/**
 * The transformation itself (kept separate so it can be tested on its own).
 */
function sbc_legal_format( $content ) {
	$original = $content;
	$sections = array(); // Section title (lowercase, no number) => anchor id.

	// Section titles: <h5><b><br />1. Deposit &amp; Payment Terms</b></h5> → <h2 id="terms-…">.
	$content = preg_replace_callback(
		'#<h5\b[^>]*>(.*?)</h5>#s',
		function ( $m ) use ( &$sections ) {
			$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( preg_replace( '#<br\s*/?>#i', ' ', $m[1] ) ) ) );
			if ( '' === $text ) {
				return '';
			}
			$plain = trim( preg_replace( '/^\d+\.\s*/', '', $text ) );
			if ( 0 === stripos( $plain, 'Important' ) ) {
				return '<h2 class="legal-callout-title">' . $text . '</h2>';
			}
			if ( 0 === stripos( $plain, 'Table of Contents' ) ) {
				return '<h2 class="legal-toc-title" id="terms-contents">' . $text . '</h2>';
			}
			$id = 'terms-' . sanitize_title( html_entity_decode( $plain, ENT_QUOTES, 'UTF-8' ) );
			$sections[ sbc_legal_key( $plain ) ] = $id;
			return '<h2 class="legal-h2" id="' . esc_attr( $id ) . '">' . $text . '</h2>';
		},
		$content
	);

	// List items wrapped in their own paragraph: <li><p>…</p></li> → <li>…</li>. (First, so a bold list item isn't taken for a subhead.)
	$content = preg_replace( '#<li\b([^>]*)>\s*<p\b[^>]*>(.*?)</p>\s*</li>#s', '<li$1>$2</li>', $content );

	// Subheads: a paragraph that is only bold text (often underlined) → h3.
	$content = preg_replace(
		'#<p\b[^>]*>\s*(?:<span\b[^>]*>\s*)?<(b|strong)\b[^>]*>([^<]+)</\1>\s*(?:</span>\s*)?</p>#',
		'<h3 class="legal-h3">$2</h3>',
		$content
	);

	// Table of contents: link each entry to its section and wrap it in a nav card.
	$content = preg_replace_callback(
		'#(<h2 class="legal-toc-title"[^>]*>.*?</h2>)\s*(<ol\b[^>]*>)(.*?)</ol>#s',
		function ( $m ) use ( $sections ) {
			$items = preg_replace_callback(
				'#<li\b[^>]*>(.*?)</li>#s',
				function ( $li ) use ( $sections ) {
					$text = trim( wp_strip_all_tags( $li[1] ) );
					$key  = sbc_legal_key( $text );
					return isset( $sections[ $key ] )
						? '<li><a href="#' . esc_attr( $sections[ $key ] ) . '">' . $text . '</a></li>'
						: '<li>' . $text . '</li>';
				},
				$m[3]
			);
			return '<nav class="legal-toc" aria-labelledby="terms-contents">' . $m[1] . '<ol>' . $items . '</ol></nav>';
		},
		$content
	);

	// Leftovers: dividers (the section titles carry their own rule), empty paragraphs, "###".
	$content = preg_replace( '#<hr\b[^>]*>#', '', $content );
	$content = preg_replace( '#<p\b[^>]*>(?:\s|&nbsp;|\xC2\xA0)*</p>#u', '', $content );
	$content = preg_replace( '#<p\b[^>]*>\s*\#{2,}\s*</p>#', '', $content );

	// A regex that fails (e.g. on a huge page) returns null and every later step then yields an
	// empty string: in that case show the page exactly as Elementor rendered it.
	return ( is_string( $content ) && '' !== trim( $content ) ) ? $content : $original;
}

/**
 * Comparable form of a section title: no number, entities decoded, lowercase, single spaces.
 */
function sbc_legal_key( $text ) {
	$text = html_entity_decode( wp_strip_all_tags( $text ), ENT_QUOTES, 'UTF-8' );
	$text = preg_replace( '/^\d+\.\s*/', '', trim( $text ) );
	return strtolower( preg_replace( '/\s+/u', ' ', $text ) );
}
