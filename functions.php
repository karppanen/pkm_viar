<?php
/**
 * MotoShop theme setup and dealership enquiry workflow.
 *
 * @package MotoShop
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MOTOSHOP_VERSION', '2.0.2');

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
    wp_enqueue_style('motoshop-catalog', get_template_directory_uri() . '/assets/css/catalog.css', array('motoshop-main'), MOTOSHOP_VERSION);
    wp_enqueue_script('motoshop-main', get_template_directory_uri() . '/assets/js/main.js', array(), MOTOSHOP_VERSION, true);
    wp_localize_script('motoshop-main', 'motoshopTrack', array(
        'endpoint' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('motoshop_track'),
    ));
}
add_action('wp_enqueue_scripts', 'motoshop_assets');

function motoshop_site_icon() {
    $icon_url = get_template_directory_uri() . '/assets/images/logo-mis.png';
    printf("<link rel=\"icon\" href=\"%s\" type=\"image/png\">\n", esc_url($icon_url));
    printf("<link rel=\"apple-touch-icon\" href=\"%s\">\n", esc_url($icon_url));
}
add_action('wp_head', 'motoshop_site_icon', 2);
add_action('admin_head', 'motoshop_site_icon', 2);
add_action('login_head', 'motoshop_site_icon', 2);

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
        'Ciledug'     => 'Jl. KH. Hasyim Ashari No. 70A, Pedurenan, Karang Tengah, Kota Tangerang',
        'Kebon Jeruk' => 'Jl. Panjang No. 12, Kebon Jeruk, Jakarta Barat',
        'Depok'       => 'Jl. Arif Rahman Hakim, Kemiri Muka, Beji, Depok',
        'Karawaci'    => 'Jl. Imam Bonjol No. A5, Sukajadi, Karawaci',
        'Serang'      => 'Jl. Raya Cilegon Drangong No. 78, Serang',
        'Balaraja'    => 'Jl. Raya Serang KM 24, Kosambi, Balaraja',
    );
}

/**
 * Detail of the branch that owns this website.
 *
 * Jam operasional dikonfirmasi mitra: Senin - Sabtu 08.30 - 16.30 WIB,
 * Minggu tutup. Nomor WhatsApp adalah nomor sales cabang Ciledug.
 */
function motoshop_primary_branch() {
    return array(
        'name'     => 'Cabang Viar Mahkota Ciledug',
        'company'  => 'PT Mahkota Inti Sejahtera',
        'address'  => 'Jl. KH. Hasyim Ashari No. 70A, Pedurenan, Karang Tengah, Kota Tangerang, Banten',
        'phone'    => '0856-5666-7156',
        'whatsapp' => '6285665667156',
        'hours'    => 'Senin - Sabtu 08.30 - 16.30 WIB (Minggu tutup)',
        'maps'     => 'https://www.google.com/maps/search/?api=1&query=Jl.+KH.+Hasyim+Ashari+No.+70A+Pedurenan+Karang+Tengah+Kota+Tangerang',
        'instagram'=> 'https://www.instagram.com/viar.mahkota_official/',
    );
}

function motoshop_whatsapp_number() {
    $branch = motoshop_primary_branch();
    return $branch['whatsapp'];
}

function motoshop_whatsapp_link($message = '') {
    $url = 'https://wa.me/' . motoshop_whatsapp_number();
    if ($message) {
        $url = add_query_arg('text', $message, $url);
    }
    return $url;
}

/**
 * Preferred visit slots offered in the request form.
 *
 * Slots stay inside the branch opening hours so the form never proposes a time
 * when the showroom is already closed.
 */
function motoshop_visit_time_options() {
    return array(
        '',
        'Pagi (08.30 - 11.00)',
        'Siang (11.00 - 13.30)',
        'Sore (13.30 - 16.30)',
    );
}

/**
 * Follow-up status of a customer request.
 */
function motoshop_lead_statuses() {
    return array(
        'Baru'                => __('Baru', 'motoshop'),
        'Dihubungi'          => __('Dihubungi', 'motoshop'),
        'Menunggu Konfirmasi' => __('Menunggu Konfirmasi', 'motoshop'),
        'Janji Kunjungan'    => __('Janji Kunjungan', 'motoshop'),
        'Selesai'            => __('Selesai', 'motoshop'),
        'Batal'              => __('Batal', 'motoshop'),
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
        'capability_type' => array('motoshop_lead', 'motoshop_leads'),
        'map_meta_cap'   => true,
    ));
}
add_action('init', 'motoshop_register_leads');

function motoshop_lead_capabilities() {
    return array(
        'edit_motoshop_lead',
        'read_motoshop_lead',
        'delete_motoshop_lead',
        'edit_motoshop_leads',
        'edit_others_motoshop_leads',
        'publish_motoshop_leads',
        'read_private_motoshop_leads',
        'delete_motoshop_leads',
        'delete_private_motoshop_leads',
        'delete_published_motoshop_leads',
        'delete_others_motoshop_leads',
        'edit_private_motoshop_leads',
        'edit_published_motoshop_leads',
    );
}

/**
 * Capabilities of the operator role.
 *
 * The operator may only manage catalogue content. Financial, plugin, user,
 * theme, and point-of-sale rights stay with the administrator.
 */
function motoshop_operator_capabilities() {
    return array(
        // WordPress basics.
        'read'                       => true,
        'upload_files'               => true,
        'manage_categories'          => true,
        'moderate_comments'          => true,
        // Posts and pages.
        'edit_posts'                 => true,
        'edit_others_posts'          => true,
        'edit_published_posts'       => true,
        'publish_posts'              => true,
        'read_private_posts'         => true,
        'delete_posts'               => true,
        'delete_others_posts'        => true,
        'delete_published_posts'     => true,
        'delete_private_posts'       => true,
        'edit_private_posts'         => true,
        'edit_pages'                 => true,
        'edit_others_pages'          => true,
        'edit_published_pages'       => true,
        'publish_pages'              => true,
        'read_private_pages'         => true,
        'delete_pages'               => true,
        'delete_others_pages'        => true,
        'delete_published_pages'     => true,
        'delete_private_pages'       => true,
        'edit_private_pages'         => true,
        // Catalogue.
        'edit_products'              => true,
        'edit_others_products'       => true,
        'edit_published_products'    => true,
        'publish_products'           => true,
        'read_private_products'      => true,
        'delete_products'            => true,
        'delete_others_products'     => true,
        'delete_published_products'  => true,
        'delete_private_products'    => true,
        'edit_private_products'      => true,
        'manage_product_terms'       => true,
        'edit_product_terms'         => true,
        'delete_product_terms'       => true,
        'manage_product_attributes'  => true,
    );
}

/**
 * Capabilities that must never be granted to the operator role.
 */
function motoshop_operator_forbidden_capabilities() {
    return array(
        'manage_options',
        'manage_woocommerce',
        'manage_woocommerce_settings',
        'view_woocommerce_reports',
        'manage_woocommerce_analytics',
        'manage_woocommerce_marketing',
        'manage_woocommerce_pos',
        'read_woocommerce_pos',
        'manage_woocommerce_tax_settings',
        'manage_woocommerce_shipping',
        'manage_woocommerce_payment_methods',
        'edit_woocommerce_tax_classes',
        'activate_plugins',
        'install_plugins',
        'update_plugins',
        'delete_plugins',
        'edit_plugins',
        'edit_themes',
        'switch_themes',
        'delete_themes',
        'edit_users',
        'create_users',
        'delete_users',
        'list_users',
        'promote_users',
        'remove_users',
        'edit_dashboard',
        'manage_categories_shop',
    );
}

