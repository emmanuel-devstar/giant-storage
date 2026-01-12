<div class="banner__start">
    <?php
    $data = block_start("tm_slide_" . $index, $block, $settings);
    $id = $data["id"];

    $mask_class = $background["mask"] ? "u-mask " : " ";
    ?>
    <style>
    /* Mobile styles for H1 banner title - reduce font size and add top spacing */
    @media screen and (max-width: 768px) {
        div#<?= esc_attr($id); ?> .banner__title.custom-title-colour {
            font-size: 28px !important;
            line-height: 1.3em !important;
            margin-top: 40px !important;
            padding-top: 20px !important;
        }
    }
    @media screen and (max-width: 576px) {
        div#<?= esc_attr($id); ?> .banner__title.custom-title-colour {
            font-size: 24px !important;
            line-height: 1.3em !important;
            margin-top: 50px !important;
            padding-top: 25px !important;
        }
    }
    </style>
    <div id="<?= esc_attr($id); ?>" class="c-banner <?= $banner_class; ?> l-section-top <?= $mask_class; ?>  <?= $data["color_schema"]; ?> " <?= $background_image; ?>>

        <?php
        if ($background["video"]) :
        ?>
            <video playsinline autoplay muted loop id="introVideo" class="banner__video">
                <source src="<?= $background["video"]["url"] ?>" type="video/mp4">
                Your browser does not support HTML5 video.
            </video>
        <?php
        endif;
        ?>

        <div class="container-fluid u-z-index-10 <?= $settings["horizontal_aligment"] ?>">
            <div class="row">
                <div class="col-12">
                    <div class="banner__content ">

                        <?php if ($content["title"]) : ?>

                            <<?= $heading_tag; ?> class="banner__title custom-title-colour">
                                <?= $content["title"] ?>
                            </<?= $heading_tag; ?>>

                        <?php endif; ?>

                        <?php if ($content["description"]) : ?>

                            <div class="banner__desc wysiwyg">
                                <?= $content["description"] ?>
                            </div>

                        <?php endif; ?>

                        <?php
                        if (isset($ctas["button_cta_left"]) && $ctas["button_cta_left"] || isset($ctas["button_cta_right"]) && $ctas["button_cta_right"]) :
                        ?>
                            <div class="desc__bottom"></div>
                        <?php
                        endif;
                        ?>


                        <?php if ((isset($ctas["button_cta_left"]) && $ctas["button_cta_left"]) || (isset($ctas["button_cta_right"]) && $ctas["button_cta_right"])) : ?>
                            <div class="banner__cta-buttons" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                <?php
                                $ml = "";
                                $mr = "";

                                if ($settings["horizontal_aligment"] === "left") :
                                    $mr = isset($ctas["button_cta_right"]) && $ctas["button_cta_right"]  ? "mr-3" : "";
                                endif;

                                if ($settings["horizontal_aligment"] === "center") :
                                    $ml = isset($ctas["button_cta_right"]) && $ctas["button_cta_right"]  ? "ml-2" : "";
                                    $mr = isset($ctas["button_cta_right"]) && $ctas["button_cta_right"]  ? "mr-2" : "";
                                endif;

                                if ($settings["horizontal_aligment"] === "right") :
                                    $ml = isset($ctas["button_cta_right"]) && $ctas["button_cta_right"]  ? "ml-3" : "";
                                endif;

                                echo btn_from_link($ctas["button_cta_left"], $ml . " std-btn-primary " . $mr);
                                echo btn_from_link($ctas["button_cta_right"], $ml . " std-btn-secondary " . $mr);
                                ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


