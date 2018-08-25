<?php 
	$this->template->title->set('Rollover Bonus');
	$userdata = userdata(); 
?>
<script type="text/javascript">
	$('#breadcrumb').hide();
	$('#badge').show();
</script>

<div class="row">
    <div class="col-md-12">
    	<div class="card">
			<div class="card-body collapse show"> 
				<a href='javascript:' class='btn btn-primary btn-sm buy-ticket' >Buy 1 Ticket</a> 
		    </div>
		</div>
    </div> 
</div>   

<div class="row">
	<div class="col-md-4">
    	<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">Waiting List</h4>
		        <div class="table-responsive">
			        <table class="table table-hover table-striped">
			        	<thead>
			        		<tr>
			        			<th>#</th>
			        			<th>Username</th>
			        			<th>Ticket ID</th>
			        			<th>Date</th>
			        		</tr>
			        	</thead>
			        	<tbody>
			        	<?php 
			        		$total = 0;
	                        $kolom = 3;
	                        $limit_per_page = 10; 
	                        $offset         = 0;
	                        if ( ($this->input->get('page')) && $this->input->get('board') == '0' ) {
	                            $offset     = $this->input->get('page');
	                        }
	                        $no = $offset+1;
				        	$this->db->where('rollover_class', '0');
				        	$this->db->order_by('rollover_date', 'asc');
				        	$a = $this->db->get('tb_rollover', $limit_per_page, $offset); 
				        	foreach ($a->result() as $key) {
				        		$color = '';
				        		if ($key->rollover_userid == userid()){
				        			$color = 'background-color:#c9fdf5';
				        		}
				        ?>
			        		<tr style="<?php echo $color ?>">
			        			<td><?php echo $no++ ?></td>
			        			<td><?php echo userdata(array('id' => $key->rollover_userid))->username ?></td>
			        			<td><?php echo $key->rollover_txid ?></td>
			        			<td><?php echo $key->rollover_date ?></td>
			        		</tr>
			        	<?php } ?>
			        	</tbody> 
			        	<tfoot>
							<?php 
								$this->db->where('rollover_class', '0');   
		    					$num_rows = $this->db->get('tb_rollover')->num_rows(); 
							?>
							<tr>
								<td colspan="4" class="text-right"><?php echo $this->paginationmodel->paginate( 'bonus-rollover?board=0' , $num_rows, $limit_per_page ); ?></td>
							</tr>
						</tfoot>
			        </table>
			    </div>
		    </div>
		</div>
    </div>
    <div class="col-md-4">
    	<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">Rollover 1</h4>
		        <div class="table-responsive">
			        <table class="table table-hover table-striped">
			        	<thead>
			        		<tr>
			        			<th>#</th>
			        			<th>Username</th>
			        			<th>Ticket ID</th>
			        			<th>Date</th>
			        		</tr>
			        	</thead>
			        	<tbody>
			        	<?php 
			        		$total = 0;
	                        $kolom = 3;
	                        $limit_per_page = 10; 
	                        $offset         = 0;
	                        if ( ($this->input->get('page')) && $this->input->get('board') == '1' ) {
	                            $offset     = $this->input->get('page');
	                        }
	                        $no = $offset+1;
				        	$this->db->where('rollover_class', '1');
				        	$this->db->order_by('rollover_date', 'asc');
				        	$a = $this->db->get('tb_rollover', $limit_per_page, $offset); 
				        	foreach ($a->result() as $key) {
				        		$color = '';
				        		if ($key->rollover_userid == userid()){
				        			$color = 'background-color:#c9fdf5';
				        		}
				        ?>
			        		<trstyle="<?php echo $color ?>">
			        			<td><?php echo $no++ ?></td>
			        			<td><?php echo userdata(array('id' => $key->rollover_userid))->username ?></td>
			        			<td><?php echo $key->rollover_txid ?></td>
			        			<td><?php echo $key->rollover_date ?></td>
			        		</tr>
			        	<?php } ?>
			        	</tbody> 
			        	<tfoot>
							<?php 
								$this->db->where('rollover_class', '1');   
		    					$num_rows = $this->db->get('tb_rollover')->num_rows(); 
							?>
							<tr>
								<td colspan="4" class="text-right"><?php echo $this->paginationmodel->paginate( 'bonus-rollover?board=1' , $num_rows, $limit_per_page ); ?></td>
							</tr>
						</tfoot>
			        </table>
			    </div>
		    </div>
		</div>
    </div>
    <div class="col-md-4">
    	<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">Rollover 2</h4>
		        <div class="table-responsive">
			        <table class="table table-hover table-striped">
			        	<thead>
			        		<tr>
			        			<th>#</th>
			        			<th>Username</th>
			        			<th>Ticket ID</th>
			        			<th>Date</th>
			        		</tr>
			        	</thead>
			        	<tbody>
			        	<?php
			        		$total = 0;
	                        $kolom = 3;
	                        $limit_per_page = 10; 
	                        $offset         = 0;
	                        if ( ($this->input->get('page')) && $this->input->get('board') == '2' ) {
	                            $offset     = $this->input->get('page');
	                        }
	                        $no = $offset+1;

				        	$this->db->where('rollover_class', '2');
				        	$this->db->order_by('rollover_date', 'asc');
				        	$a = $this->db->get('tb_rollover', $limit_per_page, $offset); 
				        	foreach ($a->result() as $key) {
				        		$color = '';
				        		if ($key->rollover_userid == userid()){
				        			$color = 'background-color:#c9fdf5';
				        		}
				        ?>
			        		<tr style="<?php echo $color ?>">
			        			<td><?php echo $no++ ?></td>
			        			<td><?php echo userdata(array('id' => $key->rollover_userid))->username ?></td>
			        			<td><?php echo $key->rollover_txid ?></td>
			        			<td><?php echo $key->rollover_date ?></td>
			        		</tr>
			        	<?php } ?>
			        	</tbody> 
			        	<tfoot>
							<?php 
								$this->db->where('rollover_class', '2');   
		    					$num_rows = $this->db->get('tb_rollover')->num_rows(); 
							?>
							<tr>
								<td colspan="4" class="text-right"><?php echo $this->paginationmodel->paginate( 'bonus-rollover?board=2' , $num_rows, $limit_per_page ); ?></td>
							</tr>
						</tfoot>
			        </table>
			    </div>
		    </div>
		</div>
    </div> 
