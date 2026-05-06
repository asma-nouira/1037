<?php
/**
 * vsc-theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package vsc-theme
 */

if ( ! function_exists( 'vsc_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function vsc_theme_setup() {

		load_theme_textdomain( 'vsc-theme', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		// Tailles d'images pour les recettes
		add_image_size( 'recipe-card', 600, 400, true );
		add_image_size( 'recipe-hero', 1200, 600, true );
		add_image_size( 'blog-thumb', 800, 500, true );

		// Menus — on garde ton menu-1 + on ajoute un footer
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'vsc-theme' ),
			'footer'  => __( 'Menu Footer', 'vsc-theme' ),
		) );

		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		add_theme_support( 'custom-background', apply_filters( 'vsc_theme_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		add_theme_support( 'customize-selective-refresh-widgets' );

		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'vsc_theme_setup' );

/**
 * Content width
 */
function vsc_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'vsc_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'vsc_theme_content_width', 0 );

/**
 * Register widget areas
 */
function vsc_theme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'vsc-theme' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'vsc-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget-title">',
		'after_title'   => '</div>',
	) );

	// Widgets Footer Harissa
	register_sidebar( array(
		'name'          => __( 'Footer Col 1', 'vsc-theme' ),
		'id'            => 'footer-1',
		'before_widget' => '<div class="footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Col 2', 'vsc-theme' ),
		'id'            => 'footer-2',
		'before_widget' => '<div class="footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Col 3', 'vsc-theme' ),
		'id'            => 'footer-3',
		'before_widget' => '<div class="footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'vsc_theme_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function vsc_theme_scripts() {
	// Tes CSS existants
	wp_enqueue_style( 'vsc-header-style', get_template_directory_uri() . '/css/header.css', array(), null );

	// Google Fonts — Harissa
	wp_enqueue_style(
		'harissa-google-fonts',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600&display=swap',
		array(),
		null
	);

	// Font Awesome
	wp_enqueue_style(
		'font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
		array(),
		'6.5.1'
	);

	// CSS Harissa (à créer dans /css/harissa.css)
	wp_enqueue_style(
		'harissa-style',
		get_template_directory_uri() . '/css/harissa.css',
		array( 'harissa-google-fonts', 'font-awesome' ),
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'vsc_theme_scripts' );

/**
 * Footer scripts — tes enqueues existants + harissa JS
 */
add_action( 'wp_footer', function() {
	wp_enqueue_style( 'vsc-mobile-style', get_template_directory_uri() . '/css/mobile.css', array(), null );
	wp_enqueue_style( 'vsc-theme-style', get_template_directory_uri() . '/css/style.css', array(), null );
	wp_enqueue_style( 'vsc-theme-fonts', get_template_directory_uri() . '/css/fonts/fonts.css', array(), null );

	wp_enqueue_script( 'vsc-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), null, true );
	wp_enqueue_script( 'vsc-theme-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), null, true );

	// Script Harissa (scroll header, menu mobile, animations, compteurs)
	wp_enqueue_script( 'harissa-main', get_template_directory_uri() . '/js/harissa.js', array(), '1.0.0', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
});

// Remove WP version from scripts
function vc_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );


// ============================================
// HARISSA — CUSTOM POST TYPE : RECETTES
// ============================================
function harissa_register_recettes() {
	$labels = array(
		'name'               => 'Recettes',
		'singular_name'      => 'Recette',
		'menu_name'          => 'Recettes',
		'add_new'            => 'Ajouter une recette',
		'add_new_item'       => 'Ajouter une nouvelle recette',
		'edit_item'          => 'Modifier la recette',
		'view_item'          => 'Voir la recette',
		'all_items'          => 'Toutes les recettes',
		'search_items'       => 'Chercher une recette',
		'not_found'          => 'Aucune recette trouvée',
	);

	register_post_type( 'recette', array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => array( 'slug' => 'recettes' ),
		'menu_icon'          => 'dashicons-carrot',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'       => true,
	) );

	// Taxonomie : Type de recette
	register_taxonomy( 'type_recette', 'recette', array(
		'labels' => array(
			'name'          => 'Types de recettes',
			'singular_name' => 'Type de recette',
		),
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'type-recette' ),
		'show_in_rest' => true,
	) );

	// Taxonomie : Difficulté
	register_taxonomy( 'difficulte', 'recette', array(
		'labels' => array(
			'name'          => 'Difficultés',
			'singular_name' => 'Difficulté',
		),
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'difficulte' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'harissa_register_recettes' );


// ============================================
// HARISSA — META BOXES RECETTES
// ============================================
function harissa_recette_meta_boxes() {
	add_meta_box(
		'recette_details',
		'Détails de la recette',
		'harissa_recette_meta_callback',
		'recette',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'harissa_recette_meta_boxes' );

function harissa_recette_meta_callback( $post ) {
	wp_nonce_field( 'harissa_recette_nonce', 'harissa_recette_nonce_field' );

	$temps_prep    = get_post_meta( $post->ID, '_temps_preparation', true );
	$temps_cuisson = get_post_meta( $post->ID, '_temps_cuisson', true );
	$portions      = get_post_meta( $post->ID, '_portions', true );
	$ingredients   = get_post_meta( $post->ID, '_ingredients', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="temps_preparation">Temps de préparation</label></th>
			<td><input type="text" id="temps_preparation" name="temps_preparation" value="<?php echo esc_attr( $temps_prep ); ?>" placeholder="ex: 30 min" class="regular-text"></td>
		</tr>
		<tr>
			<th><label for="temps_cuisson">Temps de cuisson</label></th>
			<td><input type="text" id="temps_cuisson" name="temps_cuisson" value="<?php echo esc_attr( $temps_cuisson ); ?>" placeholder="ex: 45 min" class="regular-text"></td>
		</tr>
		<tr>
			<th><label for="portions">Portions</label></th>
			<td><input type="text" id="portions" name="portions" value="<?php echo esc_attr( $portions ); ?>" placeholder="ex: 4 personnes" class="regular-text"></td>
		</tr>
		<tr>
			<th><label for="ingredients">Ingrédients</label></th>
			<td><textarea id="ingredients" name="ingredients" rows="8" class="large-text" placeholder="Un ingrédient par ligne"><?php echo esc_textarea( $ingredients ); ?></textarea></td>
		</tr>
	</table>
	<?php
}

function harissa_save_recette_meta( $post_id ) {
	if ( ! isset( $_POST['harissa_recette_nonce_field'] ) ||
	     ! wp_verify_nonce( $_POST['harissa_recette_nonce_field'], 'harissa_recette_nonce' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$fields = array( 'temps_preparation', 'temps_cuisson', 'portions', 'ingredients' );
	foreach ( $fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, '_' . $field, sanitize_textarea_field( $_POST[ $field ] ) );
		}
	}
}
add_action( 'save_post_recette', 'harissa_save_recette_meta' );


// ============================================
// HARISSA — CUSTOMIZER (Réseaux sociaux + Langues)
// ============================================
function harissa_customizer( $wp_customize ) {
	// Section : Réseaux sociaux
	$wp_customize->add_section( 'harissa_social', array(
		'title'    => 'Réseaux sociaux',
		'priority' => 90,
	) );

	$socials = array( 'facebook', 'instagram', 'pinterest', 'youtube', 'tiktok' );
	foreach ( $socials as $social ) {
		$wp_customize->add_setting( 'harissa_' . $social, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'harissa_' . $social, array(
			'label'   => ucfirst( $social ) . ' URL',
			'section' => 'harissa_social',
			'type'    => 'url',
		) );
	}

	// Section : Langues
	$wp_customize->add_section( 'harissa_languages', array(
		'title'    => 'Liens de langues',
		'priority' => 95,
	) );
	$langs = array( 'ar' => 'Arabe', 'en' => 'Anglais' );
	foreach ( $langs as $code => $name ) {
		$wp_customize->add_setting( 'harissa_lang_' . $code, array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'harissa_lang_' . $code, array(
			'label'   => 'URL version ' . $name,
			'section' => 'harissa_languages',
			'type'    => 'url',
		) );
	}
}
add_action( 'customize_register', 'harissa_customizer' );


// ============================================
// HARISSA — SHORTCODES
// ============================================

// [unesco_badge year="2022"]
function harissa_unesco_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'year' => '2022' ), $atts );
	return '<div class="unesco-badge">
		<div class="unesco-badge__year">' . esc_html( $atts['year'] ) . '</div>
		<div class="unesco-badge__label">Patrimoine<br>immatériel UNESCO</div>
	</div>';
}
add_shortcode( 'unesco_badge', 'harissa_unesco_shortcode' );

// [section_tag text="Patrimoine culinaire"]
function harissa_section_tag_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'text' => '' ), $atts );
	return '<div class="section-tag">' . esc_html( $atts['text'] ) . '</div>';
}
add_shortcode( 'section_tag', 'harissa_section_tag_shortcode' );

// [counter number="3000" suffix="+" label="Ans d'histoire"]
function harissa_counter_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'number' => '0',
		'label'  => '',
		'suffix' => '',
	), $atts );
	return '<div class="stat-item">
		<div class="stat-number" data-target="' . esc_attr( $atts['number'] ) . '">' . esc_html( $atts['number'] ) . esc_html( $atts['suffix'] ) . '</div>
		<div class="stat-label">' . esc_html( $atts['label'] ) . '</div>
	</div>';
}
add_shortcode( 'counter', 'harissa_counter_shortcode' );


// ============================================
// HARISSA — PERFORMANCE & SÉCURITÉ
// ============================================
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );


// ============================================
// INCLUDES EXISTANTS (ne pas toucher)
// ============================================
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';

if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

include_once "integrated_vc.php";