<?php
/**
 * Feature cards (homepage and city landing pages).
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="sec sec--white">
	<div class="wrap">
		<h2 class="h2"><?php echo esc_html( sbc_mod( 'features_title' ) ); ?></h2>
		<div class="feat">
			<?php
			// Icons follow the order of the features: sharing, design, lighting, gallery.
			$sbc_feat_icons = array( 'send', 'pen', 'light', 'gallery' );
			foreach ( sbc_lines( 'features', 2 ) as $sbc_fi => $sbc_f ) :
				?>
				<article class="feat-card feat-card--<?php echo (int) $sbc_fi % 4 + 1; ?>">
					<span class="feat-icon" aria-hidden="true"><?php sbc_icon( isset( $sbc_feat_icons[ $sbc_fi ] ) ? $sbc_feat_icons[ $sbc_fi ] : 'spark' ); ?></span>
					<h3><?php echo esc_html( $sbc_f[0] ); ?></h3>
					<p><?php echo esc_html( $sbc_f[1] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
