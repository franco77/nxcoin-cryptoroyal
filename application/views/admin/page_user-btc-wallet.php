<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

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
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>

$(document).ready(function() {

    var url = env.site_url+'wallet/listing';
    $("#btc_wallet_list").DataTable({
        "ajax": url,
        "columns": [
            {"data": 'label'},
            {"data": 'address'},
            {"data": 'balance'},
            {"data": 'total_received'}
        ]
    });
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
