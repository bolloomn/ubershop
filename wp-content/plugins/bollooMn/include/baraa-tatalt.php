<?php
global  $wpdb;

    //config
    $limit=30;
    $link=home_url('wp-admin/admin.php?page=product_tatalt&pages=');
    $pages=1;
    if(isset($_GET['pages'])){ if($_GET['pages']!=''){ $pages=$_GET['pages']; } }

    //actoin delete
    if(isset($_GET['del'])){
        if($_GET['del']!=''){
            $wpdb->delete( 'trade_product_info', [ 'id' => $_GET['del'] ] );
        }
        die('<meta http-equiv="refresh" content="0;URL=\''.$link.$pages.'\'" /> ');
    }

    //actoin  insert update
    if(isset($_POST['send'])){

        $data=[
            'type' => '1',
            'user_id' => get_current_user_id(),
            'product_id' => $_POST['product_id'],
            'date' => $_POST['date'],
            'cost' => $_POST['cost'],
            'quantity' => $_POST['quantity'],
            'amount' => $_POST['cost']*$_POST['quantity'],
        ];

        if($_POST['id']==0){
            $wpdb->insert('trade_product_info', $data, ['%s', '%s', '%s', '%s', '%s', '%s', '%s']);
        } else {
            $wpdb->update('trade_product_info', $data, ['ID'=> $_POST['id']], ['%s', '%s', '%s', '%s', '%s', '%s', '%s'], ['%d']);
        }
        update_prices($_POST['product_id'],  $_POST['date']);

    }

    //get products
    $query= "SELECT trade_posts.id, trade_posts.post_title
             FROM  trade_posts 
             where trade_posts.post_type = 'product'
             and post_status='publish'
             order by  trade_posts.post_title asc
             ";
    $products= $wpdb->get_results($query);

    /*tatalt*/
    $allpage = $wpdb->get_var(
                "SELECT CEIL(count(0)/".$limit.") 
                       FROM trade_product_info as info 
                       inner join trade_posts 
                       on trade_posts.id=info.product_id and trade_posts.post_type = 'product' and trade_posts.post_status='publish' and info.type=1"
            );

    $previous=1;
    if($pages>1){ $previous=$pages-1; }

    $next=$allpage;
    if($pages<$allpage){ $next=$pages+1; }

    $query= "SELECT info.*, trade_posts.post_title as name 
             FROM trade_product_info as info 
             inner join trade_posts 
             on trade_posts.id=info.product_id and trade_posts.post_type = 'product' and trade_posts.post_status='publish' and info.type=1
             order by info.date desc
             limit ".($pages-1).", ".$limit;

     $rows = $wpdb->get_results($query);

?>
<div class="wrap">
    <h1 class="wp-heading-inline mb-lg-3">Бараа таталт
        <button type="button"
                class="btn btn-success btn-sm"
                data-toggle="modal"
                data-target="#tataltModal"
                data-id="0"
                data-product=""
                data-date=""
                data-cost=""
                data-quantity=""
        >Нэмэх</button>
    </h1>


    <table class="table table-hover table-bordered">
        <thead>
            <tr class="bg-secondary text-white">
                <th width="200">Огноо</th>
                <th>Бараа</th>
                <th>Үнэ</th>
                <th>Тоо</th>
                <th>Нийт</th>
                <th width="75"></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo $row->date; ?></td>
                <td><?php echo $row->name; ?></td>
                <td><?php echo number_format($row->cost,2, '.', ''); ?> төг</td>
                <td><?php echo $row->quantity; ?></td>
                <td><?php echo number_format($row->amount,2, '.', ''); ?> төг</td>
                <td>

                    <?php  if(time()-60*60*24*30<=strtotime($row->date)){?>
                    <a  class="btn btn-sm btn-secondary text-white"
                        data-toggle="modal"
                        data-target="#tataltModal"
                        data-id="<?=$row->id;?>"
                        data-product="<?=$row->product_id;?>"
                        data-date="<?=date('Y-m-d H:i', strtotime($row->date));?>"
                        data-cost="<?php echo number_format($row->cost,2, '.', ''); ?>"
                        data-quantity="<?=$row->quantity;?>"
                    ><i class="fa fa-pencil"></i></a>
                    <a class="btn btn-sm btn-secondary text-white"
                       href="<?php echo $link.$pages.'&del='.$row->id; ?>"
                       onclick="return confirm('Та устгахдаа итгэлтэй байна уу!')">
                        <i class="fa fa-trash"></i>
                    </a>
                    <?php } ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mt-4">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="<?=$link.$previous;?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <?php for ($page=1; $page<=$allpage; $page++){ ?>
                    <li class="page-item <?php if($page==$pages){ echo 'active'; }?>"><a class="page-link" href="<?php echo $link.$page; ?>"><?php echo $page;?></a></li>
                <?php }; ?>
                <li class="page-item">
                    <a class="page-link" href="<?=$link.$next;?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="tataltModal"  role="dialog" aria-labelledby="tataltModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Бараа таталт нэмэх</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="<?php echo $link.$pages; ?>">
                <div class="modal-body">
                   <input type="hidden" id="id" name="id" value="0">
                   <div class="form-group">
                       <label for="date">Огноо</label>
                       <input type="text" id="date"  name="date" class=" date form-control" required >
                   </div>
                   <div class="form-group">
                       <label for="product">Бараа</label>
                       <select  class="form-control select2" name="product_id" id="product" required>
                           <option value=""></option>
                           <?php foreach ($products as $product){ ?>
                           <option value="<?php echo $product->id; ?>"><?php echo $product->post_title; ?> /<?php echo $product->id; ?>/</option>
                           <?php } ?>
                       </select>
                   </div>
                   <div class="form-group">
                       <label for="quantity">Тоо ширхэг</label>
                       <input type="number" class="form-control" min="0" name="quantity" id="quantity" required>
                   </div>
                   <div class="form-group">
                       <label for="cost">Нэгжийн үнэ (Байнга худалдан авдаг үнэ)</label>
                       <input type="number" class="form-control" min="0" name="cost" id="cost" step="0.01" required  >
                   </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Хаах</button>
                    <button type="submit" name="send" class="btn btn-primary">Хадгалах</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#tataltModal').on('show.bs.modal', function (event) {
            var button  = $(event.relatedTarget)
            var id = button.data('id')
            var product = button.data('product')
            var date = button.data('date')
            var cost = button.data('cost')
            var quantity = button.data('quantity')
            var modal   = $(this)

            if (id == 0) {
                modal.find('.modal-header').html('Бараа таталт нэмэх')
                modal.find('.modal-footer button.btn-primary').text('нэмэх')
            } else {
                modal.find('.modal-header').html('Бараа таталт засах')
                modal.find('.modal-footer button.btn-primary').text('засах')
            }
            $('.select-img-form').removeClass('selected');

            modal.find('.modal-body #id').val(id)

            modal.find('.modal-body #product').val(product)
            modal.find('.modal-body input#date').val(date)
            console.log(date);
            modal.find('.modal-body #cost').val(cost)
            modal.find('.modal-body #quantity').val(quantity)
            modal.find(".modal-body #select2-product-container").text($('option:selected', '#product').text())
        })
    });
</script>