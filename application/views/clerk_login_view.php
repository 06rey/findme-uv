
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="<?php echo base_url();?>assets/img/fmv-icon.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $pageTitle?></title>


  <!-- Custom fonts for this template-->
  <link href="<?php echo base_url();?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?php echo base_url();?>assets/css/sb-admin-2.css" rel="stylesheet">



</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>

              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">

                      <h1 class="h4 text-gray-900 mb-4">FIND ME UV</h1>
                    
                   


                  <form action="<?php echo base_url('employee/login_validation');?>" method="post">

                    <?php if (!empty($message)):?>
                        <div class="alert alert-<?php echo $message['type'] ?>">
                            <?php echo $message['message'] ?>
                        </div>
                    <?php endif ?>

                    <div class="form-group">
                      <input name="contact_no" id="contact_no" type="text" class="form-control form-control-user" placeholder="Phone Number" required="required">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" placeholder="Password" required="required" name="password" id="password">
                    </div>
                    
                    
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                    </div>
                    
                  </form>
                 
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

<!-- Bootstrap core JavaScript-->
  <script src="<?php echo base_url();?>assets/vendor/jquery/jquery.min.js"></script>
  <script src="<?php echo base_url();?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?php echo base_url();?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?php echo base_url();?>assets/js/sb-admin-2.min.js"></script>

</body>

</html>
