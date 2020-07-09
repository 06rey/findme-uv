<?php $this->load->view('partials/header'); ?>
  <!-- Begin Page Content -->
  <div class="container-fluid" id="app">
    <!-- Page Heading -->
    <h1 class="h3 mt-3 text-gray-800"><?= $data->origin.' to '.$data->destination ?></h1>
    <!-- Navigation -->
    <ol class="breadcrumb mb-3 mt-3">
      <li class="breadcrumb-item active">
        <a href="<?=base_url('booking')?>">UV Express Booking</a>
      </li>
      <div class="div-v"></div>
      <li class="breadcrumb-item active-nav">
        <a v-bind:href="pageUrl">
          Trip Schedule
        </a>
      </li>
    </ol>
    <!-- DataTales Example -->
    <div class="card mb-4">
      <div class="card-header py-3 d-flex align-items-center justify-content-between">


          <div class="btn-group">
            <button class="btn btn-outline-primary btn-sm" 
                type="button" 
                v-on:click="decrementDate" 
                id="btnDecreaseDate">
              <i class="fa fa-chevron-left"></i>
            </button>
             <button class="btn btn-outline-primary btn-sm" 
              type="button" 
              v-on:click="incrementDate" 
              id="btnIncreaseDate"> 
              <i class="fa fa-chevron-right"></i>
            </button>
          </div>
          <button class="btn btn-outline-primary btn-sm ml-2" 
                type="button" 
                v-on:click="todayTrip" 
                id="btnToday">
            Today
          </button>

          <h6 class="h5 text-gray-900 ml-auto mr-auto mt-2">
            {{ `${departDate.day}, ${departDate.month} ${departDate.dayNo}, ${departDate.year}` }}
          </h6>

          <div class="btn-group">
            <input type="date" 
              placeholder="Select Date" 
              class="btn btn-outline-primary btn-sm" 
              id="dateInput"
              min="<?= date('Y-m-d') ?>">
            <button class="btn btn-primary btn-sm text-align-left" v-on:click="searchTripDate">
              Go
            </button>
          </div>

      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-gray-800"  id="tripTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>#</th>
                <th>Departure Time</th>
                <th>Driver</th>
                <th>Plate No.</th>
                <th>Vacant Seat</th>
                <th>Action</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>#</th>
                <th>Departure Time</th>
                <th>Driver</th>
                <th>Plate No.</th>
                <th>Vacant Seat</th>
                <th>Action</th>
              </tr>
            </tfoot>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!------------------------------------------------------------------------
     | MODAL
     ------------------------------------------------------------------------>
    <!-- Passenger list modal -->
    <div class="modal modal-hide" id="tripInfoModal">
      <div class="modal-dialog modal-xl mt-4 mdPass">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h5 class="modal-title"><strong>Trip Information</strong></h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">&times</button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <div class="breadcrumb d-flex justify-content-between mb-3">
              <div>
                <h6 class="modal-title font-weight-bold mr-3"><span class="text-gray-800">Trip #: </span><span id="tripNo"></span></h6>
                <h6 class="modal-title font-weight-bold mr-3"><span class="text-gray-800">Depart Time: </span><span id="dTime"></span></h6>
              </div>
              <div>
                <h6 class="modal-title font-weight-bold mr-3"><span class="text-gray-800">Plate #: </span><span id="plateNo"></span></h6>
                <h6 class="modal-title font-weight-bold mr-3"><span class="text-gray-800">Seat Reservation: </span><span id="countPass"></span></h6>
              </div>
              <div>
                <h6 class="modal-title font-weight-bold mr-3"><span class="text-gray-800">Driver: </span><span id="driverName"></span></h6>
                <h6 class="modal-title font-weight-bold mr-3"><span class="text-gray-800">Vacant Seat: </span><span id="vacantSeat"></span></h6>
              </div>
            </div>
            <button class="btn btn-outline-info btn-sm table-modal-btn" data-toggle="modal" data-target="#noOfPassModal">
              <i class="fa fa-plus"></i>
              Add Reservation
            </button>
            <!-- Passenger table -->
            <div class="table-responsive">
              <table class="table table-bordered table-striped text-gray-800 text-gray-900" width="100%"  id="passengerTable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Boarding Pass</th>
                    <th>Contact No</th>
                    <th>Seat No.</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
             </div>
             <!-- End Passenger table -->
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End passenger list modal -->

    <!-- Booking modal -->
    <div class="modal fade" id="bookingModal" data-backdrop="static" data-focus="true">
      <div class="modal-dialog modal-lg shadow mt-3">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><strong>Select Seat</strong></h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body pt-0 pb-0">
            <!-- UV Express layout -->
            <div class="layout">
              <div class="row" style="padding: 20px">                  
                <div class="col-md-4">
                  <center>
                    <div class="van-div" id="van">
                      <div class="position-absolute w-100 h-100 row align-items-center justify-content-center uv-overlay ml-1">
                        <div class="spinner-border text-primary" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </div>
                      <div class="row row-1" style="margin-top: 80px;">
                        <div class="seat" style="margin-left: 44px; background: gray;"></div>
                        <div id="seat1" data-vacant="true" data-selected="false" data-vacant="true" class="seat" style="margin-left: 33px; margin-top: 4px" v-on:click="selectSeat(1)">1</div>
                        <div id="seat2" data-vacant="true" data-selected="false" class="seat" style="margin-left: 11px;" v-on:click="selectSeat(2)">2</div>
                      </div>
                      <div  class="row row-1" style="margin-top: 0px">
                        <div id="seat3" data-vacant="true" data-selected="false" class="col-xs-3 seat" style="margin-left: 37px;" v-on:click="selectSeat(3)">3</div>
                        <div id="seat4" data-vacant="true" data-selected="false" class="col-xs-3 seat" style="margin-left: 13px;" v-on:click="selectSeat(4)">4</div>
                        <div id="seat5" data-vacant="true" data-selected="false" class="col-xs-3 seat" style="margin-left: 12px;" v-on:click="selectSeat(5)">5</div>
                      </div>

                      <div class="row row-1" style="margin-top: -16px">
                        <div id="seat6" data-vacant="true" data-selected="false" class="col-xs-3 seat" style="margin-left: 37px;" v-on:click="selectSeat(6)">6</div>
                        <div id="seat7" data-vacant="true" data-selected="false" class="col-xs-3 seat" style="margin-left: 12px;" v-on:click="selectSeat(7)">7</div>
                        <div id="seat8" data-vacant="true" data-selected="false" class="col-xs-3 seat" style="margin-left: 39px;" v-on:click="selectSeat(8)">8</div>
                      </div>

                      <div class="row row-1" style="margin-top: -15px">
                        <div id="seat9" data-vacant="true" data-selected="false" class="col-xs-3 seat" style="margin-left: 37px;" v-on:click="selectSeat(9)">9</div>
                        <div id="seat10" data-vacant="true" data-selected="false" data-vacant="true" class="col-xs-3 seat" style="margin-left: 12px;" v-on:click="selectSeat(10)">10</div>
                        <div id="seat11" data-vacant="true" data-selected="false" class="col-xs-3 seat" style="margin-left: 39px;" v-on:click="selectSeat(11)">11</div>
                      </div>

                      <div class="row row-1" style="margin-top: -18px">
                        <div id="seat12" data-vacant="true" data-selected="false" class="col-xs-3 seat" style="margin-left: 41px;" v-on:click="selectSeat(12)">12</div>
                        <div id="seat13" data-vacant="true" data-selected="false" class="col-xs-3 seat" style="margin-left: 20px;" v-on:click="selectSeat(13)">13</div>
                        <div id="seat14" data-vacant="true" data-selected="false" class="col-xs-3 seat" style="margin-left: 20px;" v-on:click="selectSeat(14)">14</div>
                      </div>
                    </div>
                  </center>
                </div>
                <div class="col-md-8">
                  <div class="card mt-3 mb-3 ml-3" id="reservation">
                    <div class="card-header">
                      <h6 class="font-weight-bold">Reservation Information</h6>
                    </div>
                    <div class="card-body booking-info">
                      <div id="infoPanel">
                        <div class="d-flex align-items-center justify-content-between">
                          <h6 class="font-weight-bold">From</h6><h6 class="modal-title font-weight-bold">{{ route.origin }}</h6>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                          <h6 class="font-weight-bold">To</h6>
                          <h6 class="modal-title font-weight-bold">{{ route.destination }}</h6>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                          <h6 class="font-weight-bold">Departure</h6>
                          <h6 class="modal-title font-weight-bold">{{ departDate.month }} {{ departDate.dayNo }}, {{ departDate.year }}<span class="font-weight-light"> at </span> <span class="modal-title font-weight-bold" id="timeD"></span></h6>
                        </div>
                        <hr>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                          <h6 class="font-weight-bold">Number of Passenger</h6>
                          <h6 class="modal-title font-weight-bold">{{ bookNoOfPass }}</h6>
                        </div>
                        <h6 class="font-weight-bold">Passenger Data</h6>
                        <!-- Passenger data -->
                        <div id="passengerData">
                          
                        </div>
                      </div>
                    </div>
                    <!-- End booking info -->
                  </div>
                </div>
              </div>
            </div> 
            <!-- End UV Express layout -->
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary btn-sm" type="button" v-on:click="saveBooking">Save Reservation</button>
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End booking modal -->

    <!-- Enter number of pass modal -->
    <div class="modal fade" id="noOfPassModal" data-backdrop="static" data-focus="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title font-weight-bold">Enter Number of Passenger</h6>
            <button class="close" type="button" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <input class="form-control" max="14" min="1" type="number" id="inputNoPass" v-model="bookNoOfPass">
          </div>
          <div class="modal-footer">
            <button class="btn btn-outline-info btn-sm" v-on:click="openBookingModal">Next</button>
            <button class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Enter number of pass modal -->

    <!------------------------------------------------------------------------
     | END MODAL
     ------------------------------------------------------------------------>
  </div>
  <!-- End container-fluid -->
</div>
<!-- End of Main Content -->
<?php $this->load->view('partials/footer');?>
<script type="text/javascript">
  var ROUTE_ID = '<?= $data->route_id ?>';
  var ORIGIN = '<?= $data->origin ?>';
  var DESTINATION = '<?= $data->destination ?>';
  var TRIP_DATE = '<?= $date ?>';
</script>
<script type="text/javascript" src="<?= base_url('assets/js/app/booking-trip.js') ?>"></script>