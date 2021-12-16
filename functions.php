<?php
/**
 * Theme functions and definitions.
 *
 * @param mixed $return
 */

/*
 * Load child theme css and optional scripts
 *
 * @return void
 */

// Load child theme css and optional scripts.

function ele_disable_page_title($return)
{
    return false;
}
add_filter('hello_elementor_page_title', 'ele_disable_page_title');

add_filter('get_the_archive_title', function ($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">'.get_the_author().'</span>';
    }

    return $title;
});

function archive_callback($title)
{
    $str = 'Here\'s What We Found About: ';
    if (is_search()) {
        return stripslashes($str).get_search_query();
    }

    return $title;
}
add_filter('elementor/utils/get_the_archive_title', 'archive_callback');

if (function_exists('register_sidebar')) {
    register_sidebar();
}

// Enable WordPress Custom Fields
add_filter('acf/settings/remove_wp_meta_box', '__return_false');

function hello_elementor_custom_child_enqueue_scripts()
{
    wp_enqueue_style(
        'hello-elementor-custom-child-style',
        get_stylesheet_directory_uri().'/style.css',
        [
            'hello-elementor-theme-style',
        ],
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'hello_elementor_custom_child_enqueue_scripts', 20);
