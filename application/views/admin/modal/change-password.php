<?php $user_code = $this->input->get_post('user_code'); ?>


<?php 

	$userdata 	= userdata( array('id' => $user_code) );
	if( $userdata == false ): 

		echo alerts( 'invalid user ID', 'danger' );

	else:

?>

<?php echo form_open('', array('id' 	=> 'change_password_form'), array( 'id' => $userdata->id ) ); ?>
	
	<fieldset class="form-group">
		<label for="exampleInputEmail1">New Password</label>
		<input type="text" name="new_password" class="form-control" placeholder="">
	</fieldset>

	<button type="submit" class="btn btn-primary">Submit</button>

<?php echo form_close(); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#change_password_form').submit(function(event) {
			
			$('body').loading();

			$.ajax({
				url: '<?php echo site_url('admin/postdata/change_password') ?>',
				type: 'post',
				dataType: 'json',
				data: $('#change_password_form').serialize(),
			})
			.done(function( result ) {
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
			

			event.preventDefault();
		});
	});
</script>


<?php endif; ?>