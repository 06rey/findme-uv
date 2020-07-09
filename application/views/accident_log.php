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
    <div class="card-header">
      <button type="button" class="btn btn-outline-danger btn-sm" id="contactModalToggle">Emergency Contact List</button>
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
              <th>Driver Name</th>
              <th>Trip No.</th>
              <th>Plate No</th>
              <th>Speed</th>
              <th>Acceleration</th>
              <th>Date of Accident</th>
              <th>Location</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>#</th>
              <th>Driver Name</th>
              <th>Trip No.</th>
              <th>Plate No</th>
              <th>Speed</th>
              <th>Acceleration</th>
              <th>Date of Accident</th>
              <th>Location</th>
            </tr>
          </tfoot>
          <tbody>
            <?php foreach($all_logs as $logs ):?>
              <tr>
                <td><?= $logs->accident_id ?></td>
                <td><?php echo $logs->f_name.' '.$logs->l_name; ?></td>
                <td><?php echo $logs->trip_id?></td>
                <td><?php echo $logs->plate_no; ?></td>
                <td><?php echo $logs->speed.' kph' ?></td>
                <td><?php echo $logs->g_force; ?></td>
                <td><?php echo $logs->date_added; ?></td>
                <td>
                  <button class="btn btn-outline-info btn-sm" data-lat="<?=$logs->lat?>" data-lng="<?=$logs->lng?>" data-toggle="modal" data-target="#gmapModal" title="View Accident Location">
                    <i class="fa fa-map-marker"></i> 
                  </button>
                </td>
              </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- MAP MODAL -->
  <div class="modal fade" id="gmapModal">
    <div class="modal-dialog modal-xl mt-3">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="title">UV Express Accident Location</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" id="googleMap" style="height: 400px">
          <!-- <div class="row align-items-center justify-content-center h-100" id="mapLoading">
            <div class="spinner-border text-secondary">
              <span class="sr-only">Loading...</span>
            </div>
          </div> -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!--END MAP MODAL -->

  <!-- Accident contact modal -->
  <div class="modal fade" id="contactListModal" data-backdrop="static">
    <div class="modal-dialog modal-xl mt-4">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-bold">Emergency Contact List</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body mb-4" style="max-height: 400px">
          <button class="btn btn-outline-info btn-sm mb-4 position-absolute" id="addContactToggle">
            Add Emergecy Contact Number
          </button>
          <div class="table-responsive">
            <table class="table table-bordered table-striped text-gray-800"  id="contactTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Route</th>
                  <th>Description</th>
                  <th>Contact #</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Accident contact modal -->

  <!-- Add/Update contact modal -->
  <div class="modal fade" id="contactForm">
    <div class="modal-dialog modal-md mt-6">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-bold" id="contactModalTitle"></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="formContact">
            <div class="form-group mb-1">
              <label class="control-label">Route</label>
              <select class="form-control" id="routeId">
                <option value="select">--Select Route--</option>
              <?php foreach ($route as $key => $value) : ?>
                <option value="<?= $value->route_id ?>"><?= $value->origin.' to '.$value->destination ?></option>
              <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group mb-1">
              <label class="control-label">Emergency Contact Number</label>
              <input class="form-control" type="text" placeholder="Enter contact number" id="contactNo"> 
            </div>
            <div class="form-group">
              <label class="control-label">Description</label>
              <textarea class="form-control" placeholder="Enter description" id="description"></textarea> 
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info btn-sm" id="btnSaveContact">Save Changes</button>
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!--End Add/Update contact modal -->
</div>
<!-- End container-fluid -->
</div>
<!-- End of Main Content -->

<?php $this->load->view('partials/footer');?>

<script type="text/javascript">

