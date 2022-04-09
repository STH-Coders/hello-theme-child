<?php
/**
 * Theme functions and definitions.
 *
 * @param mixed $return
 * @param mixed $title
 */

/*
 * Load child theme css and optional scripts
 *
 * @return void
 */

// Load child theme css and optional scripts. //

// Hide the Archive Title Prefix -- Credit: Ben Gillbanks //
function hide_the_archive_title($title)
{
    // Skip if the site isn't LTR, this is visual, not functional.
    // Should try to work out an elegant solution that works for both directions.
    if (is_rtl()) {
        return $title;
    }
    // Split the title into parts so we can wrap them with spans.
    $title_parts = explode(': ', $title, 2);
    // Glue it back together again.
    if (!empty($title_parts[1])) {
        $title = wp_kses(
            $title_parts[1],
            [
                'span' => [
                    'class' => [],
                ],
            ]
        );
        $title = '<span class="screen-reader-text">'.esc_html($title_parts[0]).': </span>'.$title;
    }

    return $title;
}
add_filter('get_the_archive_title', 'hide_the_archive_title');


// Disable comments on images //
function filter_media_comment_status($open, $post_id)
{
    $post = get_post($post_id);
    if ('attachment' == $post->post_type) {
        return false;
    }

    return $open;
}
add_filter('comments_open', 'filter_media_comment_status', 10, 2);


// estimated reading time
function reading_time()
{
    $content = get_post_field('post_content', $post->ID);
    $word_count = str_word_count(strip_tags($content));
    $readingtime = ceil($word_count / 200);
    if (1 == $readingtime) {
        $timer = ' minute';
    } else {
        $timer = ' minutes';
    }

    return $readingtime.$timer;
}

// Custom “search results for:” archive title //
function archive_callback($title)
{
    $str = 'Here\'s What We Found About: ';
    if (is_search()) {
        return stripslashes($str).get_search_query();
    }

    return $title;
}
add_filter('elementor/utils/get_the_archive_title', 'archive_callback');

// Add the menu widgets option //
if (function_exists('register_sidebar')) {
    register_sidebar();
}

// Enable ACF Custom Fields //
add_filter('acf/settings/remove_wp_meta_box', '__return_false');

// Enqueue custom scripts and styles //
function hello_elementor_child_enqueue_scripts()
{
    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_directory_uri().'/style.css',
        [
            'hello-elementor-theme-style',
        ],
        '1.0.1'
    );
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20);
