<?php
/**
 * Harissa — Shortcodes pour la page Histoire
 * 
 * [harissa_timeline]  → Frise chronologique interactive
 * [harissa_regions]   → Carte des régions productrices
 * 
 * UTILISATION DANS VCE :
 * Coller le shortcode dans un Text Block ou Raw HTML
 */


// ============================================
// SHORTCODE [harissa_timeline]
// ============================================
function harissa_timeline_shortcode( $atts ) {

    $atts = shortcode_atts( array(
        'titre' => 'Une histoire millénaire',
    ), $atts );

    // Données de la timeline
    $events = array(
        array(
            'year'  => '3000 av. J.-C.',
            'title' => 'Les origines du piment',
            'desc'  => 'Le piment est cultivé pour la première fois en Amérique du Sud. Il faudra des millénaires avant qu\'il n\'atteigne les côtes nord-africaines.',
            'icon'  => 'ti-seed',
        ),
        array(
            'year'  => 'XVIe siècle',
            'title' => 'Le piment arrive en Tunisie',
            'desc'  => 'Les explorateurs espagnols et portugais introduisent le piment en Afrique du Nord via les routes commerciales méditerranéennes. Les Tunisiens l\'adoptent immédiatement.',
            'icon'  => 'ti-ship',
        ),
        array(
            'year'  => 'XVIIIe siècle',
            'title' => 'Naissance de la recette',
            'desc'  => 'La recette de la harissa se codifie dans les familles tunisiennes. Chaque région développe sa propre variante avec des épices locales.',
            'icon'  => 'ti-bowl',
        ),
        array(
            'year'  => '1920',
            'title' => 'Production industrielle',
            'desc'  => 'Les premières conserveries tunisiennes commencent la production de harissa en tube et en pot, rendant le condiment accessible au-delà des frontières.',
            'icon'  => 'ti-building-factory',
        ),
        array(
            'year'  => '2013',
            'title' => 'Reconnaissance nationale',
            'desc'  => 'La harissa est officiellement reconnue comme élément du patrimoine culinaire national tunisien. Les efforts de protection commencent.',
            'icon'  => 'ti-award',
        ),
        array(
            'year'  => '2022',
            'title' => 'Patrimoine UNESCO',
            'desc'  => 'La harissa est inscrite sur la Liste représentative du patrimoine culturel immatériel de l\'humanité par l\'UNESCO. Une fierté pour toute la Tunisie.',
            'icon'  => 'ti-world',
            'highlight' => true,
        ),
    );

    ob_start();
    ?>
    <div class="harissa-timeline">
        <?php if ( $atts['titre'] ) : ?>
            <div class="section-tag" style="text-align:center;">Histoire</div>
            <h2 class="harissa-timeline__title"><?php echo esc_html( $atts['titre'] ); ?></h2>
        <?php endif; ?>

        <div class="harissa-timeline__track">
            <?php foreach ( $events as $i => $event ) :
                $highlight_class = ! empty( $event['highlight'] ) ? 'harissa-timeline__item--highlight' : '';
                $side = ( $i % 2 === 0 ) ? 'left' : 'right';
            ?>
                <div class="harissa-timeline__item harissa-timeline__item--<?php echo $side; ?> <?php echo $highlight_class; ?>">
                    
                    <!-- Point sur la ligne -->
                    <div class="harissa-timeline__dot">
                        <i class="ti <?php echo esc_attr( $event['icon'] ); ?>" aria-hidden="true"></i>
                    </div>

                    <!-- Carte -->
                    <div class="harissa-timeline__card">
                        <span class="harissa-timeline__year"><?php echo esc_html( $event['year'] ); ?></span>
                        <h3 class="harissa-timeline__card-title"><?php echo esc_html( $event['title'] ); ?></h3>
                        <p class="harissa-timeline__card-desc"><?php echo esc_html( $event['desc'] ); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'harissa_timeline', 'harissa_timeline_shortcode' );


// ============================================
// SHORTCODE [harissa_regions]
// ============================================
function harissa_regions_shortcode( $atts ) {

    $atts = shortcode_atts( array(
        'titre' => 'Les régions de la harissa',
    ), $atts );

    $regions = array(
        array(
            'name'    => 'Nabeul — Cap Bon',
            'desc'    => 'Le berceau de la harissa. Le climat maritime et le sol fertile du Cap Bon produisent le piment baklouti, la variété la plus prisée.',
            'icon'    => 'ti-star',
            'detail'  => 'Piment baklouti — saveur fruitée, piquant modéré',
            'color'   => 'crimson',
        ),
        array(
            'name'    => 'Gabès — Sud-Est',
            'desc'    => 'Connue pour une harissa plus forte, parfumée aux graines de carvi torréfiées. Le soleil intense du sud concentre les saveurs.',
            'icon'    => 'ti-flame',
            'detail'  => 'Piment korni — très piquant, séchage au soleil',
            'color'   => 'terracotta',
        ),
        array(
            'name'    => 'Kasserine — Centre',
            'desc'    => 'Les hauts plateaux produisent une harissa aux notes terreuses. Les familles y maintiennent la tradition du mortier en pierre.',
            'icon'    => 'ti-mountain',
            'detail'  => 'Mélange traditionnel au mortier — texture granuleuse',
            'color'   => 'olive',
        ),
        array(
            'name'    => 'Tozeur — Sud-Ouest',
            'desc'    => 'L\'oasis produit une harissa parfumée aux roses. Le mélange de piment et de pétales de rose est unique au monde.',
            'icon'    => 'ti-flower',
            'detail'  => 'Harissa aux roses — douceur florale, piquant subtil',
            'color'   => 'gold',
        ),
    );

    ob_start();
    ?>
    <div class="harissa-regions">
        <?php if ( $atts['titre'] ) : ?>
            <div class="section-tag" style="text-align:center;">Terroir</div>
            <h2 class="harissa-regions__title"><?php echo esc_html( $atts['titre'] ); ?></h2>
        <?php endif; ?>

        <div class="harissa-regions__grid">
            <?php foreach ( $regions as $region ) : ?>
                <div class="harissa-regions__card harissa-regions__card--<?php echo esc_attr( $region['color'] ); ?>">
                    <div class="harissa-regions__card-icon">
                        <i class="ti <?php echo esc_attr( $region['icon'] ); ?>" aria-hidden="true"></i>
                    </div>
                    <h3><?php echo esc_html( $region['name'] ); ?></h3>
                    <p><?php echo esc_html( $region['desc'] ); ?></p>
                    <div class="harissa-regions__card-detail">
                        <i class="ti ti-leaf" aria-hidden="true"></i>
                        <?php echo esc_html( $region['detail'] ); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'harissa_regions', 'harissa_regions_shortcode' );
