<?php
/**
 * Seed a starter VIAR catalog from current official product pages.
 *
 * Run: php wp-content/themes/motoshop/scripts/seed-catalog.php
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

$products = array(
    array(
        'sku' => 'VIAR-KARYA-200-LN',
        'name' => 'VIAR Karya 200 Ln',
        'category' => 'Motor Niaga',
        'summary' => 'Motor niaga roda tiga dengan bak panjang untuk distribusi, angkutan barang, dan kebutuhan usaha.',
        'description' => 'VIAR Karya 200 Ln dirancang sebagai kendaraan operasional serbaguna. Bak berukuran panjang, mesin 200 cc berpendingin radiator, dan transmisi dengan gigi mundur membantu aktivitas distribusi maupun usaha harian.',
        'source' => 'https://viarmotor.com/commercials-new-karya-200n-l/',
        'image' => 'https://viarmotor.com/wp-content/uploads/2026/10/2-Head-Banner-KARYA-200Ln-ba.jpg',
        'specs' => array(
            'Tipe Mesin' => '4 Langkah OHV',
            'Volume Langkah' => '200 cc',
            'Sistem Pendingin' => 'Water Cooler (Radiator)',
            'Daya Maksimum' => '10 kW / 10.000 rpm',
            'Transmisi' => 'N-1-2-3-4-5 + Reverse Gear',
            'Kapasitas Bensin' => '16,5 liter',
            'Daya Angkut Maksimal' => '700 kg',
            'Dimensi Unit' => '3540 x 1255 x 1344 mm',
            'Dimensi Bak' => '2007 x 1255 x 370 mm',
        ),
    ),
    array(
        'sku' => 'VIAR-KARYA-150-RLN',
        'name' => 'VIAR Karya 150 RLn',
        'category' => 'Motor Niaga',
        'summary' => 'Motor roda tiga untuk operasional usaha dengan bak angkut dan mesin 150 cc berpendingin radiator.',
        'description' => 'VIAR Karya 150 RLn cocok untuk usaha yang membutuhkan kendaraan angkut ringkas. Unit dilengkapi bak terbuka, suspensi belakang pegas daun dengan peredam hidrolik, serta gigi mundur.',
        'source' => 'https://viarmotor.com/commercials-new-karya-150l/',
        'image' => 'https://viarmotor.com/wp-content/uploads/2026/10/2-Head-Banner-KARYA-150RLn-2.jpg',
        'specs' => array(
            'Tipe Mesin' => '4 Langkah OHV, Water Cooler',
            'Volume Langkah' => '150 cc',
            'Daya Maksimum' => '7,5 kW / 7.500 rpm',
            'Transmisi' => 'N-1-2-3-4-5 + Reverse Gear',
            'Kapasitas Bensin' => '16,5 liter',
            'Daya Angkut Maksimal' => '449 kg',
            'Dimensi Unit' => '3300 x 1250 x 1350 mm',
            'Dimensi Bak' => '1800 x 1250 x 375 mm',
        ),
    ),
    array(
        'sku' => 'VIAR-NEW-Q1L',
        'name' => 'VIAR New Q1L',
        'category' => 'Motor Listrik',
        'summary' => 'Skuter listrik untuk mobilitas harian dengan baterai lithium-ion dan jarak tempuh hingga 60 km.',
        'description' => 'VIAR New Q1L merupakan skuter listrik praktis untuk perjalanan perkotaan. Pengisian baterai dapat dilakukan menggunakan sumber listrik AC 180-240 volt dan sistem pengereman menggunakan cakram hidrolik depan-belakang.',
        'source' => 'https://viarmotor.com/subsidi-motor-listrik-viar-new-q1/',
        'image' => 'https://viarmotor.com/wp-content/uploads/2025/09/New-Q1L-1-scaled.jpg',
        'specs' => array(
            'Power Motor' => '800 W - maksimum 2000 W',
            'Baterai' => 'Lithium-Ion 60V 23,4 Ah',
            'Kecepatan Maksimum' => '60 km/jam',
            'Jarak Tempuh' => 'Hingga 60 km',
            'Waktu Pengisian' => '4-5 jam',
            'Daya Angkut' => '150 kg',
            'Rem' => 'Cakram hidrolik depan dan belakang',
            'Dimensi' => '1680 x 690 x 1220 mm',
        ),
    ),
    array(
        'sku' => 'VIAR-ER2',
        'legacy_sku' => 'VIAR-AKASHA-3',
        'name' => 'VIAR ER2',
        'category' => 'Sepeda Listrik',
        'summary' => 'Sepeda listrik roda tiga dengan motor 650 W, ruang penyimpanan, dan jarak tempuh hingga 50 km.',
        'description' => 'VIAR ER2 merupakan sepeda listrik roda tiga untuk mobilitas jarak dekat. Konfigurasi tiga roda memberi pijakan yang stabil, sedangkan suspensi teleskopik dan peredam kejut ganda membantu kenyamanan penggunaan harian.',
        'source' => 'https://viarmotor.com/sepeda-listrik-roda-tiga-viar-er2/',
        'image' => 'https://viarmotor.com/wp-content/uploads/2026/01/ER2.png',
        'specs' => array(
            'Power Motor' => '650 W - maksimum 1600 W',
            'Baterai' => '60V 20Ah VRLA',
            'Kecepatan Maksimum' => '25 km/jam',
            'Jarak Tempuh' => 'Hingga 50 km',
            'Suspensi Depan' => 'Teleskopik',
            'Suspensi Belakang' => 'Peredam Kejut Ayun Ganda',
            'Ban' => '3.00-10 Tubeless',
            'Dimensi' => '2150 x 930 x 1720 mm',
            'Pilihan Warna' => 'Krem dan Hitam',
        ),
    ),
    array(
        'sku' => 'VIAR-CROSS-XF-150',
        'name' => 'VIAR Cross XF 150',
        'category' => 'Motor Trail',
        'summary' => 'Motor trail 150 cc untuk kebutuhan medan nonaspal dan aktivitas luar ruang.',
        'description' => 'VIAR Cross XF 150 menggunakan mesin 4 langkah SOHC, suspensi depan upside down, dan monoshock di belakang. Sistem pengereman cakram depan-belakang mendukung penggunaan pada berbagai kondisi medan.',
        'source' => 'https://viarmotor.com/off-road-cross-xf-150/',
        'image' => 'https://viarmotor.com/wp-content/uploads/2026/09/CrossXF-150-Sept2026-rev.jpg',
        'specs' => array(
            'Tipe Mesin' => '4 Langkah, SOHC',
            'Volume Langkah' => '149 cc',
            'Daya Maksimum' => '9,5 kW / 9.500 rpm',
            'Torsi Maksimum' => '9,8 Nm / 7.000 rpm',
            'Transmisi' => '1-N-2-3-4-5',
            'Kapasitas Tangki' => '5,5 liter',
            'Berat Kosong' => '104 kg',
            'Dimensi' => '1980 x 880 x 1305 mm',
        ),
    ),
    array(
        'sku' => 'VIAR-RAZOR-150-UT',
        'name' => 'VIAR Razor 150 UT',
        'category' => 'ATV',
        'summary' => 'ATV bermesin 150 cc dengan transmisi R-N-1-2-3 untuk rekreasi dan penggunaan area khusus.',
        'description' => 'VIAR Razor 150 UT adalah ATV utilitas dengan mesin satu silinder 4 langkah SOHC. Rem cakram depan-belakang, ban tapak lebar, dan pilihan gigi mundur mendukung manuver di area nonjalan raya.',
        'source' => 'https://viarmotor.com/off-road-razor-150ut/',
        'image' => 'https://viarmotor.com/wp-content/uploads/2023/07/Razor-150UT-Banner-header-scaled.jpg',
        'specs' => array(
            'Tipe Mesin' => 'Single Cylinder, 4 Stroke, SOHC',
            'Volume Langkah' => '150 cc',
            'Daya Maksimum' => '9,1 kW / 7.500 rpm',
            'Torsi Maksimum' => '10 Nm / 6.000 rpm',
            'Transmisi' => 'R-N-1-2-3',
            'Kapasitas Tangki' => '6 liter',
            'Berat Kosong' => '147,5 kg',
            'Dimensi' => '1650 x 880 x 960 mm',
        ),
    ),
    array(
        'sku' => 'VIAR-A2',
        'name' => 'VIAR A2',
        'category' => 'Sepeda Listrik',
        'summary' => 'Sepeda listrik ringkas dengan motor 500 W dan jarak tempuh hingga 60 km untuk mobilitas harian.',
        'description' => 'VIAR A2 dirancang untuk perjalanan jarak dekat dengan posisi berkendara praktis, baterai lead acid 48V 20Ah, dan ban tubeless. Unit ini cocok untuk mobilitas lingkungan perumahan maupun aktivitas harian ringan.',
        'source' => 'https://viarmotor.com/sepeda-listrik-viar-a2/',
        'image' => 'https://motor-viar.com/wp-content/uploads/2024/03/A2-White.jpg',
        'specs' => array(
            'Power Motor' => '500 W BLDC',
            'Kecepatan Maksimum' => '25 km/jam',
            'Jarak Tempuh' => 'Hingga 60 km',
            'Baterai' => 'Lead Acid 48V 20Ah',
            'Usia Baterai' => '600-800 siklus',
            'Suspensi Depan' => 'Teleskopik',
            'Suspensi Belakang' => 'Peredam Kejut Ganda',
            'Ban' => '2.50-10 Tubeless',
            'Dimensi' => '1550 x 660 x 1040 mm',
        ),
    ),
    array(
        'sku' => 'VIAR-KARYA-300',
        'name' => 'VIAR Karya 300',
        'category' => 'Motor Niaga',
        'summary' => 'Motor niaga roda tiga berkapasitas 270 cc dengan bak panjang dan daya angkut hingga 852 kg.',
        'description' => 'VIAR Karya 300 ditujukan untuk kebutuhan distribusi dengan muatan lebih besar. Mesin berpendingin air, bak panjang, suspensi belakang pegas daun, dan gigi mundur mendukung operasional usaha.',
        'source' => 'https://viarmotor.com/commercials-new-karya-300/',
        'image' => 'https://viarmotor.com/wp-content/uploads/2026/10/3-Head-Banner-KARYA-300Nn.jpg',
        'specs' => array(
            'Tipe Mesin' => '4 Langkah OHV',
            'Volume Langkah' => '270 cc',
            'Sistem Pendingin' => 'Water Cooler',
            'Daya Maksimum' => '12,8 kW / 7.000 rpm',
            'Transmisi' => 'N-1-2-3-4-5 + Reverse Gear',
            'Kapasitas Bensin' => '16,5 liter',
            'Daya Angkut Maksimal' => '852 kg',
            'Dimensi Unit' => '3530 x 1250 x 1350 mm',
            'Dimensi Bak' => '2000 x 1252 x 375 mm',
        ),
    ),
    array(
        'sku' => 'VIAR-KARYA-E5',
        'name' => 'VIAR Karya E5',
        'category' => 'Motor Niaga',
        'summary' => 'Kendaraan niaga roda tiga bertenaga listrik dengan daya angkut hingga 442 kg.',
        'description' => 'VIAR Karya E5 menghadirkan kendaraan angkut listrik untuk operasional usaha. Motor listrik, bak terbuka, rangka penuh, dan kecepatan maksimum 41 km/jam membuatnya sesuai untuk distribusi jarak dekat.',
        'source' => 'https://viarmotor.com/commercials-karya-e5/',
        'image' => 'https://viarmotor.com/wp-content/uploads/2026/08/Karya-Listrik-2.jpg',
        'specs' => array(
            'Power Motor' => '2500 W - maksimum 4305 W',
            'Torsi Maksimum' => '448 Nm / 13 rpm',
            'Kecepatan Maksimum' => '41 km/jam',
            'Jarak Tempuh' => 'Hingga 50 km',
            'Baterai' => '72V 58Ah VRLA',
            'Daya Angkut Maksimal' => '442 kg',
            'Berat Kosong' => '362 kg',
            'Dimensi Unit' => '3010 x 1190 x 1370 mm',
            'Dimensi Bak' => '1500 x 1190 x 370 mm',
        ),
    ),
    array(
        'sku' => 'VIAR-VITRON-O2',
        'name' => 'VIAR Vitron O2',
        'category' => 'Sepeda Listrik',
        'summary' => 'Sepeda listrik berdaya hingga 700 W dengan jarak tempuh hingga 60 km.',
        'description' => 'VIAR Vitron O2 merupakan sepeda listrik praktis untuk mobilitas harian. Baterai 48V 12Ah, suspensi teleskopik, peredam kejut ganda, dan ban tubeless mendukung penggunaan yang nyaman.',
        'source' => 'https://viarmotor.com/sepeda-listrik-viar-vitron-o2/',
        'image' => 'https://viarmotor.com/wp-content/uploads/2025/01/E-Bike-Banner-header-Vitron-scaled.jpg',
        'specs' => array(
            'Power Motor' => '350 W - maksimum 700 W',
            'Kecepatan Maksimum' => '25 km/jam',
            'Jarak Tempuh' => 'Hingga 60 km',
            'Baterai' => 'Lead Acid 48V 12Ah',
            'Waktu Pengisian' => '5-6 jam',
            'Suspensi Depan' => 'Teleskopik',
            'Suspensi Belakang' => 'Peredam Kejut Ganda',
            'Ban' => '60/90-10 Tubeless',
            'Dimensi' => '1580 x 650 x 1070 mm',
        ),
    ),
);

function motoshop_seed_content($item) {
    return '<p>' . esc_html($item['description']) . '</p>'
        . '<p class="motoshop-product-note"><strong>Catatan:</strong> Harga, warna, stok, dan jarak tempuh aktual perlu dikonfirmasi kepada sales cabang. Spesifikasi dapat berubah mengikuti pembaruan produsen.</p>'
        . '<p><a href="' . esc_url($item['source']) . '" target="_blank" rel="noopener">Sumber spesifikasi resmi VIAR</a></p>';
}

foreach ($products as $item) {
    $existing_id = wc_get_product_id_by_sku($item['sku']);
    if (!$existing_id && !empty($item['legacy_sku'])) {
        $existing_id = wc_get_product_id_by_sku($item['legacy_sku']);
    }
    $product = $existing_id ? wc_get_product($existing_id) : new WC_Product_Simple();

    $term = term_exists($item['category'], 'product_cat');
    if (!$term) {
        $term = wp_insert_term($item['category'], 'product_cat');
    }
    if (is_wp_error($term)) {
        echo "Category error for {$item['name']}: {$term->get_error_message()}\n";
        continue;
    }
    $term_id = is_array($term) ? (int) $term['term_id'] : (int) $term;

    $product->set_name($item['name']);
    $product->set_slug(sanitize_title($item['name']));
    $product->set_sku($item['sku']);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_featured(true);
    $product->set_short_description($item['summary']);
    $product->set_description(motoshop_seed_content($item));
    $product->set_category_ids(array($term_id));
    $product->set_stock_status('instock');
    $product->set_manage_stock(false);
    $product->set_regular_price('');

    $attributes = array();
    $position = 0;
    foreach ($item['specs'] as $label => $value) {
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
    update_post_meta($product_id, '_motoshop_source_url', esc_url_raw($item['source']));

    if (!get_post_thumbnail_id($product_id)) {
        $image_id = media_sideload_image($item['image'], $product_id, $item['name'], 'id');
        if (is_wp_error($image_id)) {
            echo "Image warning for {$item['name']}: {$image_id->get_error_message()}\n";
        } else {
            set_post_thumbnail($product_id, $image_id);
        }
    }

    echo ($existing_id ? 'Updated' : 'Created') . ": {$item['name']} (#{$product_id})\n";
}

echo "Catalog seed complete.\n";
