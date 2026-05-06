<?php
/**
 * Template pour une recette individuelle (CPT: recette)
 */
get_header();
?>

<main class="site-main" role="main">
<?php while ( have_posts() ) : the_post(); ?>

    <!-- Hero de la recette -->
    <section style="
        background: linear-gradient(135deg, #3a0d0b, #6b1a14);
        padding: 100px 48px 60px;
        color: #fff;
        position: relative;
    ">
        <div style="max-width: 800px; margin: 0 auto;">
            <?php
            $types = get_the_terms( get_the_ID(), 'type_recette' );
            if ( $types && ! is_wp_error( $types ) ) :
                $type = $types[0];
            ?>
                <span style="
                    display: inline-block;
                    padding: 4px 14px;
                    border-radius: 20px;
                    background: rgba(201,154,46,0.25);
                    color: #e8c96a;
                    font-size: 12px;
                    font-weight: 600;
                    letter-spacing: 1.5px;
                    text-transform: uppercase;
                    margin-bottom: 16px;
                "><?php echo esc_html( $type->name ); ?></span>
            <?php endif; ?>

            <h1 style="font-size: 48px; margin-bottom: 16px; line-height: 1.15; color: #fff;">
                <?php the_title(); ?>
            </h1>

            <?php if ( has_excerpt() ) : ?>
                <p style="font-size: 18px; opacity: 0.8; font-weight: 300; max-width: 600px;">
                    <?php echo get_the_excerpt(); ?>
                </p>
            <?php endif; ?>

            <!-- Meta recette -->
            <div style="display: flex; gap: 32px; margin-top: 28px; flex-wrap: wrap;">
                <?php
                $prep    = get_post_meta( get_the_ID(), '_temps_preparation', true );
                $cuisson = get_post_meta( get_the_ID(), '_temps_cuisson', true );
                $portions = get_post_meta( get_the_ID(), '_portions', true );
                ?>
                <?php if ( $prep ) : ?>
                    <div style="text-align: center;">
                        <div style="font-size: 13px; opacity: 0.6; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Préparation</div>
                        <div style="font-size: 22px; font-weight: 600;"><?php echo esc_html( $prep ); ?></div>
                    </div>
                <?php endif; ?>
                <?php if ( $cuisson ) : ?>
                    <div style="text-align: center;">
                        <div style="font-size: 13px; opacity: 0.6; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Cuisson</div>
                        <div style="font-size: 22px; font-weight: 600;"><?php echo esc_html( $cuisson ); ?></div>
                    </div>
                <?php endif; ?>
                <?php if ( $portions ) : ?>
                    <div style="text-align: center;">
                        <div style="font-size: 13px; opacity: 0.6; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Portions</div>
                        <div style="font-size: 22px; font-weight: 600;"><?php echo esc_html( $portions ); ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Contenu de la recette -->
    <section style="max-width: 800px; margin: 0 auto; padding: 60px 48px;">
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 48px;">

            <!-- Ingrédients (sidebar gauche) -->
            <aside>
                <h3 style="font-size: 22px; margin-bottom: 20px;">Ingrédients</h3>
                <?php
                $ingredients = get_post_meta( get_the_ID(), '_ingredients', true );
                if ( $ingredients ) :
                    $items = explode( "\n", $ingredients );
                ?>
                    <ul style="list-style: none; padding: 0;">
                        <?php foreach ( $items as $item ) :
                            $item = trim( $item );
                            if ( empty( $item ) ) continue;
                        ?>
                            <li style="
                                padding: 10px 0;
                                border-bottom: 1px solid rgba(0,0,0,0.06);
                                font-size: 15px;
                                color: var(--charcoal);
                                display: flex;
                                align-items: center;
                                gap: 8px;
                            ">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: var(--crimson); flex-shrink: 0;"></span>
                                <?php echo esc_html( $item ); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Aucun ingrédient renseigné.</p>
                <?php endif; ?>
            </aside>

            <!-- Instructions -->
            <div>
                <h3 style="font-size: 22px; margin-bottom: 20px;">Préparation</h3>
                <div class="recipe-content" style="font-size: 16px; line-height: 1.8; color: var(--charcoal);">
                    <?php the_content(); ?>
                </div>
            </div>

        </div>

        <!-- Image de la recette -->
        <?php if ( has_post_thumbnail() ) : ?>
            <div style="margin-top: 48px; border-radius: 16px; overflow: hidden;">
                <?php the_post_thumbnail( 'recipe-hero', array( 'style' => 'width: 100%; height: auto;' ) ); ?>
            </div>
        <?php endif; ?>

        <!-- Boutons de partage -->
        <div style="margin-top: 48px; padding-top: 32px; border-top: 1px solid rgba(0,0,0,0.08); display: flex; align-items: center; gap: 16px;">
            <span style="font-size: 14px; font-weight: 600; color: var(--warm-gray); text-transform: uppercase; letter-spacing: 1px;">Partager :</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" style="color: var(--charcoal); font-size: 20px;">
                <i class="fa-brands fa-facebook-f"></i>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" target="_blank" rel="noopener" style="color: var(--charcoal); font-size: 20px;">
                <i class="fa-brands fa-x-twitter"></i>
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode( get_permalink() ); ?>&description=<?php echo urlencode( get_the_title() ); ?>" target="_blank" rel="noopener" style="color: var(--charcoal); font-size: 20px;">
                <i class="fa-brands fa-pinterest-p"></i>
            </a>
            <a href="https://wa.me/?text=<?php echo urlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" rel="noopener" style="color: var(--charcoal); font-size: 20px;">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
        </div>

    </section>

<?php endwhile; ?>
</main>

<?php get_footer(); ?>
