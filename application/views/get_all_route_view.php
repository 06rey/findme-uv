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

              <a href="<?php echo base_url();?>route/add" class=" btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus-circle"></i>
                </span>
                <span class="text">ADD ROUTE</span>
              </a>

            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped text-gray-800"  id="dataTable" width="100%" cellspacing="0">
                  <thead>
                      <tr>
                      <th>Route Name</th>
                      <th>Origin</th>
                      <th>Destination</th>
                      <th>Via</th>
                      <th>Fare</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Route Name</th>
                      <th>Origin</th>
                      <th>Destination</th>
                      <th>Via</th>
                      <th>Fare</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach($all_route as $route ):?>
                    <tr>
                      <td><?php echo $route->route_name ?></td>
                      <td><?php echo $route->origin ?></td>
                      <td><?php echo $route->destination ?></td>
                      <td><?php echo $route->via ?></td>
                      <td><?php echo $route->fare ?></td>
                      <td class="text-center">
                        <a class="text-info " href="<?php echo base_url('route/edit_route/'.$route->route_id) ?>" title="Edit"><i class="fa fa-info"></i> Info</a>
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



