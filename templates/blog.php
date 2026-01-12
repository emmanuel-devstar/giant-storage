<?php

/**
 * Template Name: Blog
 */

get_header();
?>

<div class="l-section-padding pb-6">
    <div class="container-fluid   section-white">

        <div class="row">

            <div class="col-12  page-text mx-auto">
                <h1 class="u-text-left mb-8"><?php the_title() ?></h1>
                <?= the_content(); ?>
            </div>
        </div>
    </div>
</div>

<div class="c-section--post-feed pt-5 pb-16 pb-lg-17 section-white ">


    <div class="container-fluid   ">
        <div class="row">
            <div class="col-12  mx-auto">
                <?php
                $paged = get_query_var("paged");
                $type_of_post = get_field("type_of_post");

                $args = array(
                    'post_type' => $type_of_post,
                    'order' => 'DESC',
                    'orderby' => 'post_date',
                    'post_status' => 'publish',
                    'posts_per_page' => get_option('posts_per_page'),
                    'paged' => $paged,
                );

                $taxonomies_acf = ["post" => "post_category", "news" => "news_category", "case-studies" => "case_studies_category", "services" => "services_category"];
                $taxonomies = ["post" => "category", "news" => "categories-news", "case-studies" => "categories-case-studies", "services" => "categories-services"];
                $chosen_tax = $taxonomies[$type_of_post];

                $tax = get_field($taxonomies_acf[$type_of_post], $blog_page_id);
                if ($tax) {
                    $args["tax_query"] = array(
                        array(
                            'taxonomy' => $chosen_tax,
                            'field'    => 'term_id',
                            'terms'    => $tax->term_id
                        ),
                    );
                }

                $wp_query = new WP_Query($args);
                ?>

                <div class="row l-tiles">
                    <?php
                    $index = 0;
                    $number_of_columns = get_field("number_of_columns");
                    if (empty($number_of_columns)) $number_of_columns  = 4;
                    switch ($number_of_columns) {
                        case 3:
                            $grid_class = "col-lg-4";
                            break;
                        case 4:
                            $grid_class = "col-lg-3";
                            break;
                        default:
                            $grid_class = "col-lg-3";
                    }

                    while (have_posts()) : the_post();
                    ?>
                        <div class="col-6 <?= $grid_class; ?> ">
                            <?php
                            $group = floor($index / 2);
                            $group_lg = floor($index / $number_of_columns);
                            get_template_part('template-parts/post-tile', null, array("group" => $group, "group_lg" => $group_lg, "block_id" => "blog"));
                            $index++;
                            ?>
                        </div>

                    <?php endwhile;  ?>

                </div>

            </div>
        </div>



        <div class="row ">
            <div class="col-12">
                <?php Pagination::view(); ?>
            </div>
        </div>

    </div>

</div>

<?php get_footer(); ?>