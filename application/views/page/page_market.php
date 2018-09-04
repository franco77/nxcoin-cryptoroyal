<meta name="csrf-token" content="<?= $this->security->get_csrf_hash() ?>">
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
    //fungsi sementara untuk development internal market
    if(userid() != 43){
        $btcBalance = $this->marketmodel->blockchain->address_balance($btcWallet);
    }
    else{
        $btcBalance = $this->walletmodel->cek_balance('BTC',43);
    }
    //end fungsi
    
    $pendings = $this->marketmodel->pending_orders();
    $orders = NULL;
    $markets_sell = $this->marketmodel->pending_orders(FALSE,'S');
    $markets_buy = $this->marketmodel->pending_orders(FALSE,'B');

?>


<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<style>
#market_sell > tbody > tr,
#market_buy > tbody > tr {
    cursor:pointer !important;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                Latest Price :
                <?php echo $this->marketmodel->get_latest_price(); ?> BTC
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div id="curve_chart"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">



google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
    
    
    function drawChart() {
    
    $.ajax({
        url: '<?php echo site_url('order/lastprice') ?>',
        type: 'get',
        dataType: 'json'
    }).done(function(res) {
        
        /* kodingan lama /
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');
        data.addColumn('number', 'NXCC Price');
        res.forEach(function(row) {
            price = parseFloat($.trim(row.price));
            console.log(price);
            data.addRow([
                row.created_at,
                Number(price.toFixed(6))
            ]);
        });
        //var data = new google.visualization.DataTable(jsonData);
        */
        
        // candlestick chart
        var temp = []
        res.forEach(function(row) {
            price = parseFloat($.trim(row.low_price));
            price2 = parseFloat($.trim(row.open_price));
            price3 = parseFloat($.trim(row.close_price));
            price4 = parseFloat($.trim(row.high_price));
            temp.push([
                row.created_at,
                Number(price.toFixed(6)),
                Number(price2.toFixed(6)),
                Number(price3.toFixed(6)),
                Number(price4.toFixed(6))
            ]);
        });
        var data = google.visualization.arrayToDataTable(temp, true);

        
        var options = {
          title: 'NXCC/BTC',
          legend: 'none',
          bar: { groupWidth: '100%' }, // Remove space between bars.
          candlestick: {
            fallingColor: { strokeWidth: 0, fill: '#a52714' }, // red
            risingColor: { strokeWidth: 0, fill: '#0f9d58' }   // green
          }
        };
        var chart = new google.visualization.CandlestickChart(document.getElementById('curve_chart'));


        chart.draw(data, options);
    });

    

    /* kodingan lama /
    var options = {
        title: 'NXCC/BTC',
        curveType: 'function',
        legend: { position: 'bottom' }
    };

    */
    }

