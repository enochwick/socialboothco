<?php
/**
 * Homepage: "Choose your backdrop" scroller. Photos come from Appearance → Customize →
 * Social Booth Co → Homepage: backdrops (Media Library IDs, in order).
 */

defined( 'ABSPATH' ) || exit;

$sbc_backdrops = array();
foreach ( array_filter( array_map( 'absint', explode( ',', (string) sbc_mod( 'backdrop_photos' ) ) ) ) as $sbc_id ) {
	if ( wp_attachment_is_image( $sbc_id ) ) {
		$sbc_backdrops[] = $sbc_id;
	}
}
if ( ! $sbc_backdrops ) {
	return;
}
$sbc_total = count( $sbc_backdrops );
?>
<section class="sec backdrops" id="backdrops" aria-labelledby="sbc-backdrops-title">
	<div class="wrap">
		<div class="backdrops-head">
			<div>
				<h2 class="h2" id="sbc-backdrops-title"><?php echo esc_html( sbc_mod( 'backdrops_title' ) ); ?></h2>
				<p class="intro"><?php echo esc_html( sbc_mod( 'backdrops_intro' ) ); ?></p>
			</div>
			<div class="backdrops-nav" data-scroller-nav hidden>
				<button class="backdrops-btn" type="button" data-scroller-prev aria-controls="sbc-backdrops-track" aria-label="<?php esc_attr_e( 'Previous backdrops', 'socialboothco' ); ?>">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M15 5l-7 7 7 7"/></svg>
				</button>
				<button class="backdrops-btn" type="button" data-scroller-next aria-controls="sbc-backdrops-track" aria-label="<?php esc_attr_e( 'Next backdrops', 'socialboothco' ); ?>">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 5l7 7-7 7"/></svg>
				</button>
			</div>
		</div>

		<?php /* A focusable, labelled scroll region: arrow keys, trackpads and swipes all scroll it natively. */ ?>
		<ul class="backdrops-track" id="sbc-backdrops-track" role="list" tabindex="0" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: number of backdrops */ __( '%d backdrop options. Scroll sideways to see more.', 'socialboothco' ), $sbc_total ) ); ?>" data-scroller>
			<?php foreach ( $sbc_backdrops as $sbc_n => $sbc_id ) : ?>
				<?php
				$sbc_alt = trim( (string) get_post_meta( $sbc_id, '_wp_attachment_image_alt', true ) );
				if ( '' === $sbc_alt ) {
					/* translators: 1: backdrop number, 2: total */
					$sbc_alt = sprintf( __( 'Photo booth backdrop option %1$d of %2$d', 'socialboothco' ), $sbc_n + 1, $sbc_total );
				}
				?>
				<li class="backdrops-item">
					<?php
					echo sbc_image( // phpcs:ignore WordPress.Security.EscapeOutput
						$sbc_id,
						'medium_large',
						array(
							'alt'   => $sbc_alt,
							'sizes' => '(min-width: 1200px) 290px, (min-width: 700px) 30vw, 46vw',
						)
					);
					?>
				</li>
			<?php endforeach; ?>
		</ul>
		<p class="backdrops-count">
			<?php
			/* translators: %d: number of backdrops */
			echo esc_html( sprintf( __( '%d backdrops to choose from, plus custom designs.', 'socialboothco' ), $sbc_total ) );
			?>
		</p>
	</div>
</section>
