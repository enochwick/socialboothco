<?php defined( 'ABSPATH' ) || exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'socialboothco' ); ?></a>

<header class="site-header">
	<div class="wrap">
		<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' ' . __( 'home', 'socialboothco' ) ); ?>"><?php sbc_logo( 'dark' ); ?></a>

		<nav class="nav" aria-label="<?php esc_attr_e( 'Main', 'socialboothco' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-list',
					'depth'          => 1,
					'fallback_cb'    => 'sbc_menu_fallback',
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', 'berry', 'header-cta' ); ?>
			<button class="menu-btn" type="button" aria-haspopup="dialog" aria-controls="mobile-menu" data-menu-open>
				<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'socialboothco' ); ?></span>
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
			</button>
		</div>
	</div>
</header>

<dialog class="mobile-menu" id="mobile-menu" aria-label="<?php esc_attr_e( 'Menu', 'socialboothco' ); ?>">
	<div class="mobile-menu__top">
		<?php sbc_logo( 'dark' ); ?>
		<button class="menu-btn" type="button" data-menu-close>
			<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'socialboothco' ); ?></span>
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
		</button>
	</div>
	<nav aria-label="<?php esc_attr_e( 'Mobile', 'socialboothco' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mobile-list',
				'depth'          => 1,
				'fallback_cb'    => 'sbc_menu_fallback',
			)
		);
		?>
	</nav>
	<div class="mobile-menu__cta">
		<?php sbc_button( __( 'Check your date', 'socialboothco' ), 'book', 'berry' ); ?>
		<a class="mobile-menu__contact" href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', sbc_mod( 'phone' ) ) ); ?>"><?php echo esc_html( sbc_mod( 'phone' ) ); ?></a>
	</div>
</dialog>

<main id="content" class="site-main">
