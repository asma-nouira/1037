<?php
/**
 * Template pour une recette individuelle (CPT: recette)
 * Classes identiques à la maquette single-recette-mockup.html
 */
get_header();

while ( have_posts() ) : the_post();

    // === Récupérer les meta ===
    $temps_prep    = get_post_meta( get_the_ID(), '_temps_preparation', true );
    $temps_cuisson = get_post_meta( get_the_ID(), '_temps_cuisson', true );
    $portions      = get_post_meta( get_the_ID(), '_portions', true );
    $ingredients   = get_post_meta( get_the_ID(), '_ingredients', true );

    // === Type de recette ===
    $types      = get_the_terms( get_the_ID(), 'type_recette' );
    $type_name  = '';
    $type_slug  = '';
    if ( $types && ! is_wp_error( $types ) ) {
        $type_name = $types[0]->name;
        $type_slug = $types[0]->slug;
    }

    // === Difficulté ===
    $diffs     = get_the_terms( get_the_ID(), 'difficulte' );
    $diff_name = '';
    if ( $diffs && ! is_wp_error( $diffs ) ) {
        $diff_name = $diffs[0]->name;
    }

    // === Badge class mapping ===
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
?>

<!-- ==================== RECIPE HERO ==================== -->
<section class="recipe-hero">
    <?php if ( has_post_thumbnail() ) : ?>
        <div style="position:absolute;inset:0;z-index:0;">
            <?php the_post_thumbnail( 'recipe-hero', array(
                'style' => 'width:100%;height:100%;object-fit:cover;opacity:0.3;',
            ) ); ?>
        </div>
    <?php endif; ?>

    <div class="recipe-hero-content">

        <!-- Breadcrumb -->
        <div class="recipe-hero-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a>
            <span class="sep">/</span>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'recette' ) ); ?>">Recettes</a>
            <span class="sep">/</span>
            <span style="color:rgba(255,255,255,.8)"><?php the_title(); ?></span>
        </div>

        <!-- Badges -->
        <div class="recipe-hero-badges">
            <?php if ( $type_name ) : ?>
                <span class="recipe-hero-badge recipe-hero-badge--type"><?php echo esc_html( $type_name ); ?></span>
            <?php endif; ?>
            <?php if ( $diff_name ) : ?>
                <span class="recipe-hero-badge recipe-hero-badge--diff">
                    <i class="ti ti-star" style="font-size:12px"></i> <?php echo esc_html( $diff_name ); ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Titre -->
        <h1><?php the_title(); ?></h1>

        <!-- Description -->
        <?php if ( has_excerpt() ) : ?>
            <p class="recipe-hero-desc"><?php echo get_the_excerpt(); ?></p>
        <?php endif; ?>

        <!-- Meta bar -->
        <div class="recipe-meta-bar">
            <?php if ( $temps_prep ) : ?>
                <div class="recipe-meta-item">
                    <div class="meta-icon"><i class="ti ti-clock"></i></div>
                    <span class="meta-value"><?php echo esc_html( $temps_prep ); ?></span>
                    <span class="meta-label">Préparation</span>
                </div>
            <?php endif; ?>
            <?php if ( $temps_cuisson ) : ?>
                <div class="recipe-meta-item">
                    <div class="meta-icon"><i class="ti ti-flame"></i></div>
                    <span class="meta-value"><?php echo esc_html( $temps_cuisson ); ?></span>
                    <span class="meta-label">Cuisson</span>
                </div>
            <?php endif; ?>
            <?php if ( $portions ) : ?>
                <div class="recipe-meta-item">
                    <div class="meta-icon"><i class="ti ti-users"></i></div>
                    <span class="meta-value"><?php echo esc_html( $portions ); ?></span>
                    <span class="meta-label">Portions</span>
                </div>
            <?php endif; ?>
            <?php if ( $diff_name ) : ?>
                <div class="recipe-meta-item">
                    <div class="meta-icon"><i class="ti ti-chef-hat"></i></div>
                    <span class="meta-value"><?php echo esc_html( $diff_name ); ?></span>
                    <span class="meta-label">Niveau</span>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>