function motoshop_register_staff_roles() {
    $lead_caps = motoshop_lead_capabilities();
    foreach (array('administrator', 'shop_manager') as $role_name) {
        $role = get_role($role_name);
        if ($role) {
            foreach ($lead_caps as $capability) {
                $role->add_cap($capability);
            }
        }
    }

    $operator = get_role('motoshop_operator');
    if (!$operator) {
        $operator = add_role(
            'motoshop_operator',
            __('Operator MotoShop', 'motoshop'),
            motoshop_operator_capabilities()
        );
    }
    if ($operator) {
        // Apply the intended capability set and revoke anything inherited.
        foreach (motoshop_operator_capabilities() as $capability => $granted) {
            $operator->add_cap($capability);
        }
        foreach (motoshop_operator_forbidden_capabilities() as $capability) {
            $operator->remove_cap($capability);
        }
        foreach ($lead_caps as $capability) {
            $operator->add_cap($capability);
        }
    }

    $sales = get_role('motoshop_sales');
    if (!$sales) {
        $sales = add_role('motoshop_sales', __('Sales MotoShop', 'motoshop'), array('read' => true));
    }
    if ($sales) {
        foreach (array('edit_motoshop_leads', 'edit_others_motoshop_leads', 'edit_published_motoshop_leads', 'read_motoshop_lead', 'view_admin_dashboard') as $capability) {
            $sales->add_cap($capability);
        }
        // The sales role only reads the catalogue, never edits it.
        $sales->remove_cap('edit_products');
        $sales->remove_cap('publish_products');
        $sales->remove_cap('delete_products');
        $sales->remove_cap('edit_published_products');
        $sales->remove_cap('edit_others_products');
        $sales->remove_cap('upload_files');
        $sales->remove_cap('moderate_comments');
    }
}
add_action('init', 'motoshop_register_staff_roles', 6);

/**
 * WooCommerce blocks the admin area for accounts without editing rights.
 * The dealership staff roles are managed by this theme, so they stay allowed.
 */
function motoshop_allow_staff_admin_access($prevent_access) {
    if (current_user_can('edit_motoshop_leads')) {
        return false;
    }
    return $prevent_access;
}
add_filter('woocommerce_prevent_admin_access', 'motoshop_allow_staff_admin_access');

