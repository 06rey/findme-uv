<style type="text/css">
    .van-div {
      height: 300px;
      width: 200px;
      background-image: url("<?php echo base_url();?>assets/img/seat_Layout.jpg");
      background-size: 100% 100%;
      background-repeat: no-repeat;
      border: 1px solid gray;/*NOTE: naruruba an layout pag tinangal kay nareresize an parent layout*/
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
  </style>
<br>
<br>
<div class="ts-main-content">
      <?php $this->load->view('partials/header');?>
    <div class="content-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <h2 class="page-title"><?php echo $pageTitle?></h2>

            <?php if (!empty($message)):?>
                        <div class="alert alert-<?php echo $message['type'] ?>">
                          <?php echo $message['message'] ?>
                        </div>
                      <?php endif ?>
            <div class="panel panel-default">
              <div class="panel-heading">
                <a class="btn btn-success btn-sm"  style="font-size: 15px; text-decoration-color: #ffff" href="<?php echo base_url();?>trip_management/add"><i class="fa fa-plus-circle"></i> <strong>ADD TRIP</strong></a>&nbsp;&nbsp;


                <!-- BOOOOOOKKK-->
                <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#driver_info" title="View Full Details" onclick="getAvailableSeat()"><i class="fa fa-plus-circle"></i> <strong>BOOK</strong></a> 
              </div>



              <div id="driver_info" class="modal fade" role="dialog">
                      <div class="modal-dialog modal-lg">
                          <!-- Modal content BOOOOOOOOOOOOOOKINNGGGG-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Tacloban-Ormoc Trip</h4>
                            </div>

                            <div class="modal-body">
                              <div class="container-fluid">
                                <div class="panel panel-default">
                                  <div class="panel-heading">Seat Allocation 
                                      <button onclick="newAlloc()">Add new passenger</button>
                                      <input type="number" id="pass" placeholder="Enter no. of passenger" style="width:100px;">
                                      <button onclick="setNoOfPass()">enter</button>
                                  </div>
                                  <div class="panel-body">
                                    <div class="layout">
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
                                        <p><strong>Available Seat(s):</strong> <span id="available-seat">0</span></p>
                                      </div>
                                  </div>
                                </div>

                                <button onclick="showFrom()">Show form</button>

                                <div id="frm-1" style="display: none">
                                    <form method="POST">
                                    <input class="formVal" type="text" name="fullname" value="asdasdas">
                                    <input class="formVal" type="number" name="contact" value="1231231">
                                  </form>
                                </div>

                                <div id="frm-2" style="display: none">
                                    <form method="POST">
                                    <input class="formVal" type="text" name="fullname" value="skdhg">
                                    <input class="formVal" type="number" name="contact" value="123231">
                                  </form>
                                </div>

                                <div id="frm-3" style="display: none">
                                    <form method="POST">
                                    <input class="formVal" type="text" name="fullname">
                                    <input class="formVal" type="number" name="contact">
                                  </form>
                                </div>

                                <div id="frm-4" style="display: none">
                                    <form method="POST">
                                    <input class="formVal" type="text" name="fullname">
                                    <input class="formVal" type="number" name="contact">
                                  </form>
                                </div>

                                <div id="frm-5" style="display: none">
                                    <form method="POST">
                                    <input class="formVal" type="text" name="fullname">
                                    <input class="formVal" type="number" name="contact">
                                  </form>
                                </div>

                                <div id="frm-6" style="display: none">
                                    <form method="POST">
                                    <input class="formVal" type="text" name="fullname">
                                    <input class="formVal" type="number" name="contact">
                                  </form>
                                </div>


                                <button onclick="saveBooking()">Save</button>
                                <button onclick="setTripId(<?php echo 191; ?>)">Trip id</button>

                              </div>



                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>

                     <!-- Modal content BOOOOOOOOOOOOOOKINNGGGG-->




              <div class="panel-body">
                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>Route</th>
                      <th>UV</th>
                      <th>Driver</th>
                      <th>Date</th>
                      <th>Departure Time</th>
                      <th>Status</th>
                      <th>Action</th>
                      
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Route</th>
                      <th>UV</th>
                      <th>Driver</th>
                      <th>Date</th>
                      <th>Departure Time</th>
                      <th>Status</th>
                      <th>Action</th>
                      
                    </tr>
                  </tfoot>
                  <tbody>
                      <?php foreach($all_trip as $trip ):?>
                      <tr>
                        <td><?php echo $trip->route_name?></td>
                        <td><?php echo $trip->plate_no ?></td>
                        <td><?php echo $trip->driver_id ?></td>
                        <td><?php echo $trip->date?></td>
                        <td><?php echo $trip->depart_time ?></td>
                        <td><?php echo $trip->status ?></td>
                        <td>

                          

                        </td>
                      </tr>
                    <?php endforeach;?>
                    
                  </tbody>
                </table>
              </div>
            </div>

          
          </div>
        </div>

        <?php echo "my shhsdhsbdjshbdhsdhsjhsbsd " . base_url();?>

      

      </div>
    </div>
  </div>

  <!-- Loading Scripts -->
  <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/bootstrap-select.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/Chart.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/fileinput.js"></script>
  <script src="<?php echo base_url();?>assets/js/chartData.js"></script>
  <script src="<?php echo base_url();?>assets/js/main.js"></script>

</body>


<script type="text/javascript">

  var GREEN = "rgb(26, 255, 26)";
  var BLUE = "rgb(0, 0, 255)";
  var RED = "rgb(255, 0, 0)";

  var doing_booking = false;

  var selectedSeat = new Array();// index 1-14 = seat no 1-14, if set to true, seat no is selected by user 
  var noOfPass = 0; 
  var noOfSelectedSeat = 0; 
  var availableSeat = 0; // trip availbale no of seat
  var queue_id = 0; // transaction queue id
  var done = false; // if false seat button are disabled. 
  var trip_id = 0;
  var INTERVAL = 500;

  // set all seat as not selected
  for (a=1; a<15; a++) {
    selectedSeat[a] = false;
  }

  function setTripId(id) {
    trip_id = id;
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
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
            console.log("LOADING.... " + this.responseText);
            try {
              checkSeat(Object.entries(JSON.parse(this.responseText)[0]));
            } catch(e) {
              console.log("ERROR response.");
              console.log(this.responseText);
            }
            if (!done) {
              setTimeout(getAvailableSeat, INTERVAL);
            }
        }
    };
    xhttp.open("GET", "<?php echo site_url("booking/get_seat"); ?>", true);
    xhttp.send();
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
            }
          }
        } 
      }
    }
    availableSeat = (availableSeat - noOfSelectedSeat);
    document.getElementById("available-seat").innerHTML = availableSeat;
  }

  function setNoOfPass() {
    noOfPass = document.getElementById("pass").value;
    alert(noOfPass);
    if (noOfPass >= availableSeat) {

    } else {
      alert("Only "+availableSeat+" seat(s) are available");
    }
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

    for (var pair of formData.entries())
    {
     console.log(pair[0]+ ', '+ pair[1]); 
    }

    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "<?php echo site_url("booking/save_booking/"); ?>", true);
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText);
          //queue_id = Object.entries(JSON.parse(this.responseText))[0][1];
        }
    };
    xhttp.send(formData);
  }


</script>

