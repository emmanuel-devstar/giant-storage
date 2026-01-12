<?php

/**
 * Block Gallery
 */
?>

<?php
$gallery = get_field("gallery");
$settings = get_field("settings");
$layout = strtolower($settings["layout"]);
if (!empty($settings["grid"])) {
    $cols_arr = array("2 columns" => "cols-2", "3 columns" => "cols-3", "4 columns" => "cols-4");
    $grid = $cols_arr[$settings["grid"]];
}


$content = get_field("content");


$data = block_start("gallery", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];
?>

<div class="c-section--gallery u-relative <?= $color_schema; ?> " id="<?php echo esc_attr($id); ?>">
    <div class="container-fluid    ">


        <div class="gallery <?= $grid; ?> <?= $layout; ?>">
            <?php

            foreach ($content as $image) :
            ?>

                <?php
                if ($layout === "mosaic") :
                ?>
                    <a href="<?= $image["image"]["sizes"]["custom_medium"]; ?>">
                        <img class="gallery__image " src="<?= $image["image"]["sizes"]["custom_medium"]; ?>">
                    </a>

                <?php
                else :
                ?>
                    <a class="gallery__item " href="<?= $image["image"]["sizes"]["custom_medium"]; ?>">
                        <div class="gallery__image ratio-js" data-ratio="1" style="background-image:url(<?= $image["image"]["sizes"]["custom_medium"]; ?>)"></div>
                    </a>
                <?php
                endif;
                ?>
                </a>

            <?php

            endforeach;
            ?>
        </div>

    </div>


</div>
<?php
wp_enqueue_script('gallery-js', get_template_directory_uri() . '/blocks/gallery/gallery.js', array('jquery'), filemtime(get_template_directory() . '/blocks/gallery/gallery.js'), false);
?>