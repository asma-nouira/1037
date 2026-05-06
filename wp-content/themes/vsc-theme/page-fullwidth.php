<?php
/**
 * Template Name: Page Builder (Pleine largeur)
 *
 * Template sans sidebar, pleine largeur, idéal pour
 * construire les pages avec WPBakery Page Builder.
 * Utilise ce template pour : Accueil, Histoire, Culture, Contact
 */
get_header();
?>

<main class="site-main site-main--fullwidth" role="main">

    <?php
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
    ?>

</main>

<?php get_footer(); ?>
