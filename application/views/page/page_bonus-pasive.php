<?php 
	$this->template->title->set('Pasive Bonus');
?>

<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Date</th>
						<th>Amount</th>
						<th>Desc</th>
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

                        $this->db->where('bonus_userid', userid()); 
                        $this->db->order_by('bonus_id', 'desc');
    					$this->db->where('bonus_type', 'pasive'); 
    					$a = $this->db->get('tb_bonus', $limit_per_page, $offset); 
    					foreach ($a->result() as $var) {  
    				?>
					<tr>
						<td><?php echo $no++ ?></td>
						<td><?php echo $var->bonus_name ?></td>
						<td><?php echo $var->bonus_date ?></td>
						<td><?php echo $var->bonus_amount ?></td>
						<td><?php echo $var->bonus_desc ?></td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<?php 
						$this->db->where('bonus_userid', userid()); 
                        $this->db->order_by('bonus_id', 'desc');
    					$this->db->where('bonus_type', 'pasive'); 
    					$num_rows = $this->db->get('tb_bonus')->num_rows(); 
					?>
					<tr>
						<td colspan="4" class="text-right"><?php echo $this->paginationmodel->paginate( 'bonus-pasive' , $num_rows, $limit_per_page ); ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>