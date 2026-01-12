<?php
/**
 * Block Name: Text
 */
?>

<?php
$banner = get_field("banner");
if (!is_array($banner)) {
    $banner = [];
}

$carousel = get_field("carousel");
if (!is_array($carousel)) {
    $carousel = [];
}

$data = block_start("text", $block, $carousel , null);
$id = $data["id"];
$color_schema = $data["color_schema"];

$mode = (isset($carousel["mode"]) && trim(strtolower($carousel["mode"])) === "carousel") ? "carousel" : "single";

if($mode==="single"){
    $banner[] = get_field("slide");
}
?>
<div class="u-relative <?= $color_schema; ?> " id="<?php echo esc_attr($id); ?>">
    <div  class=" banner-wrapper full">
        <?php
        foreach ($banner as $index => $slide) :            

            $content = $slide["content"] ?? null;
            $background = $slide["background"] ?? null;
            $settings = $slide["settings"] ?? null;

            $heading_tag = ($index === 0  && $data["h_tag"] === "h1") ? "h1" : "h2";
            
            $ctas = $slide["ctas"] ?? null;

            include("full-width.php");

            if ($mode === "single") break;

        endforeach;
        ?>

    </div>    

</div>