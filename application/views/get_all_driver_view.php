

<?php $this->load->view('partials/header'); ?>


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

              <a href="<?php echo base_url();?>employee/add_driver" class=" btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus-circle"></i>
                </span>
                <span class="text">ADD DRIVER</span>
              </a>

            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped text-gray-800"  id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Full Name</th>
                      <th>License Number</th>
                      <th>User Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Full Name</th>
                      <th>License Number</th>
                      <th>User Status</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach($all_driver as $driver ):?>
                      <tr>
                        <td><?php echo $driver->f_name . ' ' . $driver->m_name .' ' . $driver->l_name ?></td>
                        <td><?php echo $driver->license_no ?></td>
                         <?php
                    
                            if ($driver->status == 1) {
                              $status = "ACTIVE";
                              $color = 'green';
                            } else {
                              $status =  "INACTIVE";
                              $color = 'red';
                            }
                          ?>
                        <td style="color: <?php echo $color; ?>"><?php echo $status; ?></td>
                        <td class="text-center">

                          <a class="btn btn-info btn-circle btn-sm" data-toggle="modal" data-target="#driver_info<?php echo $driver->employee_id ?>" title="View Full Details">
                            <i class="fas fa-user"></i>
                          </a>&nbsp;&nbsp;

                          <a href="<?php echo base_url('employee/edit_driver/'.$driver->employee_id) ?>" title="Edit" class="btn btn-warning btn-circle btn-sm text-gray-800">
                            <i class="fas fa-pen"></i>
                          </a>



                        </td>
                      </tr>

                      <!-- Modal -->

                      <div class="modal fade" id="driver_info<?php echo $driver->employee_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Driver's Information</h5>
                              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                              </button>
                            </div>

                            <div class="modal-body">
                              <div class="row">
                                      <div class="col-md-6">
                                        <p><strong>First Name:</strong> <?php echo $driver->f_name ?></p>
                                        <p><strong>Middle Name:</strong> <?php echo $driver->m_name ?> </p>
                                        <p><strong>Last Name:</strong> <?php echo $driver->l_name ?></p>
                                        <p><strong>License No.:</strong> <?php echo $driver->license_no ?> </p>
                                      </div>

                                      <div class="col-md-6">
                                        <p><strong>User Status:</strong> <?php echo $status ?> </p>
                                        <p><strong>Contact No.:</strong> <?php echo $driver->contact_no ?> </p>
                                        <p><strong>Address:</strong> <?php echo $driver->address ?></p>
                                      </div>
                                    </div>

                            </div>
                            <div class="modal-footer">
                              
                              <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>

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





