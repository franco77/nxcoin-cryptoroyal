<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="<?=base_url()?>assets/dist/css/style.css" rel="stylesheet">
    
</head>
<body style="background:#ECF5F9;" data-theme="dark">
<style>
.card {
    border-radius:6px;
    padding:30px;
}

</style>
    <div class="container-fluid">
       
        <div class="row" style="margin-top:10%;">
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body text-center">
                        
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <img src="<?= site_url('assets/images/logo-icon.png') ?>" class="img-responsive" alt="Image">
                                
                            </div>
                            
                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                <img src="<?= site_url('assets/images/logo-light-text.png')?>" class="img-responsive" alt="Image">
                                <h4><?= $message; ?></h4>
                        
                                <a href="<?=base_url();?>" class="btn btn-default"><h6>Back To Dashboard</h6></a>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                
            </div>
        </div>
    </div>
    
    <!-- Latest compiled and minified JS -->
    
    
</body>
</html>