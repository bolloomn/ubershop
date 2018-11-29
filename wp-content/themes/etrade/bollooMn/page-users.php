<?php /* Template Name: my-users */

if (!is_user_logged_in()) {
    wp_redirect(home_url());
}



$user = wp_get_current_user();



?>

<?php get_header(); ?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri().'/js/jquery.jOrgChart.css'; ?>" >
<script src="<?php echo get_template_directory_uri().'/js/jquery.jOrgChart.js';?>" ></script>
<script>
    jQuery(document).ready(function() {
        jQuery("#org").jOrgChart({
            chartElement : '#chart',
            dragAndDrop  : false
        });
    });
</script>

<div class="text-center mt-4 mb-4">
    <h1 style="color:#232f3e; font-weight: 400;">Миний гишүүд</h1>
</div>

<ul id="org" style="display:none">
    <li>
        <?php
            echo $user->user_login;
            userTree($user->ID);
        ?>
    </li>
</ul>
<div id="chart" class="orgChart"></div>


<?php get_footer(); ?>

