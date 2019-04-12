<?php
/*
* Template name: Home Page
*/

/*
* This is page template for the Home Page
*/
?>
<?php get_header(); ?>


<!--<div id="content" class="clearfix">-->
    <div id="primary" class="content-area" >
        <main id="main" class="site-main" role="main">
            <?php echo  do_shortcode('[recent_products paginate="true" limit="36"]'); ?>
        </main>
    </div>

<!--</div><!-- #content -->

<?php get_footer(); ?>