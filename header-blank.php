<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title(); ?></title>
    <link rel="icon" type="image/png" sizes="32x32" href="<?= (class_exists('Configuration') && isset(Configuration::$favicon)) ? Configuration::$favicon : ''; ?>">

    <script>
        const ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
        const themeUri = '<?= THEME_URI ?>';
    </script>

    <?php get_template_part('template-parts/load-carousel'); ?>

    <?php wp_head(); ?>

    <?= (class_exists('Configuration') && method_exists('Configuration', 'get_root_styles')) ? Configuration::get_root_styles() : ''; ?>

    <script>
        <?php echo get_fields("option")["custom_script"]; ?>
    </script>
</head>

<body <?php body_class('no-default-header-footer'); ?>>
