<?php
/**
 * First four FAQs with a link to the FAQ page.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="sec">
	<div class="wrap faq-split">
		<div>
			<h2 class="h2"><?php esc_html_e( 'Photo booth rental FAQs', 'socialboothco' ); ?></h2>
			<p class="intro">
				<?php esc_html_e( 'Quick answers before you book.', 'socialboothco' ); ?>
				<a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'Read all FAQs', 'socialboothco' ); ?></a>
			</p>
		</div>
		<?php sbc_faq_list( 4 ); ?>
	</div>
</section>
