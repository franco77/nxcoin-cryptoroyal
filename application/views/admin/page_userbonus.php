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
						<th>Bonus Type</th>
						<th>Amount</th>
						<th>Desc</th>
						<th>Bonus Date</th>
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
    					$a = $this->db->get('tb_bonus', $limit_per_page, $offset); 
    					foreach ($a->result() as $var) {  
    				?>
					<tr>
						<td><?php echo $no++ ?></td> 
						<td><?php echo $var->username ?></td>
						<td><?php echo $var->bonus_name ?></td>
						<td><?php echo $var->bonus_amount ?></td>
						<td><?php echo $var->bonus_desc ?></td>
						<td><?php echo $var->bonus_date ?></td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<?php  
    					$num_rows = $this->db->get('tb_bonus')->num_rows(); 
					?>
					<tr>
						<td colspan="4" class="text-right"><?php echo $this->paginationmodel->paginate( 'userbonus' , $num_rows, $limit_per_page ); ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>