<?php 
	$this->template->title->set('Bonus BTC');  
	
?>  
<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Username</th>
						<th>Bonus Type</th>
						<th>Amount</th>
						<th>Date</th>
						<th>BTC Address</th>
						<th>Option</th>
					</tr>
				</thead>
				<tbody>
					<?php 
                        $total = 0;
                        $kolom = 3;
                        $limit_per_page = 20; 
                        $offset         = 0;
                        if ( $this->input->get('page') ) {
                            $offset     = $this->input->get('page');
                        }
                        $no = $offset+1; 
 						$this->db->join('tb_users', 'bonus_userid = id', 'left');
                        $this->db->order_by('bonus_id', 'desc'); 
                        $this->db->where('bonus_name', 'Bonus Rollover');
    					$a = $this->db->get('tb_bonus', $limit_per_page, $offset); 
    					foreach ($a->result() as $var) {  
    						$this->db->where('id', $var->bonus_userid);
							$a = $this->db->get('tb_users')->row();
    				?>
					<tr>
						<?php
							$this->db->where('id', $var->bonus_userid);
							$a = $this->db->get('tb_users')->row();
							$userWallet = $this->walletmodel->get_wallet('BTC',$a->id);
						?>
						<td><?php echo $no++ ?></td> 
						<td><?php echo $var->username ?></td>
						<td><?php echo $var->bonus_name ?></td>
						<td><?php echo $var->bonus_amount ?></td>
						<td><?php echo $var->bonus_date ?></td>
						<td><?= ($userWallet) ? $userWallet->wallet_address : ''; ?></td>
						<td><?php  
							

							if ( $userWallet && $var->bonus_status !== 'transfer' ){ 
						 ?>
							<a href="javscript:" data-id="<?php echo $var->bonus_id ?>" class="btn btn-success send-btc">
								<i class="fa fa-paper-plane"></i>
							</a><br> 



						<?php }else{ ?>
							<span class="label label-success" style="font-size:1.2em;">Transfered</span>
						<?php } ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<?php  
    					$num_rows = $this->db->get('tb_bonus')->num_rows(); 
					?>
					<tr>
						<td colspan="6" class="text-right"><?php echo $this->paginationmodel->paginate( 'sendbonusbtc' , $num_rows, $limit_per_page ); ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('.send-btc').click(function(event) {
		id = $(this).data('id');
		$('body').loading();
		swal({
		  title: 'Are you sure?',
		  text: "You won't be able to revert this!",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, Send it!'
		}).then((result) => {
		  if (result.value) {
		    $.ajax({
		    	url: env.site_url + 'admin/postdata/send_btc',
		    	type: 'GET',
		    	dataType: 'json',
		    	data: {id: id},
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
		    
		  }
		})
		
	});
</script>