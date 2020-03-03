<?php $this->load->view('partials/header');

//echo "<pre>";

//print_r($all_trip);

?>


        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mt-3 text-gray-800"><?php echo $pageTitle?></h1>
          <hr>

            <?php if (!empty($message)):?>
              <div class="alert alert-<?php echo $message['type'] ?>">
                <?php echo $message['message'] ?>
              </div>
            <?php endif ?>

            <div class="row">

              <?php

                if ($all_trip == null) :?>
                  <br><br>
                  <h3 style="margin: auto;">No trip schedule found</h3>
                <?php endif;

                foreach ($all_trip as $key => $trip) :?>

                  <div class="col-xl-4 mb-4">
                    <div class="card border-left-info shadow h-100 ">
                      <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-0">
                              <a style="font-weight: bold; text-align: left;" href="<?php echo base_url('booking/route_trip/'.$trip->route_name.'/'.$trip->route_id);?>" class="btn btn-default link">
                                <?php echo $trip->route_name; ?>
                              </a>
                            </div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800" style="margin-left: 15px">
                              <?php echo  $trip->count; ?>  
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
            </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

  <?php $this->load->view('partials/footer');?>



