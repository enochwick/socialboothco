<?php
/**
 * Gallery markup. Variables from sbcg_shortcode(): $atts, $items, $filters, $per_page, $hero_ids.
 */

defined( 'ABSPATH' ) || exit;

$sbcg_icon = function ( $name ) {
	$paths = array(
		'camera'   => '<path d="M14.5 4h-5L7.5 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3.5z"/><circle cx="12" cy="13" r="3.5"/>',
		'share'    => '<path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4z"/>',
		'gallery'  => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
		'sparkle'  => '<path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9z"/><path d="M19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8z"/>',
		'light'    => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
		'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
	);
	return '<svg class="sbcg-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
};

$sbcg_includes = array(
	array( 'camera', 'High-quality digital photos' ),
	array( 'share', 'Instant sharing via text, email & AirDrop' ),
	array( 'gallery', 'Live online gallery' ),
	array( 'sparkle', 'Filters and effects (limited)' ),
	array( 'light', 'Professional lighting' ),
	array( 'clock', 'On-time setup & breakdown' ),
);

$sbcg_book_url = esc_url( $atts['book_url'] );
?>
<div class="sbcg" data-per-page="<?php echo (int) $per_page; ?>">

	<?php if ( 'yes' === $atts['hero'] ) : ?>
	<section class="sbcg-hero" aria-labelledby="sbcg-title">
		<div class="sbcg-wrap sbcg-hero__inner">
			<div class="sbcg-hero__copy">
				<p class="sbcg-eyebrow">Photo Booth Gallery &middot; Dallas&ndash;Fort Worth</p>
				<h1 id="sbcg-title" class="sbcg-hero__title">Real moments. Real reactions. <em>Real memories.</em></h1>
				<p class="sbcg-hero__lede">Weddings, quincea&ntilde;eras, birthdays and corporate nights across DFW, plus a closer look at the prints, backdrops and setups your guests will step into.</p>
				<div class="sbcg-hero__actions">
					<a class="sbcg-btn sbcg-btn--primary" href="<?php echo $sbcg_book_url; ?>" data-sbcg-modal="<?php echo esc_attr( $atts['book_modal'] ); ?>">Check Your Date</a>
					<a class="sbcg-btn sbcg-btn--ghost" href="#sbcg-moments">Browse the gallery</a>
				</div>
			</div>

			<?php if ( $hero_ids ) : ?>
			<div class="sbcg-hero__strips" aria-hidden="true">
				<?php foreach ( array_chunk( $hero_ids, 3 ) as $sbcg_strip ) : ?>
				<div class="sbcg-strip">
					<?php foreach ( $sbcg_strip as $sbcg_id ) : ?>
					<div class="sbcg-strip__frame">
						<?php echo wp_get_attachment_image( $sbcg_id, 'medium_large', false, array( 'alt' => '', 'loading' => 'eager', 'sizes' => '(min-width: 1024px) 220px, 40vw' ) ); ?>
					</div>
					<?php endforeach; ?>
					<p class="sbcg-strip__brand">Social Booth Co.</p>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<section id="sbcg-moments" class="sbcg-moments" aria-labelledby="sbcg-moments-title">
		<div class="sbcg-wrap">
			<header class="sbcg-head">
				<h2 id="sbcg-moments-title" class="sbcg-h2">The Moments</h2>
				<p class="sbcg-count" aria-live="polite"><span data-sbcg-count><?php echo count( $items ); ?></span> photos</p>
			</header>

			<?php if ( $filters ) : ?>
			<div class="sbcg-filters" role="group" aria-label="Filter photos by category">
				<button type="button" class="sbcg-chip is-active" data-filter="*" aria-pressed="true">All <span class="sbcg-chip__n"><?php echo count( $items ); ?></span></button>
				<?php foreach ( $filters as $sbcg_f ) : ?>
				<button type="button" class="sbcg-chip" data-filter="<?php echo esc_attr( $sbcg_f['slug'] ); ?>" aria-pressed="false"><?php echo esc_html( $sbcg_f['name'] ); ?> <span class="sbcg-chip__n"><?php echo (int) $sbcg_f['count']; ?></span></button>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

			<ul class="sbcg-grid" role="list">
				<?php foreach ( $items as $sbcg_item ) : ?>
				<li class="sbcg-item<?php echo $sbcg_item['wide'] ? ' is-wide' : ''; ?>" data-cats="<?php echo esc_attr( implode( ' ', $sbcg_item['slugs'] ) ); ?>">
					<a class="sbcg-tile" href="<?php echo esc_url( $sbcg_item['full'] ); ?>" data-caption="<?php echo esc_attr( implode( ' · ', $sbcg_item['labels'] ) ); ?>">
						<?php
						if ( 'full' === $sbcg_item['size'] ) :
							// GIF: plain original file, no srcset (WordPress's resized GIFs are static).
							printf( '<img src="%s" alt="%s" loading="lazy" decoding="async">', esc_url( $sbcg_item['full'] ), esc_attr( $sbcg_item['alt'] ) );
						else :
						echo wp_get_attachment_image(
							$sbcg_item['id'],
							$sbcg_item['size'],
							false,
							array(
								'alt'     => $sbcg_item['alt'],
								'loading' => 'lazy',
								'sizes'   => $sbcg_item['wide'] ? '(min-width: 1024px) 600px, (min-width: 700px) 66vw, 100vw' : '(min-width: 1024px) 300px, (min-width: 700px) 33vw, 50vw',
							)
						);
						endif;
						?>
						<span class="sbcg-tile__label"><?php echo esc_html( implode( ' · ', $sbcg_item['labels'] ) ); ?></span>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>

			<div class="sbcg-more">
				<button type="button" class="sbcg-btn sbcg-btn--outline" data-sbcg-more hidden>View More Moments</button>
			</div>
		</div>
	</section>

	<?php if ( 'yes' === $atts['includes'] ) : ?>
	<section class="sbcg-includes" aria-labelledby="sbcg-includes-title">
		<div class="sbcg-wrap">
			<h2 id="sbcg-includes-title" class="sbcg-h2">Every booking includes</h2>
			<ul class="sbcg-includes__list" role="list">
				<?php foreach ( $sbcg_includes as $sbcg_inc ) : ?>
				<li class="sbcg-includes__item">
					<span class="sbcg-includes__icon"><?php echo $sbcg_icon( $sbcg_inc[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					<span class="sbcg-includes__text"><?php echo esc_html( $sbcg_inc[1] ); ?></span>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php endif; ?>

	<dialog class="sbcg-lightbox" aria-label="Photo viewer">
		<div class="sbcg-lightbox__stage">
			<img class="sbcg-lightbox__img" alt="" decoding="async">
		</div>
		<p class="sbcg-lightbox__meta"><span data-sbcg-lb-caption></span> <span class="sbcg-lightbox__pos" data-sbcg-lb-pos></span></p>
		<button type="button" class="sbcg-lb-btn sbcg-lb-btn--close" data-sbcg-lb="close" aria-label="Close"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
		<button type="button" class="sbcg-lb-btn sbcg-lb-btn--prev" data-sbcg-lb="prev" aria-label="Previous photo"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg></button>
		<button type="button" class="sbcg-lb-btn sbcg-lb-btn--next" data-sbcg-lb="next" aria-label="Next photo"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg></button>
	</dialog>
</div>
