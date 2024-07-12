<?php get_header(); ?>

<?php pageBanner([
  'title'=>'All Events',
  'subtitle'=>"what's going on in our world"
]) ?>

<div class="container container--narrow page-section">
  <?php while (have_posts()) : the_post(); ?>
  <?php get_template_part( 'template-parts/content','event' ); ?>
  <?php endwhile;

  echo paginate_links();
  ?>

  <hr class="section-break">

  <p><a href="<?php echo site_url('/past-events') ?>">Past Events Here</a></p>
</div>

<?php get_footer(); ?>