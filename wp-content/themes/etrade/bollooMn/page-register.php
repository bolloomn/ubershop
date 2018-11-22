<?php /* Template Name: register */ ?>

<?php get_header(); ?>
<?php if ( have_posts() ) : ?>
    <?php /* Start the Loop */ ?>
    <?php while ( have_posts() ) : the_post(); ?>
        <div id="content" class="clearfix">

            <div class="content-wrap">
                <div class="entry-content">
                    <form id="loginform" method="post">
                        <fieldset>
                            asds
                        </fieldset>
                    </form>
                </div><!-- .entry-content -->
            </div> <!-- .content-wrap -->

        </div><!-- #content -->
    <?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>

