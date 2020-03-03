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

             
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped text-gray-800 filtered"  id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Activity</th>
                      <th>Created-By</th>
                      <th>Created-On</th>
                      <th>Creator-Role</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Activity</th>
                      <th>Created-By</th>
                      <th>Created-On</th>
                      <th>Creator-Role</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach($all_logs as $logs ):?>
                      <tr>
                        <td><?php echo $logs['activity'] ?></td>
                        <td><?php echo $logs['fullname'] ?></td>
                        <td><?php echo $logs['created_on'] ?></td>
                        <td><?php echo $logs['role'] ?></td>
                        
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






