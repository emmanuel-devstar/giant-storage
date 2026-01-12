<?php
/**
 * Schema Management System
 * Handles schema markup for pages and posts
 */

if (!defined('ABSPATH')) {
    exit;
}

class Modular_Schema_Manager
{
    private static $instance = null;
    private $option_name = 'modular_schema_settings';
    private $meta_key = '_modular_schema_data';

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        add_action('wp_head', array($this, 'output_schema'), 1);
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_post_schema'), 10, 2);
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu()
    {
        add_menu_page(
            __('Schema', 'theme_modular'),
            __('Schema', 'theme_modular'),
            'manage_options',
            'modular-schema',
            array($this, 'render_admin_page'),
            'dashicons-editor-code',
            30
        );
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook)
    {
        if ($hook !== 'toplevel_page_modular-schema' && $hook !== 'post.php' && $hook !== 'post-new.php') {
            return;
        }

        // Enqueue React and dependencies
        wp_enqueue_script('react', 'https://unpkg.com/react@18/umd/react.production.min.js', array(), '18.2.0', true);
        wp_enqueue_script('react-dom', 'https://unpkg.com/react-dom@18/umd/react-dom.production.min.js', array('react'), '18.2.0', true);
        
        // Enqueue Babel standalone for JSX transformation
        wp_enqueue_script('babel-standalone', 'https://unpkg.com/@babel/standalone/babel.min.js', array(), '7.23.0', true);
        
        // Enqueue SweetAlert2
        wp_enqueue_script('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', array(), '11.0.0', true);
        wp_enqueue_style('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css', array(), '11.0.0');

        // Enqueue schema admin script
        $schema_js_path = get_template_directory() . '/js/schema-admin.js';
        if (file_exists($schema_js_path)) {
            wp_enqueue_script(
                'modular-schema-admin',
                get_template_directory_uri() . '/js/schema-admin.js',
                array('react', 'react-dom', 'babel-standalone', 'sweetalert2', 'wp-api-fetch', 'wp-element'),
                filemtime($schema_js_path),
                true
            );
        }

        // Enqueue meta box script for post edit screens
        if (in_array($hook, array('post.php', 'post-new.php'))) {
            $meta_box_js_path = get_template_directory() . '/js/schema-meta-box.js';
            if (file_exists($meta_box_js_path)) {
                wp_enqueue_script(
                    'modular-schema-meta-box',
                    get_template_directory_uri() . '/js/schema-meta-box.js',
                    array('react', 'react-dom', 'babel-standalone', 'sweetalert2', 'modular-schema-admin'),
                    filemtime($meta_box_js_path),
                    true
                );
            }
        }

        // Enqueue admin styles
        $schema_css_path = get_template_directory() . '/css/schema-admin.css';
        if (file_exists($schema_css_path)) {
            wp_enqueue_style(
                'modular-schema-admin',
                get_template_directory_uri() . '/css/schema-admin.css',
                array(),
                filemtime($schema_css_path)
            );
        }

        // Localize script
        wp_localize_script('modular-schema-admin', 'modularSchema', array(
            'apiUrl' => rest_url('modular-schema/v1/'),
            'nonce' => wp_create_nonce('wp_rest'),
            'themeUri' => get_template_directory_uri(),
            'postTypes' => $this->get_post_types(),
            'currentPostId' => get_the_ID(),
            'currentPostType' => get_post_type(),
            'isPostEdit' => in_array($hook, array('post.php', 'post-new.php')),
        ));
    }

    /**
     * Get all post types
     */
    private function get_post_types()
    {
        $post_types = get_post_types(array('public' => true), 'objects');
        $types = array();
        
        foreach ($post_types as $post_type) {
            $types[] = array(
                'name' => $post_type->name,
                'label' => $post_type->label,
                'singular_label' => $post_type->labels->singular_name,
            );
        }
        
        return $types;
    }

