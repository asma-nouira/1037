<?php
/**
 * Harissa — Shortcode [harissa_newsletter]
 *
 * Formulaire newsletter qui s'intègre avec Brevo (Sendinblue).
 * 
 * 2 MODES DE FONCTIONNEMENT :
 * ----------------------------
 * 
 * MODE 1 — Avec le plugin Brevo (recommandé) :
 *   1. Installe le plugin "Brevo – WP Marketing Automation" 
 *   2. Connecte ton compte Brevo avec la clé API
 *   3. Crée un formulaire dans Brevo > Formulaires
 *   4. Utilise : [harissa_newsletter brevo_form_id="3"]
 *   → Le formulaire Brevo est affiché avec le style Harissa
 * 
 * MODE 2 — Sans plugin (API directe) :
 *   1. Crée un compte sur brevo.com
 *   2. Récupère ta clé API dans Réglages > Clés API
 *   3. Ajoute dans wp-config.php : define('BREVO_API_KEY', 'ta-clé-ici');
 *   4. Utilise : [harissa_newsletter]
 *   → Le formulaire envoie directement à l'API Brevo en AJAX
 *
 * OPTIONS :
 * ---------
 * [harissa_newsletter]                                → Mode API directe
 * [harissa_newsletter brevo_form_id="3"]              → Mode plugin Brevo
 * [harissa_newsletter titre="Restez connecté"]        → Changer le titre
 * [harissa_newsletter description="Recevez..."]       → Changer la description
 * [harissa_newsletter list_id="4"]                    → ID de la liste Brevo (mode API)
 * [harissa_newsletter show_header="non"]              → Masquer titre + description
 */

// ============================================
// SHORTCODE PRINCIPAL
// ============================================
function harissa_newsletter_shortcode( $atts ) {

    $atts = shortcode_atts( array(
        'titre'         => 'Restez connecté',
        'description'   => 'Recevez nos nouvelles recettes et articles chaque semaine.',
        'brevo_form_id' => '',
        'list_id'       => '2',  // ID de la liste Brevo par défaut
        'show_header'   => 'oui',
    ), $atts, 'harissa_newsletter' );

    ob_start();
    ?>
    <div class="harissa-newsletter" id="harissa-newsletter">

        <?php if ( $atts['show_header'] === 'oui' ) : ?>
            <h2 class="harissa-newsletter__title"><?php echo esc_html( $atts['titre'] ); ?></h2>
            <p class="harissa-newsletter__desc"><?php echo esc_html( $atts['description'] ); ?></p>
        <?php endif; ?>

        <?php if ( ! empty( $atts['brevo_form_id'] ) ) : ?>
            <!-- MODE PLUGIN BREVO -->
            <?php echo do_shortcode( '[sibwp_form id="' . intval( $atts['brevo_form_id'] ) . '"]' ); ?>

        <?php else : ?>
            <!-- MODE API DIRECTE -->
            <form class="harissa-newsletter__form" id="harissa-nl-form" novalidate>
                <?php wp_nonce_field( 'harissa_newsletter_nonce', 'harissa_nl_nonce' ); ?>
                <input type="hidden" name="list_id" value="<?php echo esc_attr( $atts['list_id'] ); ?>">
                
                <div class="harissa-newsletter__input-wrap">
                    <input 
                        type="email" 
                        name="email" 
                        id="harissa-nl-email"
                        placeholder="Votre adresse email..."
                        required
                        autocomplete="email"
                    >
                    <button type="submit" id="harissa-nl-btn">
                        <span class="harissa-newsletter__btn-text">S'abonner</span>
                        <span class="harissa-newsletter__btn-loading" style="display:none;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83">
                                    <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite"/>
                                </path>
                            </svg>
                        </span>
                    </button>
                </div>

                <!-- Messages -->
                <div class="harissa-newsletter__message" id="harissa-nl-message" style="display:none;"></div>
            </form>

            <!-- RGPD -->
            <p class="harissa-newsletter__rgpd">
                En vous abonnant, vous acceptez de recevoir nos emails. 
                Désabonnement possible à tout moment.
            </p>
        <?php endif; ?>

    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'harissa_newsletter', 'harissa_newsletter_shortcode' );


