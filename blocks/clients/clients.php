<?php

/**
 * Block Name: Clients
 */
?>

<?php
$content = get_field("content");
$tiles = get_field("logotypes");
$settings = get_field("settings");

$data = block_start("clients", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];

//$class = ($block["align"] === "wide") ?  "col-12" : "col-12 col-xl-10 mx-auto";
?>

<div class="c-section--logotypes <?= $color_schema; ?>" id="<?= esc_attr($id); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="l-text-md mx-auto">
                    <?php if ($content["headline_text"]): ?>
                        <<?= $data["h_tag"]; ?> class="section__title  custom-title-colour u-text-<?= $settings["align_content"] ?> "><?= $content["headline_text"]; ?></<?= $data["h_tag"]; ?>>
                    <?php endif; ?>

                    <?php if ($content["body_text"]): ?>
                        <div class="section__subtitle wysiwyg  u-text-<?= $settings["align_content"] ?> "><?= $content["body_text"] ?></div>
                    <?php endif; ?>
                </div>

                <?php if ($tiles): ?>
                    <div class="logotypes-wrapper logotypes-js owl-carousel owl-theme " data-id="<?= $block['id']; ?>" data-items="<?= ($tiles) ? sizeof($tiles) : 0  ?>">
                        <?php
                        if ($tiles && 1 == 1) :
                            foreach ($tiles as $tile) :

                                $link = $tile["url"];
                                $image = $tile["image"];

                                if (isset($link) && !empty($link)) :
                                    $rel = ($link["target"] === "_blank") ? 'rel="external nofollow"' : '';
                        ?>
                                    <a href=" <?= $link["url"] ?> " target="<?= $link["target"] ?>" <?= $rel ?> class="l__tile">
                                        <img class="l__icon" src="<?= $image["sizes"]["medium"]; ?>" alt="<?= $image["alt"]; ?>">
                                    </a>
                                <?php
                                else :
                                ?>
                                    <a class="l__tile">
                                        <img class="l__icon" src="<?= $image["sizes"]["medium"]; ?>" alt="<?= $image["alt"]; ?>">
                                    </a>
                                <?php
                                endif;
                                ?>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                    <div class="u-nav l-btns-next-to nav-js">
                        <div class="l-prev-js o-nav-btn ml-auto"> <?= file_get_contents(IMAGES . '/icons/arrow-left.svg'); ?> </div>
                        <div class="l-next-js o-nav-btn mr-auto"> <?= file_get_contents(IMAGES . '/icons/arrow-right.svg'); ?> </div>
                    </div>
                <?php endif; ?>



            </div>
        </div>
    </div>
</div>

<?php
wp_enqueue_script('clients-js', get_template_directory_uri() . '/blocks/clients/clients.js', array('jquery'), filemtime(get_template_directory() . '/blocks/clients/clients.js'), false);
?>