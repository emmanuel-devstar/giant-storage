<?php

/**
 * Block Name: Trust Bar
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

$data = block_start("trust-bar", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];
$remove_padding_bottom = $settings["remove_padding_bottom"] ?? false;
$section_class = "c-section c-section--trust-bar " . esc_attr($color_schema);
if ($remove_padding_bottom) {
    $section_class .= " c-section--trust-bar-no-padding-bottom";
}
$padding_bottom = $remove_padding_bottom ? "0" : "50px";
?>
<style>
section#<?= esc_attr($id); ?>.c-section--trust-bar {
    padding-top: 0 !important;
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
            <div class="col-12">
                <div class="trust-bar">
                    <?php if (!empty($list)) : ?>
                        <ul class="trust-bar__list">
                            <?php foreach ($list as $item) :
                                $text = $item["text"] ?? "";
                                if (empty($text)) {
                                    continue;
                                }
                            ?>
                                <li class="trust-bar__item">
                                    <?= esc_html($text); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
