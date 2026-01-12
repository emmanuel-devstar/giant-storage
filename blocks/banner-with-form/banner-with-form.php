<?php

/**
 * Block Name: Banner with form
 */
?>

<?php
$banner = array();
$banner = get_field("banner");

$carousel = get_field("slide");
$carousel["background"] = false;

//$block["id"] =  uniqid();
$data = block_start("banner_form", $block, $carousel["settings"], $color_schema);
$id = $data["id"];

$color_schema = $data["color_schema"];

$mode = "single";

if ($mode === "single") {
    $banner[] = get_field("slide");
}
?>
<div class="u-relative u-z-index-20 <?= $color_schema; ?> " id="<?php echo esc_attr($id); ?>">
    <div class=" banner-wrapper full">
        <?php
        foreach ($banner as $index => $slide) :

            $content = $slide["content"];
            $background = $slide["background"];

            $mask_class = $background["mask"] ? "u-mask" : "";
            $background_image = ($background["image"]) ? "style='background-image:url(" . $background["image"]["sizes"]["extra_large"] . ")'" : "";
            if ($background_image) {
                $color_schema = "section-transparent";
            }

            $settings = $slide["settings"];

            $heading_tag =  ($settings["h1"]) ? "h1" : "h2";

            $ctas = $slide["ctas"];

            $form = get_field("form");

            include("full-width.php");

            if ($mode === "single") break;

        endforeach;
        ?>

    </div>

</div>