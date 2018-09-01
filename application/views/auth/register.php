<h5 class="font-medium m-b-20">Register</h5>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
                            <?php echo form_open('', array('class' => 'form-horizontal m-t-20', 'id' => 'login_form' )); ?>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" name="user_fullname" class="form-control form-control-lg" placeholder="Full Name" aria-label="Username" aria-describedby="basic-addon1">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="ti-envelope"></i></span>
                                    </div>
                                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" aria-label="Username" aria-describedby="basic-addon1">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" name="user_referral" value="<?php echo $username_referral ?>" class="form-control form-control-lg" placeholder="Referral Username" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                                
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="ti-key"></i></span>
                                    </div>
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="ti-key"></i></span>
                                    </div>
                                    <input type="password" name="confirm_password" class="form-control form-control-lg" placeholder="Confirm Password" aria-label="Password" aria-describedby="basic-addon1">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="check"> Accept <a href="http://cryptoroyal.co/assets/Term_And_Conditions.pdf" target="_blank">Term and Conditions</a>
                                        </label>
                                    </div>

                                </div>
 
                                <div class="form-group text-center">
                                    <div class="col-xs-12 p-b-20">
                                        <button class="btn btn-block btn-lg btn-info" name="do_login" type="submit">Register</button>
                                    </div>
                                </div>
                                <div class="form-group m-b-0 m-t-10">
                                    <div class="col-sm-12 text-center">
                                        Have an account? <a href="<?php echo base_url() ?>" class="text-info m-l-5"><b>Sign In</b></a>
                                    </div>
                                </div>
                                <div class="form-group m-b-0 m-t-10">
                                    <div class="col-sm-12 text-center">
                                        - <a href="https://cryptoroyal.co/" class="text-info m-l-5"><b>Back To Homepage</b></a> - 
                                    </div>
                                </div>
                            <?php echo form_close(); ?> 

        <script type="text/javascript">
    
    // magic.js
    $(document).ready(function() {

        // process the form
        $('#login_form').submit(function(event) {
            event.preventDefault();

            $('body').loading();

            $.ajax({
                url: '<?php echo site_url('apis/postdata/doRegister') ?>',
                type: 'post',
                dataType: 'json',
                data: $('#login_form').serialize(),
            })
            .done(function( data ) {
                $('#login_form input[name=csrf_nx]').val( data.csrf_data );
                console.log( data );

                swal({
                    heading: data.heading,
                    html: data.message,
                    type: data.type
                }).then( function(){

                    if( data.status ){
                        window.location.href='<?php echo site_url('') ?>';
                    }
                    
                });

            })
            .always(function() {
                $('body').loading('stop');
            }); 
        });

    });

</script>