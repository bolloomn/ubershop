<?php
global  $wpdb;


    if(isset($_POST['send'])){

        if($_POST['id']==0){
            $data=[
                'type' => '1',
                'user_id' => get_current_user_id(),
                'product_id' => $_POST['product_id'],
                'date' => $_POST['date'],
                'cost' => $_POST['cost'],
                'quantity' => $_POST['quantity'],
            ];
            $wpdb->insert('trade_product_info', $data, ['%s', '%s', '%s', '%s', '%s', '%s']);
        } else {

        }

    }

    /*tatalt*/
    $rows = $wpdb->get_results(
             "
                    SELECT info.*, trade_posts.post_title as name 
                    FROM trade_product_info as info 
                    inner join trade_posts 
                    on trade_posts.id=info.product_id and trade_posts.post_type = 'product'
                    order by info.date desc
                    limit
                    "
             );
?>
<div class="wrap">
    <h1 class="wp-heading-inline mb-lg-3">Бараа таталт
        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tataltModal" data-id="0">Нэмэх</button>
    </h1>


    <table class="table table-striped table-bordered">
        <thead>
            <tr class="bg-primary text-white">
                <th>Огноо</th>
                <th>Бараа</th>
                <th>Үнэ</th>
                <th>Тоо</th>
                <th>Нийт</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?=?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
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
            <form method="post" action="<?php echo home_url('wp-admin/admin.php?page=product_tatalt'); ?>">
                <div class="modal-body">
                   <input type="hidden" id="id" name="id" value="0">
                   <div class="form-group">
                       <label for="date">Огноо</label>
                       <input type="datetime-local" id="date" name="date" class="form-control" required >
                   </div>
                   <div class="form-group">
                       <label for="product">Бараа</label>
                       <select  class="form-control select2" name="product_id" id="product" required>
                           <option value=""></option>
                           <option>1</option>
                           <option>2</option>
                           <option>3</option>
                           <option>4</option>
                           <option>5</option>
                       </select>
                   </div>
                   <div class="form-group">
                       <label for="quantity">Тоо ширхэг</label>
                       <input type="number" class="form-control"  name="quantity" id="quantity" required>
                   </div>
                   <div class="form-group">
                       <label for="cost">Нэгжийн үнэ (Байнга худалдан авдаг үнэ)</label>
                       <input type="number" class="form-control" name="cost" id="cost" step="0.01" required  >
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
            var id      = button.data('id')
            var modal   = $(this)
            if (id == 0) {
                modal.find('.modal-header').html('Бараа таталт нэмэх')
                modal.find('.modal-footer button.btn-primary').text('нэмэх')
            } else {
                modal.find('.modal-header').html('Бараа таталт засах')
                modal.find('.modal-footer button.btn-primary').text('засах')
            }
            $('.select-img-form').removeClass('selected');

            // modal.find(".modal-body #select2-product-container").text($('option:selected', '#product').text())
            modal.find('.modal-body #id').val(id)
        })
    });
</script>