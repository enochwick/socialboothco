<?php
/**
 * Packages as cards: a soft colored top (blurred color blobs + film grain) with name,
 * description, price and booking button, then the feature list and a footer note.
 * Content comes from Appearance → Customize → Social Booth Co → Packages and prices.
 */

defined( 'ABSPATH' ) || exit;

$sbc_packages = sbc_packages();
$sbc_palettes = array( 'blush', 'berry', 'navy' );
$sbc_label    = sbc_mod( 'pkg_price_label' );
?>

<div class="plans">
	<?php foreach ( $sbc_packages as $sbc_i => $sbc_p ) : ?>
		<?php
		$sbc_palette = $sbc_palettes[ $sbc_i % 3 ];
		$sbc_grain   = 'sbc-grain-' . ( $sbc_i + 1 );
		?>
		<article class="plan plan--<?php echo esc_attr( $sbc_palette ); ?><?php echo $sbc_p['popular'] ? ' is-popular' : ''; ?>">
			<div class="plan-top">
				<span class="plan-blob plan-blob--a" aria-hidden="true"></span>
				<span class="plan-blob plan-blob--b" aria-hidden="true"></span>
				<svg class="plan-grain" aria-hidden="true" focusable="false">
					<filter id="<?php echo esc_attr( $sbc_grain ); ?>">
						<feTurbulence type="fractalNoise" baseFrequency="0.68" numOctaves="3" stitchTiles="stitch" />
						<feColorMatrix type="saturate" values="0" />
					</filter>
					<rect width="100%" height="100%" filter="url(#<?php echo esc_attr( $sbc_grain ); ?>)" />
				</svg>

				<div class="plan-body">
					<div class="plan-badges">
						<span class="plan-mark" aria-hidden="true">
							<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><rect x="5" y="5" width="11" height="17" rx="5.5"/><rect x="8" y="9" width="5" height="8" rx="2.5" fill="#fff" opacity=".9"/><path d="M19 1.5c.3 1.9 1.6 3.2 3.5 3.5-1.9.3-3.2 1.6-3.5 3.5-.3-1.9-1.6-3.2-3.5-3.5 1.9-.3 3.2-1.6 3.5-3.5z"/></svg>
						</span>
						<?php if ( $sbc_p['popular'] ) : ?>
							<span class="plan-flag"><?php esc_html_e( 'Most popular', 'socialboothco' ); ?></span>
						<?php endif; ?>
					</div>

					<div>
						<h3 class="plan-name"><?php echo esc_html( $sbc_p['name'] ); ?></h3>
						<p class="plan-desc"><?php echo esc_html( $sbc_p['best'] ); ?></p>
					</div>

					<div>
						<?php if ( $sbc_label ) : ?>
							<p class="plan-price-label"><?php echo esc_html( $sbc_label ); ?></p>
						<?php endif; ?>
						<p class="plan-price"><?php echo esc_html( $sbc_p['price'] ); ?></p>
					</div>

					<?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', $sbc_p['popular'] || 'navy' === $sbc_palette ? 'dark' : 'soft', 'plan-cta' ); ?>
				</div>
			</div>

			<div class="plan-details">
				<?php if ( $sbc_p['features'] ) : ?>
					<ul class="plan-features">
						<?php foreach ( $sbc_p['features'] as $sbc_feature ) : ?>
							<?php if ( ':' === substr( $sbc_feature, -1 ) ) : // Lead-in line such as "Everything in The Social Booth, plus:". ?>
								<li class="plan-lead"><?php echo esc_html( $sbc_feature ); ?></li>
								<?php continue; ?>
							<?php endif; ?>
							<li>
								<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7"/></svg>
								<?php echo esc_html( $sbc_feature ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( $sbc_p['note'] ) : ?>
					<p class="plan-note"><?php echo esc_html( $sbc_p['note'] ); ?></p>
				<?php endif; ?>
			</div>
		</article>
	<?php endforeach; ?>
</div>

<?php if ( sbc_mod( 'packages_note' ) ) : ?>
	<p class="plans-note"><?php echo esc_html( sbc_mod( 'packages_note' ) ); ?></p>
<?php endif; ?>

<?php $sbc_offers = sbc_lines( 'offers', 2 ); ?>
<?php if ( $sbc_offers ) : ?>
	<ul class="offers">
		<?php foreach ( $sbc_offers as $sbc_o ) : ?>
			<li class="offer">
				<span class="offer-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 13.4l-7.2 7.2a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg></span>
				<span>
					<strong class="offer-title"><?php echo esc_html( $sbc_o[0] ); ?></strong>
					<?php if ( $sbc_o[1] ) : ?>
						<span class="offer-text"><?php echo esc_html( str_replace( '{holiday_year}', (string) sbc_holiday_year(), $sbc_o[1] ) ); ?></span>
					<?php endif; ?>
				</span>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<?php $sbc_upgrades = sbc_lines( 'upgrades', 3 ); ?>
<?php if ( $sbc_upgrades ) : ?>
	<div class="upgrades">
		<h3 class="upgrades-h"><?php esc_html_e( 'Popular upgrades', 'socialboothco' ); ?></h3>
		<ul>
			<?php foreach ( $sbc_upgrades as $sbc_u ) : ?>
				<li>
					<span class="up-name"><?php echo esc_html( $sbc_u[0] ); ?></span>
					<span class="up-price"><?php echo esc_html( $sbc_u[1] ); ?></span>
					<?php if ( $sbc_u[2] ) : ?>
						<span class="up-desc"><?php echo esc_html( $sbc_u[2] ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
