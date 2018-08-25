<?php $user_code = $this->input->get_post('user_code'); 
	
?>


<?php 

	$userdata 	= userdata( array('id' => $user_code) );
	if( $userdata == false ): 

		echo alerts( 'invalid user ID', 'danger' );

	else:
		$this->db->where(array('wallet_userid' => $userdata->id, 'wallet_type' => 'A'));
		$wallet = $this->db->get('tb_wallet')->row()->wallet_address;
		$this->db->where(array('wallet_userid' => $userdata->id, 'wallet_type' => 'B'));
		$wallet2 = $this->db->get('tb_wallet')->row()->wallet_address;
		$this->db->where(array('wallet_userid' => $userdata->id, 'wallet_type' => 'C'));
		$wallet3 = $this->db->get('tb_wallet')->row()->wallet_address;
?>
 
	
			<div class="card-body"> 
    			NXCC Wallet
				<div class="input-group mb-3">
                    <input type="text" id="textarea_banner" class="form-control" rows="3" required="required" readonly="" value="<?php echo $wallet ?>"class="form-control form-control-lg" aria-label="Username" aria-describedby="basic-addon1">
                    <div class="input-group-prepend">
                        <span class="input-group-text copy" style="cursor: pointer;" data-id="<?php echo $wallet ?>" id="basic-addon1"><i class="ti-layers"></i></span>
                    </div>
                </div>
                Bonus Entry Wallet
				<div class="input-group mb-3">
                    <input type="text" id="textarea_banner" class="form-control" rows="3" required="required" readonly="" value="<?php echo $wallet2 ?>"class="form-control form-control-lg" aria-label="Username" aria-describedby="basic-addon1">
                    <div class="input-group-prepend">
                        <span class="input-group-text copy" style="cursor: pointer;" data-id="<?php echo $wallet2 ?>" id="basic-addon1"><i class="ti-layers"></i></span>
                    </div>
                </div> 
			</div>
 

<?php endif; ?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".copy").click(function(event) {
          copyText = $(this).data('id'); 
          copyTextToClipboard(copyText); 
          swal({
            position: 'top-end',
            type: 'success',
            title: 'Copy to clipboard',
            showConfirmButton: false, //
            timer: 3000, //rgba(0,0,123,0.4)
            backdrop: ` rgba(0,0,0,0.8)`
            // url("https://media3.giphy.com/media/12NUbkX6p4xOO4/giphy.gif") 
            //   bottom
            //   no-repeat
          })
        });	
	});
</script>