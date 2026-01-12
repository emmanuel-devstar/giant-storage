<a class="pf__tile" href="<?= get_permalink($post->ID) ?>">

    <?php
    $featured = get_post_img($post->ID, "custom_medium");
    if ($featured):
    ?>
        <div class="pf__image ratio-js" data-ratio="0.61" style="background-image:url(<?= get_post_img($post->ID, "custom_medium") ?>)" alt="<?= get_post_alt($loop->post->ID) ?>"></div>
    <?php
    endif;
    ?>

    <h4 class="pf__title  align-h-js" data-block="<?= $args["block_id"] ?>" data-align="pf-title-<?= $args["group"]; ?>" data-align-lg="pf-title-<?= $args["group_lg"]; ?>"> <?= get_the_title($post->ID); ?> </h4>

    <p class="pf__excerpt align-h-js " data-block="<?= $args["block_id"] ?>" data-align="pf-excerpt-<?= $args["group"]; ?>" data-align-lg="pf-excerpt-<?= $args["group_lg"]; ?>"> <?= get_the_excerpt($post->ID); ?> </p>

    <!-- <p class="pf__more tile-hover">Read more</p> -->
</a>