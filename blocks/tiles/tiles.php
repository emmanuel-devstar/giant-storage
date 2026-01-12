<?php

/**
 * Block Masonry
 */
?>

<?php
$masonry = get_field("masonry");
$settings = get_field("settings");
$layout = strtolower($settings["layout"]);
if (!empty($settings["grid"])) {
    $cols_arr = array("2 columns" => "cols-2", "3 columns" => "cols-3", "4 columns" => "cols-4");
    $grid = $cols_arr[$settings["grid"]];
}


$content = get_field("content");
$tiles = get_field("tiles");


$data = block_start("masonry", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];
?>

<div class="c-section--masonry u-relative  " id="<?php echo esc_attr($id); ?>">
    <div class="container-fluid">

        <div class="<?= $color_schema; ?> l-text-md">
            <?php if ($content["heading"]) : ?>

                <h2 class="section__title ">
                    <?= $content["heading"] ?>
                </h2>

            <?php endif; ?>

            <?php if ($content["support_text"]) : ?>
                <p class="section__subtitle ">
                    <?= $content["support_text"] ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="m__grid">
            <?php
            foreach ($tiles as $tile):
                $button = $tile["link"];
                if (isset($tile["image"]) && $tile["image"]) {
                    $bg_image  = 'background-image: url(' . $tile["image"]["sizes"]["large"] . ')';
                } else {
                    $bg_image = '';
                }

            ?>

                <div class="m__tile ratio-js" data-ratio="1">
                    <div class="tile__front u-mask o4" style="<?= $bg_image ?>">
                        <?php
                        if (isset($tile["heading"])) :
                        ?>
                            <h3 class="tile__heading"><?= $tile["heading"] ?></h3>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="tile__back">
                        <?php
                        if (isset($button) && $button && (!isset($button["title"]) || !$button["title"])) :
                            $button["title"] = "Explore more";
                        endif;
                        btn_from_link($button, "btn btn--outline-white hover-white");
                        ?>
                    </div>

                </div>


            <?php
            endforeach;
            ?>


        </div>


    </div>
</div>