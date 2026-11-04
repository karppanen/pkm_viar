<?php
/**
 * Set the administrator password for the handover package.
 *
 * Run: php wp-content/themes/motoshop/scripts/reset-admin-password.php
 */
define('WP_USE_THEMES', false);
require dirname(__DIR__, 4) . '/wp-load.php';

$login = 'admin';
$password = 'Admin#Ciledug2026';

$user = get_user_by('login', $login);
if (!$user) {
    echo "Pengguna $login tidak ditemukan.\n";
    exit(1);
}

wp_set_password($password, $user->ID);
echo "Kata sandi $login diperbarui.\n";
echo "Verifikasi: " . (wp_check_password($password, get_user_by('login', $login)->user_pass, $user->ID) ? 'OK' : 'GAGAL') . "\n";
