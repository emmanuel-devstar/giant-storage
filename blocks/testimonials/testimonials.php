<?php

/**
 * Block Testimonials
 */
?>

<?php
$testimonials = get_field("testimonials");
$settings = get_field("settings");

$data = block_start("testimonials", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];

$class = ($block["align"] === "wide") ?  "col-12" : "col-12 col-xl-10 mx-auto";
$block_class = $block["className"] ?? "";
?>

<div class="c-section--testimonials <?= $color_schema; ?> <?= esc_attr($block_class); ?> " id="<?php echo esc_attr($id); ?>">
    <div class="container-fluid ">
        <div class="row">
            <div class="<?= $class ?> ">
                <div class="u-relative">
                    <div class="testimonials-wrapper testimonials-js owl-carousel owl-theme">

                        <?php
                        if ($testimonials && is_array($testimonials)) :
                            foreach ($testimonials as $testimonial) :
                        ?>
                            <div class="t__content">
                                <div class="t__quote wysiwyg settings_content"><?= $testimonial["quote"]; ?></div>
                                <?php
                                // Get name and company, handle both array keys and potential whitespace
                                $name = isset($testimonial["name"]) ? trim($testimonial["name"]) : "";
                                $company = isset($testimonial["company"]) ? trim($testimonial["company"]) : "";
                                
                                if (!empty($name)) :
                                ?>
                                    <div class="u-nav">
                                        <p class="t__name"><?= esc_html($name); ?></p>
                                        <?php if (!empty($company)) : ?>
                                            <p class="t__company"><?= esc_html($company); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php
                                endif;
                                ?>
                            </div>
                        <?php
                            endforeach;
                        endif;
                        ?>

                    </div>
                    <div class="t__nav l-btns-testimonials team-nav-js">
                        <div class="next-js o-nav-btn "> <?= file_get_contents(IMAGES . '/icons/arrow-right.svg'); ?> </div>
                        <div class="prev-js o-nav-btn "> <?= file_get_contents(IMAGES . '/icons/arrow-left.svg'); ?> </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</div>

<?php
wp_enqueue_script('testimonials-js', get_template_directory_uri() . '/blocks/testimonials/testimonials.js', array('jquery'), filemtime(get_template_directory() . '/blocks/testimonials/testimonials.js'), false);
?>
