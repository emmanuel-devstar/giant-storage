<?php
include 'inc/setup.php';
include 'inc/taxonomies.php';
include 'inc/utilities.php';
include 'inc/optimalization.php';
include 'inc/hooks.php';
include 'inc/blocks.php';
include 'inc/mail.php';
include 'inc/prevent-uploading-very-large-images.php';
include 'inc/custom-posts.php';
include 'inc/schema.php';

/**
 * Hide News, Services, and Case Studies from WP Admin menu
 * Note: This only hides the menu items, doesn't delete the post types
 */
function hide_admin_menu_items() {
    remove_menu_page('edit.php?post_type=news');
    remove_menu_page('edit.php?post_type=services');
    remove_menu_page('edit.php?post_type=case-studies');
}
add_action('admin_menu', 'hide_admin_menu_items', 999);

/**
 * Hide specific ACF fields in the block editor
 * This hides Copyright Text and Footer Links from the Footer block
 */
function hide_acf_fields_admin_css() {
    echo '<style>
        /* Hide Copyright Text and Footer Links fields in Footer block */
        .acf-field[data-name="copyright_text"],
        .acf-field[data-name="footer_links"] {
            display: none !important;
        }
    </style>';
}
add_action('admin_head', 'hide_acf_fields_admin_css');

/**
 * Register footnotes meta field to prevent database update errors
 * This fixes the "Could not update the meta value of footnotes in database" error
 */
function register_footnotes_meta() {
    $post_types = array('post', 'page');
    
    foreach ($post_types as $post_type) {
        register_meta(
            $post_type,
            'footnotes',
            array(
                'type'              => 'string',
                'description'       => 'Footnotes content for the post/page',
                'single'            => true,
                'sanitize_callback' => 'wp_kses_post',
                'auth_callback'     => function() {
                    return current_user_can('edit_posts');
                },
                'show_in_rest'      => true,
                'default'           => '',
            )
        );
    }
}
add_action('init', 'register_footnotes_meta', 20);

// Temporary: Auto-save all ACF groups
// Access via: /wp-admin/edit.php?post_type=acf-field-group&save_all_acf=1
add_action('admin_init', function() {
    if (isset($_GET['save_all_acf']) && current_user_can('manage_options') && isset($_GET['post_type']) && $_GET['post_type'] === 'acf-field-group') {
        if (!function_exists('acf_get_field_groups')) {
            wp_die('ACF plugin is not active.');
        }

        $groups = acf_get_field_groups();
        $saved_count = 0;
        $results = [];

        foreach ($groups as $group) {
            if (acf_update_field_group($group)) {
                $saved_count++;
                $results[] = "✓ Saved: {$group['title']} (Key: {$group['key']})";
            } else {
                $results[] = "✗ Failed: {$group['title']} (Key: {$group['key']})";
            }
        }

        // Display results
        add_action('admin_notices', function() use ($results, $saved_count) {
            echo '<div class="notice notice-success is-dismissible" style="padding: 15px;"><p><strong>✅ ACF Groups Auto-Saved!</strong></p>';
            echo '<pre style="max-height: 400px; overflow-y: auto; background: #f5f5f5; padding: 10px; border: 1px solid #ddd;">';
            echo implode("\n", $results);
            echo "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo "Total field groups processed: " . count($results) . "\n";
            echo "Successfully saved: {$saved_count}\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo '</pre>';
            echo '<p><strong>All ACF groups have been saved to JSON files. You can remove this code from functions.php after confirming.</strong></p></div>';
        });

        // Remove the query parameter to avoid re-running
        wp_redirect(admin_url('edit.php?post_type=acf-field-group'));
        exit;
    }
});
