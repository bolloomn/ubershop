<?php get_header(); ?>

        <?php if ( have_posts() ) : ?>

            <?php
                /* Queue the first post, that way we know
                 * what author we're dealing with.
                 */
                the_post();
            ?>

            <article>
                <div class="content-wrap">
                    <?php if ( function_exists('pukka_post_author') ) : ?>
                        <?php echo pukka_post_author(); ?>
                    <?php endif; ?>
                </div> <!--. content-wrap -->
            </article>

            <?php
                /* Since we called the_post() above, we need to
                 * rewind the loop back to the beginning.
                 */
                rewind_posts();
            ?>

            <?php /* The loop */ ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'content', get_post_format() ); ?>
            <?php endwhile; ?>

            <?php pukka_paging_nav(); ?>
        
        <?php else : ?>
            <?php get_template_part( 'content', 'none' ); ?>
        <?php endif; ?>

<?php get_footer(); ?>