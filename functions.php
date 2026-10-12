<?php
/**
 * MotoShop theme setup and dealership enquiry workflow.
 *
 * @package MotoShop
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MOTOSHOP_VERSION', '1.2.0');

function motoshop_setup() {
    load_theme_textdomain('motoshop', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array('height' => 72, 'width' => 240, 'flex-height' => true, 'flex-width' => true));
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus(array(
        'primary' => __('Menu utama', 'motoshop'),
        'footer'  => __('Menu footer', 'motoshop'),
    ));
}
add_action('after_setup_theme', 'motoshop_setup');

function motoshop_assets() {
    wp_enqueue_style('motoshop-style', get_stylesheet_uri(), array(), MOTOSHOP_VERSION);
    wp_enqueue_style('motoshop-main', get_template_directory_uri() . '/assets/css/main.css', array('motoshop-style'), MOTOSHOP_VERSION);
    wp_enqueue_script('motoshop-main', get_template_directory_uri() . '/assets/js/main.js', array(), MOTOSHOP_VERSION, true);
}
add_action('wp_enqueue_scripts', 'motoshop_assets');

function motoshop_fallback_menu() {
    $items = array(
        home_url('/')         => __('Beranda', 'motoshop'),
        home_url('/shop/')    => __('Motor', 'motoshop'),
        home_url('/#layanan') => __('Layanan', 'motoshop'),
        home_url('/#tentang') => __('Tentang Kami', 'motoshop'),
        home_url('/artikel/') => __('Artikel', 'motoshop'),
        home_url('/#kontak')  => __('Kontak', 'motoshop'),
    );

    echo '<ul class="site-menu">';
    foreach ($items as $url => $label) {
        printf('<li><a href="%s">%s</a></li>', esc_url($url), esc_html($label));
    }
    echo '</ul>';
}

function motoshop_branches() {
    return array(
        'Kebon Jeruk' => 'Jl. Panjang No. 12, Kebon Jeruk, Jakarta Barat',
        'Depok'       => 'Jl. Arif Rahman Hakim, Kemiri Muka, Beji, Depok',
        'Karawaci'    => 'Jl. Imam Bonjol No. A5, Sukajadi, Karawaci',
        'Serang'      => 'Jl. Raya Cilegon Drangong No. 78, Serang',
        'Balaraja'    => 'Jl. Raya Serang KM 24, Kosambi, Balaraja',
    );
}

function motoshop_register_leads() {
    register_post_type('motoshop_lead', array(
        'labels' => array(
            'name'          => __('Permintaan Pembelian', 'motoshop'),
            'singular_name' => __('Permintaan Pembelian', 'motoshop'),
            'menu_name'     => __('Permintaan Motor', 'motoshop'),
            'all_items'     => __('Semua Permintaan', 'motoshop'),
            'edit_item'     => __('Detail Permintaan', 'motoshop'),
        ),
        'public'         => false,
        'show_ui'        => true,
        'show_in_menu'   => 'motoshop-dashboard',
        'menu_icon'      => 'dashicons-clipboard',
        'supports'       => array('title'),
        'capability_type' => 'post',
        'map_meta_cap'   => true,
    ));
}
add_action('init', 'motoshop_register_leads');

function motoshop_handle_lead() {
    if (!isset($_POST['motoshop_lead_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['motoshop_lead_nonce'])), 'motoshop_submit_lead')) {
        wp_die(esc_html__('Permintaan tidak dapat diverifikasi. Silakan muat ulang halaman.', 'motoshop'));
    }

    $name    = isset($_POST['customer_name']) ? sanitize_text_field(wp_unslash($_POST['customer_name'])) : '';
    $phone   = isset($_POST['customer_phone']) ? sanitize_text_field(wp_unslash($_POST['customer_phone'])) : '';
    $email   = isset($_POST['customer_email']) ? sanitize_email(wp_unslash($_POST['customer_email'])) : '';
    $product = isset($_POST['product_name']) ? sanitize_text_field(wp_unslash($_POST['product_name'])) : '';
    $method  = isset($_POST['purchase_method']) ? sanitize_key(wp_unslash($_POST['purchase_method'])) : 'tunai';
    $branch  = isset($_POST['branch']) ? sanitize_text_field(wp_unslash($_POST['branch'])) : '';
    $message = isset($_POST['customer_message']) ? sanitize_textarea_field(wp_unslash($_POST['customer_message'])) : '';

    if (!$name || !$phone || !$product || !array_key_exists($branch, motoshop_branches())) {
        $back = wp_get_referer() ? wp_get_referer() : home_url('/');
        wp_safe_redirect(add_query_arg('request', 'invalid', $back) . '#request-form');
        exit;
    }

    if (!in_array($method, array('tunai', 'kredit'), true)) {
        $method = 'tunai';
    }

    $lead_id = wp_insert_post(array(
        'post_type'   => 'motoshop_lead',
        'post_status' => 'publish',
        'post_title'  => sprintf('%s - %s', $name, $product),
    ));

    if (is_wp_error($lead_id)) {
        wp_die(esc_html__('Permintaan belum tersimpan. Silakan coba kembali.', 'motoshop'));
    }

    $fields = array(
        '_motoshop_name'    => $name,
        '_motoshop_phone'   => $phone,
        '_motoshop_email'   => $email,
        '_motoshop_product' => $product,
        '_motoshop_method'  => $method,
        '_motoshop_branch'  => $branch,
        '_motoshop_message' => $message,
        '_motoshop_status'  => 'Baru',
    );
    foreach ($fields as $key => $value) {
        update_post_meta($lead_id, $key, $value);
    }

    $back = wp_get_referer() ? wp_get_referer() : home_url('/');
    wp_safe_redirect(add_query_arg('request', 'sent', $back) . '#request-form');
    exit;
}
add_action('admin_post_nopriv_motoshop_submit_lead', 'motoshop_handle_lead');
add_action('admin_post_motoshop_submit_lead', 'motoshop_handle_lead');

function motoshop_lead_columns($columns) {
    return array(
        'cb'     => $columns['cb'],
        'title'  => __('Pelanggan / Motor', 'motoshop'),
        'phone'  => __('WhatsApp', 'motoshop'),
        'method' => __('Metode', 'motoshop'),
        'branch' => __('Cabang', 'motoshop'),
        'status' => __('Status', 'motoshop'),
        'date'   => __('Tanggal', 'motoshop'),
    );
}
add_filter('manage_motoshop_lead_posts_columns', 'motoshop_lead_columns');

function motoshop_lead_column_content($column, $post_id) {
    $map = array(
        'phone'  => '_motoshop_phone',
        'method' => '_motoshop_method',
        'branch' => '_motoshop_branch',
        'status' => '_motoshop_status',
    );
    if (isset($map[$column])) {
        $value = get_post_meta($post_id, $map[$column], true);
        if ('phone' === $column && $value) {
            $whatsapp = preg_replace('/\D+/', '', $value);
            if (str_starts_with($whatsapp, '0')) {
                $whatsapp = '62' . substr($whatsapp, 1);
            }
            printf('<a href="https://wa.me/%s" target="_blank" rel="noopener">%s</a>', esc_attr($whatsapp), esc_html($value));
            return;
        }
        echo esc_html($value);
    }
}
add_action('manage_motoshop_lead_posts_custom_column', 'motoshop_lead_column_content', 10, 2);

function motoshop_lead_metaboxes() {
    add_meta_box('motoshop-lead-details', __('Detail calon pelanggan', 'motoshop'), 'motoshop_lead_metabox', 'motoshop_lead', 'normal', 'high');
}
add_action('add_meta_boxes', 'motoshop_lead_metaboxes');

function motoshop_lead_metabox($post) {
    $labels = array(
        '_motoshop_name'    => __('Nama', 'motoshop'),
        '_motoshop_phone'   => __('WhatsApp', 'motoshop'),
        '_motoshop_email'   => __('Email', 'motoshop'),
        '_motoshop_product' => __('Motor diminati', 'motoshop'),
        '_motoshop_method'  => __('Metode pembelian', 'motoshop'),
        '_motoshop_branch'  => __('Cabang', 'motoshop'),
        '_motoshop_message' => __('Catatan', 'motoshop'),
    );
    wp_nonce_field('motoshop_save_lead_status', 'motoshop_lead_status_nonce');
    echo '<table class="widefat striped"><tbody>';
    foreach ($labels as $key => $label) {
        printf('<tr><th style="width:180px">%s</th><td>%s</td></tr>', esc_html($label), nl2br(esc_html(get_post_meta($post->ID, $key, true))));
    }
    $current_status = get_post_meta($post->ID, '_motoshop_status', true) ?: 'Baru';
    $statuses = array('Baru', 'Dihubungi', 'Janji Kunjungan', 'Selesai', 'Batal');
    echo '<tr><th>' . esc_html__('Status tindak lanjut', 'motoshop') . '</th><td><select name="motoshop_lead_status">';
    foreach ($statuses as $status) {
        printf('<option value="%s" %s>%s</option>', esc_attr($status), selected($current_status, $status, false), esc_html($status));
    }
    echo '</select><p class="description">' . esc_html__('Perbarui status setelah sales menghubungi calon pelanggan.', 'motoshop') . '</p></td></tr>';
    echo '</tbody></table>';
}

function motoshop_save_lead_status($post_id) {
    if (!isset($_POST['motoshop_lead_status_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['motoshop_lead_status_nonce'])), 'motoshop_save_lead_status')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id) || !isset($_POST['motoshop_lead_status'])) {
        return;
    }

    $status = sanitize_text_field(wp_unslash($_POST['motoshop_lead_status']));
    $allowed = array('Baru', 'Dihubungi', 'Janji Kunjungan', 'Selesai', 'Batal');
    if (in_array($status, $allowed, true)) {
        update_post_meta($post_id, '_motoshop_status', $status);
    }
}
add_action('save_post_motoshop_lead', 'motoshop_save_lead_status');

function motoshop_product_choices() {
    $choices = array();
    if (function_exists('wc_get_products')) {
        $products = wc_get_products(array('status' => 'publish', 'limit' => 40, 'orderby' => 'name', 'order' => 'ASC'));
        foreach ($products as $product) {
            $choices[] = $product->get_name();
        }
    }
    return $choices;
}

function motoshop_request_form($product_name = '') {
    $request_state = isset($_GET['request']) ? sanitize_key(wp_unslash($_GET['request'])) : '';
    $choices = motoshop_product_choices();
    ob_start();
    ?>
    <div class="request-shell" id="request-form">
        <div class="request-copy">
            <p class="eyebrow"><?php esc_html_e('Konsultasi pembelian', 'motoshop'); ?></p>
            <h2><?php esc_html_e('Ceritakan motor yang Anda cari.', 'motoshop'); ?></h2>
            <p><?php esc_html_e('Tim cabang akan menghubungi Anda untuk mengecek unit, harga terbaru, pilihan kredit, dan jadwal kunjungan.', 'motoshop'); ?></p>
            <p class="request-contact"><span><?php esc_html_e('Butuh cepat?', 'motoshop'); ?></span> <a href="https://wa.me/6285591220003" target="_blank" rel="noopener">0855 9122 0003</a></p>
        </div>
        <form class="request-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <input type="hidden" name="action" value="motoshop_submit_lead">
            <?php wp_nonce_field('motoshop_submit_lead', 'motoshop_lead_nonce'); ?>
            <?php if ('sent' === $request_state) : ?>
                <p class="form-notice form-notice--success" role="status"><?php esc_html_e('Permintaan sudah diterima. Tim kami akan segera menghubungi Anda.', 'motoshop'); ?></p>
            <?php elseif ('invalid' === $request_state) : ?>
                <p class="form-notice form-notice--error" role="alert"><?php esc_html_e('Mohon lengkapi nama, WhatsApp, motor, dan cabang.', 'motoshop'); ?></p>
            <?php endif; ?>
            <div class="form-row form-row--two">
                <label><?php esc_html_e('Nama lengkap', 'motoshop'); ?><input type="text" name="customer_name" autocomplete="name" required></label>
                <label><?php esc_html_e('Nomor WhatsApp', 'motoshop'); ?><input type="tel" name="customer_phone" autocomplete="tel" placeholder="08xxxxxxxxxx" required></label>
            </div>
            <div class="form-row form-row--two">
                <label><?php esc_html_e('Email opsional', 'motoshop'); ?><input type="email" name="customer_email" autocomplete="email"></label>
                <label><?php esc_html_e('Motor yang diminati', 'motoshop'); ?>
                    <?php if ($product_name) : ?>
                        <input type="text" name="product_name" value="<?php echo esc_attr($product_name); ?>" readonly>
                    <?php elseif ($choices) : ?>
                        <select name="product_name" required><option value=""><?php esc_html_e('Pilih motor', 'motoshop'); ?></option><?php foreach ($choices as $choice) : ?><option value="<?php echo esc_attr($choice); ?>"><?php echo esc_html($choice); ?></option><?php endforeach; ?></select>
                    <?php else : ?>
                        <input type="text" name="product_name" placeholder="Contoh: motor niaga roda tiga" required>
                    <?php endif; ?>
                </label>
            </div>
            <fieldset>
                <legend><?php esc_html_e('Rencana pembelian', 'motoshop'); ?></legend>
                <label class="radio-card"><input type="radio" name="purchase_method" value="tunai" checked><span><?php esc_html_e('Tunai', 'motoshop'); ?></span></label>
                <label class="radio-card"><input type="radio" name="purchase_method" value="kredit"><span><?php esc_html_e('Kredit', 'motoshop'); ?></span></label>
            </fieldset>
            <label><?php esc_html_e('Cabang terdekat', 'motoshop'); ?><select name="branch" required><option value=""><?php esc_html_e('Pilih cabang', 'motoshop'); ?></option><?php foreach (motoshop_branches() as $name => $address) : ?><option value="<?php echo esc_attr($name); ?>"><?php echo esc_html($name); ?></option><?php endforeach; ?></select></label>
            <label><?php esc_html_e('Catatan opsional', 'motoshop'); ?><textarea name="customer_message" rows="3" placeholder="Warna, tipe penggunaan, atau waktu yang nyaman untuk dihubungi"></textarea></label>
            <button class="button button--dark button--full" type="submit"><?php esc_html_e('Kirim permintaan', 'motoshop'); ?><span aria-hidden="true">&#8594;</span></button>
            <p class="form-consent"><?php esc_html_e('Dengan mengirim formulir ini, Anda bersedia dihubungi oleh tim Mahkota Inti Sejahtera.', 'motoshop'); ?></p>
        </form>
    </div>
    <?php
    return ob_get_clean();
}

function motoshop_request_shortcode($atts) {
    $atts = shortcode_atts(array('product' => ''), $atts, 'motoshop_request');
    return motoshop_request_form(sanitize_text_field($atts['product']));
}
add_shortcode('motoshop_request', 'motoshop_request_shortcode');

function motoshop_single_product_request() {
    global $product;
    if ($product && is_a($product, 'WC_Product')) {
        echo motoshop_request_form($product->get_name()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action('woocommerce_after_single_product_summary', 'motoshop_single_product_request', 12);

function motoshop_excerpt_length() {
    return 24;
}
add_filter('excerpt_length', 'motoshop_excerpt_length', 99);

/**
 * A focused admin hub for dealership operators and training sessions.
 */
