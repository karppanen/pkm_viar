<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
<article <?php post_class('standard-page'); ?>>
    <header class="page-hero"><div class="container"><p class="eyebrow"><?php echo esc_html(get_the_date('d.m.Y')); ?></p><h1><?php the_title(); ?></h1></div></header>
    <div class="container prose"><?php if (has_post_thumbnail()) : ?><figure class="entry-image"><?php the_post_thumbnail('large'); ?></figure><?php endif; ?><?php the_content(); ?></div>
</article>
<?php endwhile; ?>
<?php get_footer(); ?>
