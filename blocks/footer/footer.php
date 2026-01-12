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

$data = block_start("footer", $block, $settings, "");
$id = $data["id"];
$color_schema = "";

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


// Remove empty footer with specific ID on live site
$empty_footer_id = "footer-693b1655e863a";
if ($id === $empty_footer_id) {
    // Check if footer is actually empty
    $has_content = !empty($heading) || !empty($availability_text) || !empty($reserve_button) || 
                   !empty($phone_button) || !empty($additional_text) || !empty($copyright_text) || 
                   !empty($footer_links);
    
    if (!$has_content) {
        // Don't render empty footer
        return;
    }
}
?>
<style>
footer#<?= esc_attr($id); ?>.c-section--footer {
    background: transparent !important;
    padding: 0 !important;
    margin: 0 !important;
}
footer#<?= esc_attr($id); ?> .footer__cta {
    background: linear-gradient(135deg, #0b0a29 0%, #1a1942 100%) !important;
    padding: 60px 0 !important;
    text-align: center !important;
    color: white !important;
    width: 100% !important;
}
footer#<?= esc_attr($id); ?> .footer__heading {
    font-size: 2.8rem !important;
    text-align: center !important;
    color: white !important;
    margin-bottom: 24px !important;
}
footer#<?= esc_attr($id); ?> .footer__availability {
    display: inline-block !important;
    padding: 12px 24px !important;
    border: 1px solid #4aa0ec !important;
    border-radius: 4px !important;
    margin-bottom: 24px !important;
    color: white !important;
    background: transparent !important;
}
footer#<?= esc_attr($id); ?> .footer__cta-row {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 16px !important;
    margin-bottom: 24px !important;
}
@media screen and (min-width: 768px) {
    footer#<?= esc_attr($id); ?> .footer__cta-row {
        flex-direction: row !important;
        gap: 24px !important;
    }
}
footer#<?= esc_attr($id); ?> .footer__cta-link {
    display: inline-block !important;
    padding: 14px 32px !important;
    border: 2px solid white !important;
    border-radius: 4px !important;
    text-decoration: none !important;
    color: white !important;
    background: transparent !important;
}
footer#<?= esc_attr($id); ?> .footer__button--phone {
    display: inline-block !important;
    padding: 14px 32px !important;
    border-radius: 4px !important;
    text-decoration: none !important;
    background: #aa151f !important;
    border: 2px solid #aa151f !important;
    color: white !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
}
footer#<?= esc_attr($id); ?> .footer__additional {
    color: white !important;
    font-size: 14px !important;
    margin-top: 16px !important;
}
footer#<?= esc_attr($id); ?> .footer__copyright {
    display: none !important;
    background: #0b0a29 !important;
    padding: 20px 0 !important;
    text-align: center !important;
    color: #999 !important;
    border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
    margin-top: 0 !important;
}
footer#<?= esc_attr($id); ?> .footer__copyright-text {
    color: #999 !important;
    margin-bottom: 8px !important;
}
footer#<?= esc_attr($id); ?> .footer__links {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 16px !important;
    justify-content: center !important;
}
footer#<?= esc_attr($id); ?> .footer__link {
    color: #999 !important;
    text-decoration: none !important;
}
@media screen and (max-width: 767px) {
    footer#<?= esc_attr($id); ?> .footer__cta-link,
    footer#<?= esc_attr($id); ?> .footer__button--phone {
        width: 100% !important;
        max-width: 400px !important;
    }
}
/* Force hide empty footer */
footer#footer-693b1655e863a {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
    overflow: hidden !important;
    padding: 0 !important;
    margin: 0 !important;
    position: absolute !important;
    left: -9999px !important;
}
footer#footer-693b1655e863a * {
    display: none !important;
}
</style>
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
                            <?= wp_kses_post($availability_text); ?>
                        </div>
                    <?php endif; ?>

                    <div class="footer__cta-row">
                        <?php if (!empty($reserve_button) && !empty($reserve_button["url"])) : ?>
                            <a href="<?= esc_url($reserve_button["url"]); ?>" target="<?= esc_attr($reserve_button["target"] ?? ""); ?>" class="footer__cta-link">
                                <?= esc_html($reserve_button["title"] ?? "Reserve Your Unit Online"); ?>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($phone_button) && !empty($phone_button["url"])) : ?>
                            <a href="<?= esc_url($phone_button["url"]); ?>" target="<?= esc_attr($phone_button["target"] ?? ""); ?>" class="footer__button footer__button--phone">
                                <?= esc_html($phone_button["title"] ?? "Questions? Call 01373 481 521"); ?>
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

    <div class="footer__copyright">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <?php if (!empty($copyright_text)) : ?>
                        <p class="footer__copyright-text"><?= esc_html($copyright_text); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($footer_links)) : ?>
                        <div class="footer__links">
                            <?php foreach ($footer_links as $link) : ?>
                                <?php if (!empty($link["link"]) && !empty($link["link"]["url"])) : ?>
                                    <a href="<?= esc_url($link["link"]["url"]); ?>" target="<?= esc_attr($link["link"]["target"] ?? ""); ?>" class="footer__link">
                                        <?= esc_html($link["link"]["title"] ?? ""); ?>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</footer>
