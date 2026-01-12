<?php
/**
 * Block Spacer
 */
?>

<?php 
$settings = get_field("settings");
$data = block_start("spacer", $block, $settings);
$id = $data["id"];
?>



<div class="c-section--spacer section-white " id="<?php echo esc_attr($id); ?>">
    
</div>

