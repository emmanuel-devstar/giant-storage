<?php
function checkCategoryOrder($categories)
{


    //custom category array
    $temp = array(
        'slug' => 'modules',
        'title' => 'Modular'
    );


    //adding new custom category
    $newCategories = array();
    $newCategories[0] = $temp;

    foreach ($categories as $category) {
        if ($category["slug"] === "modules") continue;
        $newCategories[] = $category;
    }


    //return new categories
    return $newCategories;
}
add_filter('block_categories', 'checkCategoryOrder', 99, 1);

function mytheme_setup()
{
    add_theme_support('align-wide');
}
add_action('after_setup_theme', 'mytheme_setup');

function load_custom_wp_admin_style()
{
    wp_register_style('custom_wp_admin_css', get_stylesheet_directory_uri() . '/css/gutenberg.min.css', false, filemtime(get_stylesheet_directory() . '/css/gutenberg.min.css'));
    wp_enqueue_style('custom_wp_admin_css');
}

add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');

function pt_block_category($categories, $post)
{
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'modules',
                'title' => 'Modular'
            )
        )
    );
}

add_filter('block_categories', 'pt_block_category', 10, 2);

add_action('acf/init', 'my_acf_init');
function my_acf_init()
{


    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'header-block',
            'title' => 'Header',
            'description' => __('Header block'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('header', 'navigation'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'hero',
            'title' => 'Hero',
            'description' => __('Hero block'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('hero', 'banner'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'trust-bar',
            'title' => 'Trust Bar',
            'description' => __('Trust Bar'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('trust', 'bar'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'text-media',
            'title' => 'Text & Media',
            'description' => __('Text & Media'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('text & media', 'banner'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'steps',
            'title' => 'Steps',
            'description' => __('Steps'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('steps', 'process'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'steps-cards',
            'title' => 'Steps Cards',
            'description' => __('3-column card block with CTA'),
            'mode' => 'preview',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('steps', 'cards', 'process', 'cta'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            ),
            'example'  => array(
                'attributes' => array(
                    'mode' => 'preview',
                    'data' => array(
                        'is_preview' => true,
                        'content' => array(
                            'title' => 'Getting Started is Easy',
                            'steps' => array(
                                array(
                                    'title_step' => 'Choose Your Size',
                                    'text_step' => '8ft (garden shed) → 20ft (single garage) → 40ft (double garage) Not sure? We\'ll help you pick'
                                ),
                                array(
                                    'title_step' => 'Move In Today',
                                    'text_step' => 'Drive right up. Use our free equipment. Take your time.'
                                ),
                                array(
                                    'title_step' => 'Access 24/7',
                                    'text_step' => 'Your stuff. Your schedule. Come and go as you please.'
                                )
                            ),
                            'cta' => array(
                                'title' => 'RESERVE YOUR UNIT NOW',
                                'url' => '#'
                            )
                        ),
                        'settings' => array()
                    )
                )
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'faq',
            'title' => 'FAQ',
            'description' => __('FAQ'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('FAQ'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'cta',
            'title' => 'CTA',
            'description' => __('Call to Action'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('cta', 'call to action'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'footer',
            'title' => 'Footer',
            'description' => __('Footer'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('footer'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'text',
            'title' => 'Text',
            'description' => __('Text'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('text', 'banner'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'banner-with-form',
            'title' => 'Banner with form',
            'description' => __('Banner with form'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('form', 'banner'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'text-list',
            'title' => 'Text & List',
            'description' => __('Text list'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('text', 'list'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'usp',
            'title' => 'USPs & Services',
            'description' => __('USP'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('columns'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'clients',
            'title' => 'Clients',
            'description' => __('Clients'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('Clients'),
            'align'        => 'none',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'team',
            'title' => 'Team',
            'description' => __('Team'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('Team'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'testimonials',
            'title' => 'Testimonials',
            'description' => __('Testimonials'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('Testimonials'),
            //'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true,
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'testimonials-cards',
            'title' => 'Testimonials Cards',
            'description' => __('Testimonials Cards with stars'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('testimonials', 'cards', 'reviews'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'post-feed',
            'title' => 'Post feed',
            'description' => __('Post feed'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('Post feed'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'faq',
            'title' => 'FAQ',
            'description' => __('FAQ'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('FAQ'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'contact',
            'title' => 'Contact',
            'description' => __('Contact'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('Contact'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'gallery',
            'title' => 'Gallery',
            'description' => __('Gallery'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('Gallery'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'tiles',
            'title' => 'Tiles',
            'description' => __('Tiles'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('Tiles'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }


    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'counters',
            'title' => 'Counters',
            'description' => __('Counters'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('Counters'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'find-us',
            'title' => 'Find Us',
            'description' => __('Find Us map & info'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('map', 'location', 'find us'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'pricing-table',
            'title' => 'Pricing Table',
            'description' => __('Pricing Table'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('Pricing', 'Table', 'Costs'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'spacer',
            'title' => 'Spacer',
            'description' => __('Spacer'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('Spacer'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'pagination',
            'title' => 'Pagination',
            'description' => __('Pagination'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('Pagination'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'author',
            'title' => 'Author',
            'description' => __('author'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'modules',
            'keywords' => array('author'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    }

    /*     if (function_exists('acf_register_block')) {
        acf_register_block(array(
            'name' => 'button',
            'title' => 'Button',
            'description' => __('button'),
            'mode' => 'edit',
            'render_callback' => 'pt_block_render_callback',
            'category' => 'devstars',
            'keywords' => array('button'),
            'align'        => 'wide',
            'supports'    => array(
                'align'        => array('wide'),
                'anchor' => true
            )
        ));
    } */
}

// Force register missing blocks that aren't being registered by my_acf_init
// Use init hook with late priority to ensure ACF is fully loaded
add_action('init', 'register_missing_acf_blocks', 999);
function register_missing_acf_blocks() {
    if (!function_exists('acf_register_block')) {
        return;
    }
    
    // Get currently registered blocks
    $blocks = acf_get_block_types();
    $registered_names = [];
    foreach ($blocks as $block) {
        $registered_names[] = $block['name'];
    }
    
    // Define missing blocks
    $missing_blocks = [
        'steps-cards' => [
            'title' => 'Steps Cards',
            'description' => __('3-column card block with CTA'),
            'mode' => 'preview',
            'example' => [
                'attributes' => [
                    'mode' => 'preview',
                    'data' => [
                        'is_preview' => true,
                        'content' => [
                            'title' => 'Getting Started is Easy',
                            'steps' => [
                                ['title_step' => 'Choose Your Size', 'text_step' => '8ft (garden shed) → 20ft (single garage) → 40ft (double garage) Not sure? We\'ll help you pick'],
                                ['title_step' => 'Move In Today', 'text_step' => 'Drive right up. Use our free equipment. Take your time.'],
                                ['title_step' => 'Access 24/7', 'text_step' => 'Your stuff. Your schedule. Come and go as you please.']
                            ],
                            'cta' => ['title' => 'RESERVE YOUR UNIT NOW', 'url' => '#']
                        ],
                        'settings' => []
                    ]
                ]
            ]
        ],
        'pricing-table' => [
            'title' => 'Pricing Table',
            'description' => __('Pricing Table'),
            'mode' => 'edit'
        ],
        'trust-bar' => [
            'title' => 'Trust Bar',
            'description' => __('Trust Bar'),
            'mode' => 'edit'
        ],
        'header-block' => [
            'title' => 'Header',
            'description' => __('Header block'),
            'mode' => 'edit'
        ],
        'hero' => [
            'title' => 'Hero',
            'description' => __('Hero block'),
            'mode' => 'edit'
        ],
        'find-us' => [
            'title' => 'Find Us',
            'description' => __('Find Us map & info'),
            'mode' => 'edit'
        ],
        'cta' => [
            'title' => 'CTA',
            'description' => __('Call to Action'),
            'mode' => 'edit'
        ],
        'footer' => [
            'title' => 'Footer',
            'description' => __('Footer'),
            'mode' => 'edit'
        ]
    ];
    
    // Register missing blocks
    foreach ($missing_blocks as $name => $config) {
        $block_name = 'acf/' . $name;
        if (!in_array($block_name, $registered_names)) {
            $args = [
                'name' => $name,
                'title' => $config['title'],
                'description' => __($config['description']),
                'mode' => $config['mode'],
                'render_callback' => 'pt_block_render_callback',
                'category' => 'modules',
                'keywords' => [],
                'align' => 'wide',
                'supports' => [
                    'align' => ['wide'],
                    'anchor' => true
                ]
            ];
            
            if (isset($config['example'])) {
                $args['example'] = $config['example'];
            }
            
            $result = acf_register_block($args);
        }
    }
}

function m_deny_list_blocks()
{
    wp_enqueue_script(
        'deny-list-blocks',
        get_template_directory_uri() . '/js/deny-list-blocks.js',
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post')
    );
}
add_action('enqueue_block_editor_assets', 'm_deny_list_blocks');

add_filter('allowed_block_types_all', function ($allowed_blocks, $post) {
    $allowed_blocks = [
        'core/image',
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/list-item',
        'core/embed',
        /* 'core/spacer', */
        'core/quote',
        /* 'core/gallery',  */
        'core/table',
        'core/code',
        'core/html',
        'core/media-text',
        'core/cover',
        'core/columns',
        'core/buttons',
        'core/separator',
        'acf/header-block',
        'acf/hero',
        'acf/trust-bar',
        'acf/text-media',
        'acf/text-list',
        'acf/text',
        'acf/banner-with-form',
        'acf/usp',
        'acf/clients',
        'acf/team',
        'acf/testimonials',
        'acf/testimonials-cards',
        'acf/post-feed',
        'acf/steps',
        'acf/steps-cards',
        'acf/faq',
        'acf/contact',
        'acf/gallery',
        'acf/tiles',
        'acf/counters',
        'acf/find-us',
        'acf/pricing-table',
        'acf/cta',
        'acf/footer',
        'acf/spacer',
        'acf/pagination',
        /* 'acf/button', */
        'acf/author',
    ];


    return $allowed_blocks;
}, 10, 2);

function pt_block_render_callback($block)
{
    $slug = str_replace('acf/', '', $block['name']);
    $block_path = get_template_directory() . "/blocks/{$slug}/{$slug}.php";

    if (file_exists($block_path)) {
        include($block_path);
    }
}


function add_container_to_block($block_content, $block)
{

    global $post;
    global $template;

    if ($post instanceof WP_Post) {
        $post_type = $post->post_type;

        if ($post_type === 'page' || $post_type === 'post' || $post_type === 'services' || $post_type === 'case-studies') {

            $content_class = "mx-auto";

            $page_template = get_template_directory() . '/templates/blog.php';
            if ($template === $page_template) {
                $content_class = "mx-auto";
            }


            $blocks_without_section = array("acf/button", "acf/author");

            if (strpos($block["blockName"], "acf") !== false && !in_array($block["blockName"], $blocks_without_section) || $block["blockName"] === "core/cover") {
                //if acf and block with section
                if (is_single()) {
                    $block_content = '</div></div></div>' . $block_content .
                        '<div class="container-fluid page-text section-white"><div class="row"><div class="col-12 col-xl-10 ' . $content_class . '">';
                }

                if (is_page()) {
                    $block_content = '</div></div></div>' . $block_content .
                        '<div class="container-fluid page-text section-white"><div class="row"><div class="col-12 col-xl-10  ' . $content_class . '">';
                }
            }

            /*     if ($block["attrs"]["align"] === "wide")  {                
                $block_content = '</div></div></div>' . $block_content .
                    '<div class="container-fluid page-text section-white"><div class="row"><div class="col-12 col-xl-10 '.$content_class.'">';
            } */
        }
    }

    return $block_content;
}

add_filter('render_block', 'add_container_to_block', 10, 2);

function enqueue_block_editor_scripts()
{
    wp_enqueue_script(
        'theme-block-editor-js',
        get_stylesheet_directory_uri() . '/js/block-editor-scripts.js',
        array('wp-blocks', 'wp-dom', 'wp-edit-post'),
        '1.0',
        true
    );
}
add_action('enqueue_block_editor_assets', 'enqueue_block_editor_scripts');
