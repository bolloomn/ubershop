<?php
$stock=WCM_Stock::get_instance();
$sda_seller=0;
if(isset($_GET['seller']) and $_GET['seller']!=0){
    $sda_seller=$_GET['seller'];
}
?>
<script>
    $( document ).ready(function() {
        $('.stock_number').prop('readonly', true);
        $('.line-price').prop('readonly', true);
    });
</script>
<div class="wrap">
<div class="t-col-12">
    <div class="toret-box box-info">
        <div class="row">
            <div class="col-lg-4">
                <h1 class="wp-heading-inline mb-lg-3">Барааны тайлан</h1>
            </div>
            <div class="col-lg-8 text-lg-right">

                <form method="get"  class="form-inline pull-right p-3 report-form">
                    <input name="page" value="baraa_report" type="hidden">
                    <?php $sellers=get_terms('product_tag', 'hide_empty=0');?>
                    <select  class="form-control select2" name="seller" id="seller" >

                        <option value="0">Бүх борлуулагч</option>
                        <?php foreach ($sellers as $seller){ ?>
                            <option value="<?php echo $seller->term_id; ?>"  <?php if($sda_seller==$seller->term_id){ echo 'selected';} ?>><?php echo $seller->name; ?></option>
                        <?php } ?>
                    </select>

                    <button type="submit" class="btn btn-primary mb-2  mr-sm-2">Шүүх</button>
                    <button type="button" onclick="window.print();" class="btn btn-success mb-2">Хэвлэх</button>

                </form>

            </div>
        </div>
        <div class="box-body">



            <div class="clear"></div>
            <form method="post" action="" style="position:relative;">
                <div class="lineloader"></div>
                <table class="table-bordered table">
                    <thead>
                    <tr class="bg-primary text-white">
                      <th>Бүтээгдэхүүний нэр</th>
                      <th>төрөл</th>
                      <th>үнэ</th>
                      <th>Борлуулагч</th>
                      <th>Үлдэгдэл</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $products = $stock->get_products( $_GET );

                    if( !empty( $products->posts ) ){
                        foreach( $products->posts as $item ){
                            $product_meta = get_post_meta($item->ID);
                            $item_product = wc_get_product($item->ID);
                            $product_type = $item_product->get_type();
                            ?>
                            <tr class="bg-white">
                                <td><?php echo $item_product->name; ?></td>

                                <td class="td_center">
                                    <?php if($product_type == 'variable'){
                                        echo 'variables';
                                    }else{
                                        echo $product_type;
                                    } ?>
                                </td>
                               <?php  WCM_Table::price_box( $product_meta, $item->ID, $product_type ); ?>
                                <td><?php  if($product_meta['seller'][0]){ $t=get_term($product_meta['seller'][0]); echo $t->name; }; ?></td>
                                <?php  WCM_Table::qty_box($product_meta, $item->ID, $item_product); ?>
                            </tr>
                            <?php
                            if($product_type == 'variable'){
                                $args = array(
                                    'post_parent' => $item->ID,
                                    'post_type'   => 'product_variation',
                                    'numberposts' => -1,
                                    'post_status' => 'publish',
                                    'order_by' => 'menu_order'
                                );
                                $variations_array = $item_product->get_children();
                                foreach($variations_array as $vars){

                                    $product_meta = get_post_meta($vars);
                                    $item_product = wc_get_product($vars);
                                    $product_type = 'product variation' ;
                                    ?>
                                    <tr class="bg-info">


                                        <td><?php
                                            foreach($item_product->get_variation_attributes() as $k => $v){

                                                $tag = get_term_by('slug', $v, str_replace('attribute_','',$k));
                                                if($tag == false ){
                                                    echo $v.' ';
                                                }else{
                                                    if(is_array($tag)){
                                                        echo $tag['name'].' ';
                                                    }else{
                                                        echo $tag->name.' ';
                                                    }
                                                }
                                            }
                                            ?></td>
                                        <td><?php echo $product_type; ?></td>
                                        <?php WCM_Table::price_box($product_meta, $vars); ?>
                                        <td><?php  if($product_meta['seller'][0]){ $t=get_term($product_meta['seller'][0]); echo $t->name; }; ?></td>
                                        <?php WCM_Table::qty_box($product_meta, $vars, $item_product); ?>

                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        <?php }

                    }
                    ?>
                    </tbody>
                </table>
                <div class="clear"></div>

            </form>
            <div class="clear"></div>


        </div>
    </div>


</div>
</div>