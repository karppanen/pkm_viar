<?php get_header(); ?>
<?php if (is_product()) : ?>
    <section class="page-hero page-hero--product">
        <div class="container">
            <nav class="product-hero-menu" aria-label="<?php esc_attr_e('Navigasi katalog', 'motoshop'); ?>">
                <a class="product-hero-menu__back" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><span aria-hidden="true">&#8592;</span> <?php esc_html_e('Kembali ke katalog', 'motoshop'); ?></a>
            </nav>
            <p class="eyebrow"><?php esc_html_e('Katalog kendaraan', 'motoshop'); ?></p>
            <h1><?php the_title(); ?></h1>
        </div>
    </section>
<?php else : ?>
    <section class="page-hero page-hero--shop"><div class="container"><p class="eyebrow"><?php esc_html_e('Katalog kendaraan', 'motoshop'); ?></p><h1><?php woocommerce_page_title(); ?></h1><p><?php esc_html_e('Bandingkan unit, cek spesifikasi, lalu ajukan konsultasi ke cabang terdekat.', 'motoshop'); ?></p></div></section>
<?php endif; ?>
<section class="section shop-content">
    <div class="container">
        <?php if (is_product()) : ?>
            <?php woocommerce_content(); ?>
        <?php else : ?>
            <?php
            $active_category = is_product_category() ? get_queried_object() : null;
            $product_categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC'));
            ?>
            <div class="catalog-layout">
                <aside class="catalog-sidebar" id="catalog-categories" aria-label="Pilihan kategori motor">
                    <div class="catalog-sidebar__heading"><p class="eyebrow"><?php esc_html_e('Temukan kendaraan', 'motoshop'); ?></p><h2><?php esc_html_e('Pilih kategori', 'motoshop'); ?></h2><p><?php esc_html_e('Saring katalog berdasarkan kebutuhan Anda.', 'motoshop'); ?></p></div>
                    <form class="catalog-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <label for="catalog-search-input"><?php esc_html_e('Cari motor', 'motoshop'); ?></label>
                        <div><input id="catalog-search-input" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Contoh: Karya 300"><button type="submit" aria-label="<?php esc_attr_e('Cari produk', 'motoshop'); ?>">&#8594;</button></div>
                        <input type="hidden" name="post_type" value="product">
                    </form>
                    <nav class="catalog-categories" aria-label="Kategori produk">
                        <a class="<?php echo $active_category ? '' : 'is-active'; ?>" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><span><?php esc_html_e('Semua Motor', 'motoshop'); ?></span><small><?php echo esc_html(wp_count_posts('product')->publish); ?></small></a>
                        <?php if ($product_categories && !is_wp_error($product_categories)) : ?>
                            <?php foreach ($product_categories as $category) : ?>
                                <a class="<?php echo $active_category && (int) $active_category->term_id === (int) $category->term_id ? 'is-active' : ''; ?>" href="<?php echo esc_url(get_term_link($category)); ?>"><span><?php echo esc_html($category->name); ?></span><small><?php echo esc_html($category->count); ?></small></a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </nav>
                    <div class="catalog-help"><strong><?php esc_html_e('Belum yakin memilih?', 'motoshop'); ?></strong><p><?php esc_html_e('Ceritakan kebutuhan angkut, jarak tempuh, atau rencana penggunaan Anda.', 'motoshop'); ?></p><a href="<?php echo esc_url(home_url('/#request-form')); ?>"><?php esc_html_e('Konsultasi gratis', 'motoshop'); ?> &#8594;</a></div>
                </aside>
                <div class="catalog-results">
                    <div class="catalog-results__intro">
                        <p class="eyebrow"><?php esc_html_e('Daftar kendaraan', 'motoshop'); ?></p>
                        <h2><?php echo $active_category ? esc_html($active_category->name) : esc_html__('Semua motor', 'motoshop'); ?></h2>
                        <?php if ($active_category && $active_category->description) : ?><p><?php echo esc_html($active_category->description); ?></p><?php endif; ?>
                    </div>
                    <?php woocommerce_content(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
