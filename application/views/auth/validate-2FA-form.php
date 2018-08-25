                        <h5 class="font-medium m-b-20">VALIDATE 2FA</h5>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
                            <?php echo form_open('', array('class' => 'form-horizontal m-t-20', 'id' => 'validate2fa' )); ?>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="ti-key"></i></span>
                                    </div>
                                    <input type="text" name="oneCodeAuth" class="form-control form-control-lg" placeholder="One Code" aria-label="oneCodeAuth" aria-describedby="basic-addon1">
                                </div> 
                                <div class="form-group text-center">
                                    <div class="col-xs-12 p-b-20">
                                        <button class="btn btn-block btn-lg btn-info" name="do_login" type="submit">VALIDATE 2 FA</button>
                                    </div>
                                </div> 
                                <div class="form-group m-b-0 m-t-10">
                                    <div class="col-sm-12 text-center">
                                        Lost 2Fa? <a href="<?php echo base_url('auth/reset2FA') ?>" class="text-info m-l-5"><b>Reset Here</b></a>
                                    </div>
                                </div>
                            <?php echo form_close(); ?> 

        <script type="text/javascript">
    
    // magic.js
    $(document).ready(function() {

        // process the form
        $('#validate2fa').submit(function(event) {
            event.preventDefault();
            $('body').loading();

            $.ajax({
                url         : '<?php echo site_url('userpost/postdata/validate2FA') ?>', // the url where we want to POST
                type        : 'post', // define the type of HTTP verb we want to use (POST for our form)
                dataType    : 'json', // what type of data do we expect back from the server
                data        : $('#validate2fa').serialize(), // our data object
                encode      : true
            })
                // using the done promise callback
                .done(function(result) {
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
            event.preventDefault();
        });

    });

</script>