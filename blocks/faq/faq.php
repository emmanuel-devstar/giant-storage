<?php

/**
 * Block FAQ
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

$accordion = $content["accordion"] ?? [];
if (!is_array($accordion)) {
    $accordion = [];
}

$data = block_start("faq", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];
$h_tag = $data["h_tag"];

$title = $content["title"] ?? "";

$remove_padding_bottom = $settings["remove_padding_bottom"] ?? false;
$section_class = "c-section--faq " . esc_attr($color_schema);
if ($remove_padding_bottom) {
    $section_class .= " c-section--faq-no-padding-bottom";
}
$padding_bottom = $remove_padding_bottom ? "0" : "50px";
?>
<style>
div#<?= esc_attr($id); ?>.c-section--faq {
    padding-top: 120px !important;
    padding-bottom: <?= $padding_bottom; ?> !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}
</style>
<div class="<?= $section_class; ?>" id="<?php echo esc_attr($id); ?>">
    <div class="container-fluid container-fluid-lg">
        <?php if (!empty($title)) : ?>
            <<?= $h_tag; ?> class="section__title custom-title-colour text-center">
                <?= esc_html($title); ?>
            </<?= $h_tag; ?>>
        <?php endif; ?>

        <div class="faq-wrapper">
            <?php foreach ($accordion as $item) :
                $question = $item["question"] ?? "";
                $answer = $item["answer"] ?? "";
                if (empty($question) || empty($answer)) {
                    continue;
                }
            ?>
                <div class="faq__row faq-row-js u-no-select">
                    <div class="faq__header">
                        <div class="header__content"><?= esc_html($question); ?></div>
                        <div class="faq__icon">
                            <div class="expand icon__img">
                                <?= file_get_contents(IMAGES . '/icons/expand.svg'); ?>
                            </div>
                            <div class="colapse icon__img">
                                <?= file_get_contents(IMAGES . '/icons/colapse.svg'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="faq__content acc-content-js">
                        <div class="content__wrapper wysiwyg"><?= wp_kses_post($answer); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
wp_enqueue_script('faq-js', get_template_directory_uri() . '/blocks/faq/faq.js', array('jquery'), filemtime(get_template_directory() . '/blocks/faq/faq.js'), false);
?>