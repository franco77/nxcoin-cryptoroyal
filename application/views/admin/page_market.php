<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<?php
    $orders = $this->marketmodel->pending_orders(FALSE, NULL, TRUE);
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-hover" id="admin_order_table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>USERNAME</th>
                                <th>TYPE</th>
                                <th>PRICE</th>
                                <th>AMOUNT</th>
                                <th>PAIRS</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($orders) > 0) { ?>
                                <?php foreach($orders as $order) { ?>
                                <tr>
                                    <td><?= $order->booking_id ?></td>
                                    <td><?= trim($order->username) ?></td>
                                    <td><?= ($order->type == 'S') ? 'SELL' : 'BUY' ?></td>
                                    <td><?= $order->price ?></td>
                                    <td><?= $order->amount ?></td>
                                    <td><?= strtoupper($order->pairs) ?></td>
                                    <td>
                                        <button class="btn btn-primary submit" data-order='<?= json_encode($order); ?>'>
                                            <?= ($order->type == 'S') ? 'BUY' : 'SELL' ?>
                                        </button>
                                    </td>
                                </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!--/ TABLE -->
            </div>
        </div>
        
    </div>
</div>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>

$(document).ready(function() {

    $("#admin_order_table").DataTable();

    $('.submit').on('click', function(e) {
        e.preventDefault();
        var order = $(this).data('order');
        
        if(order.type === 'S') {
            executeOrder(order, "<?= base_url('order/buy') ?>");
        } else {
            executeOrder(order, "<?= base_url('order/sell') ?>");
        }
    });

    function executeOrder(data, url) {
        csrf = $('meta[name=csrf_nx]');
        $.ajax({
            method: 'POST',
            type: "application/json",
            url: url,
            data: {
                price: data.price,
                amount: data.amount,
                csrf_nx: csrf.attr("content")
            },
            beforeSend: function() {
                $('body').loading();
            },
            success: function(res) {

                console.log(res);
                swal({
                
                    heading: res.heading,
                    html: res.message,
                    type: res.type
    
                }).then( function() {
                    if(res.status) {
                        location.reload();
                    }
                });
            },
            error: function(err) {

                console.log(err);

            }
        }).always(function(res) {

            $('body').loading('stop');
            csrf.attr("content", res.csrf_data);

        });
    }



});

</script>