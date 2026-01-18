<?php

/**
 * Block Name: Text & Media
 */
?>

<?php
$banner = array();
$banner = get_field("banner");

$carousel = get_field("carousel");

$color_schema = ($carousel["width"] === "half") ? "" : "section-transparent";

$data = block_start("text_media", $block, $carousel, $color_schema);
$id = $data["id"];

$color_schema = $data["color_schema"];
$banner_class = ($data["h_tag"] === "h1") ? "used_as_banner" : "";

$mode = (trim(strtolower($carousel["mode"])) === "carousel") ? "carousel" : "single";

if ($mode === "single") {
    $banner[] = get_field("slide");
}
?>
<div class="u-relative <?= $color_schema; ?> " id="<?php echo esc_attr($id); ?>">
  <div data-interval="<?= $carousel["interval"]; ?>" data-autoplay="<?= $carousel["autoplay"]; ?>"
    class="banner-wrapper  <?= $carousel["width"]; ?>  <?= ($mode === "carousel") ? "banner-js owl-carousel owl-theme" : ""; ?>   ">
    <?php
        foreach ($banner as $index => $slide) :

            $content = $slide["content"];
            $background = $slide["background"];
            $settings = $slide["settings"];

            $heading_tag = ($index === 0  && $data["h_tag"] === "h1") ? "h1" : "h2";

            $background_image = ($background["image"]) ? "style='background-image:url(" . $background["image"]["sizes"]["extra_large"] . ")'" : "";
            $ctas = $slide["ctas"];

            include($carousel["width"] . "-width.php");

            if ($mode === "single") break;

        endforeach;
        ?>

  </div>

  <?php

    $image_aligment = $carousel["image_aligment"];
    ?>
  <?php if ($mode === "carousel" && $carousel["show_navigation"] && $index > 0) : ?>

  <div class="container-fluid banner__nav <?= $carousel["width"]; ?> <?= $image_aligment; ?> ">
    <div class="row">
      <?php
                $col_class = "col-12";
                if ($carousel["width"] === "half" && $image_aligment === "right") {
                    $col_class = "col-6";
                }

                $nav_class = $carousel["width"] === "half" ? "l-btns-next-to" : "";

                if ($image_aligment) {
                ?>
      <div class="<?= $col_class; ?>">
        <div class="u-nav <?= $nav_class ?>">
          <div class="tm-prev-js o-nav-btn black"> <?= file_get_contents(IMAGES . '/icons/arrow-left.svg'); ?> </div>
          <div class="tm-next-js o-nav-btn black"> <?= file_get_contents(IMAGES . '/icons/arrow-right.svg'); ?> </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
  <?php endif; ?>



</div>
<?php
if ($mode) {
    wp_enqueue_script('text-media-js', get_template_directory_uri() . '/blocks/text-media/text-media.js', array('jquery'), filemtime(get_template_directory() . '/blocks/text-media/text-media.js'), false);
}
?>