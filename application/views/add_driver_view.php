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
              <p class="m-0 text-primary">Fill All Info</p>
            </div>
            <div class="card-body">

        <div class="row">
          <div class="col-lg-12">
            <div class="p-5">
              
              <form method="post" action="<?php echo base_url('employee/add_driver_validation/driver');?>" class="form-horizontal">


                          

                          <h4>Personal Info</h4><br>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">First Name : </label>
                            <div class="col-sm-8">  
                              <input type="text" name="f_name" id="f_name"  class="form-control" value="<?php echo $employee->f_name; ?>">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Middle Name : </label>
                            <div class="col-sm-8">
                              <input type="text" name="m_name" id="m_name"  class="form-control" value="<?php echo $employee->m_name; ?>">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Last Name : </label>
                            <div class="col-sm-8">
                              <input type="text" name="l_name" id="l_name"  class="form-control" value="<?php echo $employee->l_name; ?>">
                            </div>
                          </div>


                          <div class="form-group">
                            <label class="col-sm-2 control-label">License Number : </label>
                            <div class="col-sm-8">
                              <input type="text" name="license_no" id="license_no"  class="form-control" value="<?php echo $employee->license_no; ?>">
                            </div>
                          </div>
                                
                          <div class="form-group">
                            <label class="col-sm-2 control-label">Address : </label>
                            <div class="col-sm-8">
                              <input type="text" name="address" id="address"  class="form-control" value="<?php echo $employee->address; ?>">
                            </div>
                          </div><br>


                          <h4>User Account Info</h4><br>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Contact Number</label>
                            <div class="col-sm-8">
                              <input type="text" name="contact_no" id="contact_no"  class="form-control" value="<?php echo $employee->contact_no; ?>">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-8">
                              <input type="password"name="password" id="password"  class="form-control">
                            </div>
                          </div> 

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Confirm Password</label>
                            <div class="col-sm-8">
                              <input type="password"name="confirm_password" id="confirm_password"  class="form-control">
                            </div>
                          </div> 


                          <br>

                          <div class="col-sm-6 col-sm-offset-4">
                            <button class="btn btn-secondary" type="reset">Cancel</button>
                            <input type="submit" name="submit" Value="Register" class="btn btn-primary">
                          </div>
                        </form>

            </div>
          </div>
        </div>
              
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

  <?php $this->load->view('partials/footer');?>








