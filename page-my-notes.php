<?php

if (!is_user_logged_in()) {
    wp_redirect(esc_url(site_url('/')));
    exit;
}

get_header();

while (have_posts()) {
    the_post(); ?>
    <?php pageBanner([]); ?>

    <div class="container container--narrow page-section">

        <div class="create-note">
            <h2 class="headline headline--medium">create new note</h2>
            <input required type="text" placeholder="title" class="new-note-title">
            <textarea required placeholder="note here" class="new-note-body"></textarea>
            <span class="submit-note">create note</span>
            <span class="note-limit-message">note limit reached, delete your post to make room for new one</span>
        </div>

        <ul class="min-list link-list" id="my-notes">
            <?php
            $userNotes = new WP_Query([
                'post_type' => 'note',
                'posts_per_page' => -1,
                'author' => get_current_user_id()
            ]);
            ?>

            <?php if ($userNotes->have_posts()) : ?>
                <?php while ($userNotes->have_posts()) : $userNotes->the_post(); ?>
                    <li data-id="<?php the_ID() ?>">

                        <input readonly class="note-title-field" type="text" value="<?php echo str_replace('Private: ', '', esc_attr(wp_strip_all_tags(get_the_title()))) ?>">
                        <span class="edit-note"><i class="fa fa-pencil"></i> Edit</span>
                        <span class="delete-note"><i class="fa fa-trash-o"></i> Delete</span>
                        <textarea readonly class="note-body-field"><?php echo esc_textarea(wp_strip_all_tags(get_the_content())); ?></textarea>

                        <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right"></i> Save</span>
                    </li>
                <?php endwhile; ?>
            <?php endif; ?>
        </ul>
    </div>

<?php }

get_footer();

?>