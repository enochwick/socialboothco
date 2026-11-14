<?php
/**
 * Packages: comparison table on wide screens, one card per package on phones.
 * Both are built from the same Customizer data.
 */

defined( 'ABSPATH' ) || exit;

$sbc_packages = sbc_packages();
$sbc_rows     = sbc_lines( 'compare', 4 );

$sbc_cell_class = function ( $value ) {
	if ( 0 === stripos( $value, 'included' ) ) {
		return 'yes';
	}
	if ( in_array( $value, array( '—', '-', '–', '' ), true ) ) {
		return 'no';
	}
	return '';
};
$sbc_cell = function ( $value ) {
	if ( in_array( $value, array( '—', '-', '–', '' ), true ) ) {
		return '<span aria-hidden="true">—</span><span class="screen-reader-text">' . esc_html__( 'Not included', 'socialboothco' ) . '</span>';
	}
	return esc_html( $value );
};
?>

<div class="compare">
	<table>
		<caption class="screen-reader-text"><?php esc_html_e( 'Photo booth packages compared', 'socialboothco' ); ?></caption>
		<thead>
			<tr>
				<td><p class="compare-note"><?php echo esc_html( sbc_mod( 'packages_note' ) ); ?></p></td>
				<?php foreach ( $sbc_packages as $sbc_p ) : ?>
					<th scope="col" class="<?php echo $sbc_p['popular'] ? 'hot' : ''; ?>">
						<?php if ( $sbc_p['popular'] ) : ?>
							<span class="tag"><?php esc_html_e( 'Most popular', 'socialboothco' ); ?></span>
						<?php endif; ?>
						<span class="pkg-name"><?php echo esc_html( $sbc_p['name'] ); ?></span>
						<span class="pkg-price"><?php echo esc_html( $sbc_p['price'] ); ?></span>
						<span class="pkg-best"><?php echo esc_html( $sbc_p['best'] ); ?></span>
					</th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $sbc_rows as $sbc_row ) : ?>
				<tr>
					<th scope="row"><?php echo esc_html( $sbc_row[0] ); ?></th>
					<?php for ( $sbc_c = 1; $sbc_c <= 3; $sbc_c++ ) : ?>
						<td class="<?php echo esc_attr( trim( $sbc_cell_class( $sbc_row[ $sbc_c ] ) . ( $sbc_packages[ $sbc_c - 1 ]['popular'] ? ' hot' : '' ) ) ); ?>"><?php echo $sbc_cell( $sbc_row[ $sbc_c ] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in closure. ?></td>
					<?php endfor; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<?php foreach ( $sbc_packages as $sbc_p ) : ?>
					<td class="<?php echo $sbc_p['popular'] ? 'hot' : ''; ?>"><?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', $sbc_p['popular'] ? 'berry' : 'line' ); ?></td>
				<?php endforeach; ?>
			</tr>
		</tfoot>
	</table>
</div>

<div class="pkg-cards">
	<p class="compare-note"><?php echo esc_html( sbc_mod( 'packages_note' ) ); ?></p>
	<?php foreach ( $sbc_packages as $sbc_i => $sbc_p ) : ?>
		<article class="pkg-card<?php echo $sbc_p['popular'] ? ' is-hot' : ''; ?>">
			<?php if ( $sbc_p['popular'] ) : ?>
				<span class="tag"><?php esc_html_e( 'Most popular', 'socialboothco' ); ?></span>
			<?php endif; ?>
			<h3 class="pkg-name"><?php echo esc_html( $sbc_p['name'] ); ?></h3>
			<p class="pkg-price"><?php echo esc_html( $sbc_p['price'] ); ?></p>
			<p class="pkg-best"><?php echo esc_html( $sbc_p['best'] ); ?></p>
			<dl>
				<?php foreach ( $sbc_rows as $sbc_row ) : ?>
					<div class="<?php echo esc_attr( $sbc_cell_class( $sbc_row[ $sbc_i + 1 ] ) ); ?>">
						<dt><?php echo esc_html( $sbc_row[0] ); ?></dt>
						<dd><?php echo $sbc_cell( $sbc_row[ $sbc_i + 1 ] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
			<?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', $sbc_p['popular'] ? 'berry' : 'line' ); ?>
		</article>
	<?php endforeach; ?>
</div>

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
		<p class="upgrades-help"><?php esc_html_e( 'Not sure which package fits?', 'socialboothco' ); ?> <?php sbc_button( __( 'Book a quick consultation', 'socialboothco' ), 'consult', 'text' ); ?></p>
	</div>
<?php endif; ?>