function motoshop_register_admin_hub() {
    add_menu_page(
        __('MotoShop', 'motoshop'),
        __('MotoShop', 'motoshop'),
        'edit_posts',
        'motoshop-dashboard',
        'motoshop_render_admin_dashboard',
        'dashicons-store',
        3
    );
    add_submenu_page('motoshop-dashboard', __('Ringkasan MotoShop', 'motoshop'), __('Ringkasan', 'motoshop'), 'edit_posts', 'motoshop-dashboard', 'motoshop_render_admin_dashboard');
    if (current_user_can('edit_products')) {
        add_submenu_page('motoshop-dashboard', __('Semua Produk', 'motoshop'), __('Semua Produk', 'motoshop'), 'edit_products', 'edit.php?post_type=product');
        add_submenu_page('motoshop-dashboard', __('Tambah Produk', 'motoshop'), __('Tambah Produk', 'motoshop'), 'edit_products', 'post-new.php?post_type=product');
        add_submenu_page('motoshop-dashboard', __('Kategori Produk', 'motoshop'), __('Kategori Produk', 'motoshop'), 'manage_product_terms', 'edit-tags.php?taxonomy=product_cat&post_type=product');
    }
    if (current_user_can('manage_woocommerce')) {
        $orders_url = class_exists('Automattic\\WooCommerce\\Utilities\\OrderUtil') && Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled()
            ? 'admin.php?page=wc-orders'
            : 'edit.php?post_type=shop_order';
        add_submenu_page('motoshop-dashboard', __('Pesanan WooCommerce', 'motoshop'), __('Pesanan', 'motoshop'), 'manage_woocommerce', $orders_url);
    }
    add_submenu_page('motoshop-dashboard', __('Panduan Pengelola', 'motoshop'), __('Panduan', 'motoshop'), 'edit_posts', 'motoshop-guide', 'motoshop_render_admin_guide');
}
add_action('admin_menu', 'motoshop_register_admin_hub', 5);

