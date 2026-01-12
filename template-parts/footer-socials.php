<?php
foreach (Configuration::$socials as $index => $social) :
?>
    <a href="<?= $social["url"] ?>" class="c-media icon-link">

        <?= file_get_contents(IMAGES . '/icons/' . strtolower($social["name"]) . '.svg'); ?>

        <p class="media-body">
            <?= $social["name"]; ?>
        </p>

    </a>

<?php
endforeach;
?>