<?php
/**
 * Appearance → Customize → Social Booth Co.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', 'sbc_customize_register' );
function sbc_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'sbc',
		array(
			'title'       => __( 'Social Booth Co', 'socialboothco' ),
			'description' => __( 'Edit the homepage, packages, FAQ and contact details. List fields take one item per line, with parts separated by a | character.', 'socialboothco' ),
			'priority'    => 20,
		)
	);

	$sections = array(
		'sbc_hero'     => array(
			'title'  => __( 'Homepage: hero', 'socialboothco' ),
			'fields' => array(
				'hero_title'   => array( 'textarea', __( 'Headline', 'socialboothco' ), __( 'Put {1} and {2} where the two small photos sit inside the headline. Remove them to show text only.', 'socialboothco' ) ),
				'hero_photo_1' => array( 'image', __( 'Headline photo {1} (landscape)', 'socialboothco' ) ),
				'hero_photo_2' => array( 'image', __( 'Headline photo {2} (portrait)', 'socialboothco' ) ),
				'hero_lede'    => array( 'textarea', __( 'Intro text', 'socialboothco' ) ),
				'reel_1'       => array( 'image', __( 'Photo row: 1', 'socialboothco' ) ),
				'reel_2'       => array( 'image', __( 'Photo row: 2', 'socialboothco' ) ),
				'reel_3'       => array( 'image', __( 'Photo row: 3', 'socialboothco' ) ),
				'reel_4'       => array( 'image', __( 'Photo row: 4', 'socialboothco' ) ),
			),
		),
		'sbc_features' => array(
			'title'  => __( 'Homepage: features', 'socialboothco' ),
			'fields' => array(
				'features_title' => array( 'text', __( 'Heading', 'socialboothco' ) ),
				'features'       => array( 'textarea', __( 'Features', 'socialboothco' ), __( 'One per line: Title | Description', 'socialboothco' ) ),
			),
		),
		'sbc_packages' => array(
			'title'  => __( 'Packages and prices', 'socialboothco' ),
			'fields' => array(
				'packages_title' => array( 'text', __( 'Heading', 'socialboothco' ) ),
				'packages_intro' => array( 'textarea', __( 'Intro', 'socialboothco' ) ),
				'pkg1_name'      => array( 'text', __( 'Package 1 name', 'socialboothco' ) ),
				'pkg1_price'     => array( 'text', __( 'Package 1 price', 'socialboothco' ) ),
				'pkg1_best'      => array( 'text', __( 'Package 1 best for', 'socialboothco' ) ),
				'pkg2_name'      => array( 'text', __( 'Package 2 name', 'socialboothco' ) ),
				'pkg2_price'     => array( 'text', __( 'Package 2 price', 'socialboothco' ) ),
				'pkg2_best'      => array( 'text', __( 'Package 2 best for', 'socialboothco' ) ),
				'pkg3_name'      => array( 'text', __( 'Package 3 name', 'socialboothco' ) ),
				'pkg3_price'     => array( 'text', __( 'Package 3 price', 'socialboothco' ) ),
				'pkg3_best'      => array( 'text', __( 'Package 3 best for', 'socialboothco' ) ),
				'pkg_popular'    => array( 'select', __( '“Most popular” package', 'socialboothco' ), '', array( '0' => __( 'None', 'socialboothco' ), '1' => '1', '2' => '2', '3' => '3' ) ),
				'packages_note'  => array( 'textarea', __( 'Note above the table', 'socialboothco' ) ),
				'compare'        => array( 'textarea', __( 'Comparison rows', 'socialboothco' ), __( 'One per line: Feature | Package 1 | Package 2 | Package 3. Write “Included” to highlight it, or “—” for not included.', 'socialboothco' ) ),
				'upgrades'       => array( 'textarea', __( 'Upgrades', 'socialboothco' ), __( 'One per line: Name | Price | Description', 'socialboothco' ) ),
			),
		),
		'sbc_events'   => array(
			'title'  => __( 'Homepage: event types', 'socialboothco' ),
			'fields' => array(
				'events_title' => array( 'text', __( 'Heading', 'socialboothco' ) ),
				'events_intro' => array( 'text', __( 'Intro', 'socialboothco' ) ),
				'event1'       => array( 'text', __( 'Event 1 (Title | Words)', 'socialboothco' ) ),
				'event1_photo' => array( 'image', __( 'Event 1 photo', 'socialboothco' ) ),
				'event2'       => array( 'text', __( 'Event 2 (Title | Words)', 'socialboothco' ) ),
				'event2_photo' => array( 'image', __( 'Event 2 photo', 'socialboothco' ) ),
				'event3'       => array( 'text', __( 'Event 3 (Title | Words)', 'socialboothco' ) ),
				'event3_photo' => array( 'image', __( 'Event 3 photo', 'socialboothco' ) ),
				'event4'       => array( 'text', __( 'Event 4 (Title | Words)', 'socialboothco' ) ),
				'event4_photo' => array( 'image', __( 'Event 4 photo', 'socialboothco' ) ),
			),
		),
		'sbc_faq'      => array(
			'title'  => __( 'FAQ', 'socialboothco' ),
			'fields' => array(
				'faq' => array( 'textarea', __( 'Questions and answers', 'socialboothco' ), __( 'Question on the first line, answer below it. Leave one blank line between questions. The first four also show on the homepage.', 'socialboothco' ) ),
			),
		),
		'sbc_contact'  => array(
			'title'  => __( 'Contact, footer and booking', 'socialboothco' ),
			'fields' => array(
				'phone'            => array( 'text', __( 'Phone', 'socialboothco' ) ),
				'email'            => array( 'text', __( 'Email', 'socialboothco' ) ),
				'areas'            => array( 'textarea', __( 'Areas we serve', 'socialboothco' ) ),
				'about'            => array( 'textarea', __( 'Footer description', 'socialboothco' ) ),
				'instagram'        => array( 'url', __( 'Instagram URL (leave empty to hide)', 'socialboothco' ) ),
				'facebook'         => array( 'url', __( 'Facebook URL (leave empty to hide)', 'socialboothco' ) ),
				'closing_title'    => array( 'text', __( 'Closing call-to-action heading', 'socialboothco' ) ),
				'closing_text'     => array( 'textarea', __( 'Closing call-to-action text', 'socialboothco' ) ),
				'booking_category' => array( 'text', __( 'Amelia category ID for “Check your date”', 'socialboothco' ) ),
				'consult_category' => array( 'text', __( 'Amelia category ID for “Book a consultation”', 'socialboothco' ) ),
				'booking_fallback' => array( 'text', __( 'Fallback booking page if Amelia is unavailable', 'socialboothco' ) ),
			),
		),
	);

	$priority = 10;
	foreach ( $sections as $section_id => $section ) {
		$wp_customize->add_section(
			$section_id,
			array(
				'title'    => $section['title'],
				'panel'    => 'sbc',
				'priority' => $priority += 10,
			)
		);

		foreach ( $section['fields'] as $key => $field ) {
			$type        = $field[0];
			$label       = $field[1];
			$description = isset( $field[2] ) ? $field[2] : '';
			$setting     = 'sbc_' . $key;
			$defaults    = sbc_defaults();

			$wp_customize->add_setting(
				$setting,
				array(
					'default'           => isset( $defaults[ $key ] ) ? $defaults[ $key ] : '',
					'sanitize_callback' => sbc_sanitizer( $type ),
					'transport'         => 'refresh',
				)
			);

			if ( 'image' === $type ) {
				$wp_customize->add_control(
					new WP_Customize_Media_Control(
						$wp_customize,
						$setting,
						array(
							'label'       => $label,
							'description' => $description,
							'section'     => $section_id,
							'mime_type'   => 'image',
						)
					)
				);
				continue;
			}

			$args = array(
				'label'       => $label,
				'description' => $description,
				'section'     => $section_id,
				'type'        => $type,
			);
			if ( 'select' === $type ) {
				$args['choices'] = $field[3];
			}
			if ( 'textarea' === $type ) {
				$args['input_attrs'] = array( 'rows' => 'faq' === $key || 'compare' === $key ? 14 : 5 );
			}
			$wp_customize->add_control( $setting, $args );
		}
	}
}

function sbc_sanitizer( $type ) {
	switch ( $type ) {
		case 'image':
			return 'absint';
		case 'url':
			return 'esc_url_raw';
		case 'textarea':
			return 'sanitize_textarea_field';
		default:
			return 'sanitize_text_field';
	}
}
