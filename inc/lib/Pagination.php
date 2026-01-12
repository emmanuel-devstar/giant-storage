<?php
Class Pagination{
    public static function view()
    {
        if (is_singular())
        return;          
    
        global $wp_query;
    
        /** Stop execution if there's only 1 page */
        if ($wp_query->max_num_pages <= 1)
            return;
    
        $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
        $max = intval($wp_query->max_num_pages);
    
        /** Add current page to the array */
        if ($paged >= 1)
            $links[] = $paged;
    
        /** Add the pages around the current page to the array */
        if ($paged >= 3) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }
    
        if (($paged + 2) <= $max) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }
    
        echo '<div class="c-pagination-numb mx-auto sectio-white" aria-label="Page navigation">' . "\n";
    
        /** Previous Post Link */
        if (get_previous_posts_link()) {
            
            $prev_img = file_get_contents(THEME."/assets/img/icons/chevron-left.svg");
            $prev_link = get_previous_posts_link(   "<span>Previous</span>" );  
            $prev_link = str_replace('<a  href=', '<a href=', $prev_link );
            printf('<div class="prev pn">%s</div>' . "\n", $prev_link );
        }
    
    
        /** Link to first page, plus ellipses if necessary */
        if (!in_array(1, $links)) {
            $class = 1 == $paged ? "active" : "";
    
            printf('<div class="pn %s" ><a href="%s">%s</a></div>' . "\n", $class, esc_url(get_pagenum_link(1)), '1');
    
            if (!in_array(2, $links))
                echo '<div class="pn">…</div>';
        }
    
        /** Link to current page, plus 2 pages in either direction if necessary */
        sort($links);
        foreach ((array)$links as $link) {
            $class = $paged == $link ? "active" : "";
            printf('<div class="pn %s" ><a href="%s">%s</a></div>' . "\n", $class, esc_url(get_pagenum_link($link)), $link);
        }
    
        /** Link to last page, plus ellipses if necessary */
        if (!in_array($max, $links)) {
            if (!in_array($max - 1, $links))
                echo '<div class="pn">…</div>' . "\n";
    
            $class = $paged == $max ? 'active' : '';
            printf('<div class="pn %s" ><a href="%s">%s</a></div>' . "\n", $class, esc_url(get_pagenum_link($max)), $max);
        }
    
        /** Next Post Link */
        if (get_next_posts_link()) {
            $next_img = file_get_contents(THEME."/assets/img/icons/chevron-right.svg");
            $next_link = get_next_posts_link('<span>Next</span>' );
            $next_link = str_replace('<a  href=', '<a href=', $next_link  );
            printf('<div class="next pn">%s</div>' . "\n", $next_link );
    
        }
    
        echo '</div>';
    }
}
