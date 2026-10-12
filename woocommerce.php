<?php get_header(); ?>
<?php if (is_product()) : ?>
    <section class="page-hero page-hero--product"><div class="container"><p class="eyebrow"><?php esc_html_e('Katalog kendaraan', 'motoshop'); ?></p><h1><?php esc_html_e('Detail motor', 'motoshop'); ?></h1></div></section>
<?php else : ?>
    <section class="page-hero page-hero--shop"><div class="container"><p class="eyebrow"><?php esc_html_e('Katalog kendaraan', 'motoshop'); ?></p><h1><?php woocommerce_page_title(); ?></h1><p><?php esc_html_e('Bandingkan unit, cek spesifikasi, lalu ajukan konsultasi ke cabang terdekat.', 'motoshop'); ?></p></div></section>
<?php endif; ?>
<section class="section shop-content"><div class="container"><?php woocommerce_content(); ?></div></section>
<?php get_footer(); ?>
