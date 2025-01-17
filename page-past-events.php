<?php get_header(); ?>
<?php pageBanner([
  'title'=>'Pasts events',
  'subtitle'=>'a recap of our past events'
]) ?>


<div class="container container--narrow page-section">

  <?php
  $today = date('Ymd'); 
  $pastEvents = new WP_Query(
    [   'paged'=>get_query_var('paged',1),
        'post_type'=>'event',
        'meta_query' => array(
            array(
            'key' => 'event_date',
            'compare' => '<=',
            'value' => $today,
            'type' => 'numeric'
            )
            ),
        'meta_key'=>'event_date',
        'orderby'=>'meta_value_num',
        'order'=>'ASC'
    ]
  );
  
  ?>
  <?php while ($pastEvents->have_posts()) : $pastEvents->the_post(); ?>
  <?php get_template_part( 'template-parts/content','event' ); ?>
  <?php endwhile;

  echo paginate_links([
    'total'=>$pastEvents->max_num_pages
  ]);

  ?>
</div>

<?php get_footer(); ?>