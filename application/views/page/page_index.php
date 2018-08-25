<?php 
	$this->template->title->set('Beranda');
	$this->db->where(array('wallet_userid' => userid(), 'wallet_type' => 'A'));
	$wallet = $this->db->get('tb_wallet')->row()->wallet_address;
	$this->db->where(array('wallet_userid' => userid(), 'wallet_type' => 'B'));
	$wallet2 = $this->db->get('tb_wallet')->row()->wallet_address;
	$this->db->where(array('wallet_userid' => userid(), 'wallet_type' => 'C'));
	$wallet3 = $this->db->get('tb_wallet')->row()->wallet_address;

	$userdata = userdata();
	if ( $userdata->gauth_status == 'off' ):
	    // generates the secret code
	    $secret = $this->googleauthenticator->createSecret();
	    $icon = 'fa-times text-danger';
	else:
	    $icon = 'fa-check text-success';
	    $secret     = $userdata->gauth_secret;

	endif;
	$qrCodeUrl = $this->googleauthenticator->getQRCodeGoogleUrl( APP_NAME, $userdata->username, $secret); 
?> 
<script type="text/javascript">
	$('#badge').show();
	$('#breadcrumb').hide();
</script>

<div class="row">
    <div class="col-md-3">
    	<div class="card">
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
		</div>
	</div> 
    <div class="col-md-9">
    	<div class="card">
    		<div class="card-body"> 
    			<h4>Profile Summary</h4> 
				<?php echo $this->session->flashdata('profile_flash'); ?> 
	        	<div class="row">
	        		<div class="col-sm-3 text-center" style="border-right: 1px solid #ebebeb">
			        	 
			        	<img width="100" src="<?php echo base_url('uploads/image/').''.$userdata->user_picture ?>" class="img-thumbnail img-responsive">
				        	 
					</div> 
					<div class="col-sm-9"> 
						<div class="form-group">
	                                <label>Username:</label>
	                                <input type="text"  class="form-control" value="<?php echo $userdata->username ?>" readonly="" disabled="">
	                            </div>

	                            <div class="form-group">
	                                <label>Full name:</label>
	                                <input type="text" name="user_fullname"  class="form-control"  readonly="" disabled="" value="<?php echo $userdata->user_fullname ?>" >
	                            </div>

	                            <div class="form-group">
	                                <label>E-Mail:</label>
	                                <input type="text" name="email"  class="form-control"  readonly="" disabled="" value="<?php echo $userdata->email ?>" >
	                            </div> 
	        			<?php 

	        			echo ( userdata()->gauth_status == 'off' )? '<i class="ti ti-close"></i> Gauth Status is Off' : '<i class="ti ti-check"></i> Gauth Status is On';  
	        			?>
	        			<hr>
	        			<p class="text-right">
	        				<a class="btn btn-success" href="<?php echo base_url('profile') ?>">Update My Profile</a>
	        			</p>
		        	</div> 
	    		</div>
	    	</div>
    	</div>
    </div>
</div>