function motoshop_handle_lead() {
    if (!isset($_POST['motoshop_lead_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['motoshop_lead_nonce'])), 'motoshop_submit_lead')) {
        wp_die(esc_html__('Permintaan tidak dapat diverifikasi. Silakan muat ulang halaman.', 'motoshop'));
    }

    if (!empty($_POST['customer_website'])) {
        wp_safe_redirect(home_url('/'));
        exit;
    }

    $name    = isset($_POST['customer_name']) ? sanitize_text_field(wp_unslash($_POST['customer_name'])) : '';
    $phone   = isset($_POST['customer_phone']) ? sanitize_text_field(wp_unslash($_POST['customer_phone'])) : '';
    $product = isset($_POST['product_name']) ? sanitize_text_field(wp_unslash($_POST['product_name'])) : '';
    $method  = isset($_POST['purchase_method']) ? sanitize_key(wp_unslash($_POST['purchase_method'])) : 'tunai';
    $branch  = isset($_POST['branch']) ? sanitize_text_field(wp_unslash($_POST['branch'])) : '';
    $message = isset($_POST['customer_message']) ? sanitize_textarea_field(wp_unslash($_POST['customer_message'])) : '';
    $source  = isset($_POST['lead_source']) ? sanitize_text_field(wp_unslash($_POST['lead_source'])) : 'Website';
    $visit_date = isset($_POST['visit_date']) ? sanitize_text_field(wp_unslash($_POST['visit_date'])) : '';
    $visit_time = isset($_POST['visit_time']) ? sanitize_text_field(wp_unslash($_POST['visit_time'])) : '';
    $phone_digits = preg_replace('/\D+/', '', $phone);
    $valid_products = motoshop_product_choices();
    $valid_phone = (bool) preg_match('/^(?:62|0)8[0-9]{7,13}$/', $phone_digits);
    $valid_times = motoshop_visit_time_options();

    if ($visit_date && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $visit_date)) {
        $visit_date = '';
    }
    if (!in_array($visit_time, $valid_times, true)) {
        $visit_time = '';
    }
    // The branch is closed on Sunday, so a Sunday visit date is not accepted.
    if ($visit_date && '0' === wp_date('w', strtotime($visit_date))) {
        $back = wp_get_referer() ? wp_get_referer() : home_url('/');
        wp_safe_redirect(add_query_arg('request', 'closed_day', $back) . '#request-form');
        exit;
    }

    if (!$name || !$valid_phone || !$product || !in_array($product, $valid_products, true) || !array_key_exists($branch, motoshop_branches())) {
        $back = wp_get_referer() ? wp_get_referer() : home_url('/');
        wp_safe_redirect(add_query_arg('request', 'invalid', $back) . '#request-form');
        exit;
    }

    if (!in_array($method, array('tunai', 'kredit'), true)) {
        $method = 'tunai';
    }

    $remote_address = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : 'unknown';
    $rate_limit_key = 'motoshop_lead_' . md5($remote_address . '|' . $phone_digits . '|' . $product);
    if (get_transient($rate_limit_key)) {
        $back = wp_get_referer() ? wp_get_referer() : home_url('/');
        wp_safe_redirect(add_query_arg('request', 'sent', $back) . '#request-form');
        exit;
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
        '_motoshop_product' => $product,
        '_motoshop_method'  => $method,
        '_motoshop_branch'  => $branch,
        '_motoshop_message' => $message,
        '_motoshop_source'  => $source ?: 'Website',
        '_motoshop_status'  => 'Baru',
        '_motoshop_visit_date' => $visit_date,
        '_motoshop_visit_time' => $visit_time,
    );
    foreach ($fields as $key => $value) {
        update_post_meta($lead_id, $key, $value);
    }
    set_transient($rate_limit_key, 1, MINUTE_IN_SECONDS);

    $product_object = wc_get_product_id_by_sku($product) ? wc_get_product(wc_get_product_id_by_sku($product)) : null;
    if (!$product_object) {
        $page = get_page_by_title($product, OBJECT, 'product');
        $product_object = $page ? wc_get_product($page->ID) : null;
    }
    motoshop_track_event('lead', array(
        'path'       => motoshop_current_request_path(),
        'product_id' => $product_object ? $product_object->get_id() : 0,
        'source'     => $source ?: 'Website',
    ));

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
        'source' => __('Sumber', 'motoshop'),
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
        'source' => '_motoshop_source',
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
        '_motoshop_product' => __('Motor diminati', 'motoshop'),
        '_motoshop_method'  => __('Metode pembelian', 'motoshop'),
        '_motoshop_branch'  => __('Cabang', 'motoshop'),
        '_motoshop_source'  => __('Sumber permintaan', 'motoshop'),
        '_motoshop_message' => __('Catatan', 'motoshop'),
        '_motoshop_visit_date' => __('Tanggal kunjungan yang diinginkan', 'motoshop'),
        '_motoshop_visit_time' => __('Waktu yang diinginkan', 'motoshop'),
    );
    wp_nonce_field('motoshop_save_lead_status', 'motoshop_lead_status_nonce');
    echo '<table class="widefat striped"><tbody>';
    foreach ($labels as $key => $label) {
        printf('<tr><th style="width:180px">%s</th><td>%s</td></tr>', esc_html($label), nl2br(esc_html(get_post_meta($post->ID, $key, true))));
    }
    $current_status = get_post_meta($post->ID, '_motoshop_status', true) ?: 'Baru';
    $statuses = motoshop_lead_statuses();
    echo '<tr><th>' . esc_html__('Status tindak lanjut', 'motoshop') . '</th><td><select name="motoshop_lead_status">';
    foreach ($statuses as $status => $label) {
        printf('<option value="%s" %s>%s</option>', esc_attr($status), selected($current_status, $status, false), esc_html($label));
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
    $allowed = array_keys(motoshop_lead_statuses());
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
    $lead_source = $product_name ? __('Detail Produk', 'motoshop') : __('Beranda', 'motoshop');
    if (!empty($_GET['utm_source'])) {
        $lead_source = sprintf(__('Kampanye: %s', 'motoshop'), sanitize_text_field(wp_unslash($_GET['utm_source'])));
    }
    $choices = motoshop_product_choices();
    ob_start();
    ?>
    <div class="request-shell" id="request-form">
        <div class="request-copy">
            <p class="eyebrow"><?php esc_html_e('Konsultasi pembelian', 'motoshop'); ?></p>
            <h2><?php esc_html_e('Ceritakan motor yang Anda cari.', 'motoshop'); ?></h2>
            <p><?php esc_html_e('Tim cabang akan menghubungi Anda untuk mengecek unit, harga terbaru, pilihan kredit, dan jadwal kunjungan.', 'motoshop'); ?></p>
            <p class="request-contact"><span><?php esc_html_e('Butuh cepat?', 'motoshop'); ?></span> <a href="<?php echo esc_url(motoshop_whatsapp_link(__('Halo, saya ingin bertanya tentang motor VIAR di Cabang Ciledug.', 'motoshop'))); ?>" target="_blank" rel="noopener"><?php echo esc_html(motoshop_primary_branch()['phone']); ?></a></p>
        </div>
        <form class="request-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <input type="hidden" name="action" value="motoshop_submit_lead">
            <input type="hidden" name="lead_source" value="<?php echo esc_attr($lead_source); ?>">
            <label class="form-trap" aria-hidden="true">Website<input type="text" name="customer_website" tabindex="-1" autocomplete="off"></label>
            <?php wp_nonce_field('motoshop_submit_lead', 'motoshop_lead_nonce'); ?>
            <?php if ('sent' === $request_state) : ?>
                <p class="form-notice form-notice--success" role="status"><?php esc_html_e('Permintaan sudah diterima. Tim kami akan segera menghubungi Anda.', 'motoshop'); ?></p>
            <?php elseif ('invalid' === $request_state) : ?>
                <p class="form-notice form-notice--error" role="alert"><?php esc_html_e('Mohon lengkapi nama, WhatsApp, motor, dan cabang.', 'motoshop'); ?></p>
            <?php elseif ('closed_day' === $request_state) : ?>
                <p class="form-notice form-notice--error" role="alert"><?php esc_html_e('Cabang tutup hari Minggu. Silakan pilih tanggal lain atau tentukan jadwal lewat WhatsApp.', 'motoshop'); ?></p>
            <?php endif; ?>
            <div class="form-row form-row--two">
                <label><?php esc_html_e('Nama lengkap', 'motoshop'); ?><input type="text" name="customer_name" autocomplete="name" required></label>
                <label><?php esc_html_e('Nomor WhatsApp', 'motoshop'); ?><input type="tel" name="customer_phone" autocomplete="tel" inputmode="tel" pattern="[0-9+() -]{9,18}" placeholder="08xxxxxxxxxx" required></label>
            </div>
            <label><?php esc_html_e('Motor yang diminati', 'motoshop'); ?>
                <?php if ($product_name) : ?>
                    <input type="text" name="product_name" value="<?php echo esc_attr($product_name); ?>" readonly>
                <?php elseif ($choices) : ?>
                    <select name="product_name" required><option value=""><?php esc_html_e('Pilih motor', 'motoshop'); ?></option><?php foreach ($choices as $choice) : ?><option value="<?php echo esc_attr($choice); ?>"><?php echo esc_html($choice); ?></option><?php endforeach; ?></select>
                <?php else : ?>
                    <input type="text" name="product_name" placeholder="Contoh: motor niaga roda tiga" required>
                <?php endif; ?>
            </label>
            <fieldset>
                <legend><?php esc_html_e('Rencana pembelian', 'motoshop'); ?></legend>
                <label class="radio-card"><input type="radio" name="purchase_method" value="tunai" checked><span><?php esc_html_e('Tunai', 'motoshop'); ?></span></label>
                <label class="radio-card"><input type="radio" name="purchase_method" value="kredit"><span><?php esc_html_e('Kredit', 'motoshop'); ?></span></label>
            </fieldset>
            <label><?php esc_html_e('Cabang terdekat', 'motoshop'); ?><select name="branch" required><option value=""><?php esc_html_e('Pilih cabang', 'motoshop'); ?></option><?php foreach (motoshop_branches() as $name => $address) : ?><option value="<?php echo esc_attr($name); ?>" <?php selected('Ciledug', $name); ?>><?php echo esc_html($name); ?><?php echo 'Ciledug' === $name ? ' (' . esc_html__('cabang website ini', 'motoshop') . ')' : ''; ?></option><?php endforeach; ?></select></label>
            <div class="form-row form-row--two">
                <label><?php esc_html_e('Tanggal kunjungan yang diinginkan', 'motoshop'); ?><input type="date" name="visit_date" min="<?php echo esc_attr(date('Y-m-d')); ?>"></label>
                <label><?php esc_html_e('Waktu yang nyaman', 'motoshop'); ?><select name="visit_time"><?php foreach (motoshop_visit_time_options() as $slot) : ?><option value="<?php echo esc_attr($slot); ?>" <?php selected('', $slot); ?>><?php echo esc_html('' === $slot ? __('Bebas / tentukan lewat WhatsApp', 'motoshop') : $slot); ?></option><?php endforeach; ?></select></label>
            </div>
            <p class="form-hint"><?php esc_html_e('Jam cabang: Senin - Sabtu 08.30 - 16.30 WIB, Minggu tutup. Jadwal pasti dikonfirmasi sales lewat WhatsApp.', 'motoshop'); ?></p>
            <label><?php esc_html_e('Catatan opsional', 'motoshop'); ?><textarea name="customer_message" rows="3" placeholder="Warna, tipe penggunaan, atau hal lain yang perlu kami siapkan"></textarea></label>
            <button class="button button--dark button--full" type="submit"><?php esc_html_e('Kirim permintaan', 'motoshop'); ?><span aria-hidden="true">&#8594;</span></button>
            <p class="form-consent"><?php esc_html_e('Data Anda hanya digunakan untuk menindaklanjuti permintaan ini. Dengan mengirim formulir, Anda bersedia dihubungi oleh tim Mahkota Inti Sejahtera.', 'motoshop'); ?></p>
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

/**
 * Keep WooCommerce in dealership catalog mode.
 * Vehicle payment, financing approval, and handover remain offline.
 */
function motoshop_catalog_mode_setup() {
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

    add_action('woocommerce_shop_loop_item_title', 'motoshop_loop_product_category', 5);
    add_action('woocommerce_after_shop_loop_item_title', 'motoshop_loop_product_specs', 7);
    add_action('woocommerce_after_shop_loop_item', 'motoshop_loop_product_link', 10);

    add_action('woocommerce_single_product_summary', 'motoshop_single_product_category', 4);
    add_action('woocommerce_single_product_summary', 'motoshop_single_product_highlights', 22);
    add_action('woocommerce_single_product_summary', 'motoshop_single_product_actions', 31);
    add_action('woocommerce_after_single_product_summary', 'motoshop_single_product_features', 4);
    add_action('woocommerce_after_single_product_summary', 'motoshop_single_product_specs', 5);
    add_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    // Tab deskripsi WooCommerce tetap ditampilkan, tapi kontennya disaring
    // agar bagian spesifikasi yang sudah ada di atas tidak muncul lagi.
}
add_action('wp', 'motoshop_catalog_mode_setup');

add_filter('woocommerce_is_purchasable', '__return_false');
add_filter('woocommerce_show_page_title', '__return_false');

// Hide the sort dropdown — the catalogue is editorial, not user-sortable.
add_filter('woocommerce_catalog_orderby', '__return_empty_array');
// Default order: name A-Z so the list is predictable.
add_filter('woocommerce_default_catalog_orderby', function () { return 'menu_order'; });
// Show all products per page in the sidebar catalogue (WooCommerce paginates by 16).
add_filter('loop_shop_per_page', function () { return 16; }, 20);

function motoshop_related_products_layout($args) {
    $args['posts_per_page'] = 3;
    $args['columns'] = 3;
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'motoshop_related_products_layout');

/**
 * Keep the compact product thumbnail in catalog cards while allowing a wider
 * campaign banner to lead the product detail gallery.
 */
function motoshop_single_product_banner($html, $attachment_id) {
    if (!is_product()) {
        return $html;
    }

    global $product;
    if (!$product || (int) $attachment_id !== (int) $product->get_image_id()) {
        return $html;
    }

    $banner_id = absint($product->get_meta('_motoshop_banner_id'));
    if (!$banner_id || !wp_attachment_is_image($banner_id)) {
        return $html;
    }

    return wc_get_gallery_image_html($banner_id, true);
}
add_filter('woocommerce_single_product_image_thumbnail_html', 'motoshop_single_product_banner', 10, 2);

function motoshop_catalog_redirect_checkout() {
    if (function_exists('is_cart') && (is_cart() || is_checkout())) {
        wp_safe_redirect(wc_get_page_permalink('shop'));
        exit;
    }
}
add_action('template_redirect', 'motoshop_catalog_redirect_checkout');

function motoshop_product_attribute_rows($product) {
    $rows = array();
    if (!$product || !is_a($product, 'WC_Product')) {
        return $rows;
    }

    foreach ($product->get_attributes() as $attribute) {
        $values = $attribute->is_taxonomy()
            ? wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names'))
            : $attribute->get_options();
        $value = implode(', ', array_filter(array_map('wp_strip_all_tags', $values)));
        if ($value) {
            $rows[] = array(
                'label' => wc_attribute_label($attribute->get_name()),
                'value' => $value,
            );
        }
    }
    return $rows;
}

function motoshop_loop_product_category() {
    global $product;
    if (!$product) {
        return;
    }
    $categories = wc_get_product_category_list($product->get_id(), ' / ');
    if ($categories) {
        echo '<p class="catalog-product-category">' . wp_kses_post($categories) . '</p>';
    }
}

function motoshop_loop_product_specs() {
    global $product;
    $rows = array_slice(motoshop_product_attribute_rows($product), 0, 2);
    if (!$rows) {
        return;
    }
    echo '<ul class="catalog-product-specs">';
    foreach ($rows as $row) {
        printf('<li><span>%s</span><strong>%s</strong></li>', esc_html($row['label']), esc_html($row['value']));
    }
    echo '</ul>';
}

function motoshop_loop_product_link() {
    global $product;
    if (!$product) {
        return;
    }
    printf(
        '<a class="button catalog-product-link" href="%s" aria-label="%s">%s <span aria-hidden="true">&#8594;</span></a>',
        esc_url($product->get_permalink()),
        esc_attr(sprintf(__('Lihat detail %s', 'motoshop'), $product->get_name())),
        esc_html__('Lihat detail', 'motoshop')
    );
}

/**
 * Some official VIAR images are wide campaign banners. WooCommerce crops them
 * to a square thumbnail, which cuts off the product name and the specifications
 * printed on the banner. When that happens, use the uncropped large size so the
 * whole banner stays readable inside the card.
 */
function motoshop_uncropped_banner_thumbnail($image, $product, $size, $attr, $placeholder, $rendered) {
    if (!$product || !is_a($product, 'WC_Product')) {
        return $image;
    }
    if (!in_array($size, array('woocommerce_thumbnail', 'thumbnail', 'medium'), true)) {
        return $image;
    }
    // The sixth filter argument is the rendered markup, so read the image ID from the product.
    $attachment_id = (int) $product->get_image_id();
    if (!$attachment_id) {
        return $image;
    }
    $meta = wp_get_attachment_metadata($attachment_id);
    if (!$meta || empty($meta['width']) || empty($meta['height'])) {
        return $image;
    }
    $ratio = (float) $meta['width'] / (float) $meta['height'];
    // WooCommerce hard-crops the catalogue thumbnail to 1:1, which trims the
    // product name on portrait banners and the specifications on wide banners.
    if ($ratio > 0.92 && $ratio < 1.35) {
        return $image;
    }
    $full = wp_get_attachment_image($attachment_id, 'large', false, $attr);
    return $full ?: $image;
}
add_filter('woocommerce_product_get_image', 'motoshop_uncropped_banner_thumbnail', 10, 6);

function motoshop_single_product_category() {
    global $product;
    if (!$product) {
        return;
    }
    $terms = get_the_terms($product->get_id(), 'product_cat');
    if ($terms && !is_wp_error($terms)) {
        $term = reset($terms);
        printf('<a class="single-product-category" href="%s">%s</a>', esc_url(get_term_link($term)), esc_html($term->name));
    }
}

function motoshop_single_product_highlights() {
    global $product;
    $rows = array_slice(motoshop_product_attribute_rows($product), 0, 4);
    if (!$rows) {
        return;
    }
    echo '<div class="product-highlights" aria-label="Ringkasan spesifikasi">';
    foreach ($rows as $row) {
        printf('<div><span>%s</span><strong>%s</strong></div>', esc_html($row['label']), esc_html($row['value']));
    }
    echo '</div>';
}

function motoshop_single_product_actions() {
    global $product;
    if (!$product) {
        return;
    }
    $message = sprintf('Halo tim Mahkota Ciledug, saya ingin berkonsultasi mengenai %s. %s', $product->get_name(), $product->get_permalink());
    $whatsapp_url = motoshop_whatsapp_link($message);
    $availability = get_post_meta($product->get_id(), '_motoshop_availability_note', true);
    echo '<div class="product-actions">';
    printf('<a class="button button--red" href="#request-form" data-product-id="%d">%s</a>', (int) $product->get_id(), esc_html__('Ajukan permintaan', 'motoshop'));
    printf('<a class="button button--outline" href="%s" target="_blank" rel="noopener" data-product-id="%d">%s</a>', esc_url($whatsapp_url), (int) $product->get_id(), esc_html__('Tanya via WhatsApp', 'motoshop'));
    echo '</div>';
    echo '<ul class="product-availability">';
    printf('<li><span>%s</span><strong>%s</strong></li>', esc_html__('Harga', 'motoshop'), esc_html__('Hubungi sales untuk harga terbaru', 'motoshop'));
    printf('<li><span>%s</span><strong>%s</strong></li>', esc_html__('Status unit', 'motoshop'), esc_html($availability ? $availability : __('Konfirmasi ke sales cabang', 'motoshop')));
    printf('<li><span>%s</span><strong>%s</strong></li>', esc_html__('Pembelian', 'motoshop'), esc_html__('Tunai atau kredit, langsung di cabang', 'motoshop'));
    echo '</ul>';
}

function motoshop_single_product_features() {
    global $product;
    if (!$product || !is_a($product, 'WC_Product')) {
        return;
    }

    $feature_ids = array_values(array_filter(array_map('absint', (array) $product->get_meta('_motoshop_feature_gallery'))));
    $feature_ids = array_values(array_filter($feature_ids, 'wp_attachment_is_image'));
    if (!$feature_ids) {
        return;
    }
    ?>
    <section class="product-features" aria-labelledby="product-features-title" data-feature-slider data-interval="4200">
        <div class="product-section-heading">
            <div>
                <p class="eyebrow"><?php esc_html_e('Detail unggulan', 'motoshop'); ?></p>
                <h2 id="product-features-title"><?php esc_html_e('Fitur kendaraan', 'motoshop'); ?></h2>
            </div>
            <?php if (count($feature_ids) > 1) : ?>
                <div class="feature-slider__controls" aria-label="<?php esc_attr_e('Navigasi fitur', 'motoshop'); ?>">
                    <button type="button" data-feature-prev aria-label="<?php esc_attr_e('Fitur sebelumnya', 'motoshop'); ?>">&#8592;</button>
                    <span><strong data-feature-current>01</strong> / <?php echo esc_html(str_pad((string) count($feature_ids), 2, '0', STR_PAD_LEFT)); ?></span>
                    <button type="button" data-feature-next aria-label="<?php esc_attr_e('Fitur berikutnya', 'motoshop'); ?>">&#8594;</button>
                </div>
            <?php endif; ?>
        </div>
        <div class="feature-slider__viewport" data-feature-viewport tabindex="0">
            <div class="feature-slider__track">
                <?php foreach ($feature_ids as $index => $feature_id) :
                    $label = get_the_title($feature_id);
                    ?>
                    <figure class="feature-slide" data-feature-slide>
                        <?php echo wp_get_attachment_image($feature_id, 'large', false, array('loading' => 0 === $index ? 'eager' : 'lazy')); ?>
                        <?php if ($label) : ?><figcaption><?php echo esc_html($label); ?></figcaption><?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if (count($feature_ids) > 1) : ?>
            <div class="feature-slider__dots" aria-label="<?php esc_attr_e('Pilih fitur', 'motoshop'); ?>">
                <?php foreach ($feature_ids as $index => $feature_id) : ?>
                    <button type="button" data-feature-dot="<?php echo esc_attr($index); ?>" aria-label="<?php echo esc_attr(sprintf(__('Lihat fitur %d', 'motoshop'), $index + 1)); ?>"></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
    <?php
}

function motoshop_single_product_specs() {
    global $product;
    $rows = motoshop_product_attribute_rows($product);
    if (!$rows) {
        return;
    }
    ?>
    <section class="product-specification" aria-labelledby="product-specification-title">
        <div class="product-section-heading product-specification__heading">
            <div><p class="eyebrow"><?php esc_html_e('Data kendaraan', 'motoshop'); ?></p><h2 id="product-specification-title"><?php esc_html_e('Spesifikasi lengkap', 'motoshop'); ?></h2></div>
        </div>
        <dl class="product-specification__grid">
            <?php foreach ($rows as $row) : ?>
                <div><dt><?php echo esc_html($row['label']); ?></dt><dd><?php echo esc_html($row['value']); ?></dd></div>
            <?php endforeach; ?>
        </dl>
    </section>
    <?php
}

function motoshop_product_tabs($tabs) {
    unset($tabs['additional_information']);
    unset($tabs['reviews']);
    if (isset($tabs['description'])) {
        $tabs['description']['title']    = __('Deskripsi', 'motoshop');
        $tabs['description']['callback'] = 'motoshop_product_description_tab';

        // Hide the tab when the cleaned description adds nothing beyond the
        // short description already shown next to the gallery.
        if (!motoshop_product_description_has_extra()) {
            unset($tabs['description']);
        }
    }
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'motoshop_product_tabs', 20);

/**
 * Cleaned long description: the specification block and the source note are
 * removed because they are rendered once by motoshop_single_product_specs().
 */
function motoshop_clean_product_description($product = null) {
    if (!$product) {
        global $product;
    }
    if (!$product || !is_a($product, 'WC_Product')) {
        return '';
    }
    $content = (string) $product->get_description();
    if ('' === trim($content)) {
        return '';
    }
    $content = preg_replace('/<h3[^>]*>\s*Spesifikasi\s*<\/h3>[\s\S]*/i', '', $content);
    $content = preg_replace('/<p[^>]*>\s*Spesifikasi di bawah ini diambil dari[\s\S]*?<\/p>/i', '', $content);
    return trim($content);
}

function motoshop_product_description_has_extra($product = null) {
    if (!$product) {
        global $product;
    }
    if (!$product || !is_a($product, 'WC_Product')) {
        return false;
    }
    $clean = wp_strip_all_tags(motoshop_clean_product_description($product));
    $clean = trim(preg_replace('/\s+/', ' ', $clean));
    if ('' === $clean) {
        return false;
    }
    $short = wp_strip_all_tags($product->get_short_description());
    $short = trim(preg_replace('/\s+/', ' ', $short));
    if ('' !== $short && (false !== strpos($clean, $short) || false !== strpos($short, $clean))) {
        return false;
    }
    return true;
}

/**
 * Render the description tab with the redundant specification block removed.
 */
function motoshop_product_description_tab() {
    $content = motoshop_clean_product_description();
    if ('' === $content) {
        return;
    }
    // WooCommerce already wraps the callback output in a tab panel, so only print the content here.
    echo wp_kses_post(wpautop($content));
}

function motoshop_excerpt_length() {
    return 24;
}
add_filter('excerpt_length', 'motoshop_excerpt_length', 99);

/**
 * Lightweight internal statistics for the dealership website.
 *
 * The data set is kept inside WordPress so staff can read it without an
 * external analytics account, which keeps the maintenance work simple for
 * the partner during the training and mentoring phase.
 */
function motoshop_analytics_table_name() {
    global $wpdb;
    return $wpdb->prefix . 'motoshop_events';
}

function motoshop_analytics_install() {
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table = motoshop_analytics_table_name();
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$table} (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        event_type varchar(30) NOT NULL DEFAULT 'view',
        path varchar(190) NOT NULL DEFAULT '',
        product_id bigint(20) unsigned NOT NULL DEFAULT 0,
        source varchar(120) NOT NULL DEFAULT '',
        referrer varchar(190) NOT NULL DEFAULT '',
        created_at datetime NOT NULL,
        PRIMARY KEY  (id),
        KEY event_type (event_type),
        KEY product_id (product_id),
        KEY created_at (created_at)
    ) {$charset};";
    dbDelta($sql);
    update_option('motoshop_analytics_version', '1.0');
}

function motoshop_analytics_maybe_install() {
    if ('1.0' !== get_option('motoshop_analytics_version')) {
        motoshop_analytics_install();
    }
}
add_action('init', 'motoshop_analytics_maybe_install', 5);
add_action('after_switch_theme', 'motoshop_analytics_install');

function motoshop_analytics_table_exists() {
    global $wpdb;
    $table = motoshop_analytics_table_name();
    return $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table)) === $table;
}

