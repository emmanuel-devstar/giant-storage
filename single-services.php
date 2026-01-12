<?php get_header(); ?>

<?php

$class = '';

$post = get_post();
$first_block = get_first_block($post);
$last_block = get_last_block($post);

if (is_pagetext_block($first_block["blockName"])) {
    $class .= " l-text-page-top";
}

if (is_pagetext_block($last_block["blockName"])) {
    $class .= " l-text-page-bottom";
}
?>

<div class="single-service">

    <div class="container-fluid  section-white <?= $class ?>">
        <div class="row">
            <div class="col-12 col-xl-10 mx-auto page-text">

                <?php
                global $wp_query;
                while (have_posts()) : the_post();
                ?>
                    <?php the_content(); ?>

                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>