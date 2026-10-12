<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e('Lewati ke konten', 'motoshop'); ?></a>
<div class="topbar">
    <div class="container topbar__inner">
        <p><?php esc_html_e('Dealer resmi motor VIAR - PT Mahkota Inti Sejahtera', 'motoshop'); ?></p>
        <div><a href="tel:+6285591220003">0855 9122 0003</a><span aria-hidden="true"> / </span><a href="https://www.instagram.com/viar.mahkota_official/" target="_blank" rel="noopener">@viar.mahkota_official</a></div>
    </div>
</div>
<header class="site-header" data-site-header>
    <div class="container site-header__inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('MotoShop beranda', 'motoshop'); ?>">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <span class="brand__mark" aria-hidden="true">M</span>
                <span class="brand__copy"><strong>MAHKOTA</strong><small>Dealer resmi motor VIAR</small></span>
            <?php endif; ?>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation"><span></span><span></span><span></span><span class="screen-reader-text"><?php esc_html_e('Buka menu', 'motoshop'); ?></span></button>
        <nav class="primary-navigation" id="primary-navigation" aria-label="<?php esc_attr_e('Navigasi utama', 'motoshop'); ?>">
            <?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'site-menu', 'fallback_cb' => 'motoshop_fallback_menu')); ?>
        </nav>
        <a class="header-cta" href="<?php echo esc_url(home_url('/#request-form')); ?>"><?php esc_html_e('Konsultasi', 'motoshop'); ?></a>
    </div>
</header>
<main id="main-content">
