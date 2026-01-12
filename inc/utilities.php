<?php
add_filter('body_class', 'custom_class');
function custom_class($classes)
{
    if (!is_admin()) {
        /*$classes[] = 'example';*/
        return $classes;
    }
}

function get_post_img($id, $size = 'medium_large')
{
    // Thumbnail (150 x 150 hard cropped)
    // Medium resolution (300 x 300 max height 300px)
    // Medium Large (added in WP 4.4) resolution (768 x 0 infinite height)
    // Large resolution (1024 x 1024 max height 1024px)
    // Full resolution (original size uploaded)

    //With WooCommerce
    // Shop thumbnail (180 x 180 hard cropped)
    // Shop catalog (300 x 300 hard cropped)
    // Shop single (600 x 600 hard cropped)

    $img = wp_get_attachment_image_src(get_post_thumbnail_id($id), $size, false)[0];
    return $img;
}

function get_post_alt($id)
{
    return get_post_meta($id, '_wp_attachment_image_alt', TRUE);
}

function store_content_of_function($callback, $args)
{
    ob_start();

    call_user_func_array($callback, $args);

    return ob_get_clean();
}

function show_if_exist($template, $value)
{
    if ($value)
        printf($template, $value);
}

function btn_from_link($button, $classes = "")
{

    if (isset($button) && $button) :
        $rel = ($button["target"] === "_blank") ? 'rel="external nofollow"' : '';
?>
        <a href="<?= $button["url"] ?>" target="<?= $button["target"] ?>" <?= $rel ?> class="<?= $classes; ?>"><?= $button["title"] ?> </a>
    <?php
    endif;
}