(function(){
  _allRouteTripCallback = ()=>{};
  var contactData = [];
  var selIndex = null;
  const colReport = [0, 1, 2, 3, 4, 5, 6];
  var formMode = 'Add';

  $('#dataTable').DataTable({
    responsive: true,
    stateSave: true,
    pageLength: 10,
    dom: 'Blfrtip',
    buttons: [{
      extend: 'print',
      title: `Accident Logs`,
      exportOptions: {
        columns: colReport
      }
    },{
      extend: 'excel',
      title: `Accident Logs`,
      exportOptions: {
        columns: colReport
      }
    },{
      extend: 'csv',
      title: `Accident Logs`,
      exportOptions: {
        columns: colReport
      }
    },{
      extend: 'pdf',
      title: `Accident Logs`,
      exportOptions: {
        columns: colReport
      }
    }],
  });

  // Print/dowload button
  $('#btnPrint').click(()=>{
   $('#dataTable_wrapper').find('.buttons-print').click();
  });
  $('#btnExcel').click(()=>{
    $('#dataTable_wrapper').find('.buttons-excel').click();
  });
  $('#btnCsv').click(()=>{
    $('#dataTable_wrapper').find('.buttons-csv').click();
  });
  $('#btnPdf').click(()=>{
    $('#dataTable_wrapper').find('.buttons-pdf').click();
  });

  var contactTable = $('#contactTable').DataTable({
    responsive: true,
    stateSave: true,
    pageLength: 10,
    scrollY: "45vh",
    paging: false,
    scrollCollapse: true,
    dom: 'Blfrtip',
    ajax: {
      url: `${BASE_URL}logs/allContact`,
      dataSrc: function(json){
        contactData = json.data;
        return json.data;
      }
    },
    columns: [
      {'data': 'contact_id'},
      {'data': ''},
      {'data': 'description'},
      {'data': 'contact_no'},
    ],
    columnDefs: [{
      targets: 1,
      render: function(data, type, row, meta){
        return `${row.origin} <span class="ml-2 mr-2">&#8644;</span> ${row.destination}`;
      }
    },{
      targets: 4,
      render: function(data, type, row, meta){
        let badge = 'badge-danger';
        if (row.status === 'Active'){
          badge = 'badge-success';
        }
        return `<span class="badge ${badge}">${row.status}</span>`
      }
    },{
      targets: 5,
      render: function(data, type, row, meta){
        html = `<div class="btn-group">
                  <button class="btn btn-outline-info btn-sm" data-update="${meta.row}" title="Update">
                    <i class="fa fa-edit"></i>
                  </button>`;
        if (row.status === 'Active'){
          html += `<button class="btn btn-outline-secondary btn-sm" data-status="${meta.row}//${row.status}" title="Deactivate">
                    <i class="fa fa-times"></i>
                  </button>`;
        }else{
          html += `<button class="btn btn-outline-success btn-sm" data-status="${meta.row}//${row.status}" title="Activate">
                    <i class="fa fa-check"></i>
                  </button>`;
        }
        html += `<button class="btn btn-outline-danger btn-sm" data-delete="${meta.row}" title="Delete">
                    <i class="fa fa-trash"></i>
                  </button>
                </div>`;
        return html;
      }
    }]
  });

  $('#contactTable_wrapper').find('.dataTables_info').hide();

  $('#contactModalToggle').click(function(){
    $('#contactListModal').modal('show');
  });

  $('#contactListModal').on('shown.bs.modal', function(){
    $('#contactTable').trigger('resize');
  });

  $('#contactListModal').on('hidden.bs.modal', function(){
   clearFields();
  });

  $('#contactForm').on('hidden.bs.modal', function(){
    $('body').addClass('modal-open');
    if (formMode === 'Update'){
      clearFields();
      $('#routeId').attr('disabled', false);
    }
  });

  $(document).on('click', '[data-status]', function(){
    let arr = $(this).data('status').split('//');

    _confirm( 
      `Are you sure you want to ${arr[1] === 'Active' ? 'Deactivate' : 'Activate'} this emergency contact number?`,
      function(){
        _showLoading();
        $.ajax({
          url: `${BASE_URL}logs/contactStatus`,
          type: 'POST',
          dataType: 'json',
          data: {contact_id: contactData[arr[0]].contact_id, status: arr[1] === 'Active' ? 'Deactivated' : 'Active'},
          error: function(){
            _hideLoading();
            $.alert({
              title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
              content: `Failed to update contact status.`,
            });
          },
          success: function(resp){
            _hideLoading();
            if (resp.data){
              _success({
                title: 'Success',
                content: 'Emergency contact number status successfully updated.',
                confirm: {
                  text: 'Ok',
                  btnClass: 'btn-blue',
                  action: ()=>{
                    contactTable.ajax.reload(null, false);
                  }
                }
              });
            }
          }
        });
      },
      ()=>{}
    );
  });

  $(document).on('click', '[data-update]', function(){
    selIndex = $(this).data('update');
    formMode = 'Update';
    $('#contactModalTitle').text('Update Emergency Contact Number');
    $('#routeId').attr('disabled', true);
    $('#routeId').val(contactData[selIndex].route_id);
    $('#contactNo').val(contactData[selIndex].contact_no);
    $('#description').val(contactData[selIndex].description);
    $('#contactForm').modal('toggle');
  });

  $(document).on('click', '[data-delete]', function(){
    selIndex = $(this).data('delete');
    _confirm( 
      `Are you sure you want to delete this emergency contact number?`,
      function(){
        _showLoading();
        $.ajax({
          url: `${BASE_URL}logs/deleteContact`,
          type: 'POST',
          dataType: 'json',
          data: { contact_id: contactData[selIndex].contact_id },
          error: function(){
            _hideLoading();
            $.alert({
              title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
              content: `Failed to delete contact.`,
            });
          },
          success: function(resp){
            _hideLoading();
            if (resp.data){
              _success({
                title: 'Success',
                content: 'Emergency contact number successfully deleted.',
                confirm: {
                  text: 'Ok',
                  btnClass: 'btn-blue',
                  action: ()=>{
                    contactTable.ajax.reload(null, false);
                  }
                }
              });
            }else{
              $.alert({
                title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
                content: `Failed to delete contact.`,
              });
            }
          }
        });
      },
      ()=>{}
    );
  });

  $('#addContactToggle').click(function(){
    formMode = 'Add';
    $('#routeId').attr('disabled', false);
    $('#contactModalTitle').text('Add Emergency Contact Number');
    $('#contactForm').modal('toggle');
  });


  $('#btnSaveContact').click(function(){
    if ($('#routeId').val() === 'select'){
      $('#routeId').addClass('is-invalid');
      toast('Required', 'Route is required!', 'danger');
      return false;
    }
    if ($('#contactNo').val().trim() === ''){
      $('#contactNo').addClass('is-invalid');
      toast('Required', 'Contact number is required!', 'danger');
      return false;
    }

    if ($('#description').val().trim() === ''){
      $('#description').addClass('is-invalid');
      toast('Required', 'Description is required!', 'danger');
      return false;
    }
    $('#routeId').removeClass('is-invalid');
    $('#contactNo').removeClass('is-invalid');
    $('#description').removeClass('is-invalid');

    _showLoading();
    $.ajax({
      url: `${BASE_URL}/logs/${formMode === 'Add' ? 'saveContact' : 'updateContact'}`,
      type: 'POST',
      dataType: 'json',
      data: {
        routeId: $('#routeId').val(),
        contactNo: $('#contactNo').val(),
        description: $('#description').val(),
        contactId: formMode === 'Update' ? contactData[selIndex].contact_id : 0
      },
      error: function(){
        _hideLoading();
        $.alert({
          title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
          content: `Failed to save emergency contact.`,
        });
      },
      success: function(resp){
        _hideLoading();
        if (resp.data){
          _success({
            title: 'Success',
            content: 'Emergency contact number successfully save.',
            confirm: {
              text: 'Ok',
              btnClass: 'btn-blue',
              action: ()=>{
                formMode === 'Add' ? clearFields() : $('#contactForm').modal('toggle');
                contactTable.ajax.reload(null, false);
              }
            }
          });
        }else{
          $.alert({
            title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
            content: `No changes made.`,
          });
        }
      }
    });

  });

  function clearFields(){
    $('#routeId').val('select');
    $('#contactNo').val('');
    $('#description').val('');
  }


  /*
  |--------------------------------------------------------------------------
  | Google Maps
  |--------------------------------------------------------------------------
  */

  var lt = 11.240988, lg = 125.002026;
  var uvLocMarker = null;
  G_MAP = function() {
    // GOOGLE MAP PROPERTIES
    var mapProp= {
      center:new google.maps.LatLng(lt,lg),
      zoom:10,
      mapTypeId: "OSM",
      mapTypeControl: false,
      streetViewControl: false
    };
    var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);


    // Coat google map with OSM map
    map.mapTypes.set("OSM", new google.maps.ImageMapType({
      getTileUrl: function(coord, zoom) {
      return "https://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
    },
      tileSize: new google.maps.Size(256, 256),
      name: "OpenStreetMap",
      maxZoom: 18
    }));

    // Adding main terminal marker
    function addMarker(lat, lng) {
      loc = new google.maps.LatLng(lat, lng);
      uvLocMarker = new google.maps.Marker({
        position: loc,
        map: map,
        title: 'UV Express Accident Location'
      });
    }

    google.maps.event.addDomListener(window, "load", null);

    $('[data-lat]').click(function(){
      if (uvLocMarker != null){
        uvLocMarker.setMap(null);
      }
      const lat = $(this).data('lat');
      const lng = $(this).data('lng');
      addMarker(lat, lng);
    });
  }



})();

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDniqAbGD4phpzXC4owCA9bkJK5PdnUdvA&callback=G_MAP"></script>
