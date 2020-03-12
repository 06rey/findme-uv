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
                <table class="table table-bordered table-striped text-gray-800 filtered" id="dataTable"  width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Passenger Name</th>
                      <th>Feedback</th>
                      <th>Date Created</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Passenger Name</th>
                      <th>Feedback</th>
                      <th>Date Created</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach($all_feedback as $feedback ):?>
                      <tr>
                        <td><?php echo $feedback->f_name.' '.$feedback->l_name; ?></td>
                        <td><?php echo $feedback->temp_msg?><?php echo $feedback->shorten ? '<i class="fa fa-eye msg" style="float: right; cursor:pointer" id="'.$feedback->message.'"></i>' : ''?></td>
                        <td><?php echo $feedback->date_added; ?></td>
                        <td>
                          <a class="delete" style="color: red" href="#"  id="<?php echo base_url('logs/delete_feedback/'.$feedback->feedback_id);?>" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash"></i> Delete</a>
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

      <div class="container">
        <!-- Modal -->
        <div class="modal fade" id="modal-msg" role="dialog" data-keyboard="false" data-backdrop="static" style="z-index: 1600; top: 20%">
          <div class="modal-dialog modal-md">
          
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="title">Feedback</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <!-- form -->
              <form id="myForm" action="" method="POST">

              <div class="modal-body" style="background: white"><br>
                <div style="padding-left: 50px; padding-right: 50px">
            
                      <p class="p-msg"></p>
                    
                </div>
              </div>

              <div class="modal-footer" style="width: 100%">
                <button type="button" class="btn btn-danger" id="modal-msg-close">Close</button>
              </div>

              </form>
              <!-- end form -->
            </div>
          </div>
        </div>
      </div>



  <?php $this->load->view('partials/footer');?>



<script type="text/javascript">


  $(document).ready(function(){



    $('#btn-close').click(function(){
      clearFields();
    });
  
  });

</script>




