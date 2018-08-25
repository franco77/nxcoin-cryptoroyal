<h5 class="font-medium m-b-20">Reset Two Factor Authentication</h5>
</div>  
<div class="row">
    <div class="col-12">
	<?php echo form_open('', array('class' => 'login-form') ); ?>

	<?php echo $this->session->flashdata('auth_flash'); ?>
	
		<div class="form-group has-feedback">
			<input name="username" class="form-control" type="text" placeholder="Your Username" required> <span class="glyphicon glyphicon-user form-control-feedback " aria-hidden="true"></span> 
		</div>

		<div class="form-group has-feedback">
			<input name="email" class="form-control" type="email" placeholder="Email Account" required> <span class="glyphicon glyphicon-envelope form-control-feedback " aria-hidden="true"></span> 
		</div>

		<div class="form-group has-feedback">
			<input name="phone" class="form-control" type="text" placeholder="Phone Number" required> <span class="glyphicon glyphicon-lock form-control-feedback " aria-hidden="true"></span> 
		</div>

		<div class="mrgn-b-lg">
			<button name="doReset2FA" value="true" type="submit" class="btn btn-lg btn-warning btn-block font-2x">Verify to Remove</button>
		</div>
		<div class="text-center">
			<h5 class="base-dark">I'm ready to login ? <a href="<?php echo site_url() ?>" style="color:#ff0000">Go to dashboard</a></h5>
		</div> 
	
	<?php echo form_close(); ?> 
