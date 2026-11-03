<?php
/**
 * Export the live catalogue as Markdown for the programme evidence folder.
 *
 * Run: php wp-content/themes/motoshop/scripts/export-product-list.php "D:\path\evidence"
 */

if ('cli' !== PHP_SAPI) {
    exit("CLI only.\n");
}

define('WP_USE_THEMES', false);
require_once dirname(__DIR__, 4) . '/wp-load.php';

$target = isset($argv[1]) ? rtrim($argv[1], "\\/") : getcwd();
if (!is_dir($target)) {
    exit("Target folder not found: {$target}\n");
}

$products = wc_get_products(array('status' => 'publish', 'limit' => -1, 'orderby' => 'name', 'order' => 'ASC'));
$categories = array();
foreach (get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false)) as $term) {
    $categories[$term->slug] = $term->name;
}

$lines = array();
$lines[] = '# Daftar Produk Website Cabang Viar Mahkota Ciledug';
$lines[] = '';
$lines[] = 'Data diekspor otomatis dari WooCommerce pada ' . wp_date('d/m/Y H:i') . '.';
$lines[] = 'Total produk terbit: ' . count($products) . ' unit.';
$lines[] = '';
$lines[] = 'Spesifikasi diambil dari halaman produk resmi VIAR. Harga sengaja dikosongkan dan';
$lines[] = 'ditampilkan sebagai "Hubungi sales" karena harga dapat berbeda antar cabang dan';
$lines[] = 'berubah mengikuti promo. Ketersediaan unit dikonfirmasi langsung oleh sales cabang.';
$lines[] = '';
$lines[] = '| No | Nama Kendaraan | Kategori | Harga | Status Stok | Gambar Utama |';
$lines[] = '| --- | --- | --- | --- | --- | --- |';

$number = 0;
foreach ($products as $product) {
    $number++;
    $terms = get_the_terms($product->get_id(), 'product_cat');
    $category_names = array();
    if ($terms && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $category_names[] = $term->name;
        }
    }
    $thumbnail_id = get_post_thumbnail_id($product->get_id());
    $image = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'thumbnail') : '';
    $lines[] = sprintf(
        '| %d | %s | %s | %s | %s | %s |',
        $number,
        $product->get_name(),
        $category_names ? implode(', ', $category_names) : '-',
        $product->get_regular_price() ? $product->get_regular_price() : 'Hubungi sales',
        $product->get_stock_status(),
        $image ? 'Tersedia' : 'Belum ada'
    );
}

$lines[] = '';
$lines[] = '## Ringkasan per kategori';
$lines[] = '';
$lines[] = '| Kategori | Jumlah Unit |';
$lines[] = '| --- | --- |';
foreach ($categories as $slug => $name) {
    $count = count(wc_get_products(array('status' => 'publish', 'limit' => -1, 'category' => array($slug))));
    if ($count > 0) {
        $lines[] = sprintf('| %s | %d |', $name, $count);
    }
}

$lines[] = '';
$lines[] = '## Cara memperbarui data produk';
$lines[] = '';
$lines[] = '1. Masuk ke dashboard WordPress, lalu buka menu **MotoShop > Semua Produk**.';
$lines[] = '2. Pilih produk yang akan diperbarui.';
$lines[] = '3. Periksa nama, kategori, gambar utama, galeri, deskripsi, dan tab Atribut untuk spesifikasi.';
$lines[] = '4. Pastikan kolom harga tetap kosong sehingga tampil "Hubungi sales".';
$lines[] = '5. Klik **Perbarui**, lalu periksa tampilan halaman produk dari sisi pengunjung.';

$file = $target . DIRECTORY_SEPARATOR . 'Daftar Produk MotoShop.md';
file_put_contents($file, implode(PHP_EOL, $lines) . PHP_EOL);

echo "Daftar produk ditulis: {$file}\n";
