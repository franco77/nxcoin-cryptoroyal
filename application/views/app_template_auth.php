<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo APP_NAME ?></title>  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content=""> 
    <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>assets/images/favicon.png"> 
    <link href="<?=base_url()?>assets/dist/css/style.min.css" rel="stylesheet"> 
    <link href="<?=base_url()?>assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">  
    <link href="<?=base_url()?>assets/node_modules/jquery-easy-loading/dist/jquery.loading.min.css" rel="stylesheet"> 
    <?php   
        echo script_tag('assets/vendors/bower_components/jquery/dist/jquery.min.js');
        echo script_tag('assets/node_modules/jquery-easy-loading/dist/jquery.loading.min.js');
        echo script_tag('assets/node_modules/sweetalert2/dist/sweetalert2.min.js');
    ?>
    
</head>
<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div> 
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url(<?php echo base_url() ?>assets/images/big/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box">
                <div id="loginform">
                    <div class="logo">
                        <span class="db"><img src="<?php echo base_url('assets/images/logo-icon.png') ?>" width="100" alt="logo" /></span>
                        
                            <?php echo $this->template->content ?> 
                        </div>
                    </div>
                </div> 
            </div>
        </div> 
        
    </div>  
    
</body>        

    <?php  

        $js    = array(
            'assets/libs/popper.js/dist/umd/popper.min.js',
            'assets/libs/bootstrap/dist/js/bootstrap.min.js',
            'assets/dist/js/app.min.js',
            'assets/dist/js/app.init.light-sidebar.js', 
            'assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js',
            'assets/extra-libs/sparkline/sparkline.js',
            'assets/dist/js/waves.js',
            'assets/dist/js/sidebarmenu.js',
            'assets/dist/js/custom.js', 
            'assets/extra-libs/c3/d3.min.js',
            'assets/extra-libs/c3/c3.min.js', 
        ); 
        foreach ($js as $js) {
            echo script_tag( $js )."\n";
        }
    ?>
    <script type="text/javascript"> 
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut(); 
    

      function fallbackCopyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea); 
        textArea.select();

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
           
        } catch (err) { 
        }

        document.body.removeChild(textArea);
      }
      function copyTextToClipboard(text) {
        if (!navigator.clipboard) {
          fallbackCopyTextToClipboard(text);
          return;
        }
        navigator.clipboard.writeText(text).then(function() {
           
        } );
      }
      jQuery(document).ready(function($) {
 
        $("#myModal").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-body").load( link.data('href') );
            $(".modal-title").html( link.data('title') );

        });

        $(".copy").click(function(event) {
          copyText = $(this).data('id'); 
          copyTextToClipboard(copyText); 
          swal({
            position: 'center',
            type: 'success',
            title: 'Tersalin ke papan Ketik',
            showConfirmButton: false, //
            timer: 3000, //rgba(0,0,123,0.4)
            /*backdrop: `
              rgba(0,0,0,0.8)
              url("https://media3.giphy.com/media/12NUbkX6p4xOO4/giphy.gif") 
              bottom
              no-repeat
            `*/
          })
        });
      });



    </script>
</body>

</html>
