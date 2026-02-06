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
    // Add fallback message after calcumate-root (shown if API fails)
    $fallback = '<div id="calcumate-fallback">'
        . '<h3>The storage calculator is temporarily unavailable</h3>'
        . '<p>Please try again in a moment, or call us on <a href="tel:01722698000"><strong>01722 698000</strong></a> and we\'ll help you work out the right storage size.</p>'
        . '<button class="retry-btn" onclick="window.location.reload()">Try Again</button>'
        . '</div>';
    $content = str_replace('</div><!-- calcumate-end -->', '</div>' . $fallback, $content);
    // If no special marker, insert after the calcumate-root div
    if (strpos($content, 'calcumate-fallback') === false) {
        $content = preg_replace(
            '#(<div\s+id="calcumate-root"[^>]*>[\s]*</div>)#is',
            '$1' . $fallback,
            $content,
            1
        );
    }
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
 * Exclude Calcumate from WP Rocket JS minification
 *
 * WP Rocket's minifier re-minifies the already-minified React bundle from S3,
 * which corrupts it (SyntaxError: Unexpected token '}').
 *
 * We intentionally ALLOW WP Rocket to delay the script. WP Rocket's delay
 * actually helps: it runs the script AFTER dispatching faux DOMContentLoaded
 * and load events, which the Calcumate React app needs to fully initialize.
 * The delay is ~200-500ms (triggers on first mouse move / scroll / touch).
 */
function exclude_calcumate_from_rocket_minify($excluded) {
    $excluded[] = 'calcumate-calculator-new-production.s3-ap-southeast-2.amazonaws.com';
    $excluded[] = 'calcumate';
    return $excluded;
}
add_filter('rocket_exclude_js', 'exclude_calcumate_from_rocket_minify');
add_filter('rocket_minify_excluded_external_js', 'exclude_calcumate_from_rocket_minify');

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
        #calcumate-fallback {
            display: none;
            text-align: center;
            padding: 60px 20px;
            font-family: var(--modular-primary-font, sans-serif);
        }
        #calcumate-fallback h3 {
            font-size: 22px;
            margin-bottom: 16px;
            color: #333;
        }
        #calcumate-fallback p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }
        #calcumate-fallback .retry-btn {
            display: inline-block;
            padding: 14px 36px;
            background: #000683;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            border: none;
        }
        #calcumate-fallback .retry-btn:hover {
            background: #333;
        }
    </style>';
}
add_action('wp_head', 'storage_calculator_styles', 20);

/**
 * Calcumate watchdog: show fallback message if the calculator API fails
 * Also logs diagnostics to console for debugging
 */
function calcumate_watchdog_script() {
    if (!is_singular()) {
        return;
    }
    $content = get_post()->post_content ?? '';
    if (strpos($content, 'calcumate-root') === false) {
        return;
    }
    ?>
    <script id="calcumate-watchdog">
    (function() {
        var apiFailed = false;
        var apiSucceeded = false;
        var LOG = '[Calcumate]';

        // Intercept fetch to detect Calcumate API failure and enable retry
        var originalFetch = window.fetch;
        window.fetch = function() {
            var url = typeof arguments[0] === 'string' ? arguments[0] : '';
            var isCalcumateAPI = url.indexOf('execute-api') !== -1 && url.indexOf('integration') !== -1;

            if (isCalcumateAPI) {
                console.log(LOG + ' API request: ' + url);
                return originalFetch.apply(this, arguments).then(function(response) {
                    if (response.ok) {
                        console.log(LOG + ' API response OK (' + response.status + ')');
                        apiSucceeded = true;
                    } else {
                        console.warn(LOG + ' API response error: ' + response.status);
                    }
                    return response;
                }).catch(function(error) {
                    console.error(LOG + ' API FAILED: ' + error.message);
                    apiFailed = true;
                    showFallback();
                    return Promise.reject(error);
                });
            }
            return originalFetch.apply(this, arguments);
        };

        function showFallback() {
            var fb = document.getElementById('calcumate-fallback');
            var root = document.getElementById('calcumate-root');
            if (fb) {
                fb.style.display = 'block';
            }
            if (root) {
                root.style.minHeight = '0';
                root.style.display = 'none';
            }
            console.warn(LOG + ' Showing fallback message (API unreachable)');
        }

        // Safety net: if after 20 seconds the calculator has no visible content, show fallback
        setTimeout(function() {
            if (apiSucceeded) return;
            var root = document.getElementById('calcumate-root');
            if (!root) return;
            var text = root.innerText.trim();
            if (text.length < 10 && !apiSucceeded) {
                console.warn(LOG + ' 20s timeout: calculator not loaded, showing fallback');
                showFallback();
            }
        }, 20000);
    })();
    </script>
    <?php
}
add_action('wp_head', 'calcumate_watchdog_script', 1);

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
