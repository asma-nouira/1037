<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package vsc-theme
 */

get_header();
?>

	<div id="primary" class="content-area">
	<main class="site-main" role="main">

    <?php if ( have_posts() ) : ?>

        <?php if ( is_home() && ! is_front_page() ) : ?>
            <header class="page-header" style="padding: 60px 48px 40px; background: var(--sand);">
                <div class="section-tag">Blog</div>
                <h1 class="section-title">Nos <em>articles</em></h1>
            </header>
        <?php endif; ?>

        <div style="max-width: 1100px; margin: 0 auto; padding: 40px 48px; display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 28px;">

            <?php while ( have_posts() ) : the_post(); ?>

                <article class="recipe-card" id="post-<?php the_ID(); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="recipe-card__image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'recipe-card' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="recipe-card__body">
                        <h3><a href="<?php the_permalink(); ?>" style="color: var(--charcoal); text-decoration: none;"><?php the_title(); ?></a></h3>
                        <p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
                        <div class="recipe-card__meta">
                            <span><i class="fa-regular fa-calendar"></i> <?php echo get_the_date( 'j M Y' ); ?></span>
                            <span><i class="fa-regular fa-user"></i> <?php the_author(); ?></span>
                        </div>
                    </div>
                </article>

            <?php endwhile; ?>

        </div>

        <!-- Pagination -->
        <div style="text-align: center; padding: 40px;">
            <?php
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => '&larr; Précédent',
                'next_text' => 'Suivant &rarr;',
            ) );
            ?>
        </div>

    <?php else : ?>

        <div style="text-align: center; padding: 100px 48px;">
            <h2>Aucun contenu trouvé</h2>
            <p>La page que vous cherchez n'existe pas encore.</p>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary">Retour à l'accueil</a>
        </div>

    <?php endif; ?>

</main>

	</div><!-- #primary -->

<?php
get_footer();
