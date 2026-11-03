<?php
/**
 * Synchronise product material from a structured local folder into WooCommerce.
 *
 * Usage:
 * php wp-content/themes/motoshop/scripts/sync-local-catalog.php "C:\\path\\to\\Produk VIAR"
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

$source_root = isset($argv[1]) ? rtrim($argv[1], "\\/") : 'C:\\Users\\fariz\\Downloads\\Produk VIAR';
if (!is_dir($source_root)) {
    exit("Product material folder was not found: {$source_root}\n");
}
$requested_products = isset($argv[2])
    ? array_filter(array_map('trim', explode(',', $argv[2])))
    : array();

$catalog = array(
    array('folder' => 'Agri/R5Ke', 'name' => 'VIAR R5Ke', 'category' => 'Kendaraan Agrikultur'),
    array('folder' => 'Agri/VXT 350 s (4x4)', 'name' => 'VIAR VXT 350 S (4x4)', 'category' => 'Kendaraan Agrikultur'),
    array('folder' => 'E-Bike/Akasha 3', 'name' => 'VIAR Akasha 3', 'category' => 'Sepeda Listrik'),
    array('folder' => 'E-Bike/C3x', 'name' => 'VIAR C3X', 'category' => 'Sepeda Listrik'),
    array('folder' => 'E-Motor/EV 1', 'name' => 'VIAR EV1', 'category' => 'Motor Listrik'),
    array('folder' => 'E-Motor/N1', 'name' => 'VIAR N1', 'category' => 'Motor Listrik'),
    array('folder' => 'E-Motor/N2', 'name' => 'VIAR N2', 'category' => 'Motor Listrik'),
    array('folder' => 'E-Motor/New Q1L', 'name' => 'VIAR New Q1L', 'category' => 'Motor Listrik'),
    array('folder' => 'E-Motor/NX', 'name' => 'VIAR NX', 'category' => 'Motor Listrik'),
    array('folder' => 'Off-Road/CrossX 150 XF', 'name' => 'VIAR CrossX 150 XF', 'category' => 'Motor Trail', 'legacy_names' => array('VIAR Cross XF 150')),
    array('folder' => 'Off-Road/Mini Trail 100', 'name' => 'VIAR Mini Trail 100', 'category' => 'Motor Trail'),
    array('folder' => 'Off-Road/Mini Trail 70', 'name' => 'VIAR Mini Trail 70', 'category' => 'Motor Trail'),
    array('folder' => 'Vitron/Vitron H20', 'name' => 'VIAR Vitron H20', 'category' => 'Sepeda Listrik'),
    array('folder' => 'Vitron/Vitron o2', 'name' => 'VIAR Vitron O2', 'category' => 'Sepeda Listrik'),
);

function motoshop_local_normalize_name($name) {
    $name = preg_replace('/^viar\s+/i', '', $name);
    return strtolower(preg_replace('/[^a-z0-9]+/i', '', $name));
}

function motoshop_local_product($name, $sku) {
    $product_id = wc_get_product_id_by_sku($sku);
    if ($product_id) {
        return wc_get_product($product_id);
    }

    $wanted = motoshop_local_normalize_name($name);
    $products = wc_get_products(array('limit' => -1, 'status' => array('publish', 'draft', 'private')));
    foreach ($products as $product) {
        if (motoshop_local_normalize_name($product->get_name()) === $wanted) {
            return $product;
        }
    }

    return new WC_Product_Simple();
}

function motoshop_local_files($directory, $matcher) {
    if (!is_dir($directory)) {
        return array();
    }
    $files = array();
    foreach (new DirectoryIterator($directory) as $file) {
        if ($file->isFile() && preg_match($matcher, $file->getFilename())) {
            $files[] = $file->getPathname();
        }
    }
    natcasesort($files);
    return array_values($files);
}

function motoshop_local_first_file($directory, $prefix) {
    $files = motoshop_local_files($directory, '/^' . preg_quote($prefix, '/') . '.*\.(?:jpe?g|png|webp)$/i');
    return $files ? reset($files) : '';
}

function motoshop_local_spec_file($directory) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if ($file->isFile() && preg_match('/^spesifikasi.*\.txt$/i', $file->getFilename())) {
            return $file->getPathname();
        }
    }
    return '';
}

function motoshop_local_spec_label($label) {
    $label = trim(preg_replace('/\s+/', ' ', $label));
    $replacements = array(
        '/^power output$/i' => 'Power Motor',
        '/^kecepatan max\.?$/i' => 'Kecepatan Maksimum',
        '/^kecepatan maks\.?$/i' => 'Kecepatan Maksimum',
        '/^jangkauan berkendara$/i' => 'Jarak Tempuh',
        '/^p\s*x\s*l\s*x\s*t$/i' => 'Dimensi',
        '/^panjang\s*x\s*lebar\s*x\s*tinggi$/i' => 'Dimensi',
        '/^panjang\s*x\s*lebar\s*x\s*ringgi$/i' => 'Dimensi',
        '/^l\s*x\s*w\s*x\s*h$/i' => 'Dimensi',
        '/^dimensi unit p\s*x\s*l\s*x\s*t$/i' => 'Dimensi Unit',
        '/^dimensi kargo\/bak p\s*x\s*l\s*x\s*t$/i' => 'Dimensi Kargo/Bak',
        '/^tors[!i]\s*maksimal$/i' => 'Torsi Maksimal',
        '/^pengdigin mesin$/i' => 'Pendingin Mesin',
        '/^fuel sistem$/i' => 'Sistem Bahan Bakar',
        '/^suspens\/ dep(an)?$/i' => 'Suspensi Depan',
        '/^suspens\/ belakang$/i' => 'Suspensi Belakang',
        '/^depan belakang$/i' => 'Rem Depan & Belakang',
    );
    foreach ($replacements as $pattern => $replacement) {
        if (preg_match($pattern, $label)) {
            return $replacement;
        }
    }
    if (function_exists('mb_strtoupper') && mb_strtoupper($label, 'UTF-8') === $label) {
        return mb_convert_case(strtolower($label), MB_CASE_TITLE, 'UTF-8');
    }
    return $label;
}

function motoshop_local_spec_value($value) {
    $value = trim(preg_replace('/\s+/', ' ', $value));
    $value = preg_replace('/\b(?:max\.?\s*power|maks\.?\s*power)\b/i', 'maksimum', $value);
    $value = preg_replace('/\bwatts?\b/i', 'W', $value);
    $value = preg_replace('/(?<=\d)\s*w\b/i', ' W', $value);
    $value = preg_replace('/\bkm\s*\/\s*jam\b/i', 'km/jam', $value);
    $value = preg_replace('/\bkm\b/i', 'km', $value);
    $value = preg_replace('/\bkg\b/i', 'kg', $value);
    $value = preg_replace('/\bliter\b/i', 'liter', $value);
    $value = preg_replace('/(?<=\d)\s*X\s*(?=\d)/', ' x ', $value);
    return rtrim($value, '* ');
}

function motoshop_local_specs($file) {
    if (!$file || !is_readable($file)) {
        return array();
    }
    $rows = array();
    $last_label = '';
    $lines = preg_split('/\R/u', preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents($file)));
    foreach ($lines as $line) {
        $line = trim($line);
        if (!$line || preg_match('/^spesifikasi(?:\s|$)/i', $line)) {
            continue;
        }

        if (false !== strpos($line, "\t")) {
            list($label, $value) = array_pad(preg_split('/\t+/', $line, 2), 2, '');
        } elseif (preg_match('/^([^:]+)\s*:\s*(.+)$/u', $line, $parts)) {
            $label = $parts[1];
            $value = $parts[2];
        } else {
            if ($last_label && preg_match('/^[\(\-]/', $line)) {
                $rows[$last_label] .= ' ' . $line;
            }
            continue;
        }

        $label = motoshop_local_spec_label($label);
        $value = motoshop_local_spec_value(preg_replace('/^\s*:\s*/', '', $value));
        if (!$label && $last_label && $value) {
            $rows[$last_label] .= ' ' . $value;
        } elseif ($label && $value) {
            $rows[$label] = $value;
            $last_label = $label;
        }
    }
    return $rows;
}

