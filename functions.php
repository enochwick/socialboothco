<?php
/**
 * Social Booth Co theme setup.
 */

defined( 'ABSPATH' ) || exit;

define( 'SBC_THEME_VERSION', '1.0.0' );

require get_template_directory() . '/inc/content.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/booking.php';
require get_template_directory() . '/inc/template-tags.php';

add_action( 'after_setup_theme', 'sbc_setup' );
function sbc_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 520,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Main menu', 'socialboothco' ),
			'footer'  => __( 'Footer menu', 'socialboothco' ),
		)
	);
}

add_action( 'wp_enqueue_scripts', 'sbc_assets' );
function sbc_assets() {
	$dir = get_template_directory();
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'sbc-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Urbanist:wght@500;600;700;800&display=swap', array(), null );
	wp_enqueue_style( 'sbc-theme', $uri . '/assets/theme.css', array(), SBC_THEME_VERSION . '.' . filemtime( $dir . '/assets/theme.css' ) );
	wp_enqueue_script( 'sbc-theme', $uri . '/assets/theme.js', array(), SBC_THEME_VERSION . '.' . filemtime( $dir . '/assets/theme.js' ), true );
}

add_filter( 'wp_resource_hints', 'sbc_resource_hints', 10, 2 );
function sbc_resource_hints( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}

/**
 * Pages drawn by this theme's own templates should not inherit Elementor's global
 * kit typography/colors. Elementor scopes those styles to an `elementor-kit-N` body
 * class, so drop it on theme-built pages. Elementor-built pages keep it.
 */
add_filter( 'body_class', 'sbc_body_class', 20 );
function sbc_body_class( $classes ) {
	$classes[] = 'sbc';
	if ( sbc_is_theme_layout() ) {
		$classes = array_filter(
			$classes,
			function ( $class ) {
				return 0 !== strpos( $class, 'elementor-kit-' );
			}
		);
		$classes[] = 'sbc-layout';
	}
	return array_values( $classes );
}

/**
 * True when the current view is drawn entirely by this theme (not by Elementor content).
 */
function sbc_is_theme_layout() {
	if ( is_front_page() || is_404() || is_search() || is_home() || is_archive() ) {
		return true;
	}
	if ( is_page( array( 'experience', 'faq', 'gallery' ) ) ) {
		return true;
	}
	return is_singular() && ! sbc_is_elementor_page();
}

function sbc_is_elementor_page( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_queried_object_id();
	return $post_id && 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true );
}

/**
 * Menu fallback so the header works before a menu is assigned under Appearance → Menus.
 * Links match the current site's URLs.
 */
function sbc_menu_fallback( $args = array() ) {
	$links = array(
		home_url( '/experience/' ) => __( 'Experience', 'socialboothco' ),
		home_url( '/#pricing' )    => __( 'Packages', 'socialboothco' ),
		home_url( '/gallery/' )    => __( 'Gallery', 'socialboothco' ),
		home_url( '/faq/' )        => __( 'FAQ', 'socialboothco' ),
	);
	if ( ! empty( $args['sbc_footer'] ) ) {
		$links = array( home_url( '/' ) => __( 'Home', 'socialboothco' ) ) + $links + array( home_url( '/agreement/' ) => __( 'Agreement', 'socialboothco' ) );
	}
	echo '<ul class="' . esc_attr( isset( $args['menu_class'] ) ? $args['menu_class'] : 'menu' ) . '">';
	foreach ( $links as $url => $label ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}
