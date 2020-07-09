(function(){

  /*
  |--------------------------------------------------------------------------
  | Google Map
  |--------------------------------------------------------------------------
  */
  
  G_MAP = function(){
    var routePath = null;
    var arrPath = [];
    var arrEdit = {do: false, path: []};
    var subTerminal = {
      marker: null,
      title: null,
      isSet: false,
      location: null
    };
    var editingRoute = false;    

    var mapProp = {
      center: new google.maps.LatLng(LOCATION.lat, LOCATION.lng),
      zoom: 10,
      mapTypeId: "OSM",
      mapTypeControl: false,
      streetViewControl: false
    };
    var map = new google.maps.Map(document.querySelector("#googleMap"), mapProp);

    // Coat google map with OSM map
    map.mapTypes.set("OSM", new google.maps.ImageMapType({
        getTileUrl: function(coord, zoom) {
        return "https://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
    },
        tileSize: new google.maps.Size(256, 256),
        name: "OpenStreetMap",
        maxZoom: 100
    }));

    // Google map events
    google.maps.event.addDomListener(window, "load", function(){ 
      // Set main terminal marker
      let infoWindow = new google.maps.InfoWindow({
        content: `<div>
                   <span class="modal-title font-weight-bold">Duptours Main Terminal Tacloban City</span>
                  </div>`
      });
      let marker = new google.maps.Marker({
          position: LOCATION,
          map: map,
          title: 'Duptours Main Terminal Tacloban City'
      });
      marker.addListener('click', function(){
        infoWindow.open(map, marker);
      });

    });

    google.maps.event.addListener(map, "click", function(e){ // Map click
      if (routePath === null){

        if ((subTerminal.marker === null) || (!subTerminal.isSet)){
          if (subTerminal.marker !== null){
            subTerminal.marker.setMap(null);
          }
          subTerminal.marker = setSubTerminalMarker({
            title: `Duptors Terminal ${subTerminal.title}`,
            location: new google.maps.LatLng(e.latLng.lat(), e.latLng.lng())
          });

          subTerminal.marker.addListener('click', function(){ // Start marker click

            if (!subTerminal.isSet){
              _confirm(
                `Set as ${subTerminal.title} Duptours Terminal location?`,
                function(){
                  subTerminal.isSet = true;
                  subTerminal.location = {lat: e.latLng.lat(), lng: e.latLng.lng()};
                  arrPath.push(subTerminal.location);
                  arrEdit.path.push(subTerminal.location);
                  setArrPath(arrPath);
                  apps.disableDraw = false;
                  _alert(
                    'Information',
                    `Click "Draw lines" button and start clicking direction from ${subTerminal.title} Terminal marker to Tacloban City Terminal marker and click save if finished.`,
                    'blue'
                  );
                },
                ()=>{}
              );
            }
          }); // End marker click
        }
      }else{
        if (apps.disableSave){
          if (apps.drawingLines){

            let distanceInMeters = google.maps.geometry.spherical.computeDistanceBetween(
              new google.maps.LatLng(arrEdit.path[arrEdit.path.length - 1].lat, arrEdit.path[arrEdit.path.length - 1].lng),
              new google.maps.LatLng(e.latLng.lat(), e.latLng.lng())
            );

            if (distanceInMeters < 501){
              if (arrEdit.do){
                let arrPathLen = arrPath.length;
                for(var i=arrEdit.path.length; i<arrPathLen; i++){
                  arrPath.pop();
                }
              }

              arrPath.push({lat: e.latLng.lat(), lng: e.latLng.lng()});
              arrEdit.path.push({lat: e.latLng.lat(), lng: e.latLng.lng()});
              setArrPath(arrPath);
              apps.disableClear = false;
              apps.disableUndo = !(arrPath.length > 1);
              apps.disableRedo = true;
              arrEdit.do = false;
            }else{
              toast('Information', 'Line distance from previous point is too long. Try again.', 'warn');
            }

            let distanceToMain  = google.maps.geometry.spherical.computeDistanceBetween(
              new google.maps.LatLng(arrEdit.path[arrEdit.path.length - 1].lat, arrEdit.path[arrEdit.path.length - 1].lng),
              new google.maps.LatLng(LOCATION.lat, LOCATION.lng)
            );

            if (distanceToMain < 20){
              apps.disableSave = false;
              $('#btnMapEdit').click();
              apps.disableDraw = true;
              arrPath.push(LOCATION);
              setArrPath(arrPath);
              _alert('Information', `You have reached Tacloban City Duptours Terminal map marker. Click "Save" button to save route direction.`, 'blue');
            }
          }
        }
      }
    }); // End map click

    function setArrPath(path){
      if (routePath !== null){
        routePath.setMap(null);
      }
      routePath = new google.maps.Polyline({
        path: path,
        geodesic: true,
        strokeColor: '#2b5797',
        strokeOpacity: 1.0,
        strokeWeight: 2
      });
      routePath.setMap(map);
    }

    /*
    |--------------------------------------------------------------------------
    | Map edit control button
    |--------------------------------------------------------------------------
    */
   
    $('#btnSaveRoute').click(function(){
      _showLoading();
      if (apps.selectedRow.value[1].includes('Tacloban City')){
        originLoc = LOCATION;
        destinationLoc = arrPath[0];
      }else{
        originLoc = arrPath[0];
        destinationLoc = LOCATION;
      }
      console.log(JSON.stringify(destinationLoc));
      $.ajax({
        url: `${BASE_URL}trip_management/setMapRoute`,
        type: 'POST',
        dataType: 'json',
        data: {
          route_id: apps.selectedRow.value[0],
          origin: JSON.stringify(originLoc),
          destination: JSON.stringify(destinationLoc),
          path: JSON.stringify(arrPath)
        },
        error: function(){
          _hideLoading();
          toast('Failed', 'Failed to save route direction. Something went wrong.', 'danger');
        },
        success: function(){
          _hideLoading();
          toast('Success', 'Route direction successfully saved.', 'success');
        }
      });
    });

    $('#btnMapClear').click(function(){
      _confirm(
        'Are you sure you want to clear marker and direction?',
        function(){
          arrPath = [];
          arrEdit = {do: false, path: []};
          if (routePath !== null){
            routePath.setMap(null);
          }
          routePath = null;
          subTerminal.isSet = false;
          apps.disableUndo = true;
          apps.disableRedo = true;
          apps.disableClear = true;
          apps.drawingLines = false;
          apps.disableDraw = true;
        },
        ()=>{}
      );
    });
   
    $('#btnMapRedo').click(function(){
      arrEdit.path.push(arrPath[arrEdit.path.length]);
      setArrPath(arrEdit.path);
      apps.disableRedo = (arrEdit.path.length === arrPath.length);
      arrEdit.do = true;
      apps.disableUndo = false;
    });
   
    $('#btnMapUndo').click(function(){
      arrEdit.path.pop();
      setArrPath(arrEdit.path);
      apps.disableUndo = (arrEdit.path.length < 2);
      arrEdit.do = true;
      apps.disableRedo = false;
    });
   
    $('#btnMapEdit').click(function(){
      if (apps.drawingLines){
        apps.disableUndo = true;
        apps.disableRedo = true;
        apps.disableClear = true;
      }else{
        apps.disableUndo = (arrEdit.path.length < 2);
        apps.disableRedo = (arrEdit.path.length === arrPath.length);
      }
      apps.drawingLines = !apps.drawingLines;
    });
   
    function initMapControls(){
      apps.drawingLines = false;
      apps.disableDraw = true;
      apps.disableRedo = true;
      apps.disableUndo = true;
      apps.disableBackspace = true;
      apps.disableSave = true;
      apps.disableClear = true;

      if (subTerminal.marker !== null){
        subTerminal.marker.setMap(null);
      }

      if (routePath !== null){
        routePath.setMap(null);
      }

      subTerminal = {
        marker: null,
        title: null,
        isSet: false
      };
      routePath = null;
      arrPath = [];
      arrEdit = {do: false, path: []};
    }
   
    $('.data-map').click(function(){
      if (editingRoute){
        _confirm(
          'Are you sure you want to close this window? Progress will not be saved.',
          function(){
            initMapControls();
            editingRoute = false;
            $('#mapModal').modal('toggle');
          },
          ()=>{}
        );
      }else{
        initMapControls();
        editingRoute = false;
        $('#mapModal').modal('toggle');
      }
    });

    /*
    |--------------------------------------------------------------------------
    | Map route methods
    |--------------------------------------------------------------------------
    */
   
    // Set route polyline
    function setRoutePath(path){
      routePath = new google.maps.Polyline({
        path: path,
        geodesic: true,
        strokeColor: '#2b5797',
        strokeOpacity: 1.0,
        strokeWeight: 2
      });
      routePath.setMap(map);
      _hideLoading();
      $('#mapModal').modal('toggle');
    }

    function setSubTerminalMarker(markerObj){
      // Set main terminal marker
      let infoWindow = new google.maps.InfoWindow({
        content: `<div>
                   <span class="modal-title font-weight-bold">${markerObj.title}</span>
                  </div>`
      });
      let marker = new google.maps.Marker({
          position: markerObj.location,
          map: map
      });
      marker.addListener('click', function(){
        infoWindow.open(map, marker);
      });
      return marker;
    }

    $(document).on('click', '.route-map', function(){
      apps.selectedRow.value = [];
      $(this).closest('tr').children().each(function(){
        apps.selectedRow.value.push($(this).text());
      });
      getRouteGeoPoints(apps.selectedRow.value[0]);
    });

    // Get route way points
    function getRouteGeoPoints(routeId){
      _showLoading();
      $.ajax({
        url: `${BASE_URL}trip_management/fetchRouteGeoPoints`,
        type: 'POST',
        dataType: 'json',
        data: {route_id: routeId},
        error: ()=>{
          _hideLoading();
          toast('Failed loading', 'Failed to load map direction. Something went wrong.', 'danger');
        },
        success: function(resp){
          if (resp.status){
            if (routePath !== null){
              routePath.setMap(null);
              routePath = null;
            }
            if (subTerminal.marker !== null){
              subTerminal.marker.setMap(null);
              subTerminal.marker = null;
            }

            if (resp.data[0].way_point !== null){
              resp.data[0].way_point.forEach(function(item, index){
                item.lat = parseFloat(item.lat);
                item.lng = parseFloat(item.lng);
              });
              if (resp.data[0].destination.includes('Tacloban City')){
                resp.data[0].origin_lat_lng.lat = parseFloat(resp.data[0].origin_lat_lng.lat);
                resp.data[0].origin_lat_lng.lng = parseFloat(resp.data[0].origin_lat_lng.lng);
                subTerminal.marker = setSubTerminalMarker({
                  title: `Duptors Terminal ${resp.data[0].origin}`,
                  location: resp.data[0].origin_lat_lng
                });
              }else{
                resp.data[0].destination_lat_lng.lat = parseFloat(resp.data[0].destination_lat_lng.lat);
                resp.data[0].destination_lat_lng.lng = parseFloat(resp.data[0].destination_lat_lng.lng);
                subTerminal.marker = setSubTerminalMarker({
                  title: `Duptors Terminal ${resp.data[0].destination}`,
                  location: resp.data[0].destination_lat_lng
                });
              }
              subTerminal.isSet = true;
              setRoutePath(resp.data[0].way_point);
            }else{
              if (apps.selectedRow.value[1].includes('Tacloban City')){
                subTerminal.title = apps.selectedRow.value[2];
              }else{
                subTerminal.title = apps.selectedRow.value[1];
              }
              _hideLoading();
              $('#mapModal').modal('toggle');
              setTimeout(function(){
                _alert(
                  'Information', 
                  `${apps.selectedRow.value[1] } to ${apps.selectedRow.value[2]} route direction is not set.
                    Click ${subTerminal.title} Duptors Terminal location on the map to start adding route direction.`, 
                  'blue'
                )
              }, 500);
              editingRoute = true;
            }
            map.setZoom(9);
            map.panTo(LOCATION);
          }
        }
      });
    }

  } // End google map method
 
  /*
  |--------------------------------------------------------------------------
  | Page Vue instance
  |--------------------------------------------------------------------------
  */
  var apps = new Vue({
    el: '#app',
    data: {

      routeTable: null,

      routeForm: {
        origin: '',
        destination: '',
        via: '',
        fare: ''
      },

      formMode: '',
      selectedRouteId: '',
      selectedRow: {
        id: [],
        value: []
      },

      // Route map button
      drawingLines: false,
      disableDraw: true,
      disableRedo: true,
      disableUndo: true,
      disableSave: true,
      disableClear: true,
      disableBackspace: true,
    },
    mounted: function(){
      let app = this;
      // Route table action button event
      $(document).on('click', '.route-edit', function(){
        app.showUpdateRouteForm($(this).attr('id'));
      });

      // Set route table
      this.setRouteDt();
      this.routeTable.column(3).visible(false);
      this.routeTable.column(4).visible(false);

      _allRouteTripCallback = function(data){
        $.each(data, function(){
          $(`#pending${this.route_id}`).text(this.count.pending);
          $(`#traveling${this.route_id}`).text(this.count.traveling);
          $(`#cancelled${this.route_id}`).text(this.count.cancelled);
          $(`#history${this.route_id}`).text(this.count.arrived);
        });
      }

      $('.form-control').change(function(){
        if ($(this).hasClass('is-invalid')){
          $(this).removeClass('is-invalid');
        }
      });

      $('#modalAddRoute').on('hidden.bs.modal', function () {
        app.selectedRouteId = '';
        app.clearFields();
      });
      
    },
    methods: {

      // Submit route form
      submitRouteForm: function(){
        let app = this;
        if (this.checkFields()){
          _showLoading();
          $.ajax({
            url: `${BASE_URL}trip_management/${this.formMode.toLowerCase()}Route`,
            type: 'POST',
            dataType: 'json',
            data: {
              route_id: this.selectedRouteId,
              origin: this.routeForm.origin,
              destination: this.routeForm.destination,
              via: this.routeForm.via,
              fare: this.routeForm.fare
            },
            error: ()=>{
              toast('Failed', 'Failed saving data. Something went wrong there.', 'danger');
              _hideLoading();
            },
            success: function(resp){
              _hideLoading();
              if (app.formMode === 'Add'){
                if (resp.status){
                  app.routeTable.ajax.reload(null, false);
                  $('#modalAddRoute').modal('toggle');
                  _alert('Success', 'New route successfully added.', 'green');
                }else{
                  _alert('Route Exists', `${app.routeForm.origin} to ${app.routeForm.destination} already exists.`, 'red');
                  $('#origin').addClass('is-invalid');
                  $('#destination').addClass('is-invalid');
                }
              }else{
                if (resp.data){
                  $('#modalAddRoute').modal('toggle');
                  app.routeTable.ajax.reload(null, false);
                  _alert('Success', `Route no. ${app.selectedRouteId} successfully updated.`, 'green');
                }else{
                  _alert('Information', 'No changes made.', 'blue');
                }
              }
            }
          });
        }
      },

      // Show route adding form
      showAddRoute: function(){
        this.formMode = 'Add';
        $('#modalAddRoute').modal('toggle');
      },

      // Show update route form modal
      showUpdateRouteForm: function(routeId){
        this.formMode = 'Update';
        this.selectedRouteId = routeId;
        let app = this;
        _showLoading();
        $.ajax({
          url: `${BASE_URL}trip_management/getRouteInfo/${routeId}`,
          type: 'GET',
          dataType: 'json',
          error: ()=>{
            _hideLoading();
            toast('Loading Failed', `Could'nt load route information. Something went wrong.`, 'danger');
          },
          success: (resp)=>{
            _hideLoading();
            if (resp.status){
              app.routeForm = resp.data[0];
              $('#modalAddRoute').modal('toggle');
            }
          }
        });
      },

      // Check fields if has clear values
      checkFields: function(){
        let pass = true;
        if (this.routeForm.origin.trim() === ''){
          $('#origin').addClass('is-invalid');
          this.routeForm.origin = '';
          toast('Required', 'Route origin is required!', 'danger');
          pass = false;
        }
        if (this.routeForm.destination.trim() === ''){
          $('#destination').addClass('is-invalid');
          this.routeForm.destination = '';
          toast('Required', 'Route destination is required!', 'danger');
          pass = false;
        }
        if (this.routeForm.via.trim() === ''){
          $('#via').addClass('is-invalid');
          this.routeForm.via = '';
          toast('Required', 'Route via is required!', 'danger');
          pass = false;
        }
        if (this.routeForm.fare.trim() === ''){
          $('#fare').addClass('is-invalid');
          this.routeForm.fare = '';
          toast('Required', 'Fare is required!', 'danger');
          pass = false;
        }
        return pass;
      },

      // Confirm clearing fields
      confirmClearFields: function(){
        _confirm(
          'Are you sure you want to clear all fields?',
          this.clearFields,
          ()=>{}
        );
      },

      // Clear fields
      clearFields: function(){
        this.routeForm.origin = '';
        this.routeForm.destination = '';
        this.routeForm.via = '';
        this.routeForm.fare = '';
        $('.form-control').each(function(){
          $(this).removeClass('is-invalid');
        });
      },
      // Table Setters
      setRouteDt: function(){
        let date = $.format.date(new Date(), 'yyyy-MM-dd')
        this.routeTable = $('#tblRoute').DataTable({
          responsive: true,
          stateSave: true,
          pageLength: 10,
          dom: 'Blfrtip',
          bSort: false,
          ajax: `${BASE_URL}trip_management/fetchRoute`,
          columns: [
              {'data':'route_id'},
              {'data':'origin'},
              {'data':'destination'},
              {'data':'via'},
              {'data':'fare'}
            ],
          columnDefs: [{
            targets: 5,
            render: function(data, type, row, meta){
              return `<a class="badge badge-info"
                        href="${BASE_URL}trip_management/trip/Pending/${row['route_id']}/${$.format.date(new Date(), 'yyyy-MM-dd')}">
                        Trip Schedule
                        <span class="badge badge-light" id="pending${row['route_id']}">0</span>
                      </a>`;
            }
          },{
            targets: 6,
            render: function(data, type, row, meta){
              return `<a class="badge badge-primary"
                        href="${BASE_URL}trip_management/trip/Traveling/${row['route_id']}/${$.format.date(new Date(), 'yyyy-MM-dd')}">
                        Traveling
                        <span class="badge badge-light" id="traveling${row['route_id']}">0</span>
                      </a>`;
            }
          },{
            targets: 7,
            render: function(data, type, row, meta){
              return `<a class="badge badge-danger"
                        href="${BASE_URL}trip_management/trip/Cancelled/${row['route_id']}/${$.format.date(new Date(), 'yyyy-MM-dd')}">
                        Cancelled
                        <span class="badge badge-light" id="cancelled${row['route_id']}">0</span>
                      </a>`;
            }
          },{
            targets: 8,
            render: function(data, type, row, meta){
              return `<a class="badge badge-success"
                        href="${BASE_URL}trip_management/trip/Arrived/${row['route_id']}/${$.format.date(new Date(), 'yyyy-MM-dd')}">
                        Trip History
                        <span class="badge badge-light" id="history${row['route_id']}">0</span>
                      </a>`;
            }
          },{
            targets: 9,
            render: function(data, type, row, meta){
              return `<div class="btn-group"> 
                        <button class="btn btn-outline-info btn-sm route-edit" id="${row['route_id']}" title="Update route">
                          <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm route-map" 
                              id="${row['route_id']}" 
                              title="Route Map">
                          <i class="fas fa-map-marker"></i>
                        </button>
                      </div>`;
            }
          }],
          buttons: [{
            extend: 'print',
            title: `<div class="d-flex align-items-center justify-content-between">
                      <h4 class="modal-title">Trip Route</h4>
                      <h5>Date: ${date}</h5>
                    </div>`,
            exportOptions: {
              columns: [0, 1, 2, 3, 4]
            }
          },{
            extend: 'excel',
            title: `Trip Route Date: ${date}`,
            exportOptions: {
              columns: [0, 1, 2, 3, 4]
            }
          },{
            extend: 'csv',
            title: `Trip Route Date: ${date}`,
            exportOptions: {
              columns: [0, 1, 2, 3, 4]
            }
          },{
            extend: 'pdf',
            title: `Trip Route Date: ${date}`,
            exportOptions: {
              columns: [0, 1, 2, 3, 4]
            }
          }]
        });

        // Print/dowload button
        $('#btnPrint').click(()=>{
         $('#tblRoute_wrapper').find('.buttons-print').click();
        });
        $('#btnExcel').click(()=>{
          $('#tblRoute_wrapper').find('.buttons-excel').click();
        });
        $('#btnCsv').click(()=>{
          $('#tblRoute_wrapper').find('.buttons-csv').click();
        });
        $('#btnPdf').click(()=>{
          $('#tblRoute_wrapper').find('.buttons-pdf').click();
        });
      }
    },
    // Computed properties
    computed: {
      isfieldsClear: function(){
        return (this.routeForm.origin === '' && this.routeForm.destination === '' && this.routeForm.via === '' && this.routeForm.fare === '');
      }
    }
  });

})();