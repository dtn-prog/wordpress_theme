<?php

  get_header();

  while(have_posts()) {
    the_post(); ?>
    <?php pageBanner([

    ]); ?>

    <div class="container container--narrow page-section">
        
        <?php
        $parentId = wp_get_post_parent_id(get_the_ID(  ));
        if($parentId) { ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                <a class="metabox__blog-home-link" href="<?= get_permalink($parentId) ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($parentId) ?></a> 
                <span class="metabox__main"><?php the_title() ?></span>
                </p>
            </div>
        <?php
        } 
        ?>
        <?php
        function isParent() {
            return get_pages(['child_of' => get_the_ID()]);
        }
        
        if($parentId or isParent()): ?>
      <div class="page-links">
        <h2 class="page-links__title"><a href="<?php echo get_the_permalink($parentId) ?>"><?php echo get_the_title($parentId) ?></a></h2>
        <ul class="min-list">
          <?php
            $parentId; 
            if($parentId) {
                $child_of = $parentId;
            } else {
                $child_of = get_the_ID();
            }

            wp_list_pages([
                'title_li' => '',
                'child_of'=> $child_of,
                'sort_column' => 'menu_order'
            ]);
          ?>
        </ul>
      </div>
      <?php endif; ?>

      <div class="generic-content">
        <?php the_content() ?>
      </div>
    </div>

  <?php }

  get_footer();

?>