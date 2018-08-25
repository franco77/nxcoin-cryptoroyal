<?php 
	$this->template->title->set('Staking');
	$userdata = userdata();
?>
<script type="text/javascript">
	$('#breadcrumb').hide();
	$('#badge').show();
</script>

<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">New Staking<hr></h4>
		        <?php echo form_open('', array('id' => 'stacking_form')); ?>
		        <div class="alert alert-success" id="alert" style="display: none"> 
		        	<i class="ti-user"></i> Amount to much to take over form wallet B, only maximum 10% 
				</div>
		        <div class="form-group">
		        	<label for="Amount">Amount</label>
		        	<input type="Text" name="amount" id="Amount" autocomplete="off" placeholder="Amount To Staking" class="form-control">
		        </div>
		        <div class="form-group"> 
		        	<input type="checkbox" name="use10" id="use10" class=""> 
		        	<label for="use10">Use Bonus Entry Staking Balance</label> <br>
		        	*Maksimum only 10% 
		        </div> 
		        <?php if ($userdata->gauth_status == 'on'){?>
		        	<div class="form-group">
			        	<label for="onecode">2FA</label>
			        	<input type="Text" name="oneCode" placeholder="One Code Auth" id="onecode" class="form-control">
			        </div> 
		        <?php } ?>
		        <button class="btn btn-primary" type="submit" name="smbButton" id="smbButton">START STAKING</button>
	        </div>
	    </div>
	    <script type="text/javascript">
	    	$('#Amount').keyup(function(event) {
	    		$("#use10").prop("checked", false);
	    		$('#alert').hide();
	    	});
	    	$('#use10').click(function(event) { 
	    		if ($('#Amount').val() > 0){
	    			$.ajax({
	    				url: '<?php echo base_url("userpost/postdata/cek_for_stacking") ?>',
	    				type: 'GET',
	    				dataType: 'json',
	    				data: {amount: $('#Amount').val()},
	    			})
	    			.done(function(data) { 
	    				if (data){
	    					$('#alert').hide();  
	    				}else{
	    					$('#alert').show(); 
	    				}
	    			}) 
    			}
	    	});
	    	
	    	$('#smbButton').click(function(event) {
	    		event.preventDefault();
	    		$('body').loading();
	    		swal({
					title: 'Are you sure?',
					text: "You won't be able to revert this!",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, start it!'
				}).then((result) => {
					if (result.value) {
						$.ajax({
							url: '<?php echo base_url("userpost/postdata/startStacking") ?>',
							type: 'post',
							dataType: 'json',
							data: $('#stacking_form').serialize(),
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
						.always(function() {
	    					$('body').loading('stop');
						});
						
					}
				})
	    	});

	    </script>
	    <div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">My Staking</h4>
		        <div class="table-responsive">
		        	<table class="table table-hover table-striped">
		        		<thead>
		        			<tr>
		        				<th>#</th> 
		        				<th>Amount Staking</th>
		        				<th>Date Start</th> 
		        				<th>Date End</th>  
		        			</tr>
		        		</thead>
		        		<tbody>
		        			<?php 
		        				$no = 1;
		        				$this->db->where('stc_userid', userid());
		        				$variable = $this->db->get('tb_stacking'); 
		        				foreach ($variable->result() as $key) {?>
		        				<tr>
		        					<td><?php echo $no++ ?></td>
		        					<td><?php echo $key->stc_amount ?></td>
		        					<td><?php echo $key->stc_date_start ?></td>
		        					<td><?php echo $key->stc_date_end ?></td>
		        				</tr>	
		        				<?php } ?>
		        		</tbody>
		        	</table>
		        </div>
		    </div>
	    </div>

	</div>
	<div class="col-md-6">
		<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">Staking Package<hr></h4>
		        All Package Valid For 24 Week and Profit Runs every 7 Days.
		        <div class="table-responsive">
		        	<table class="table table-hover table-striped">
		        		<thead>
		        			<tr>
		        				<th>#</th> 
		        				<th>Coin Amount</th>   
		        				<th>Profit</th>
		        			</tr>
		        		</thead>
		        		<tbody>
		        			<tr>
			        			<td>1</td>
			        			<td>50 - 200</td>
			        			<td>4.4%</td>
			        		</tr>
			        		<tr>
			        			<td>2</td>
			        			<td>201 - 5.000</td>
			        			<td>5.0%</td>
			        		</tr>
			        		<tr>
			        			<td>3</td>
			        			<td>5.001 - 10.000</td>
			        			<td>5.5%</td>
			        		</tr>
			        		<tr>
			        			<td>4</td>
			        			<td>10.001 - 20.000</td>
			        			<td>6.0%</td>
			        		</tr>
			        		<tr>
			        			<td>5</td>
			        			<td> > 20.001</td>
			        			<td>6.5%</td>
			        		</tr>
		        		</tbody>
		        	</table>
		        </div>
	        </div>
	    </div>
	</div>
</div>