function motoshop_local_attachment($path, $parent_id, $root) {
    if (!$path || !is_readable($path)) {
        return 0;
    }
    $relative = ltrim(str_replace(array($root, '\\'), array('', '/'), $path), '/');
    $asset_key = sha1($relative . '|' . hash_file('sha1', $path));
    $existing = get_posts(array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_key' => '_motoshop_asset_key',
        'meta_value' => $asset_key,
    ));
    if ($existing) {
        return (int) reset($existing);
    }

    $filename = sanitize_file_name(basename($path));
    $upload = wp_upload_bits($filename, null, file_get_contents($path));
    if (!empty($upload['error'])) {
        echo 'WARN  ' . $upload['error'] . "\n";
        return 0;
    }

    $filetype = wp_check_filetype($upload['file']);
    $title = trim(preg_replace('/[-_]+/', ' ', pathinfo($filename, PATHINFO_FILENAME)));
    $attachment_id = wp_insert_attachment(array(
        'post_mime_type' => $filetype['type'],
        'post_title' => $title,
        'post_status' => 'inherit',
        'post_parent' => $parent_id,
    ), $upload['file'], $parent_id);
    if (is_wp_error($attachment_id)) {
        return 0;
    }
    wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));
    update_post_meta($attachment_id, '_motoshop_asset_key', $asset_key);
    update_post_meta($attachment_id, '_motoshop_source_path', $relative);
    return (int) $attachment_id;
}

function motoshop_local_attributes($specs) {
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
    return $attributes;
}

