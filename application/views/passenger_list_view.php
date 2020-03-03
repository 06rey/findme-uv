<style type="text/css">
    .van-div {
      height: 300px;
      width: 200px;
      background-image: url("<?php echo base_url();?>assets/img/seat_Layout.jpg");
      background-size: 100% 100%;
      background-repeat: no-repeat;
      border: 1px solid transparent;/*NOTE: naruruba an layout pag tinangal kay nareresize an parent layout*/
     
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
    .legend {
      background: blue;
      color: white;
      font-size: 12px;
      padding-top: 5px;
      cursor: pointer;
      height: 15px;
      width: 15px;
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


<?php $this->load->view('partials/header');

//echo "<pre>";

//print_r($passenger_list);

?>


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

                     <a href="<?php echo base_url($this->session->userdata('url')); ?>" class=" btn btn-danger btn-icon-split">
                    <span class="icon text-white-50">
                      <i class="fas fa-history"></i>
                    </span>
                    <span class="text">BACK</span>
                  </a>
        
                  <a href="#" class=" btn btn-info btn-icon-split" onclick="location.reload();">
                    <span class="icon text-white-50">
                      <i class="fas fa-info-circle"></i>
                    </span>
                    <span class="text">REFRESH</span>
                  </a>
                  <a href="#" class=" btn btn-success btn-icon-split" onclick="setTripId(<?php echo $trip->trip_id;?>, <?php echo $trip->fare;?>)">
                    <span class="icon text-white-50">
                      <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">ADD PASSENGER</span>
                  </a>
            </div>



            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped text-gray-800"  id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Boarding Pass</th>
                      <th>Passenger Name</th>
                      <th>Seat No.</th>
                      <th>Contact No.</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Boarding Pass</th>
                      <th>Passenger Name</th>
                      <th>Seat No.</th>
                      <th>Contact No.</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach ($passenger_list as $key => $passenger) :?>
                    <tr>
                      <td><?php echo $passenger->boarding_pass; ?></td>
                      <td><?php echo $passenger->full_name; ?></td>
                      <td><?php echo $passenger->seat_no; ?></td>
                      <td><?php echo $passenger->contact_no; ?></td>
                      <td class="text-center">

                          <a class="text-danger" style="font-weight: bold" title="Book Passenger" href="<?php echo base_url('booking/removePassenger/'.$trip->route_name.'/'.$trip->trip_id.'/'.$passenger->booking_id.'/'.$passenger->seat_id); ?>">
                            <i class="fa fa-times"></i> Cancel
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

        <div class="container">
                        <!-- Modal -->
                        <div class="modal fade" id="step1Modal" role="dialog" data-keyboard="false" data-backdrop="static">
                          <div class="modal-dialog modal-md">
                          
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="title">Number of Passenger</h4>
                              </div>

                              <div class="modal-body" style="background: white"><br>
                                <div style="padding-left: 50px; padding-right: 50px">
                                    <input type="number" class="form-control form-control-user " id="pass" name="no_of_passenger" placeholder="Enter Number of Passenger"><br>
                                </div>
                              </div>

                              <div class="modal-footer" style="width: 100%">
                                <button type="button" class="btn btn-danger" id="step1Cancel">Cancel</button>
                                <button type="button" class="btn btn-success" id="step1Next">Next</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- End of step1 modal -->

                      <!-- step2 modal start -->

                      <div class="container">
                        <!-- Modal -->
                        <div class="modal fade" id="step2Modal" role="dialog" data-keyboard="false" data-backdrop="static">
                          <div class="modal-dialog modal-md">
                          
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="title">UV Express Seat Reservation</h4>
                              </div>

                              <div class="modal-body" style="background: white">
                                <div class="row">
                                  <div class="col-lg-12 ">
                                    <div class="layout">
                                    <center>
                                    <div class="row" style="padding: 20px">

                                        <div class="col-md-6" style="text-align: left; padding-left: 20px;">

                                            <strong class="text-info">SEAT LEGEND</strong><br>
                                            <span class="legend" style="background: blue"></span> Available<br>
                                            <span class="legend" style="background: lightgreen"></span> Selected<br>
                                            <span class="legend" style="background: red"></span> Reserved<br>
                                            <span class="legend" style="background: darkgray"></span> Driver<br>

                                            <br><br>
                                            <strong>Available Seat:</strong> <span id="available-seat">0</span><br>
                                            <strong>Passenger:</strong> <span id="no-of-pass">0</span><br>
                                            <strong>Fare:</strong> &#8369;<span id="fare">0</span><br>
                                            <strong>Amount:</strong> &#8369;<span id="amount">0</span><br>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="van-div" id="van">

                                          <div class="row row-1" style="margin-top: 50px;">
                                            <div class="one seat" style="margin-left: 38px; background: gray;"></div>
                                            <div id="seat1" class="seat one" style="margin-left: 30px;" onclick="seatClick(1)">1</div>
                                            <div id="seat2" class="seat one" style="margin-left: 11px;" onclick="seatClick(2)">2</div></div>

                                          <div  class="row row-1" style="margin-top: -9px">
                                            <div id="seat3" class="col-xs-3 seat one" style="margin-left: 33px;" onclick="seatClick(3)">3</div>
                                            <div id="seat4" class="col-xs-3 seat one" style="margin-left: 13px;" onclick="seatClick(4)">4</div>
                                            <div id="seat5" class="col-xs-3 seat one" style="margin-left: 12px;" onclick="seatClick(5)">5</div>
                                          </div>

                                          <div class="row row-1" style="margin-top: -22px">
                                            <div id="seat6" class="col-xs-3 seat one" style="margin-left: 33px;" onclick="seatClick(6)">6</div>
                                            <div id="seat7" class="col-xs-3 seat one" style="margin-left: 12px;" onclick="seatClick(7)">7</div>
                                            <div id="seat8" class="col-xs-3 seat one" style="margin-left: 34px;" onclick="seatClick(8)">8</div>
                                          </div>

                                          <div class="row row-1" style="margin-top: -23px">
                                            <div id="seat9" class="col-xs-3 seat one" style="margin-left: 33px;" onclick="seatClick(9)">9</div>
                                            <div id="seat10" class="col-xs-3 seat two" style="margin-left: 12px;" onclick="seatClick(10)">10</div>
                                            <div id="seat11" class="col-xs-3 seat two" style="margin-left: 34px;" onclick="seatClick(11)">11</div>
                                          </div>

                                          <div class="row row-1" style="margin-top: -24px">
                                            <div id="seat12" class="col-xs-3 seat two" style="margin-left: 35px;" onclick="seatClick(12)">12</div>
                                            <div id="seat13" class="col-xs-3 seat two" style="margin-left: 19px;" onclick="seatClick(13)">13</div>
                                            <div id="seat14" class="col-xs-3 seat two" style="margin-left: 19px;" onclick="seatClick(14)">14</div>
                                          </div>

                                        </div>
                                        </div>
                                        
                                    </div>
                                    </center>



                                      </div>

                                    </div>
                                  
                                </div>

                              </div>

                              <div class="modal-footer" style="width: 100%">
                                <button type="button" class="btn btn-danger" id="step2Cancel">Cancel</button>
                                <button type="button" class="btn btn-success" id="step2Next">Next</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- end of step2 modal -->


                      <!-- step 3 modal -->
                      <div class="container">
                        <!-- Modal -->
                        <div class="modal fade" id="step3Modal" role="dialog" data-keyboard="false" data-backdrop="static">
                          <div class="modal-dialog modal-md">
                          
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="title">Passenger Infromation</h4>
                              </div>
                              <div class="modal-body" style="background: white; overflow-y: auto; margin: auto; max-height: 400px">
                                
                                <!-- MODAL BODY -->
                                <div  style="">

                                  <div id="frm-1" class="p-1" style="display: none">
                                    <label class="col-sm-8 control-label">Passenger 1 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname*">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-2" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 2 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-3" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 3 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-4" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 4 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-5" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 5 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-6" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 6 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-7" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 7 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-8" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 8 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-9" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 9 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-10" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 10 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-11" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 11 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-12" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 12 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-13" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 13 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>

                                    <div id="frm-14" class="p-1" style="display: none">
                                       <label class="col-sm-8 control-label">Passenger 14 Info : </label>
                                     <div class="row">
                                          <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control formVal" name="fullname" placeholder="Enter Fullname">
                                          </div>
                                          <div class="col-sm-6">
                                            <input type="text" class="form-control formVal" name="contact" placeholder="Contact No">
                                          </div>
                                        </div>
                                    </div>



                                </div>

                                <!-- MODAL BODY END -->

                              </div>
                              <div class="modal-footer" style="width: 100%">
                                <button type="button" class="btn btn-danger" id="step3Cancel">Cancel</button>
                                <button type="button" class="btn btn-success" id="step3Next">Save</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- End Of Modal 3-->


                      <!-- LOADING MODAL -->
                      <div class="container">
                        <!-- Modal -->
                        <div class="modal fade" id="loadingModal" role="dialog" data-keyboard="false" data-backdrop="static">
                           <!-- LOADING GIF -->
                            <div>
                                
                            </div>

                        </div>
                      </div>
                      <!-- END OF LOADING MODAL -->


      </div>
      <!-- End of Main Content -->

  <?php $this->load->view('partials/footer');?>

  <script type="text/javascript">


  var GREEN = "rgb(26, 255, 26)";
  var BLUE = "rgb(0, 0, 255)";
  var RED = "rgb(255, 0, 0)";

  var doing_booking = true;

  var selectedSeat = new Array();// index 1-14 = seat no 1-14, if set to true, seat no is selected by user 
  var noOfPass = 0; 
  var noOfSelectedSeat = 0;
  var vacantSeat = 0; 
  var availableSeat = 14; // trip availbale no of seat
  var queue_id = 0; // transaction queue id
  var done = false; // if false seat button are disabled. 
  var trip_id = 0;
  var fare = 0;
  var totFare = 0;
  var INTERVAL = 0; // MS interval of synchronise http request to server
  var isModalOpen = false; // Hold status of booking modal if open 

  initVar();  


    // Get trip available seat on page load
   $(window).on('load', function() {
        get_available_seat('<?php echo $trip->trip_id; ?>');
    });

  // Notify user on page reload or leave while booking modal is open
    window.onbeforeunload = function() {
        if (isModalOpen) {
            return 'Are you sure you want to leave?';
        }
    };

    // Delete booking queue if modal is open on page reloading
    $(window).on('unload', function() {
        if (isModalOpen) {
            $.ajax({
                type: 'GET',
                url: '<?php echo site_url("booking/delete_book/"); ?>' + queue_id,
                async: false
            });
        }
    });
 

  // modal button function

  $(document).ready(function(){

    // STEP 1 NEXT ANBD CANCEL BUTTON CLICK
    $("#step1Next").click(function(){
        if (setNoOfPass()){
            $('#step1Modal').modal('toggle');
            $('#step2Modal').modal('show');
            $('#no-of-pass').text(noOfPass);
            $('#fare').text(fare);
            $('#amount').text(fare * noOfPass);
        }
    });

    $("#step1Cancel").click(function(){
        var yes = confirm("Are you sure you want to cancel booking?");
        if (yes){
            $('#step1Modal').modal('toggle');
            $('#pass').val('');
             isModalOpen = false;
             location.reload();
        } 
    });

    // STEP 2 NEXT ANBD CANCEL BUTTON CLICK
    $('#step2Next').click(function(){
        if (noOfSelectedSeat == noOfPass) {
            $('#step2Modal').modal('toggle');
            $('#step3Modal').modal('show');
            showFrom();
        } else {
            alert('Please select ' + (noOfPass - noOfSelectedSeat) + ' more seat.');
        }
    });

    $('#step2Cancel').click(function(){
        var yes = confirm("Are you sure you want to cancel booking?");
        if (yes){
            $('#step2Modal').modal('toggle');
            cancelBooking();
        } 
    });

    // STEP 3 NEXT AND CANCEL BUTTON CLICK
    $('#step3Next').click(function(){
        $('#step3Modal').modal('toggle');
        saveBooking();
    });

    $('#step3Cancel').click(function(){
        var yes = confirm("Are you sure you want to cancel booking?");
        if (yes){
            $('#step3Modal').modal('toggle');
           cancelBooking();
        } 
    });

  });

  function removePassenger(path) {
    alert(path);
    if (confirm('Are you sure you want to cancel seat reservation?')) {
         $.ajax({
            type: 'GET',
            url: path,
            dataType: 'json',
            success: function(data){
              if (data['result'] > 0) {
                location.reload();
              } else {
                alert('Failed to remove seat reservation.');
              }
            }
        });
    }
  }

  function get_available_seat(id) {
    $.ajax({
        type: 'GET',
        url: '<?php echo site_url("booking/count_available_seat/"); ?>' + id,
        async: true,
        dataType: 'json',
        success: function(data){
           vacantSeat = 14 - data['available'];
           console.log('TRIP VACANT SEAT: ' + vacantSeat);
           setTimeout(get_available_seat(id), 1000);
        }
    });
  }

  function cancelBooking() {
    deleteAllocSeat();
    initVar();
    done = true;
    $('#pass').val('');
    isModalOpen = false;
    location.reload();
  }

  // end modal button functions

  function initVar() {
    deleteAllocSeat();
    noOfPass = 0;
    noOfSelectedSeat = 0;
    queue_id = 0;
    totFare =0;
    document.getElementById("pass").innerHTML = '';
    selectedSeat = new Array();
    for (a=1; a<15; a++) {
      selectedSeat[a] = false;
    }
  }

  function setTripId(id, fr) {
    if (vacantSeat > 0) {
        isModalOpen = true;
        trip_id = id;
        fare = fr;
        $('#step1Modal').modal('show');
    } else {
        alert('This trip is currently no available seat.');
    }
  }

  function setNoOfPass() {
    noOfPass = document.getElementById("pass").value;
    if ((noOfPass <= vacantSeat) && noOfPass > 0) {
      insertBookQueue();
      totFare = noOfPass*fare;
      return true;
    } else {
        if (noOfPass < 1) {
            alert("Number of passenger is not valid.");
        } else {
            alert("Only "+vacantSeat+" seat(s) are available");
        }
      return false;
    }
    return true;
  }

  function showFrom() {
    for (a=1; a<=noOfPass; a++ ) {
      document.getElementById("frm-"+a).style.display = "inline-block";
    }
  }

  function seatClick(idNo) {
    var div = document.getElementById("seat"+idNo);
    var color = window.getComputedStyle(div, null).getPropertyValue("background-color");
    if (doing_booking) {
      if (color != RED) {
        if (color == BLUE) {
          if (noOfSelectedSeat < noOfPass) {
            document.getElementById("seat"+idNo).style.backgroundColor = GREEN;
            selectedSeat[idNo] = true;
            noOfSelectedSeat++;
            insertSelectedSeat(idNo);
          } else {
            alert("You have selected "+noOfSelectedSeat+" seat(s) already for "+noOfPass+ " passenger.");
          }
        } else {
          document.getElementById("seat"+idNo).style.backgroundColor = BLUE;
          selectedSeat[idNo] = false;
          noOfSelectedSeat--;
          deleteSelectedSeat(idNo);
        }
      }
    }
  }


  function getAvailableSeat() {
    var xhttp = new XMLHttpRequest();
    var param = trip_id +"/"+queue_id;
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
            console.log("LOADING.... " + this.responseText);
            try {
              checkSeat(Object.entries(JSON.parse(this.responseText)[0]));
            } catch(e) {
              console.log("ERROR response.");
              console.log(this.responseText);
              document.getElementById("available-seat").innerHTML = availableSeat - noOfSelectedSeat;
            }
            if (!done) {
              setTimeout(getAvailableSeat, INTERVAL);
            }
        }
    };
    xhttp.open("GET", "<?php echo site_url("booking/get_seat/"); ?>"+param, true);
    xhttp.send();
    console.log("<?php echo site_url("booking/get_seat/"); ?>"+param);
  }
  // Set not available seat to color red
  function checkSeat(arr) {
    availableSeat = 14;
    for (a=1; a<15; a++) { 
      if (selectedSeat[a] == false) {
        document.getElementById("seat"+a).style.backgroundColor = BLUE;
        for (b=0 ; b<arr.length; b++) {
          if (arr[b][1] == a) {
            if (selectedSeat[a] == false) {
              document.getElementById("seat"+a).style.backgroundColor = RED;
              availableSeat--;
              console.log("AVAILBALE: " + availableSeat);
            }
          }
        } 
      }
    }
    availableSeat = (availableSeat - noOfSelectedSeat);
    document.getElementById("available-seat").innerHTML = availableSeat;
  }

  //Demo 
  function newAlloc() {
    insertBookQueue();
    doing_booking = true;
  }

  function deleteSelectedSeat(seatNo) {
    var xhttp = new XMLHttpRequest();
    var param = seatNo+"/"+queue_id;
    xhttp.open("GET", "<?php echo site_url("booking/delete_seat/"); ?>" + param, true);
    xhttp.send();
  }

  function insertSelectedSeat(seatNo) {
    var xhttp = new XMLHttpRequest();
    var param = seatNo+"/"+queue_id+"/"+trip_id;
    xhttp.open("GET", "<?php echo site_url("booking/insert_seat/"); ?>" + param, true);
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        //console.log(this.responseText);
        var state = Object.entries(JSON.parse(this.responseText))[0][1];
        console.log("state: " + state);
        if (state == "false") {
          document.getElementById("seat"+seatNo).style.backgroundColor = RED;
          selectedSeat[seatNo] = false;
          noOfSelectedSeat--;
          console.log("state: " + state);
          alert("Seat number "+seatNo+ " are just reserved to new passenger.");
        }

      }
    };
    xhttp.send();
  }

  function insertBookQueue() {
    var xhttp = new XMLHttpRequest();
    var param = noOfPass+"/"+trip_id; 
    xhttp.open("GET", "<?php echo site_url("booking/insert_queue/"); ?>" + param, true);
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        //console.log(this.responseText);
          queue_id = Object.entries(JSON.parse(this.responseText))[0][1];
          getAvailableSeat();
        }
    };
    xhttp.send();
  }
  // Delete cancel transaction
  function deleteAllocSeat() {
    var xhttp = new XMLHttpRequest();
    var param = queue_id;
    xhttp.open("GET", "<?php echo site_url("booking/delete_book/"); ?>" + param, true);
    xhttp.send();
    return 0;
  }

  function saveBooking() {
    var elements = document.getElementsByClassName("formVal");
    var formData = new FormData();
    var i =0;
    var temp = 2;
    for (b=0; b<noOfPass; b++) {
      for(; i<temp; i++)
      {
        formData.append(elements[i].name + b, elements[i].value);
      }
      temp+=2;
      for (a=1; a<15; a++) {
          if (selectedSeat[a] == true) {
             formData.append("seat"+b, a);
             selectedSeat[a] = false;
             break;
          }
      }
    }

    formData.append("no_of_pass", noOfPass);
    formData.append("trip_id", trip_id);
    formData.append("amount", totFare);


    for (var pair of formData.entries()) {
     console.log("---------------------"+pair[0]+ ', '+ pair[1]); 
    }

    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "<?php echo site_url("booking/save_booking/"); ?>", true);
    var stat = false;
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            //queue_id = Object.entries(JSON.parse(this.responseText))[0][1];
            stat = true;
            initVar();
        }
        isModalOpen = false;
        location.reload();
        alert("Successfully save reservation.");
    };
    xhttp.send(formData);
  }


</script>



