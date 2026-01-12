<?php
/**
 * Fix Footer Block Duplicates - WP-CLI Script
 */

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

// Find all "Block - Footer" entries
$footer_posts = get_posts([
    'post_type' => 'acf-field-group',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'title' => 'Block - Footer'
]);

echo "Found " . count($footer_posts) . " 'Block - Footer' entries\n";

// Keep the first one (oldest), deactivate others
$kept_id = null;
foreach ($footer_posts as $index => $post) {
    $group_key = get_post_meta($post->ID, 'group_key', true);
    
    if ($group_key === 'group_67512050a1300') {
        if ($kept_id === null) {
            // Keep the first one
            $kept_id = $post->ID;
            echo "✓ Keeping 'Block - Footer' (ID: {$post->ID})\n";
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
    }
}

// Verify results
$all_groups = acf_get_field_groups();
$active_footer_blocks = 0;

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
        }
    }
}

echo "\nActive footer block ACF groups: {$active_footer_blocks}\n";

if ($active_footer_blocks === 1) {
    echo "✓✓✓ SUCCESS! Only one footer block ACF group is active! ✓✓✓\n";
} else {
    echo "⚠ WARNING: {$active_footer_blocks} footer block groups are still active\n";
}

wp_cache_flush();
