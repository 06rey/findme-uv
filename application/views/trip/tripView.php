<?php $this->load->view('partials/header');?>

  <!-- Begin Page Content -->
  <div class="container-fluid" id="app">
    <div class="d-flex align-items-center justify-content-between mb-3 mt-3">
      <h1 class="h3 text-gray-800">
        {{ `${route.origin} to ${route.destination}` }}
      </h1>
    </div>
    <ol class="breadcrumb mb-3 mt-3">
      <li class="breadcrumb-item active">
        <a href="<?=base_url('trip_management/allTrip')?>">Trip Management</a>
      </li>
      <div class="div-v"></div>
      <li class="breadcrumb-item <?= $status == 'Pending' ? 'active-nav' : 'active'?>">
        <a href="<?= base_url('trip_management/trip/Pending/'.$route->route_id.'/'.date('Y-m-d')) ?>">
          Trip Schedule
        </a>
      </li>
      <div class="div-v"></div>
      <li class="breadcrumb-item <?= $status == 'Traveling' ? 'active-nav' : 'active'?>">
        <a href="<?= base_url('trip_management/trip/Traveling/'.$route->route_id.'/'.date('Y-m-d')) ?>">
          Traveling UV Express
        </a>
      </li>
       <div class="div-v"></div>
      <li class="breadcrumb-item <?= $status == 'Cancelled' ? 'active-nav' : 'active'?>">
        <a href="<?= base_url('trip_management/trip/Cancelled/'.$route->route_id.'/'.date('Y-m-d')) ?>">
          Cancelled Trip
        </a>
      </li>
      <div class="div-v"></div>
      <li class="breadcrumb-item <?= $status == 'Arrived' ? 'active-nav' : 'active'?>">
        <a href="<?= base_url('trip_management/trip/Arrived/'.$route->route_id.'/'.date('Y-m-d')) ?>">
         Trip History
        </a>
      </li>
    </ol>

  <?php if (!empty($message)):?>
    <div class="alert alert-<?php echo $message['type'] ?>">
      <?php echo $message['message'] ?>
    </div>
  <?php endif ?>

    <!-- DataTales Example -->
    <div class="card mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header d-flex align-items-center justify-content-between bg-white">
        
        <h5 class="text-gray-900 font-weight-bold">{{ activePage }}</h5>

        <div class="btn-group ml-auto mr-0">
          <button class="btn btn-outline-info btn-sm" v-on:click="showAddTrip" data-toggle="modal" data-target="#tripFormModal" v-if="tripStatus === 'Pending'">
            <i class="fa fa-plus"></i>
            Add Trip Schedule
          </button>
          <button class="btn btn-outline-secondary btn-sm" id="btnPrint" rel="tooltip" data-toggle="tooltip" title="Print">
              <i class="fas fa-print fa-fw text-primary"></i>
            </button>
            <button class="btn btn-outline-secondary btn-sm" id="btnExcel" rel="tooltip" data-toggle="tooltip" title="Excel">
              <i class="fas fa-file-excel fa-fw text-primary"></i>
            </button>
            <button class="btn btn-outline-secondary btn-sm" id="btnPdf" rel="tooltip" data-toggle="tooltip" title="Pdf">
              <i class="fas fa-file-pdf fa-fw text-primary"></i>
            </button>
            <button class="btn btn-outline-secondary btn-sm" id="btnCsv" rel="tooltip" data-toggle="tooltip" title="Csv">
              <i class="fas fa-file fa-fw text-primary"></i>
            </button>
        </div>
      </div>
      <div class="card-body">

        <div class="breadcrumb d-flex align-items-center justify-content-between">

          <div class="btn-group" v-if="tripStatus !== 'Traveling'">
            <button class="btn btn-outline-primary btn-sm" type="button" v-on:click="decrementDate" id="btnDecreaseDate">
              <i class="fa fa-chevron-left"></i>
            </button>
             <button class="btn btn-outline-primary btn-sm" type="button" v-on:click="incrementDate" id="btnIncreaseDate"> 
              <i class="fa fa-chevron-right"></i>
            </button>
          </div>
          <button class="btn btn-outline-primary btn-sm ml-2" 
                type="button" 
                v-on:click="todayTrip" 
                id="btnToday"
                v-if="tripStatus !== 'Traveling'">
            Today
          </button>

          <h6 class="h5 text-gray-900 ml-auto mr-auto mt-2">
            {{ `${departDate.day}, ${departDate.month} ${departDate.dayNo}, ${departDate.year}` }}
          </h6>

          <div class="btn-group" v-if="tripStatus !== 'Traveling'">
            <input type="date" placeholder="Select Date" class="btn btn-outline-primary btn-sm" id="dateInput">
            <button class="btn btn-primary btn-sm text-align-left" v-on:click="searchTripDate">
              Go
            </button>
          </div>

        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped text-gray-800" width="100%"  id="tripTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Departure Time</th>
                <th>Time Arrived</th>
                <th>Driver</th>
                <th>Vehicle Plate No.</th>
                <th>Action</th>
              </tr>
            </thead>
            <tfoot class="border-bottom">
              <tr>
                <th>#</th>
                <th>Departure Time</th>
                <th>Time Arrived</th>
                <th>Driver</th>
                <th>Vehicle Plate No.</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
    <!-- End card -->

    <!------------------------------------------------------------------------
     | MODAL
     ------------------------------------------------------------------------>
    <!-- Passenger list modal -->
    <div class="modal fade modal-hide" id="tripInfoModal">
      <div class="modal-dialog modal-lg mt-4">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h5 class="modal-title">Trip Information</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">&times</button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <div class="breadcrumb d-flex justify-content-between mb-3">
              <h6 class="modal-title font-weight-bold mr-3"><span class="text-gray-800">Trip #: </span>{{ selectedRowData.value[0] }}</h6>
              <h6 class="modal-title font-weight-bold mr-3"><span class="text-gray-800">Plate #: </span> {{ selectedRowData.value[tripStatus === 'Arrived' ? 4 : 3] }}</h6>
              <h6 class="modal-title font-weight-bold mr-3"><span class="text-gray-800">Driver: </span> {{ selectedRowData.value[tripStatus === 'Arrived' ? 3 : 2] }}</h6>
              <h6 class="modal-title font-weight-bold mr-3"><span class="text-gray-800">Depart Time: </span> {{ selectedRowData.value[1] }}</h6>
            </div>
            <h5 class="table-modal-btn modal-title">Passenger ({{ selectedTripNoOfPass }})</h5>
            <!-- Passenger table -->
            <div class="table-responsive">
              <table class="table table-bordered table-striped text-gray-800 text-gray-900" width="100%"  id="passengerTable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Seat No.</th>
                    <th>Boarding Pass</th>
                    <th>Status</th>
                    <th>Contact No</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
             </div>
             <!-- End Passenger table -->
          </div>
          <div class="modal-footer">
            <!-- <div class="btn-group mr-auto ml-0">
              <button class="btn btn-outline-secondary btn-sm" id="pl-btnPrint" rel="tooltip" data-toggle="tooltip" title="Print">
                <i class="fas fa-print fa-fw text-primary"></i>
              </button>
              <button class="btn btn-outline-secondary btn-sm" id="pl-btnExcel" rel="tooltip" data-toggle="tooltip" title="Excel">
                <i class="fas fa-file-excel fa-fw text-primary"></i>
              </button>
              <button class="btn btn-outline-secondary btn-sm" id="pl-btnPdf" rel="tooltip" data-toggle="tooltip" title="Pdf">
                <i class="fas fa-file-pdf fa-fw text-primary"></i>
              </button>
              <button class="btn btn-outline-secondary btn-sm" id="pl-btnCsv" rel="tooltip" data-toggle="tooltip" title="Csv">
                <i class="fas fa-file fa-fw text-primary"></i>
              </button>
            </div> -->
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End passenger list modal -->

    <!-- Add/Update Trip -->
    <div class="modal fade modal-hide" id="tripFormModal">
      <div class="modal-dialog mt-4">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h6 class="modal-title font-weight-bold">{{ tripFormData.mode }} Trip Schedule</h6>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">&times</button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <!-- Form -->
            <?= form_open('trip_management/updateTrip', 'id="fromTrip"') ?>

              <div class="row">
                <div class="form-group mb-2 col-md-6">
                  <label class="control-label">From</label>
                  <input class="form-control" type="text" disabled v-model="route.origin">
                </div>  

                <div class="form-group mb-2 col-md-6">
                  <label class="control-label">To</label>
                  <input class="form-control" type="text" disabled v-model="route.destination">
                </div> 
              </div>

              <div class="row"> 
                <div class="form-group mb-2 col-md-6">
                  <label class="control-label">Date : </label>
                  <input type="date" name="date"  class="form-control" v-model="tripDate" disabled>
                </div>

                <div class="form-group field-wrappes col-md-6">
                  <label class="control-label">Departure Time</label>
                  <select class="form-control selectpicker time_start" 
                            title="Select Departure Time" 
                            data-width="100%" 
                            data-live-search="true" 
                            id="departTime"
                            v-model="tripFormData.departTime">
                      <optgroup label="Morning Time">
                        <?php $t=4;?>
                        <?php for ($i=4; $i <= 12; $i++) { ?>
                          <?php if($i!=12){ ?>
                            <option value="<?=$t?>:00:00"><?=$i?>:00 AM</option>
                            <option value="<?=$t?>:30:00"><?=$i?>:30 AM</option>
                          <?php } else { ?>
                            <option value="<?=$t?>:00:00"><?=$i?>:00 PM</option>
                          <?php } ?>
                          <?php $t++;?>
                        <?php } ?>
                        <option value="12:30:00">12:30 PM</option>
                      </optgroup>

                      <optgroup label="Afternoon Time">
                        <?php for ($i=1; $i <= 11; $i++) { ?>
                          <option value="<?=$t?>:00:00"><?=$i?>:00 PM</option>
                          <option value="<?=$t?>:30:00"><?=$i?>:30 PM</option>
                          <?php $t++;?>
                        <?php } ?>
                      </optgroup>
                  </select>
                </div>
              </div>

               <div class="form-group mb-2">
                <label class="control-label">UV Express </label>
                <select class="selectpicker form-control" 
                      title="Select Uv Express" 
                      data-width="100%" 
                      data-live-search="true" 
                      id="uvId" 
                      v-model="tripFormData.uvId">
                  <?php foreach ($uv_unit as $uv) : ?>
                    <option 
                      value="<?= $uv->uv_id ?>" 
                      data-subtext="<?= $uv->brand_name ?> <?= $uv->model ?>" 
                      data-tokens="<?= $uv->brand_name ?>,
                                   <?= $uv->model ?>, 
                                   <?= $uv->plate_no ?>">
                      <?= $uv->plate_no ?>
                    </option>
                  <?php endforeach ?>
                </select>
              </div>

              <div class="form-group mb-2">
                <label class="control-label">Driver</label>
                <select class="selectpicker form-control" 
                      title="Select Driver" 
                      data-width="100%" 
                      data-live-search="true"
                      id="driverId"
                      v-model="tripFormData.driverId">
                  <?php foreach ($employee as $driver) : ?>
                    <option 
                      value="<?= $driver->employee_id ?>" 
                      data-subtext="Employee ID: <?= $driver->employee_id ?>" 
                      data-tokens="<?= $driver->f_name ?>,
                                   <?= $driver->l_name ?>">
                      <?= $driver->f_name.' '.$driver->l_name ?>
                    </option>
                  <?php endforeach ?>
                </select>
              </div>
            <?= form_close() ?>
          </div>
          <div class="modal-footer">
            <a class="btn btn-info btn-circle btn-sm text-white ml-0 mr-auto" data-toggle="modal" data-target="#help"title="Help">
              <i  class="fas fa-question"></i>
            </a>
            <button class="btn btn-outline-primary btn-sm" type="button" v-on:click="submitFormTrip">
              Save Changes
            </button>
            <button type="button" 
                    class="btn btn-outline-danger btn-sm" 
                    v-on:click="clearFormTrip('clear')"
                    v-if="tripFormData.mode === 'Add'">
              Clear
            </button>
            <button class="btn btn-outline-secondary btn-sm" type="button" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End update trip -->

    <!-- Map modal -->
    <div class="modal fade" id="mapModal" role="dialog" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg mt-4">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title font-weight-bold">{{ route.origin }} to {{ route.destination }} Trip# {{ selectedRowData.value[0] }}</h6>
            <button class="close data-map" type="button">&times</button>
          </div>
          <div class="modal-body" id="googleMap" style="height: 400px">
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End map modal -->
    <!------------------------------------------------------------------------
     | END MODAL
     ------------------------------------------------------------------------>
  </div>
  <!-- End container-fluid -->

</div>
<!-- End of Main Content -->

<?php $this->load->view('partials/footer');?>
<script type="text/javascript">
  var TRIP_STATUS = '<?= $status ?>';
  var ROUTE_ID = '<?= $route->route_id ?>';
  var ROUTE_NAME = '<?= $route->route_name ?>';
  var ORIGIN = '<?= $route->origin ?>';
  var DESTINATION = '<?= $route->destination ?>';
  var TRIP_DATE = '<?= $date ?>';
</script>
<script type="text/javascript" src="<?= base_url('assets/js/app/trip_management/trip-view.js') ?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDniqAbGD4phpzXC4owCA9bkJK5PdnUdvA&callback=G_MAP"></script>

