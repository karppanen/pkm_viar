<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
<article <?php post_class('standard-page'); ?>>
    <header class="page-hero"><div class="container"><p class="eyebrow"><?php esc_html_e('Mahkota Inti Sejahtera', 'motoshop'); ?></p><h1><?php the_title(); ?></h1></div></header>
    <div class="container prose"><?php the_content(); ?></div>
</article>
<?php endwhile; ?>
<?php get_footer(); ?>
