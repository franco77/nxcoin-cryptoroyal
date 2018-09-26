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
    <link href="<?=base_url()?>assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/extra-libs/c3/c3.min.css" rel="stylesheet"> 
    <link href="<?=base_url()?>assets/dist/css/style.css" rel="stylesheet"> 
    <link href="<?=base_url()?>assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet"> 
    <link href="<?=base_url()?>assets/node_modules/jquery-easy-loading/dist/jquery.loading.min.css" rel="stylesheet"> 
    <link href="<?=base_url()?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet"> 
    <?php   
        echo script_tag('assets/vendors/bower_components/jquery/dist/jquery.min.js');
        echo script_tag('assets/node_modules/jquery-easy-loading/dist/jquery.loading.min.js');
        echo script_tag('assets/node_modules/sweetalert2/dist/sweetalert2.min.js');

        $userdata = userdata();
    ?>
</head>

<body> 
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div> 
    <div id="main-wrapper"> 
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header"> 
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a> 
                    <a class="navbar-brand" href="index.html">  
                        <b class="logo-icon">  
                            <img src="<?=base_url()?>assets/images/logo-icon.png" alt="homepage" width="50" class="light-logo" />
                        </b>
                        <span class="logo-text">  
                             <img src="<?=base_url()?>assets/images/logo-light-text.png" class="light-logo" width="170" alt="homepage" />
                        </span>
                    </a> 
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div> 
                <div class="navbar-collapse collapse" id="navbarSupportedContent"> 
                    <ul class="navbar-nav float-left mr-auto">
                        <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li> 
                          
                        <!-- <li class="nav-item search-box"> <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search position-absolute">
                                <input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn"><i class="ti-close"></i></a>
                            </form>
                        </li> -->
                    </ul> 
                    <ul class="navbar-nav float-right">   
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img alt="user" class="img-circle" width="31" src="<?php echo base_url('uploads/image/').''.$userdata->user_picture ?>"></a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <span class="with-arrow"><span class="bg-primary"></span></span>
                                <div class="d-flex no-block align-items-center p-15 bg-warning text-white mb-10">
                                    <div class="">
                                        <img alt="user" class="img-circle" width="60" src="<?php echo base_url('uploads/image/').''.$userdata->user_picture ?>">
                                        </div>
                                    <div class="ml-10">
                                        <h4 class="mb-0" style="margin-left: 10px"><?php echo $userdata->user_fullname ?></h4>
                                        <p class=" mb-0" style="margin-left: 10px"><?php echo $userdata->email ?></p>
                                    </div>
                                </div>
                                <a class="dropdown-item" href="<?php echo base_url('profile') ?>"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item logout" href="#"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a> 
                            </div>
                        </li> 
                    </ul>
                </div>
            </nav>
        </header> 
        <aside class="left-sidebar"> 
            <div class="scroll-sidebar"> 
                <?php 
                    echo $this->load->view('library/sidebar', '', TRUE);
                ?> 
            </div> 
        </aside> 
        <div class="page-wrapper"> 
            <div class="page-breadcrumb" id="breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title"><?php echo $this->template->title; ?></h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->template->title; ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <script src="<?= site_url('assets/croyal/UserBalance.js'); ?>"></script>
            <div style="display: none" id="badge">
                <?php echo $this->load->view('library/badge', '', TRUE); ?>
            </div> 
            <div class="container-fluid">
            
                <?php echo $this->template->content ?>
            </div> 
            <footer class="footer text-center">
                All Rights Reserved by <?php echo APP_NAME ?>.
            </footer> 
        </div> 
    </div> 
            
    
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header "> 
                <h4 class="modal-title" id="myModalLabel"></h4>
                <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                
            </div>
            <div class="modal-body">
                <i class="fa fa-spinner fa-spin"></i> Loading... 
            </div>
            <div class="modal-footer"> 
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    <!-- jQuery -->


    <?php  
        $this->mainmodel->Always_Load();
        $js    = array( 
            'assets/libs/popper.js/dist/umd/popper.min.js',
            'assets/libs/bootstrap/dist/js/bootstrap.min.js',
            'assets/dist/js/app.js',
            'assets/dist/js/app.init.light-sidebar.js',
            'assets/dist/js/app-style-switcher.js',
            'assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js',
            'assets/extra-libs/sparkline/sparkline.js',
            'assets/dist/js/waves.js',
            'assets/dist/js/sidebarmenu.js',
            'assets/dist/js/custom.js',  
            'assets/extra-libs/c3/d3.min.js',
            'assets/extra-libs/c3/c3.min.js',  
            'assets/croyal/Leadership.js',  
        ); 
        foreach ($js as $js) {
            echo script_tag( $js )."\n";
        }
    ?>
    
    <script type="text/javascript">


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
        
        $('.logout').click(function(event) {
            event.preventDefault();
            $('body').loading();
            swal({
                title: 'Are you sure?',
                text: "Logout",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '<?php echo base_url('auth/logout') ?>',
                        type: 'get',
                        dataType: 'json',
                        data: {},
                    }) 
                    .always(function() {
                        window.location.reload();
                    });
                    
                }
            })
            $('body').loading('stop');  
        });  

        $("#myModal").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-body").load( link.data('href') ); 
            $(".modal-title").html( link.data('title') );

        });

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
    
    var env = {
        site_url: '<?= base_url(); ?>',
    };
    var CR_USERID = '<?= userid(); ?>';
    $(document).ready(function() {
        $.fn.CrLeadership().updateStar();
    });

    </script>
</body>

</html>