function get_permalink_by_template($template_file_php)
{
    global $wpdb;
    $filename = "templates/$template_file_php.php";
    $pages = $wpdb->get_results("SELECT `post_id` FROM $wpdb->postmeta
      WHERE `meta_key` ='_wp_page_template' AND `meta_value` = '$filename' ", ARRAY_A);
    return $pages ? get_permalink($pages[0]['post_id']) : null;
}

function wysiwyg_clean($value)
{
    return trim(strip_tags($value, '<h1><h2><h3><h4><h5><h6><span><br><p><b><strong><em><a><ul><ol><li>'));
}

function isColorDark($hexColor, $threshold = 0.7)
{

    $hexColor = ltrim($hexColor, '#');

    // Convert hex to RGB values
    $red = hexdec(substr($hexColor, 0, 2));
    $green = hexdec(substr($hexColor, 2, 2));
    $blue = hexdec(substr($hexColor, 4, 2));

    // Calculate luminance
    $luminance = (0.299 * $red + 0.587 * $green + 0.114 * $blue) / 255;

    // You can adjust the threshold to your preference    

    return $luminance <= $threshold;
}

function isColorBright($hexColor, $threshold = 0.7)
{

    $hexColor = ltrim($hexColor, '#');

    // Convert hex to RGB values
    $red = hexdec(substr($hexColor, 0, 2));
    $green = hexdec(substr($hexColor, 2, 2));
    $blue = hexdec(substr($hexColor, 4, 2));

    // Calculate luminance
    $luminance = (0.299 * $red + 0.587 * $green + 0.114 * $blue) / 255;

    // You can adjust the threshold to your preference    

    return $luminance >= $threshold;
}

function isColorBlack($hexColor)
{
    return isColorDark($hexColor, 0.19);
}

function isColorWhite($hexColor)
{
    return isColorBright($hexColor, 0.91);
}

function block_start($name, $block, $settings, $color_schema = null)
{
    // Ensure $settings is an array
    if (!is_array($settings)) {
        $settings = [];
    }
    
    // Static ID based on block name
    $id = $name . '-block';

    if (!empty($block['anchor'])) {
        $id = $block['anchor'];
    }

    $custom_background = $settings["background"] ?? null;

    if (!empty($custom_background)) :
        $color_schema = (isColorDark($custom_background)) ? "section-dark" : "section-bright";

    ?>
        <style>
            #<?= esc_attr($id); ?> {
                background-color: <?= $custom_background ?>;
            }
        </style>
    <?php
    else:

    endif;

    $title_colour = $settings["title_colour"] ?? null;
    if (!empty($title_colour)) :
    ?>
        <style>
            #<?= esc_attr($id) . " "; ?>.custom-title-colour {
                color: <?= $title_colour ?> !important;
            }
        </style>
    <?php
    endif;


    $text_colour = $settings["text_colour"] ?? null;
    if (!empty($text_colour)) :
        $color_schema .= " section-custom";
    ?>
        <style>
            #<?= esc_attr($id); ?> {
                --modular-section-custom-colour: <?= $text_colour ?> !important;
            }
        </style>
    <?php
    endif;

    /*     if ($color_schema === "section-dark" && strtoupper($settings["color_of_highlighted"]) === "#FFFFFF") {
        $color_schema .= " highlighted-white";
    }

    if (($color_schema === "section-bright" || $color_schema === "section-transparent") && strtoupper($settings["color_of_highlighted"]) === "#000000") {
        $color_schema .= " highlighted-black";
    } */


    $color_of_highlighted = $settings["color_of_highlighted"] ?? null;
    if ($color_schema === "section-dark" && $color_of_highlighted && isColorWhite($color_of_highlighted)) {
        $color_schema .= " highlighted-white";
    }

    if (($color_schema === "section-bright" || $color_schema === "section-transparent") && ($color_of_highlighted && isColorBlack($color_of_highlighted))) {
        $color_schema .= " highlighted-black";
    }

    $color_highlighted = $color_of_highlighted;
    if (!empty($color_highlighted)) :
    ?>
        <style>
            #<?= esc_attr($id); ?> {
                --modular-highlighted: <?= $color_highlighted ?>;
            }
        </style>
        <?php
        if ($name === "banner_form") :
            $h_rgb = hexToRgb($color_highlighted);
        ?>
            <style>
                #<?= esc_attr($id) . " "; ?>.form__input {
                    background-color: rgba(<?= $h_rgb[0] . "," . $h_rgb[1] . "," . $h_rgb[2] ?>, 0.18) !important;
                }
            </style>
        <?php
        endif;
    endif;

    $pt = $settings["padding_top"] ?? null;
    if ($pt === "no") :
        ?>
        <style>
            #<?= esc_attr($id); ?> {
                padding-top: 0 !important;
            }
        </style>
    <?php
    endif;

    $pb = $settings["padding_bottom"] ?? null;
    if ($pb === "no") :
    ?>
        <style>
            #<?= esc_attr($id); ?> {
                padding-bottom: 0 !important;
            }
        </style>
    <?php
    endif;

    $rpb = $settings["remove_padding_bottom"] ?? null;
    if ($rpb && $name !== "contact") :
        $class = "";
        if ($name == "text") $class = ".l-text";
        if ($name == "text_media") $class = ".c-banner.l-half";
    ?>
        <style>
            div#<?= esc_attr($id) . $class; ?> {
                padding-bottom: 0 !important;
            }
        </style>
    <?php
    endif;

    if (isset($settings["pt_mobile"])) :
        $pt_mobile = $settings["pt_mobile"];
    ?>
        <style>
            #<?= esc_attr($id); ?> {
                padding-top: <?= $pt_mobile . "px" ?> !important;
            }
        </style>
    <?php
    endif;

    if (isset($settings["pt_desktop"])) :
        $pt_desktop = $settings["pt_desktop"];
    ?>
        <style>
            @media screen and (min-width: 992px) {
                #<?= esc_attr($id); ?> {
                    padding-top: <?= $pt_desktop . "px" ?> !important;
                }
            }
        </style>
    <?php
    endif;

    $h_tag = (isset($settings["h1"])  && !empty($settings["h1"])) ? "h1" : "h2";

    if (isset($settings["content"]["font_size_mobile"])) :
        $fs_mobile = $settings["content"]["font_size_mobile"];
    ?>
        <style>
            #<?= esc_attr($id); ?> {
                --modular-quote-content-fs: <?= $fs_mobile . "px" ?>;
            }
        </style>
    <?php
    endif;

    if (isset($settings["content"]["font_size_desktop"])) :
        $fs_desktop = $settings["content"]["font_size_desktop"];
    ?>
        <style>
            @media screen and (min-width: 992px) {

                #<?= esc_attr($id); ?> {
                    --modular-quote-content-fs: <?= $fs_desktop . "px" ?>;
                }
            }
        </style>
    <?php
    endif;

    return array("id" => $id, "color_schema" => $color_schema, "h_tag" => $h_tag);
}


function footer_socials_shortcode($atts = [])
{


    ob_start();

    $fields = get_fields("option");
    $company_details = $fields["company_details"];
    $socials = array();

    if ($company_details['sm_repeater']) :
        foreach ($company_details['sm_repeater'] as $key => $social) :
            $socials[$social['name']] = array('icon' => strtolower($social['name']), 'name' => $social['name'], 'url' => $social['url']);
        endforeach;
    endif;


    foreach ($socials as $index => $social) :
    ?>
        <a href="<?= $social["url"] ?>" target="_blank" class="c-media icon-link">

            <?= file_get_contents(IMAGES . '/icons/' . strtolower($social["name"]) . '.svg'); ?>

            <span class="media-body">
                <?= $social["name"]; ?>
            </span>

        </a>

<?php
    endforeach;

    return ob_get_clean();
}

add_action('init', function () {
    add_shortcode('footer_socials', 'footer_socials_shortcode');
});


