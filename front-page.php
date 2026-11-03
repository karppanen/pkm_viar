<?php
get_header();

$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
$catalog_limit = 12;
$products = function_exists('wc_get_products') ? wc_get_products(array('status' => 'publish', 'limit' => $catalog_limit, 'featured' => true)) : array();
if (function_exists('wc_get_products') && count($products) < $catalog_limit) {
    $remaining_products = wc_get_products(array(
        'status' => 'publish',
        'limit' => $catalog_limit - count($products),
        'orderby' => 'date',
        'order' => 'DESC',
        'exclude' => array_map(static function ($product) { return $product->get_id(); }, $products),
    ));
    $products = array_merge($products, $remaining_products);
}
$published_product_count = post_type_exists('product') ? (int) wp_count_posts('product')->publish : count($products);
$catalog_categories = taxonomy_exists('product_cat') ? get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC')) : array();
$hero_products = $products ? array_slice($products, 0, 8) : array();
?>
<section class="hero">
    <div class="container hero__grid">
        <div class="hero__content">
            <p class="eyebrow">Dealer resmi VIAR &middot; Cabang Ciledug &middot; Tangerang</p>
            <h1>Kendaraan yang <span>bekerja</span> untuk Anda.</h1>
            <p class="hero__lead">Temukan motor harian, kendaraan niaga, ATV, dan sepeda listrik untuk mobilitas maupun pertumbuhan usaha Anda.</p>
            <div class="hero__actions">
                <a class="button button--red" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Lihat katalog', 'motoshop'); ?><span aria-hidden="true">&#8594;</span></a>
                <a class="text-link" href="#request-form"><?php esc_html_e('Konsultasi dengan sales', 'motoshop'); ?></a>
            </div>
            <div class="hero__trust"><span><?php esc_html_e('Penjualan tunai & kredit', 'motoshop'); ?></span><span><?php esc_html_e('Cabang Ciledug, Tangerang', 'motoshop'); ?></span><span><?php esc_html_e('Layanan 3S', 'motoshop'); ?></span></div>
        </div>
        <div class="hero__visual" aria-label="Produk unggulan" data-hero-slider data-interval="5200">
            <div class="hero__visual-top"><span class="hero__edition">Mahkota Selection</span><span class="hero__number" data-hero-number>01 / <?php echo esc_html(sprintf('%02d', max(1, count($hero_products)))); ?></span></div>
            <div class="hero__slides">
                <?php if ($hero_products) : ?>
                    <?php foreach ($hero_products as $index => $hero_product) : ?>
                        <article class="hero__slide<?php echo 0 === $index ? ' is-active' : ''; ?>" data-hero-slide aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
                            <div class="hero__vehicle">
                                <a href="<?php echo esc_url($hero_product->get_permalink()); ?>" tabindex="<?php echo 0 === $index ? '0' : '-1'; ?>">
                                    <?php echo $hero_product->get_image('large', array('loading' => 'eager', 'decoding' => 'sync', 'fetchpriority' => 0 === $index ? 'high' : 'auto')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </a>
                            </div>
                            <div class="hero__product-label"><span>Unit pilihan</span><strong><?php echo esc_html($hero_product->get_name()); ?></strong><a href="<?php echo esc_url($hero_product->get_permalink()); ?>" tabindex="<?php echo 0 === $index ? '0' : '-1'; ?>">Lihat detail &#8594;</a></div>
                        </article>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="hero__placeholder">VIAR</div>
                <?php endif; ?>
            </div>
            <?php if (count($hero_products) > 1) : ?>
                <div class="hero__slider-controls">
                    <button type="button" data-hero-prev aria-label="Produk sebelumnya">&#8592;</button>
                    <div class="hero__slider-dots" role="group" aria-label="Pilih produk unggulan">
                        <?php foreach ($hero_products as $index => $hero_product) : ?>
                            <button type="button" data-hero-dot="<?php echo esc_attr($index); ?>" class="<?php echo 0 === $index ? 'is-active' : ''; ?>" aria-label="<?php echo esc_attr(sprintf('Tampilkan %s', $hero_product->get_name())); ?>" aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" data-hero-next aria-label="Produk berikutnya">&#8594;</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="category-band" aria-label="Kategori kendaraan">
    <div class="container category-list">
        <?php
        $categories = array();
        if ($catalog_categories && !is_wp_error($catalog_categories)) {
            foreach ($catalog_categories as $catalog_category) {
                if ((int) $catalog_category->count < 1) {
                    continue;
                }
                $term_url = get_term_link($catalog_category);
                if (is_wp_error($term_url)) {
                    continue;
                }
                $categories[] = array(
                    'number' => str_pad((string) (count($categories) + 1), 2, '0', STR_PAD_LEFT),
                    'name'   => $catalog_category->name,
                    'note'   => sprintf('%d unit tersedia', (int) $catalog_category->count),
                    'url'    => $term_url,
                );
            }
        }
        foreach (array_slice($categories, 0, 4) as $category) :
            ?>
            <a href="<?php echo esc_url($category['url']); ?>"><span><?php echo esc_html($category['number']); ?></span><strong><?php echo esc_html($category['name']); ?></strong><small><?php echo esc_html($category['note']); ?></small></a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section products-section" id="motor">
    <div class="container">
        <div class="section-heading section-heading--split">
            <div>
                <p class="eyebrow"><?php esc_html_e('Pilihan kendaraan', 'motoshop'); ?></p>
                <h2><?php esc_html_e('Cari unit yang cocok dengan pekerjaan Anda.', 'motoshop'); ?></h2>
                <p class="products-summary"><?php echo esc_html(sprintf(__('Menampilkan %1$d pilihan dari %2$d produk yang tersedia.', 'motoshop'), count($products), $published_product_count)); ?></p>
            </div>
            <a class="button products-all-link" href="<?php echo esc_url($shop_url); ?>"><span><?php esc_html_e('Lihat semua motor', 'motoshop'); ?></span><span aria-hidden="true">&#8594;</span></a>
        </div>
        <?php if ($products) : ?>
            <div class="catalog-filter" role="group" aria-label="Filter kendaraan">
                <button class="is-active" type="button" data-product-filter="all">Semua</button>
                <?php if ($catalog_categories && !is_wp_error($catalog_categories)) : ?>
                    <?php foreach ($catalog_categories as $catalog_category) : ?>
                        <button type="button" data-product-filter="<?php echo esc_attr($catalog_category->slug); ?>"><?php echo esc_html($catalog_category->name); ?></button>
                    <?php endforeach; ?>
                <?php endif; ?>
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

<section class="trust-strip" aria-label="Keunggulan layanan">
    <div class="container trust-strip__grid">
        <div><span>01</span><strong>Dealer resmi VIAR</strong><small>Informasi produk dan layanan dealer</small></div>
        <div><span>02</span><strong>Tunai &amp; kredit</strong><small>Pilihan pembelian sesuai kebutuhan</small></div>
        <div><span>03</span><strong><?php esc_html_e('Lokasi Tangerang', 'motoshop'); ?></strong><small><?php esc_html_e('Cabang Ciledug, Pedurenan', 'motoshop'); ?></small></div>
        <div><span>04</span><strong>Layanan 3S</strong><small>Sales, service, dan sparepart</small></div>
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
            <p><?php esc_html_e('Cabang Viar Mahkota Ciledug berada di bawah naungan PT Mahkota Inti Sejahtera. Kami menyediakan motor konvensional, kendaraan niaga roda tiga dengan pilihan karoseri, ATV, dan sepeda listrik.', 'motoshop'); ?></p>
            <p><?php esc_html_e('Cabang Ciledug melayani penjualan tunai dan kredit, servis berkala, serta kebutuhan suku cadang untuk kawasan Pedurenan, Karang Tengah, dan sekitarnya.', 'motoshop'); ?></p>
            <a class="text-link" href="#kontak"><?php esc_html_e('Lihat lokasi cabang', 'motoshop'); ?> &#8595;</a>
        </div>
    </div>
</section>

<?php $branch = motoshop_primary_branch(); ?>
<section class="section contact-section" id="kontak">
    <div class="container contact-grid">
        <div class="contact-copy">
            <p class="eyebrow"><?php esc_html_e('Kunjungi cabang', 'motoshop'); ?></p>
            <h2><?php echo esc_html($branch['name']); ?></h2>
            <p><?php esc_html_e('Tim sales kami siap membantu memilih unit yang sesuai kebutuhan harian maupun operasional usaha Anda, lengkap dengan simulasi kredit dan jadwal kunjungan.', 'motoshop'); ?></p>
            <ul class="contact-list">
                <li><span><?php esc_html_e('Alamat', 'motoshop'); ?></span><strong><?php echo esc_html($branch['address']); ?></strong></li>
                <li><span><?php esc_html_e('Jam operasional', 'motoshop'); ?></span><strong><?php echo esc_html($branch['hours']); ?></strong></li>
                <li><span><?php esc_html_e('WhatsApp dan telepon', 'motoshop'); ?></span><strong><?php echo esc_html($branch['phone']); ?></strong></li>
                <li><span><?php esc_html_e('Layanan', 'motoshop'); ?></span><strong><?php esc_html_e('Penjualan, servis, dan suku cadang', 'motoshop'); ?></strong></li>
            </ul>
            <div class="contact-actions">
                <a class="button button--red" href="<?php echo esc_url(motoshop_whatsapp_link(__('Halo, saya ingin bertanya motor VIAR di Cabang Ciledug.', 'motoshop'))); ?>" target="_blank" rel="noopener"><?php esc_html_e('Chat WhatsApp', 'motoshop'); ?></a>
                <a class="button button--outline" href="<?php echo esc_url($branch['maps']); ?>" target="_blank" rel="noopener" data-track="map"><?php esc_html_e('Buka Google Maps', 'motoshop'); ?></a>
            </div>
        </div>
        <aside class="contact-note">
            <h3><?php esc_html_e('Pembelian dan pembayaran', 'motoshop'); ?></h3>
            <ul>
                <li><?php esc_html_e('Website ini hanya menampilkan katalog dan mencatat permintaan calon pelanggan.', 'motoshop'); ?></li>
                <li><?php esc_html_e('Harga final, ketersediaan unit, dan simulasi kredit ditentukan oleh sales cabang.', 'motoshop'); ?></li>
                <li><?php esc_html_e('Pemeriksaan dokumen, pembayaran, dan serah terima kendaraan dilakukan langsung di cabang.', 'motoshop'); ?></li>
                <li><?php esc_html_e('Formulir website tidak meminta dokumen identitas, slip gaji, atau data rekening.', 'motoshop'); ?></li>
            </ul>
        </aside>
    </div>
</section>

<section class="section request-section"><div class="container"><?php echo motoshop_request_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div></section>

<?php
$posts = get_posts(array('numberposts' => 3, 'post_status' => 'publish'));
if ($posts) :
?>
<section class="section journal-section">
    <div class="container">
        <div class="section-heading section-heading--split"><div><p class="eyebrow"><?php esc_html_e('Artikel dan panduan', 'motoshop'); ?></p><h2><?php esc_html_e('Sebelum memilih kendaraan.', 'motoshop'); ?></h2></div><a class="button button--soft" href="<?php echo esc_url(home_url('/artikel/')); ?>"><?php esc_html_e('Lihat semua artikel', 'motoshop'); ?></a></div>
        <div class="journal-grid">
            <?php foreach ($posts as $post) : setup_postdata($post); ?>
                <article><p><?php echo esc_html(get_the_date('d.m.Y')); ?></p><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><div><?php the_excerpt(); ?></div><a class="journal-card__link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('Baca artikel %s', 'motoshop'), get_the_title())); ?>"><?php esc_html_e('Baca artikel', 'motoshop'); ?></a></article>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php get_footer(); ?>
