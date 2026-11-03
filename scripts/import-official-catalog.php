<?php
/**
 * Import the official VIAR catalogue (scraped from viarmotor.com) into WooCommerce.
 *
 * Data produk, spesifikasi, dan foto diambil dari halaman produk resmi VIAR.
 * Harga tidak diisi: halaman produk menampilkan "Hubungi sales" karena harga
 * dapat berbeda antar cabang dan berubah mengikuti promo.
 *
 * Run: php wp-content/themes/motoshop/scripts/import-official-catalog.php
 */

if ('cli' !== PHP_SAPI) {
    exit("CLI only.\n");
}

define('WP_USE_THEMES', false);
require_once dirname(__DIR__, 4) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

if (!class_exists('WooCommerce')) {
    exit("WooCommerce is not active.\n");
}

$json_path = __DIR__ . '/official-catalog.json';
if (!file_exists($json_path)) {
    exit("official-catalog.json not found. Run scrape-official-catalog.ps1 first.\n");
}

$raw = file_get_contents($json_path);
if (0 === strpos($raw, "\xEF\xBB\xBF")) {
    $raw = substr($raw, 3);
}
$catalog = json_decode($raw, true);
if (!is_array($catalog)) {
    exit("official-catalog.json could not be decoded.\n");
}

/**
 * Curated product identity per official source page.
 * name     : display name used on the website
 * sku      : unique stock keeping unit
 * category : product_cat term
 * summary  : one-sentence description for the catalogue card
 */
