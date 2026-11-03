</main>
<?php $branch = motoshop_primary_branch(); ?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <div class="brand brand--footer"><img class="brand__logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-mis.png'); ?>" alt="<?php esc_attr_e('Logo Mahkota Inti Sejahtera', 'motoshop'); ?>"><span class="brand__copy"><strong>MAHKOTA</strong><small>Dealer resmi motor VIAR - Cabang Ciledug</small></span></div>
            <p><?php echo esc_html(sprintf(__('%s adalah dealer resmi motor VIAR milik %s. Kami melayani penjualan tunai dan kredit, servis, serta suku cadang untuk kawasan Tangerang dan sekitarnya.', 'motoshop'), $branch['name'], $branch['company'])); ?></p>
            <address class="footer-address">
                <strong><?php echo esc_html($branch['name']); ?></strong>
                <span><?php echo esc_html($branch['address']); ?></span>
                <span><?php echo esc_html($branch['hours']); ?></span>
            </address>
            <a class="footer-phone" href="<?php echo esc_url(motoshop_whatsapp_link(__('Halo, saya ingin bertanya tentang motor VIAR.', 'motoshop'))); ?>" target="_blank" rel="noopener"><?php echo esc_html($branch['phone']); ?></a>
        </div>
        <div>
            <h2><?php esc_html_e('Menu', 'motoshop'); ?></h2>
            <?php wp_nav_menu(array('theme_location' => 'footer', 'container' => false, 'menu_class' => 'footer-menu', 'fallback_cb' => 'motoshop_fallback_menu')); ?>
        </div>
        <div class="footer-branches">
            <h2><?php esc_html_e('Cabang', 'motoshop'); ?></h2>
            <ul><?php foreach (motoshop_branches() as $name => $address) : ?><li<?php echo 'Ciledug' === $name ? ' class="is-primary"' : ''; ?>><strong><?php echo esc_html($name); ?></strong><span><?php echo esc_html($address); ?></span></li><?php endforeach; ?></ul>
            <p class="footer-note"><?php esc_html_e('Permintaan calon pelanggan pada website ini ditangani langsung oleh tim Cabang Ciledug.', 'motoshop'); ?></p>
        </div>
    </div>
    <div class="container footer-bottom">
        <p>&copy; <?php echo esc_html(wp_date('Y')); ?> <?php echo esc_html($branch['company']); ?>. Website Cabang Ciledug.</p>
        <p><?php esc_html_e('Harga dan ketersediaan unit dikonfirmasi langsung oleh sales cabang. Pembayaran dan serah terima kendaraan dilakukan di cabang.', 'motoshop'); ?></p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
