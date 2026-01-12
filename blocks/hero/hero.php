<?php

/**
 * Block Name: Hero
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

$list = $content["list"] ?? [];
if (!is_array($list)) {
    $list = [];
}

$data = block_start("hero", $block, $settings, "section-dark");
$id = $data["id"];
$color_schema = $data["color_schema"];
$h_tag = $data["h_tag"];

$title = $content["title"] ?? "";
$description = $content["description"] ?? "";
$cta = $content["cta"] ?? [];
$cta_url = $cta["check_unit_availability"] ?? "";
$additional_cta_text = $cta["additional_cta_text"] ?? "";
$image = $content["image"] ?? null;

$background = $settings["background"] ?? "#0b0a29";
?>

<section id="<?= esc_attr($id); ?>" class="c-section c-section--hero <?= esc_attr($color_schema); ?>" style="background: <?= esc_attr($background); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="hero">
                    <div class="hero__content">
                        <?php if (!empty($title)) : ?>
                            <<?= $h_tag; ?> class="hero__title custom-title-colour">
                                <?= esc_html($title); ?>
                            </<?= $h_tag; ?>>
                        <?php endif; ?>

                        <?php if (!empty($description)) : ?>
                            <div class="hero__description wysiwyg">
                                <?= wp_kses_post($description); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($list)) : ?>
                            <ul class="hero__list">
                                <?php foreach ($list as $item) :
                                    $text = $item["text"] ?? "";
                                    if (empty($text)) {
                                        continue;
                                    }
                                ?>
                                    <li class="hero__list-item">
                                        <span class="hero__list-check" aria-hidden="true">
                                            <?= file_get_contents(IMAGES . '/icons/check-mark.svg'); ?>
                                        </span>
                                        <span><?= esc_html($text); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <div class="hero__actions">
                            <?php if (!empty($cta_url)) : ?>
                                <div class="hero__cta-primary">
                                    <a href="<?= esc_url($cta_url); ?>" target="_blank" rel="noopener noreferrer" class="btn btn--highlighted hover-highlighted">
                                        CHECK UNIT AVAILABILITY
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($additional_cta_text)) : ?>
                                <div class="hero__cta-text">
                                    <?= esc_html($additional_cta_text); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($image)) : ?>
                        <div class="hero__image">
                            <img src="<?= esc_url($image["url"]); ?>" alt="<?= esc_attr($image["alt"] ?? ""); ?>">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
