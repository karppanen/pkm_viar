<?php
get_header();

$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
$products = function_exists('wc_get_products') ? wc_get_products(array('status' => 'publish', 'limit' => 6, 'featured' => true)) : array();
if (!$products && function_exists('wc_get_products')) {
    $products = wc_get_products(array('status' => 'publish', 'limit' => 6, 'orderby' => 'date', 'order' => 'DESC'));
}
$hero_product = $products ? reset($products) : false;
?>
<section class="hero">
    <div class="container hero__grid">
        <div class="hero__content">
            <p class="eyebrow">Dealer resmi VIAR &middot; PT Mahkota Inti Sejahtera</p>
            <h1>Kendaraan yang <span>bekerja</span> untuk Anda.</h1>
            <p class="hero__lead">Temukan motor harian, kendaraan niaga, ATV, dan kendaraan listrik untuk mobilitas maupun pertumbuhan usaha Anda.</p>
            <div class="hero__actions">
                <a class="button button--red" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Lihat katalog', 'motoshop'); ?><span aria-hidden="true">&#8594;</span></a>
                <a class="text-link" href="#request-form"><?php esc_html_e('Konsultasi dengan sales', 'motoshop'); ?></a>
            </div>
            <div class="hero__trust"><span>Penjualan tunai &amp; kredit</span><span>5 jaringan cabang</span><span>Layanan 3S</span></div>
        </div>
        <div class="hero__visual" aria-label="Produk unggulan">
            <div class="hero__visual-top"><span class="hero__edition">Mahkota Selection</span><span class="hero__number">01</span></div>
            <div class="hero__vehicle">
                <?php if ($hero_product) : ?>
                    <a href="<?php echo esc_url($hero_product->get_permalink()); ?>">
                        <?php echo $hero_product->get_image('large', array('loading' => 'eager')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </a>
                <?php else : ?>
                    <div class="hero__placeholder">VIAR</div>
                <?php endif; ?>
            </div>
            <div class="hero__product-label"><span>Unit pilihan</span><strong><?php echo esc_html($hero_product ? $hero_product->get_name() : 'VIAR Mahkota'); ?></strong><a href="<?php echo esc_url($hero_product ? $hero_product->get_permalink() : $shop_url); ?>">Lihat detail &#8594;</a></div>
        </div>
    </div>
</section>

<section class="category-band" aria-label="Kategori kendaraan">
    <div class="container category-list">
        <?php
        $categories = array(
            array('01', 'Motor Niaga', 'Usaha dan angkutan', 'motor-niaga'),
            array('02', 'Motor Listrik', 'Mobilitas perkotaan', 'motor-listrik'),
            array('03', 'Sepeda Listrik', 'Mobilitas praktis', 'sepeda-listrik'),
            array('04', 'ATV & Trail', 'Medan dan rekreasi', ''),
        );
        foreach ($categories as $category) :
            $category_url = $shop_url;
            if ($category[3]) {
                $term_url = get_term_link($category[3], 'product_cat');
                if (!is_wp_error($term_url)) {
                    $category_url = $term_url;
                }
            }
        ?>
            <a href="<?php echo esc_url($category_url); ?>"><span><?php echo esc_html($category[0]); ?></span><strong><?php echo esc_html($category[1]); ?></strong><small><?php echo esc_html($category[2]); ?></small></a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section products-section" id="motor">
    <div class="container">
        <div class="section-heading section-heading--split">
            <div><p class="eyebrow"><?php esc_html_e('Pilihan kendaraan', 'motoshop'); ?></p><h2><?php esc_html_e('Cari unit yang cocok dengan pekerjaan Anda.', 'motoshop'); ?></h2></div>
            <a class="text-link" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Buka semua motor', 'motoshop'); ?> &#8594;</a>
        </div>
        <?php if ($products) : ?>
            <div class="catalog-filter" role="group" aria-label="Filter kendaraan">
                <button class="is-active" type="button" data-product-filter="all">Semua</button>
                <button type="button" data-product-filter="motor-niaga">Niaga</button>
                <button type="button" data-product-filter="motor-listrik">Listrik</button>
                <button type="button" data-product-filter="motor-trail">Off-road</button>
            </div>
            <div class="product-grid">
                <?php foreach ($products as $product) :
                    $product_terms = get_the_terms($product->get_id(), 'product_cat');
                    $product_slugs = $product_terms && !is_wp_error($product_terms) ? wp_list_pluck($product_terms, 'slug') : array();
                ?>
                    <article class="product-card reveal" data-product-categories="<?php echo esc_attr(implode(' ', $product_slugs)); ?>">
                        <a class="product-card__image" href="<?php echo esc_url($product->get_permalink()); ?>">
                            <?php echo $product->get_image('woocommerce_thumbnail'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <?php if ($product->is_on_sale()) : ?><span class="product-badge"><?php esc_html_e('Promo', 'motoshop'); ?></span><?php endif; ?>
                        </a>
                        <div class="product-card__body">
                            <p class="product-card__meta"><?php echo wp_kses_post(wc_get_product_category_list($product->get_id(), ' / ')); ?></p>
                            <h3><a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a></h3>
                            <div class="product-card__footer"><span><?php echo wp_kses_post($product->get_price_html() ?: __('Hubungi sales', 'motoshop')); ?></span><a href="<?php echo esc_url($product->get_permalink()); ?>" aria-label="<?php echo esc_attr(sprintf(__('Lihat %s', 'motoshop'), $product->get_name())); ?>">&#8594;</a></div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="catalog-empty">
                <div><span>01</span><h3><?php esc_html_e('Katalog siap diisi dari WooCommerce', 'motoshop'); ?></h3></div>
                <p><?php esc_html_e('Tambahkan nama motor, foto, harga, kategori, stok, dan spesifikasi dari menu Produk. Daftar ini akan terisi otomatis tanpa mengubah kode.', 'motoshop'); ?></p>
                <?php if (current_user_can('edit_products')) : ?><a class="button button--outline" href="<?php echo esc_url(admin_url('post-new.php?post_type=product')); ?>"><?php esc_html_e('Tambah produk', 'motoshop'); ?></a><?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="partner-strip" aria-label="Mitra sementara">
    <div class="container">
        <div class="partner-strip__intro"><span>Mitra ekosistem</span><small>Logo sementara &mdash; dapat diganti dari tema</small></div>
        <div class="partner-logos">
            <?php foreach (array('Mitra Satu', 'Mitra Dua', 'Mitra Tiga', 'Mitra Empat', 'Mitra Lima') as $index => $partner) : ?>
                <div class="partner-logo" aria-label="<?php echo esc_attr($partner); ?>"><i><?php echo esc_html(sprintf('%02d', $index + 1)); ?></i><strong><?php echo esc_html($partner); ?></strong></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section services" id="layanan">
    <div class="container services__grid">
        <div class="section-heading"><p class="eyebrow"><?php esc_html_e('Layanan purna jual', 'motoshop'); ?></p><h2><?php esc_html_e('Bukan selesai saat motor keluar dari dealer.', 'motoshop'); ?></h2><p><?php esc_html_e('Tim kami membantu dari pemilihan unit hingga kendaraan tetap siap digunakan.', 'motoshop'); ?></p></div>
        <div class="service-list">
            <article><span>01</span><div><h3>Sales</h3><p><?php esc_html_e('Konsultasi unit, ketersediaan, pembelian tunai atau kredit, dan proses dokumen.', 'motoshop'); ?></p></div></article>
            <article><span>02</span><div><h3>Service</h3><p><?php esc_html_e('Perawatan di bengkel resmi untuk menjaga keamanan dan kenyamanan kendaraan.', 'motoshop'); ?></p></div></article>
            <article><span>03</span><div><h3>Sparepart</h3><p><?php esc_html_e('Suku cadang dan aksesori untuk kebutuhan perawatan maupun operasional.', 'motoshop'); ?></p></div></article>
        </div>
    </div>
</section>

<section class="section purchase-flow">
    <div class="container">
        <div class="section-heading"><p class="eyebrow"><?php esc_html_e('Cara membeli', 'motoshop'); ?></p><h2><?php esc_html_e('Mulai dari kebutuhan, bukan sekadar tipe motor.', 'motoshop'); ?></h2></div>
        <ol class="flow-list">
            <li><span>01</span><h3><?php esc_html_e('Pilih kendaraan', 'motoshop'); ?></h3><p><?php esc_html_e('Bandingkan kategori, spesifikasi, dan kegunaan unit.', 'motoshop'); ?></p></li>
            <li><span>02</span><h3><?php esc_html_e('Kirim permintaan', 'motoshop'); ?></h3><p><?php esc_html_e('Tentukan tunai atau kredit serta cabang yang paling dekat.', 'motoshop'); ?></p></li>
            <li><span>03</span><h3><?php esc_html_e('Dihubungi sales', 'motoshop'); ?></h3><p><?php esc_html_e('Sales mengonfirmasi stok, harga, dokumen, dan simulasi.', 'motoshop'); ?></p></li>
            <li><span>04</span><h3><?php esc_html_e('Kunjungan dan serah terima', 'motoshop'); ?></h3><p><?php esc_html_e('Lanjutkan proses di cabang dan atur penyerahan kendaraan.', 'motoshop'); ?></p></li>
        </ol>
    </div>
</section>

<section class="section value-section">
    <div class="container value-grid">
        <div><p class="eyebrow">Kenapa Mahkota</p><h2>Lebih mudah memilih, lebih jelas prosesnya.</h2></div>
        <div class="value-list">
            <article><strong>01</strong><h3>Konsultasi sesuai kebutuhan</h3><p>Rekomendasi unit disesuaikan dengan mobilitas, operasional usaha, dan anggaran Anda.</p></article>
            <article><strong>02</strong><h3>Jaringan cabang terdekat</h3><p>Permintaan diteruskan ke cabang pilihan agar informasi unit dan tindak lanjut lebih relevan.</p></article>
            <article><strong>03</strong><h3>Dukungan setelah pembelian</h3><p>Layanan servis dan suku cadang membantu kendaraan tetap siap digunakan.</p></article>
        </div>
    </div>
</section>

<section class="section about-section" id="tentang">
    <div class="container about-grid">
        <div class="about-visual"><div class="about-visual__word">MIS</div><p><?php esc_html_e('Dealer resmi motor VIAR', 'motoshop'); ?></p></div>
        <div class="about-copy">
            <p class="eyebrow"><?php esc_html_e('Tentang kami', 'motoshop'); ?></p>
            <h2><?php esc_html_e('Solusi mobilitas untuk harian, usaha, dan medan khusus.', 'motoshop'); ?></h2>
            <p><?php esc_html_e('VIAR Mahkota berada di bawah naungan PT Mahkota Inti Sejahtera. Kami menyediakan motor konvensional, kendaraan niaga roda tiga dengan pilihan karoseri, ATV, dan sepeda listrik.', 'motoshop'); ?></p>
            <p><?php esc_html_e('Jaringan cabang kami melayani penjualan tunai dan kredit, servis, serta kebutuhan suku cadang.', 'motoshop'); ?></p>
            <a class="text-link" href="#kontak"><?php esc_html_e('Lihat lokasi cabang', 'motoshop'); ?> &#8595;</a>
        </div>
    </div>
</section>

<section class="section request-section"><div class="container"><?php echo motoshop_request_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div></section>

<?php
$posts = get_posts(array('numberposts' => 3, 'post_status' => 'publish'));
if ($posts) :
?>
<section class="section journal-section">
    <div class="container">
        <div class="section-heading section-heading--split"><div><p class="eyebrow"><?php esc_html_e('Artikel dan panduan', 'motoshop'); ?></p><h2><?php esc_html_e('Sebelum memilih kendaraan.', 'motoshop'); ?></h2></div><a class="text-link" href="<?php echo esc_url(home_url('/artikel/')); ?>"><?php esc_html_e('Semua artikel', 'motoshop'); ?> &#8594;</a></div>
        <div class="journal-grid">
            <?php foreach ($posts as $post) : setup_postdata($post); ?>
                <article><p><?php echo esc_html(get_the_date('d.m.Y')); ?></p><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><div><?php the_excerpt(); ?></div><a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">&#8594;</a></article>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php get_footer(); ?>
