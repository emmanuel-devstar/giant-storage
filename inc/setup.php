<?php
DEFINE("THEME", get_template_directory());
DEFINE("THEME_URI", get_template_directory_uri());

DEFINE("BLOCKS", get_template_directory() . "/blocks");
DEFINE("BLOCKS_URI", get_template_directory_uri() . "/blocks");

DEFINE("IMAGES", get_template_directory_uri() . "/images");
DEFINE("ID_FRONT_PAGE", get_option('page_on_front'));

DEFINE("CSS_NAME", "style.css");
DEFINE("JS_NAME", "app.js");

DEFINE("CSS_VER", filemtime(get_template_directory() . "/css/" . CSS_NAME));
DEFINE("JS_VER", filemtime(get_template_directory() . '/js/' . JS_NAME));



function init_autoload()
{
    // Check if vendor/autoload.php exists before requiring it
    $vendor_autoload = get_template_directory() . '/vendor/autoload.php';
    if (file_exists($vendor_autoload)) {
        require $vendor_autoload;
    }

    add_theme_support('responsive-embeds');

    // Only initialize Configuration if class exists
    if (class_exists('Configuration')) {
        Configuration::init();
    }

    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title'    => 'PHP Mailer',
            'menu_title'    => 'PHP Mailer',
            'menu_slug'     => 'php-mailer',
            'capability'    => 'manage_options',
            'parent_slug'   => 'options-general.php', // Adds a subpage in "Settings"
            'redirect'      => false
        ));
    }
}
add_action('init', 'init_autoload');



function theme_min_scripts()
{

    $fonts = get_fields("option")["fonts"];
    $root_primary = $fonts["root_primary_font"];
    $root_secondary = $fonts["root_secondary_font"];

    $f_primary = ($root_primary) ? $root_primary : "Roboto";
    $f_primary = str_replace(' ', '+', $f_primary);
    $f_secondary = ($root_secondary) ? $root_secondary : "Playfair Display";
    $f_secondary = str_replace(' ', '+', $f_secondary);

    wp_enqueue_style('modular-font-primary', "https://fonts.googleapis.com/css2?family=" . $f_primary . ":wght@300;400;500;700;900&display=swap");
    wp_enqueue_style('modular-font-secondary', "https://fonts.googleapis.com/css2?family=" . $f_secondary . ":wght@300;400;500;700;900&display=swap");


    wp_enqueue_style('theme_min-style', get_stylesheet_uri());



    wp_enqueue_script('scripts-js', get_template_directory_uri() . '/js/' . JS_NAME, array('jquery'), JS_VER, true);

    //wp_enqueue_script('lazy-images-js', get_template_directory_uri() . '/js/jquery.lazyloadxt.min.js', array('jquery'),false,true);
    //wp_enqueue_script('lazy-images-bg-js', get_template_directory_uri() . '/js/jquery.lazyloadxt.bg.min.js', array('jquery'),false,true);    

    wp_enqueue_style('css', get_template_directory_uri() . '/css/' . CSS_NAME, array(), CSS_VER);
}

add_action('wp_enqueue_scripts', 'theme_min_scripts');


function theme_setup()
{
    add_theme_support('post-thumbnails');
    add_theme_support('yoast-seo-breadcrumbs');

    register_nav_menus(array(
        'menu-top' => 'Menu top',
        'menu-footer' => "Menu footer"
    ));

    add_image_size('favicon', 32, 32);
    add_image_size('custom_medium', 700, 700);
    add_image_size('extra_large', 1500, 1500);

    load_theme_textdomain('theme_min', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'theme_setup');


function hide_editor()
{
    if (!is_admin()) return;

    if (!isset($_GET["post"]) && !isset($_POST["post_id"])) return;

    $post_id = $_GET['post'] ?? ($_POST['post_ID'] ?? 0);
    if (!isset($post_id)) return;

    $template_file = get_post_meta($post_id, '_wp_page_template', true);

    $templates = array();
    $templates[] = 'templates/contact.php';

    if (in_array($template_file, $templates)) {
        remove_post_type_support('page', 'editor');
    }
}
add_action('admin_init', 'hide_editor');


function hide_acf()
{
    global $submenu;
    global $_registered_pages;

    if (Configuration::$server === "production") {
        add_filter('acf/settings/show_admin', '__return_false');
        remove_menu_page('edit.php?post_type=acf-field-group');

        $_registered_pages["tools_page_acf-tools"] = true;

        $submenu['tools.php'][] = ['ACF', 'manage_options', admin_url('edit.php?post_type=acf-field-group')];
        $submenu['tools.php'][] = ['ACF Tools', 'manage_options', admin_url('edit.php?post_type=acf-field-group&page=acf-tools')];
    }
}
add_action('admin_menu', 'hide_acf', 999);


define('ALLOW_UNFILTERED_UPLOADS', true);
function add_file_types_to_uploads($file_types)
{
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes);
    return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');

//** *Enable upload for webp image files.*/
function webp_upload_mimes($existing_mimes)
{
    $existing_mimes['webp'] = 'image/webp';
    return $existing_mimes;
}
add_filter('mime_types', 'webp_upload_mimes');

function custom_excerpt_length($length)
{
    return 24;
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);

add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});
