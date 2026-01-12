<?php

/**
 * Block Name: Steps
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

$steps = $content["steps"] ?? [];
if (!is_array($steps)) {
    $steps = [];
}

$data = block_start("steps", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];
$h_tag = $data["h_tag"];

$title = $content["title"] ?? "";
$cta = $content["cta"] ?? null;
?>

<section id="<?= esc_attr($id); ?>" class="c-section c-section--steps <?= esc_attr($color_schema); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                <?php if (!empty($title)) : ?>
                    <<?= $h_tag; ?> class="section__title custom-title-colour text-center">
                        <?= esc_html($title); ?>
                    </<?= $h_tag; ?>>
                <?php endif; ?>

                <?php if (!empty($steps)) : ?>
                    <div class="steps">
                        <?php foreach ($steps as $index => $step) :
                            $step_title = $step["title_step"] ?? "";
                            $step_text = $step["text_step"] ?? "";
                            $step_small = $step["small_text_step"] ?? "";
                            $step_number = $index + 1;
                        ?>
                            <div class="steps__item">
                                <div class="steps__number">
                                    <?= esc_html($step_number); ?>
                                </div>
                                <?php if (!empty($step_title)) : ?>
                                    <h3 class="steps__title">
                                        <?= esc_html($step_title); ?>
                                    </h3>
                                <?php endif; ?>
                                <?php if (!empty($step_text)) : ?>
                                    <p class="steps__text">
                                        <?= esc_html($step_text); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($step_small)) : ?>
                                    <p class="steps__small">
                                        <?= esc_html($step_small); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($cta)) : ?>
                    <div class="steps__cta">
                        <?= btn_from_link($cta, "btn btn--highlighted hover-highlighted"); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
