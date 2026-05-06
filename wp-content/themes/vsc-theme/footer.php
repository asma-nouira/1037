<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package vsc-theme
 */

?>

	</div><!-- #content -->

	<footer class="site-footer" role="contentinfo">
    <div class="footer-grid">

        <!-- Colonne marque -->
        <div class="footer-brand">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" style="margin-bottom: 16px; display: block;">
                Harissa <span>de Tunisie</span>
            </a>
            <p>Le guide ultime de la harissa tunisienne — histoire, recettes authentiques, et culture culinaire.</p>

            <!-- Icônes sociales dans le footer -->
            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <?php if ( get_theme_mod( 'harissa_facebook' ) ) : ?>
                    <a href="<?php echo esc_url( get_theme_mod( 'harissa_facebook' ) ); ?>" target="_blank" rel="noopener" style="color: rgba(255,255,255,0.5); font-size: 18px; transition: color 0.3s;">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                <?php endif; ?>
                <?php if ( get_theme_mod( 'harissa_instagram' ) ) : ?>
                    <a href="<?php echo esc_url( get_theme_mod( 'harissa_instagram' ) ); ?>" target="_blank" rel="noopener" style="color: rgba(255,255,255,0.5); font-size: 18px; transition: color 0.3s;">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                <?php endif; ?>
                <?php if ( get_theme_mod( 'harissa_pinterest' ) ) : ?>
                    <a href="<?php echo esc_url( get_theme_mod( 'harissa_pinterest' ) ); ?>" target="_blank" rel="noopener" style="color: rgba(255,255,255,0.5); font-size: 18px; transition: color 0.3s;">
                        <i class="fa-brands fa-pinterest-p"></i>
                    </a>
                <?php endif; ?>
                <?php if ( get_theme_mod( 'harissa_youtube' ) ) : ?>
                    <a href="<?php echo esc_url( get_theme_mod( 'harissa_youtube' ) ); ?>" target="_blank" rel="noopener" style="color: rgba(255,255,255,0.5); font-size: 18px; transition: color 0.3s;">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Colonnes de widgets -->
        <div class="footer-col">
            <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                <?php dynamic_sidebar( 'footer-1' ); ?>
            <?php else : ?>
                <h4>Explorer</h4>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/#histoire' ) ); ?>">Histoire</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/#recettes' ) ); ?>">Recettes</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/#culture' ) ); ?>">Culture</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Blog</a></li>
                </ul>
            <?php endif; ?>
        </div>

        <div class="footer-col">
            <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                <?php dynamic_sidebar( 'footer-2' ); ?>
            <?php else : ?>
                <h4>Recettes</h4>
                <ul>
                    <li><a href="#">Harissa classique</a></li>
                    <li><a href="#">Ojja aux merguez</a></li>
                    <li><a href="#">Lablabi</a></li>
                    <li><a href="#">Couscous</a></li>
                </ul>
            <?php endif; ?>
        </div>

        <div class="footer-col">
            <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                <?php dynamic_sidebar( 'footer-3' ); ?>
            <?php else : ?>
                <h4>Contact</h4>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Nous contacter</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/mentions-legales' ) ); ?>">Mentions légales</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/politique-confidentialite' ) ); ?>">Confidentialité</a></li>
                </ul>
            <?php endif; ?>
        </div>

    </div>

    <!-- Footer bottom -->
    <div class="footer-bottom">
        <span>&copy; <?php echo date( 'Y' ); ?> Harissa de Tunisie. Tous droits réservés.</span>

        <div class="lang-switch">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="active">FR</a>
            <?php if ( get_theme_mod( 'harissa_lang_ar' ) ) : ?>
                <a href="<?php echo esc_url( get_theme_mod( 'harissa_lang_ar' ) ); ?>">AR</a>
            <?php else : ?>
                <a href="#">AR</a>
            <?php endif; ?>
            <?php if ( get_theme_mod( 'harissa_lang_en' ) ) : ?>
                <a href="<?php echo esc_url( get_theme_mod( 'harissa_lang_en' ) ); ?>">EN</a>
            <?php else : ?>
                <a href="#">EN</a>
            <?php endif; ?>
        </div>
    </div>

</footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
