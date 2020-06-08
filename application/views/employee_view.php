<?php $this->load->view('partials/header'); ?>
  <!-- Begin Page Content -->
  <div class="container-fluid" id="app">
    <!-- Page Heading -->
    <h1 class="h3 mt-3 text-gray-800">Manage <?= $view ?>'s</h1>
    <hr>
    <!-- Driver card -->
    <div class="card mb-4">
      <div class="card-header py-3 d-flex justify-content-between">
        <button class="btn btn-outline-info btn-sm" id="btnAddDriver">
          <i class="fa fa-plus"></i>
          Add <?= $view ?>
        </button>
        <div class="btn-group ml-auto mr-0">
          <button class="btn btn-outline-secondary btn-sm" id="d-btnPrint" rel="tooltip" data-toggle="tooltip" title="Print">
            <i class="fas fa-print fa-fw text-primary"></i>
          </button>
          <button class="btn btn-outline-secondary btn-sm" id="d-btnExcel" rel="tooltip" data-toggle="tooltip" title="Excel">
            <i class="fas fa-file-excel fa-fw text-primary"></i>
          </button>
          <button class="btn btn-outline-secondary btn-sm" id="d-btnPdf" rel="tooltip" data-toggle="tooltip" title="Pdf">
            <i class="fas fa-file-pdf fa-fw text-primary"></i>
          </button>
          <button class="btn btn-outline-secondary btn-sm" id="d-btnCsv" rel="tooltip" data-toggle="tooltip" title="Csv">
            <i class="fas fa-file fa-fw text-primary"></i>
          </button>
        </div> 
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-gray-800"  id="empTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Contact No.</th>
                <th>License No.</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Contact No.</th>
                <th>License No.</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </tfoot>
            <tbody>  
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- End Driver card -->

    <!------------------------------------------------------------------------
     | MODAL
     ------------------------------------------------------------------------>

    <!-- Add uv express modal -->
    <div class="modal fade" id="formModal">
      <div class="modal-dialog modal-lg mt-4">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h6 class="modal-title font-weight-bold">{{ formMode }} <?= $view ?></h6>
            <button type="button" class="close" title="Close" data-dismiss="modal" v-on:click="clearFields">&times;</button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <!-- Form -->
            <?= form_open('', 'id="appForm"') ?>

              <div class="row">
                <div class="col-md-8">
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label>First Name</label>
                      <input v-bind:disabled="formMode === 'Update'" class="form-control" id="f_name" placeholder="Enter first name" v-model="form.f_name">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Middle Name</label>
                      <input v-bind:disabled="formMode === 'Update'" class="form-control" id="m_name" type="text" placeholder="Enter middle name" v-model="form.m_name">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label>Last Name</label>
                      <input v-bind:disabled="formMode === 'Update'" class="form-control" id="l_name" type="text" placeholder="Enter last name" v-model="form.l_name">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Mobile Number</label>
                      <input class="form-control" id="contact_no" type="text" placeholder="Enter mobile #" v-model="form.contact_no">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Driver License #</label>
                    <input v-bind:disabled="formMode === 'Update' || view === 'Clerk'" class="form-control" id="license_no" type="text" placeholder="Enter license #" v-model="form.license_no">
                  </div>
                </div>
                <!-- User image -->
                <div class="col-md-4 d-flex align-items-end">
                  <img class="rounded border-gray mb-3" width="198px" height="198px" src="" id="userImg">
                  <input type="file" id="imgInput" class="invisible position-absolute" name="image_src">
                  <div class="d-flex flex-column ml-2 mt-4 mb-auto">
                    <button type="button" class="btn btn-outline-secondary btn-sm mb-2" id="addUserImg" title="Add Image">
                      <i class="fa fa-image"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" id="removeUserImg" title="Remove Image">
                      <i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Address</label>
                <textarea class="form-control" placeholder="Enter address" id="address" v-model="form.address"></textarea>
              </div>

              <div class="row" v-if="formMode === 'Add'">
                <div class="form-group col-md-6">
                  <label>Account Password</label>
                  <input class="form-control" id="password" type="password" placeholder="Enter password" v-model="form.password">
                </div>
                <div class="form-group col-md-6">
                  <label>Retype Password</label>
                  <input class="form-control" id="cpassword" type="password" placeholder="Retype password" v-model="form.cpassword">
                </div>
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
    <!------------------------------------------------------------------------
     |END  MODAL
     ------------------------------------------------------------------------>

  </div>
  <!-- End container-fluid -->
</div>
<!-- End of Main Content -->
<?php $this->load->view('partials/footer');?>
<script type="text/javascript">var VIEW = '<?= $view ?>'</script>
<script type="text/javascript" src="<?= base_url('assets/js/app/driver.js') ?>"></script>