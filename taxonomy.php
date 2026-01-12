<?php get_header(); ?>

<div class="content-area">
    <div class="main col-xs-12 col-sm-9 l-col-first--sm">
        <h3>Main</h3>
        <?php    
        global $wp_query;     
        global $query_string;
        var_dump($query_string);
        
          
            
//            $cos = get_term_link('bydzia','mtax');
//            var_dump($cos);
            
        
//        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
//        $category = get_query_var("category_name");    
        
        $args = array(
            'post_type' => 'obiekt',
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'mtax',
                    'field'    => 'slug',
                    'terms' => 'bydzia',
                ),
                array(
                    'taxonomy' => 'ingredients',
                    'field'    => 'slug',
                    'terms' => 'zarowka',
		),
            ),
            'posts_per_page' => 10,
        );
        
//        $args = array(
//            'post_type' => 'post',
//            'tax_query' => array(
//                'relation' => 'OR',
//                array(
//                    'taxonomy' => 'category',
//                    'field'    => 'slug',
//                    'terms' => 'aktualnosci',
//                )
//            ),
//            'posts_per_page' => 10,
//        );        


//$args = array('category_name' => $category, 'post_type' => 'post', 'post_status' => 'publish',  'posts_per_page' => 2, 'paged'=>$paged);    
        $wp_query = new WP_Query($args);

        if (have_posts()) :
            while (have_posts()) : the_post();
                ?>
                <h5><a class="post-title" href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        <?php echo (get_post_meta($post->ID ,'ranking', true)); ?>
                <div class="u-clearfix"></div>
                <?php the_excerpt(); ?>
                <a href="<?php echo the_permalink(); ?>">Więcej</a>

                <?php
            endwhile;
            the_posts_navigation(array('prev_text'=>'Poprzednie', 'next_text'=>'Następne'));
            wp_reset_postdata();
        else :
            ?>
            <h2>Brak postów</h2>            
        <?php endif; ?>
    </div>
    
    <div class="col-xs-12 col-sm-3 l-col-last--sm">
        <?php get_sidebar(); ?>
    </div>
</div>

<?php wp_footer(); ?>
