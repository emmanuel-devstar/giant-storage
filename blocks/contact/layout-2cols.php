<?php
$sc_class = "";
if ($settings["2nd_row"]["column"] === "none" && $settings["remove_padding_bottom"]) {
    $sc_class = "u-pb-0 ";
}
/* if($settings["2nd_row"]["column"] !== "none" && $settings["first_row"]["left"] !== "map" && $settings["first_row"]["right"] !== "map"){
    $sc_class = "u-pb-0 ";
} */

?>
<div class="c-section--contact <?= $sc_class; ?> <?= $color_schema; ?> " id="<?php echo esc_attr($id); ?>">
    <div class="container-fluid contact__half">
        <div class="row l-cols">
            <div class="col-12 col-lg-6 col-left <?= $settings["first_row"]["left"] ?>">

                <?php
                if ($settings["first_row"]["left"] !== "map") :
                    include("headline.php");
                endif;

                if ($settings["first_row"]["left"] === "map") :
                ?>
                    <div class="map-wrapper map-wrapper-js u-relative">
                        <div id="googleMap" class="contact__map map-js"></div>
                    </div>
                <?php
                endif;

                if ($settings["first_row"]["left"] === "details") :
                    include("details.php");
                endif;

                if ($settings["first_row"]["left"] === "form") :
                    include("form.php");
                endif;

                ?>

            </div>

            <div class="col-12 col-lg-6 col-right <?= $settings["first_row"]["right"] ?> u-relative">
                <?php
                if ($settings["first_row"]["left"] === "map") :
                    include("headline.php");
                endif;

                if ($settings["first_row"]["right"] === "map") :
                ?>
                    <div class="map-wrapper map-wrapper-js u-relative">
                        <div id="googleMap" class="contact__map map-js"></div>
                    </div>
                <?php
                endif;

                if ($settings["first_row"]["right"] === "details") :
                    include("details.php");
                ?>

                <?php
                endif;

                if ($settings["first_row"]["right"] === "form") :
                    include("form.php");
                endif;
                ?>


            </div>
        </div>

    </div>
</div>