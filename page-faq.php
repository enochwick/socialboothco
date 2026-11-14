<?php
/**
 * /faq/ — questions come from Appearance → Customize → Social Booth Co → FAQ.
 */

defined( 'ABSPATH' ) || exit;

get_header();

// FAQPage structured data for search results.
$sbc_schema = array(
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => array_map(
		function ( $item ) {
			return array(
				'@type'          => 'Question',
				'name'           => $item[0],
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $item[1],
				),
			);
		},
		sbc_faq_items()
	),
);
?>

<?php sbc_page_intro( __( 'Photo booth FAQs', 'socialboothco' ), __( 'Everything you need to know before booking. Still curious? We’re happy to help.', 'socialboothco' ) ); ?>

<section class="sec sec--tight">
	<div class="wrap faq-split">
		<aside class="faq-contact">
			<h2 class="h3"><?php esc_html_e( 'Still have questions?', 'socialboothco' ); ?></h2>
			<p><?php esc_html_e( 'We’ll help you choose the right photo booth experience for your event.', 'socialboothco' ); ?></p>
			<ul>
				<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', sbc_mod( 'phone' ) ) ); ?>"><?php echo esc_html( sbc_mod( 'phone' ) ); ?></a></li>
				<li><a href="mailto:<?php echo esc_attr( antispambot( sbc_mod( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( sbc_mod( 'email' ) ) ); ?></a></li>
			</ul>
			<?php sbc_button( __( 'Book a consultation', 'socialboothco' ), 'consult', 'line' ); ?>
		</aside>
		<?php sbc_faq_list(); ?>
	</div>
</section>

<script type="application/ld+json"><?php echo wp_json_encode( $sbc_schema ); ?></script>

<?php
sbc_closing();
get_footer();
