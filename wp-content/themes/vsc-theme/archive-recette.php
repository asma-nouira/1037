<?php
/**
 * Template d'archive pour le CPT Recettes
 * URL : /recettes/
 * 
 * Ce template affiche automatiquement le shortcode [recettes_archive]
 * pour que l'URL /recettes/ ait le même design que la page VCE.
 */
get_header();
?>

<!-- Hero -->
<section class="recipe-hero" style="min-height:40vh;padding-bottom:60px;">
    <div class="recipe-hero-content" style="padding:140px 64px 0;max-width:700px;">
        <span class="tag" style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:20px;background:rgba(201,154,46,.2);border:1px solid rgba(201,154,46,.35);color:#e8c96a;font-size:12px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:24px;">
            <i class="ti ti-chef-hat" style="font-size:14px"></i> Nos recettes
        </span>
        <h1 style="font-family:'Playfair Display',serif;font-size:54px;line-height:1.1;color:#fff;margin-bottom:16px;">
            Toutes nos <em style="font-style:italic;color:#f0a86c;">recettes</em>
        </h1>
        <p style="font-size:18px;line-height:1.7;color:rgba(255,255,255,.7);font-weight:300;max-width:500px;">
            De la harissa traditionnelle aux plats emblématiques, explorez le meilleur de la cuisine tunisienne épicée.
        </p>
    </div>
</section>

<!-- Shortcode dynamique -->
<main class="site-main" role="main">
    <?php echo do_shortcode( '[recettes_archive show_hero="non"]' ); ?>
</main>

<?php get_footer(); ?>
