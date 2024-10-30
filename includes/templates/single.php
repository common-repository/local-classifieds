<?php get_header(); ?>

    <section id="primary" class="site-content" style="width:100%;margin: 0px 20px 20px 0px;">
    <div id="content" role="main">

        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="entry-header">
                    <?php if ( is_single() ) : ?>
                        <h1 class="entry-title"><?php single_post_title(); ?></h1>
                    <?php endif;?>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <div class="entry-content">
                    <?php the_taxonomies(array('sep' => '</br>')); ?>
                </div>

            </article>
        <?php endwhile;?>
    </div>
</section>
 
<?php get_footer(); ?>