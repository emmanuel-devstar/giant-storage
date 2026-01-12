<?php

/**
 * Block Post Feed
 */
?>

<?php
$id = 'post-feed-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

$settings = get_field("settings");

$post_type = strtolower(trim($settings["post_type"]));

$content = get_field("content");

$data = block_start("post-feed", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];

$args = array(
    'post_type'      => $post_type,
    'posts_per_page' => $settings["show"],
    'orderby' => 'post_date',
    'order' => 'desc',
    'post_status' => array('publish')
);

switch ($post_type) {
    case "news":
        $categories = get_field("filters_news");
        if ($categories) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'categories-news',
                    'field' => 'id',
                    'terms' => $categories,
                ),
            );
        }
        break;

    case "case-studies":
        $categories = get_field("filters_case");
        if ($categories) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'case-studies',
                    'field' => 'id',
                    'terms' => $categories,
                ),
            );
        }

        break;

    default:
        $categories = get_field("filters");
        $args['category__in'] = $categories;
}

/* if ($post_type === "news") {
    $categories = get_field("filters_news");
    if ($categories) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'categories-news',
                'field' => 'id',
                'terms' => $categories,
            ),
        );
    }
} else {
    $categories = get_field("filters");
    $args['category__in'] = $categories;
} */
?>

<div class="c-section--post-feed  <?= $color_schema ?>" id="<?php echo esc_attr($id); ?>">
    <div class="container-fluid u-relative">

        <div class="l-text-md">
            <?php
            if ($content["headline_text"]) :
            ?>
                <<?= $data["h_tag"]; ?> class="section__title custom-title-colour "><?= $content["headline_text"]; ?></<?= $data["h_tag"]; ?>>
            <?php
            endif;
            ?>

            <?php
            if ($content["body_text"]) :
            ?>
                <div class="section__subtitle wysiwyg"><?= $content["body_text"] ?></div>
            <?php
            endif;
            ?>
        </div>

        <?php
        $loop = new WP_Query($args);
        ?>
        <div class="row ">
            <div class="pf__wrapper post-feed-js owl-carousel owl-theme" posts-number="<?= sizeof($loop->posts) ?>" carousel-id="<?= $block['id'] ?>">

                <?php
                $index = 0;
                while ($loop->have_posts()) : $loop->the_post();
                ?>
                    <div class="col-12 ">
                        <?php
                        $group = floor($index / $settings["number_of_columns"]);
                        $group_lg = floor($index / $settings["number_of_columns"]);
                        get_template_part('template-parts/post-tile', null, array("group" => $group, "group_lg" => $group_lg, "block_id" => $block["id"]));
                        $index++;
                        ?>

                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>

            </div>
        </div>

        <?php
        if ($index > 3) : ?>
            <div class="u-nav mt-10 mt-lg-14">

                <div class="u-nav l-btns-next-to nav-js" carousel-id="<?= $block['id'] ?>">
                    <div class="prev-js o-nav-btn  ml-0"> <?= file_get_contents(IMAGES . '/icons/arrow-left.svg'); ?> </div>
                    <div class="next-js o-nav-btn  mr-auto"> <?= file_get_contents(IMAGES . '/icons/arrow-right.svg'); ?> </div>
                </div>

                <?php
                if (!empty($settings["view_all"]) && get_option('page_for_posts')) :
                ?>
                    <a href="<?= get_permalink(get_option('page_for_posts')); ?>" class="std-btn-tertiary mr-0 ml-auto">Show All</a>
                <?php
                endif;
                ?>

            </div>

        <?php
        endif;

        ?>
    </div>

</div>

<?php
wp_enqueue_script('post-feed-js', get_template_directory_uri() . '/blocks/post-feed/post-feed.js', array('jquery'), filemtime(get_template_directory() . '/blocks/post-feed/post-feed.js'), false);
wp_localize_script('post-feed-js', 'settings', $settings);
?>