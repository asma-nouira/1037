<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package vsc-theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">

	<header id="masthead" class="site-header">
		<div class="site-branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo">
        Harissa <span>de Tunisie</span>
    </a>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation main-nav">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
			) );
			?>
		</nav><!-- #site-navigation -->
		<button class="menu-toggle" id="menu-toggle" aria-label="Menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>
	</header><!-- #masthead -->
<!-- ============================================
     BARRE SOCIALE FIXE
     ============================================ -->
<aside class="social-sidebar" aria-label="Réseaux sociaux">
    <?php if ( get_theme_mod( 'harissa_facebook' ) ) : ?>
        <a href="<?php echo esc_url( get_theme_mod( 'harissa_facebook' ) ); ?>" target="_blank" rel="noopener" title="Facebook">
            <i class="fa-brands fa-facebook-f"></i>
        </a>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'harissa_instagram' ) ) : ?>
        <a href="<?php echo esc_url( get_theme_mod( 'harissa_instagram' ) ); ?>" target="_blank" rel="noopener" title="Instagram">
            <i class="fa-brands fa-instagram"></i>
        </a>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'harissa_pinterest' ) ) : ?>
        <a href="<?php echo esc_url( get_theme_mod( 'harissa_pinterest' ) ); ?>" target="_blank" rel="noopener" title="Pinterest">
            <i class="fa-brands fa-pinterest-p"></i>
        </a>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'harissa_youtube' ) ) : ?>
        <a href="<?php echo esc_url( get_theme_mod( 'harissa_youtube' ) ); ?>" target="_blank" rel="noopener" title="YouTube">
            <i class="fa-brands fa-youtube"></i>
        </a>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'harissa_tiktok' ) ) : ?>
        <a href="<?php echo esc_url( get_theme_mod( 'harissa_tiktok' ) ); ?>" target="_blank" rel="noopener" title="TikTok">
            <i class="fa-brands fa-tiktok"></i>
        </a>
    <?php endif; ?>
</aside>

<!-- Spacer pour compenser le header fixed -->
<div style="height: 64px;"></div>

	<div id="content" class="site-content">
<div class="social-bar">
  <a class="social-btn" title="Facebook">f</a>
  <a class="social-btn" title="Instagram">ig</a>
  <a class="social-btn" title="Pinterest">P</a>
  <a class="social-btn" title="Partager">&#8599;</a>
</div>