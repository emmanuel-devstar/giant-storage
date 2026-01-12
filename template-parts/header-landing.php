<?php
/**
 * Custom Header for Landing Page (ID 2680)
 * This header has sticky positioning, custom background, and shadow
 */

$h_fields = (isset(Configuration::$fields) && is_array(Configuration::$fields) && isset(Configuration::$fields["header"])) ? Configuration::$fields["header"] : [];
$scheme_colors = "black"; // Force black scheme for landing page
$logo_white_url = (isset($h_fields["nav"]["logo"]["white"]["sizes"]["thumbnail"])) ? $h_fields["nav"]["logo"]["white"]["sizes"]["thumbnail"] : "";
$logo_black_url = (isset($h_fields["nav"]["logo"]["black"]["sizes"]["thumbnail"])) ? $h_fields["nav"]["logo"]["black"]["sizes"]["thumbnail"] : "";

$cta_group = (isset(Configuration::$fields) && is_array(Configuration::$fields) && isset(Configuration::$fields["header"]["nav"]["cta_group"])) ? Configuration::$fields["header"]["nav"]["cta_group"] : [];
$btn_cta_type = $cta_group["type"] ?? null;
$btn_cta = store_content_of_function('btn_from_link', [$cta_group["link"] ?? null, "btn btn-header " . ($btn_cta_type ?? "")]);
$btn_cta_mobl = store_content_of_function('btn_from_link', [$cta_group["link"] ?? null, "btn btn-header-mobile " . ($btn_cta_type ?? "")]);
?>

<style>
.c-nav-top--landing {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: #0b0a29;
    box-shadow: 0 2px 10px rgba(11, 10, 41, 0.3);
    padding: 0;
}

.c-nav-top--landing .admin-bar & {
    top: 46px;
}

@media screen and (min-width: 782px) {
    .c-nav-top--landing .admin-bar & {
        top: 32px;
    }
}

.c-nav-top--landing .nt__background {
    background-color: #0b0a29;
}
</style>

<div class="c-nav-top nav-top-js c-nav-top--landing section-<?= $scheme_colors; ?>">
    <div class="container-fluid pl-3 pr-3">
        <div class="u-nav nav-top__nav">
            <a class="nav-top__logo u-relative ml-0 mr-auto" href="<?= home_url(); ?>">
                <img class="logo--white" src="<?= $logo_white_url ?>" alt="logo">
                <img class="logo--black" src="<?= $logo_black_url ?>" alt="logo">
            </a>

            <ul class="menu-top <?= ($btn_cta) ? "" : "ml-auto mr-0"; ?>">
                <?php
                $menu_top = new Menu('top');
                $menu_top->view();
                ?>
            </ul>

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
    <div class="nt__background"></div>
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