function motoshop_track_event($event_type, $args = array()) {
    global $wpdb;
    if (!motoshop_analytics_table_exists()) {
        return;
    }
    $defaults = array(
        'path'       => '',
        'product_id' => 0,
        'source'     => '',
        'referrer'   => '',
    );
    $args = wp_parse_args($args, $defaults);
    $wpdb->insert(motoshop_analytics_table_name(), array(
        'event_type' => substr(sanitize_key($event_type), 0, 30),
        'path'       => substr(sanitize_text_field($args['path']), 0, 190),
        'product_id' => (int) $args['product_id'],
        'source'     => substr(sanitize_text_field($args['source']), 0, 120),
        'referrer'   => substr(sanitize_text_field($args['referrer']), 0, 190),
        'created_at' => current_time('mysql'),
    ));
}

function motoshop_current_request_path() {
    $uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
    $uri = strtok($uri, '?');
    return substr($uri, 0, 190);
}

function motoshop_track_page_view() {
    if (is_admin() || wp_doing_ajax() || is_feed() || is_preview()) {
        return;
    }
    $source = '';
    if (!empty($_GET['utm_source'])) {
        $source = sanitize_text_field(wp_unslash($_GET['utm_source']));
    } else {
        $referrer = wp_get_raw_referer();
        if ($referrer) {
            $host = wp_parse_url($referrer, PHP_URL_HOST);
            $own = wp_parse_url(home_url(), PHP_URL_HOST);
            if ($host && $host !== $own) {
                $source = $host;
            }
        }
    }
    $product_id = function_exists('is_product') && is_product() ? get_queried_object_id() : 0;
    motoshop_track_event('view', array(
        'path'       => motoshop_current_request_path(),
        'product_id' => $product_id,
        'source'     => $source,
        'referrer'   => wp_get_raw_referer(),
    ));
}
add_action('template_redirect', 'motoshop_track_page_view');

