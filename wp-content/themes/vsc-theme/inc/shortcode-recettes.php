<?php
/**
 * Harissa — Shortcode [recettes_populaires]
 * 
 * Affiche dynamiquement les recettes du CPT "recette"
 * sous forme de cartes identiques à la maquette.
 *
 * UTILISATION DANS VCE :
 * ----------------------
 * 1. Ajoute un élément "Text Block" ou "Raw HTML"
 * 2. Colle : [recettes_populaires]
 *
 * OPTIONS :
 * ---------
 * [recettes_populaires]                          → 3 dernières recettes
 * [recettes_populaires nombre="6"]               → 6 recettes
 * [recettes_populaires nombre="3" colonnes="2"]  → 3 recettes sur 2 colonnes
 * [recettes_populaires type="classique"]          → Filtrer par type
 * [recettes_populaires ordre="rand"]              → Ordre aléatoire
 * [recettes_populaires voir_tout="non"]           → Masquer le lien "Voir toutes"
 * [recettes_populaires titre="Nos créations"]     → Changer le titre
 */

function harissa_recettes_populaires_shortcode( $atts ) {

    $atts = shortcode_atts( array(
        'nombre'   => 3,
        'colonnes' => 3,
        'type'     => '',        // slug du terme type_recette
        'ordre'    => 'date',    // date, rand, title, menu_order
        'voir_tout' => 'oui',
        'titre'    => 'Recettes populaires',
    ), $atts, 'recettes_populaires' );

    // --- Query ---
    $args = array(
        'post_type'      => 'recette',
        'posts_per_page' => intval( $atts['nombre'] ),
        'post_status'    => 'publish',
    );

    // Ordre
    if ( $atts['ordre'] === 'rand' ) {
        $args['orderby'] = 'rand';
    } elseif ( $atts['ordre'] === 'title' ) {
        $args['orderby'] = 'title';
        $args['order']   = 'ASC';
    } elseif ( $atts['ordre'] === 'menu_order' ) {
        $args['orderby'] = 'menu_order';
        $args['order']   = 'ASC';
    } else {
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
    }

    // Filtre par type
    if ( ! empty( $atts['type'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'type_recette',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $atts['type'] ),
            ),
        );
    }

    $query = new WP_Query( $args );

    if ( ! $query->have_posts() ) {
        return '<p style="text-align:center; color:#8C8579; padding:40px;">Aucune recette publiée pour le moment.</p>';
    }

    // --- Mapping badges couleurs par type ---
    $badge_colors = array(
        'classique' => 'recipe-card__badge--classic',
        'plat'      => 'recipe-card__badge--plat',
        'variation' => 'recipe-card__badge--variation',
    );

    // --- Build HTML ---
    ob_start();
    ?>
    <div class="recettes-section-wrapper">

        <!-- Header -->
        <div class="recettes-header">
            <h2 class="recettes-header__title"><?php echo esc_html( $atts['titre'] ); ?></h2>
            <?php if ( $atts['voir_tout'] === 'oui' ) : ?>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'recette' ) ); ?>" class="recettes-header__link">
                    Voir toutes les recettes →
                </a>
            <?php endif; ?>
        </div>

        <!-- Grid -->
        <div class="recettes-grid recettes-grid--cols-<?php echo intval( $atts['colonnes'] ); ?>">

            <?php while ( $query->have_posts() ) : $query->the_post();

                // Récupérer les meta
                $temps_prep = get_post_meta( get_the_ID(), '_temps_preparation', true );
                $temps_cuisson = get_post_meta( get_the_ID(), '_temps_cuisson', true );
                $portions = get_post_meta( get_the_ID(), '_portions', true );

                // Type de recette (pour le badge)
                $types = get_the_terms( get_the_ID(), 'type_recette' );
                $type_name  = '';
                $type_slug  = '';
                $badge_class = '';
                if ( $types && ! is_wp_error( $types ) ) {
                    $type_name  = $types[0]->name;
                    $type_slug  = $types[0]->slug;
                    $badge_class = isset( $badge_colors[ $type_slug ] ) ? $badge_colors[ $type_slug ] : '';
                }

                // Difficulté
                $difficultés = get_the_terms( get_the_ID(), 'difficulte' );
                $diff_name = '';
                if ( $difficultés && ! is_wp_error( $difficultés ) ) {
                    $diff_name = $difficultés[0]->name;
                }

                // Temps total à afficher
                $temps_display = '';
                if ( $temps_prep ) {
                    $temps_display = $temps_prep;
                } elseif ( $temps_cuisson ) {
                    $temps_display = $temps_cuisson;
                }
            ?>

            <a href="<?php the_permalink(); ?>" class="recipe-card">

                <!-- Image -->
                <div class="recipe-card__image">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'recipe-card', array( 'alt' => get_the_title() ) ); ?>
                    <?php else : ?>
                        <!-- Fallback gradient si pas d'image -->
                        <div class="recipe-card__placeholder recipe-card__placeholder--<?php echo esc_attr( $type_slug ); ?>">
                            🌶
                        </div>
                    <?php endif; ?>

                    <?php if ( $type_name ) : ?>
                        <span class="recipe-card__badge <?php echo esc_attr( $badge_class ); ?>">
                            <?php echo esc_html( $type_name ); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Body -->
                <div class="recipe-card__body">
                    <h3><?php the_title(); ?></h3>
                    <?php if ( has_excerpt() ) : ?>
                        <p><?php echo wp_trim_words( get_the_excerpt(), 18, '...' ); ?></p>
                    <?php endif; ?>

                    <div class="recipe-card__meta">
                        <?php if ( $temps_display ) : ?>
                            <span><i class="fa-regular fa-clock"></i> <?php echo esc_html( $temps_display ); ?></span>
                        <?php endif; ?>
                        <?php if ( $diff_name ) : ?>
                            <span>★ <?php echo esc_html( $diff_name ); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

            </a>

            <?php endwhile; ?>

        </div>
    </div>

    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'recettes_populaires', 'harissa_recettes_populaires_shortcode' );
