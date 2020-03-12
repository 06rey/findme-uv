<?php $this->load->view('partials/header');?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mt-3 text-gray-800">Accident Alert Contact List</h1>
          <hr>

            <?php if (!empty($message)):?>
              <div class="alert alert-<?php echo $message['type'] ?>">
                <?php echo $message['message'] ?>
              </div>
            <?php endif ?>

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <button class="btn btn-success" id="add-contact">Add Contact</button>
             
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped text-gray-800 filtered"  id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Contact Number</th>
                      <th>Alert reciever</th>
                      <th>Date Modify</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                     <th>Contact Number</th>
                      <th>Alert reciever</th>
                      <th>Date Modify</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach($all_contact as $contact ):?>
                      <tr>
                        <td><?php echo $contact->contact_no?></td>
                        <td><?php echo $contact->contact_name; ?></td>
                        <td><?php echo $contact->date_modify; ?></td>
                        <td>
                          <a href="#" class="updateContact" id="<?= $contact->contact_no.'//'. $contact->contact_name.'//'.$contact->contact_id?>"><i class="fa fa-edit" ></i> Update</a>&nbsp;&nbsp;
                          <a class="delete" style="color: red" href="#" id="<?php echo base_url('logs/remove_contact/'.$contact->contact_id);?>"><i class="fa fa-trash"></i> Remove</a>
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
                        <div class="modal fade" id="add-contact-modal" role="dialog" data-keyboard="false" data-backdrop="static" style="z-index: 1600; top: 20%">
                          <div class="modal-dialog modal-md">
                          
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="title">Add Contact</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <!-- form -->
                              <form id="myForm" action="" method="POST">

                              <div class="modal-body" style="background: white"><br>
                                <div style="padding-left: 50px; padding-right: 50px">
                            
                                      <div class="form-group">
                                        <label>Contact Name</label>
                                        <input type="text" class="form-control" placeholder="Enter contact name" name="contact_name" required id="contact_name">
                                      </div>
                                      <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="text" class="form-control" placeholder="Enter contact number" name="contact_no" required id="contact_no">
                                      </div>
                                    
                                </div>
                              </div>

                              <div class="modal-footer" style="width: 100%">
                                <button type="button" class="btn btn-danger" id="btn-close">Close</button>
                                <button type="submit" class="btn btn-success" id="btn-save">Save</button>
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
      $('#add-contact').click(function(){
          $('#add-contact-modal').modal('show');
          $("#myForm").attr("action","<?php echo site_url("logs/add_contact"); ?>");
          clearFields();
      });

      $('#btn-close').click(function(){
          clearFields();
          $('#add-contact-modal').modal('toggle');
      });

       $('#btn-close').click(function(){
          clearFields();
          $('#add-contact-modal').modal('toggle');
      });

      $('.updateContact').click(function(){
        id = this.id;
        id = id.split('//');
        $('#contact_no').val(id[0]);
        $('#contact_name').val(id[1]);
        $('#add-contact-modal').modal('show');
        $("#myForm").attr("action","<?php echo site_url("logs/update_contact/"); ?>"+id[2]);
      });
   });

   function clearFields() {
        $('#contact_name').val("");
        $('#contact_no').val("");
   }

</script>