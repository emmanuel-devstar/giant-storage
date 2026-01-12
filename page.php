<?php
$use_blank_header_footer = is_page(2680);

if ($use_blank_header_footer) {
    get_header('blank');
} else {
    get_header();
}
?>

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
<div class="<?= $class ?>">

    <div class="container-fluid  page-text section-white ">
        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
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


<?php
if ($use_blank_header_footer) {
    get_footer('blank');
} else {
    get_footer();
}
?>