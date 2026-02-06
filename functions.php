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
    </style>';
}
add_action('wp_head', 'storage_calculator_styles', 20);

/**
 * Inject Calcumate diagnostic script on the storage calculator page
 * This logs every step of the calculator loading process to the console
 */
function calcumate_diagnostic_script() {
    if (!is_singular()) {
        return;
    }
    $content = get_post()->post_content ?? '';
    if (strpos($content, 'calcumate-root') === false) {
        return;
    }
    ?>
    <script id="calcumate-diagnostics">
    (function() {
        var LOG_PREFIX = '[Calcumate Diagnostics]';
        var startTime = performance.now();

        function log(msg, data) {
            var elapsed = (performance.now() - startTime).toFixed(0);
            if (data !== undefined) {
                console.log(LOG_PREFIX + ' [' + elapsed + 'ms] ' + msg, data);
            } else {
                console.log(LOG_PREFIX + ' [' + elapsed + 'ms] ' + msg);
            }
        }

        function warn(msg, data) {
            var elapsed = (performance.now() - startTime).toFixed(0);
            if (data !== undefined) {
                console.warn(LOG_PREFIX + ' [' + elapsed + 'ms] ' + msg, data);
            } else {
                console.warn(LOG_PREFIX + ' [' + elapsed + 'ms] ' + msg);
            }
        }

        function err(msg, data) {
            var elapsed = (performance.now() - startTime).toFixed(0);
            if (data !== undefined) {
                console.error(LOG_PREFIX + ' [' + elapsed + 'ms] ' + msg, data);
            } else {
                console.error(LOG_PREFIX + ' [' + elapsed + 'ms] ' + msg);
            }
        }

        // 1. Check if the calcumate-root div exists
        var root = document.getElementById('calcumate-root');
        if (root) {
            log('✅ #calcumate-root div FOUND');
            log('   data-integration: ' + root.getAttribute('data-integration'));
            log('   data-int: ' + root.getAttribute('data-int'));
            log('   data-ref length: ' + (root.getAttribute('data-ref') || '').length + ' chars');
        } else {
            err('❌ #calcumate-root div NOT FOUND in DOM');
        }

        // 2. Check document.readyState (WP Rocket overrides this)
        log('document.readyState = "' + document.readyState + '"');

        // 3. Monitor when the Calcumate script tag appears / loads
        var scriptObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(m) {
                m.addedNodes.forEach(function(node) {
                    if (node.tagName === 'SCRIPT' && node.src && node.src.indexOf('calcumate') !== -1) {
                        log('✅ Calcumate <script> tag added to DOM, src: ' + node.src);
                        node.addEventListener('load', function() {
                            log('✅ Calcumate script LOADED successfully');
                        });
                        node.addEventListener('error', function(e) {
                            err('❌ Calcumate script FAILED to load', e);
                        });
                    }
                });
            });
        });
        scriptObserver.observe(document.documentElement, { childList: true, subtree: true });

        // 4. Intercept fetch() to monitor Calcumate API calls
        var originalFetch = window.fetch;
        window.fetch = function() {
            var url = arguments[0];
            if (typeof url === 'string' && (url.indexOf('execute-api') !== -1 || url.indexOf('calcumate') !== -1 || url.indexOf('integration') !== -1)) {
                log('🌐 FETCH request to: ' + url);
                var fetchStart = performance.now();
                return originalFetch.apply(this, arguments).then(function(response) {
                    var fetchTime = (performance.now() - fetchStart).toFixed(0);
                    if (response.ok) {
                        log('✅ FETCH response OK (' + response.status + ') in ' + fetchTime + 'ms: ' + url);
                    } else {
                        warn('⚠️ FETCH response NOT OK (' + response.status + ' ' + response.statusText + ') in ' + fetchTime + 'ms: ' + url);
                    }
                    return response;
                }).catch(function(error) {
                    var fetchTime = (performance.now() - fetchStart).toFixed(0);
                    err('❌ FETCH FAILED after ' + fetchTime + 'ms: ' + url);
                    err('   Error: ' + error.message);
                    err('   Error type: ' + error.name);
                    return Promise.reject(error);
                });
            }
            return originalFetch.apply(this, arguments);
        };

        // 5. Intercept XMLHttpRequest to monitor AJAX calls
        var originalXHROpen = XMLHttpRequest.prototype.open;
        var originalXHRSend = XMLHttpRequest.prototype.send;
        XMLHttpRequest.prototype.open = function(method, url) {
            this._calcDiagUrl = url;
            return originalXHROpen.apply(this, arguments);
        };
        XMLHttpRequest.prototype.send = function() {
            var url = this._calcDiagUrl || '';
            if (url.indexOf('execute-api') !== -1 || url.indexOf('calcumate') !== -1 || url.indexOf('integration') !== -1) {
                log('🌐 XHR request to: ' + url);
                var xhr = this;
                var xhrStart = performance.now();
                xhr.addEventListener('load', function() {
                    var xhrTime = (performance.now() - xhrStart).toFixed(0);
                    log('✅ XHR response (' + xhr.status + ') in ' + xhrTime + 'ms: ' + url);
                });
                xhr.addEventListener('error', function() {
                    var xhrTime = (performance.now() - xhrStart).toFixed(0);
                    err('❌ XHR FAILED after ' + xhrTime + 'ms: ' + url);
                });
                xhr.addEventListener('timeout', function() {
                    var xhrTime = (performance.now() - xhrStart).toFixed(0);
                    err('❌ XHR TIMEOUT after ' + xhrTime + 'ms: ' + url);
                });
            }
            return originalXHRSend.apply(this, arguments);
        };

        // 6. Monitor DOM lifecycle events
        document.addEventListener('DOMContentLoaded', function() {
            log('📌 DOMContentLoaded fired');
        });
        window.addEventListener('load', function() {
            log('📌 window.load fired');
        });
        window.addEventListener('rocket-DOMContentLoaded', function() {
            log('🚀 rocket-DOMContentLoaded fired (WP Rocket faux event)');
        });
        window.addEventListener('rocket-load', function() {
            log('🚀 rocket-load fired (WP Rocket faux event)');
        });
        window.addEventListener('rocket-allScriptsLoaded', function() {
            log('🚀 rocket-allScriptsLoaded fired (WP Rocket done)');
        });

        // 7. Watch for calcumate-root content changes (React app mounting)
        if (root) {
            var rootObserver = new MutationObserver(function(mutations) {
                var childCount = root.children.length;
                var hasContent = root.innerHTML.trim().length > 1;
                if (hasContent && childCount > 0) {
                    log('✅ #calcumate-root has content (' + childCount + ' child elements)');
                    var firstChild = root.children[0];
                    log('   First child tag: <' + firstChild.tagName.toLowerCase() + '>, classes: ' + firstChild.className);
                    rootObserver.disconnect();
                }
            });
            rootObserver.observe(root, { childList: true, subtree: true });
        }

        // 8. Periodic check: is the calculator fully rendered?
        var checks = [3000, 6000, 10000, 15000, 20000];
        checks.forEach(function(delay) {
            setTimeout(function() {
                var el = document.getElementById('calcumate-root');
                if (!el) return;
                var children = el.children.length;
                var text = el.innerText.trim();
                var hasVisibleContent = text.length > 10;
                if (hasVisibleContent) {
                    log('✅ [' + (delay/1000) + 's check] Calculator appears LOADED. Text preview: "' + text.substring(0, 80) + '..."');
                } else {
                    warn('⚠️ [' + (delay/1000) + 's check] Calculator NOT fully loaded yet. Children: ' + children + ', text length: ' + text.length);
                }
            }, delay);
        });

        log('🔧 Diagnostics script initialized. Monitoring Calcumate loading...');
    })();
    </script>
    <?php
}
add_action('wp_head', 'calcumate_diagnostic_script', 1);

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
