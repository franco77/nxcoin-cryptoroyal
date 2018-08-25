<?php 
	$this->template->title->set('Profile');
	$userdata = userdata();
	$my_username 		= $userdata->username;
	$link_referral 		= site_url( 'auth/register/refid/'.$my_username.'' );
?>

<div class="row">
	<div class="col-md-8">
		<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">My Referral<hr></h4>
		        <div class="table-responsive"> 
		        	<table class="table table-hover table-striped">
		        		<thead>
		        			<tr>
		        				<th>#</th>
		        				<th>Username</th>
		        				<th>Email</th>
		        				<th>Full Name</th>
		        				<th>Option</th>
		        			</tr>
		        		</thead>
		        		<tbody>
		        			<?php 
		        				$back_icon = '';
	        					if ( $this->input->get_post('networkView') ) { 
									$id_referral 	= userdata( array( 'user_code' => $this->input->get_post('networkView') ) );
									if ( $id_referral != false ) {
										
										$this->db->where('referral_id', $id_referral->id );
										$back_icon = '<a href="javascript:window.history.back();"><h6><i class="fa fa-chevron-left"> </i> Back</h6></a>';
									}
									else{

										$this->db->where('referral_id', userid() );

									}

								}else if (get('username')){ 
									$this->db->where('username', get('username') );
									$this->db->where('username != ', $userdata->username);
								}else {
									
									$this->db->where('referral_id', userid() );
								
								}
	        					$a = $this->db->get('tb_users');
	        					$no = 1;
	        					foreach ($a->result() as $var) {
		        			?>
		        			<tr>
		        				<td><?php echo $no++ ?></td>
		        				<td><?php echo $var->username ?></td>
		        				<td><?php echo $var->email ?></td>
		        				<td><?php echo $var->user_fullname ?></td>
		        				<td><?php echo '<a href="'. site_url('referral?networkView='.$var->user_code) .'" class="btn btn-success b-warn text-warn btn-sm">'.fa('users').'</a>'; ?></td>
		        			</tr>
		        		<?php } ?>
		        		</tbody>
		        		<tfoot> 
		        			<tr> 
		        				<td colspan="5" class="text-right"><?php echo $back_icon ?></td>
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
		        <h4 class="card-title">Affiliate Link<hr></h4>
		        <label>Click copy to share</label>
		        <div class="input-group mt-2">
					<input type="text"  value="<?php echo $link_referral ?>" class="form-control text-center disabled" disabled readonly> 
					<div class="input-group-btn">
						<button type="button" class="btn btn-success copy" data-id="<?php echo $link_referral ?>">COPY</button>
					</div>
				</div>
		    </div>
		</div>
	</div>
</div>