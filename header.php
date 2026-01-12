<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php
        wp_title();
        ?>
    </title>
    <!-- <link rel="shortcut icon" href="<?= get_template_directory_uri() ?>/images/favicon/favicon.ico"> -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= Configuration::$favicon ?? ''; ?>">
    <!-- <link rel="apple-touch-icon-precomposed" href="<?= get_template_directory_uri() ?>/images/favicon/mstile-150x150.png"> -->
    <script>
        const ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
        const themeUri = '<?= THEME_URI ?>';
    </script>

    <?php get_template_part('template-parts/load-carousel');  ?>

    <?php wp_head(); ?>

    <?= (method_exists('Configuration', 'get_root_styles')) ? Configuration::get_root_styles() : ''; ?>

    <script>
        function lazyLoadCss(href) {
            var css = document.createElement('link');

            css.type = 'text/css';
            css.rel = 'stylesheet';
            css.href = href;

            var s = document.getElementsByTagName('link')[0];

            s.parentNode.insertBefore(css, s);
        }
    </script>

    <style>
        :root {
            --modular-menu_top_breakpoint: <?= Configuration::$menu_top_breakpoint ?? '992px'; ?>
        }

        @media screen and (min-width: <?= Configuration::$menu_top_breakpoint ?? '992px'; ?>) {



            /*     .nav-top__logo {
                max-width: <?= Configuration::$logo_max_width ?? '200px'; ?> !important;
            } */

            .menu-top {
                display: block !important;
            }

            .btns__wrapper {
                display: flex !important;
            }

            .c-toggler {
                display: none !important;
            }

            .menu-mobile-wrapper {
                display: none;
            }

            .header__call,
            .vert-line {
                display: none;
            }

            .l-section-top-single {
                padding-top: 125px;
            }

            .c-nav-top {
                min-height: 125px;
            }
        }
    </style>

    <script>
        <?php
        echo get_fields("option")["custom_script"];
        ?>
    </script>

</head>