    /**
     * Register REST API routes
     */
    public function register_rest_routes()
    {
        register_rest_route('modular-schema/v1', '/settings', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_settings'),
            'permission_callback' => array($this, 'check_permissions'),
        ));

        register_rest_route('modular-schema/v1', '/settings', array(
            'methods' => 'POST',
            'callback' => array($this, 'save_settings'),
            'permission_callback' => array($this, 'check_permissions'),
        ));

        register_rest_route('modular-schema/v1', '/post/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_post_schema'),
            'permission_callback' => array($this, 'check_permissions'),
        ));

        register_rest_route('modular-schema/v1', '/post/(?P<id>\d+)', array(
            'methods' => 'POST',
            'callback' => array($this, 'save_post_schema_api'),
            'permission_callback' => array($this, 'check_permissions'),
        ));

        register_rest_route('modular-schema/v1', '/posts', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_posts_list'),
            'permission_callback' => array($this, 'check_permissions'),
        ));
    }

    /**
     * Check permissions
     */
    public function check_permissions()
    {
        return current_user_can('manage_options');
    }

    /**
     * Get settings
     */
    public function get_settings()
    {
        $settings = get_option($this->option_name, array(
            'global_schema' => '',
            'global_enabled' => false,
            'page_schemas' => array(),
        ));

        return rest_ensure_response($settings);
    }

    /**
     * Save settings
     */
    public function save_settings($request)
    {
        $params = $request->get_json_params();
        
        $settings = array(
            'global_schema' => isset($params['global_schema']) ? wp_kses_post($params['global_schema']) : '',
            'global_enabled' => isset($params['global_enabled']) ? (bool) $params['global_enabled'] : false,
            'page_schemas' => isset($params['page_schemas']) ? $this->sanitize_page_schemas($params['page_schemas']) : array(),
        );

        update_option($this->option_name, $settings);

        return rest_ensure_response(array(
            'success' => true,
            'message' => __('Settings saved successfully.', 'theme_modular'),
        ));
    }

    /**
     * Sanitize page schemas
     */
    private function sanitize_page_schemas($schemas)
    {
        $sanitized = array();
        
        foreach ($schemas as $schema) {
            if (isset($schema['page_id']) && isset($schema['schema'])) {
                $sanitized[] = array(
                    'page_id' => absint($schema['page_id']),
                    'schema' => wp_kses_post($schema['schema']),
                    'enabled' => isset($schema['enabled']) ? (bool) $schema['enabled'] : true,
                );
            }
        }
        
        return $sanitized;
    }

    /**
     * Get post schema
     */
    public function get_post_schema($request)
    {
        $post_id = $request->get_param('id');
        $schema = get_post_meta($post_id, $this->meta_key, true);
        $enabled = get_post_meta($post_id, $this->meta_key . '_enabled', true);

        return rest_ensure_response(array(
            'schema' => $schema ? $schema : '',
            'enabled' => $enabled !== '0' && $enabled !== false,
        ));
    }

    /**
     * Save post schema via API
     */
    public function save_post_schema_api($request)
    {
        $post_id = $request->get_param('id');
        $params = $request->get_json_params();

        if (!get_post($post_id)) {
            return new WP_Error('invalid_post', __('Invalid post ID.', 'theme_modular'), array('status' => 404));
        }

        $schema = isset($params['schema']) ? wp_kses_post($params['schema']) : '';
        $enabled = isset($params['enabled']) ? (bool) $params['enabled'] : true;

        update_post_meta($post_id, $this->meta_key, $schema);
        update_post_meta($post_id, $this->meta_key . '_enabled', $enabled ? '1' : '0');

        return rest_ensure_response(array(
            'success' => true,
            'message' => __('Schema saved successfully.', 'theme_modular'),
        ));
    }

    /**
     * Get posts list
     */
    public function get_posts_list($request)
    {
        $post_type = $request->get_param('post_type') ?: 'any';
        $search = $request->get_param('search') ?: '';
        
        $args = array(
            'post_type' => $post_type === 'any' ? 'any' : $post_type,
            'posts_per_page' => 100,
            'post_status' => array('publish', 'draft', 'pending'),
            'orderby' => 'title',
            'order' => 'ASC',
        );

        if ($search) {
            $args['s'] = sanitize_text_field($search);
        }

        $posts = get_posts($args);
        $results = array();

        foreach ($posts as $post) {
            $results[] = array(
                'id' => $post->ID,
                'title' => $post->post_title ?: '(No Title)',
                'type' => $post->post_type,
                'edit_link' => get_edit_post_link($post->ID, 'raw'),
            );
        }

        return rest_ensure_response($results);
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes()
    {
        $post_types = get_post_types(array('public' => true));
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'modular-schema-meta',
                __('Schema Markup', 'theme_modular'),
                array($this, 'render_meta_box'),
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * Render meta box
     */
    public function render_meta_box($post)
    {
        $schema = get_post_meta($post->ID, $this->meta_key, true);
        $enabled = get_post_meta($post->ID, $this->meta_key . '_enabled', true);
        
        wp_nonce_field('modular_schema_meta', 'modular_schema_nonce');
        ?>
        <div id="modular-schema-meta-box"></div>
        <script type="text/babel">
            const postSchemaData = {
                schema: <?php echo json_encode($schema ?: ''); ?>,
                enabled: <?php echo json_encode($enabled !== '0' && $enabled !== false); ?>,
            };
        </script>
        <?php
    }

    /**
     * Save post schema
     */
    public function save_post_schema($post_id, $post)
    {
        if (!isset($_POST['modular_schema_nonce']) || !wp_verify_nonce($_POST['modular_schema_nonce'], 'modular_schema_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['modular_schema_data'])) {
            $data = json_decode(stripslashes($_POST['modular_schema_data']), true);
            
            if ($data && isset($data['schema'])) {
                update_post_meta($post_id, $this->meta_key, wp_kses_post($data['schema']));
                update_post_meta($post_id, $this->meta_key . '_enabled', isset($data['enabled']) && $data['enabled'] ? '1' : '0');
            }
        }
    }

    /**
     * Output schema in head
     */
    public function output_schema()
    {
        $settings = get_option($this->option_name, array());
        
        // Output global schema if enabled
        if (!empty($settings['global_enabled']) && !empty($settings['global_schema'])) {
            echo $this->format_schema_output($settings['global_schema']);
        }

        // Output page-specific schema
        if (is_singular()) {
            $post_id = get_queried_object_id();
            $enabled = get_post_meta($post_id, $this->meta_key . '_enabled', true);
            
            if ($enabled !== '0' && $enabled !== false) {
                $schema = get_post_meta($post_id, $this->meta_key, true);
                if (!empty($schema)) {
                    echo $this->format_schema_output($schema);
                }
            }
        }

        // Output page schemas from settings
        if (!empty($settings['page_schemas']) && is_page()) {
            $page_id = get_queried_object_id();
            
            foreach ($settings['page_schemas'] as $page_schema) {
                if ($page_schema['page_id'] == $page_id && !empty($page_schema['enabled']) && !empty($page_schema['schema'])) {
                    echo $this->format_schema_output($page_schema['schema']);
                }
            }
        }
    }

    /**
     * Format schema output
     */
    private function format_schema_output($schema)
    {
        // If it's already a script tag, return as is
        if (strpos($schema, '<script') !== false) {
            return $schema;
        }

        // If it's JSON, wrap in script tag
        if (json_decode($schema) !== null) {
            return '<script type="application/ld+json">' . $schema . '</script>' . "\n";
        }

        // Otherwise, assume it's JSON-LD and wrap it
        return '<script type="application/ld+json">' . $schema . '</script>' . "\n";
    }

    /**
     * Render admin page
     */
    public function render_admin_page()
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <div id="modular-schema-admin"></div>
        </div>
        <?php
    }
}

// Initialize
Modular_Schema_Manager::get_instance();