function motoshop_admin_count_leads($status = '') {
    $args = array('post_type' => 'motoshop_lead', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => 1);
    if ($status) {
        $args['meta_query'] = array(array('key' => '_motoshop_status', 'value' => $status));
    }
    $query = new WP_Query($args);
    return (int) $query->found_posts;
}

function motoshop_render_admin_dashboard() {
    if (!current_user_can('edit_posts')) {
        return;
    }
    $product_counts = wp_count_posts('product');
    $product_total = isset($product_counts->publish) ? (int) $product_counts->publish : 0;
    $recent_leads = get_posts(array('post_type' => 'motoshop_lead', 'post_status' => 'publish', 'numberposts' => 5));
    ?>
    <div class="wrap motoshop-admin">
        <div class="motoshop-admin__hero">
            <div><p>MOTOSHOP CONTROL CENTER</p><h1>Selamat datang, <?php echo esc_html(wp_get_current_user()->display_name); ?>.</h1><span>Kelola katalog dan calon pembeli dari satu tempat.</span></div>
            <a class="button button-primary" href="<?php echo esc_url(home_url('/')); ?>" target="_blank" rel="noopener">Lihat website &#8599;</a>
        </div>
        <div class="motoshop-admin__stats">
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=product')); ?>"><span>Produk tayang</span><strong><?php echo esc_html($product_total); ?></strong><small>Kelola katalog &#8594;</small></a>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=motoshop_lead')); ?>"><span>Permintaan masuk</span><strong><?php echo esc_html(motoshop_admin_count_leads()); ?></strong><small>Lihat semua &#8594;</small></a>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=motoshop_lead')); ?>"><span>Perlu dihubungi</span><strong><?php echo esc_html(motoshop_admin_count_leads('Baru')); ?></strong><small>Tindak lanjuti &#8594;</small></a>
        </div>
        <div class="motoshop-admin__grid">
            <section class="motoshop-admin__panel">
                <div class="motoshop-admin__panel-head"><div><span>AKSES CEPAT</span><h2>Pekerjaan utama</h2></div></div>
                <div class="motoshop-admin__actions">
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=product')); ?>"><i class="dashicons dashicons-plus-alt2"></i><strong>Tambah produk</strong><span>Nama, foto, kategori, harga, dan spesifikasi</span></a>
                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=motoshop_lead')); ?>"><i class="dashicons dashicons-whatsapp"></i><strong>Permintaan motor</strong><span>Hubungi calon pembeli dan ubah status</span></a>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=motoshop-guide')); ?>"><i class="dashicons dashicons-welcome-learn-more"></i><strong>Buka panduan</strong><span>Langkah kerja untuk operator website</span></a>
                </div>
            </section>
            <section class="motoshop-admin__panel">
                <div class="motoshop-admin__panel-head"><div><span>TERBARU</span><h2>Calon pembeli</h2></div><a href="<?php echo esc_url(admin_url('edit.php?post_type=motoshop_lead')); ?>">Semua</a></div>
                <?php if ($recent_leads) : ?><div class="motoshop-admin__leads">
                    <?php foreach ($recent_leads as $lead) : ?>
                        <a href="<?php echo esc_url(get_edit_post_link($lead->ID)); ?>"><strong><?php echo esc_html(get_post_meta($lead->ID, '_motoshop_name', true)); ?></strong><span><?php echo esc_html(get_post_meta($lead->ID, '_motoshop_product', true)); ?></span><em><?php echo esc_html(get_post_meta($lead->ID, '_motoshop_status', true) ?: 'Baru'); ?></em></a>
                    <?php endforeach; ?>
                </div><?php else : ?><p class="motoshop-admin__empty">Belum ada permintaan. Formulir di website sudah siap menerima calon pembeli.</p><?php endif; ?>
            </section>
        </div>
    </div>
    <?php
}

