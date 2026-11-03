<?php
/**
 * Finalise the catalogue after an official import.
 *
 * - merges products that share the same display name (keeps the record that
 *   already owns banner, feature gallery, and product gallery images),
 * - copies the official specification, description, and source link into it,
 * - makes sure every product is published, priced on request, and has a
 *   thumbnail plus a campaign banner for the product detail page.
 *
 * Run: php wp-content/themes/motoshop/scripts/finalize-catalog.php
 */

if ('cli' !== PHP_SAPI) {
    exit("CLI only.\n");
}

define('WP_USE_THEMES', false);
require_once dirname(__DIR__, 4) . '/wp-load.php';

if (!class_exists('WooCommerce')) {
    exit("WooCommerce is not active.\n");
}

function motoshop_normalized_name($name) {
    $name = strtolower(preg_replace('/[^a-z0-9]+/i', '', $name));
    return $name;
}

function motoshop_product_richness($product_id) {
    $score = 0;
    foreach (array('_motoshop_banner_id', '_motoshop_feature_gallery', '_product_image_gallery') as $key) {
        $value = get_post_meta($product_id, $key, true);
        if ($value && 'a:0:{}' !== $value) {
            $score += 10;
        }
    }
    $thumbnail = get_post_thumbnail_id($product_id);
    if ($thumbnail) {
        $score += 5;
    }
    return $score;
}

$products = get_posts(array(
    'post_type'      => 'product',
    'post_status'    => array('publish', 'draft', 'private'),
    'numberposts'    => -1,
    'orderby'        => 'ID',
    'order'          => 'ASC',
));

$groups = array();
foreach ($products as $post) {
    $key = motoshop_normalized_name($post->post_title);
    if ('' === $key) {
        continue;
    }
    $groups[$key][] = $post;
}

$merged = 0;
$deleted = 0;

foreach ($groups as $key => $members) {
    if (count($members) < 2) {
        continue;
    }

    usort($members, static function ($a, $b) {
        $score = motoshop_product_richness($b->ID) <=> motoshop_product_richness($a->ID);
        return 0 !== $score ? $score : $a->ID <=> $b->ID;
    });

    $keeper = array_shift($members);

    foreach ($members as $duplicate) {
        // Prefer the richer media set, but keep the official specification.
        $duplicate_product = wc_get_product($duplicate->ID);
        $keeper_product   = wc_get_product($keeper->ID);

        if ($duplicate_product && $keeper_product) {
            $keeper_attributes = $keeper_product->get_attributes();
            $duplicate_attributes = $duplicate_product->get_attributes();
            if (count($duplicate_attributes) > count($keeper_attributes)) {
                $keeper_product->set_attributes($duplicate_attributes);
            }

            $keeper_source = get_post_meta($keeper->ID, '_motoshop_source_url', true);
            if (!$keeper_source) {
                $duplicate_source = get_post_meta($duplicate->ID, '_motoshop_source_url', true);
                if ($duplicate_source) {
                    update_post_meta($keeper->ID, '_motoshop_source_url', $duplicate_source);
                }
            }

            $keeper_description = trim($keeper_product->get_description());
            $duplicate_description = trim($duplicate_product->get_description());
            if (strlen($duplicate_description) > strlen($keeper_description)) {
                $keeper_product->set_description($duplicate_description);
            }

            $keeper_product->set_status('publish');
            $keeper_product->set_regular_price('');
            $keeper_product->save();
        }

        wp_delete_post($duplicate->ID, true);
        $deleted++;
        echo "Removed duplicate #{$duplicate->ID} ({$duplicate->post_title}) in favour of #{$keeper->ID}\n";
        $merged++;
    }
}

// Make sure every remaining product is complete for the website.
$fixed = 0;
foreach (get_posts(array('post_type' => 'product', 'post_status' => array('publish', 'draft', 'private'), 'numberposts' => -1)) as $post) {
    $product = wc_get_product($post->ID);
    if (!$product) {
        continue;
    }

    $changed = false;

    if ('publish' !== $product->get_status()) {
        $product->set_status('publish');
        $changed = true;
    }
    if ($product->get_regular_price()) {
        $product->set_regular_price('');
        $changed = true;
    }
    if ('instock' !== $product->get_stock_status()) {
        $product->set_stock_status('instock');
        $changed = true;
    }
    if (!$product->get_short_description()) {
        $product->set_short_description('Kendaraan VIAR yang dilayani oleh Cabang Viar Mahkota Ciledug. Hubungi sales untuk harga terbaru, ketersediaan unit, dan pilihan pembelian tunai atau kredit.');
        $changed = true;
    }

    if ($changed) {
        $product->save();
        $fixed++;
    }

    // Banner for the product detail page.
    $thumbnail_id = get_post_thumbnail_id($post->ID);
    $banner_id = absint(get_post_meta($post->ID, '_motoshop_banner_id', true));
    if ($thumbnail_id && !$banner_id) {
        update_post_meta($post->ID, '_motoshop_banner_id', $thumbnail_id);
    }

    // Availability note used on the product detail page.
    if (!get_post_meta($post->ID, '_motoshop_availability_note', true)) {
        update_post_meta($post->ID, '_motoshop_availability_note', 'Ketersediaan unit dikonfirmasi oleh sales cabang.');
    }
}

// Remove the unused default product category.
$unused = get_term_by('slug', 'tanpa-kategori', 'product_cat');
if ($unused) {
    $products_in_term = get_posts(array(
        'post_type'      => 'product',
        'post_status'    => 'any',
        'numberposts'    => -1,
        'fields'         => 'ids',
        'tax_query'      => array(array('taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $unused->term_id)),
    ));
    if (!$products_in_term) {
        wp_delete_term($unused->term_id, 'product_cat');
        echo "Removed unused category: Tanpa kategori\n";
    }
}

echo "Finalisasi selesai. Duplikat dihapus: {$deleted}, produk diperbarui: {$fixed}\n";
