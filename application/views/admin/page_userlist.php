<?php 
	$this->template->title->set('Userlist');

?>  
<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Username</th>
						<th>Email</th>
						<th>Full Name</th>
						<th>Referral</th>
						<th>Opsi</th>
					</tr>
				</thead>
				<tbody>
					<?php 
                        $total = 0;
                        $kolom = 3;
                        $limit_per_page = 10; 
                        $offset         = 0;
                        if ( $this->input->get('page') ) {
                            $offset     = $this->input->get('page');
                        }
                        $no = $offset+1; 
 
                        $this->db->order_by('id', 'desc'); 
    					$a = $this->db->get('tb_users', $limit_per_page, $offset); 
    					foreach ($a->result() as $var) {  
    				?>
					<tr>
						<td><?php echo $no++ ?></td>
						<td><?php echo $var->username ?></td>
						<td><?php echo $var->email ?></td>
						<td><?php echo $var->user_fullname ?></td>
						<td><?php echo ($var->referral_id != 0)? userdata(array('id' => $var->referral_id))->username : 'Gusti Pangeran' ?></td>
						<td>
							<?php  
								$rollover = ($var->rollover == 0)? 'btn-danger' : 'btn-success';  
								$text = ($var->rollover == 0)? 'Only get 90% pasif bonus' : 'Get 100% pasif bonus'; 
							?>
							<a class="btn <?php echo $rollover; ?> bullseye" data-id="<?php echo $var->id ?>" style="cursor: pointer" title="<?php echo $text ?>"><i class="fa fa-bullseye"></i></a>
							<a class="btn btn-warning" style="cursor: pointer" title="Change Password" data-remote="false" data-toggle="modal" data-target="#myModal" data-href="<?php echo site_url('admin/modalajax/change-password?user_code='.$var->id) ?>" data-title="Change Password" ><i class="ti ti-key"></i></a> 
							<a class="btn btn-success" style="cursor: pointer" title="View Wallet" data-remote="false" data-toggle="modal" data-target="#myModal" data-href="<?php echo site_url('admin/modalajax/view_wallet?user_code='.$var->id) ?>" data-title="View Wallet" ><i class="ti ti-wallet"></i></a>

						</td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<?php 
						$this->db->order_by('id', 'desc');  
    					$num_rows = $this->db->get('tb_users')->num_rows(); 
					?>
					<tr>
						<td colspan="6" class="text-right"><?php echo $this->paginationmodel->paginate( 'userlist' , $num_rows, $limit_per_page ); ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('.bullseye').click(function(event) {
		$('body').loading();
		id = $(this).data('id');
		$.ajax({
			url: '<?php echo site_url('admin/postdata/bullseye') ?>',
			type: 'GET',
			dataType: 'json',
			data: {id: id},
		})
		.done(function(result) {
			swal({
                title: result.heading,
                html: result.message,
                type: result.type,
            }).then( function(){ 
                if( result.status ){
                    location.reload();
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
 