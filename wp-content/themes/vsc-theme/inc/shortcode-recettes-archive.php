<?php
/**
 * Harissa — Shortcode [recettes_archive]
 *
 * Page archive complète des recettes avec :
 * - Barre de stats dynamique
 * - Filtres par type (onglets)
 * - Recherche en temps réel
 * - Grille de cartes dynamique
 * - Pagination
 * - Empty state
 *
 * UTILISATION DANS VCE :
 * ----------------------
 * 1. Crée une page "Recettes" avec le template "Page Builder (Pleine largeur)"
 * 2. Ajoute une Row (extra class: bg-sand-light)
 * 3. Ajoute un Text Block ou Raw HTML
 * 4. Colle : [recettes_archive]
 *
 * OPTIONS :
 * ---------
 * [recettes_archive]                        → Toutes les recettes, 9 par page
 * [recettes_archive per_page="12"]          → 12 par page
 * [recettes_archive colonnes="2"]           → 2 colonnes au lieu de 3
 * [recettes_archive show_stats="non"]       → Masquer la barre de stats
 * [recettes_archive show_search="non"]      → Masquer la recherche
 * [recettes_archive show_hero="oui"]        → Ajouter le hero en haut
 */

function harissa_recettes_archive_shortcode( $atts ) {

    $atts = shortcode_atts( array(
        'per_page'    => 9,
        'colonnes'    => 3,
        'show_stats'  => 'oui',
        'show_search' => 'oui',
        'show_hero'   => 'non',
    ), $atts, 'recettes_archive' );

    $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

    // --- Query principale ---
    $args = array(
        'post_type'      => 'recette',
        'posts_per_page' => intval( $atts['per_page'] ),
        'paged'          => $paged,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $query = new WP_Query( $args );

    // --- Compter par type pour les stats ---
    $total_all = wp_count_posts( 'recette' )->publish;

    $count_classique = harissa_count_by_type( 'classique' );
    $count_plat      = harissa_count_by_type( 'plat' );
    $count_variation = harissa_count_by_type( 'variation' );

    // --- Mapping badges ---
    $badge_map = array(
        'classique' => 'rc-badge--classic',
        'plat'      => 'rc-badge--plat',
        'variation' => 'rc-badge--variation',
    );

    $placeholder_map = array(
        'classique' => 'type-classique',
        'plat'      => 'type-plat',
        'variation' => 'type-variation',
    );

    ob_start();

    // ==================== HERO (optionnel) ====================
    if ( $atts['show_hero'] === 'oui' ) : ?>
    <section class="hero-page">
      <div class="hero-page-content">
        <span class="tag"><i class="ti ti-chef-hat" style="font-size:14px"></i> Nos recettes</span>
        <h1>Toutes nos <em>recettes</em></h1>
        <p>De la harissa traditionnelle aux plats emblématiques, explorez le meilleur de la cuisine tunisienne épicée.</p>
      </div>
    </section>
    <?php endif;

    // ==================== STATS BAR ====================
    if ( $atts['show_stats'] === 'oui' ) : ?>
    <div class="recettes-stats">
      <div class="stat-box">
        <strong><?php echo intval( $total_all ); ?></strong>
        <span>Recettes</span>
      </div>
      <div class="stat-box">
        <strong><?php echo intval( $count_classique ); ?></strong>
        <span>Classiques</span>
      </div>
      <div class="stat-box">
        <strong><?php echo intval( $count_plat ); ?></strong>
        <span>Plats</span>
      </div>
      <div class="stat-box">
        <strong><?php echo intval( $count_variation ); ?></strong>
        <span>Variations</span>
      </div>
    </div>
    <?php endif; ?>

    <!-- ==================== FILTERS ==================== -->
    <div class="recettes-toolbar">
      <div class="recettes-filters">
        <div class="filter-tabs">
          <span class="filter-tab active" data-filter="all">Toutes</span>
          <?php
          $types = get_terms( array(
              'taxonomy'   => 'type_recette',
              'hide_empty' => true,
          ) );
          if ( $types && ! is_wp_error( $types ) ) :
              $icons = array(
                  'classique' => 'ti-flame',
                  'plat'      => 'ti-soup',
                  'variation' => 'ti-sparkles',
              );
              foreach ( $types as $type ) :
                  $icon = isset( $icons[ $type->slug ] ) ? $icons[ $type->slug ] : 'ti-tag';
          ?>
              <span class="filter-tab" data-filter="<?php echo esc_attr( $type->slug ); ?>">
                <i class="ti <?php echo esc_attr( $icon ); ?>" style="font-size:14px"></i>
                <?php echo esc_html( $type->name ); ?>
              </span>
          <?php endforeach; endif; ?>
        </div>

        <?php if ( $atts['show_search'] === 'oui' ) : ?>
        <div class="filter-search">
          <i class="ti ti-search"></i>
          <input type="text" placeholder="Rechercher une recette..." id="recettes-search-input">
        </div>
        <?php endif; ?>
      </div>

      <div class="recettes-count">
        <strong id="recettes-visible-count"><?php echo intval( $total_all ); ?></strong> recettes trouvées
      </div>
    </div>

    <!-- ==================== GRID ==================== -->
    <div class="recettes-archive">

      <?php if ( $query->have_posts() ) : ?>

      <div class="recettes-archive-grid" id="recettes-grid" style="grid-template-columns: repeat(<?php echo intval( $atts['colonnes'] ); ?>, 1fr);">

        <?php while ( $query->have_posts() ) : $query->the_post();

            // Meta
            $temps_prep    = get_post_meta( get_the_ID(), '_temps_preparation', true );
            $temps_cuisson = get_post_meta( get_the_ID(), '_temps_cuisson', true );
            $portions      = get_post_meta( get_the_ID(), '_portions', true );

            $temps_display = $temps_prep ? $temps_prep : $temps_cuisson;

            // Type
            $types     = get_the_terms( get_the_ID(), 'type_recette' );
            $type_name = '';
            $type_slug = '';
            $badge_class = '';
            $placeholder_class = '';
            if ( $types && ! is_wp_error( $types ) ) {
                $type_name = $types[0]->name;
                $type_slug = $types[0]->slug;
                $badge_class = isset( $badge_map[ $type_slug ] ) ? $badge_map[ $type_slug ] : '';
                $placeholder_class = isset( $placeholder_map[ $type_slug ] ) ? $placeholder_map[ $type_slug ] : '';
            }

            // Difficulté
            $diffs     = get_the_terms( get_the_ID(), 'difficulte' );
            $diff_name = '';
            if ( $diffs && ! is_wp_error( $diffs ) ) {
                $diff_name = $diffs[0]->name;
            }
        ?>

        <a href="<?php the_permalink(); ?>"
           class="rc-card anim"
           data-type="<?php echo esc_attr( $type_slug ); ?>"
           data-name="<?php echo esc_attr( strtolower( get_the_title() ) ); ?>">

          <!-- Image -->
          <div class="rc-card-img">
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'recipe-card', array( 'alt' => get_the_title() ) ); ?>
            <?php else : ?>
              <div class="rc-card-img-placeholder <?php echo esc_attr( $placeholder_class ); ?>">🌶</div>
            <?php endif; ?>

            <?php if ( $type_name ) : ?>
              <span class="rc-badge <?php echo esc_attr( $badge_class ); ?>">
                <?php echo esc_html( $type_name ); ?>
              </span>
            <?php endif; ?>

            <?php if ( $diff_name ) : ?>
              <span class="rc-diff">
                <i class="ti ti-star" style="font-size:12px"></i>
                <?php echo esc_html( $diff_name ); ?>
              </span>
            <?php endif; ?>
          </div>

          <!-- Body -->
          <div class="rc-card-body">
            <h3><?php the_title(); ?></h3>
            <?php if ( has_excerpt() ) : ?>
              <p><?php echo wp_trim_words( get_the_excerpt(), 18, '...' ); ?></p>
            <?php endif; ?>

            <div class="rc-card-meta">
              <?php if ( $temps_display ) : ?>
                <span><i class="ti ti-clock"></i> <?php echo esc_html( $temps_display ); ?></span>
              <?php endif; ?>
              <?php if ( $portions ) : ?>
                <span><i class="ti ti-users"></i> <?php echo esc_html( $portions ); ?></span>
              <?php endif; ?>
            </div>
          </div>

        </a>

        <?php endwhile; ?>

      </div>

      <!-- Empty State (hidden, shown by JS when filters match nothing) -->
      <div class="recettes-empty" id="recettes-empty-state" style="display:none;">
        <i class="ti ti-search"></i>
        <h3>Aucune recette trouvée</h3>
        <p>Essayez un autre filtre ou une autre recherche.</p>
      </div>

      <!-- Pagination -->
      <?php if ( $query->max_num_pages > 1 ) : ?>
      <div class="recettes-pagination">
        <?php
        // Flèche précédente
        if ( $paged > 1 ) {
            echo '<a href="' . get_pagenum_link( $paged - 1 ) . '" class="page-arrow"><i class="ti ti-chevron-left"></i></a>';
        } else {
            echo '<span class="page-arrow disabled"><i class="ti ti-chevron-left"></i></span>';
        }

        // Numéros de page
        for ( $i = 1; $i <= $query->max_num_pages; $i++ ) {
            $active = ( $i === $paged ) ? ' active' : '';
            echo '<a href="' . get_pagenum_link( $i ) . '" class="page-num' . $active . '">' . $i . '</a>';
        }

        // Flèche suivante
        if ( $paged < $query->max_num_pages ) {
            echo '<a href="' . get_pagenum_link( $paged + 1 ) . '" class="page-arrow"><i class="ti ti-chevron-right"></i></a>';
        } else {
            echo '<span class="page-arrow disabled"><i class="ti ti-chevron-right"></i></span>';
        }
        ?>
      </div>
      <?php endif; ?>

      <?php else : ?>

      <!-- Aucune recette publiée -->
      <div class="recettes-empty">
        <i class="ti ti-chef-hat"></i>
        <h3>Aucune recette pour le moment</h3>
        <p>Les recettes apparaîtront ici dès qu'elles seront publiées.</p>
      </div>

      <?php endif; ?>

    </div>

    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'recettes_archive', 'harissa_recettes_archive_shortcode' );


/**
 * Compter les recettes par type
 */
function harissa_count_by_type( $slug ) {
    $q = new WP_Query( array(
        'post_type'      => 'recette',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'tax_query'      => array(
            array(
                'taxonomy' => 'type_recette',
                'field'    => 'slug',
                'terms'    => $slug,
            ),
        ),
    ) );
    return $q->found_posts;
}


/**
 * Enqueue du script de filtrage
 */
function harissa_recettes_archive_scripts() {
    global $post;
    if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'recettes_archive' ) ) {
        wp_enqueue_script(
            'harissa-recettes-filter',
            get_template_directory_uri() . '/js/recettes-filter.js',
            array(),
            '1.0.0',
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'harissa_recettes_archive_scripts' );
