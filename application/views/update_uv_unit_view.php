
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
              
              <form method="post" action="<?php echo base_url('uv_unit/update_uv/').$uv->uv_id;?>" class="form-horizontal">


                          <div class="form-group">
                            <label class="col-sm-8 control-label">Plate Number : </label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control " id="plate_no" name="plate_no" value="<?php echo $uv->plate_no; ?>" required>

                            </div>
                          </div>


                          <div class="form-group">
                            <label class="col-sm-8 control-label">Maximum Passenger : </label>
                            <div class="col-sm-8">
                              <input type="number" class="form-control " id="max_pass" name="max_pass" value="<?php echo $uv->max_pass; ?>" required>

                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-8 control-label">Franchise Number : </label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control-user" id="franchise_no" name="franchise_no" value="<?php echo $uv->franchise_no; ?>" required>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-8 control-label">Model : </label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control " id="model" name="model" value="<?php echo $uv->model; ?>" required>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Brand Name : </label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control " id="brand_name" name="brand_name" value="<?php echo $uv->brand_name; ?>" required>
                            </div>
                          </div>

                          <br>
                          <div class="col-sm-6 col-sm-offset-4">
                             <a class="btn btn-secondary" href="<?php echo base_url();?>uv_unit/all">Cancel</a>
                            <input type="submit" name="submit" value="Submit" class="btn btn-primary">
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






