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

              <a href="<?php echo base_url();?>uv_unit/add" class=" btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus-circle"></i>
                </span>
                <span class="text">Add UV Express</span>
              </a>

            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped text-gray-800"  id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Plate Number</th>
                      <th>Model</th>
                      <th>Max. Passenger</th>
                      <th>Franchise Number</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Plate Number</th>
                      <th>Model</th>
                      <th>Max. Passenger</th>
                      <th>Franchise Number</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach($all_uv as $uv ):?>
                      <tr>
                        <td><?php echo $uv->plate_no ?></td>
                        <td><?php echo $uv->model ?></td>
                        <td><?php echo $uv->max_pass ?></td>
                        <td><?php echo $uv->franchise_no ?></td>
                        <td class="text-center">

                          <a href="<?php echo base_url('uv_unit/update_uv_view/'.$uv->uv_id) ?>" title="Info" class="text-info">
                            <i class="fa fa-info"></i> Info
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






