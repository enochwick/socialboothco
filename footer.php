<?php defined( 'ABSPATH' ) || exit; ?>
</main>

<footer class="site-footer">
	<div class="wrap">
		<div class="footer-grid">
			<div>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' ' . __( 'home', 'socialboothco' ) ); ?>"><?php sbc_logo( 'light' ); ?></a>
				<p class="footer-about"><?php echo esc_html( sbc_mod( 'about' ) ); ?></p>
				<?php
				$sbc_social = array_filter(
					array(
						'Instagram' => sbc_mod( 'instagram' ),
						'Facebook'  => sbc_mod( 'facebook' ),
					)
				);
				if ( $sbc_social ) :
					?>
					<ul class="footer-social">
						<?php foreach ( $sbc_social as $sbc_name => $sbc_url ) : ?>
							<li><a href="<?php echo esc_url( $sbc_url ); ?>" rel="noopener" target="_blank"><?php echo esc_html( $sbc_name ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<div>
				<h2 class="footer-h"><?php esc_html_e( 'Explore', 'socialboothco' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-list',
						'depth'          => 1,
						'fallback_cb'    => 'sbc_menu_fallback',
						'sbc_footer'     => true,
					)
				);
				?>
			</div>
			<div>
				<h2 class="footer-h"><?php esc_html_e( 'Contact', 'socialboothco' ); ?></h2>
				<ul class="footer-list">
					<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', sbc_mod( 'phone' ) ) ); ?>"><?php echo esc_html( sbc_mod( 'phone' ) ); ?></a></li>
					<li><a href="mailto:<?php echo esc_attr( antispambot( sbc_mod( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( sbc_mod( 'email' ) ) ); ?></a></li>
				</ul>
			</div>
			<div>
				<h2 class="footer-h"><?php esc_html_e( 'Areas we serve', 'socialboothco' ); ?></h2>
				<p><?php echo esc_html( sbc_mod( 'areas' ) ); ?></p>
			</div>
		</div>
		<div class="footer-legal">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'socialboothco' ); ?></span>
			<span><?php esc_html_e( 'Serving the entire Dallas–Fort Worth Metroplex.', 'socialboothco' ); ?></span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
