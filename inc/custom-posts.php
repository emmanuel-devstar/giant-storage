<?php
function create_posttype()
{
    register_post_type(
        'news',
        [
            'labels'             => [
                'name'          => __('News'),
                'singular_name' => __('News')
            ],
            'supports'           => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
            'publicly_queryable' => true,
            'public'             => true,
            'has_archive'        => false,
            'rewrite'            => ['slug' => 'news', 'with_front' => false],
            'show_in_rest'       => true,
        ]
    );

    $args = [
        'label'        => __('Category'),
        'rewrite'      => ['slug' => 'categories-news', 'with_front' => false],
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_ui'      => true,
        'show_admin_column' => true,
    ];
    register_taxonomy('categories-news', 'news', $args);

    register_post_type(
        'services',
        [
            'labels'             => [
                'name'          => __('Services'),
                'singular_name' => __('Services')
            ],
            'supports'           => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
            'publicly_queryable' => true,
            'public'             => true,
            'has_archive'        => false,
            'rewrite'            => ['slug' => 'services', 'with_front' => false],
            'show_in_rest'       => true,
        ]
    );

    $args = [
        'label'        => __('Category'),
        'rewrite'      => ['slug' => 'categories-services', 'with_front' => false],
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_ui'      => true,
        'show_admin_column' => true,
    ];
    register_taxonomy('categories-services', 'services', $args);


    register_post_type(
        'case-studies',
        [
            'labels'             => [
                'name'          => __('Case Studies'),
                'singular_name' => __('Case Study')
            ],
            'supports'           => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
            'publicly_queryable' => true,
            'public'             => true,
            'has_archive'        => false,
            'rewrite'            => ['slug' => 'case-studies', 'with_front' => false],
            'show_in_rest'       => true,
        ]
    );

    $args = [
        'label'        => __('Category'),
        'rewrite'      => ['slug' => 'categories-case-studies', 'with_front' => false],
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_ui'      => true,
        'show_admin_column' => true,
    ];
    register_taxonomy('categories-case-studies', 'case-studies', $args);

    register_post_type(
        'authors',
        [
            'labels'             => [
                'name'          => __('Authors'),
                'singular_name' => __('Author'),
                'add_new'               => __('Add New'),
                'add_new_item'          => __('Add New'),
            ],
            'supports'           => ['title', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields'],
            'hierarchical'       => true,
            'publicly_queryable' => true,
            'public'             => true,
            'has_archive'        => false,
            'rewrite'            => ['slug' => 'authors', 'with_front' => false],
            'show_in_rest'       => true,

        ]
    );
}


add_action('init', 'create_posttype');
