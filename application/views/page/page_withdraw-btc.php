
<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
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
</div>


<script>

$(document).ready(function() {


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