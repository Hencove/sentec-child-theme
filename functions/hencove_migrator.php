<?php

/**
 * 
 */


$username = 'hencove'; // Replace with your WP username
$password = 'ILRW QlFw aAY7 UZWs LFzj YZEi'; // Replace with an application password or token

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('hencove_migrator', 'hencove_migrator');
}
function hencove_migrator()
{
    // Mapping arrays for product lines and care settings
    $tag_to_product_mapping = [
        'ipv-therapy' => 'ipv',
        'intrapulmonary-percussive-ventilation' => 'ipv',
        'transcutaneous-monitoring' => 'TCM',
        'electrical-impedance-tomography' => 'EIT',
    ];

    $tag_to_care_setting_mapping = [
        'neonatal-pediatric-intensive-care' => 'NICU',
        'anesthesia-post-operative-care' => 'anesthesia-post-op',
        'home-ventilation-management' => 'home'
    ];

    // Fetch posts with specific tags
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => array('blog'), // Specify the tags you want to migrate
            ),
        ),
    );

    $posts = get_posts($args);

    if (empty($posts)) {
        WP_CLI::success('No posts found with the specified tags.');
        return;
    }

    // Check if the Red_Item class exists, and if not, include the file
    if (!class_exists('Red_Item')) {
        // Attempt to load the Redirection plugin files manually
        $redirection_plugin_path = WP_PLUGIN_DIR . '/redirection/redirection.php';
        if (file_exists($redirection_plugin_path)) {
            require_once $redirection_plugin_path;
            WP_CLI::log('Loaded Redirection plugin manually.');
        } else {
            WP_CLI::error('Redirection plugin not available or not installed.');
            return;
        }
    }

    foreach ($posts as $post) {
        WP_CLI::log('Processing post ID: ' . $post->ID);

        // Step 1: Change the post type using wp_update_post to trigger all necessary actions
        $new_post_type = 'sentec-articles'; // Your custom post type
        $post_data = array(
            'ID'        => $post->ID,
            'post_type' => $new_post_type,
        );
        wp_update_post($post_data); // This ensures all actions, including Redirection, are triggered.
        WP_CLI::log('Updated post type for post ID: ' . $post->ID . ' using wp_update_post().');

        // Step 2: Get terms and assign custom taxonomies
        $post_id = $post->ID;
        $terms = wp_get_post_terms($post_id, 'post_tag');
        $term_slugs = wp_list_pluck($terms, 'slug'); // Extract slugs of all post tags

        // Handle 'sentec-product-lines' taxonomy
        foreach ($tag_to_product_mapping as $tag => $term) {
            if (in_array($tag, $term_slugs)) {
                $product_term = get_term_by('slug', $term, 'sentec-product-lines');
                if ($product_term && !is_wp_error($product_term)) {
                    wp_set_post_terms($post->ID, array($product_term->term_id), 'sentec-product-lines', true);
                    WP_CLI::log('Assigned product term "' . $term . '" to post ID: ' . $post->ID);
                }
            }
        }

        // Handle 'sentec-care-settings' taxonomy
        foreach ($tag_to_care_setting_mapping as $tag => $term) {
            if (in_array($tag, $term_slugs)) {
                $care_term = get_term_by('slug', $term, 'sentec-care-settings');
                if ($care_term && !is_wp_error($care_term)) {
                    wp_set_post_terms($post->ID, array($care_term->term_id), 'sentec-care-settings', true);
                    WP_CLI::log('Assigned care setting term "' . $term . '" to post ID: ' . $post->ID);
                }
            }
        }

        // Step 3: Create the redirect via Redirection API
        $old_url = get_permalink($post->ID); // Old permalink
        $new_url = home_url('/new-post-slug/' . $post->post_name); // Adjust as needed based on your new structure

        if (class_exists('Red_Item')) {
            $redirect = new Red_Item();
            $redirect->set('url', $old_url);
            $redirect->set('action_type', 'url');
            $redirect->set('action_data', $new_url);
            $redirect->set('match_type', 'url');
            $redirect->set('action_code', 301); // Permanent redirect
            $redirect->create();

            WP_CLI::log('Added redirect from ' . $old_url . ' to ' . $new_url . ' via Redirection API.');
        } else {
            WP_CLI::warning('Red_Item class not available after attempted load.');
        }
    }

    WP_CLI::success('Post migration, taxonomy assignment, and redirects completed!');
}
