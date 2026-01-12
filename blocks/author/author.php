<?php

/**
 * Block Name: Author
 */

$author_page = get_field("author");
if ($author_page):
    $author_data = get_fields($author_page->ID)["author"];
    $img_url = get_post_img($author_page->ID, "medium_large");
?>
    <div class="c-section--author mb-6">
        <div class="c-media u-flex-wrap">
            <?php if ($img_url): ?>
                <div class="author__image mb-4" style="background:url(<?= $img_url; ?>)"></div>
            <?php endif; ?>
            <div class="media-body">
                <h3 class="section__title"><?= get_the_title($author_page->ID); ?></h3>
                <p class="author__position"><?= $author_data["overtitle"] ?></p>

                <?php
                if ($author_data['social']) :
                ?>
                    <p class="author__label">Connect</p>
                    <div class="social-icons">
                        <?php
                        foreach ($author_data['social'] as $social) {
                            if ($icon = getSocialIconSvg($social['platform'])) {
                                echo '<a class="no-underline" href="' . $social['link'] . '" target="_blank">' . $icon . '</a> ';
                            }
                        }
                        ?>
                    </div>
                <?php
                endif;
                ?>

            </div>
        </div>
    </div>
<?php
endif;
?>

<?php
$post_id = get_the_ID();
?>
<div class="u-nav mb-10">
    <p class="single-published mr-8">Published: <span><?= get_the_date('d/m/Y', $post_id); ?></span></p>
    <p class="single-published">Updated: <span><?= get_post_modified_time('d/m/Y', $post_id); ?></span></p>
</div>