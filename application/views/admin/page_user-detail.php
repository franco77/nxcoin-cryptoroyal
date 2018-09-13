<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
            <form action="<?= site_url('adminarea/bonuses/force_pasive_bonus'); ?>" method="POST" role="form">
                
                <?= csrf_field(); ?>

                <div class="form-group">
                    
                    <input name="userid" type="hidden" value="<?= $this->uri->segment(4) ?>" class="form-control" id="" placeholder="Input field">
                </div>
            
                
            
                <button type="submit" class="btn btn-primary">Force Pasive Bonus</button>
            </form>
            </div>
        </div>
        
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4>Bonus History</h4>
                <br>
                
                <form class="form-inline">
                
                    <div class="form-group">
                        <label for="bonus_history_type">Bonus Type
                            <select id="bonus_history_type" name="" class="form-control">
                                <option value="">-- Select One --</option>
                                <option value="pasive">Pasif</option>
                                <option value="active">Active</option>
                                <option value="btc">BTC</option>
                                <option value="rollover">Rollover</option>
                            </select>
                        </label>
                    </div>
                
                    
                
                    
                </form>
                
                
            </div>
            <div class="card-body">

                
                <table id="bonus_history" class="table table-hover">
                    
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    
                    <tbody></tbody>

                    <tfoot>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Type</th>
                        </tr>
                    </tfoot>
                </table>
                

            </div>
        </div>
    </div>
</div>


<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<script>
var bonus_userid = "<?= $this->uri->segment(4); ?>";

$(document).ready(function() {
    
    var bonus_history = $("#bonus_history").DataTable({
        
        "ajax": env.site_url + 'admin/user-bonuses/' + bonus_userid,
        "columns": [
            { "data": "bonus_id" },
            { "data": "bonus_name" },
            { "data": "bonus_amount" },
            { "data": "bonus_date" },
            { "data": "bonus_type" },
        ],
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    });

    var bonus_type = $("#bonus_history_type");
    bonus_type.on('change', function() {
        var type = $(this).val();
        
    });


});

</script>