function motoshop_render_admin_guide() {
    if (!current_user_can('edit_posts')) {
        return;
    }
    ?>
    <div class="wrap motoshop-admin motoshop-guide">
        <div class="motoshop-admin__hero"><div><p>PANDUAN OPERATOR</p><h1>Mengelola katalog &amp; permintaan</h1><span>Ikuti urutan ini saat pelatihan pengelola website.</span></div></div>
        <div class="motoshop-guide__layout">
            <nav><a href="#tambah-produk">Tambah produk</a><a href="#spesifikasi">Isi spesifikasi</a><a href="#permintaan">Tindak lanjut pelanggan</a><a href="#checklist">Checklist publikasi</a></nav>
            <div>
                <section id="tambah-produk"><span>01</span><h2>Menambahkan produk WooCommerce</h2><ol><li>Buka <strong>MotoShop &gt; Tambah Produk</strong>.</li><li>Isi nama motor dan deskripsi lengkap.</li><li>Pilih kategori produk di panel kanan.</li><li>Di <strong>Data produk</strong>, pilih <strong>Produk sederhana</strong>, lalu isi harga bila sudah pasti. Kosongkan harga bila pelanggan harus menghubungi sales.</li><li>Tambahkan <strong>Gambar produk</strong> utama dan galeri dari beberapa sudut.</li><li>Isi deskripsi singkat, cek tampilan dengan tombol Pratinjau, lalu klik Terbitkan.</li></ol></section>
                <section id="spesifikasi"><span>02</span><h2>Membuat tabel spesifikasi</h2><p>Pada kotak <strong>Data produk</strong>, buka tab <strong>Atribut</strong>. Tambahkan satu atribut untuk setiap data, misalnya Tipe Mesin, Daya Maksimum, Transmisi, Kapasitas Tangki, Dimensi, dan Berat. Centang <strong>Tampilkan di halaman produk</strong>, simpan atribut, lalu perbarui produk.</p></section>
                <section id="permintaan"><span>03</span><h2>Menindaklanjuti calon pembeli</h2><ol><li>Buka <strong>MotoShop &gt; Permintaan Motor</strong>.</li><li>Klik nomor WhatsApp untuk menghubungi pelanggan.</li><li>Buka detail permintaan dan ubah status dari Baru menjadi Dihubungi, Janji Kunjungan, Selesai, atau Batal.</li></ol></section>
                <section id="checklist"><span>04</span><h2>Checklist sebelum terbit</h2><ul><li>Nama dan kategori motor benar.</li><li>Foto jelas, latar rapi, dan tidak pecah.</li><li>Harga/stok sudah dikonfirmasi cabang.</li><li>Spesifikasi berasal dari sumber resmi.</li><li>Deskripsi tidak menjanjikan promo yang sudah berakhir.</li></ul><p><strong>Catatan:</strong> produk awal di website berfungsi sebagai contoh katalog pelatihan. Operator tetap perlu meninjau harga, stok, foto, dan spesifikasi sebelum dipakai secara resmi.</p></section>
            </div>
        </div>
    </div>
    <?php
}

