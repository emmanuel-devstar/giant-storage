<?php

/**
 * Block Name: Pricing Table
 */

$block_post = $block["id"];

$settings = get_field("settings", $block_post);
if (!is_array($settings)) {
    $settings = [];
}

$content = get_field("content", $block_post);
if (!is_array($content)) {
    $content = [];
}

$data = block_start("pricing-table", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];
$h_tag = $data["h_tag"];

$headline = $content["headline_text"] ?? "";
$subheadline = $content["body_text"] ?? "";
$cta = $content["cta"] ?? null;
$rows = $content["rows"] ?? [];
if (!is_array($rows)) {
    $rows = [];
}
$notes = $content["notes"] ?? [];
if (!is_array($notes)) {
    $notes = [];
}

$remove_padding_bottom = $settings["remove_padding_bottom"] ?? false;
$section_class = "c-section c-section--pricing " . esc_attr($color_schema);
if ($remove_padding_bottom) {
    $section_class .= " c-section--pricing-no-padding-bottom";
}
$padding_bottom = $remove_padding_bottom ? "0" : "50px";
?>
<style>
section#<?= esc_attr($id); ?>.c-section--pricing {
    padding-top: 100px !important;
    padding-bottom: <?= $padding_bottom; ?> !important;
    margin-bottom: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
}
</style>
<section id="<?= esc_attr($id); ?>" class="<?= $section_class; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                <div class="l-text-md text-center">
                    <?php if (!empty($headline)) : ?>
                        <<?= $h_tag; ?> class="section__title custom-title-colour text-center">
                            <?= esc_html($headline); ?>
                        </<?= $h_tag; ?>>
                    <?php endif; ?>

                    <?php if (!empty($subheadline)) : ?>
                        <div class="section__subtitle wysiwyg text-center">
                            <?= wp_kses_post($subheadline); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($rows)) : ?>
                    <div class="pricing-table__card">
                        <div class="pricing-table">
                            <?php foreach ($rows as $row) : ?>
                                <?php
                                $unit = $row["unit_label"] ?? "";
                                $price = $row["price"] ?? "";
                                $description = $row["description"] ?? "";
                                ?>
                                <div class="pricing-table__row">
                                    <?php if (!empty($unit)) : ?>
                                        <div class="pricing-table__cell pricing-table__unit">
                                            <?= esc_html($unit); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($price)) : ?>
                                        <div class="pricing-table__cell pricing-table__price">
                                            <?= esc_html($price); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($description)) : ?>
                                        <div class="pricing-table__cell pricing-table__desc">
                                            <?= esc_html($description); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($notes) || !empty($cta)) : ?>
                    <div class="pricing-table__footer">
                        <?php if (!empty($notes)) : ?>
                            <ul class="pricing-table__notes">
                                <?php foreach ($notes as $note) : ?>
                                    <?php $note_text = $note["text"] ?? ""; ?>
                                    <?php if (!empty($note_text)) : ?>
                                        <li class="pricing-table__note">
                                            <span class="pricing-table__check" aria-hidden="true">
                                                <?= file_get_contents(IMAGES . '/icons/check-mark.svg'); ?>
                                            </span>
                                            <span><?= esc_html($note_text); ?></span>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if (!empty($cta)) : ?>
                            <div class="pricing-table__cta">
                                <?= btn_from_link($cta, "btn btn--red"); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
