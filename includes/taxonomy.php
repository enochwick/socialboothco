<?php
/**
 * "Gallery Category" taxonomy on Media Library items.
 * A photo shows up in the gallery once it has at least one Gallery Category.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'sbcg_register_taxonomy' );

function sbcg_register_taxonomy() {
	register_taxonomy(
		SBCG_TAX,
		'attachment',
		array(
			'labels'                => array(
				'name'          => __( 'Gallery Categories', 'sbc-gallery' ),
				'singular_name' => __( 'Gallery Category', 'sbc-gallery' ),
				'menu_name'     => __( 'Gallery Categories', 'sbc-gallery' ),
				'all_items'     => __( 'All Gallery Categories', 'sbc-gallery' ),
				'edit_item'     => __( 'Edit Gallery Category', 'sbc-gallery' ),
				'add_new_item'  => __( 'Add Gallery Category', 'sbc-gallery' ),
				'search_items'  => __( 'Search Gallery Categories', 'sbc-gallery' ),
				'not_found'     => __( 'No gallery categories yet.', 'sbc-gallery' ),
			),
			'public'                => false,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'show_in_rest'          => true,
			'hierarchical'          => true, // Checkboxes on the Edit Media screen.
			'rewrite'               => false,
			'query_var'             => false,
			'update_count_callback' => '_update_generic_term_count', // Attachments use the "inherit" status.
		)
	);
}

/**
 * Filter order: these slugs first, any new categories after them alphabetically.
 */
function sbcg_default_terms() {
	return array(
		'weddings'    => 'Weddings',
		'corporate'   => 'Corporate',
		'parties'     => 'Parties',
		'quinceanera' => 'Quinceañera',
		'backdrops'   => 'Backdrops',
		'prints'      => 'Prints',
		'setups'      => 'Setups',
	);
}
