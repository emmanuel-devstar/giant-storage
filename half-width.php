<div class="banner__start">
    <?php
    $data = block_start("tm_slide_" . $index, $block, $settings);
    $id = $data["id"];
    $color_schema = (empty($data["color_schema"])) ? "section-bright" : $data["color_schema"];
    
    // Check for remove_padding_bottom setting - check both carousel and slide settings
    $remove_padding_bottom = false;
    if (isset($carousel["remove_padding_bottom"]) && $carousel["remove_padding_bottom"]) {
        $remove_padding_bottom = true;
    } elseif (isset($settings) && is_array($settings) && !empty($settings["remove_padding_bottom"])) {
        $remove_padding_bottom = true;
    }
    $no_padding_class = $remove_padding_bottom ? " c-banner--no-padding-bottom" : "";
    ?>
    <style>
    div#<?= esc_attr($id); ?>.c-banner.l-half {
        padding-top: 120px !important;
        padding-bottom: 100px;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }
    @media screen and (max-width: 767.98px) {
        div#<?= esc_attr($id); ?>.c-banner.l-half {
            padding-top: 60px !important;
        }
    }
    div#<?= esc_attr($id); ?>.c-banner.l-half.c-banner--no-padding-bottom {
        padding-bottom: 0 !important;
    }
    </style>
    <div id="<?= esc_attr($id); ?>" class="c-banner <?= $banner_class; ?> l-half <?= $color_schema ?><?= $no_padding_class; ?>">

        <div class="container-fluid ">

            <div class="row  u-w-1350-100 <?= $carousel["image_aligment"]; ?> ">
                <div class="col-12 col-xl-6 col__content pl-xl-0">
                    <div class="banner__content">
                        <?php if ($content["title"]) : ?>

                            <<?= $heading_tag; ?> class="banner__title custom-title-colour">
                                <?= $content["title"] ?>
                            </<?= $heading_tag; ?>>

                        <?php endif; ?>

                        <?php if ($content["description"]) : ?>

                            <div class="banner__desc wysiwyg u-br-none">
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

                        <?php
                        $btn_class = isset($ctas["button_cta_right"]) ? "std-btn-primary mr-3 mb-3" : "std-btn-primary mr-3 mb-0";
                        echo btn_from_link($ctas["button_cta_left"], $btn_class);
                        ?>
                        <?php
                        echo btn_from_link($ctas["button_cta_right"], "std-btn-secondary mb-0");
                        ?>

                    </div>
                </div>
                <div class="col-12 col-xl-6 pr-xl-0">
                    <?php
                    if ($background["image"]) :
                    ?>
                        <img class="banner__image" src="<?= $background["image"]["sizes"]["extra_large"]; ?>" alt="<?= $background["image"]["alt"] ?>">
                    <?php
                    endif;
                    ?>
                </div>

            </div>

        </div>

    </div>
</div>