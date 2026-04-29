<?php

/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 11/10/2017
 * Time: 10:55
 */
function vsc_map()
{
    $map = '<div id="map1" style="width: 100%;height:430px;min-height: 100%;"></div>';
    ?>
    <script>

        addedMap = false;
        jQuery(document).scroll(function () {
            if (!addedMap && jQuery(document).scrollTop() > 100) {
                addedMap = true;
                jQuery(function () {
                    var points = new google.maps.LatLng(45.50769580633233, -73.56435486050708);
                    var styles = [
                        {
                            "stylers": [
                                {"visibility": "on"},
                                {"hue": "#F1ECE9"}
                            ]
                        }
                    ];

                    var mapOptions1 = {
                        scrollwheel: false,
                        // How zoomed in you want the map to start at (always required)
                        zoom: 17,
                        center: points, // New York
                        // This is where you would paste any style found on Snazzy Maps.
                        styles: styles
                    };
                    // Create the Google Map using our element and options defined above
                    var map1 = new google.maps.Map(document.getElementById("map1"), mapOptions1);
                    var rectangle = new google.maps.Rectangle();

                    // Let's also add a marker while we're at it
                    var marker = new google.maps.Marker({
                        position: points,
                        map: map1,
                        icon: '/wp-content/themes/vsc-theme/css/img/icon-map.svg',
                        url: 'https://www.google.com/maps/place/Complexe+Desjardins/@45.5075266,-73.5669083,17z/data=!3m1!5s0x4cc91a45a1cf65b7:0x3310f1891dd11e56!4m14!1m7!3m6!1s0x4cc91a4fca1ae933:0x9e1c26554e8a2355!2sComplexe+Desjardins!8m2!3d45.5075229!4d-73.5643334!16zL20vMDR2NTJo!3m5!1s0x4cc91a4fca1ae933:0x9e1c26554e8a2355!8m2!3d45.5075229!4d-73.5643334!16zL20vMDR2NTJo?entry=ttu'
                    });
                    marker.setAnimation(google.maps.Animation.BOUNCE);

                    marker.addListener('mouseover', function () {
                        marker.setAnimation(null);
                    });
                    marker.addListener('mouseout', function () {
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                    });
                    google.maps.event.addListener(marker, 'click', function () {
                        window.location.href = this.url;
                    });
                });
            }
            document.getElementById("year").innerHTML = new Date().getFullYear();
        });
    </script>
    <?php return $map;
}

add_shortcode('vsc_map', 'vsc_map');

function vsc_hero_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'texte_bandeau'  => "Obtenez un accès rapide : Aucune référence médicale n'est requise pour prendre rendez-vous.",
    ), $atts, 'vsc_hero');

    $theme_uri = get_template_directory_uri();

    // Slides du hero — modifie ce tableau pour ajouter/changer les slides
    $slides = array(
        array(
            'image'    => $theme_uri . '/img/hero-1.jpg',
            'titre'    => 'RENDEZ-VOUS RAPIDE',
            'btn_text' => 'PRENDRE RENDEZ-VOUS',
            'btn_url'  => '/nous-joindre/',
        ),
        array(
            'image'    => $theme_uri . '/img/hero-2.jpg',
            'titre'    => 'CENTRE DE PRÉVENTION DU CANCER DIGESTIF',
            'btn_text' => 'EN SAVOIR PLUS',
            'btn_url'  => '/notre-expertise/',
        ),
        array(
            'image'    => $theme_uri . '/img/hero-3.jpg',
            'titre'    => 'UNE ÉQUIPE À VOTRE ÉCOUTE',
            'btn_text' => 'NOUS CONTACTER',
            'btn_url'  => '/nous-joindre/',
        ),
    );

    ob_start();
    ?>
    <section class="hero-section">
        <div class="hero-slider">
            <?php foreach ($slides as $slide) : ?>
                <div class="hero-slide" style="background-image: url('<?php echo esc_url($slide['image']); ?>');">
                    <div class="hero-slide__overlay"></div>
                    <div class="hero-slide__content">
                        <h2 class="hero-slide__title"><?php echo esc_html($slide['titre']); ?></h2>
                        <a href="<?php echo esc_url($slide['btn_url']); ?>" class="hero-slide__btn"><?php echo esc_html($slide['btn_text']); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_shortcode('vsc_hero', 'vsc_hero_shortcode');

function vsc_presentation_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'titre' => 'Centre de prévention du cancer digestif',
        'texte' => "La Clinique 1037, Centre de prévention du cancer digestif, se spécialise en gastroentérologie, gastroscopie, coloscopie longue et sigmoïdoscopie. Toutes les procédures sont pratiquées par des gastroentérologues expérimentés et certains sont affiliés à des centres hospitaliers universitaires. La Clinique 1037 satisfait ou surpasse les standards hospitaliers pour la stérilité, pour le monitoring et pour la sécurité des patients. Veuillez noter que nous sommes situés au 2e étage d'un édifice sans ascenseur : vous aurez donc environ 25 marches à monter.",
    ), $atts, 'vsc_presentation');

    ob_start();
    ?>
    <section class="presentation-section">
        <div class="presentation-container">
            <h1><?php echo esc_html($atts['titre']); ?></h1>
            <p><?php echo esc_html($atts['texte']); ?></p>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_shortcode('vsc_presentation', 'vsc_presentation_shortcode');

function vsc_services_grid_shortcode($atts)
{
    $services = array(
        array('titre' => 'Gastroscopie',         'url' => '/gastroscopie/'),
        array('titre' => 'Coloscopie longue',    'url' => '/coloscopie-longue/'),
        array('titre' => 'Sigmoïdoscopie',       'url' => '/coloscopie-courte/'),
        array('titre' => 'Endoscopie digestive', 'url' => '/endoscopie/'),
    );

    ob_start();
    ?>
    <section class="services-grid">
        <?php foreach ($services as $service) : ?>
            <a href="<?php echo esc_url($service['url']); ?>" class="service-card">
                <h2><?php echo esc_html($service['titre']); ?></h2>
            </a>
        <?php endforeach; ?>
    </section>
    <?php
    return ob_get_clean();
}

add_shortcode('vsc_services_grid', 'vsc_services_grid_shortcode');
