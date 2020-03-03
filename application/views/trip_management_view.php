<style type="text/css">
    .van-div {
      height: 300px;
      width: 200px;
      background-image: url("<?php echo base_url();?>assets/img/seat_Layout.jpg");
      background-size: 100% 100%;
      background-repeat: no-repeat;
      border: 1px solid gray;/*NOTE: naruruba an layout pag tinangal kay nareresize an parent layout*/
      margin-left: 130px;
    }
    .seat {
      background: blue;
      color: white;
      font-size: 12px;
      padding-top: 5px;
      cursor: pointer;
      height: 25px;
      width: 25px;
      display: inline-block;
    }
    .one {
      padding-left: 9px;
    }
    .two {
      padding-left: 6px;
    }
    .row-1 {
      padding: 20px;
    }
    .layout { 
    }

    body {
      background: white;
    }

    <style>
/* width */
::-webkit-scrollbar {
    width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #f1f1f1; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
    background: #888; 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #555; 
}
</style>
  </style>
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

                  <?php if ($this->session->userdata('role') == 'admin'):?>

                  <a href="<?php echo base_url();?>trip_management/add" class=" btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                      <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">ADD TRIP</span>
                  </a> 

                  <?php else:?>

                  <?php endif?>  

                  

                </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped text-gray-800 filtered text-gray-900"  id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>From</th>
                      <th>To</th>
                      <th>Date</th>
                      <th>Departure Time</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>From</th>
                      <th>to</th>
                      <th>Date</th>
                      <th>Departure Time</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                      <?php foreach($all_trip as $trip ):?>
                      <tr>
                        <td><?php echo $trip->origin?></td>
                        <td><?php echo $trip->destination?></td>
                        
                       
                        <td><?php echo $trip->date?></td>
                        <td><?php echo date("g:i A", strtotime($trip->depart_time)) ?></td>

                        <?php
                          $stat_color = 'black';
                          if ($trip->status == "Arrived") {
                            $stat_color = 'green';
                          } else if  ($trip->status == "Cancelled") {
                             $stat_color = 'red';
                          }
                        ?>

                        <td style="color: <?php echo $stat_color; ?>"><?php echo $trip->status ?></td>
                        <td>

                          <?php
                            date_default_timezone_set('Asia/Manila');
                            $hidden = '';
                            if ($trip->query_date_time < date('Y-m-d H:i:s') || $trip->status == 'Arrived' || $trip->status == 'Cancelled') {
                              $hidden = 'none';
                            } else {
                              $hidden = 'block';
                            }
                          ?>

                          <a class="text-success" style="display: <?php echo $hidden; ?>" href="<?php echo base_url('trip_management/update_trip/'.$trip->trip_id);?>" title="Update Trip" >
                            <i class="fa fa-edit"></i> Update
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