function motoshop_ajax_track() {
    check_ajax_referer('motoshop_track', 'nonce');
    $event_type = isset($_POST['event_type']) ? sanitize_key(wp_unslash($_POST['event_type'])) : '';
    $allowed = array('whatsapp', 'map', 'request_start');
    if (!in_array($event_type, $allowed, true)) {
        wp_send_json_error();
    }
    $source = '';
    if (!empty($_GET['utm_source'])) {
        $source = sanitize_text_field(wp_unslash($_GET['utm_source']));
    }
    motoshop_track_event($event_type, array(
        'path'       => isset($_POST['path']) ? sanitize_text_field(wp_unslash($_POST['path'])) : '',
        'product_id' => isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0,
        'source'     => $source,
    ));
    wp_send_json_success();
}
add_action('wp_ajax_motoshop_track', 'motoshop_ajax_track');
add_action('wp_ajax_nopriv_motoshop_track', 'motoshop_ajax_track');

function motoshop_login_redirect($redirect_to, $requested_redirect_to, $user) {
    if ($user instanceof WP_User && user_can($user, 'edit_motoshop_leads')) {
        return admin_url('admin.php?page=motoshop-dashboard');
    }
    return $redirect_to;
}
add_filter('login_redirect', 'motoshop_login_redirect', 10, 3);

/**
 * A focused admin hub for dealership operators and training sessions.
 */
