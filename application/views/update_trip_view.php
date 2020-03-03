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
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <p class="m-0 text-primary">Fill All Info</p>
              <div>
                <a class="btn btn-info btn-circle btn-sm text-white" data-toggle="modal" data-target="#help"title="Help">
                  <i  class="fas fa-question"></i>
                </a>

                <!-- Help Modal -->
                <div class="modal fade" id="help" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">How to Update Trip</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body">
                              

                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div><!-- End of Help Modal -->
              </div>
            </div><!-- End of Card Header -->

            <div class="card-body">

              <div class="row">
                <div class="col-sm-12">
                  <div class="p-5">
                    
                    <form method="post" action="<?php echo base_url('trip_management/save_trip/'.$trip->trip_id);?>" class="form-horizontal">

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Route : </label>
                                  <div class="col-sm-8">
                                    <input type="text" name="" id=""  class="form-control" value="<?php echo $trip->route_name ?>" disabled>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Trip Status : </label>
                                  <div class="col-sm-8">
                                    <input type="text" name="" id=""  class="form-control" value="<?php echo $trip->status ?>" disabled>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">UV Van : </label>
                                  <div class="col-sm-8">
                                    <select type="text" name="uv_id" id="uv_id"  class="form-control">
                                      <option value="">--Select Available UV--</option>
                                      <?php 

                                        if ($trip->uv_id != '') :?>
                                          <option selected value="<?php echo $trip->uv_id; ?>"><?php echo $trip->plate_no; ?></option>
                                        <?php endif;

                                        foreach($uv_unit as $uv ):?>
                                          <option value="<?php echo $uv->uv_id ?>"><?php echo $uv->plate_no ?></option>
                                      <?php endforeach;?>
                                    </select>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Driver : </label>
                                  <div class="col-sm-8">
                                    <select type="text" name="driver_id" id="driver_id"  class="form-control">
                                      <option value="">--Select Available Driver--</option>
                                      <?php
                                      if ($trip->driver_id != '') :?>
                                          <option selected value="<?php echo $trip->driver_id; ?>"><?php echo $trip->driver_name; ?></option>
                                        <?php endif;

                                       foreach($driver as $id ):?>

                                      <option value="<?php echo $id->employee_id ?>"><?php echo $id->f_name .'&nbsp;'. $id->l_name ?></option>

                                      <?php endforeach;?>
                                    </select>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Date : </label>
                                  <div class="col-sm-8">
                                    <input type="date" name="date" id="date"  class="form-control" required value="<?php echo $trip->date; ?>">
                                  </div>
                                </div>

                      

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Departure Time  </label>
                                  <div class="col-sm-8">
                                    <select  type="text" name="depart_time"  class="form-control" required>
                                      <option value="">--Select Depart Time--</option>
                                      <option selected value="<?php echo date("g:i A", strtotime( $trip->depart_time)); ?>"><?php echo date("g:i A", strtotime( $trip->depart_time)); ?></option>
                                      <option value="4:00 AM">4:00 AM</option>
                                      <option value="4:30 AM">4:30 AM</option>
                                      <option value="5:00 AM">5:00 AM</option>
                                      <option value="5:30 AM">5:30 AM</option>
                                      <option value="6:00 AM">6:00 AM</option>
                                      <option value="6:30 AM">6:30 AM</option>
                                      <option value="4:00 AM">4:00 AM</option>
                                      <option value="4:30 AM">4:30 AM</option>
                                      <option value="5:00 AM">5:00 AM</option>
                                      <option value="5:30 AM">5:30 AM</option>
                                      <option value="6:00 AM">6:00 AM</option>
                                      <option value="6:30 AM">6:30 AM</option>
                                      <option value="7:00 AM">7:00 AM</option>
                                      <option value="7:30 AM">7:30 AM</option>
                                      <option value="8:00 AM">8:00 AM</option>
                                      <option value="8:30 AM">8:30 AM</option>
                                      <option value="9:00 AM">9:00 AM</option>
                                      <option value="9:30 AM">9:30 AM</option>
                                      <option value="10:00 AM">10:00 AM</option>
                                      <option value="10:30 AM">10:30 AM</option>
                                      <option value="11:00 AM">11:00 AM</option>
                                      <option value="11:00 AM">11:30 AM</option>
                                      <option value="12:00">12:00 AM</option>
                                      <option value="12:30 AM">12:30 AM</option>
                                      <option value="1:00 PM">1:00 PM</option>
                                      <option value="1:30 PM">1:30 PM</option>
                                      <option value="2:00 PM">2:00 PM</option>
                                      <option value="2:30 PM">2:30 PM</option>
                                      <option value="3:00 PM">3:00 PM</option>
                                      <option value="3:30 PM">3:30 PM</option>
                                      <option value="4:00 PM">4:00 PM</option>
                                      <option value="4:30 PM">4:30 PM</option>
                                      <option value="5:00 PM">5:00 PM</option>
                                      <option value="5:30 PM">5:30 PM</option>
                                      <option value="6:00 PM">6:00 PM</option>
                                      <option value="6:30 PM">6:30 PM</option>
                                      <option value="4:00 PM">4:00 PM</option>
                                      <option value="4:30 PM">4:30 PM</option>
                                      <option value="5:00 PM">5:00 PM</option>
                                      <option value="5:30 PM">5:30 PM</option>
                                      <option value="6:00 PM">6:00 PM</option>
                                      <option value="6:30 PM">6:30 PM</option>
                                      <option value="7:00 PM">7:00 PM</option>
                                      <option value="7:30 PM">7:30 PM</option>
                                      <option value="8:00 PM">8:00 PM</option>

                                    </select>
                                  </div>
                                </div>

                                <br>                                 

                                <div class="col-sm-6 col-sm-offset-4">
                                  <a class="btn btn-secondary" href="<?php echo base_url();?>trip_management/all">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>

                                  <a class="btn btn-danger" href="<?php echo base_url();?>trip_management/trip_status/Cancelled/<?php echo $trip->trip_id; ?>">Cancel Trip</a>

                                  <input type="submit" name="submit" value="Update Trip" class="btn btn-primary">
                                </div>
                              </form>
                  </div>
                </div>
              </div>
              
            </div> <!-- End of Card Body -->

                                 
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

  <?php $this->load->view('partials/footer');?>







