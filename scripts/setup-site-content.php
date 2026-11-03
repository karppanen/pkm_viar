<?php
/**
 * Publish the branch pages, clean placeholder content, and set the site identity.
 *
 * Run: php wp-content/themes/motoshop/scripts/setup-site-content.php
 */

if ('cli' !== PHP_SAPI) {
    exit("CLI only.\n");
}

define('WP_USE_THEMES', false);
require_once dirname(__DIR__, 4) . '/wp-load.php';

$branch = motoshop_primary_branch();

function motoshop_upsert_page($slug, $title, $content, $menu_order = 0) {
    $existing = get_page_by_path($slug);
    $data = array(
        'post_title'     => $title,
        'post_name'      => $slug,
        'post_content'   => $content,
        'post_status'    => 'publish',
        'post_type'      => 'page',
        'post_author'    => 1,
        'menu_order'     => $menu_order,
        'comment_status' => 'closed',
    );
    if ($existing) {
        $data['ID'] = $existing->ID;
        wp_update_post($data);
        return (int) $existing->ID;
    }
    return (int) wp_insert_post($data);
}

$cabang_content = <<<HTML
<p>Website ini dikelola oleh {$branch['name']}, cabang resmi VIAR milik {$branch['company']}. Kantor cabang berada di {$branch['address']} dan melayani kawasan Pedurenan, Karang Tengah, Kota Tangerang serta wilayah sekitarnya.</p>

<h2>Alamat dan jam operasional</h2>
<table class="motoshop-spec-table">
    <tr><th>Alamat</th><td>{$branch['address']}</td></tr>
    <tr><th>Jam operasional</th><td>{$branch['hours']}</td></tr>
    <tr><th>WhatsApp dan telepon</th><td>{$branch['phone']}</td></tr>
    <tr><th>Layanan</th><td>Penjualan motor tunai dan kredit, servis berkala, serta suku cadang dan aksesori</td></tr>
</table>

<p><a class="button button--red" href="{$branch['maps']}" target="_blank" rel="noopener">Buka lokasi di Google Maps</a></p>

<h2>Layanan yang tersedia</h2>
<ul>
    <li><strong>Penjualan motor</strong> &mdash; motor konvensional, kendaraan niaga roda tiga dengan pilihan karoseri, ATV, dan sepeda listrik.</li>
    <li><strong>Simulasi kredit</strong> &mdash; beserta informasi tenor dan uang muka yang dapat dihitung bersama sales.</li>
    <li><strong>Servis</strong> &mdash; perawatan berkala di bengkel resmi.</li>
    <li><strong>Suku cadang dan aksesori</strong> &mdash; kebutuhan perawatan serta operasional kendaraan.</li>
</ul>

<h2>Alur pembelian di cabang</h2>
<ol>
    <li>Calon pelanggan memilih unit melalui katalog website atau berkonsultasi melalui WhatsApp.</li>
    <li>Sales mengonfirmasi ketersediaan unit, harga terbaru, dan pilihan pembelian tunai atau kredit.</li>
    <li>Calon pelanggan dapat menyepakati jadwal kunjungan ke cabang sesuai janji yang disepakati.</li>
    <li>Pemeriksaan dokumen, pembayaran, dan serah terima kendaraan dilakukan sesuai prosedur perusahaan.</li>
</ol>

<p>Permintaan yang dikirim melalui formulir website langsung tercatat pada dashboard MotoShop sehingga dapat ditindaklanjuti oleh tim sales cabang.</p>
HTML;

$cabang_content = str_replace('surrounding Transaction', 'wilayah sekitarnya', $cabang_content);

$layanan_content = <<<HTML
<p>Cabang Ciledug tidak berhenti pada proses penjualan. Setiap unit yang dijual selalu mendapat dukungan servis dan suku cadang agar kendaraan tetap siap digunakan.</p>

<h2>Sales</h2>
<p>Konsultasi unit sesuai kebutuhan harian atau operasional usaha, cek ketersediaan unit, simulasi pembelian kredit, serta penjelasan requisitos dokumen.</p>

<h2>Service</h2>
<p>Perawatan berkala di bengkel resmi dengan suku cadang asli, termasuk pengecekan mesin, rem, dan transmisi.</p>

<h2>Sparepart</h2>
<p>Suku cadang dan aksesori untuk kebutuhan perawatan maupun operasional kendaraan.</p>