function motoshop_register_admin_hub() {
    add_menu_page(
        __('MotoShop', 'motoshop'),
        __('MotoShop', 'motoshop'),
        'edit_motoshop_leads',
        'motoshop-dashboard',
        'motoshop_render_admin_dashboard',
        'dashicons-store',
        3
    );
    add_submenu_page('motoshop-dashboard', __('Ringkasan MotoShop', 'motoshop'), __('Ringkasan', 'motoshop'), 'edit_motoshop_leads', 'motoshop-dashboard', 'motoshop_render_admin_dashboard');
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
    add_submenu_page('motoshop-dashboard', __('Panduan Pengelola', 'motoshop'), __('Panduan', 'motoshop'), 'edit_motoshop_leads', 'motoshop-guide', 'motoshop_render_admin_guide');
    add_submenu_page('motoshop-dashboard', __('Statistik Website', 'motoshop'), __('Statistik', 'motoshop'), 'edit_motoshop_leads', 'motoshop-statistik', 'motoshop_render_admin_statistics');
}
add_action('admin_menu', 'motoshop_register_admin_hub', 5);

/**
 * Hide menus that are not part of the daily duty of the dealership staff.
 *
 * The administrator keeps the full WordPress and WooCommerce menus.
 */
function motoshop_trim_admin_menu_for_staff() {
    if (current_user_can('manage_options')) {
        return;
    }

    // Remove top-level menus that staff never need.
    foreach (array('edit.php?post_type=product', 'edit.php?post_type=shop_order', 'edit.php?post_type=shop_coupon', 'tools.php', 'themes.php', 'users.php', 'plugins.php', 'options-general.php') as $parent) {
        remove_menu_page($parent);
    }

    // WooCommerce panels that expose finances and configuration.
    foreach (array(
        'woocommerce',
        'wcs-settings',
        'wc-settings',
        'wc-reports',
        'wc-admin',
        'admin.php?page=wc-settings',
        'admin.php?page=wc-reports',
        'admin.php?page=wc-admin',
        'admin.php?page=wc-status',
        'admin.php?page=wc-analytics',
        'admin.php?page=wc-marketing',
        'admin.php?page=wc-orders',
        'edit.php?post_type=shop_order',
        'edit.php?post_type=shop_coupon',
        'edit.php?post_type=shop_order_placehold',
        'post-new.php?post_type=shop_order_placehold',
    ) as $page) {
        remove_submenu_page('woocommerce', $page);
    }

    // The whole WooCommerce panel is not part of the daily duty of staff.
    remove_menu_page('woocommerce');

    // WooCommerce POS (point of sale) is offline for this dealership.
    remove_menu_page('woocommerce-pos');
    remove_submenu_page('woocommerce', 'woocommerce-pos');
    remove_submenu_page('woocommerce', 'shop_order_placehold');
    remove_submenu_page('woocommerce', 'edit.php?post_type=shop_order_placehold');
    remove_submenu_page('woocommerce', 'post-new.php?post_type=shop_order_placehold');

    // Theme and plugin screens.
    remove_submenu_page('themes.php', 'themes.php');
    remove_submenu_page('themes.php', 'theme-editor.php');
    remove_submenu_page('themes.php', 'widgets.php');
}
add_action('admin_menu', 'motoshop_trim_admin_menu_for_staff', 99);

function motoshop_admin_count_leads($status = '') {
    $args = array('post_type' => 'motoshop_lead', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => 1);
    if ($status) {
        $args['meta_query'] = array(array('key' => '_motoshop_status', 'value' => $status));
    }
    $query = new WP_Query($args);
    return (int) $query->found_posts;
}

function motoshop_analytics_count($event_type, $days = 30) {
    global $wpdb;
    if (!motoshop_analytics_table_exists()) {
        return 0;
    }
    $since = gmdate('Y-m-d H:i:s', strtotime('-' . (int) $days . ' days', current_time('timestamp')));
    return (int) $wpdb->get_var($wpdb->prepare(
        'SELECT COUNT(*) FROM ' . motoshop_analytics_table_name() . ' WHERE event_type = %s AND created_at >= %s',
        $event_type,
        $since
    ));
}

