<div class="banner__start">

    <div class="c-banner l-text">

        <div class="container-fluid u-z-index-10 <?= $carousel["horizontal_aligment"] ?>">
            <div class="row">
                <div class="col-12">
                    <div class="banner__content ">

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

                        switch ($carousel["horizontal_aligment"]) {
                            case "left":
                                $margins = "mr-4";
                                break;
                            case "center":
                                $margins = "ml-2 mr-2";
                                break;
                            case "right":
                                $margins = "ml-4";
                                break;
                            default:
                                $margins = "ml-2 mr-2";
                        }



                        $mr = isset($ctas["button_cta_right"]) && $ctas["button_cta_right"]  ? $margins : "";

                        $btn_class_1 = "std-btn-primary mb-3";
                        $btn_class_2 = "std-btn-secondary mb-0";

                        echo btn_from_link($ctas["button_cta_left"], $btn_class_1  . " "  . $mr);
                        ?>
                        <?php
                        echo btn_from_link($ctas["button_cta_right"], $btn_class_2 . " " . $mr);
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>