<?php
    global $wpdb;

    $sdate=date('Y-m-d 00:00');
    $fdate=date('Y-m-d 23:59');
    if(isset($_GET['sdate'])){   $sdate=$_GET['sdate'];}
    if(isset($_GET['fdate'])){   $fdate=$_GET['fdate'];}
    $link=home_url('wp-admin/admin.php?page=product_uldegdel&sdate='.$sdate.'&fdate='.$fdate);


    if(isset($_POST['send'])){
        if($_POST['id']!=0){
            $a=1; if($_POST['type']==2){ $a=-1; }
            $cost=unit_price($_POST['id'], $_POST['date']);
            $data=[
                'type' => $_POST['type'],
                'user_id' => get_current_user_id(),
                'product_id' => $_POST['id'],
                'date' => $_POST['date'],
                'cost' => $cost,
                'quantity' => $a*$_POST['quantity'],
                'amount' => $a*$cost*$_POST['quantity'],
                'content' => $_POST['content'],
            ];
            $wpdb->insert('trade_product_info', $data, ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']);
            update_prices($_POST['id'],  $_POST['date']);
        }
    }



     $query= "select 
                p.ID,
                p.post_title as name,
                IFNULL(END.quantity,0) 
                - IFNULL(nemegdsen.quantity,0) 
                + IFNULL(horogdson.quantity,0) 
                + IFNULL(zarlagdsan.quantity,0) 
                AS start_quantity,
                IFNULL(END.amount,0) 
                - IFNULL(nemegdsen.amount,0) 
                + IFNULL(horogdson.amount,0) 
                + IFNULL(zarlagdsan.amount,0) 	
                AS start_amount,
                IFNULL(nemegdsen.quantity,0) as nemegdsen_quantity,
                IFNULL(nemegdsen.amount,0) as nemegdsen_amount,
                IFNULL(horogdson.quantity,0) as horogdson_quantity,
                IFNULL(horogdson.amount,0) as horogdson_amount,
                IFNULL(zarlagdsan.quantity,0) as zarlagdsan_quantity,
                IFNULL(zarlagdsan.amount,0) as zarlagdsan_amount,
                IFNULL(end.quantity,0) as end_quantity,
                IFNULL(end.amount,0) as end_amount
                from (
                    select ID, post_title
                    from trade_posts
                    where post_type='product'
                    and post_status='publish' 
                ) p
                LEFT JOIN (
                    select product_id, sum(quantity) as quantity,  sum(amount) as amount
                    from trade_product_info as info
                    left join trade_posts 
                    on trade_posts.ID=info.order_id
                    where  
                    info.date<= '".$fdate."'
                    AND (info.type!=0 or (info.type=0 and trade_posts.post_status='wc-completed' ))
                    GROUP BY product_id
                ) end 
                on `end`.product_id=p.ID
                LEFT JOIN (
                    select product_id, sum(quantity) as quantity,  sum(amount) as amount
                    from trade_product_info 
                    where date BETWEEN '".$sdate."' AND '".$fdate."'
                    and type=1
                    GROUP BY product_id
                ) nemegdsen
                on `nemegdsen`.product_id=p.ID
                LEFT JOIN (
                    select product_id, (-1)*sum(quantity) as quantity,  (-1)*sum(amount) as amount
                    from trade_product_info  as info
                    join trade_posts 
                    on trade_posts.ID=info.order_id
                    where trade_posts.post_status='wc-completed'
                    and info.date BETWEEN '".$sdate."' AND '".$fdate."'
                    and info.type=0
                    GROUP BY product_id
                ) horogdson
                on `horogdson`.product_id=p.ID
                LEFT JOIN (
                    select product_id, (-1)*sum(quantity) as quantity,  (-1)*sum(amount) as amount
                    from trade_product_info 
                    where date BETWEEN '".$sdate."' AND '".$fdate."'
                    and type in (2,3)
                    GROUP BY product_id
                ) zarlagdsan
                on `zarlagdsan`.product_id=p.ID
                order by name asc
            ";
    $rows= $wpdb->get_results($query);
?>
<div class="wrap">
    <div class="row">
        <div class="col-lg-4">
            <h1 class="wp-heading-inline mb-lg-3">Бараа материалын үлдэгдэл</h1>
        </div>
        <div class="col-lg-8 text-lg-right">
            <form method="get"  class="form-inline pull-right p-3">
                <input name="page" value="product_uldegdel" type="hidden">
                <input type="text" name="sdate" value="<?=$sdate;?>" class="form-control date mb-2 mr-sm-2">
                <input type="text"  name="fdate" value="<?=$fdate;?>" class="form-control date mb-2 mr-sm-2">
                <button type="submit" class="btn btn-primary mb-2">Хайх</button>
            </form>
        </div>
    </div>

    <table class="bg-white table table-hover table-bordered ">
        <thead>
        <tr class="bg-secondary text-white">
            <th >ID</th>
            <th>Бараа</th>
            <th>Эхний үлдэгдэл</th>
            <th>Нэмэгдсэн</th>
            <th>Хорогдсон</th>
            <th>Эцсийн үлдэгдэл</th>
            <th>Зарлагдсан</th>
            <th width="90"></th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row){ ?>
                <tr>
                    <td><?php echo $row->ID; ?></td>
                    <td><?php echo $row->name; ?></td>
                    <td>
                        <?php echo $row->start_quantity.' ши<br>'.number_format($row->start_amount, 2, '.', '').' ₮'; ?>
                        <br>д.ө:
                        <?php
                            if($row->start_quantity!=0) { echo number_format($row->start_amount/$row->start_quantity, 2, '.', ''); }
                            else { echo 0; }
                        ?>
                        ₮
                    </td>
                    <td><?php echo $row->nemegdsen_quantity.' ши<br>'.number_format($row->nemegdsen_amount, 2, '.', '').' ₮'; ?></td>
                    <td><?php echo $row->horogdson_quantity.' ши<br>'.number_format($row->horogdson_amount, 2, '.', '').' ₮'; ?></td>
                    <td>
                        <?php echo $row->end_quantity.' ши<br>'.number_format($row->end_amount, 2, '.', '').' ₮'; ?>
                        <br>д.ө:
                        <?php
                            if($row->end_quantity!=0) { echo number_format($row->end_amount/$row->end_quantity, 2, '.', ''); }
                            else { echo 0; }
                        ?>
                        ₮
                    </td>
                    <td><?php echo $row->zarlagdsan_quantity.' ши<br>'.number_format($row->zarlagdsan_amount, 2, '.', '').' ₮'; ?></td>
                    <td >
                        <a  class="btn btn-sm btn-secondary text-white"
                            data-toggle="modal"
                            data-target="#tataltModal"
                            data-id="<?=$row->ID;?>"
                            data-end="<?=$row->end_quantity;?>"
                        ><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-sm btn-secondary text-white"
                           href="<?php echo $link.'&pid='.$row->ID; ?>" >
                            <i class="fa fa-history"></i>
                        </a>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>




<!-- Modal -->
<div class="modal fade" id="tataltModal"  role="dialog" aria-labelledby="tataltModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Засварлах</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="<?php echo $link; ?>">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" value="0">
                    <div class="form-group">
                        <label for="product">Төрөл</label>
                        <select  class="form-control" name="type" id="type" required>
                            <option value="2">Зарлагдах /үлдэгдэлээс хасах/</option>
                            <option value="3">Буцаах бичилт /үлдэгдэл дээр нэмэх/</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Огноо</label>
                        <input type="text" id="date"  name="date" class="form-control date" required >
                    </div>
                    <div class="form-group">
                        <label for="quantity">Тоо ширхэг</label>
                        <input type="number" class="form-control" min="1"  max="" setMax="" name="quantity" id="quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Тайлбар</label>
                       <textarea class="form-control" id="content" name="content" required>

                       </textarea>
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
            var end = button.data('end')
            var modal   = $(this)
            modal.find('.modal-body #id').val(id)
            modal.find('.modal-body #quantity').attr('max', end)
            modal.find('.modal-body #quantity').attr('setMax', end)
        })
    });


    $("#type").change(function () {
        if($(this).val()==2){
            $("#quantity").attr('max', $("#quantity").attr('setMax'));
        } else {
            $("#quantity").attr('max', '');
        }
    });
</script>