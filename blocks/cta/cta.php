<?php

/**
 * Block Name: CTA
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

$data = block_start("cta", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];
$h_tag = $data["h_tag"];

$title = $content["title"] ?? "";
$text = $content["text"] ?? "";
$phone_button_text = $content["phone_button_text"] ?? "";
$phone_number = $content["phone_number"] ?? "";
$cta = $content["cta"] ?? null;
$additional_text = $content["additional_text"] ?? "";
?>

<section id="<?= esc_attr($id); ?>" class="c-section c-section--cta <?= esc_attr($color_schema); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                <?php if (!empty($title)) : ?>
                    <<?= $h_tag; ?> class="section__title custom-title-colour text-center">
                        <?= esc_html($title); ?>
                    </<?= $h_tag; ?>>
                <?php endif; ?>

                <?php if (!empty($text)) : ?>
                    <div class="cta__text text-center">
                        <?= esc_html($text); ?>
                    </div>
                <?php endif; ?>

                <div class="cta__actions">
                    <?php if (!empty($cta)) : ?>
                        <div class="cta__button-primary">
                            <?= btn_from_link($cta, "btn btn--highlighted hover-highlighted"); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($phone_number)) : ?>
                        <div class="cta__button-phone">
                            <a href="tel:<?= esc_attr(preg_replace('/[^0-9+]/', '', $phone_number)); ?>" class="btn btn--red">
                                <?= esc_html($phone_button_text); ?> <?= esc_html($phone_number); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($additional_text)) : ?>
                    <div class="cta__additional text-center">
                        <?= esc_html($additional_text); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