<body <?php body_class(); ?>>

    <?php
    global $post;
    $post_blocks = parse_blocks($post->post_content);

    if (is_home()) { // blog page
        $wrapper_class = "l-section-top";
    }

    if (is_single() &&  !is_singular('services') && !is_singular('case-studies')) {
        //$wrapper_class = "l-section-top-single";
        //adding in breadcrumbs
    }

    if (get_page_template_slug()  === "templates/blog.php" || get_page_template_slug()  === "templates/news.php") {
        $wrapper_class = "l-section-top";
    }

    if (is_page() || is_404()) {
        $wrapper_class = "l-section-top";
        if ($post_blocks[0]["blockName"] === "acf/text-media" && $post_blocks[0]["attrs"]["data"]["carousel_width"] === "full") {
            $wrapper_class = "";
        }

        if ($post_blocks[0]["blockName"] === 'acf/banner-with-form') {
            $wrapper_class = "";
        }
    }

    if (get_field("transparent_header")) {
        $nav_class = "section-transparent";
    }

    $h_fields = (isset(Configuration::$fields) && is_array(Configuration::$fields) && isset(Configuration::$fields["header"])) ? Configuration::$fields["header"] : [];
    $scheme_colors = trim(strtolower($h_fields["color_scheme"] ?? ""));
    
    $components_color = ($scheme_colors === "white") ? "black" : "white";
    $logo_white_url = (isset($h_fields["nav"]["logo"]["white"]["sizes"]["thumbnail"])) ? $h_fields["nav"]["logo"]["white"]["sizes"]["thumbnail"] : "";
    $logo_black_url = (isset($h_fields["nav"]["logo"]["black"]["sizes"]["thumbnail"])) ? $h_fields["nav"]["logo"]["black"]["sizes"]["thumbnail"] : "";

    $cta_group = (isset(Configuration::$fields) && is_array(Configuration::$fields) && isset(Configuration::$fields["header"]["nav"]["cta_group"])) ? Configuration::$fields["header"]["nav"]["cta_group"] : [];
    $btn_cta_type = $cta_group["type"] ?? null;
    $btn_cta_colour = $cta_group["colour"] ?? null;
    $btn_cta = store_content_of_function('btn_from_link', [$cta_group["link"] ?? null, "btn btn-header " . ($btn_cta_type ?? "")]);
    $btn_cta_mobl = store_content_of_function('btn_from_link', [$cta_group["link"] ?? null, "btn btn-header-mobile " . ($btn_cta_type ?? "")]);
    ?>

    <div class="c-nav-top nav-top-js section-<?= $scheme_colors; ?> <?= $nav_class ?> ">

        <div class="container-fluid pl-3 pr-3">

            <div class="u-nav nav-top__nav">
                <a class="nav-top__logo u-relative ml-0 mr-auto" href="<?= home_url(); ?>">
                    <img class="logo--white" src="<?= $logo_white_url ?>" alt="logo">
                    <img class="logo--black" src="<?= $logo_black_url ?>" alt="logo">
                </a>

                <?php
                // Wrap menu and phone together for better spacing control
                if (Configuration::$phone) :
                ?>
                <div class="header__nav-phone-wrapper">
                <?php
                endif;
                ?>
                <ul class="menu-top <?= ($btn_cta || Configuration::$phone) ? "" : "ml-auto mr-0"; ?>">
                    <?php
                    $menu_top = new Menu('top');
                    $menu_top->view();
                    ?>
                </ul>

                <?php
                // Desktop phone display (hidden on mobile)
                if (Configuration::$phone) :
                    $phone_icon_url = '';
                    if (isset(Configuration::$fields) && is_array(Configuration::$fields) && isset(Configuration::$fields["header"]["mobile_icon"])) {
                        $phone_icon_url = Configuration::$fields["header"]["mobile_icon"]["sizes"]["thumbnail"] ?? '';
                    }
                ?>
                    <div class="header__phone-desktop mr-0">
                        <a href="<?= Configuration::$phone_link ?? '#'; ?>" class="header__phone-link">
                            <?php if ($phone_icon_url): ?>
                                <img class="header__phone-icon" src="<?= esc_url($phone_icon_url); ?>" alt="phone">
                            <?php else: ?>
                                <svg class="header__phone-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" fill="currentColor" stroke="currentColor"/>
                                </svg>
                            <?php endif; ?>
                            <span class="header__phone-number"><?= esc_html(Configuration::$phone); ?></span>
                        </a>
                    </div>
                </div>
                <?php
                endif;
                ?>

                <?php
                if ($btn_cta) :
                ?>
                    <div class="btns__wrapper ml-auto mr-0">
                        <?php
                        echo $btn_cta;
                        ?>
                    </div>
                <?php
                endif;
                ?>

                <?php
                $toggler_ml = "ml-auto";
                $has_mobile_icon = (isset(Configuration::$fields) && is_array(Configuration::$fields) && isset(Configuration::$fields["header"]["mobile_icon"]));
                if (Configuration::$phone && $has_mobile_icon):
                    $toggler_ml = "ml-4 ml-sm-5";
                    $link_mr = "mr-4 mr-sm-5";
                ?>
                    <a href="<?= Configuration::$phone_link ?? '#'; ?>" class="header__call ml-auto <?= $link_mr; ?>">
                        <img class="call__icon" src="<?= Configuration::$fields["header"]["mobile_icon"]["sizes"]["thumbnail"] ?? ''; ?>" alt="call">
                    </a>
                    <div class="vert-line">
                        <div>&nbsp;</div>
                    </div>
                <?php
                endif;
                ?>

                <button class="c-toggler hamburger-js <?= $toggler_ml; ?> mr-0 " type="button" aria-expanded="false">
                    <span class="toggler__lines"></span>
                </button>

            </div>

        </div>
        <?php if ($scheme_colors === "black" && !get_field("transparent_header")) : ?>
            <div class="nt__background"></div>
        <?php endif; ?>
    </div>







    <div class="menu-mobile-wrapper section-<?= $scheme_colors; ?> menu-mobile-js ">
        <div class="container-fluid">
            <div class="menu-mobile">
                <ul class="menu-mobile-list">
                    <?php
                    $menu_top = new Menu('top');
                    $menu_top->view();
                    ?>
                </ul>
                <?php
                if ($btn_cta) :
                ?>
                    <div class="mt-6">
                        <?php
                        echo $btn_cta_mobl;
                        ?>
                    </div>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>

    <div class="<?= $wrapper_class ?>"></div>