// https://fontawesome.com/search?p=3&o=r&f=brands
function getSocialIconSvg($platform)
{
    switch ($platform) {
        case 'linkedin':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>';
            break;
        case 'twitter':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>';
            break;
        case 'whatsapp':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>';
            break;
        case 'pinterest':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M496 256c0 137-111 248-248 248-25.6 0-50.2-3.9-73.4-11.1 10.1-16.5 25.2-43.5 30.8-65 3-11.6 15.4-59 15.4-59 8.1 15.4 31.7 28.5 56.8 28.5 74.8 0 128.7-68.8 128.7-154.3 0-81.9-66.9-143.2-152.9-143.2-107 0-163.9 71.8-163.9 150.1 0 36.4 19.4 81.7 50.3 96.1 4.7 2.2 7.2 1.2 8.3-3.3.8-3.4 5-20.3 6.9-28.1.6-2.5.3-4.7-1.7-7.1-10.1-12.5-18.3-35.3-18.3-56.6 0-54.7 41.4-107.6 112-107.6 60.9 0 103.6 41.5 103.6 100.9 0 67.1-33.9 113.6-78 113.6-24.3 0-42.6-20.1-36.7-44.8 7-29.5 20.5-61.3 20.5-82.6 0-19-10.2-34.9-31.4-34.9-24.9 0-44.9 25.7-44.9 60.2 0 22 7.4 36.8 7.4 36.8s-24.5 103.8-29 123.2c-5 21.4-3 51.6-.9 71.2C65.4 450.9 0 361.1 0 256 0 119 111 8 248 8s248 111 248 248z"/></svg>';
            break;
        case 'medium':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M180.5,74.262C80.813,74.262,0,155.633,0,256S80.819,437.738,180.5,437.738,361,356.373,361,256,280.191,74.262,180.5,74.262Zm288.25,10.646c-49.845,0-90.245,76.619-90.245,171.095s40.406,171.1,90.251,171.1,90.251-76.619,90.251-171.1H559C559,161.5,518.6,84.908,468.752,84.908Zm139.506,17.821c-17.526,0-31.735,68.628-31.735,153.274s14.2,153.274,31.735,153.274S640,340.631,640,256C640,171.351,625.785,102.729,608.258,102.729Z"/></svg>';
            break;
        case 'skype':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M424.7 299.8c2.9-14 4.7-28.9 4.7-43.8 0-113.5-91.9-205.3-205.3-205.3-14.9 0-29.7 1.7-43.8 4.7C161.3 40.7 137.7 32 112 32 50.2 32 0 82.2 0 144c0 25.7 8.7 49.3 23.3 68.2-2.9 14-4.7 28.9-4.7 43.8 0 113.5 91.9 205.3 205.3 205.3 14.9 0 29.7-1.7 43.8-4.7 19 14.6 42.6 23.3 68.2 23.3 61.8 0 112-50.2 112-112 .1-25.6-8.6-49.2-23.2-68.1zm-194.6 91.5c-65.6 0-120.5-29.2-120.5-65 0-16 9-30.6 29.5-30.6 31.2 0 34.1 44.9 88.1 44.9 25.7 0 42.3-11.4 42.3-26.3 0-18.7-16-21.6-42-28-62.5-15.4-117.8-22-117.8-87.2 0-59.2 58.6-81.1 109.1-81.1 55.1 0 110.8 21.9 110.8 55.4 0 16.9-11.4 31.8-30.3 31.8-28.3 0-29.2-33.5-75-33.5-25.7 0-42 7-42 22.5 0 19.8 20.8 21.8 69.1 33 41.4 9.3 90.7 26.8 90.7 77.6 0 59.1-57.1 86.5-112 86.5z"/></svg>';
            break;
        case 'spotify':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm26.9-65.6c-5.2 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31-76.2c-5.2 0-8.4-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"/></svg>';
            break;
        case 'facebook-messenger':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>';
            break;
        case 'youtube':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>';
            break;
    }

    return null;
}

function sg_first_block_is($block_handle)
{
    $post = get_post();

    if (has_blocks($post->post_content)) {
        $blocks = parse_blocks($post->post_content);

        if ($blocks[0]['blockName'] === $block_handle) {
            return true;
        } else {
            return false;
        }
    }
}

function get_first_block($post)
{
    if (has_blocks($post->post_content)) {
        $blocks = parse_blocks($post->post_content);

        return $blocks[0];
    }
}

function get_last_block($post)
{
    if (has_blocks($post->post_content)) {
        $blocks = parse_blocks($post->post_content);

        return end($blocks);
    }
}

function is_pagetext_block($block_name)
{
    $blocks = ["core/heading", "core/paragraph", "core/image", 'core/list', 'core/list-item', 'core/separator', 'core/table', 'core/quote'];

    return in_array($block_name, $blocks);
}

function hexToRgb($hex)
{
    // Remove '#' if it exists
    $hex = ltrim($hex, '#');

    // Check if the hex is shorthand (e.g., #f00)
    if (strlen($hex) === 3) {
        $hex = str_repeat($hex[0], 2) . str_repeat($hex[1], 2) . str_repeat($hex[2], 2);
    }

    // Convert hex to RGB values
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    return [$r, $g, $b];
}