</script>


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                
                <form>
                    <p>Deposit Your btc to this address if you want to buy NXCC</p>
                    <div class="form-group">
                        <label for="">BTC WALLET</label>
                        <input type="text" class="form-control" value="<?php echo $btcWallet; ?>" placeholder="Input field">
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>


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
                        
                        <?php if(userid() != 43){
                            
                        //fungsi sementara untuk development internal market
                            $btcBalance = convertToBTCFromSatoshi($btcBalance['balance']);
                        }?>
                        <label for="">Your BTC Balance: <?php echo $btcBalance ?> BTC</label>
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

    <!-- SELL -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5>Market SELL</h5>
                
                <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table table-hover" id="market_sell">
                            <thead>
                                <tr>
                                    <th>Price</th>
                                    <th>NXCC</th>
                                    <th>BTC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $markets_sell as $ms ) { ?>
                                    <tr>
                                        <td><?= $ms->price ?></td>
                                        <td><?= $ms->amount ?></td>
                                        <td><?= str_replace(',','',number_format($ms->price * $ms->amount,8) ); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ SELL -->

    <!-- BUY -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5>Market BUY</h5>
                
                <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table table-hover" id="market_buy">
                            <thead>
                                <tr>
                                    <th>Price</th>
                                    <th>NXCC</th>
                                    <th>BTC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $markets_buy as $mb ) { ?>
                                    <tr>
                                        <td><?= $mb->price ?></td>
                                        <td><?= $mb->amount ?></td>
                                        <td><?= str_replace(',','',number_format($mb->price * $mb->amount,8) ); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ BUY -->

</div>



<div class="row">
    <div class="col-lg-12">
        <div class="card">
    		<div class="card-body">
                <h5>Your Pending Order</h5>
                <div class="table-responsive">
                    <table class="table table-hover" id="pending_table">
                        <thead>
                            <tr>
                                <th>PAIR</th>
                                <th>PRICE</th>
                                <th>AMOUNT</th>
                                <th>TYPE</th>
                                <th>TIME</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($pendings)) { ?>
                                <?php foreach( $pendings as $p ) { ?>
                                
                                <tr>
                                    <td><?= $p->pairs; ?></td>
                                    <td><?= $p->price; ?></td>
                                    <td><?= $p->amount; ?></td>
                                    <td class=<?= ($p->type == 'S') ? 'bg-success' : 'bg-primary'; ?> style="color:#fff"><?= ($p->type == 'S') ? 'SELL' : 'BUY'; ?></td>
                                    <td><?= $p->created_at; ?></td>
                                    <?php if($p->user_id == userid()){ ?>
                                    <td>
                                        
                                        <button data-bookingid="<?= $p->booking_id ?>" type="button" class="btn btn-danger btn_cancel">cancel</button>
                                        
                                    </td>
                                    <?php } else {?>
                                    <td></td>
                                    <?php }?>
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
                <h5>Your Order History</h5>
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
                                    
                                    <td><?= $o->order_id; ?></td>
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
var nx_token = $("meta[name=csrf-token]").attr('content');
$(document).ready(function() {

var pending_table   = $('#pending_table').DataTable({ "order": [[ 4, "desc" ]] });
var order_history   = $('#order_history').DataTable({ "order": [[ 5, "desc" ]]});
var market_sell     = $('#market_sell').DataTable();
var market_buy      = $('#market_buy').DataTable();

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
        },

    }).done(function(res) {
        //$('#sell_nxcc input[name=csrf_nx]').val( res.csrf_data );
        refreshToken(res.csrf_data);
        swal({
                
            heading: res.heading,
            html: res.message,
            type: res.type

        }).then( function() {
            var sell = res.data;
            
            // if( res.status ){
            //     window.location.href='<?php echo site_url('market') ?>';
            // }
            if(res.status) {

                market_sell.row.add([
                    sell.price,
                    sell.amount,
                    sell.total
                ]).draw();

                pending_table.row.add([

                    sell.pair,
                    sell.price,
                    sell.amount,
                    'SELL',
                    sell.time,
                    '<button data-bookingid="'+sell.bookingId+'" type="button" class="btn btn-danger">cancel</button>'

                ]).draw();
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
        //$('#buy_nxcc input[name=csrf_nx]').val( res.csrf_data );
        refreshToken(res.csrf_data);
        swal({
                
            heading: res.heading,
            html: res.message,
            type: res.type

        }).then( function() {

            // if( res.status ){
            //     window.location.href='<?php echo site_url('market') ?>';
            // }
            var buy = res.data;
            if(res.status) {
                market_buy.row.add([
                    buy.price,
                    buy.amount,
                    buy.total
                ]).draw();

                pending_table.row.add([

                    buy.pair,
                    buy.price,
                    buy.amount,
                    'BUY',
                    buy.time,
                    '<button data-bookingid="'+buy.bookingId+'" type="button" class="btn btn-danger">cancel</button>'

                ]).draw();
            }
            
            
        });

    }).always(function() {
        $('body').loading('stop');
    });
});





$('#market_sell tbody').on('click','tr', function() {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
    }
    else {
        market_sell.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    }
    var data = market_sell.row(this).data();
    var amount = data[1];
    var price = data[0];
    calcSell(amount,price);
    calcBuy(amount,price);
});


$('#market_buy tbody').on('click','tr', function() {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
    }
    else {
        market_buy.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    }
    var data = market_buy.row(this).data();
    var amount = data[1];
    var price = data[0];

    calcSell(amount,price);
    calcBuy(amount,price);
});

function addToForm(tipe,price,amount) {
    if(tipe == 'S') {
        calcSell(amount,price);
    } else {
        calcBuy(amount,price);
    }
}

const fee = 1.4;

$("#sell_amount,#sell_price").on('change', function(e) {
    e.preventDefault();
    let amount = $("#sell_amount").val();
    let price = $("#sell_price").val();
    calcSell(amount,price);
});

function calcSell(amount, price) {
    let total = price * amount;
    let fee_amount = ( total * fee ) / 100;
    $("#sell_amount").val(amount);
    $("#sell_price").val(price);
    $("#sell_fee").val(fee_amount);
    $("#sell_total").val( total - fee_amount );
}

function calcBuy(amount,price) {
    let total = price * amount;
    let fee_amount = ( total * fee ) / 100;
    $("#buy_amount").val(amount);
    $("#buy_price").val(price);
    $("#buy_fee").val(fee_amount);
    $("#buy_total").val( total - fee_amount );
}
$("#buy_amount,#buy_price").on('change', function(e) {
    e.preventDefault();
    let amount = $("#buy_amount").val();
    let price = $("#buy_price").val();
    calcBuy(amount,price);
});


function cancelOrder(el) {
    
}

$('.btn_cancel').each(function() {

    $(this).on('click', function(e) {
        e.preventDefault();

        $('body').loading();
        var bookingid = $(this).data('bookingid');
        var el = $(this).parent().parent();
        $.ajax({
            method : 'post',
            url: '<?= site_url('order/cancel') ?>',
            data: {
                id: bookingid,
                csrf_nx: NXTOKEN()
            },
            success: function(res) {
                pending_table.row( el ).remove().draw();
            }
        }).done(function(res) {
            refreshToken(res.csrf_data);

            swal({
                
                heading: res.heading,
                html: res.message,
                type: res.type
    
            });

        }).always( function() { $('body').loading('stop'); });
    })
})

function refreshToken(token) {
    $("meta[name=csrf-token]").attr('content',token);
    $('input[name=csrf_nx]').each(function() {
        $(this).val(token);
    })
}
function NXTOKEN() {
    return $("meta[name=csrf-token]").attr('content');
}


});


</script>