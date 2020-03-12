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
            

            <!-- Card Header - Dropdown -->
                <div class="card-header d-flex flex-row  justify-content-between"> 
                  <h3>Passenger List</h3>
                </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped text-gray-800 filtered text-gray-900"  id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Boarding Pass</th>
                      <th>Passenger Name</th>
                      <th>Seat No.</th>
                      <th>Contact No.</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Boarding Pass</th>
                      <th>Passenger Name</th>
                      <th>Seat No.</th>
                      <th>Contact No.</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach ($passenger_list as $key => $passenger) :?>
                    <tr>
                      <td><?php echo $passenger->boarding_pass; ?></td>
                      <td><?php echo $passenger->full_name; ?></td>
                      <td><?php echo $passenger->seat_no; ?></td>
                      <td><?php echo $passenger->contact_no; ?></td>
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

