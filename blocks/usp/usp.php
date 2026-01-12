<?php

/**
 * Block Name: usp
 */
?>

<?php
$block_post = $block["id"];

$id = 'usp-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

$settings = get_field("settings", $block_post);
if (!is_array($settings)) {
    $settings = [];
}
$number_of_columns = $settings["number_of_columns"] ?? null;
$images = strtolower($settings["images"] ?? "");
$content = get_field("content", $block_post);
if (!is_array($content)) {
    $content = [];
}

$tiles = get_field("tiles", $block_post);
if (!is_array($tiles)) {
    $tiles = [];
}
$button = btn_from_link($content["button"] ?? null, "btn btn--black");


$data = block_start("usp", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];

//$class = ($block["align"] === "wide") ?  "col-12" : "col-12 col-xl-10 mx-auto";
?>

<div id="<?= $id ?>" class="c-section--usp <?= $color_schema; ?> ">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="l-text-md ">
                    <?php if (!empty($content["headline_text"])): ?>
                        <<?= $data["h_tag"]; ?> class="section__title custom-title-colour"><?= $content["headline_text"]; ?></<?= $data["h_tag"]; ?>>
                    <?php endif; ?>

                    <?php if (!empty($content["body_text"])): ?>
                        <div class="section__subtitle wysiwyg"><?= $content["body_text"] ?></div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <?php
                    //$col_classes = "col-6";
                    if ($number_of_columns == 2) $col_classes = "col-12 col-md-6";
                    if ($number_of_columns == 3) $col_classes = "col-12 col-md-6 col-lg-4";
                    if ($number_of_columns == 4) $col_classes = "col-12 col-md-6 col-lg-3";

                    foreach ($tiles as $index => $tile) :

                        if ($number_of_columns == 2) {
                            $group = floor($index / 1);
                            $group_md = floor($index / 2);
                            $group_lg = floor($index / 2);
                        } elseif ($number_of_columns == 3) {
                            $group = floor($index / 1);
                            $group_md = floor($index / 2);
                            $group_lg = floor($index / 3);
                        } elseif ($number_of_columns == 4) {
                            $group = floor($index / 1);
                            $group_md = floor($index / 2);
                            $group_lg = floor($index / 4);
                        }

                    ?>
                        <div class="<?= $col_classes ?> ">
                            <?php
                            $button = $tile["button"];
                            if (!empty($button) && $button["url"]) :
                                $rel = ($button["target"] === "_blank") ? 'rel="external nofollow"' : '';
                            ?>
                                <a href="<?= $button["url"] ?>" target="<?= $button["target"] ?>" <?= $rel ?> class="usp__tile">
                                <?php
                            else :
                                ?>
                                    <a class="usp__tile">
                                    <?php
                                endif;
                                    ?>

                                    <?php
                                    if ($images === "icon") :
                                    ?>
                                        <img class="usp__icon" src="<?= $tile["image"]["sizes"]["medium"] ?>" alt="<?= $tile["image"]["alt"] ?>">
                                    <?php
                                    else :
                                    ?>
                                        <div class="usp__image ratio-js" data-ratio="0.61" style="background-image:url(<?= $tile["image"]["sizes"]["custom_medium"] ?>)" alt="<?= $tile["image"]["alt"] ?>"></div>
                                    <?php
                                    endif;
                                    ?>

                                    <?php
                                    $desc_class = ($images === "icon") ? "l-short" : "";
                                    ?>

                                    <?php
                                    if ($number_of_columns == "2" || $number_of_columns == "3") :
                                    ?>
                                        <div class="<?= $desc_class; ?> usp__content align-h-js" data-block="<?= $block['id'] ?>" data-align="usp-title-<?= $group; ?>" data-align-md="usp-title-<?= $group_md; ?>" data-align-lg="usp-title-<?= $group_lg; ?>">

                                            <?php if ($tile["title"]) : ?>
                                                <h3 class="usp__title <?= $desc_class; ?>">
                                                    <?= $tile["title"] ?>
                                                </h3>
                                            <?php endif; ?>

                                            <?php if ($tile["description"]) : ?>
                                                <p class="usp__desc <?= $desc_class; ?>">
                                                    <?= $tile["description"] ?>
                                                </p>
                                            <?php endif; ?>

                                        </div>
                                    <?php
                                    endif;
                                    ?>

                                    <?php
                                    if ($number_of_columns == "4") :
                                    ?>
                                        <div class="<?= $desc_class; ?> usp__content align-h-js " data-block="<?= $block['id'] ?>" data-align="usp-title-<?= $group; ?>" data-align-lg="usp-title-<?= $group_lg; ?>">
                                            <?php if ($tile["title"]) : ?>
                                                <h3 class="usp__title">
                                                    <?= $tile["title"] ?>
                                                </h3>
                                            <?php endif; ?>

                                            <?php if ($tile["description"]) : ?>
                                                <p class="usp__desc">
                                                    <?= $tile["description"] ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>


                                    <?php
                                    endif;
                                    ?>

                                    <?php
                                    $button = $tile["button"];


                                    if (!empty($button) && $button["url"]) :
                                        if (strtolower($settings["links"]) === "text"):
                                    ?>
                                            <span class="custom-link"><?= $button["title"] ?> </span>
                                        <?php endif;

                                        if (strtolower($settings["links"]) === "icon"):
                                        ?>
                                            <div class="usp-icon-link">
                                                <?= file_get_contents(IMAGES . '/icons/arrow-right-circle.svg'); ?> <span class="custom-link"><?= $button["title"] ?> </span>
                                            </div>
                                        <?php
                                        endif;
                                        ?>
                                    <?php


                                    endif;
                                    ?>

                                    </a>
                        </div>
                    <?php
                    endforeach;
                    ?>

                </div>


            </div>
        </div>




    </div>
</div>