$map = array(
    'https://viarmotor.com/commercials-karya-bit/'                        => array('name' => 'VIAR Karya BIT', 'sku' => 'VIAR-KARYA-BIT', 'category' => 'Motor Niaga', 'summary' => 'Motor roda tiga compact 97 cc untuk operasional usaha harian dengan bak angkut ringkas.'),
    'https://viarmotor.com/commercials-new-karya-150l/'                    => array('name' => 'VIAR Karya 150 RLn', 'sku' => 'VIAR-KARYA-150-RLN', 'category' => 'Motor Niaga', 'summary' => 'Motor niaga roda tiga dengan bak angkut dan mesin 150 cc berpendingin radiator.'),
    'https://viarmotor.com/commercials-new-karya-200n-l/'                  => array('name' => 'VIAR Karya 200 Ln', 'sku' => 'VIAR-KARYA-200-LN', 'category' => 'Motor Niaga', 'summary' => 'Motor niaga roda tiga dengan bak panjang untuk distribusi, angkutan barang, dan kebutuhan usaha.'),
    'https://viarmotor.com/commercials-new-karya-300/'                     => array('name' => 'VIAR Karya 300', 'sku' => 'VIAR-KARYA-300', 'category' => 'Motor Niaga', 'summary' => 'Motor niaga roda tiga berkapasitas 270 cc dengan bak panjang dan daya angkut hingga 852 kg.'),
    'https://viarmotor.com/commercials-karya-bit-ram/'                     => array('name' => 'VIAR Karya BIT RAM', 'sku' => 'VIAR-KARYA-BIT-RAM', 'category' => 'Motor Niaga', 'summary' => 'Varian Karya BIT dengan rangka diperkuat untuk beban kerja yang lebih berat.'),
    'https://viarmotor.com/commercials-karya-150rn-rln-ram/'               => array('name' => 'VIAR Karya 150 RAM', 'sku' => 'VIAR-KARYA-150-RAM', 'category' => 'Motor Niaga', 'summary' => 'Varian Karya 150 dengan rangka diperkuat untuk kebutuhan angkutan lebih berat.'),
    'https://viarmotor.com/commercials-karya-200ln-ram/'                  => array('name' => 'VIAR Karya 200 RAM', 'sku' => 'VIAR-KARYA-200-RAM', 'category' => 'Motor Niaga', 'summary' => 'Varian Karya 200 dengan rangka diperkuat untuk distribusi harian dengan beban besar.'),
    'https://viarmotor.com/commercials-karya-300nn-ram/'                  => array('name' => 'VIAR Karya 300 RAM', 'sku' => 'VIAR-KARYA-300-RAM', 'category' => 'Motor Niaga', 'summary' => 'Varian Karya 300 dengan rangka diperkuat untuk operasional distribusi terberat.'),
    'https://viarmotor.com/commercials-karya-300-mdt-bak-sampah-terbuka/' => array('name' => 'VIAR Karya 300 MDT Bak Sampah Terbuka', 'sku' => 'VIAR-KARYA-300-MDT', 'category' => 'Motor Niaga', 'summary' => 'Karya 300 dengan karoseri bak sampah terbuka untuk penanganan sampah perkotaan.'),
    'https://viarmotor.com/commercials-karya-150-box-alumunium-profile/'  => array('name' => 'VIAR Karya 150 Box Aluminium', 'sku' => 'VIAR-KARYA-150-BOX', 'category' => 'Motor Niaga', 'summary' => 'Karya 150 dengan bak box alumunium profile untuk angkut material dan barang yang dapat didaur ulang.'),
    'https://viarmotor.com/commercials-karya-200-r-ppk/'                   => array('name' => 'VIAR Karya 200 PPK', 'sku' => 'VIAR-KARYA-200-PPK', 'category' => 'Motor Niaga', 'summary' => 'Varian Karya 200 dengan konfigurasi bak untuk kebutuhan usaha harian.'),
    'https://viarmotor.com/commercials-karya-200l-mdt-bak-terbuka/'       => array('name' => 'VIAR Karya 200 MDT Bak Terbuka', 'sku' => 'VIAR-KARYA-200-MDT', 'category' => 'Motor Niaga', 'summary' => 'Karya 200 dengan karoseri MDT bak terbuka untuk angkut material.'),
    'https://viarmotor.com/commercials-karya-200l-hmdt-bak-tertutup/'     => array('name' => 'VIAR Karya 200 HMDT Bak Tertutup', 'sku' => 'VIAR-KARYA-200-HMDT', 'category' => 'Motor Niaga', 'summary' => 'Karya 200 dengan karoseri HMDT bak tertutup untuk angkut material dalam kondisi tertutup.'),
    'https://viarmotor.com/commercials-karya-mbg-200-ln/'                  => array('name' => 'VIAR Karya MBG 200 Ln', 'sku' => 'VIAR-KARYA-MBG-200', 'category' => 'Motor Niaga', 'summary' => 'Karya 200 dengan konfigurasi khusus untuk kebutuhan operasional harian dan layanan masyarakat.'),
    'https://viarmotor.com/commercials-karya-e5/'                         => array('name' => 'VIAR Karya E5', 'sku' => 'VIAR-KARYA-E5', 'category' => 'Motor Niaga', 'summary' => 'Kendaraan niaga roda tiga bertenaga listrik dengan daya angkut hingga 442 kg.'),
    'https://viarmotor.com/commercials-new-karya-karya-traktor-200-300/'  => array('name' => 'VIAR Karya Traktor 200 / 300', 'sku' => 'VIAR-KARYA-TRAKTOR', 'category' => 'Motor Niaga', 'summary' => 'Karya seri traktor untuk pekerjaan pertanian dan beban kerja berat.'),
    'https://viarmotor.com/commercials-vxt-350-s/'                         => array('name' => 'VIAR VXT 350 S (4x4)', 'sku' => 'VIAR-VXT-350-S', 'category' => 'Kendaraan Agrikultur', 'summary' => 'Traktor roda empat (4x4) untuk pengolahan lahan di sektor pertanian.'),
    'https://viarmotor.com/commercials-vxt-300/'                           => array('name' => 'VIAR VXT 300', 'sku' => 'VIAR-VXT-300', 'category' => 'Kendaraan Agrikultur', 'summary' => 'Traktor roda tiga untuk pengolahan lahan di sektor pertanian.'),
    'https://viarmotor.com/commercials-vxt-350/'                           => array('name' => 'VIAR VXT 350', 'sku' => 'VIAR-VXT-350', 'category' => 'Kendaraan Agrikultur', 'summary' => 'Traktor dengan tenaga besar untuk pengolahan lahan di area sempit.'),
    'https://viarmotor.com/commercials-karya-r5k/'                        => array('name' => 'VIAR Karya R5K', 'sku' => 'VIAR-KARYA-R5K', 'category' => 'Kendaraan Agrikultur', 'summary' => 'Traktor pertanian R5K untuk pengolahan lahan dan aktivitas tani.'),
    'https://viarmotor.com/commercials-karya-r6k/'                        => array('name' => 'VIAR Karya R6K', 'sku' => 'VIAR-KARYA-R6K', 'category' => 'Kendaraan Agrikultur', 'summary' => 'Traktor R6K dengan fitur pertanian untuk kegiatan perkebunan dan kebun.'),
    'https://viarmotor.com/commercials-agri-r2k/'                          => array('name' => 'VIAR R2K', 'sku' => 'VIAR-R2K', 'category' => 'Kendaraan Agrikultur', 'summary' => 'Traktor R2K untuk hortikultura dan kegiatan kebun.'),
    'https://viarmotor.com/off-road-mini-trail-100/'                       => array('name' => 'VIAR Mini Trail 100', 'sku' => 'VIAR-MINI-TRAIL-100', 'category' => 'Motor Trail', 'summary' => 'Motor trail ringan 100 cc untuk perjalanan ringan di jalur nonaspal.'),
    'https://viarmotor.com/off-road-mini-trail-70/'                        => array('name' => 'VIAR Mini Trail 70', 'sku' => 'VIAR-MINI-TRAIL-70', 'category' => 'Motor Trail', 'summary' => 'Motor trail 70 cc untuk penggunaan ringan dengan harga terjangkau.'),
    'https://viarmotor.com/off-road-cross-xf-150/'                         => array('name' => 'VIAR Cross XF 150', 'sku' => 'VIAR-CROSS-XF-150', 'category' => 'Motor Trail', 'summary' => 'Motor trail 150 cc untuk kebutuhan medan nonaspal dan aktivitas luar ruang.'),
    'https://viarmotor.com/off-road-razor-110-ut/'                         => array('name' => 'VIAR Razor 110 UT', 'sku' => 'VIAR-RAZOR-110-UT', 'category' => 'ATV', 'summary' => 'ATV utilitas 110 cc untuk area kerja nonjalan raya.'),
    'https://viarmotor.com/off-road-razor-150ut/'                          => array('name' => 'VIAR Razor 150 UT', 'sku' => 'VIAR-RAZOR-150-UT', 'category' => 'ATV', 'summary' => 'ATV bermesin 150 cc dengan transmisi R-N-1-2-3 untuk rekreasi dan penggunaan area khusus.'),
    'https://viarmotor.com/off-road-razor-200rzm/'                         => array('name' => 'VIAR Razor 200 RZM', 'sku' => 'VIAR-RAZOR-200-RZM', 'category' => 'ATV', 'summary' => 'ATV performa 200 cc dengan suspensi trail untuk penggunaan yang lebih responsif.'),
    'https://viarmotor.com/subsidi-motor-listrik-viar-new-q1/'             => array('name' => 'VIAR New Q1', 'sku' => 'VIAR-NEW-Q1', 'category' => 'Motor Listrik', 'summary' => 'Skuter listrik untuk mobilitas harian dengan baterai lithium-ion dan jarak tempuh hingga 60 km.'),
    'https://viarmotor.com/motor-listrik-viar-ev1/'                        => array('name' => 'VIAR EV1', 'sku' => 'VIAR-EV1', 'category' => 'Motor Listrik', 'summary' => 'Motor listrik harian dengan desain ringkas dan baterai yang mudah diisi.'),
    'https://viarmotor.com/motor-listrik-viar-nx/'                         => array('name' => 'VIAR NX', 'sku' => 'VIAR-NX', 'category' => 'Motor Listrik', 'summary' => 'Skuter listrik modern untuk perjalanan perkotaan yang lebih tenang.'),
    'https://viarmotor.com/sepeda-listrik-roda-tiga-viar-er2/'             => array('name' => 'VIAR ER2', 'sku' => 'VIAR-ER2', 'category' => 'Sepeda Listrik', 'summary' => 'Sepeda listrik roda tiga dengan motor 650 W dan jarak tempuh hingga 50 km.'),
    'https://viarmotor.com/sepeda-listrik-roda-tiga-viar-er1-n/'           => array('name' => 'VIAR ER1N', 'sku' => 'VIAR-ER1N', 'category' => 'Sepeda Listrik', 'summary' => 'Sepeda listrik roda tiga untuk mobilitas jarak dekat dengan pijakan yang stabil.'),
    'https://viarmotor.com/sepeda-listrik-viar-vitron-o2/'                 => array('name' => 'VIAR Vitron O2', 'sku' => 'VIAR-VITRON-O2', 'category' => 'Sepeda Listrik', 'summary' => 'Sepeda listrik berdaya hingga 700 W dengan jarak tempuh hingga 60 km.'),
);

