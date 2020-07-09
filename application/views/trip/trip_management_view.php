<?php $this->load->view('partials/header');?>

  <!-- Begin Page Content -->
  <div class="container-fluid" id="app">
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
        <h5 class="text-gray-900 font-weight-bold">Trip Route</h5>
        <div class="btn-group ml-auto mr-0">
          <button class="btn btn-outline-info btn-sm" v-on:click="showAddRoute">
            <i class="fa fa-plus"></i>
            Add Trip Route
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
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-gray-800 filtered text-gray-900"  id="tblRoute" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>#</th>
                <th>From</th>
                <th>To</th>
                <th>Via</th>
                <th>Fare</th>
                <th>Trip Schedule</th>
                <th>Traveling</th>
                <th>Cancelled</th>
                <th>Trip History</th>
                <th>Action</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>#</th>
                <th>From</th>
                <th>To</th>
                <th>Via</th>
                <th>Fare</th>
                <th>Trip Schedule</th>
                <th>Traveling</th>
                <th>Cancelled</th>
                <th>Trip History</th>
                <th>Action</th>
              </tr>
            </tfoot>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
     <!-- End card -->

    <!------------------------------------------------------------------------
     | MODAL
     ------------------------------------------------------------------------>

    <!-- Add trip modal -->
    <div class="modal fade" id="modalAddRoute">
      <div class="modal-dialog mt-4">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h6 class="modal-title font-weight-bold">{{ formMode }} Trip Route</h6>
            <button type="button" class="close" title="Close" data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <!-- Form -->
            <?= form_open('trip_management/add_validation', 'id="fromTrip"') ?>
              <div class="form-group">
                <label>From</label>
                <input class="form-control" id="origin" type="text" placeholder="Enter route origin" v-model="routeForm.origin">
              </div>

              <div class="form-group">
                <label>To</label>
                <input class="form-control" id="destination" type="text" placeholder="Enter route destination" v-model="routeForm.destination">
              </div>

              <div class="form-group">
                <label>Via</label>
                <input class="form-control" id="via" type="text" placeholder="Enter route via" v-model="routeForm.via">
              </div>

              <div class="form-group">
                <label>Fare</label>
                <input class="form-control" id="fare" type="number" placeholder="Enter fare" v-model="routeForm.fare">
              </div>
            <?= form_close() ?>
            <!-- End form -->
          </div>
          <!-- Modal footer -->
          <div class="modal-footer">
            <a class="btn btn-info btn-circle btn-sm text-white ml-0 mr-auto" data-toggle="modal" data-target="#help"title="Help">
              <i  class="fas fa-question"></i>
            </a>
            <button type="submit"
                    class="btn btn-outline-primary btn-sm"
                    v-on:click="submitRouteForm">
              Save Changes
            </button>
            <button type="button" 
                    class="btn btn-outline-danger btn-sm"
                    v-bind:disabled="isfieldsClear"
                    v-on:click="confirmClearFields"
                    v-if="formMode === 'Add'">
              Clear
            </button>
            <button type="button" 
                    class="btn btn-outline-secondary btn-sm" 
                    data-dismiss="modal"
                    v-on:click="clearFields">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- End add trip modal -->

    <!-- Help Modal -->
    <div class="modal fade" id="help" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">How to Add Trip</h5>
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
    </div>
    <!-- End of Help Modal -->

    <!-- Map modal -->
    <div class="modal fade" id="mapModal" role="dialog" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg mt-4">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title font-weight-bold">{{ selectedRow.value[1] }} to {{ selectedRow.value[2] }} Route Direction</h6>
            <button class="close data-map" type="button">&times</button>
          </div>
          <div class="modal-body" id="googleMap" style="height: 400px">
        
          </div>
          <div class="modal-footer">
            <div class="mr-auto">
              <button v-bind:disabled="disableDraw" 
                    class="btn btn-sm" 
                    id="btnMapEdit" 
                    title="Draw lines"
                    v-bind:class="{'btn-success': drawingLines, 'btn-outline-info': !drawingLines}">
                <i class="fas fa-edit"></i>
              </button>
              <div class="btn-group mr-2 ml-2" id="btnMapGroup">
                <button v-bind:disabled="disableUndo" 
                      class="btn btn-outline-info btn-sm" 
                      id="btnMapUndo" 
                      title="Undo">
                  <i class="fas fa-undo"></i>
                </button>
                <button v-bind:disabled="disableRedo" 
                      class="btn btn-outline-info btn-sm" 
                      id="btnMapRedo" 
                      title="Redo">
                  <i class="fas fa-redo"></i>
                </button>
                <button v-bind:disabled="disableClear" 
                      class="btn btn-outline-danger btn-sm" 
                      id="btnMapClear" 
                      title="Clear">
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <button v-bind:disabled="disableSave" type="button" 
                    class="btn btn-outline-info btn-sm"
                    title="Save"
                    id="btnSaveRoute">
                    <i class="fa fa-save"></i>
              </button>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm data-map">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End map modal -->
    <!------------------------------------------------------------------------
     | END MODAL
     ----------------------------------------------------------------------->
  </div>
  <!-- End container-fluid -->
</div>
<!-- End of Main Content -->

<?php $this->load->view('partials/footer');?>
<script type="text/javascript" src="<?= base_url('assets/js/app/trip_management/trip.js') ?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDniqAbGD4phpzXC4owCA9bkJK5PdnUdvA&callback=G_MAP"></script>
