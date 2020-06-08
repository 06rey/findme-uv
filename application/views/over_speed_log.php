<?php $this->load->view('partials/header');?>
  <!-- Begin Page Content -->
  <div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mt-3 text-gray-800"><?php echo $pageTitle?></h1>
    <hr>
    <!-- DataTales Example -->
    <div class="card mb-4">
      <div class="card-header d-flex">
        <div class="btn-group ml-auto mr-0">
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
          <table class="table table-bordered table-striped text-gray-800 filtered"  id="over_speed_table" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>#</th>
                <th>Driver Name</th>
                <th>Speed</th>
                <th>Trip No.</th>
                <th>Plate No.</th>
                <th>Date Added</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>#</th>
                <th>Driver Name</th>
                <th>Speed</th>
                <th>Trip No.</th>
                <th>Plate No.</th>
                <th>Date Added</th>
              </tr>
            </tfoot>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- End container-fluid -->
</div>
<!-- End of Main Content -->
<?php $this->load->view('partials/footer');?>
<script type="text/javascript">

$(function(){

  _allRouteTripCallback = ()=>{};

  var over_speed_table = $('#over_speed_table').DataTable({
    responsive: true,
    stateSave: true,
    pageLength: 10,
    dom: 'Blfrtip',
    ajax: {
      url: `${BASE_URL}logs/overSpeedLogs`,
    },
    columns: [
      {'data':'over_speed_id'},
      {'data':''},
      {'data':'speed'},
      {'data':'trip_id'},
      {'data':'plate_no'},
      {'data':'date_time'}
    ],
    columnDefs: [{
      targets: 1,
      render: function(data, type, row, meta){
        return `${row['f_name']} ${row['l_name']}`;
      }
    }],
    buttons: [{
      extend: 'print',
      title: `<h4 class="modal-title">Driver Over Speed</h4>`,
      exportOptions: {
        columns: [ 0, 1, 2, 3, 4, 5 ]
      }
    },{
      extend: 'excel',
      title: `<h4 class="modal-title">Driver Over Speed</h4>`,
      exportOptions: {
        columns: [ 0, 1, 2, 3, 4, 5 ]
      }
    },{
      extend: 'csv',
      title: `<h4 class="modal-title">Driver Over Speed</h4>`,
      exportOptions: {
        columns: [ 0, 1, 2, 3, 4, 5 ]
      }
    },{
      extend: 'pdf',
      title: `<h4 class="modal-title">Driver Over Speed</h4>`,
      exportOptions: {
        columns: [ 0, 1, 2, 3, 4, 5 ]
      }
    }]

  });

  // Print/dowload button
  $('#btnPrint').click(()=>{
   $('#over_speed_table_wrapper').find('.buttons-print').click();
  });
  $('#btnExcel').click(()=>{
    $('#over_speed_table_wrapper').find('.buttons-excel').click();
  });
  $('#btnCsv').click(()=>{
    $('#over_speed_table_wrapper').find('.buttons-csv').click();
  });
  $('#btnPdf').click(()=>{
    $('#over_speed_table_wrapper').find('.buttons-pdf').click();
  });

})

</script>