$extra_attributes = array(
    'VIAR-KARYA-150-BOX' => array('Karoseri' => 'Bak Box Aluminium Profile'),
);

function motoshop_import_content($summary, $source, $specs) {
    $detail = 'Spesifikasi di bawah ini diambil dari halaman produk resmi VIAR. '
        . 'Harga, pilihan warna, dan ketersediaan unit perlu dikonfirmasi kepada sales '
        . 'Cabang Viar Mahkota Ciledug sebelum melakukan transaksi.';
    $rows = '';
    if ($specs) {
        $rows = '<h3>Spesifikasi</h3><ul class="motoshop-spec-list">';
        foreach ($specs as $label => $value) {
            $rows .= '<li><span>' . esc_html($label) . '</span><strong>' . esc_html($value) . '</strong></li>';
        }
        $rows .= '</ul>';
    }

    return '<p>' . esc_html($summary) . '</p>'
        . '<p>' . esc_html($detail) . '</p>'
        . $rows
        . '<p class="motoshop-product-note"><strong>Catatan:</strong> spesifikasi dapat berubah '
        . 'mengikuti pembaruan produsen. Pembayaran, pemeriksaan dokumen kredit, dan serah terima '
        . 'kendaraan dilakukan langsung di cabang sesuai prosedur perusahaan.</p>'
        . '<p><a href="' . esc_url($source) . '" target="_blank" rel="noopener">Sumber spesifikasi resmi VIAR</a></p>';
}