<h2>Catatan untuk calon pelanggan</h2>
<ul>
    <li>Website hanya berfungsi sebagai katalog dan pencatat permintaan. Tidak ada pembayaran kendaraan secara online.</li>
    <li>Harga final dan ketersediaan unit selalu dikonfirmasi oleh sales cabang.</li>
    <li>Pemeriksaan dokumen kredit dan penandatanganan berkas dilakukan langsung di cabang.</li>
</ul>
HTML;

$layanan_content = str_replace('configuration_launched', 'dijual', $layanan_content);

$privasi_content = <<<HTML
<p>Website Cabang Viar Mahkota Ciledug hanya mengumpulkan data seperlunya untuk menindaklanjuti permintaan calon pelanggan. Kebijakan ini menjelaskan data yang dikumpulkan, tujuan penggunaan, dan hak pengguna.</p>

<h2>Data yang dikumpulkan</h2>
<ul>
    <li>Nama lengkap calon pelanggan.</li>
    <li>Nomor WhatsApp untuk kebutuhan komunikasi dan tindak lanjut.</li>
    <li>Produk atau kendaraan yang diminati.</li>
    <li>Pilihan pembelian tunai atau kredit.</li>
    <li>Cabang yang dipilih dan preferensi jadwal kunjungan.</li>
    <li>Catatan tambahan yang diketik sendiri oleh calon pelanggan.</li>
</ul>

<h2>Tujuan penggunaan data</h2>
<p>Data digunakan untuk menghubungi calon pelanggan, mengecek ketersediaan unit, menyusun simulasi kredit, menyiapkan jadwal kunjungan, dan mencatat proses penjualan sesuai ketentuan perusahaan.</p>

<h2>Batasan pengumpulan data</h2>
<p>Formulir website tidak meminta dokumen identitas, slip gaji, nomor rekening, atau dokumen kredit lainnya. Pemeriksaan dokumen tersebut dilakukan oleh mitra atau perusahaan pembiayaan sesuai prosedur yang berlaku.</p>

<h2>Akses dan penyimpanan data</h2>
<p>Data permintaan hanya dapat diakses oleh administrator website serta staf sales dan operator yang telah ditunjuk. Data disimpan pada server website dan tidak dibagikan kepada pihak ketiga tanpa persetujuan calon pelanggan.</p>

<h2>Permintaan penghapusan data</h2>
<p>Calon pelanggan dapat meminta penghapusan data melalui WhatsApp pada nomor sales cabang. Penghapusan akan dilakukan apabila data tersebut tidak diperlukan untuk kewajiban penyimpanan dokumen penjualan.</p>

<h2>Perubahan kebijakan</h2>
<p>Kebijakan ini dapat diperbarui apabila terdapat perubahan pada website atau prosedur perusahaan. Versi terbaru selalu tersedia pada halaman ini.</p>
HTML;

$privasi_content = str_replace('cabal', 'pada', $privasi_content);

$page_ids = array(
    'cabang-ciledug'    => motoshop_upsert_page('cabang-ciledug', 'Cabang Ciledug', $cabang_content, 1),
    'layanan'           => motoshop_upsert_page('layanan', 'Layanan', $layanan_content, 2),
    'kebijakan-privasi' => motoshop_upsert_page('kebijakan-privasi', 'Kebijakan Privasi', $privasi_content, 3),
);

echo "Pages siap:\n";
foreach ($page_ids as $slug => $id) {
    echo " - {$slug} (#{$id})\n";
}

// Remove placeholder content shipped with WordPress.
foreach (array('sample-page') as $slug) {
    $page = get_page_by_path($slug);
    if ($page && 'trash' !== $page->post_status) {
        wp_delete_post($page->ID, true);
        echo "Hapus halaman contoh: {$slug}\n";
    }
}

// WooCommerce system pages stay available so the login redirect and the
// account page work. The theme redirects cart and checkout back to the shop.
foreach (array('keranjang', 'checkout', 'akun-saya') as $slug) {
    $page = get_page_by_path($slug);
    if ($page && 'publish' !== $page->post_status) {
        wp_update_post(array('ID' => $page->ID, 'post_status' => 'publish'));
        echo "Halaman WooCommerce diaktifkan: {$slug}\n";
    }
}

// Site identity.
update_option('blogname', 'Viar Mahkota Ciledug');
update_option('blogdescription', 'Katalog dan layanan pembelian motor VIAR di Cabang Ciledug, Tangerang');
update_option('timezone_string', 'Asia/Jakarta');

echo "Identitas situs diperbarui.\n";
