<?php
function remove_posts_menu_item()
{
  if (!get_fields("option")["panel"]["posts"]) remove_menu_page('edit.php'); // 'edit.php' corresponds to the Posts menu
}
add_action('admin_menu', 'remove_posts_menu_item');

function remove_custom_post_menu()
{

  if (!get_fields("option")["panel"]["news"]) remove_menu_page('edit.php?post_type=news');
  if (!get_fields("option")["panel"]["services"]) remove_menu_page('edit.php?post_type=services');
  if (!get_fields("option")["panel"]["case_studies"]) remove_menu_page('edit.php?post_type=case-studies');
}
add_action('admin_menu', 'remove_custom_post_menu');

function clean_str($str)
{
  return trim(nl2br($str));
}

function my_acf_load_value($value, $post_id, $field)
{
  if (!is_admin()) {
    if (is_string($value)) {
      $value = clean_str($value);
    }
  }

  return $value;
}

function acf_wysiwyg_load_value($value, $post_id, $field)
{
  if (!is_admin()) {
    if (is_string($value)) {
      $value = (strip_tags($value, '<br><p><b><strong><em><a><ul><ol><li>'));
    }
  }

  return $value;
}

add_filter('acf/load_value/type=text', 'my_acf_load_value', 10, 3);
add_filter('acf/load_value/type=url', 'my_acf_load_value', 10, 3);
add_filter('acf/load_value/type=textarea', 'acf_wysiwyg_load_value', 10, 3);

function updated_disable_comments_post_types_support()
{
  $types = get_post_types();
  foreach ($types as $type) {
    if (post_type_supports($type, 'comments')) {
      remove_post_type_support($type, 'comments');
      remove_post_type_support($type, 'trackbacks');
    }
  }
}
add_action('admin_init', 'updated_disable_comments_post_types_support');


function my_acf_google_map_api($api)
{

  $api['key'] = Configuration::$google_map_api_key;

  return $api;
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

function add_post_types_to_select($field)
{

  $custom_post_types = get_post_types(array('_builtin' => false));

  foreach ($custom_post_types as $key => $post_type) {

    if (!strstr($post_type, "acf") && $post_type != "authors" && $post_type != "services") {
      $field['choices']['option_' . $key] = $post_type;
    }
  }

  return $field;
}

add_filter('acf/load_field/name=post_type', 'add_post_types_to_select');



function set_default_content_for_new_post($content, $post)
{
  if ($post->post_type === 'post' && empty($content)) {

    $content = '<!-- wp:acf/pagination {"name":"acf/pagination","align":"wide","mode":"edit"} /-->';
  }
  return $content;
}
add_filter('default_content', 'set_default_content_for_new_post', 10, 2);


// Move Yoast to bottom
function yoasttobottom()
{
  return 'low';
}
add_filter('wpseo_metabox_prio', 'yoasttobottom');


// Extend WordPress search to include custom fields
function cf_search_join($join)
{
  global $wpdb;

  if (is_search()) {
    $join .= ' LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
  }

  return $join;
}
add_filter('posts_join', 'cf_search_join');

// Modify the search query with posts_where
function cf_search_where($where)
{
  global $pagenow, $wpdb;

  if (is_search()) {
    $where = preg_replace(
      "/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
      "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)",
      $where
    );
  }

  return $where;
}
add_filter('posts_where', 'cf_search_where');

// Prevent duplicates
function cf_search_distinct($where)
{
  global $wpdb;

  if (is_search()) {
    return "DISTINCT";
  }

  return $where;
}
add_filter('posts_distinct', 'cf_search_distinct');


function my_acf_input_admin_footer()
{
  //https://www.advancedcustomfields.com/resources/adding-custom-javascript-fields/
?>
  <script type="text/javascript">
    (function($) {
      acf.add_filter('color_picker_args', function(args, $field) {

        args.palettes = JSON.parse('<?= json_encode(Configuration::$brand_colours); ?>');

        return args;

      });

    })(jQuery);
  </script>
<?php

}

add_action('acf/input/admin_footer', 'my_acf_input_admin_footer');

function custom_pagination_rewrite()
{
  add_rewrite_rule(
    'news/page/([0-9]+)/?',
    'index.php?pagename=news&paged=$matches[1]',
    'top'
  );
}
add_action('init', 'custom_pagination_rewrite');


function remove_picture_tags_from_output($buffer)
{
  return preg_replace_callback(
    '/<picture([^>]*)>\s*(<source[^>]*srcset=["\']([^"\']+)["\'][^>]*>)?\s*(<img[^>]*>)\s*<\/picture>/is',
    function ($matches) {
      $pictureAttributes = $matches[1]; // Attributes of <picture> (e.g., classes)
      $sourceTag = $matches[2] ?? '';  // Full <source> tag (if exists)
      $sourceSrcset = $matches[3] ?? ''; // srcset value from <source>
      $imgTag = $matches[4]; // Found <img>

      // If <source> exists and has a WebP srcset, replace <img> src with it
      if (!empty($sourceSrcset)) {
        $imgTag = preg_replace('/src=["\'][^"\']+["\']/', 'src="' . $sourceSrcset . '"', $imgTag);
      }

      // If <picture> has a class, add it to <img>
      if (preg_match('/class=["\']([^"\']+)["\']/', $pictureAttributes, $classMatch)) {
        $classAttr = $classMatch[1];
        if (preg_match('/class=["\']([^"\']*)["\']/', $imgTag, $imgClassMatch)) {
          // If <img> already has a class, append the new classes
          $newImgTag = preg_replace(
            '/class=["\']([^"\']*)["\']/',
            'class="' . $imgClassMatch[1] . ' ' . $classAttr . '"',
            $imgTag
          );
        } else {
          // If <img> does not have a class, add it
          $newImgTag = str_replace('<img', '<img class="' . $classAttr . '"', $imgTag);
        }
      } else {
        $newImgTag = $imgTag; // If <picture> has no class, leave <img> unchanged
      }

      return $newImgTag;
    },
    $buffer
  );
}

function start_buffering()
{
  ob_start('remove_picture_tags_from_output');
}

function end_buffering()
{
  ob_end_flush();
}


require_once ABSPATH . 'wp-admin/includes/plugin.php';
if (is_plugin_active('imagify/imagify.php')) {
  add_action('wp_loaded', 'start_buffering');
  add_action('shutdown', 'end_buffering');
}
