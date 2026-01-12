<?php

/**
 * Block Name: Testimonials Cards
 */

$block_post = $block["id"];

$settings = get_field("settings", $block_post);
if (!is_array($settings)) {
    $settings = [];
}

$content = get_field("content", $block_post);
if (!is_array($content)) {
    $content = [];
}

$testimonials = $content["testimonials"] ?? [];
if (!is_array($testimonials)) {
    $testimonials = [];
}

$data = block_start("testimonials-cards", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];
$h_tag = $data["h_tag"];

$title = $content["title"] ?? "";

$remove_padding_bottom = $settings["remove_padding_bottom"] ?? false;
$section_class = "c-section c-section--testimonials-cards " . esc_attr($color_schema);
if ($remove_padding_bottom) {
    $section_class .= " c-section--testimonials-cards-no-padding-bottom";
}
$padding_bottom = $remove_padding_bottom ? "0" : "50px";
?>
<style>
section#<?= esc_attr($id); ?>.c-section--testimonials-cards {
    padding-top: 120px !important;
    padding-bottom: <?= $padding_bottom; ?> !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}
</style>
<section id="<?= esc_attr($id); ?>" class="<?= $section_class; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                <?php if (!empty($title)) : ?>
                    <<?= $h_tag; ?> class="section__title custom-title-colour text-center">
                        <?= esc_html($title); ?>
                    </<?= $h_tag; ?>>
                <?php endif; ?>

                <?php if (!empty($testimonials)) : ?>
                    <div class="testimonials-cards">
                        <?php foreach ($testimonials as $testimonial) :
                            $stars = $testimonial["stars"] ?? "5";
                            $name = $testimonial["author"] ?? "";
                            $text = $testimonial["text"] ?? "";
                            if (empty($text)) {
                                continue;
                            }
                        ?>
                            <div class="testimonials-cards__item">
                                <?php if (!empty($stars)) : ?>
                                    <div class="testimonials-cards__stars">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <span class="star <?= $i <= intval($stars) ? 'star--filled' : ''; ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="testimonials-cards__text">
                                    <?= wp_kses_post($text); ?>
                                </div>
                                <?php if (!empty($name)) : ?>
                                    <div class="testimonials-cards__author">
                                        <?= esc_html($name); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
