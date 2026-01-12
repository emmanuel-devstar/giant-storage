<?php

/**
 * Block Name: Header
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

$data = block_start("header-block", $block, $settings, "section-dark");
$id = $data["id"];

$logo = $content["logo"] ?? get_field('logo') ?? null;
$logo_link = $content["logo_link"] ?? null;
$phone = $content["phone"] ?? "";
$cta = $content["cta"] ?? null;

$background = $settings["background"] ?? "#0b0a29";

$tel_href = "";
if (!empty($phone)) {
    $tel_href = "tel:" . preg_replace('/[^0-9+]/', '', $phone);
}
?>

<style>
    #<?= esc_attr($id); ?> .c-header-block__cta .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 700;
        font-family: 'Arial';
        line-height: normal;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    #<?= esc_attr($id); ?> {
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(11, 10, 41, 0.3);
        padding: 14px 0;
        color: #f3f5ff;
    }

    #<?= esc_attr($id); ?> .c-header-block__inner {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    #<?= esc_attr($id); ?> .c-header-block__brand {
        display: flex;
        align-items: center;
        flex-shrink: 0;
        width: auto;
        min-width: 200px;
    }

    #<?= esc_attr($id); ?> .c-header-block__logo-link {
        display: inline-block;
        text-decoration: none;
        transition: opacity 0.2s ease;
    }

    #<?= esc_attr($id); ?> .c-header-block__logo-link:hover {
        opacity: 0.85;
    }

    #<?= esc_attr($id); ?> .c-header-block__logo {
        max-height: 46px;
        max-width: 240px;
        width: auto;
        height: auto;
        min-width: 150px;
        display: block;
        flex-shrink: 0;
        object-fit: contain;
        visibility: visible !important;
        opacity: 1 !important;
    }

    #<?= esc_attr($id); ?> .c-header-block__spacer {
        flex: 1 1 auto;
    }

    #<?= esc_attr($id); ?> .c-header-block__phone {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #f3f5ff;
        text-decoration: none;
        font-weight: 600;
        transition: opacity 0.2s ease;
    }

    #<?= esc_attr($id); ?> .c-header-block__phone:hover,
    #<?= esc_attr($id); ?> .c-header-block__phone:focus {
        opacity: 0.85;
    }

    #<?= esc_attr($id); ?> .c-header-block__phone-icon {
        display: inline-flex;
        color: #c7cadc;
    }

    #<?= esc_attr($id); ?> .c-header-block__cta .btn {
        margin-left: 16px;
        white-space: nowrap;
        background-color: #b02029;
        color: #fff;
    }

    #<?= esc_attr($id); ?> .c-header-block__cta .btn:hover,
    #<?= esc_attr($id); ?> .c-header-block__cta .btn:focus {
        opacity: 0.9;
    }

    @media (max-width: 767.98px) {
        #<?= esc_attr($id); ?> {
            padding: 10px 0 !important;
        }

        #<?= esc_attr($id); ?> .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        #<?= esc_attr($id); ?> .c-header-block__inner {
            flex-wrap: nowrap !important;
            justify-content: space-between !important;
            gap: 12px !important;
            align-items: center !important;
        }

        #<?= esc_attr($id); ?> .c-header-block__brand {
            min-width: 150px !important;
            width: auto !important;
            flex: 0 0 auto !important;
            order: 1 !important;
            max-width: none !important;
        }

        #<?= esc_attr($id); ?> .c-header-block__spacer {
            display: none !important;
        }

        #<?= esc_attr($id); ?> .c-header-block__phone {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            width: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
        }

        #<?= esc_attr($id); ?> .c-header-block__cta {
            width: auto !important;
            flex: 0 0 auto !important;
            order: 2 !important;
            display: flex !important;
            justify-content: flex-end !important;
            margin-left: 0 !important;
            margin-top: 0 !important;
            max-width: none !important;
        }

        #<?= esc_attr($id); ?> .c-header-block__cta .btn {
            width: auto !important;
            margin-left: 0 !important;
            padding: 10px 20px !important;
            font-size: 14px !important;
            flex-shrink: 0 !important;
        }
    }
</style>

<?php
// Normalize logo to an array with url/alt even if an ID is returned
if (!empty($logo) && is_numeric($logo)) {
    $src = wp_get_attachment_image_src($logo, 'full');
    if ($src) {
        $logo = [
            'url' => $src[0],
            'alt' => get_post_meta($logo, '_wp_attachment_image_alt', true) ?: ''
        ];
    }
}

// Fallback to theme configuration logo if block field is empty
if (empty($logo) && isset(Configuration::$fields["header"]["nav"]["logo"])) {
    $logo = Configuration::$fields["header"]["nav"]["logo"]["white"] ?? Configuration::$fields["header"]["nav"]["logo"]["black"] ?? null;
}
?>

<header id="<?= esc_attr($id); ?>" class="c-header-block" style="background: <?= esc_attr($background); ?>">
    <div class="container-fluid">
        <div class="c-header-block__inner">
            <div class="c-header-block__brand">
                <?php if (!empty($logo)) : ?>
                    <?php if (!empty($logo_link) && !empty($logo_link["url"])) : ?>
                        <a href="<?= esc_url($logo_link["url"]); ?>" 
                           <?= !empty($logo_link["target"]) ? 'target="' . esc_attr($logo_link["target"]) . '"' : ''; ?>
                           class="c-header-block__logo-link">
                            <img src="<?= esc_url($logo["url"]); ?>" alt="<?= esc_attr($logo["alt"] ?? "Logo"); ?>" class="c-header-block__logo">
                        </a>
                    <?php else : ?>
                        <img src="<?= esc_url($logo["url"]); ?>" alt="<?= esc_attr($logo["alt"] ?? "Logo"); ?>" class="c-header-block__logo">
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="c-header-block__spacer"></div>

            <?php if (!empty($phone)) : ?>
                <a class="c-header-block__phone" href="<?= esc_url($tel_href); ?>">
                    <span class="c-header-block__phone-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                            <path d="M6.5 4.5C6.5 3.12 5.38 2 4 2H3C2.45 2 2 2.45 2 3C2 13.49 10.51 22 21 22C21.55 22 22 21.55 22 21V20C22 18.62 20.88 17.5 19.5 17.5H18.12C17.58 17.5 17.08 17.76 16.79 18.21L16.04 19.34C15.75 19.79 15.19 19.96 14.7 19.74C11.25 18.15 7.85 14.76 6.26 11.3C6.04 10.81 6.21 10.25 6.66 9.96L7.79 9.21C8.24 8.92 8.5 8.42 8.5 7.88V6.5C8.5 5.12 7.38 4 6 4H6.5Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="c-header-block__phone-text"><?= esc_html($phone); ?></span>
                </a>
            <?php endif; ?>

            <?php if (!empty($cta)) : ?>
                <div class="c-header-block__cta">
                    <?= btn_from_link($cta, "btn btn--highlighted hover-highlighted"); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>
