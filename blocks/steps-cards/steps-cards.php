<?php

/**
 * Block Name: Steps Cards
 */

// Check if in preview mode
$is_preview = isset($block['data']['is_preview']) && $block['data']['is_preview'];

if ($is_preview && isset($block['data']['content'])) {
    // Use preview data
    $content = $block['data']['content'];
    $settings = $block['data']['settings'] ?? [];
    $id = 'steps-cards-preview-' . uniqid();
    $color_schema = 'section-white';
    $h_tag = 'h2';
} else {
    // Use actual field data
    $block_post = $block["id"];
    $settings = get_field("settings", $block_post);
    if (!is_array($settings)) {
        $settings = [];
    }
    $content = get_field("content", $block_post);
    if (!is_array($content)) {
        $content = [];
    }
    $data = block_start("steps-cards", $block, $settings, "section-white");
    $id = $data["id"];
    $color_schema = $data["color_schema"];
    $h_tag = $data["h_tag"];
}

$steps = $content["steps"] ?? [];
if (!is_array($steps)) {
    $steps = [];
}

$title = $content["title"] ?? "";
$cta = $content["cta"] ?? null;

$remove_padding_bottom = $settings["remove_padding_bottom"] ?? false;
$section_class = "c-section c-section--steps-cards " . esc_attr($color_schema);
if ($remove_padding_bottom) {
    $section_class .= " c-section--steps-cards-no-padding-bottom";
}
$padding_bottom = $remove_padding_bottom ? "0" : "80px";
?>
<style>
section#<?= esc_attr($id); ?>.c-section--steps-cards {
    padding-top: 120px !important;
    padding-bottom: <?= $padding_bottom; ?> !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}
@media screen and (max-width: 767.98px) {
    section#<?= esc_attr($id); ?>.c-section--steps-cards {
        padding-top: 60px !important;
    }
}

section#<?= esc_attr($id); ?> .steps-cards {
    display: grid !important;
    grid-template-columns: 1fr !important;
    gap: 32px !important;
    margin-bottom: 40px !important;
}

@media screen and (min-width: 768px) {
    section#<?= esc_attr($id); ?> .steps-cards {
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 40px !important;
    }
}

section#<?= esc_attr($id); ?> .steps-cards__item {
    background: #ffffff !important;
    border-radius: 12px !important;
    padding: 32px 24px !important;
    text-align: center !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
    transition: transform 0.3s ease, box-shadow 0.3s ease !important;
}

section#<?= esc_attr($id); ?> .steps-cards__item:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12) !important;
}

section#<?= esc_attr($id); ?> .steps-cards__number {
    width: 60px !important;
    height: 60px !important;
    border-radius: 50% !important;
    background-color: #aa151f !important;
    color: #ffffff !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 24px !important;
    font-weight: 700 !important;
    margin: 0 auto 20px !important;
}

section#<?= esc_attr($id); ?> .steps-cards__title {
    font-size: 22px !important;
    font-weight: 700 !important;
    color: #333333 !important;
    margin-bottom: 12px !important;
}

section#<?= esc_attr($id); ?> .steps-cards__text {
    font-size: 16px !important;
    color: rgba(51, 51, 51, 0.8) !important;
    line-height: 1.6 !important;
    margin: 0 !important;
}

section#<?= esc_attr($id); ?> .steps-cards__cta {
    display: flex !important;
    justify-content: center !important;
    margin-top: 40px !important;
}

section#<?= esc_attr($id); ?> .steps-cards__cta .btn.btn--red {
    border: none !important;
    border-radius: 5px !important;
    color: #fff !important;
    padding: 12px 30px !important;
    font-size: 16px !important;
    font-weight: 700 !important;
    font-family: "Arial", sans-serif !important;
    line-height: normal !important;
    cursor: pointer !important;
    text-decoration: none !important;
    display: inline-block !important;
    transition: all 0.3s !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    background-color: #aa151f !important;
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

                <?php if (!empty($steps)) : ?>
                    <div class="steps-cards">
                        <?php foreach ($steps as $index => $step) :
                            $step_title = $step["title_step"] ?? "";
                            $step_text = $step["text_step"] ?? "";
                            $step_number = $index + 1;
                        ?>
                            <div class="steps-cards__item">
                                <div class="steps-cards__number">
                                    <?= esc_html($step_number); ?>
                                </div>
                                <?php if (!empty($step_title)) : ?>
                                    <h3 class="steps-cards__title">
                                        <?= esc_html($step_title); ?>
                                    </h3>
                                <?php endif; ?>
                                <?php if (!empty($step_text)) : ?>
                                    <div class="steps-cards__text">
                                        <?= wp_kses_post($step_text); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($cta)) : ?>
                    <div class="steps-cards__cta">
                        <?= btn_from_link($cta, "btn btn--red"); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
