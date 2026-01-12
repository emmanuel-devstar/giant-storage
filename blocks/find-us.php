<?php

/**
 * Block Name: Find Us
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

$bullets = $content["bullets"] ?? [];
if (!is_array($bullets)) {
    $bullets = [];
}

$data = block_start("find-us", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];
$h_tag = $data["h_tag"];

$headline = $content["headline_text"] ?? "";
$address = $content["address_text"] ?? "";
$map_url = $content["map_url"] ?? "";
$map_api_key = $content["map_api_key"] ?? "";

// Build map src with optional API key appended if not present.
$map_src = $map_url;
if (!empty($map_src) && !empty($map_api_key) && strpos($map_src, "key=") === false) {
    $map_src .= (strpos($map_src, '?') === false ? '?' : '&') . 'key=' . urlencode($map_api_key);
}
?>
<style>
section#<?= esc_attr($id); ?>.c-section--find-us .find-us__map-inner {
    width: 500px !important;
    height: 278px !important;
    max-width: 100%;
}
</style>
<section id="<?= esc_attr($id); ?>" class="c-section c-section--find-us <?= esc_attr($color_schema); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <?php if (!empty($headline)) : ?>
                    <<?= $h_tag; ?> class="find-us__title custom-title-colour">
                        <?= esc_html($headline); ?>
                    </<?= $h_tag; ?>>
                <?php endif; ?>

                <div class="find-us">

                    <div class="find-us__map">
                        <?php if (!empty($map_src)) : ?>
                            <div class="find-us__map-inner">
                                <iframe
                                    src="<?= esc_url($map_src); ?>"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="find-us__content">
                        <?php if (!empty($address)) : ?>
                            <div class="find-us__address wysiwyg">
                                <?= wp_kses_post($address); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($bullets)) : ?>
                            <ul class="find-us__list">
                                <?php foreach ($bullets as $item) :
                                    $text = $item["text"] ?? "";
                                    if (empty($text)) {
                                        continue;
                                    }
                                ?>
                                    <li class="find-us__list-item">
                                        <span class="find-us__list-dot" aria-hidden="true"></span>
                                        <span><?= esc_html($text); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if (!empty($content["note"])) : ?>
                            <div class="find-us__note">
                                <?= esc_html($content["note"]); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
