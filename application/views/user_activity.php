<?php $this->load->view('partials/header');?>
  <!-- Begin Page Content -->
  <div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mt-3 text-gray-800"><?php echo $pageTitle?></h1>
    <hr>

    <!-- DataTales Example -->
    <div class="card mb-4">
      <div class="card-header py-3">
        <div class="btn-group" style="float: right;">
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
          <table class="table table-bordered table-striped text-gray-800 filtered"  id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>#</th>
                <th>Activity</th>
                <th>Created-By</th>
                <th>Creator-Role</th>
                <th>Created-On</th>
                <th>Record ID</th>
                <th>Action</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>#</th>
                <th>Activity</th>
                <th>Created-By</th>
                <th>Creator-Role</th>
                <th>Created-On</th>
                <th>Record ID</th>
                <th>Action</th>
              </tr>
            </tfoot>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- End container-fluid -->
</div>
<!-- End of Main Content -->

<div class="modal fade" id="logData">
  <div class="modal-dialog modal-md mt-3">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="title">Recorded Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="recordData">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('partials/footer');?>
<script type="text/javascript" src="<?= base_url('assets/js/app/user-activity.js') ?>"></script>