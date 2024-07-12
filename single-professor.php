<?php

  get_header();

  while(have_posts()) {
    the_post(); ?>
    <?php pageBanner(); ?>

    <div class="container container--narrow page-section">

        <div class="generic-content">
            <div class="row group">
                <div class="one-third">
                    <?php the_post_thumbnail('professorPortrait'); ?>
                </div>
                <div class="two-third">
                <?php  the_content(); ?>
                </div>
            </div>
        </div>
        <?php $realtedPrograms = get_field('related_programs'); ?>

        <?php if($realtedPrograms): ?>
          <hr class="section-break"></hr>
        <h2 class="headline headline--medium">Subject(s) Taught</h2>
        <ul class="link-list min-list">
        <?php
        foreach($realtedPrograms as $program) { ?>
          <li><a href="<?php echo get_the_permalink($program) ?>"><?php echo get_the_title($program); ?></a></li>
        <?php }
        
        ?>
        </ul>
        <?php endif; ?>
    </div>

  <?php }

  get_footer();

?>