$created = 0;
$updated = 0;
$skipped = 0;

foreach ($catalog as $entry) {
    $url = isset($entry['url']) ? $entry['url'] : '';
    if (!$url || !isset($map[$url]) || '' === $map[$url]['name']) {
        $skipped++;
        echo "Skipped (no mapping): {$url}\n";
        continue;
    }

    $meta = $map[$url];
    $specs = isset($entry['specs']) && is_array($entry['specs']) ? $entry['specs'] : array();
    foreach ($extra_attributes as $sku => $extra) {
        if ($meta['sku'] === $sku) {
            $specs = array_merge($specs, $extra);
        }
    }

    $term = term_exists($meta['category'], 'product_cat');
    if (!$term) {
        $term = wp_insert_term($meta['category'], 'product_cat');
    }
    if (is_wp_error($term)) {
        echo "Category error for {$meta['name']}: {$term->get_error_message()}\n";
        continue;
    }
    $term_id = is_array($term) ? (int) $term['term_id'] : (int) $term;

    $existing_id = wc_get_product_id_by_sku($meta['sku']);
    $is_new = !$existing_id;
    $product = $existing_id ? wc_get_product($existing_id) : new WC_Product_Simple();

    $product->set_name($meta['name']);
    $product->set_slug(sanitize_title($meta['name']));
    $product->set_sku($meta['sku']);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_featured(true);
    $product->set_short_description($meta['summary']);
    $product->set_description(motoshop_import_content($meta['summary'], $url, $specs));
    $product->set_category_ids(array($term_id));
    $product->set_stock_status('instock');
    $product->set_manage_stock(false);
    $product->set_regular_price('');
    $product->set_virtual(false);

    $attributes = array();
    $position = 0;
    foreach ($specs as $label => $value) {
        $attribute = new WC_Product_Attribute();
        $attribute->set_id(0);
        $attribute->set_name($label);
        $attribute->set_options(array($value));
        $attribute->set_position($position++);
        $attribute->set_visible(true);
        $attribute->set_variation(false);
        $attributes[] = $attribute;
    }
    $product->set_attributes($attributes);

    $product_id = $product->save();
    update_post_meta($product_id, '_motoshop_source_url', esc_url_raw($url));
    update_post_meta($product_id, '_motoshop_availability_note', 'Ketersediaan unit dikonfirmasi oleh sales cabang.');

    if (!get_post_thumbnail_id($product_id) && !empty($entry['image'])) {
        $image_id = media_sideload_image($entry['image'], $product_id, $meta['name'], 'id');
        if (is_wp_error($image_id)) {
            echo "Image warning for {$meta['name']}: {$image_id->get_error_message()}\n";
        } else {
            set_post_thumbnail($product_id, $image_id);
        }
    }

    if (!empty($entry['gallery']) && is_array($entry['gallery'])) {
        $gallery_ids = array();
        foreach ($entry['gallery'] as $gallery_url) {
            $gallery_id = media_sideload_image($gallery_url, $product_id, $meta['name'] . ' gallery', 'id');
            if (!is_wp_error($gallery_id)) {
                $gallery_ids[] = (int) $gallery_id;
            }
        }
        if ($gallery_ids) {
            update_post_meta($product_id, '_product_image_gallery', implode(',', $gallery_ids));
            update_post_meta($product_id, '_motoshop_feature_gallery', $gallery_ids);
        }
    }

    if ($is_new) {
        $created++;
        echo "Created: {$meta['name']} (#{$product_id})\n";
    } else {
        $updated++;
        echo "Updated: {$meta['name']} (#{$product_id})\n";
    }
}

echo "Import selesai. Dibuat: {$created}, diperbarui: {$updated}, dilewati: {$skipped}\n";