function motoshop_admin_assets($hook) {
    if (!in_array($hook, array('toplevel_page_motoshop-dashboard', 'motoshop_page_motoshop-guide'), true)) {
        return;
    }
    wp_register_style('motoshop-admin-inline', false, array(), MOTOSHOP_VERSION);
    wp_enqueue_style('motoshop-admin-inline');
    wp_add_inline_style('motoshop-admin-inline', '
        .motoshop-admin{max-width:1220px;color:#132235}.motoshop-admin__hero{display:flex;align-items:end;justify-content:space-between;gap:30px;margin:22px 0;padding:34px 38px;border-left:7px solid #d71920;border-radius:5px;background:#0b1f33;color:#fff}.motoshop-admin__hero p,.motoshop-admin__panel-head span,.motoshop-guide section>span{margin:0 0 6px;color:#ff555b;font-size:11px;font-weight:800;letter-spacing:.13em}.motoshop-admin__hero h1{margin:0 0 7px;color:#fff;font-size:32px}.motoshop-admin__hero span{color:#aebdca}.motoshop-admin__stats{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin:0 0 16px}.motoshop-admin__stats>a,.motoshop-admin__panel{border:1px solid #dce4ea;border-radius:5px;background:#fff;box-shadow:0 8px 25px rgba(11,31,51,.05)}.motoshop-admin__stats>a{display:grid;padding:22px;color:#132235;text-decoration:none}.motoshop-admin__stats span{color:#627181}.motoshop-admin__stats strong{margin:5px 0;font-size:34px}.motoshop-admin__stats small{color:#d71920;font-weight:700}.motoshop-admin__grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}.motoshop-admin__panel{padding:25px}.motoshop-admin__panel-head{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #dce4ea;padding-bottom:16px}.motoshop-admin__panel-head h2{margin:0;font-size:20px}.motoshop-admin__actions{display:grid;gap:10px;margin-top:16px}.motoshop-admin__actions>a{display:grid;grid-template-columns:38px 1fr;column-gap:10px;padding:14px;border:1px solid #e2e8ed;border-radius:4px;color:#132235;text-decoration:none}.motoshop-admin__actions i{grid-row:1/3;color:#d71920}.motoshop-admin__actions span{color:#627181}.motoshop-admin__leads>a{display:grid;grid-template-columns:1fr auto;gap:2px 10px;padding:14px 0;border-bottom:1px solid #e2e8ed;color:#132235;text-decoration:none}.motoshop-admin__leads span{color:#627181}.motoshop-admin__leads em{grid-row:1/3;grid-column:2;align-self:center;border-radius:30px;background:#edf2f5;padding:4px 9px;font-size:11px;font-style:normal}.motoshop-guide__layout{display:grid;grid-template-columns:220px 1fr;gap:20px}.motoshop-guide nav{position:sticky;top:50px;height:max-content;display:grid;border:1px solid #dce4ea;background:#fff}.motoshop-guide nav a{padding:13px 16px;border-bottom:1px solid #dce4ea;color:#132235;text-decoration:none;font-weight:600}.motoshop-guide section{margin-bottom:16px;border:1px solid #dce4ea;border-radius:5px;background:#fff;padding:28px}.motoshop-guide section h2{margin-top:0}.motoshop-guide section li{margin-bottom:8px}@media(max-width:782px){.motoshop-admin__stats,.motoshop-admin__grid,.motoshop-guide__layout{grid-template-columns:1fr}.motoshop-admin__hero{align-items:start;flex-direction:column}.motoshop-guide nav{position:static}}
    ');
}
add_action('admin_enqueue_scripts', 'motoshop_admin_assets');
