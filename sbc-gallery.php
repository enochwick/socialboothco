<?php
/**
 * Plugin Name:       Social Booth Co Gallery
 * Description:       Filterable photo gallery for the /gallery/ page. Add the [sbc_gallery] shortcode, then tag photos under Media → Gallery Categories.
 * Version:           1.0.0
 * Author:            Social Booth Co.
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * License:           GPL-2.0-or-later
 * Text Domain:       sbc-gallery
 */

defined( 'ABSPATH' ) || exit;

define( 'SBCG_VERSION', '1.0.0' );
define( 'SBCG_FILE', __FILE__ );
define( 'SBCG_DIR', __DIR__ );
define( 'SBCG_TAX', 'sbc_gallery_category' );

require SBCG_DIR . '/includes/taxonomy.php';
require SBCG_DIR . '/includes/seed.php';
require SBCG_DIR . '/includes/render.php';

register_activation_hook( __FILE__, 'sbcg_activate' );

function sbcg_activate() {
	sbcg_register_taxonomy();
	sbcg_seed();
}
