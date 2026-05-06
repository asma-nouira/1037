# VSC Theme — Virus Santé Communication

## Stack technique
- WordPress 6.x + WPBakery Page Builder (Visual Composer)
- PHP 8.x, SCSS (compilé avec Gulp), JavaScript/jQuery
- WooCommerce, Contact Form 7, Google Maps API
- Slick Slider (disponible mais commenté)

## Architecture SCSS
- **sass/statics.scss** : Variables, resets, utilitaires, mixins
- **sass/header.scss** : Styles du header (chargé en priorité dans wp_head)
- **sass/style.scss** : Styles principaux + Contact Form 7
- **sass/mobile.scss** : Styles responsive/mobile
- **sass/parts/** : Partials (fonts.scss contient les breakpoints et mixins de taille)
- Compilation : `gulp watch` (compile les 3 fichiers SCSS vers css/)

## Système de tailles (IMPORTANT)
La maquette de référence est en **1920px**.
Le système utilise des fonctions SCSS pour convertir px → vw :
- `widthCalc($px)` : pour écrans >= 1366px → `calc($px * vw / 19.2)`
- `widthCalcFixedSize($px)` : pour écrans < 1366px → `calc($px * vw / 13.66)`
- `@include fontSize($px)` : applique les deux + fallback mobile

## Breakpoints
- Desktop : > 1366px (référence maquette 1920px)
- Tablette/petit desktop : <= 1366px (référence 1366px)
- Tablette : <= 767px
- Mobile : <= 425px

## Conventions
- Intégration pixel-perfect depuis maquettes
- Classes utilitaires : .center, .uppercase, .white, .underline, .nowrap, .full-width
- Les classes Visual Composer sont préfixées `.vce`
- Le margin-bottom des éléments VC est reset à 0 (.vce { margin-bottom: 0 })
- Resets appliqués sur p, h1-h3, ul (margin-block à 0)

## Structure des fichiers
```
vsc-theme/
├── sass/                    # Sources SCSS
│   ├── statics.scss         # Variables + resets + mixins
│   ├── header.scss          # → compile vers css/header.css
│   ├── style.scss           # → compile vers css/style.css
│   ├── mobile.scss          # → compile vers css/mobile.css
│   └── parts/
│       └── fonts.scss       # Breakpoints, fonctions widthCalc, mixin fontSize
├── css/                     # CSS compilés (ne pas modifier directement)
├── js/                      # Scripts JS
├── slick-slider/            # Slick carousel (dispo, commenté dans functions.php)
├── inc/                     # Fonctions PHP (customizer, template-tags, woocommerce)
├── template-parts/          # Parties de templates WP
├── woocommerce/             # Templates WooCommerce custom
├── integrated_vc.php        # Shortcodes custom (ex: Google Map)
├── functions.php            # Enqueue styles/scripts, includes
├── header.php               # Header (logo + nav menu-1)
├── footer.php               # Footer
└── gulpfile.js              # Compilation SCSS → CSS
```

## Ordre de chargement CSS
1. css/header.css (dans wp_head)
2. css/mobile.css (dans wp_footer)
3. css/style.css (dans wp_footer)
4. css/fonts/fonts.css (dans wp_footer)

## Quand tu génères du code pour ce projet :
- Écris le SCSS, pas le CSS directement
- Utilise les fonctions widthCalc() et le mixin fontSize() pour les dimensions
- Place les styles de header dans sass/header.scss
- Place les styles de sections dans sass/style.scss
- Place les media queries mobile dans sass/mobile.scss
- Pour les nouveaux shortcodes VC, ajoute-les dans integrated_vc.php
- Pour les nouveaux partials SCSS, crée-les dans sass/parts/ et importe-les
