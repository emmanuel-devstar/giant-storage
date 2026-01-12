<?php

/**
 * Block Name: Footer
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

$data = block_start("footer", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];

$heading = $content["heading"] ?? "";
$availability_text = $content["availability_text"] ?? "";
$reserve_button = $content["reserve_button"] ?? null;
$phone_button = $content["phone_button"] ?? null;
$additional_text = $content["additional_text"] ?? "";
$copyright_text = $content["copyright_text"] ?? "";
$footer_links = $content["footer_links"] ?? [];
if (!is_array($footer_links)) {
    $footer_links = [];
}
?>

<footer id="<?= esc_attr($id); ?>" class="c-section c-section--footer <?= esc_attr($color_schema); ?>">
    <div class="footer__cta">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <?php if (!empty($heading)) : ?>
                        <h2 class="footer__heading"><?= esc_html($heading); ?></h2>
                    <?php endif; ?>

                    <?php if (!empty($availability_text)) : ?>
                        <div class="footer__availability">
                            <?= esc_html($availability_text); ?>
                        </div>
                    <?php endif; ?>

                    <div class="footer__buttons">
                        <?php if (!empty($reserve_button) && !empty($reserve_button["url"])) : ?>
                            <a href="<?= esc_url($reserve_button["url"]); ?>" target="<?= esc_attr($reserve_button["target"] ?? ""); ?>" class="footer__button footer__button--reserve">
                                <?= esc_html($reserve_button["title"] ?? "RESERVE YOUR UNIT ONLINE"); ?>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($phone_button) && !empty($phone_button["url"])) : ?>
                            <a href="<?= esc_url($phone_button["url"]); ?>" target="<?= esc_attr($phone_button["target"] ?? ""); ?>" class="footer__button footer__button--phone">
                                <?= esc_html($phone_button["title"] ?? "QUESTIONS? CALL 01373 481 521"); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($additional_text)) : ?>
                        <div class="footer__additional">
                            <?= esc_html($additional_text); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($copyright_text) || !empty($footer_links)) : ?>
        <div class="footer__copyright">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php if (!empty($copyright_text)) : ?>
                            <div class="footer__copyright-text"><?= esc_html($copyright_text); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($footer_links)) : ?>
                            <nav class="footer__links">
                                <?php foreach ($footer_links as $link) :
                                    $link_data = $link["link"] ?? null;
                                    if (empty($link_data) || empty($link_data["url"])) {
                                        continue;
                                    }
                                    $rel = ($link_data["target"] === "_blank") ? 'rel="external nofollow"' : '';
                                ?>
                                    <a href="<?= esc_url($link_data["url"]); ?>" target="<?= esc_attr($link_data["target"] ?? ""); ?>" <?= $rel; ?> class="footer__link">
                                        <?= esc_html($link_data["title"] ?? ""); ?>
                                    </a>
                                <?php endforeach; ?>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</footer>
