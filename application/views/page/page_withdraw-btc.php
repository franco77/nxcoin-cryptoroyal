<?php

    $wd_history = $this->walletmodel->history_wd_btc();

?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="card-body">
                
                <form >
                    <legend>Withdraw BTC</legend>
                    <?= csrf_field(); ?>
                    <div class="form-group">
                        <label for="">Receiver Address</label>
                        <input type="text" class="form-control" id="receiver" placeholder="Receiver Address">
                    </div>
                    <div class="form-group">
                        <label for="">Amount</label>
                        <input type="text" class="form-control" id="amount" placeholder="Amount">
                    </div>
                    
                
                    
                
                    <button type="button" id="btn_wd" class="btn btn-primary">Send</button>
                </form>
                
            </div>
        </div>
    </div>
    
    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
        <div class="card">
            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table table-hover" id="history_wd_btc">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>To</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php if(!empty($wd_history)) { ?>
                            <?php foreach($wd_history as $wd) { ?>
                                <tr>
                                    <td><?= $wd->wallet_amount ?></td>
                                    <td><?= str_replace('WITHDRAW_TO : ','',$wd->wallet_desc); ?></td>
                                    <td><?= $wd->wallet_date; ?></td>
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

    $("#badge").show();
    $('#breadcrumb').hide();

    var history_wd_btc = $("#history_wd_btc").DataTable();

    $("#btn_wd").on('click', function() {
        $('body').loading();
        var data = {
            'receiver': $("#receiver").val(),
            'amount': $("#amount").val(),
            'csrf_nx': $("[name='csrf_nx']").val()
        }
        $.ajax({
            url: env.site_url + 'walletbtc/withdraw',
            method: 'POST',
            type: 'application/json',
            data: data,
            success: function(res) {
                swal({
                    title: res.heading,
                    text: res.message,
                    type: res.type
                });
                refreshToken(res.csrf_data);
            },
            error: function(err) {
                var res = JSON.parse(err.responseText);
                
                swal({
                    title: res.heading,
                    text: res.message,
                    type: res.type
                });
                refreshToken(res.csrf_data);
            }
        }).always(function() {
            $('body').loading('stop');
        });

    });

    function refreshToken(token) {
        $("[name='csrf_nx']").val(token);
    }


})

</script>