</div> 

<div class="row">
  	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
  		<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">Ticket History</h4>
		        <div class="table-responsive">
			        <table class="table table-hover table-striped">
			        	<thead>
			        		<tr>
			        			<th>#</th> 
			        			<th>Name</th>
			        			<th>Ticket ID</th>
			        			<th>Date</th>
			        		</tr>
			        	</thead>
			        	<tbody>
			        	<?php 
			        		$total = 0;
	                        $kolom = 3;
	                        $limit_per_page = 10; 
	                        $offset         = 0;
	                        if ( ($this->input->get('page')) && $this->input->get('board') == '4' ) {
	                            $offset     = $this->input->get('page');
	                        }
	                        $no = $offset+1;
				        	$this->db->where('rollover_userid', userid()); 
				        	$this->db->order_by('rollover_date', 'desc');
				        	$a = $this->db->get('tb_rollover_history', $limit_per_page, $offset); 
				        	foreach ($a->result() as $key) { 
				        ?>
			        		<tr>
			        			<td><?php echo $no++ ?></td> 
			        			<td><?php echo $key->rollover_class ?></td>
			        			<td><?php echo $key->rollover_txid ?></td>
			        			<td><?php echo $key->rollover_date ?></td>
			        		</tr>
			        	<?php } ?>
			        	</tbody> 
			        	<tfoot>
							<?php 
				        		$this->db->where('rollover_userid', userid());  
		    					$num_rows = $this->db->get('tb_rollover_history')->num_rows(); 
							?>
							<tr>
								<td colspan="4" class="text-right"><?php echo $this->paginationmodel->paginate( 'bonus-rollover?board=4' , $num_rows, $limit_per_page ); ?></td>
							</tr>
						</tfoot>
			        </table>
			    </div>
		    </div>
		</div>
	</div>
</div>  

<input type="hidden" name="csrf_nx" value="<?php echo $this->security->get_csrf_hash() ?>">
<script type="text/javascript">
	
	$('.buy-ticket').click(function(event) {
		$('body').loading();
		var csrf_nx 	= $('input[name=csrf_nx]').val();
		swal({
			title: 'Are you sure?',
			text: "1 ticket = $12",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, buy once!'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('userpost/postdata/buy_by_ticket') ?>',
					type: 'post',
					dataType: 'json',
					data: {csrf_nx: csrf_nx},
				})
				.done(function(e) {
					$('input[name=csrf_nx]').val( e.csrf_data );
					swal(
					  	e.heading,	
					  	e.message,	
					  	e.type,		 
					).then( function(){ 
						if( e.status ){
							window.location.reload();
						} 
					});
				}) 
				.always(function() {
					$('body').loading('stop');
				});
				
			}else{
				$('body').loading('stop');
			}
		})
	});
</script>
