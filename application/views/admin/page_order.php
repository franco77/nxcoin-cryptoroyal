<!-- INCLUDE CSS -->

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

<!--/ INCLUDE CSS -->



<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="card">
            <div class="card-header">Order History</div>
            <div class="card-body">
                
                <div class="table-responsive">
                    <table id="tb_order_history" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>Pair</th>
                                <th>Price</th>
                                <th>Amount</th>
                                <th>Seller Username</th>
                                <th>Buyer Username</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                
            </div>
        </div>

    </div>
</div>


<!-- INCLUDE JS LIB -->

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<!--/ INCLUDE JS LIB -->

<script>

$(document).ready(function() {


$("#tb_order_history").DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": env.site_url+'admin/order/history',
    "columns": [
        { "data": "order_id" },
        { "data": "pair" },
        { "data": "price" },
        { "data": "amount" },
        { "data": "seller_username" },
        { "data": "buyer_username" },
        { "data": "created_at" }
    ]
});


}); //eof document ready

</script>