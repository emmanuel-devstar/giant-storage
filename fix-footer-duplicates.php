<?php
/**
 * Fix Footer Block Duplicates - LIVE SERVER ONLY
 * This script:
 * 1. Deactivates duplicate "Footer | Block" (group_68e76d504a2fe)
 * 2. Deactivates duplicate "Block - Footer" entries (keeps only one)
 * 3. Verifies only one active footer block remains
 */

// Load WordPress
require_once('../../../wp-load.php');

// Security check
if (!current_user_can('manage_options')) {
    die('Unauthorized - Admin access required');
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Footer Duplicates</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #0073aa; }
        .success { color: #00a32a; }
        .error { color: #d63638; }
        pre { background: #f0f0f0; padding: 10px; overflow-x: auto; }
    </style>
    <meta http-equiv="refresh" content="3;url=<?php echo admin_url('edit.php?post_type=acf-field-group'); ?>">
</head>
<body>
    <h1>Fixing Footer Block Duplicates...</h1>
    
    <?php
    if (!function_exists('acf_get_field_group')) {
        die('<div class="section error"><h2>Error</h2><p>ACF functions not available</p></div>');
    }
    
    echo '<div class="section">';
    echo '<h2>Step 1: Deactivating "Footer | Block" duplicate...</h2>';
    echo '<pre>';
    
    // Deactivate "Footer | Block" (group_68e76d504a2fe)
    $footer_block_duplicate = acf_get_field_group('group_68e76d504a2fe');
    if ($footer_block_duplicate && isset($footer_block_duplicate['ID'])) {
        $result = wp_update_post([
            'ID' => $footer_block_duplicate['ID'],
            'post_status' => 'draft'
        ]);
        if ($result) {
            echo "✓ Deactivated 'Footer | Block' (ID: {$footer_block_duplicate['ID']})\n";
        } else {
            echo "✗ Failed to deactivate 'Footer | Block'\n";
        }
    } else {
        echo "✓ 'Footer | Block' not found or already inactive\n";
    }
    
    echo '</pre>';
    echo '</div>';
    
    echo '<div class="section">';
    echo '<h2>Step 2: Fixing duplicate "Block - Footer" entries...</h2>';
    echo '<pre>';
    
    // Find all "Block - Footer" entries
    $footer_posts = get_posts([
        'post_type' => 'acf-field-group',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'title' => 'Block - Footer'
    ]);
    
    echo "Found " . count($footer_posts) . " 'Block - Footer' entries\n\n";
    
    // Keep the first one (oldest), deactivate others
    $kept_id = null;
    foreach ($footer_posts as $index => $post) {
        $group_key = get_post_meta($post->ID, 'group_key', true);
        
        if ($group_key === 'group_67512050a1300') {
            if ($kept_id === null) {
                // Keep the first one
                $kept_id = $post->ID;
                echo "✓ Keeping 'Block - Footer' (ID: {$post->ID}) - This is the correct one\n";
            } else {
                // Deactivate duplicates
                $result = wp_update_post([
                    'ID' => $post->ID,
                    'post_status' => 'draft'
                ]);
                if ($result) {
                    echo "✓ Deactivated duplicate 'Block - Footer' (ID: {$post->ID})\n";
                } else {
                    echo "✗ Failed to deactivate ID: {$post->ID}\n";
                }
            }
        } else {
            echo "⚠ Found 'Block - Footer' with different key: {$group_key} (ID: {$post->ID})\n";
        }
    }
    
    echo '</pre>';
    echo '</div>';
    
    echo '<div class="section">';
    echo '<h2>Step 3: Verification...</h2>';
    echo '<pre>';
    
    // Verify results
    $all_groups = acf_get_field_groups();
    $active_footer_blocks = 0;
    $footer_block_names = [];
    
    foreach ($all_groups as $group) {
        if (stripos($group['title'], 'footer') !== false && $group['active']) {
            // Check if it targets acf/footer block
            $targets_footer_block = false;
            if (isset($group['location']) && is_array($group['location'])) {
                foreach ($group['location'] as $location_group) {
                    foreach ($location_group as $rule) {
                        if (isset($rule['param']) && $rule['param'] === 'block' && 
                            isset($rule['value']) && $rule['value'] === 'acf/footer') {
                            $targets_footer_block = true;
                            break 2;
                        }
                    }
                }
            }
            
            if ($targets_footer_block) {
                $active_footer_blocks++;
                $footer_block_names[] = $group['title'] . ' (' . $group['key'] . ')';
            }
        }
    }
    
    echo "Active footer block ACF groups: {$active_footer_blocks}\n";
    if ($active_footer_blocks > 0) {
        echo "\nActive groups:\n";
        foreach ($footer_block_names as $name) {
            echo "  - {$name}\n";
        }
    }
    
    if ($active_footer_blocks === 1) {
        echo "\n✓✓✓ SUCCESS! Only one footer block ACF group is active! ✓✓✓\n";
    } else {
        echo "\n⚠ WARNING: {$active_footer_blocks} footer block groups are still active\n";
    }
    
    echo '</pre>';
    echo '</div>';
    
    // Clear cache
    wp_cache_flush();
    
    echo '<div class="section success">';
    echo '<h2>✓✓✓ CLEANUP COMPLETE! ✓✓✓</h2>';
    echo '<p><strong>Footer block duplicates have been deactivated.</strong></p>';
    echo '<p>You should now see only ONE Footer block in the editor.</p>';
    echo '<p>Redirecting to Field Groups page in 3 seconds...</p>';
    echo '</div>';
    ?>
</body>
</html>