function motoshop_render_admin_dashboard() {
    if (!current_user_can('edit_motoshop_leads')) {
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
            <a href="<?php echo esc_url(admin_url('admin.php?page=motoshop-statistik')); ?>"><span>Kunjungan 30 hari</span><strong><?php echo esc_html(motoshop_analytics_count('view')); ?></strong><small>Lihat statistik &#8594;</small></a>
        </div>
        <div class="motoshop-admin__grid">
            <section class="motoshop-admin__panel">
                <div class="motoshop-admin__panel-head"><div><span>AKSES CEPAT</span><h2>Pekerjaan utama</h2></div></div>
                <div class="motoshop-admin__actions">
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=product')); ?>"><i class="dashicons dashicons-plus-alt2"></i><strong>Tambah produk</strong><span>Nama, foto, kategori, harga, dan spesifikasi</span></a>
                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=motoshop_lead')); ?>"><i class="dashicons dashicons-whatsapp"></i><strong>Permintaan motor</strong><span>Hubungi calon pembeli dan ubah status</span></a>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=motoshop-statistik')); ?>"><i class="dashicons dashicons-chart-area"></i><strong>Statistik website</strong><span>Kunjungan, klik WhatsApp, dan produk yang diminati</span></a>
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
    if (!current_user_can('edit_motoshop_leads')) {
        return;
    }
    ?>
    <div class="wrap motoshop-admin motoshop-guide">
        <div class="motoshop-admin__hero"><div><p>PANDUAN OPERATOR</p><h1>Mengelola katalog &amp; permintaan</h1><span>Ikuti urutan ini saat pelatihan pengelola website.</span></div></div>
        <div class="motoshop-guide__layout">
            <nav>
                <a href="#alur">Alur website</a>
                <a href="#peran">Peran WordPress &amp; WooCommerce</a>
                <a href="#akun">Login &amp; akun</a>
                <a href="#tambah-produk">Tambah produk</a>
                <a href="#spesifikasi">Isi spesifikasi</a>
                <a href="#kategori">Atur kategori</a>
                <a href="#permintaan">Tindak lanjut pelanggan</a>
                <a href="#kunjungan">Jadwal kunjungan</a>
                <a href="#promosi">Konten promosi</a>
                <a href="#statistik">Statistik</a>
                <a href="#backup">Backup</a>
                <a href="#checklist">Checklist publikasi</a>
            </nav>
            <div>
                <section id="alur">
                    <span>01</span><h2>Alur website Cabang Ciledug</h2>
                    <ol>
                        <li>Pengunjung melihat katalog produk dan spesifikasinya.</li>
                        <li>Pengunjung memilih <strong>Ajukan permintaan</strong> atau <strong>Tanya via WhatsApp</strong>.</li>
                        <li>Permintaan yang dikirim melalui formulir tersimpan di menu <strong>MotoShop &gt; Permintaan Motor</strong>.</li>
                        <li>Sales menghubungi calon pelanggan dan memperbarui status tindak lanjut.</li>
                        <li>Transaksi tunai, kredit, pembayaran, dan serah terima tetap dilakukan di cabang.</li>
                    </ol>
                </section>
                <section id="peran">
                    <span>02</span><h2>Peran WordPress dan WooCommerce</h2>
                    <p><strong>WordPress</strong> adalah dasar sistem. Pada website ini WordPress berfungsi sebagai sistem manajemen konten, penyedia pembagian hak akses, pembuat formulir dan penyimpanan data calon pelanggan, serta penyedia statistik internal.</p>
                    <p><strong>WooCommerce</strong> berfungsi sebagai mesin katalog, bukan sebagai kasir. Struktur produk, kategori, atribut, galeri, pencarian, dan filter diambil dari WooCommerce. Tombol keranjang dihapus, checkout dialihkan ke katalog, dan harga dikosongkan agar tampil pesan Hubungi sales. Semua transaksi tetap dilakukan di cabang.</p>
                </section>
                <section id="akun">
                    <span>03</span><h2>Login dan keamanan akun</h2>
                    <ol>
                        <li>Gunakan akun pribadi, jangan berbagi akun administrator.</li>
                        <li>Role <strong>Operator MotoShop</strong> dapat menambah produk, kategori, promosi, dan membaca permintaan.</li>
                        <li>Role <strong>Sales MotoShop</strong> hanya membaca permintaan dan mengubah status tindak lanjut.</li>
                        <li>Menu WooCommerce, Plugin, Pengguna, Tampilan, dan Kasir tidak tampil bagi operator dan sales.</li>
                        <li>Ganti kata sandi pada login pertama dan jangan menuliskannya di dokumen bersama.</li>
                    </ol>
                </section>
                <section id="tambah-produk">
                    <span>04</span><h2>Menambahkan produk WooCommerce</h2>
                    <ol>
                        <li>Buka <strong>MotoShop &gt; Tambah Produk</strong>.</li>
                        <li>Isi nama motor dan deskripsi singkat sesuai data resmi VIAR.</li>
                        <li>Pilih kategori produk pada panel kanan.</li>
                        <li>Pada <strong>Data produk</strong>, pilih <strong>Produk sederhana</strong>. <strong>Harga dikosongkan</strong> karena harga diinformasikan oleh sales cabang.</li>
                        <li>Unggah <strong>Gambar produk</strong> utama dan tambahkan galeri beberapa sudut.</li>
                        <li>Isi <strong>Atribut</strong> untuk spesifikasi, centang <strong>Tampilkan di halaman produk</strong>, lalu terbitkan.</li>
                    </ol>
                </section>
                <section id="spesifikasi">
                    <span>05</span><h2>Menyusun tabel spesifikasi</h2>
                    <p>Buka tab <strong>Atribut</strong> pada kotak <strong>Data produk</strong>. Tambahkan satu atribut untuk setiap data, misalnya Tipe Mesin, Volume Langkah, Daya Maksimum, Transmisi, Kapasitas Tangki, Dimensi, dan Berat. Seluruh data spesifikasi harus diambil dari sumber resmi VIAR.</p>
                </section>
                <section id="kategori">
                    <span>06</span><h2>Mengatur kategori</h2>
                    <p>Buka <strong>MotoShop &gt; Kategori Produk</strong>. Kategori yang dipakai saat ini: Motor Niaga, Motor Listrik, Sepeda Listrik, Motor Trail, ATV, dan Kendaraan Agrikultur. Kategori ini otomatis tampil pada filter katalog di beranda.</p>
                </section>
                <section id="permintaan">
                    <span>07</span><h2>Menindaklanjuti calon pembeli</h2>
                    <ol>
                        <li>Buka <strong>MotoShop &gt; Permintaan Motor</strong>.</li>
                        <li>Klik nomor WhatsApp untuk menghubungi pelanggan.</li>
                        <li>Buka detail permintaan dan ubah status: <strong>Baru</strong>, <strong>Dihubungi</strong>, <strong>Menunggu Konfirmasi</strong>, <strong>Janji Kunjungan</strong>, <strong>Selesai</strong>, atau <strong>Batal</strong>.</li>
                        <li>Catat hasil komunikasi pada kolom status dan isi catatan bila diperlukan.</li>
                    </ol>
                </section>
                <section id="kunjungan">
                    <span>08</span><h2>Menangani jadwal kunjungan</h2>
                    <p>Calon pelanggan dapat mengisi tanggal dan jam yang diinginkan pada formulir. Website hanya mencatat preferensi. Jadwal final ditentukan sales melalui WhatsApp, lalu ditandai dengan status <strong>Janji Kunjungan</strong> atau <strong>Menunggu Konfirmasi</strong>.</p>
                </section>
                <section id="promosi">
                    <span>09</span><h2>Membuat konten promosi</h2>
                    <ol>
                        <li>Buka <strong>Menu &gt; Tulisan baru</strong> atau <strong>Tambah Produk</strong> untuk artikel promosi.</li>
                        <li>Tulis judul, pilih kategori, unggah foto produk, lalu tambahkan tautan ke halaman produk atau WhatsApp.</li>
                        <li>Tambahkan parameter sumber agar asal pengunjung dapat dilacak, contoh: <code>?utm_source=instagram&amp;utm_medium=social</code>.</li>
                        <li>Bagikan tautannya ke kanal media sosial mitra.</li>
                    </ol>
                </section>
                <section id="statistik">
                    <span>10</span><h2>Menilai performa website</h2>
                    <p>Buka <strong>MotoShop &gt; Statistik</strong> untuk melihat jumlah kunjungan, klik WhatsApp, permintaan masuk, halaman yang paling sering dibuka, produk yang paling diminati, dan sumber pengunjung. Data ini dipakai sebagai bahan evaluasi pemasaran dan laporan akhir.</p>
                </section>
                <section id="backup">
                    <span>11</span><h2>Backup dasar</h2>
                    <ol>
                        <li>Buka <strong>Alat &gt; Ekspor &gt; Semua</strong> untuk menyalin seluruh konten ke file XML.</li>
                        <li>Salin folder <strong>wp-content/uploads</strong> dan file <strong>wp-config.php</strong> ke folder pencadangan.</li>
                        <li>Ekspor database melalui phpMyAdmin.</li>
                        <li>Simpan berkas backup di lokasi berbeda dan catat tanggal pencadangan.</li>
                    </ol>
                </section>
                <section id="checklist">
                    <span>12</span><h2>Checklist sebelum terbit</h2>
                    <ul>
                        <li>Nama dan kategori motor benar.</li>
                        <li>Foto jelas, latar rapi, dan tidak pecah.</li>
                        <li>Harga tetap dikosongkan dan dikonfirmasi sales.</li>
                        <li>Spesifikasi berasal dari sumber resmi VIAR.</li>
                        <li>Deskripsi tidak menjanjikan promo yang sudah berakhir.</li>
                        <li>Formulir sudah diuji dan muncul di menu Permintaan Motor.</li>
                    </ul>
                    <p><strong>Catatan:</strong> website ini adalah katalog dan alat pencatatan calon pelanggan. Pembayaran, persetujuan kredit, dan serah terima kendaraan tetap dilakukan di cabang sesuai prosedur perusahaan.</p>
                </section>
            </div>
        </div>
    </div>
    <?php
}

