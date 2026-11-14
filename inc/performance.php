<?php
/**
 * Speed: keep pages drawn by this theme free of assets they never use.
 *
 * Elementor and its add-ons (The Plus, Premium Addons, Ultimate Addons, Header Footer
 * Elementor, Sticky Header Effects) load their CSS/JS on every page. Pages built by this
 * theme's own templates don't contain any Elementor markup, so those files are dropped there.
 * Pages built with Elementor (Agreement, Thank you, forms, Check Availability) are untouched.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Asset sources that theme-built pages never need.
 */
function sbc_unneeded_asset_patterns() {
	return array(
		'/plugins/elementor/',
		'/plugins/elementor-pro/',
		'/plugins/the-plus-addons-for-elementor-page-builder/',
		'/plugins/premium-addons-for-elementor/',
		'/plugins/ultimate-elementor/',
		'/plugins/header-footer-elementor/',
		'/plugins/sticky-header-effects-for-elementor/',
		'/uploads/elementor/',
		'fonts.googleapis.com',
	);
}

function sbc_should_prune() {
	if ( is_admin() || is_customize_preview() || isset( $_GET['elementor-preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return false;
	}
	return sbc_is_theme_layout();
}

// Run at enqueue time and again right before printing, to catch late enqueues.
add_action( 'wp_enqueue_scripts', 'sbc_prune_assets', 9999 );
add_action( 'wp_print_styles', 'sbc_prune_assets', 1 );
add_action( 'wp_print_footer_scripts', 'sbc_prune_assets', 1 );
function sbc_prune_assets() {
	if ( ! sbc_should_prune() ) {
		return;
	}

	$patterns = sbc_unneeded_asset_patterns();
	foreach ( array( 'style' => wp_styles(), 'script' => wp_scripts() ) as $type => $deps ) {
		foreach ( (array) $deps->queue as $handle ) {
			if ( 0 === strpos( $handle, 'sbc-' ) ) {
				continue;
			}
			// Amelia's icon font for its Elementor editor widget; the booking popup doesn't use it.
			if ( 'amelia-elementor-widget-font' === $handle ) {
				wp_dequeue_style( $handle );
				continue;
			}
			$src  = isset( $deps->registered[ $handle ] ) ? (string) $deps->registered[ $handle ]->src : '';
			$drop = false;
			foreach ( $patterns as $pattern ) {
				if ( '' !== $src && false !== strpos( $src, $pattern ) ) {
					$drop = true;
					break;
				}
			}
			// Inline-only Elementor handles (no file) carry kit CSS and config.
			if ( ! $drop && '' === $src && preg_match( '/^(elementor|e-)/', $handle ) ) {
				$drop = true;
			}
			if ( $drop ) {
				'style' === $type ? wp_dequeue_style( $handle ) : wp_dequeue_script( $handle );
			}
		}
	}

	// The homepage, Experience, FAQ and Gallery templates contain no block-editor content.
	if ( is_front_page() || is_404() || is_page( array( 'experience', 'faq', 'gallery' ) ) ) {
		foreach ( array( 'wp-block-library', 'wp-block-library-theme', 'classic-theme-styles', 'global-styles' ) as $handle ) {
			wp_dequeue_style( $handle );
		}
	}
}

/**
 * WordPress's emoji detection script and styles: every modern browser shows emoji natively.
 */
add_action(
	'init',
	function () {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter(
			'wp_resource_hints',
			function ( $urls, $relation ) {
				if ( 'dns-prefetch' === $relation ) {
					$urls = array_filter(
						$urls,
						function ( $url ) {
							return false === strpos( is_array( $url ) ? $url['href'] : $url, 's.w.org' );
						}
					);
				}
				return $urls;
			},
			10,
			2
		);
	}
);
