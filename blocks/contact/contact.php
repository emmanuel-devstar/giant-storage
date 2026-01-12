<?php

/**
 * Block Contact
 */
?>

<?php
$content = get_field("content");
$fields = get_field("additional_fields");
$settings = get_field("settings");

$data = block_start("contact", $block, $settings, "section-black");
$id = $data["id"];
$color_schema = $data["color_schema"];
$h_tag = $data["h_tag"];

$services = get_field("services");

$settings["first_row"]["left"] = strtolower(trim($settings["first_row"]["left"]));
$settings["first_row"]["right"] = strtolower(trim($settings["first_row"]["right"]));
$settings["2nd_row"]["column"] = strtolower(trim($settings["2nd_row"]["column"]));

include("layout-2cols.php");

if ($settings["2nd_row"]["column"] !== "none") {
    include("layout-1col.php");
}

$lat = Configuration::$contact["basic"]["map"]["lat"] ? Configuration::$contact["basic"]["map"]["lat"] : 51.518600;
$lng = Configuration::$contact["basic"]["map"]["lng"] ? Configuration::$contact["basic"]["map"]["lng"] : -0.142290;

?>

<?php
$block_name = str_replace("acf/", "", $block['name']);
$file_js = $block_name . ".js";

wp_enqueue_script('contact-js', BLOCKS_URI . "/" . $block_name . "/" . "contact.js", array('jquery'), filemtime(BLOCKS . "/" . $block_name . "/" . "contact.js"), array("in_footer" => true));
wp_localize_script('contact-js', 'contact', array(
    'lat' => $lat,
    'lng' => $lng,
    'rcSiteKey' => Configuration::$rc_site_key,
    'redirect_on_submit' => $settings["redirect_on_submit"]
));

if ($settings["first_row"]["left"] === "map" || $settings["first_row"]["right"] === "map" || $settings["2nd_row"]["column"] === "map") :
    //wp_enqueue_script('gm-js', "https://maps.googleapis.com/maps/api/js?key=" . Configuration::$google_map_api_key . "&loading=async&callback=window.map.init", array(), "1", array("in_footer" => true));
?>
    <script defer src="https://maps.googleapis.com/maps/api/js?key=<?= Configuration::$google_map_api_key ?>&callback=window.map.init"></script>
<?php
endif;
