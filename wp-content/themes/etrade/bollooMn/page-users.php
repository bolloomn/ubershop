<?php /* Template Name: my-users */

if (!is_user_logged_in()) {
    wp_redirect(home_url());
}



$user = wp_get_current_user();



?>

<?php get_header(); ?>


<div class=" mt-4 mb-4">
    <h1 style="color:#232f3e; font-weight: 400;">Миний гишүүд</h1>
    <p>миний доор бүртгүүлсэн гишүүд</p>
</div>

<?php
$query= "SELECT trade_users.user_login, trade_users.ID  FROM trade_usermeta 
                join trade_users
                on trade_users.ID=trade_usermeta.user_id
                and meta_key='t_parent'
                and meta_value=".$user->ID;
$users = $wpdb->get_results($query);
?>
<table class="table table-bordered">
    <?php foreach ($users as $user){?>
    <tr>
            <td class="text-white bg-success"><?php echo $user->user_login; ?></td>
    </tr>
        <?php
            $query= "SELECT trade_users.user_login, trade_users.ID  FROM trade_usermeta 
                    join trade_users
                    on trade_users.ID=trade_usermeta.user_id
                    and meta_key='t_parent'
                    and meta_value=".$user->ID;
            $subusers = $wpdb->get_results($query);
            foreach ($subusers as $subuser){
                ?>
                <tr>
                    <td class="text-white bg-info"> - <?php echo $subuser->user_login; ?></td>
                </tr>
         <?php  } ?>

    <?php } ?>
</table>

<?php get_footer(); ?>

