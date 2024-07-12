<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch()
{
    register_rest_route('university/v1', 'search', [
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'universitySearchResults',
        'permission_callback' => '__return_true',
    ]);
}



function universitySearchResults($data)
{

    $mainQuery = new WP_Query([
        'post_type' => 'any',
        's' => sanitize_text_field($data['term']),
        'posts_per_page' => -1
    ]);

    $results = [
        'generalInfo' => [],
        'professors' => [],
        'programs' => [],
        'events' => [],
    ];

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        if (get_post_type() === 'post' || get_post_type() === 'page') {
            array_push($results['generalInfo'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()

            ]);
        }

        if (get_post_type() === 'professor') {
            array_push($results['professors'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
            ]);
        }

        if (get_post_type() === 'program') {
            array_push($results['programs'], [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ]);
        }

        if (get_post_type() === 'event') {
            $eventDate = new DateTime(get_field('event_date'));

            $description = '';

            if (has_excerpt()) {
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 18);
            }
            array_push($results['events'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description
            ]);
        }
    }

    wp_reset_postdata();

    if ($results['programs']) {
        $programsMetaQuery = ['relation' => 'OR'];

        foreach ($results['programs'] as $item) {
            array_push(
                $programsMetaQuery,
                [
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . $item['id'] . '"'
                ]
            );
        }

        $programRelationshipQuery = new WP_Query([
            'posts_per_page' => -1,
            'post_type' => ['professor', 'event'],
            'meta_query' =>  $programsMetaQuery
        ]);

        while ($programRelationshipQuery->have_posts()) {
            $programRelationshipQuery->the_post();
            if (get_post_type() === 'professor') {
                array_push($results['professors'], [
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                ]);
            }

            if (get_post_type() === 'event') {
                $eventDate = new DateTime(get_field('event_date'));

                $description = '';

                if (has_excerpt()) {
                    $description = get_the_excerpt();
                } else {
                    $description = wp_trim_words(get_the_content(), 18);
                }
                array_push($results['events'], [
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description
                ]);
            }
        }

        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    }


    return $results;
}
