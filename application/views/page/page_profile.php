<?php 
	$this->template->title->set('Profile');
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
<div class="row">
	<div class="col-md-12">
		<?php echo $this->session->flashdata('profile_flash'); ?>
		<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">Profile Picture And 2 Factor Authentication<hr></h4>
		        <p class="card-text">
		        	<div class="row">
		        		<div class="col-sm-5 text-center" style="border-right: 1px solid #ebebeb">
				        	<?php echo form_open_multipart('userpost/changeUserPicture'); ?> 
					        	<img width="200" src="<?php echo base_url('uploads/image/').''.$userdata->user_picture ?>" class="img-thumbnail img-responsive">
					        	<div class="input-group mt-2"> 
				                    <input type="file" class="" id="file_name" name="file_name"> 
									<div class="input-group-btn">
										<button type="submit" class="btn btn-success">UPLOAD</button>
									</div>
				                    <span class="desc">"JPG, GIF or PNG Max size 800Kb"</span> 
								</div>
								<p class="text-left"><a class="text-left" id="resetDefaultImg" href="#">Reset To Default ?</a></p>
							<?php echo form_close(); ?>
						</div> 
						<div class="col-sm-7">
							<div class="row">
				        		<div class="col-sm-4 text-center">
				        			<?php echo '<img src="'.$qrCodeUrl.'" class="img-responsive img-thumbnail" alt="Image">' ?>
				        			<?php if ( userdata()->gauth_status == 'off' ): ?>
				        				<div class="input-group mt-2">
											<input type="text"  value="<?php echo $secret ?>" class="form-control text-center disabled" disabled readonly> 
											<div class="input-group-btn">
												<button type="button" class="btn btn-success copy" data-id="<?php echo $secret ?>">COPY</button>
											</div>
										</div>
									<?php endif; ?>
				        		</div>
				        		<div class="col-sm-8">
				        			<?php if ( userdata()->gauth_status == 'off' ){ ?><br> 
									
									<div class="p-3">
										<?php echo form_open('', array('id'=> 'formAddAuth')) ?>
										<div class="input-group"> 
											<input name="oneCodeAuth" type="text" class="form-control" placeholder="One Code Auth">
											<input type="hidden" name="secret" value="<?php echo $secret ?>">
											<div class="input-group-btn">
												<button type="submit" id="submit_auth" class="btn btn-success">ADD AUTHENTICATOR</button>
											</div> 
										</div>
										<?php echo form_close() ?>
										<p><?php echo APP_NAME ?> gives you a second authentication factor when you're logging in to your account. In addition to your password, you enter a One Time Pin (OTP) that is unique for each login. These two factors give you stronger account security. The <?php echo APP_NAME ?> Security Key sends you a temporary security code via EMAIL that you enter in addition to your password when you log in to <?php echo APP_NAME ?>.</p>
									</div>

									<?php }else{ ?>
									<div class="p-3">

										<p><strong>Remove Two Factor Auth</strong></p>
										<?php echo form_open('', array('id' => 'formRemoveAuth') ); ?>

										<div class="input-group">
											<input name="oneCodeAuth" type="text" class="form-control" placeholder="One Code Auth">
											<div class="input-group-btn">
												<button type="submit" id="removeAuthBtn" class="btn btn-danger ">REMOVE AUTHENTICATOR</button>
											</div>
										</div>

										<?php echo form_close(); ?>

									</div>

									<?php } ?>
				        		</div>
				        	</div>
			        	</div>
		        	</div>
		        </p>
		    </div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="card">
			<div class="card-body collapse show">
		        <h4 class="card-title">Profile<hr></h4> 
	        	<div class="form-container"> 
	                <?php echo form_open('', array('id' => 'icon_validate') ); ?>

	                    <!-- <p>Ad consequat in commodo mollit do ullamco minim in reprehenderit anim pariatur.</p> -->
	                    <?php echo $this->session->flashdata('profile_flash'); ?>

	                    <div class="row">
	                        <div class="col-md-6">
	                            
	                            <div class="form-group">
	                                <label>Username:</label>
	                                <input type="text"  class="form-control" value="<?php echo $userdata->username ?>" readonly="" disabled="">
	                            </div>

	                            <div class="form-group">
	                                <label>Full name:</label>
	                                <input type="text" name="user_fullname"  class="form-control" value="<?php echo $userdata->user_fullname ?>" >
	                            </div>

	                            <div class="form-group">
	                                <label>E-Mail:</label>
	                                <input type="text" name="email"  class="form-control" value="<?php echo $userdata->email ?>" >
	                            </div> 

	                        </div>
	                        <div class="col-md-6">

	                            <div class="form-group">
	                                <label>Phone Number:</label>
	                                <input type="text" name="user_phone"  class="form-control" value="<?php echo $userdata->user_phone ?>" >
	                            </div> 

	                            <div class="form-group">
	                                <label>Address:</label>
	                                <input type="text" name="user_address"  class="form-control" value="<?php echo $userdata->user_address ?>" >
	                            </div>

	                            <div class="form-group">
	                                <label>Company:</label>
	                                <input type="text" name="user_company"  class="form-control" value="<?php echo $userdata->user_company ?>" >
	                            </div>  

	                            <div class="form-group">
		                            <label>BTC Wallet Address:</label>
		                            <input type="text" name="user_btc" class="form-control" value="<?php echo $userdata->user_btc ?>" >
		                        </div> 
	                        </div>
	                    </div>

	                    <!-- <div class="row">
	                        <div class="col-md-12">
	                            <div style="font-size: 16px; font-weight: 600;">Opsi Withdrawal:</div><hr>
	                        </div>

	                        <div class="form-group col-md-6">
	                            <label>BANK:</label>
	                            <input type="text" name="user_bank_name" class="form-control" value="<?php echo $userdata->user_bank_name ?>" >
	                        </div>  

	                        <div class="form-group col-md-6">
	                            <label>Bank Name:</label>
	                            <input type="text" name="user_bank_account" class="form-control" value="<?php echo $userdata->user_bank_account ?>" >
	                        </div>

	                        <div class="form-group col-md-6">
	                            <label>Bank Number:</label>
	                            <input type="number" name="blockchain_address" class="form-control" value="<?php echo $userdata->user_btc ?>" >
	                        </div>    

	                    </div> -->

	                    <div class="row">
	                            
	                        <div class="col-md-12">
	                            <div style="font-size: 16px; font-weight: 600;">Change Password:</div><hr>
	                        </div>
	                        
	                        <div class="form-group col-md-4">
	                            <label>Old Password:</label>
	                            <input type="password" name="old_password" class="form-control" />
	                        </div>

	                        <div class="form-group col-md-4">
	                            <label>New Password:</label>
	                            <input type="password" name="new_password" class="form-control" />
	                        </div>

	                        <div class="form-group col-md-4">
	                            <label>re-type Password:</label>
	                            <input type="password" name="confirm_password" class="form-control" />
	                        </div> 

	                    </div>

	                    <div class="row">
	                        <div class="col-md-12">
	                            <div class="pull-right">
	                                <button type="button" class="btn btn-primary btn-corner right15" id="btnSaveProfile"><i class="fa fa-check"></i> Save Profile</button>
	                            </div>
	                        </div>
	                    </div>


	                <?php echo form_close(); ?>

	                <script type="text/javascript">
	                    jQuery(document).ready(function($) {
	                        $('#btnSaveProfile').click(function(event) {

	                            $('body').loading();

	                            $.ajax({
	                                url: '<?php echo site_url('userpost/postdata/save_profile') ?>',
	                                type: 'POST',
	                                dataType: 'json',
	                                data: $('#icon_validate').serialize(),
	                            })
	                            .done(function( result ) {

	                                $('input[name=csrf_nx]').val( result.csrf_data );

	                                swal(
	                                  result.heading,
	                                  result.message,
	                                  result.type,
	                                ).then( function(){ 
	                                    if( result.status ){ 
	                                        window.location.href='<?php echo site_url('profile') ?>';
	                                    } 
	                                });
	                            })
	                            .always(function() {
	                                $('body').loading('stop');
	                            });
	                            

	                        });
	                    });
	                </script>

	            </div>
		    </div>
		</div> 
	</div>
</div>

<script type="text/javascript">
	$('#submit_auth').click(function(event) {
		event.preventDefault();
		$('body').loading();  
		$.ajax({
			url: 'userpost/postdata/two_factor_activation',
			type: 'post',
			dataType: 'json',
			data: $('#formAddAuth').serialize()
		})
		.done(function( result ) { 
			console.log(result);
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
	}); 
	$('#removeAuthBtn').click(function(event) {
		event.preventDefault();
		$('body').loading();  
		$.ajax({
			url: 'userpost/postdata/two_factor_remove',
			type: 'post',
			dataType: 'json',
			data: $('#formRemoveAuth').serialize()
		})
		.done(function( result ) { 
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
	}); 
	$('#resetDefaultImg').click(function(event) {
		event.preventDefault();
		$('body').loading();
		swal({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, change it!'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('userpost/changeUserPicture?pic=default') ?>',
					type: 'get',
					dataType: 'html',
					data: {param1: 'value1'},
				})
				.done(function() {
					window.location.href='<?php echo site_url('profile') ?>';
				}) 
				.always(function() {
					$('body').loading('stop');
				});
			}
		}) 
		
	});
</script>