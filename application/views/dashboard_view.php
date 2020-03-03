<?php $this->load->view('partials/header');?>


        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
         
            <h1 class="h3 mt-3 text-gray-800"><?php echo $pageTitle?></h1>
            <hr>

       

          <!-- Content Row -->
          <div class="row">



            <!-- Trip Management-->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 ">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">TRIPS</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $trip; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-paper-plane fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                </div>
              </div>
            </div>

            <!-- Routes -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 ">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">ROUTES</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $route; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-road fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                </div>
              </div>
            </div>



            <!-- UV Units-->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 ">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">UV UNITS</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $uv; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-bus fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                </div>
              </div>
            </div>


            <!-- Employees -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 ">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div><a class="text-xs font-weight-bold text-warning text-uppercase mb-1" href="<?php echo base_url('employee/all') ?>">EMPLOYEES</a></div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $employee; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                </div>
              </div>
            </div>

            <!-- Bookings-->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 ">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div><a class="text-xs font-weight-bold text-warning text-uppercase mb-1" href="<?php echo base_url('booking/all') ?>">BOOKINGS</a></div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $book; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-book fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                </div>

              </div>
            </div>

          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->


<?php $this->load->view('partials/footer');?>





