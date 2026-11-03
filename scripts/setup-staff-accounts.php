<?php
/**
 * Prepare the staff accounts used during the training and mentoring phase.
 *
 * Run: php wp-content/themes/motoshop/scripts/setup-staff-accounts.php
 */

if ('cli' !== PHP_SAPI) {
    exit("CLI only.\n");
}

define('WP_USE_THEMES', false);
require_once dirname(__DIR__, 4) . '/wp-load.php';

$accounts = array(
    array(
        'login' => 'motoshop.operator',
        'email' => 'operator.motoshop@ciledug.test',
        'name'  => 'Operator MotoShop',
        'role'  => 'motoshop_operator',
        'pass'  => 'Operator#Ciledug2026',
    ),
    array(
        'login' => 'motoshop.sales',
        'email' => 'sales.motoshop@ciledug.test',
        'name'  => 'Sales MotoShop',
        'role'  => 'motoshop_sales',
        'pass'  => 'Sales#Ciledug2026',
    ),
);

foreach ($accounts as $account) {
    $user = get_user_by('login', $account['login']);

    if (!$user) {
        $user_id = wp_insert_user(array(
            'user_login'   => $account['login'],
            'user_email'   => $account['email'],
            'user_pass'    => $account['pass'],
            'display_name' => $account['name'],
            'first_name'   => $account['name'],
            'role'         => $account['role'],
        ));
        if (is_wp_error($user_id)) {
            echo "Gagal membuat akun {$account['login']}: {$user_id->get_error_message()}\n";
            continue;
        }
        echo "Dibuat: {$account['login']} ({$account['role']})\n";
        continue;
    }

    wp_set_password($account['pass'], $user->ID);
    $user->set_role($account['role']);
    echo "Diperbarui: {$account['login']} ({$account['role']})\n";
}

echo "Akun staf siap dipakai untuk pelatihan. Password sementara wajib diganti oleh pemilik akun.\n";
