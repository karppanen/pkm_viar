
<?php get_header(); ?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow"><?php esc_html_e('Informasi', 'motoshop'); ?></p>
        <h1><?php echo is_home() ? esc_html__('Artikel', 'motoshop') : esc_html(get_the_archive_title()); ?></h1>
    </div>
</section>
<section class="section">
    <div class="container content-grid">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article <?php post_class('entry-card'); ?>>
                <p class="entry-card__date"><?php echo esc_html(get_the_date('d.m.Y')); ?></p>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php the_excerpt(); ?>
                <a class="text-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Baca selengkapnya', 'motoshop'); ?> &#8594;</a>
            </article>
        <?php endwhile; the_posts_pagination(); else : ?>
            <p><?php esc_html_e('Belum ada konten yang dipublikasikan.', 'motoshop'); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
 
