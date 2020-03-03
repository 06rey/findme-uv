<?php $this->load->view('partials/header');

//echo "<pre>";

//print_r($trip_list);

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

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">

            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped text-gray-800"  id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Trip No.</th>
                      <th>Date</th>
                      <th>Departure Time</th>
                      <th>No. of Passenger</th>
                      <th>Vehicle Plate No.</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Trip No.</th>
                      <th>Date</th>
                      <th>Departure Time</th>
                      <th>No. of Passenger</th>
                      <th>Vehicle Plate No.</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach ($trip_list as $key => $trip) :?>
                    <tr>
                      <td class="text-info" style="font-weight: bold"><?php echo 'UVTRIP-'.$trip->trip_id ?></td>
                      <td class="text-success" style="font-weight: bold"><?php echo $trip->date ?></td>
                      <td class="text-primary" style="font-weight: bold"><?php echo $trip->depart_time ?></td>
                      <td><?php echo $trip->no_of_pass ?></td>
                      <?php
                        $color = 'black';
                        if ($trip->plate_no == 'No Assign') {
                          $color = 'red';
                        }
                      ?>
                      <td style="color: <?php echo $color; ?>"><?php echo $trip->plate_no; ?></td>
                      <td class="text-center">

                          <a class="text-warning" style="font-weight: bold" href="<?php echo base_url('booking/passenger_list/'.$route_name.'/'.$trip->trip_id);?>" title="Book Passenger">
                            <i class="fa fa-list"></i> Passenger List
                          </a>


                      </td>
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