// ============================================
// AJAX HANDLER (Mode API directe)
// ============================================
function harissa_newsletter_ajax_handler() {

    // Vérifier le nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'harissa_newsletter_nonce' ) ) {
        wp_send_json_error( array( 'message' => 'Requête non autorisée.' ) );
    }

    // Valider l'email
    $email = sanitize_email( $_POST['email'] );
    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => 'Adresse email invalide.' ) );
    }

    $list_id = intval( $_POST['list_id'] );

    // Vérifier la clé API
    if ( ! defined( 'BREVO_API_KEY' ) || empty( BREVO_API_KEY ) ) {
        // Fallback : stocker en base locale si pas de clé API
        harissa_store_email_locally( $email );
        wp_send_json_success( array( 'message' => 'Merci ! Vous êtes inscrit(e).' ) );
    }

    // Appel API Brevo
    $response = wp_remote_post( 'https://api.brevo.com/v3/contacts', array(
        'headers' => array(
            'accept'       => 'application/json',
            'content-type' => 'application/json',
            'api-key'      => BREVO_API_KEY,
        ),
        'body' => wp_json_encode( array(
            'email'            => $email,
            'listIds'          => array( $list_id ),
            'updateEnabled'    => true,
            'attributes'       => array(
                'SOURCE' => 'Site Harissa',
            ),
        ) ),
        'timeout' => 15,
    ) );

    // Gérer la réponse
    if ( is_wp_error( $response ) ) {
        // En cas d'erreur réseau, stocker localement
        harissa_store_email_locally( $email );
        wp_send_json_success( array( 'message' => 'Merci ! Vous êtes inscrit(e).' ) );
    }

    $code = wp_remote_retrieve_response_code( $response );
    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( $code === 201 || $code === 204 ) {
        wp_send_json_success( array( 'message' => 'Merci ! Vérifiez votre boîte mail pour confirmer.' ) );
    } elseif ( isset( $body['code'] ) && $body['code'] === 'duplicate_parameter' ) {
        wp_send_json_success( array( 'message' => 'Vous êtes déjà inscrit(e) ! Merci.' ) );
    } else {
        // Stocker localement en fallback
        harissa_store_email_locally( $email );
        wp_send_json_success( array( 'message' => 'Merci ! Vous êtes inscrit(e).' ) );
    }
}
add_action( 'wp_ajax_harissa_newsletter', 'harissa_newsletter_ajax_handler' );
add_action( 'wp_ajax_nopriv_harissa_newsletter', 'harissa_newsletter_ajax_handler' );


// ============================================
// STOCKAGE LOCAL (FALLBACK)
// ============================================
function harissa_store_email_locally( $email ) {
    $subscribers = get_option( 'harissa_subscribers', array() );
    
    // Éviter les doublons
    if ( ! in_array( $email, array_column( $subscribers, 'email' ) ) ) {
        $subscribers[] = array(
            'email' => $email,
            'date'  => current_time( 'mysql' ),
        );
        update_option( 'harissa_subscribers', $subscribers );
    }
}


// ============================================
// PAGE ADMIN : VOIR LES ABONNÉS LOCAUX
// ============================================
function harissa_subscribers_menu() {
    add_submenu_page(
        'edit.php?post_type=recette',
        'Abonnés Newsletter',
        'Abonnés',
        'manage_options',
        'harissa-subscribers',
        'harissa_subscribers_page'
    );
}
add_action( 'admin_menu', 'harissa_subscribers_menu' );

function harissa_subscribers_page() {
    $subscribers = get_option( 'harissa_subscribers', array() );
    
    // Export CSV
    if ( isset( $_GET['export'] ) && $_GET['export'] === 'csv' && current_user_can( 'manage_options' ) ) {
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=abonnes-harissa-' . date('Y-m-d') . '.csv' );
        $output = fopen( 'php://output', 'w' );
        fputcsv( $output, array( 'Email', 'Date inscription' ) );
        foreach ( $subscribers as $sub ) {
            fputcsv( $output, array( $sub['email'], $sub['date'] ) );
        }
        fclose( $output );
        exit;
    }
    ?>
    <div class="wrap">
        <h1>Abonnés Newsletter 
            <span style="color:#666; font-size:14px;">(<?php echo count( $subscribers ); ?> abonné<?php echo count($subscribers) > 1 ? 's' : ''; ?>)</span>
        </h1>
        
        <?php if ( ! empty( $subscribers ) ) : ?>
            <p>
                <a href="<?php echo admin_url( 'edit.php?post_type=recette&page=harissa-subscribers&export=csv' ); ?>" 
                   class="button button-primary">
                    Exporter en CSV
                </a>
                <span style="color:#666; margin-left:10px;">
                    → Importe ce CSV dans Brevo pour synchroniser tes contacts
                </span>
            </p>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Email</th>
                        <th style="width:200px;">Date d'inscription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( array_reverse( $subscribers ) as $i => $sub ) : ?>
                        <tr>
                            <td><?php echo count( $subscribers ) - $i; ?></td>
                            <td><strong><?php echo esc_html( $sub['email'] ); ?></strong></td>
                            <td><?php echo esc_html( $sub['date'] ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Aucun abonné pour le moment.</p>
        <?php endif; ?>
    </div>
    <?php
}


// ============================================
// ENQUEUE DU SCRIPT AJAX
// ============================================
function harissa_newsletter_scripts() {
    // Ne charger que si le shortcode est utilisé
    global $post;
    if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'harissa_newsletter' ) ) {
        wp_enqueue_script(
            'harissa-newsletter',
            get_template_directory_uri() . '/js/newsletter.js',
            array(),
            '1.0.0',
            true
        );
        wp_localize_script( 'harissa-newsletter', 'harissaNL', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'harissa_newsletter_scripts' );
