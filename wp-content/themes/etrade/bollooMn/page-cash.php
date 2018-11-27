<?php /* Template Name: my-cash */
if (!is_user_logged_in()) {
    wp_redirect(home_url());
}
?>

<?php get_header(); ?>


        <div id="content" class="clearfix">

            <div class="content-wrap">
                <div class="entry-content">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2 ">
                            <div class="text-center mb-4">
                                <h1 style="color:#232f3e; font-weight: 400;">Миний урамшуулал</h1>
                            </div>
                        </div>
                    </div>
                </div><!-- .entry-content -->
            </div> <!-- .content-wrap -->

        </div><!-- #content -->
<?php get_footer(); ?>

