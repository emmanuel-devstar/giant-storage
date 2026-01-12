<?php
/**
 * Block Name: Text & Media
 */
?>

<?php
$banner = array();
$banner[] = get_field("slide");

$settings = get_field("settings");

?>
<div class="u-relative u-z-index-10">
    <div   class=" banner-wrapper  ">
        <?php
        foreach ($banner as $index => $slide) :                        

            include("content.php");            

        endforeach;
        ?>

    </div>
</div>