function motoshop_render_admin_statistics() {
    if (!current_user_can('edit_motoshop_leads')) {
        return;
    }

    global $wpdb;
    $table = motoshop_analytics_table_name();
    $has_table = motoshop_analytics_table_exists();
    $days = isset($_GET['periode']) ? max(7, min(90, (int) $_GET['periode'])) : 30;
    $since = gmdate('Y-m-d H:i:s', strtotime('-' . $days . ' days', current_time('timestamp')));

    $count = function ($type) use ($wpdb, $table, $has_table, $since) {
        if (!$has_table) {
            return 0;
        }
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$table} WHERE event_type = %s AND created_at >= %s",
            $type,
            $since
        ));
    };

    $views = $count('view');
    $whatsapp = $count('whatsapp');
    $map = $count('map');
    $request_start = $count('request_start');
    $leads = (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'motoshop_lead' AND post_date >= %s",
        $since
    ));

    $top_paths = $has_table ? $wpdb->get_results($wpdb->prepare(
        "SELECT path, COUNT(*) AS total FROM {$table} WHERE event_type = 'view' AND created_at >= %s GROUP BY path ORDER BY total DESC LIMIT 10",
        $since
    )) : array();

    $top_products = $has_table ? $wpdb->get_results($wpdb->prepare(
        "SELECT product_id, COUNT(*) AS total FROM {$table} WHERE event_type = 'view' AND product_id > 0 AND created_at >= %s GROUP BY product_id ORDER BY total DESC LIMIT 10",
        $since
    )) : array();

    $sources = $has_table ? $wpdb->get_results($wpdb->prepare(
        "SELECT source, COUNT(*) AS total FROM {$table} WHERE source <> '' AND created_at >= %s GROUP BY source ORDER BY total DESC LIMIT 10",
        $since
    )) : array();

    $status_counts = array();
    foreach (motoshop_lead_statuses() as $status => $label) {
        $status_counts[$status] = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_motoshop_status' AND meta_value = %s",
            $status
        ));
    }
    ?>
    <div class="wrap motoshop-admin motoshop-stats">
        <div class="motoshop-admin__hero">
            <div>
                <p>STATISTIK WEBSITE</p>
                <h1>Performa kanal digital cabang</h1>
                <span>Data kunjungan, klik WhatsApp, dan permintaan calon pembeli dalam <?php echo esc_html($days); ?> hari terakhir.</span>
            </div>
            <div class="motoshop-stats__period">
                <?php foreach (array(7, 30, 90) as $period) : ?>
                    <a class="button <?php echo $days === $period ? 'button-primary' : ''; ?>" href="<?php echo esc_url(admin_url('admin.php?page=motoshop-statistik&periode=' . $period)); ?>"><?php echo esc_html($period); ?> hari</a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="motoshop-admin__stats">
            <div class="motoshop-admin__stats-card"><span>Kunjungan halaman</span><strong><?php echo esc_html($views); ?></strong><small>Seluruh halaman</small></div>
            <div class="motoshop-admin__stats-card"><span>Klik WhatsApp</span><strong><?php echo esc_html($whatsapp); ?></strong><small>Permintaan konsultasi</small></div>
            <div class="motoshop-admin__stats-card"><span>Permintaan masuk</span><strong><?php echo esc_html($leads); ?></strong><small>Formulir website</small></div>
            <div class="motoshop-admin__stats-card"><span>Buka lokasi</span><strong><?php echo esc_html($map); ?></strong><small>Klik peta cabang</small></div>
        </div>

        <div class="motoshop-admin__grid">
            <section class="motoshop-admin__panel">
                <div class="motoshop-admin__panel-head"><div><span>PALING SERING DIBUKA</span><h2>Halaman</h2></div></div>
                <ul class="motoshop-stats__list">
                    <?php if ($top_paths) : foreach ($top_paths as $row) : ?>
                        <li><span><?php echo esc_html($row->path); ?></span><strong><?php echo esc_html($row->total); ?></strong></li>
                    <?php endforeach; else : ?><li><span>Belum ada data kunjungan.</span><strong>0</strong></li><?php endif; ?>
                </ul>
            </section>
            <section class="motoshop-admin__panel">
                <div class="motoshop-admin__panel-head"><div><span>MINAT PELANGGAN</span><h2>Produk</h2></div></div>
                <ul class="motoshop-stats__list">
                    <?php if ($top_products) : foreach ($top_products as $row) : $product = wc_get_product($row->product_id); ?>
                        <li><span><?php echo esc_html($product ? $product->get_name() : 'Produk tidak ditemukan'); ?></span><strong><?php echo esc_html($row->total); ?></strong></li>
                    <?php endforeach; else : ?><li><span>Belum ada produk dilihat.</span><strong>0</strong></li><?php endif; ?>
                </ul>
            </section>
            <section class="motoshop-admin__panel">
                <div class="motoshop-admin__panel-head"><div><span>SUMBER PENGUNJUNG</span><h2>Asal trafik</h2></div></div>
                <ul class="motoshop-stats__list">
                    <?php if ($sources) : foreach ($sources as $row) : ?>
                        <li><span><?php echo esc_html($row->source); ?></span><strong><?php echo esc_html($row->total); ?></strong></li>
                    <?php endforeach; else : ?><li><span>Belum ada sumber tercatat.</span><strong>0</strong></li><?php endif; ?>
                </ul>
            </section>
            <section class="motoshop-admin__panel">
                <div class="motoshop-admin__panel-head"><div><span>TINDAK LANJUT</span><h2>Status permintaan</h2></div><a href="<?php echo esc_url(admin_url('edit.php?post_type=motoshop_lead')); ?>">Kelola</a></div>
                <ul class="motoshop-stats__list">
                    <?php foreach ($status_counts as $status => $total) : ?>
                        <li><span><?php echo esc_html($status); ?></span><strong><?php echo esc_html($total); ?></strong></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </div>
        <p class="motoshop-stats__note"><?php esc_html_e('Data statistik ini dihimpun langsung oleh website MotoShop, sehingga dapat dipakai sebagai bukti evaluasi pemasaran tanpa aplikasi tambahan.', 'motoshop'); ?></p>
    </div>
    <?php
}

function motoshop_admin_assets($hook) {
    if (!in_array($hook, array('toplevel_page_motoshop-dashboard', 'motoshop_page_motoshop-guide', 'motoshop_page_motoshop-statistik'), true)) {
        return;
    }
    wp_register_style('motoshop-admin-inline', false, array(), MOTOSHOP_VERSION);
    wp_enqueue_style('motoshop-admin-inline');
    wp_add_inline_style('motoshop-admin-inline', '
        .motoshop-admin{max-width:1220px;color:#132235}.motoshop-admin__hero{display:flex;align-items:end;justify-content:space-between;gap:30px;margin:22px 0;padding:34px 38px;border-left:7px solid #d71920;border-radius:5px;background:#0b1f33;color:#fff}.motoshop-admin__hero p,.motoshop-admin__panel-head span,.motoshop-guide section>span{margin:0 0 6px;color:#ff555b;font-size:11px;font-weight:800;letter-spacing:.13em}.motoshop-admin__hero h1{margin:0 0 7px;color:#fff;font-size:32px}.motoshop-admin__hero span{color:#aebdca}.motoshop-admin__stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(215px,1fr));gap:16px;margin:0 0 16px}.motoshop-admin__stats>a,.motoshop-admin__panel{border:1px solid #dce4ea;border-radius:5px;background:#fff;box-shadow:0 8px 25px rgba(11,31,51,.05)}.motoshop-admin__stats>a{display:grid;padding:22px;color:#132235;text-decoration:none}.motoshop-admin__stats span{color:#627181}.motoshop-admin__stats strong{margin:5px 0;font-size:34px}.motoshop-admin__stats small{color:#d71920;font-weight:700}.motoshop-admin__grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}.motoshop-admin__panel{padding:25px}.motoshop-admin__panel-head{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #dce4ea;padding-bottom:16px}.motoshop-admin__panel-head h2{margin:0;font-size:20px}.motoshop-admin__actions{display:grid;gap:10px;margin-top:16px}.motoshop-admin__actions>a{display:grid;grid-template-columns:38px 1fr;column-gap:10px;padding:14px;border:1px solid #e2e8ed;border-radius:4px;color:#132235;text-decoration:none}.motoshop-admin__actions i{grid-row:1/3;color:#d71920}.motoshop-admin__actions span{color:#627181}.motoshop-admin__leads>a{display:grid;grid-template-columns:1fr auto;gap:2px 10px;padding:14px 0;border-bottom:1px solid #e2e8ed;color:#132235;text-decoration:none}.motoshop-admin__leads span{color:#627181}.motoshop-admin__leads em{grid-row:1/3;grid-column:2;align-self:center;border-radius:30px;background:#edf2f5;padding:4px 9px;font-size:11px;font-style:normal}.motoshop-guide__layout{display:grid;grid-template-columns:220px 1fr;gap:20px}.motoshop-guide nav{position:sticky;top:50px;height:max-content;display:grid;border:1px solid #dce4ea;background:#fff}.motoshop-guide nav a{padding:13px 16px;border-bottom:1px solid #dce4ea;color:#132235;text-decoration:none;font-weight:600}.motoshop-guide section{margin-bottom:16px;border:1px solid #dce4ea;border-radius:5px;background:#fff;padding:28px}.motoshop-guide section h2{margin-top:0}.motoshop-guide section li{margin-bottom:8px}.motoshop-admin__stats-card{border:1px solid #dce4ea;border-radius:5px;background:#fff;box-shadow:0 8px 25px rgba(11,31,51,.05);padding:22px}.motoshop-stats__period{display:flex;gap:8px}.motoshop-stats__list{margin:16px 0 0;padding:0;list-style:none}.motoshop-stats__list li{display:flex;justify-content:space-between;gap:12px;padding:10px 0;border-bottom:1px solid #e2e8ed}.motoshop-stats__list span{color:#42566a}.motoshop-stats__list strong{color:#d71920}.motoshop-stats__note{margin-top:16px;color:#627181}@media(max-width:782px){.motoshop-admin__stats,.motoshop-admin__grid,.motoshop-guide__layout{grid-template-columns:1fr}.motoshop-admin__hero{align-items:start;flex-direction:column}.motoshop-guide nav{position:static}}
    ');
}
add_action('admin_enqueue_scripts', 'motoshop_admin_assets');
