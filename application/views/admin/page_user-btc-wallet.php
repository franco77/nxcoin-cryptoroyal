
<table class="table table-hover">
    <thead>
        <tr>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
        </tr>
    </tbody>
</table>

<script>

$(document).ready(function() {

    var url = env.site_url+'wallet/listing';

    $.ajax({
        url: url,
        method: 'GET',
        success: function(res) {
            console.log(res)
        },
        error: function(err) {
            console.log(err)
        }
    }).done(function(res) {
        console.log(res);
    });


});

</script>
