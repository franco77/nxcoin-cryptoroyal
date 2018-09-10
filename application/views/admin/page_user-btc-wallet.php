<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                
                <button id="btn_refresh_wallet" type="button" class="btn btn-default">Refresh</button>
                <table class="table table-hover" id="btc_wallet_list">
                    <thead>
                        <tr>
                            <th>Label / Username</th>
                            <th>Address</th>
                            <th>Balance</th>
                            <th>Total Received</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>

$(document).ready(function() {

    var url = env.site_url+'wallet/listing';
    var btc_wallet_list = $("#btc_wallet_list").DataTable({
        "ajax": url,
        "columns": [
            {"data": 'label'},
            {"data": 'address'},
            {"data": 'balance'},
            {"data": 'total_received'}
        ]
    });
    $("#btn_refresh_wallet").on('click', function() {
        btc_wallet_list.ajax.reload();
    })

    // $.ajax({
    //     url: url,
    //     method: 'GET',
    //     success: function(res) {
    //         console.log(res)
    //     },
    //     error: function(err) {
    //         console.log(err)
    //     }
    // }).done(function(res) {
    //     console.log(res);
    // });


});

</script>
