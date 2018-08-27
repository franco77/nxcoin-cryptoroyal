<?php
    $btcWallet = $this->marketmodel->hasBtcWallet();
    $nxccBalance = $this->walletmodel->cek_balance('A');
    $btcBalance = null;

    if( !$btcWallet ) {
        // buatkan wallet btc jika belum punya
        $btcWallet = $this->marketmodel->create_user_btc_wallet();
        
    } else {

        $btcWallet = $btcWallet->wallet_address;

    }

    $btcBalance = $this->marketmodel->blockchain->address_balance($btcWallet);
    
    $pendings = $this->marketmodel->pending_orders();
    $orders = NULL;

?>


<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">


<div class="row">
    <div class="col-lg-6">
    	<div class="card">
    		<div class="card-body">
                
                <?php echo form_open('', array('class' => 'form-horizontal m-t-20', 'id' => 'sell_nxcc' )); ?>
                    <legend>SELL NXCC</legend>
                    <div class="form-group">
                        <label for="">Your NXCC Balance: <?= $nxccBalance; ?> NXCC</label>
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="text" name="amount" class="form-control" id="sell_amount" placeholder="Amount">
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" name="price" class="form-control" id="sell_price" placeholder="Price">
                    </div>
                    <div class="form-group">
                        <label>Estimation Fee</label>
                        <input type="text" class="form-control" id="sell_fee" placeholder="Estimation Fee" readonly>
                    </div>
                    <div class="form-group">
                        <label>Total</label>
                        <input type="text" class="form-control" id="sell_total" placeholder="Total" readonly>
                    </div>
                
                    
                
                    <button type="submit" class="btn btn-primary">Submit</button>
                <?php echo form_close(); ?> 
                
            </div>
        </div>
    </div>

    <div class="col-lg-6">
    	<div class="card">
    		<div class="card-body">
                
                <?php echo form_open('', array('class' => 'form-horizontal m-t-20', 'id' => 'buy_nxcc' )); ?>
                    <legend>BUY NXCC</legend>
                    <div class="form-group">
                        <label for="">Your BTC Balance: <?php echo convertToBTCFromSatoshi($btcBalance['balance']); ?> BTC</label>
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="text" name="amount" class="form-control" id="buy_amount" placeholder="Amount">
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" name="price" class="form-control" id="buy_price" placeholder="Price">
                    </div>
                    <div class="form-group">
                        <label>Estimation Fee</label>
                        <input type="text" class="form-control" id="buy_fee" placeholder="Estimation Fee" readonly>
                    </div>
                    <div class="form-group">
                        <label>Total</label>
                        <input type="text" class="form-control" id="buy_total" placeholder="Total" readonly>
                    </div>
                
                    
                
                    <button type="submit" class="btn btn-primary">Submit</button>
                <?php echo form_close(); ?> 
                
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
    		<div class="card-body">
                <h5>Pending Order</h5>
                <div class="table-responsive">
                    <table class="table table-hover" id="pending_table">
                        <thead>
                            <tr>
                                <th>BOOKING ID</th>
                                <th>PAIR</th>
                                <th>PRICE</th>
                                <th>AMOUNT</th>
                                <th>TYPE</th>
                                <th>TIME</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($pendings)) { ?>
                                <?php foreach( $pendings as $p ) { ?>
                                
                                <tr>
                                    <td><?= $p->booking_id; ?></td>
                                    <td><?= $p->pairs; ?></td>
                                    <td><?= $p->price; ?></td>
                                    <td><?= $p->amount; ?></td>
                                    <td><?= ($p->type == 'S') ? 'SELL' : 'BUY'; ?></td>
                                    <td><?= $p->created_at; ?></td>
                                </tr>

                                <?php } ?>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card">
    		<div class="card-body">
                <h5>Order History</h5>
                <div class="table-responsive">
                    <table class="table table-hover" id="order_history">
                        <thead>
                            <tr>
                                <th>ORDER ID</th>
                                <th>PAIR</th>
                                <th>PRICE</th>
                                <th>AMOUNT</th>
                                <th>TYPE</th>
                                <th>TIME</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($orders)) { ?>
                                <?php foreach( $orders as $o ) { ?>
                                
                                <tr>
                                    <td><?= $o->booking_id; ?></td>
                                    <td><?= $o->pairs; ?></td>
                                    <td><?= $o->price; ?></td>
                                    <td><?= $o->amount; ?></td>
                                    <td><?= ($o->type == 'S') ? 'SELL' : 'BUY'; ?></td>
                                    <td><?= $o->created_at; ?></td>
                                </tr>

                                <?php } ?>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {


$("#sell_nxcc").submit( function(e) {
    e.preventDefault();
    $('body').loading();

    $.ajax({
        url: '<?php echo site_url('order/sell') ?>',
        type: 'post',
        dataType: 'json',
        data: $('#sell_nxcc').serialize(),
        error: function(err) {
            console.log(err);
        }

    }).done(function(res) {
        $('#sell_nxcc input[name=csrf_nx]').val( res.csrf_data );
        swal({
                
            heading: res.heading,
            html: res.message,
            type: res.type

        }).then( function() {

            if( res.status ){
                window.location.href='<?php echo site_url('market') ?>';
            }
            
        });

    }).always(function() {
        $('body').loading('stop');
    });
});

$("#buy_nxcc").submit( function(e) {
    e.preventDefault();
    $('body').loading();

    $.ajax({
        url: '<?php echo site_url('order/buy') ?>',
        type: 'post',
        dataType: 'json',
        data: $('#buy_nxcc').serialize(),
        error: function(err) {
            console.log(err);
        }

    }).done(function(res) {
        $('#buy_nxcc input[name=csrf_nx]').val( res.csrf_data );
        swal({
                
            heading: res.heading,
            html: res.message,
            type: res.type

        }).then( function() {

            if( res.status ){
                window.location.href='<?php echo site_url('market') ?>';
            }
            
        });

    }).always(function() {
        $('body').loading('stop');
    });
});



$('#pending_table').DataTable();
$('#order_history').DataTable();

const fee = 1.4;

$("#sell_amount,#sell_price").on('change', function(e) {
    e.preventDefault();
    let amount = $("#sell_amount").val();
    let price = $("#sell_price").val();
    let total = price * amount;
    let fee_amount = ( total * fee ) / 100;
    $("#sell_fee").val(fee_amount);
    $("#sell_total").val( total - fee_amount );
});
$("#buy_amount,#buy_price").on('change', function(e) {
    e.preventDefault();
    let amount = $("#buy_amount").val();
    let price = $("#buy_price").val();
    let total = price * amount;
    let fee_amount = ( total * fee ) / 100;
    $("#buy_fee").val(fee_amount);
    $("#buy_total").val( total - fee_amount );
})

});


</script>