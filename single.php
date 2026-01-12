<?php get_header(); ?>

<?php
if (function_exists('yoast_breadcrumb')) :
?>
    <div class="breadcrumb-wrapper section-gray l-section-top-single">
        <div class="c-breadcrumb-yoast ">
            <div class="container-fluid">
                <?php
                yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
                ?>
            </div>
        </div>
    </div>
<?php
endif;
?>

<div class="container-fluid  section-white l-single-top">
    <div class="row">
        <div class="col-12 col-xl-10 mx-auto page-text">
            <h1 class="mb-10 "><?php the_title() ?></h1>
            <?php
            global $wp_query;
            while (have_posts()) : the_post();
            ?>
                <?php the_content(); ?>

            <?php endwhile; ?>
        </div>
    </div>
    <div class="l-single-bottom"></div>
    <!-- <div class="mb-20"></div> -->
    <!--         <div class="mb-12"></div>
        <div class="row">
            <div class="col-xl-10 mx-auto">
                <div class="u-nav">
                    <div class="nav-previous ml-0 mr-auto">
                        <?php
                        $previous = get_previous_post(false);
                        $url_prev = get_permalink($previous);
                        ?>
                        <a class="o-prev-btn " href="<?= $url_prev; ?>" > <span class="icon mr-1">&#171;</span>Previous </a>
                    </div>
                    <div class="nav-next mr-0">
                    <?php
                    $next = get_next_post(false);
                    $url_next = get_permalink($next);
                    ?>
                        <a class="o-next-btn " href="<?= $url_next; ?>" >Next <span class="icon ml-1">&#187;</span> </a>
                    </div>
                </div>
            </div>
        </div> -->


</div>


<?php get_footer(); ?>