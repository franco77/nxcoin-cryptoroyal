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
						<th>Amount</th>
						<th>Package</th>
						<th>Date Start</th>
						<th>Date End</th>
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
 						$this->db->join('tb_package', 'stc_package = package_id', 'left');
                        $this->db->join('tb_users', 'stc_userid = id', 'left');
                        $this->db->order_by('stc_id', 'desc');  
    					$a = $this->db->get('tb_stacking', $limit_per_page, $offset); 
    					foreach ($a->result() as $var) {  
    				?>
					<tr>
						<td><?php echo $no++ ?></td>
						<td><?php echo $var->username ?></td> 
						<td><?php echo $var->stc_amount ?></td>
						<td><?php echo $var->package_name ?></td>
						<td><?php echo $var->stc_date_start ?></td>
						<td><?php echo $var->stc_date_end ?></td>
						<td>
							<a href="<?= site_url('admin/view/user-detail/').$var->stc_userid; ?>" class="btn btn-default">View Bonuses</button>
						</td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<?php 
						$this->db->order_by('stc_id', 'desc'); 
    					$num_rows = $this->db->get('tb_stacking')->num_rows(); 
					?>
					<tr>
						<td colspan="6" class="text-right"><?php echo $this->paginationmodel->paginate( 'userstacking' , $num_rows, $limit_per_page ); ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>