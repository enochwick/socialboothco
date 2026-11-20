<?php
/**
 * Homepage: "Our gear" scroller, below the backdrops. Items come from Appearance → Customize →
 * Social Booth Co → Homepage: gear, one per line: Name | Description | Photo. The photo is a Media
 * Library ID or a file name from assets/img/gear/ in the theme. Shares the backdrop scroller's script.
 */

defined( 'ABSPATH' ) || exit;

$sbc_gear = array();
foreach ( preg_split( '/\r\n|\r|\n/', (string) sbc_mod( 'gear_items' ) ) as $sbc_line ) {
	$sbc_parts = array_pad( array_map( 'trim', explode( '|', $sbc_line ) ), 3, '' );
	if ( '' !== $sbc_parts[0] ) {
		$sbc_gear[] = $sbc_parts;
	}
}
if ( ! $sbc_gear ) {
	return;
}
$sbc_gear_dir = get_template_directory() . '/assets/img/gear/';
$sbc_gear_uri = get_template_directory_uri() . '/assets/img/gear/';
?>
<section class="sec gear" id="gear" aria-labelledby="sbc-gear-title">
	<div class="wrap">
		<div class="backdrops-head">
			<div>
				<h2 class="h2" id="sbc-gear-title"><?php echo esc_html( sbc_mod( 'gear_title' ) ); ?></h2>
				<p class="intro"><?php echo esc_html( sbc_mod( 'gear_intro' ) ); ?></p>
			</div>
			<div class="backdrops-nav" data-scroller-nav hidden>
				<button class="backdrops-btn" type="button" data-scroller-prev aria-controls="sbc-gear-track" aria-label="<?php esc_attr_e( 'Previous gear', 'socialboothco' ); ?>">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M15 5l-7 7 7 7"/></svg>
				</button>
				<button class="backdrops-btn" type="button" data-scroller-next aria-controls="sbc-gear-track" aria-label="<?php esc_attr_e( 'Next gear', 'socialboothco' ); ?>">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 5l7 7-7 7"/></svg>
				</button>
			</div>
		</div>

		<ul class="backdrops-track gear-track" id="sbc-gear-track" role="list" tabindex="0" aria-label="<?php esc_attr_e( 'Our gear. Scroll sideways to see more.', 'socialboothco' ); ?>" data-scroller>
			<?php foreach ( $sbc_gear as $sbc_item ) : ?>
				<li class="gear-item">
					<div class="gear-photo">
						<?php
						$sbc_alt = $sbc_item[0];
						if ( ctype_digit( $sbc_item[2] ) ) {
							echo sbc_image( $sbc_item[2], 'medium_large', array( 'alt' => $sbc_alt, 'sizes' => '(min-width: 1200px) 290px, (min-width: 700px) 30vw, 70vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
						} elseif ( '' !== $sbc_item[2] && is_file( $sbc_gear_dir . basename( $sbc_item[2] ) ) ) {
							printf( '<img src="%s" alt="%s" loading="lazy" decoding="async">', esc_url( $sbc_gear_uri . basename( $sbc_item[2] ) ), esc_attr( $sbc_alt ) );
						}
						?>
						<span class="gear-tag"><?php esc_html_e( 'Rental available', 'socialboothco' ); ?></span>
					</div>
					<h3><?php echo esc_html( $sbc_item[0] ); ?></h3>
					<?php if ( $sbc_item[1] ) : ?>
						<p><?php echo esc_html( $sbc_item[1] ); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<p class="backdrops-count gear-note">
			<?php esc_html_e( 'Planning your own setup?', 'socialboothco' ); ?>
			<a href="<?php echo esc_url( 'mailto:' . antispambot( sbc_mod( 'email' ) ) . '?subject=' . rawurlencode( 'Gear rental' ) ); ?>"><?php esc_html_e( 'Ask about renting our gear', 'socialboothco' ); ?></a>
		</p>
	</div>
</section>
