<section id="primary" class="site-content" style="width:100%;margin: 0px 20px 20px 0px;">
    <div id="content" role="main" >
        <?php
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $options = get_option('LCGRW_options');

        $args = array(
            'posts_per_page' => $options['LCGRW_field_pagesize'],
            'paged' => $paged,
            'adsdomain' => $_SERVER['SERVER_NAME'],
            'post_type' => LCGRW_PLUGIN_POST_TYPE
        );

        // The Query
        query_posts( $args );

        // The Loop
        while ( have_posts() ) : the_post();
            echo '<h2><a href="';
            the_permalink();
            echo '" rel="bookmark">';
            the_title();
            echo '</a></h2>';
            echo '<div style="100%">';
            the_excerpt();
            echo '</div>';
        endwhile;
        $prev_link = get_previous_posts_link();
        $next_link = get_next_posts_link();
        ?>
    </div>

    <!-- pagination -->
    <span class="prev"><?php previous_posts_link(); ?></span>
    <?php if ($prev_link || $next_link) { ?>
    &nbsp;||&nbsp;
    <?php } ?>
    <span class="next"><?php next_posts_link(); ?></span>


    <?php
        //Reset Query
        wp_reset_query();
    ?>


</section>