function motoshop_local_summary($name, $category, $specs) {
    $intro = array(
        'Kendaraan Agrikultur' => 'Kendaraan agrikultur untuk mendukung pekerjaan lapangan dan kebutuhan angkut.',
        'Motor Listrik' => 'Motor listrik untuk mobilitas harian yang praktis dan rendah emisi.',
        'Motor Trail' => 'Motor off-road untuk aktivitas medan tanah dan penggunaan rekreasi.',
        'Sepeda Listrik' => 'Sepeda listrik praktis untuk perjalanan jarak dekat dan aktivitas harian.',
    );
    $text = isset($intro[$category]) ? $intro[$category] : 'Kendaraan VIAR untuk kebutuhan mobilitas dan usaha.';
    $highlights = array_slice($specs, 0, 2, true);
    if ($highlights) {
        $parts = array();
        foreach ($highlights as $label => $value) {
            $parts[] = $label . ' ' . $value;
        }
        $text .= ' ' . implode(' dan ', $parts) . '.';
    }
    return $text;
}

$created = 0;
$updated = 0;
foreach ($catalog as $item) {
    if ($requested_products) {
        $requested_normalized = array_map('motoshop_local_normalize_name', $requested_products);
        if (!in_array(motoshop_local_normalize_name($item['name']), $requested_normalized, true)) {
            continue;
        }
    }
    $directory = $source_root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $item['folder']);
    if (!is_dir($directory)) {
        echo "SKIP  {$item['name']} (folder missing)\n";
        continue;
    }

    $sku = 'VIAR-LOCAL-' . strtoupper(str_replace('-', '', sanitize_title($item['name'])));
    $product = motoshop_local_product($item['name'], $sku);
    $is_new = !$product->get_id();
    $specs = motoshop_local_specs(motoshop_local_spec_file($directory));

    $product->set_name($item['name']);
    if (!$product->get_sku()) {
        $product->set_sku($sku);
    }
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_stock_status('instock');
    $product->set_manage_stock(false);
    $product->set_short_description(motoshop_local_summary($item['name'], $item['category'], $specs));
    $product->set_description(sprintf('%s menampilkan material produk dan spesifikasi yang tersedia pada katalog dealer.', $item['name']));
    $product->set_attributes(motoshop_local_attributes($specs));

    $term = term_exists($item['category'], 'product_cat');
    if (!$term) {
        $term = wp_insert_term($item['category'], 'product_cat');
    }
    if (!is_wp_error($term)) {
        $product->set_category_ids(array((int) (is_array($term) ? $term['term_id'] : $term)));
    }

    $product_id = $product->save();
    $thumbnail = motoshop_local_first_file($directory, 'Thumbnail');
    $banner = motoshop_local_first_file($directory, 'Banner');
    $feature_files = motoshop_local_files($directory . DIRECTORY_SEPARATOR . 'Fitur', '/\.(?:jpe?g|png|webp)$/i');
    $colour_files = motoshop_local_files($directory . DIRECTORY_SEPARATOR . 'Warna', '/\.(?:jpe?g|png|webp)$/i');

    $thumbnail_id = motoshop_local_attachment($thumbnail, $product_id, $source_root);
    $banner_id = motoshop_local_attachment($banner, $product_id, $source_root);
    $feature_ids = array_values(array_filter(array_map(function ($path) use ($product_id, $source_root) {
        return motoshop_local_attachment($path, $product_id, $source_root);
    }, $feature_files)));
    $colour_ids = array_values(array_filter(array_map(function ($path) use ($product_id, $source_root) {
        return motoshop_local_attachment($path, $product_id, $source_root);
    }, $colour_files)));

    $product = wc_get_product($product_id);
    if ($thumbnail_id) {
        $product->set_image_id($thumbnail_id);
    }
    $product->set_gallery_image_ids($colour_ids);
    $product->update_meta_data('_motoshop_banner_id', $banner_id);
    $product->update_meta_data('_motoshop_feature_gallery', $feature_ids);
    $product->update_meta_data('_motoshop_material_folder', $item['folder']);
    $product->save();

    if (!empty($item['legacy_names'])) {
        $all_products = wc_get_products(array('limit' => -1, 'status' => array('publish', 'draft', 'private')));
        foreach ($all_products as $legacy_product) {
            if ((int) $legacy_product->get_id() === (int) $product_id) {
                continue;
            }
            foreach ($item['legacy_names'] as $legacy_name) {
                if (motoshop_local_normalize_name($legacy_product->get_name()) === motoshop_local_normalize_name($legacy_name)) {
                    $legacy_product->set_status('draft');
                    $legacy_product->save();
                    printf("DRAFT duplicate: %s\n", $legacy_product->get_name());
                }
            }
        }
    }

    $is_new ? $created++ : $updated++;
    printf("%s  %-24s specs:%02d features:%02d colours:%02d\n", $is_new ? 'ADD ' : 'SYNC', $item['name'], count($specs), count($feature_ids), count($colour_ids));
}

echo "\nFinished. Created: {$created}; updated: {$updated}.\n";
