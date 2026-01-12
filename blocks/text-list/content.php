<div class="banner__start">
    <?php
    $content_left = $slide["left"];
    $content_right = $slide["right"];

    $heading_tag = ($index === 0  && $data["h_tag"] === "h1") ? "h1" : "h2";

    $ctas = $content_left["ctas"];

    $data = block_start("tl_slide_" . $index, $block, $settings);
    $id = $data["id"];
    $color_schema = (empty($data["color_schema"])) ? "section-bright" : $data["color_schema"];
    ?>
    <div id="<?= esc_attr($id); ?>" class="c-banner l-text-list  l-half <?= $color_schema ?>">

        <div class="container-fluid ">

            <div class="row  u-w-1350-100">
                <div class="col-12 col-xl-6 col__content pl-xl-0">
                    <div class="banner__content col-left">
                        <?php if ($content_left["heading"]) : ?>

                            <<?= $heading_tag; ?> class="banner__title custom-title-colour">
                                <?= $content_left["heading"] ?>
                            </<?= $heading_tag; ?>>

                        <?php endif; ?>

                        <?php if ($content_left["support_text"]) : ?>

                            <div class="banner__desc wysiwyg">
                                <?= $content_left["support_text"] ?>
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
                        $btn_m = isset($ctas["button_cta_right"]) && $ctas["button_cta_right"]  ? " mr-3 mb-3 mb-lg-0" : " mb-0";

                        echo btn_from_link($ctas["button_cta_left"], "std-btn-primary " . $btn_m);
                        ?>
                        <?php
                        echo btn_from_link($ctas["button_cta_right"], "std-btn-secondary mb-0");
                        ?>

                    </div>
                </div>
                <div class="col-12 col-xl-6 col__content l-content-list pr-xl-0">

                    <div class="banner__content u-shadow  section-white">
                        <?php if ($content_right["heading"]) : ?>
                            <h3 class="content__title">
                                <?= $content_right["heading"] ?>
                            </h3>
                        <?php endif; ?>

                        <?php if ($content_right["support_text"]) : ?>
                            <div class="banner__desc wysiwyg">
                                <?= $content_right["support_text"] ?>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3"></div>
                        
                        <?php if ($content_right["tick_list"]) : ?>
                            <div class="banner__desc wysiwyg parent-checked-list">
                                <ul>
                                <?php 
                                foreach($content_right["tick_list"] as $item):
                                    ?> 
                                    <li>
                                        <?= $item["option"] ?>
                                    </li>
                                    <?php
                                endforeach;
                                ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                    </div>


                </div>

            </div>

        </div>

    </div>
</div>