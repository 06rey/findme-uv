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
              
              <form method="post" action="<?php echo base_url('route/edit_route_validation/'.$route->route_id) ?>" class="form-horizontal">


                          <div class="form-group">
                            <label class="col-sm-2 control-label">Route Name : </label>
                            <div class="col-sm-8">
                              <input type="text" name="route_name" id="route_name"  class="form-control" value="<?php echo $route->route_name ?>" disabled>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Origin : </label>
                            <div class="col-sm-8">
                              <input type="text" name="origin" id="origin"  class="form-control" value="<?php echo $route->origin ?>" disabled >

                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Destination : </label>
                            <div class="col-sm-8">
                              <input type="text" name="destination" id="destination"  class="form-control" value="<?php echo $route->destination ?>" disabled>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Via : </label>
                            <div class="col-sm-8">
                              <input type="text" name="via" id="via"  class="form-control" value="<?php echo $route->via ?>">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Fare : </label>
                            <div class="col-sm-8">
                              <input type="text" name="fare" id="fare"  class="form-control" value="<?php echo $route->fare ?>">
                            </div>
                          </div>

                          <br>

                          <div class="col-sm-6 col-sm-offset-4">
                            <a class="btn btn-secondary" href="<?php echo base_url();?>route/all">Cancel</a>
                            <input type="submit" name="submit" value="Update" class="btn btn-primary">
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