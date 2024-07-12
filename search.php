<?php get_header(); ?>
<?php pageBanner([
    'title' => 'Search bitch',
    'subtitle' => 'you search for "' . get_search_query() . '"'
]) ?>


<div class="container container--narrow page-section">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/content',  get_post_type()) ?>

        <?php endwhile; ?>
        <?php echo paginate_links(); ?>
    <?php else : ?>
        <h2 class="headline headline--small-plus">there cricket here</h2>
    <?php endif; ?>
    <?php get_search_form() ?>
</div>

<?php get_footer();

?>