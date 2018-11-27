<?php /* Template Name: my-cash */
if (!is_user_logged_in()) {
    wp_redirect(home_url());
}
$user = wp_get_current_user();
$wallet=get_user_meta($user->ID, 'cash', true);
if(!$wallet) { $wallet=0; }

$query= "SELECT order_item_id FROM trade_cash where user_id=".$user->ID." ";
$cashes = $wpdb->get_results($query);

?>

<?php get_header(); ?>


        <div id="content" class="clearfix">

            <div class="content-wrap">
                <div class="entry-content">
                    <div class="row">

                        <div class="col-lg-8 ">
                            <div class="bg-white p-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="heading-title">МИНИЙ ХЭТЭВЧ <span class="pull-right"><?php echo $wallet; ?>₮</span></div>
                                    </div>
                                    <div class="col-lg-12">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr class="bg-secondary">
                                                    <th class="text-white">Огноо</th>
                                                    <th class="text-white">Төрөл</th>
                                                    <th class="text-white">Төлөв</th>
                                                    <th class="text-white">Мөнгөн дүн</th>
                                                    <th class="text-white">Тайлбар</th>
                                                    <th class="text-white">Холбоос</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="col-lg-4 ">   <div class="bg-white">asdas</div></div>

                    </div>
                </div><!-- .entry-content -->
            </div> <!-- .content-wrap -->

        </div><!-- #content -->
<?php get_footer(); ?>

