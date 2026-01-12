<?php

/**
 * Block Counters
 */
?>

<?php
$settings = get_field("settings");
$data = block_start("counters", $block, $settings);
$id = $data["id"];
$color_schema = $data["color_schema"];
$counters = [];
for ($i = 1; $i <= 4; $i++):
    $counters[] = get_field("counter" . $i);
endfor;
?>


<div class="c-section--counters <?= $color_schema; ?> " id="<?php echo esc_attr($id); ?>">
    <div class="container-fluid">
        <div class="row counters-js" data-decimals="<?= $settings["decimals"] ?>">

            <?php
            foreach ($counters as $i => $counter):
                // Skip if counter is not an array or is empty
                if (!is_array($counter) || empty($counter)) {
                    continue;
                }
                
                $icon = $counter["icon"] ?? null;
                $alt = "";
                if (is_array($icon) && !empty($icon["alt"])) {
                    $alt = $icon["alt"];
                } else {
                    $alt = "icon";
                }
                
                $number = $counter["number"] ?? "";
                $title = $counter["title"] ?? "";
                $description = $counter["description"] ?? "";
            ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="counter-tile">
                        <?php if (!empty($icon) && is_array($icon) && !empty($icon["sizes"]["medium"])) : ?>
                            <img class="c__icon" src="<?= esc_url($icon["sizes"]["medium"]); ?>" alt="<?= esc_attr($alt); ?>">
                        <?php endif; ?>
                        <?php if (!empty($number)) : ?>
                            <p class="c__nubmer counter-js <?= $i ?>" data-from="0" data-to="<?= esc_attr($number); ?>" data-speed="2000" data-refresh="1"> </p>
                        <?php endif; ?>
                        <?php if (!empty($title)) : ?>
                            <h4 class="c__title"><?= esc_html($title); ?></h4>
                        <?php endif; ?>
                        <?php if (!empty($description)) : ?>
                            <div class="c__desc wysiwyg">
                                <?= wp_kses_post($description); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
            endforeach;
            ?>

        </div>
        <?php
        $btn = get_field("cta");
        if (isset($btn) && $btn):
        ?>
            <div class="mb-15"></div>
            <div class="u-nav">
                <?php
                $btn = get_field("cta");
                btn_from_link($btn, "std-btn-primary mx-auto")
                ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php
wp_enqueue_script('count-js', get_template_directory_uri() . '/js/jquery.countTo.js', array('jquery'), filemtime(get_template_directory() . '/js/jquery.countTo.js'), false);
wp_enqueue_script('counters-js', get_template_directory_uri() . '/blocks/counters/counters.js', array('jquery', 'count-js'), filemtime(get_template_directory() . '/blocks/counters/counters.js'), false);
?>