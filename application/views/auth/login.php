<h5 class="font-medium m-b-20">Login</h5>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
                            <?php echo form_open('', array('class' => 'form-horizontal m-t-20', 'id' => 'login_form' )); ?>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="custom-control custom-checkbox"> 
                                            <a href="<?php echo base_url('auth/forgot_password') ?>" class="text-dark float-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <div class="col-xs-12 p-b-20">
                                        <button class="btn btn-block btn-lg btn-info" name="do_login" type="submit">Log In</button>
                                    </div>
                                </div>
                                <div class="form-group m-b-0 m-t-10">
                                    <div class="col-sm-12 text-center">
                                        Don't have an account? <a href="<?php echo base_url('auth/register') ?>" class="text-info m-l-5"><b>Sign Up</b></a>
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
                url         : '<?php echo site_url('apis/postdata/doLogin') ?>', // the url where we want to POST
                type        : 'post', // define the type of HTTP verb we want to use (POST for our form)
                dataType    : 'json', // what type of data do we expect back from the server
                data        : $('#login_form').serialize(), // our data object
                encode      : true
            })
                // using the done promise callback
                .done(function(data) {
                    console.log( data );
                    $('input[name=csrf_nx]').val( data.csrf_data );
                    swal({
                        title: data.heading,
                        html: data.message,
                        type: data.type,
                    }).then( function(){

                        if( data.status ){
                            location.reload();
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