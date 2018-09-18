<?php 
	$this->template->title->set('Transfer');
	$userdata = userdata();
	echo $this->load->view('library/badge', '', TRUE);
	$this->db->where(array('wallet_userid' => userid(), 'wallet_type' => 'A'));
	$wallet = $this->db->get('tb_wallet')->row()->wallet_address;
?>
<script type="text/javascript">
	$('#breadcrumb').hide();
</script>
<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">Transfer To NXCC Wallet<hr></h4>
		        <?php echo form_open('', array('id' => 'trfWalletA')); ?>
		        	<div class="form-group">
		        		<label for="walletA">Wallet Address</label>
		        		<input type="text" name="address" id="address" class="form-control">		        		
		        		<input type="text" name="address2" class="form-control" value="<?php echo $wallet ?>">		        		
		        	</div>
		        	<div class="form-group">
		        		<label for="amount">Amount Transfer</label>
		        		<input type="text" name="amount" class="form-control" id="amountA">
		        		<input type="hidden" name="trfMode" value="A">		        		
		        	</div>
		        	<?php if ($userdata->gauth_status == 'on'){ ?>
		        		<div class="form-group">
		        			<label for="2fa">One Code Auth</label>
		        			<input type="text" name="oneCode" class="form-control" id="2fa">
		        		</div>
		        	<?php } ?><input type="hidden" name="X-API-KEY" value="x6Hbju8i7HkhsiYjua2hj">
		        	<button class="btn btn-success" id="buttonA" type="submit">SEND</button>
		        <?php echo form_close(); ?>
		    </div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">History Transfer To NXCC Wallet</h4>
				<div class="table-responsive">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Amount</th>
								<th>Desc</th>
							</tr>
						</thead>
						<tbody>
							<?php 
		                        $total = 0;
		                        $kolom = 3;
		                        $limit_per_page = 5; 
		                        $offset         = 0;
		                        if ( ($this->input->get('page')) && $this->input->get('tab') == 'A' ) {
		                            $offset     = $this->input->get('page');
		                        }
		                        $no = $offset+1; 

		                        $this->db->where('wallet_userid', userid()); 
		                        $this->db->order_by('wallet_date', 'desc');
		    					$this->db->where('wallet_type', 'A'); 
		    					$a = $this->db->get('tb_wallet', $limit_per_page, $offset); 
		    					foreach ($a->result() as $var) {  
		    				?>
							<tr>
								<td><?php echo $no++ ?></td>
								<td><?php echo $var->wallet_date ?></td>
								<td><?php echo $var->wallet_amount ?></td>
								<td><?php echo $var->wallet_desc ?></td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<?php 
								$this->db->where('wallet_userid', userid()); 
		                        $this->db->order_by('wallet_date', 'desc');
		    					$this->db->where('wallet_type', 'A'); 
		    					$num_rows = $this->db->get('tb_wallet')->num_rows(); 
							?>
							<tr>
								<td colspan="4" class="text-right"><?php echo $this->paginationmodel->paginate( 'transfer?tab=A' , $num_rows, $limit_per_page ); ?></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">Transfer To Bonus Entry Wallet<hr></h4>
		        <?php echo form_open('', array('id' => 'trfWalletB')); ?>
		        	<div class="form-group">
		        		<label for="walletA">Wallet Address</label>
		        		<input type="text" name="address" class="form-control" id="walletA">		        		
		        	</div>
		        	<div class="form-group">
		        		<label for="amount">Amount Transfer</label>
		        		<input type="text" name="amount" class="form-control" id="amountA">	
		        		<input type="hidden" name="trfMode" value="B">	        		
		        	</div>
		        	<?php if ($userdata->gauth_status == 'on'){ ?>
		        		<div class="form-group">
		        			<label for="2fa">One Code Auth</label>
		        			<input type="text" name="oneCode" class="form-control" id="2fa">
		        		</div>
		        	<?php } ?>
		        	<button class="btn btn-success" id="buttonB" type="submit">SEND</button>
		        <?php echo form_close(); ?>
		    </div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">History Transfer To Bonus Entry Wallet</h4>
				<div class="table-responsive">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Amount</th>
								<th>Desc</th>
							</tr>
						</thead>
						<tbody>
							<?php 
		                        $total = 0;
		                        $kolom = 3;
		                        $limit_per_pages = 5; 
		                        $offset         = 0;
		                        if ( ($this->input->get('page')) && $this->input->get('tab') == 'B' ) {
		                            $offset     = $this->input->get('page');
		                        }
		                        $no = $offset+1; 

		                        $this->db->where('wallet_userid', userid()); 
		                        $this->db->order_by('wallet_date', 'desc');
		    					$this->db->where('wallet_type', 'B'); 
		    					$a = $this->db->get('tb_wallet', $limit_per_pages, $offset); 
		    					foreach ($a->result() as $var) {  
		    				?>
							<tr>
								<td><?php echo $no++ ?></td>
								<td><?php echo $var->wallet_date ?></td>
								<td><?php echo $var->wallet_amount ?></td>
								<td><?php echo $var->wallet_desc ?></td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<?php 
								$this->db->where('wallet_userid', userid()); 
		                        $this->db->order_by('wallet_date', 'desc');
		    					$this->db->where('wallet_type', 'B'); 
		    					$num_rowss = $this->db->get('tb_wallet')->num_rows(); 
							?>
							<tr>
								<td colspan="4" class="text-right"><?php echo $this->paginationmodel->paginate( 'transfer?tab=B' , $num_rowss, $limit_per_pages ); ?></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	$('#buttonA').click(function(event) {
        /* Act on the event */
        // $('body').LoadingOverlay('show');
        event.preventDefault();
		$('body').loading();
		var csrf_data 	= $('input[name=csrf_nx]').val();
		
            if ( ($('#address').val().length == 35) ){
                url = 'https://api.nxcoin.io/api/example/sendwallet?X-API-KEY=x6Hbju8i7HkhsiYjua2hj';
                meth= 'post';
            }else{ 
                url = 'userpost/postdata/sendWallet';
                meth= 'get';
            }   

        $.ajax({
            url: 'userpost/postdata/cek_data_before_send',
            type: 'post',
            dataType: 'json',
            data: $('#trfWalletA').serialize(), 
        })
        .done(function( result ) {
            if (result.status){
                $.ajax({
    	            url: url,
    	            type: meth,
    	            dataType: 'json',
    	            data: $('#trfWalletA').serialize(),  
    	        })
    	        .done(function( result ) { 
    	        	// validate apakah data sudah sesuai
    	        	if (result.status){
	    	        	$.ajax({
	    	        		url: 'userpost/postdata/success_send',
	    	        		type: 'get',
	    	        		dataType: 'json',
	    	        		data: $('#trfWalletA').serialize(),
	    	        	})
	    	        	.done(function(result) {
							$('input[name=csrf_nx]').val( result.csrf_data );

		    	            swal({
		    	                title: result.heading,
		    	                text: result.message,
		    	                type: result.type
		    	            }).then((value) =>{
		    	                if( result.status ){
		    	                    window.location.href = "<?=base_url('transfer');?>";
		    	                }
		    	            }) 
	    	        	})
	    	        	.fail(function() {
	    	        		console.log("error");
	    	        	})
	    	        	.always(function() {
	    	        		console.log("complete");
	    	        	});
	    	        }else{
	    	        	$('input[name=csrf_nx]').val( result.csrf_data );
	    	        	console.log(result);
	    	            swal({
	    	                title: result.heading,
	    	                text: result.message,
	    	                type: result.type
	    	            }) 
	    	        }
    	        	
    	        	
    	        })
    	        .fail(function(result) {
    	            $('input[name=csrf_nx]').val( result.csrf_data );
	        		console.log(result);
    	            swal({
    	                title: result.heading,
    	                text: result.message,
    	                type: result.type
    	            }).then((value) =>{
    	                if( result.status ){
    	                    window.location.href = "<?=base_url('transfer');?>";
    	                } 
    	        	})	
    	        }).always(function() {
		            $('body').loading('stop'); 
		        });
            }else{
            	$('input[name=csrf_nx]').val( result.csrf_data );
    
	            swal({
	                title: result.heading,
	                text: result.message,
	                type: result.type
	            }) 
	            $('body').loading('stop');
            }

        }).fail (function ( result){
        	$('input[name=csrf_nx]').val( result.csrf_data ); 
            swal({
                title: result.heading,
                text: result.message,
                type: result.type
            })
        })
        
    });


	$('#buttonB').click(function(event) {
		event.preventDefault();
		$('body').loading();
		$.ajax({
			url: '<?php echo base_url('userpost/postdata/sendWallet') ?>',
			type: 'post',
			dataType: 'json',
			data: $('#trfWalletB').serialize(),
		})
		.done(function(result) {
			$('input[name=csrf_nx]').val( result.csrf_data );
			swal(
              result.heading,
              result.message,
              result.type,
            ).then( function(){ 
                if( result.status ){ 
                    window.location.reload();
                } 
            });
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			$('body').loading('stop');
		});
		
	});
</script>