<!-- ==================== RECIPE BODY ==================== -->
<div class="recipe-body">
    <div class="recipe-layout">

        <!-- ========== SIDEBAR — Ingrédients ========== -->
        <aside class="recipe-ingredients">
            <div class="recipe-ingredients-card">

                <div class="recipe-ingredients-header">
                    <h2><i class="ti ti-list-check" style="font-size:22px;color:var(--terracotta,#C4703F)"></i> Ingrédients</h2>
                    <div class="servings-control">
                        <button class="servings-btn" id="srv-minus" aria-label="Moins">−</button>
                        <span class="servings-num" id="srv-num"><?php echo $portions ? intval( $portions ) : '4'; ?></span>
                        <button class="servings-btn" id="srv-plus" aria-label="Plus">+</button>
                    </div>
                </div>

                <?php if ( $ingredients ) :
                    $items = array_filter( array_map( 'trim', explode( "\n", $ingredients ) ) );
                ?>
                    <ul class="ingredient-list">
                        <?php foreach ( $items as $item ) :
                            // Séparer quantité et nom (ex: "500g de piments" → "500g" + "de piments")
                            $qty  = '';
                            $name = $item;
                            if ( preg_match( '/^([\d½¼¾⅓⅔]+\s*[a-zA-Zàéèê.]*)\s+(.+)$/u', $item, $matches ) ) {
                                $qty  = trim( $matches[1] );
                                $name = trim( $matches[2] );
                            }
                        ?>
                            <li class="ingredient-item" onclick="this.classList.toggle('checked')">
                                <span class="ingredient-dot"></span>
                                <span class="ingredient-qty"><?php echo esc_html( $qty ?: '—' ); ?></span>
                                <span class="ingredient-name"><?php echo esc_html( $name ); ?></span>
                                <span class="ingredient-check"><i class="ti ti-check"></i></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p style="color:#8C8579;font-size:14px;">Aucun ingrédient renseigné.</p>
                <?php endif; ?>

                <button class="recipe-print" onclick="window.print()">
                    <i class="ti ti-printer"></i> Imprimer la recette
                </button>

            </div>
        </aside>


        <!-- ========== MAIN — Étapes ========== -->
        <div class="recipe-steps-wrapper">

            <div class="recipe-steps">
                <h2><i class="ti ti-route" style="font-size:24px;color:var(--crimson,#B8332E)"></i> Préparation</h2>

                <?php
                // Récupérer le contenu et le découper en étapes
                $content = get_the_content();
                $content = apply_filters( 'the_content', $content );

                // Essayer de découper par H3 (### Étape)
                $steps = preg_split( '/<h3[^>]*>(.*?)<\/h3>/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

                if ( count( $steps ) > 1 ) :
                    // On a des H3 → afficher en step-cards
                    $step_num = 0;
                    for ( $i = 0; $i < count( $steps ); $i += 2 ) :
                        $step_num++;
                        $step_title = strip_tags( $steps[ $i ] );
                        $step_body  = isset( $steps[ $i + 1 ] ) ? $steps[ $i + 1 ] : '';
                        // Nettoyer le titre (retirer "Étape X —" si présent)
                        $step_title = preg_replace( '/^[Éé]tape\s*\d+\s*[—\-:]\s*/u', '', $step_title );
                ?>
                    <div class="step-card">
                        <div class="step-number"><?php echo $step_num; ?></div>
                        <div class="step-content">
                            <h3><?php echo esc_html( $step_title ); ?></h3>
                            <?php echo $step_body; ?>
                        </div>
                    </div>
                <?php
                    endfor;
                else :
                    // Pas de H3 → afficher le contenu brut dans une seule card
                ?>
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <?php echo $content; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- TIP (si "Conseil" est dans le contenu, sinon tip générique) -->
            <div class="recipe-tip">
                <h3><i class="ti ti-bulb" style="color:var(--gold,#C99A2E)"></i> Conseil du chef</h3>
                <p>Ajoutez toujours une couche d'huile d'olive en surface après chaque utilisation pour préserver la fraîcheur. Conservez au réfrigérateur dans un pot hermétique.</p>
            </div>

            <!-- SHARE -->
            <div class="recipe-share">
                <h3>Partager cette recette</h3>
                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>"
                       target="_blank" rel="noopener" class="share-btn share-btn--fb" title="Facebook">
                        <i class="ti ti-brand-facebook"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>"
                       target="_blank" rel="noopener" class="share-btn share-btn--tw" title="X / Twitter">
                        <i class="ti ti-brand-x"></i>
                    </a>
                    <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode( get_permalink() ); ?>&description=<?php echo urlencode( get_the_title() ); ?>"
                       target="_blank" rel="noopener" class="share-btn share-btn--pt" title="Pinterest">
                        <i class="ti ti-brand-pinterest"></i>
                    </a>
                    <a href="https://wa.me/?text=<?php echo urlencode( get_the_title() . ' — ' . get_permalink() ); ?>"
                       target="_blank" rel="noopener" class="share-btn share-btn--wa" title="WhatsApp">
                        <i class="ti ti-brand-whatsapp"></i>
                    </a>
                    <button class="share-btn share-btn--cp" title="Copier le lien"
                            onclick="navigator.clipboard.writeText('<?php echo esc_js( get_permalink() ); ?>');this.innerHTML='<i class=\'ti ti-check\'></i>';setTimeout(()=>{this.innerHTML='<i class=\'ti ti-link\'></i>'},2000);">
                        <i class="ti ti-link"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>


<!-- ==================== RELATED RECIPES ==================== -->
<?php
// Query des recettes similaires (même type, exclure la recette courante)
$related_args = array(
    'post_type'      => 'recette',
    'posts_per_page' => 3,
    'post__not_in'   => array( get_the_ID() ),
    'post_status'    => 'publish',
    'orderby'        => 'rand',
);

// Filtrer par même type si possible
if ( $type_slug ) {
    $related_args['tax_query'] = array(
        array(
            'taxonomy' => 'type_recette',
            'field'    => 'slug',
            'terms'    => $type_slug,
        ),
    );
}

$related = new WP_Query( $related_args );

// Si pas assez de résultats du même type, compléter avec d'autres
if ( $related->found_posts < 3 ) {
    $related = new WP_Query( array(
        'post_type'      => 'recette',
        'posts_per_page' => 3,
        'post__not_in'   => array( get_the_ID() ),
        'post_status'    => 'publish',
        'orderby'        => 'rand',
    ) );
}

if ( $related->have_posts() ) :
?>
<section class="related-section">
    <div class="section-tag" style="text-align:center">À découvrir aussi</div>
    <h2>Recettes similaires</h2>
    <div class="related-grid">

        <?php while ( $related->have_posts() ) : $related->the_post();
            $r_types     = get_the_terms( get_the_ID(), 'type_recette' );
            $r_type_slug = '';
            $r_type_name = '';
            $r_badge     = '';
            $r_placeholder = '';
            if ( $r_types && ! is_wp_error( $r_types ) ) {
                $r_type_slug = $r_types[0]->slug;
                $r_type_name = $r_types[0]->name;
                $r_badge = isset( $badge_map[ $r_type_slug ] ) ? $badge_map[ $r_type_slug ] : '';
                $r_placeholder = isset( $placeholder_map[ $r_type_slug ] ) ? $placeholder_map[ $r_type_slug ] : '';
            }
            $r_temps = get_post_meta( get_the_ID(), '_temps_preparation', true );
            $r_diffs = get_the_terms( get_the_ID(), 'difficulte' );
            $r_diff  = ( $r_diffs && ! is_wp_error( $r_diffs ) ) ? $r_diffs[0]->name : '';
        ?>
            <a href="<?php the_permalink(); ?>" class="rc-card">
                <div class="rc-card-img">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'recipe-card' ); ?>
                    <?php else : ?>
                        <div class="rc-card-img-placeholder <?php echo esc_attr( $r_placeholder ); ?>">🌶</div>
                    <?php endif; ?>
                    <?php if ( $r_type_name ) : ?>
                        <span class="rc-badge <?php echo esc_attr( $r_badge ); ?>"><?php echo esc_html( $r_type_name ); ?></span>
                    <?php endif; ?>
                </div>
                <div class="rc-card-body">
                    <h3><?php the_title(); ?></h3>
                    <?php if ( has_excerpt() ) : ?>
                        <p><?php echo wp_trim_words( get_the_excerpt(), 14, '...' ); ?></p>
                    <?php endif; ?>
                    <div class="rc-card-meta">
                        <?php if ( $r_temps ) : ?>
                            <span><i class="ti ti-clock"></i> <?php echo esc_html( $r_temps ); ?></span>
                        <?php endif; ?>
                        <?php if ( $r_diff ) : ?>
                            <span><i class="ti ti-star"></i> <?php echo esc_html( $r_diff ); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        <?php endwhile; ?>

    </div>
</section>
<?php
    wp_reset_postdata();
endif;
?>

<?php
endwhile;
get_footer();
?>