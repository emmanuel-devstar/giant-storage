<?php
if ($content["headline_text"]) :
?>
    <<?= $h_tag; ?> class="section__title custom-title-colour"><?= $content["headline_text"]; ?></<?= $h_tag; ?>>
<?php
endif;
?>

<?php
if ($content["body_text"]) :
?>
    <p class="section__subtitle "><?= $content["body_text"] ?></p>
<?php
endif;
?>