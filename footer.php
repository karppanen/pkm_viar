</main>
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <div class="brand brand--footer"><span class="brand__mark" aria-hidden="true">M</span><span class="brand__copy"><strong>MAHKOTA</strong><small>Dealer resmi motor VIAR</small></span></div>
            <p><?php esc_html_e('Motor harian, kendaraan niaga, ATV, dan sepeda listrik dengan layanan penjualan, servis, serta suku cadang.', 'motoshop'); ?></p>
            <a class="footer-phone" href="https://wa.me/6285591220003" target="_blank" rel="noopener">0855 9122 0003</a>
        </div>
        <div>
            <h2><?php esc_html_e('Menu', 'motoshop'); ?></h2>
            <?php wp_nav_menu(array('theme_location' => 'footer', 'container' => false, 'menu_class' => 'footer-menu', 'fallback_cb' => 'motoshop_fallback_menu')); ?>
        </div>
        <div class="footer-branches">
            <h2><?php esc_html_e('Cabang', 'motoshop'); ?></h2>
            <ul><?php foreach (motoshop_branches() as $name => $address) : ?><li><strong><?php echo esc_html($name); ?></strong><span><?php echo esc_html($address); ?></span></li><?php endforeach; ?></ul>
        </div>
    </div>
    <div class="container footer-bottom">
        <p>&copy; <?php echo esc_html(wp_date('Y')); ?> PT Mahkota Inti Sejahtera.</p>
        <p><?php esc_html_e('Informasi harga dan ketersediaan dikonfirmasi oleh cabang.', 'motoshop'); ?></p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
