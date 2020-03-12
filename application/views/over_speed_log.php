<?php $this->load->view('partials/header');?>


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

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">

             
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped text-gray-800 filtered"  id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Trip No.</th>
                      <th>Driver Name</th>
                      <th>Speed</th>
                      <th>Plate No.</th>
                      <th>Date Added</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Trip No.</th>
                      <th>Driver Name</th>
                      <th>Speed</th>
                      <th>Plate No.</th>
                      <th>Date Added</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach($all_logs as $logs ):?>
                      <tr>
                        <td><?php echo $logs->trip_id; ?></td>
                        <td><?php echo $logs->f_name.' '.$logs->l_name; ?></td>
                        <td><?php echo $logs->speed.' kph' ?></td>
                        <td><?php echo $logs->plate_no; ?></td>
                        <td><?php echo $logs->date_time; ?></td>
                        
                      </tr>
                    <?php endforeach;?>
                      
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

  <?php $this->load->view('partials/footer');?>






