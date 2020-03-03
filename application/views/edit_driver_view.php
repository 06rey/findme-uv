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

                    <form method="post" action="<?php echo base_url('employee/edit_driver_validation/'.$employee->employee_id.'/'.$employee->user_id) ?>" class="form-horizontal">


                          <div class="form-group">
                            <label class="col-sm-2 control-label">First Name : </label>
                            <div class="col-sm-8">  
                              <input type="text" name="f_name" id="f_name"  class="form-control" value="<?php echo $employee->f_name; ?>" required>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Middle Name : </label>
                            <div class="col-sm-8">
                              <input type="text" name="m_name" id="m_name"  class="form-control" value="<?php echo $employee->m_name; ?>" required>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Last Name : </label>
                            <div class="col-sm-8">
                              <input type="text" name="l_name" id="l_name"  class="form-control" value="<?php echo $employee->l_name; ?>" required>
                            </div>
                          </div>


                          <div class="form-group">
                            <label class="col-sm-2 control-label">License Number : </label>
                            <div class="col-sm-8">
                              <input type="text" name="license_no" id="license_no"  class="form-control" value="<?php echo $employee->license_no; ?>" required>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Contact Number</label>
                            <div class="col-sm-8">
                              <input type="text" name="contact_no" id="contact_no"  class="form-control" value="<?php echo $employee->contact_no; ?>" required>
                            </div>
                          </div>
                                
                          <div class="form-group">
                            <label class="col-sm-2 control-label">Address : </label>
                            <div class="col-sm-8">
                              <input type="text" name="address" id="address"  class="form-control" value="<?php echo $employee->address; ?>" required>
                            </div>
                          </div><br>

                          <?php 

                            if ($employee->status == 1) {
                              $status = 'ACTIVE';
                              $active = 'selected';
                              $inactive = '';
                            } else {
                              $status = 'INACTIVE';
                              $inactive = 'selected';
                              $active = '';
                            }

                          ?>

                          <div class="form-group">
                                  <label class="col-sm-2 control-label">User Status : </label>
                                  <div class="col-sm-8">
                                    <select type="text" name="status" id="head"  class="form-control" <?php echo $status; ?>>
                                      <option value="">--Select Status--</option>
                                      <option <?php echo $active; ?> value="1">ACTIVE</option>
                                      <option <?php echo $inactive; ?> value="0">INACTIVE</option>
                                    </select>
                                  </div>
                                </div>
                          


                          <br>

                          <div class="col-sm-6 col-sm-offset-4">
                            <button class="btn btn-secondary" type="reset">Cancel</button>
                            <input type="submit" name="submit" Value="Save Changes" class="btn btn-primary">
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






