<?php $this->load->view('partials/header');?>


  <!-- Begin Page Content -->
  <div class="container-fluid" id="app">

    <!-- Page Heading -->
    <h1 class="h3 mt-3 text-gray-800"><?php echo $pageTitle?></h1>
    <hr>

    <!-- DataTales Example -->
    <div class="card mb-4">
      <div class="card-header py-3 d-flex justify-content-between">
        <button class="btn btn-outline-info btn-sm" id="btnAddUv">
          <i class="fa fa-plus"></i>
          Add UV Express
        </button>
        <div class="btn-group ml-auto mr-0">
          <button class="btn btn-outline-secondary btn-sm" id="uv-btnPrint" rel="tooltip" data-toggle="tooltip" title="Print">
            <i class="fas fa-print fa-fw text-primary"></i>
          </button>
          <button class="btn btn-outline-secondary btn-sm" id="uv-btnExcel" rel="tooltip" data-toggle="tooltip" title="Excel">
            <i class="fas fa-file-excel fa-fw text-primary"></i>
          </button>
          <button class="btn btn-outline-secondary btn-sm" id="uv-btnPdf" rel="tooltip" data-toggle="tooltip" title="Pdf">
            <i class="fas fa-file-pdf fa-fw text-primary"></i>
          </button>
          <button class="btn btn-outline-secondary btn-sm" id="uv-btnCsv" rel="tooltip" data-toggle="tooltip" title="Csv">
            <i class="fas fa-file fa-fw text-primary"></i>
          </button>
        </div> 
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-gray-800"  id="uvTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>#</th>
                <th>Plate No.</th>
                <th>Franchise No.</th>
                <th>Model</th>
                <th>Brand</th>
                <th>Max. Pass.</th>
                <th>Action</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>#</th>
                <th>Plate No.</th>
                <th>Franchise No.</th>
                <th>Model</th>
                <th>Brand</th>
                <th>Max. Pass.</th>
                <th>Action</th>
              </tr>
            </tfoot>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- Add uv express modal -->
    <div class="modal fade" id="formModal">
      <div class="modal-dialog mt-4">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h6 class="modal-title font-weight-bold">{{ formMode }} UV Express</h6>
            <button type="button" class="close" title="Close" data-dismiss="modal" v-on:click="clearFields">&times;</button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <!-- Form -->
            <?= form_open('trip_management/add_validation', 'id="appForm"') ?>

              <div class="row">
                <div class="form-group col-md-6">
                  <label>Plate Number</label>
                  <input class="form-control" id="plate_no" placeholder="Enter plate number" v-model="form.plate_no">
                </div>

                <div class="form-group col-md-6">
                  <label>Passenger Capacity</label>
                  <input class="form-control" id="max_pass" type="number" placeholder="Enter passenger apacity" v-model="form.max_pass">
                </div>
              </div>

              <div class="form-group">
                <label>Franchise Number</label>
                <input class="form-control" id="franchise_no" type="text" placeholder="Enter franchise number" v-model="form.franchise_no">
              </div>

              <div class="form-group">
                <label>Unit Model</label>
                <input class="form-control" id="model" type="text" placeholder="Enter unit model" v-model="form.model">
              </div>

              <div class="form-group">
                <label>Brand Name</label>
                <input class="form-control" id="brand_name" type="text" placeholder="Enter brand name" v-model="form.brand_name">
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
                    v-on:click="submitForm">
              Save Changes
            </button>
            <button type="button" 
                    class="btn btn-outline-danger btn-sm"
                    v-on:click="clearFields"
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
    <!-- End uv express  modal -->

  </div>
  <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php $this->load->view('partials/footer');?>
<script type="text/javascript" src="<?= base_url('assets/js/app/uv-express.js') ?>"></script>






