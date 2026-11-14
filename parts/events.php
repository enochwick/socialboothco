<?php
/**
 * Event types and "Moments from our events" (homepage and city landing pages).
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="sec sec--white">
	<div class="wrap">
		<h2 class="h2"><?php echo esc_html( sbc_mod( 'events_title' ) ); ?></h2>
		<p class="intro"><?php echo esc_html( sbc_mod( 'events_intro' ) ); ?></p>
		<div class="ev">
			<?php
			for ( $sbc_i = 1; $sbc_i <= 4; $sbc_i++ ) :
				$sbc_ev = array_pad( array_map( 'trim', explode( '|', sbc_mod( "event{$sbc_i}" ) ) ), 2, '' );
				if ( '' === $sbc_ev[0] ) {
					continue;
				}
				?>
				<figure class="ev-card ev-card--<?php echo (int) $sbc_i; ?>">
					<?php echo sbc_image( sbc_mod( "event{$sbc_i}_photo" ), 'large', array( 'alt' => sprintf( /* translators: %s: event type */ __( '%s with the Social Booth Co photo booth in Dallas–Fort Worth', 'socialboothco' ), $sbc_ev[0] ), 'sizes' => '(min-width: 900px) 40vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<figcaption>
						<h3><?php echo esc_html( $sbc_ev[0] ); ?></h3>
						<?php if ( $sbc_ev[1] ) : ?>
							<p><?php echo esc_html( $sbc_ev[1] ); ?></p>
						<?php endif; ?>
					</figcaption>
				</figure>
			<?php endfor; ?>
		</div>
		<?php sbc_moments(); ?>
	</div>
</section>
