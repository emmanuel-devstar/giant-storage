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
 * Fix storage calculator (Calcumate) markup so it loads on mobile and desktop
 * - Remove the inline script tag from content (we enqueue it in the footer)
 */
function fix_storage_calculator_markup($content) {
    if (strpos($content, 'calcumate-root') === false) {
        return $content;
    }
    // Remove <script> tags for Calcumate (and any <p> wrapper around them)
    $content = preg_replace(
        '#<p>\s*<script[^>]*(?:calcumate|s3-ap-southeast-2)[^>]*>\s*</script>\s*</p>#is',
        '',
        $content
    );
    $content = preg_replace(
        '#<script[^>]*(?:calcumate|s3-ap-southeast-2)[^>]*>\s*</script>#is',
        '',
        $content
    );
    return $content;
}
add_filter('the_content', 'fix_storage_calculator_markup', 5);

/**
 * Enqueue Calcumate script in the footer when needed
 */
function enqueue_storage_calculator_script() {
    if (!is_singular()) {
        return;
    }
    $content = get_post()->post_content ?? '';
    if (strpos($content, 'calcumate-root') === false) {
        return;
    }
    wp_enqueue_script(
        'calcumate-main',
        'https://calcumate-calculator-new-production.s3-ap-southeast-2.amazonaws.com/static/js/main.js',
        array(),
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_storage_calculator_script', 20);

/**
 * Ensure storage calculator container has min-height on mobile so it shows before app loads
 */
function storage_calculator_styles() {
    if (!is_singular()) {
        return;
    }
    $content = get_post()->post_content ?? '';
    if (strpos($content, 'calcumate-root') === false) {
        return;
    }
    echo '<style id="storage-calculator-fix">
        #calcumate-root { min-height: 400px; }
        @media (min-width: 768px) {
            #calcumate-root { min-height: 500px; }
        }
    </style>';
}
add_action('wp_head', 'storage_calculator_styles', 20);

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
