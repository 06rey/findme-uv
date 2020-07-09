(function(){

  /*
  |--------------------------------------------------------------------------
  | Google Map
  |--------------------------------------------------------------------------
  */
 
  G_MAP = function(){

    var routePath = null;
    var uvLocator = null;
    var uvExpressMarker = null;
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
      originMarker = setMarker({
        content: `Duptours Terminal ${app.route.origin}`,
        icon: null,
        location: app.routeMap.origin
      });

      destinationMarker = setMarker({
        content: `Duptours Terminal ${app.route.destination}`,
        icon: null,
        location: app.routeMap.destination
      });

      if (routePath !== null){
        routePath.setMap(null);
      }
      routePath = new google.maps.Polyline({
        path: app.routeMap.path,
        geodesic: true,
        strokeColor: '#2b5797',
        strokeOpacity: 1.0,
        strokeWeight: 2
      });
      routePath.setMap(map);
    });

    // Map methods
    
    // Map marker setter
    function setMarker(obj){
      let infoWindow = new google.maps.InfoWindow({
        content: `<div>
                   <span class="modal-title font-weight-bold">${obj.content}</span>
                  </div>`
      });
      let marker = new google.maps.Marker({
          position: obj.location,
          map: map,
          icon: obj.icon
      });

      if (obj.content.includes('Plate')){
        infoWindow.open(map, marker);
      }else{
         marker.addListener('click', function(){
          infoWindow.open(map, marker);
        });
      }
      return marker;
    }

    $(document).on('click', 'button[data-map]', function(){
      app.selectedRowData.value = [];
      $(this).closest('tr').children().each(function(){
        app.selectedRowData.value.push($(this).text());
      });
      $('#mapModal').modal('toggle');

      uvLocator = setInterval(function(){
        $.ajax({
          url: `${BASE_URL}trip_management/fetchUvLocation`,
          type: 'POST',
          dataType: 'json',
          data: {trip_id: app.selectedRowData.value[0]},
          success: function(resp){
            if (uvExpressMarker !== null){
              uvExpressMarker.setMap(null);
            }
            uvExpressMarker = setMarker({
              content: `Trip #: ${app.selectedRowData.value[0]} Plate #: ${app.selectedRowData.value[3]}`,
              icon: `${BASE_URL}/assets/img/van_marker.png`,
              location: {lat: parseFloat(resp.data.lat), lng: parseFloat(resp.data.lng)}
            });
          }
        });

      }, 3000);
    });

    $('#mapModal').on('hidden.bs.modal', function() {
      clearInterval(uvLocator);
      if (uvExpressMarker !== null){
        uvExpressMarker.setMap(null);
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | Page Vue instance
  |--------------------------------------------------------------------------
  */
  var app = new Vue({
    el: '#app',
    data: {

      tripStatus: TRIP_STATUS,
      tripRouteId: ROUTE_ID,
      tripDate: $.format.date(new Date(TRIP_DATE), 'yyyy-MM-dd'),
      tripRouteName: ROUTE_NAME,
      passengerTblReportTitle: '',
      activePage: $('.active-nav').text(),

      departDate: {
        day: $.format.date(new Date($.format.date(new Date(TRIP_DATE), 'yyyy-MM-dd')), 'E'),
        dayNo: $.format.date(new Date($.format.date(new Date(TRIP_DATE), 'yyyy-MM-dd')), 'dd'),
        month: $.format.date(new Date($.format.date(new Date(TRIP_DATE), 'yyyy-MM-dd')), 'MMMM'),
        year: $.format.date(new Date($.format.date(new Date(TRIP_DATE), 'yyyy-MM-dd')), 'yyyy'),
      },

      dateSlide: [],
      dateSlideIndex: 0,

      route: {
        origin: ORIGIN,
        destination: DESTINATION
      },

      tripTable: null, 
      passengerTable: null,
      passengerModalTripId: 0,
      selectedRowData: {
        id: [],
        value: [],
        location: null
      },
      selectedTripNoOfPass: 0,

      tripFormData: {
        mode: null,
        tripNo: null,
        uvId: null,
        driverId: null,
        date: null,
        departTime: null
      },

      routeMap: {
        path: [],
        origin: null,
        destination: null
      }
    },
    /*
    |--------------------------------------------------------------------------
    | Mounted
    |--------------------------------------------------------------------------
    */
    mounted: function(){
      let syncTripInterval = null;
      let app = this;

      // if (this.tripStatus === 'Cancelled' || this.tripStatus === 'Arrived'){
      //   this.fetchDate(this.tripRouteId);
      // }

      // Trip table action button
      $(document).on('click', '.cancel-trip', function(){
         app.cancelTrip($(this).attr('id'));
      });
      $(document).on('click', 'button[data-passenger]', function(){
        app.selectedRowData.value = [];
        $(this).closest('tr').children().each(function(){
          app.selectedRowData.value.push($(this).text());
        });
        app.selectedTripNoOfPass = $(this).data('passenger');
        app.passengerList();
      });
      $(document).on('click', 'button[data-update]', function(){
        app.tripFormData.mode = 'Update';
        app.selectedRowData.value = [];
        $(this).closest('tr').children().each(function(){
          app.selectedRowData.value.push($(this).text());
        });
        let updateData = $(this).data('update').split('//');
        if (updateData[2] === 'null'){
          updateData[2] = null;
        }
        if (updateData[3] === 'null'){
          updateData[3] = null;
        }
        updateData[1] = $.format.date( new Date(`${app.tripDate} ${updateData[1]}`), 'H:mm:ss')
        app.selectedRowData.id = updateData;
        app.showUpdateTrip();
      });

      _allRouteTripCallback = function(data){};
      setInterval(this.syncTrip, 2000);
      // Set trip table
      this.setTripTable();

      // Set trip info table modal
      this.setPassengerTable();
      $('#tripInfoModal').on('shown.bs.modal', function() {
        $(this).trigger('resize');
        _hideLoading();
        setTimeout(function(){
          $('#tripInfoModal').css('visibility', 'visible');
        }, 200);
      });
      $('#tripFormModal').on('hidden.bs.modal', function () {
        app.clear();
      });
      
      if (this.tripStatus === 'Pending' || this.tripStatus === 'Traveling'){
        setInterval(app.syncPassnger, 2000);
      }

      $('#tripInfoModal').on('hidden.bs.modal', function () {
        // clearInterval(syncTripInterval);
      });

      $('#passengerTable_wrapper').find('.dataTables_info').hide();
      this.setDateButtons();

      this.tripTable.column(2).visible(false);
      if (this.tripStatus === 'Arrived' || this.tripStatus === 'Traveling'){
        if (this.tripStatus === 'Arrived'){
          this.tripTable.column(2).visible(true);
        }
        $(this.tripTable.column(1).header()).text('Time Depart');
        $(this.tripTable.column(1).footer()).text('Time Depart');
      }

      this.passengerTable.column(4).visible(this.tripStatus === 'Traveling');

      // Fetch route map direction
      $.ajax({
        url: `${BASE_URL}trip_management/fetchRouteGeoPoints`,
        type: 'POST',
        dataType: 'json',
        data: {route_id: app.tripRouteId},
        error: ()=>{
          _hideLoading();
          toast('Failed loading', 'Failed to load map direction. Something went wrong.', 'danger');
        },
        success: function(resp){
          if (resp.status){
            if (resp.data[0].way_point !== null){
              resp.data[0].way_point.forEach(function(item, index){
                item.lat = parseFloat(item.lat);
                item.lng = parseFloat(item.lng);
              });
              app.routeMap.path = resp.data[0].way_point;
              app.routeMap.origin = {lat: parseFloat(resp.data[0].origin_lat_lng.lat), lng: parseFloat(resp.data[0].origin_lat_lng.lng)};
              app.routeMap.destination = {lat: parseFloat(resp.data[0].destination_lat_lng.lat), lng: parseFloat(resp.data[0].destination_lat_lng.lng)};
            }
          }
        }
      });
    },
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    methods: { // START METHODS

      // Date today click
      todayTrip: function(){
        let newDate = new Date();
        newDate = $.format.date(new Date(newDate.setDate(newDate.getDate())), 'yyyy-MM-dd');
        this.getTrip(newDate);
      },

      decrementDate: function(){
        let newDate = new Date(this.tripDate);
        newDate = $.format.date(new Date(newDate.setDate(newDate.getDate() - 1)), 'yyyy-MM-dd');
        this.getTrip(newDate);
      },

      incrementDate: function(){
        let newDate = new Date(this.tripDate);
        newDate = $.format.date(new Date(newDate.setDate(newDate.getDate() + 1)), 'yyyy-MM-dd');
        this.getTrip(newDate);
      },

      searchTripDate: function(){
        if ($('#dateInput').val().trim() === '' || $('#dateInput').val() === null){
          _alert('Information', 'Please select date.', 'blue');
          return;
        }
        if ($('#dateInput').val() < $.format.date(new Date(), 'yyyy-MM-dd')){
          if (this.tripStatus === 'Pending'){
            _alert('Information', 'Date must be greather than today.', 'blue');
            return;
          }
        }else if($('#dateInput').val() > $.format.date(new Date(), 'yyyy-MM-dd')){
          if (this.tripStatus === 'Arrived'){
            _alert('Information', 'Date must be less than today.', 'blue');
            return;
          }
        }else if($('#dateInput').val() === this.tripDate){
          _alert('Information', `Today's all trip information is already in the table.`, 'blue');
          return;
        }
        this.getTrip($('#dateInput').val());
      },

      // Show modal with passenger table
      passengerList: function(){
        _showLoading();
        this.passengerTable.ajax.url(`${BASE_URL}trip_management/tripPassenger/${this.selectedRowData.value[0]}`).load(function(){
          $('#tripInfoModal').css('visibility', 'hidden');
          $('#tripInfoModal').modal();
        });
      },

      cancelTrip: function(tripId){
        let app = this;
        _confirm(
          'Are you sure do you want to cancel trip',
          function(){
            _showLoading();
            let app = this;
            $.ajax({
              url: `${BASE_URL}trip_management/setTripStatus`,
              type: 'POST',
              data: {"status":"Cancelled", "trip_id":tripId},
              dataType: 'json',
              error: function(){
                _hideLoading();
                toast('Failed', 'Failed to cancel trip. Somthing went wrong', 'danger');
              },
              success: function(resp){
                _hideLoading();
                if (resp.status){
                  toast('Trip Cancelled', 'Trip  successfully cancelled.', 'success');
                }else{
                  toast('Failed', 'Failde to cancel trip.', 'danger');
                }
              }
            });
          },
          ()=>{}
        );
      },

      // Enable/disabled date buttons
      setDateButtons: function(){
        if (this.tripDate === $.format.date(new Date(), 'yyyy-MM-dd')){
          $('#btnToday').attr('disabled', true);
          if (this.tripStatus === 'Arrived'){
            $('#btnIncreaseDate').attr('disabled', true);
            $('#btnDecreaseDate').attr('disabled', false);
          }else{
            $('#btnIncreaseDate').attr('disabled', false);
            if (this.tripStatus === 'Pending'){
               $('#btnDecreaseDate').attr('disabled', true);
            }
          }
        }
        if (this.tripDate > $.format.date(new Date(), 'yyyy-MM-dd')){
          $('#btnToday').attr('disabled', false);
          if (this.tripStatus === 'Pending'){
            $('#btnDecreaseDate').attr('disabled', false);
          }
        }
        if (this.tripDate < $.format.date(new Date(), 'yyyy-MM-dd')){
          $('#btnToday').attr('disabled', false);
          if (this.tripStatus === 'Arrived'){
            $('#btnIncreaseDate').attr('disabled', false);
          }
        }
      },
      // Modal Add Trip Form
      showAddTrip: function(){
        this.tripFormData.mode = 'Add';
        this.clear();
      },

      showUpdateTrip: function(){
        $('#tripFormModal').modal();
        this.tripFormData.tripNo = this.selectedRowData.value[0];
        this.tripFormData.departTime = this.selectedRowData.id[1];
        this.tripFormData.uvId = this.selectedRowData.id[3];
        this.tripFormData.driverId = this.selectedRowData.id[2];

        if (this.selectedRowData.id[2] !== null){
          $('.selectpicker[id="driverId"]').append(`<option 
                                                      value="${this.selectedRowData.id[2]}"
                                                      data-subtext="Employee ID: ${this.selectedRowData.id[2]}"
                                                      data-tokens="${this.selectedRowData.value[2]}">
                                                      ${this.selectedRowData.value[2]}
                                                    </option>`);
        }
        if (this.selectedRowData.id[3] !== null){
          $('.selectpicker[id="uvId"]').append(`<option 
                                                      value="${this.selectedRowData.id[3]}"
                                                      data-tokens="${this.selectedRowData.value[3]}">
                                                      ${this.selectedRowData.value[3]}
                                                    </option>`);
        }
        $('.selectpicker[id="departTime"]').val(this.tripFormData.departTime);
        $('.selectpicker[id="driverId"]').val(this.tripFormData.driverId);
        $('.selectpicker[id="uvId"]').val(this.tripFormData.uvId);
        $('.selectpicker').selectpicker('refresh');
      },

      clearFormTrip: function(action){
        if (action === 'clear'){
           _confirm(
              'Are you sure you want to clear all fields?',
              this.clear,
              ()=>{}
            );
        }else{
          this.clear();
        }
      },
      refreshTripFormSelectpicker: function(){
        $('#uvId option:selected').remove();
        $('#driverId option:selected').remove();
        $('#uvId').selectpicker('refresh');
        $('#driverId').selectpicker('refresh');
      },
      clear: function(){

        if (this.tripFormData.mode === 'Update'){
          this.refreshTripFormSelectpicker();
        }

        $('button[data-id="uvId"]').removeClass('is-invalid');
        $("#uvId").val('default');
        $("#uvId").selectpicker("refresh");
        this.tripFormData.uvId = null;

        $('button[data-id="driverId"]').removeClass('is-invalid');
        $("#driverId").val('default');
        $("#driverId").selectpicker("refresh");
        this.tripFormData.driverId = null;

        $('button[data-id="departTime"]').removeClass('is-invalid');
        $("#departTime").val('default');
        $("#departTime").selectpicker("refresh");
        this.tripFormData.departTime = null;
      },

      /*
      |---------------------
      | Ajax
      |---------------------
      */
      syncTrip: function(){
        let app = this;
        $.ajax({
          url: `${BASE_URL}trip_management/numberOfTrip/${this.tripDate}/${this.tripRouteId}/${this.tripStatus}`,
          type: 'GET',
          dataType: 'json',
          success: function(resp){
            if (resp.status){
              if (resp.data != app.tripTable.rows().count()){
                app.tripTable.ajax.reload(null, false);
              }
            }
          }
        });
      },

      getTrip: function(date){
        let app = this;
        _showLoading();
        this.tripTable.ajax.url(`${BASE_URL}/trip_management/getTripJSON/${this.tripStatus}/${this.tripRouteId}/${date}`).load(function(){
            app.tripDate = date;
            app.departDate.day = $.format.date(new Date($.format.date(new Date(app.tripDate), 'yyyy-MM-dd')), 'E');
            app.departDate.dayNo = $.format.date(new Date($.format.date(new Date(app.tripDate), 'yyyy-MM-dd')), 'dd');
            app.departDate.month = $.format.date(new Date($.format.date(new Date(app.tripDate), 'yyyy-MM-dd')), 'MMMM');
            app.departDate.year = $.format.date(new Date($.format.date(new Date(app.tripDate), 'yyyy-MM-dd')), 'yyyy');
            app.setDateButtons();
            let url = `${BASE_URL}trip_management/trip/${app.tripStatus}/${app.tripRouteId}/${app.tripDate}`;
            window.history.pushState('', '', url);
            _hideLoading();
        });
      },

      // Trip form data ajax
      validateTripForm: function(){
        if (!this.tripFormData.departTime){
          $('#departTime').selectpicker('setStyle', 'form-control is-invalid');
          toast('Required', 'Departure time is required', 'danger');
          return false;
        }
        return true;
      },

      postFormTrip: function(){
        let app = this;
        let method = this.tripFormData.mode === 'Add' ? 'addTrip' : 'updateTrip';
        $.ajax({
          url: `${BASE_URL}trip_management/${method}`,
          type: 'POST',
          async: false,
          data: {
            "route_id": app.tripRouteId,
            "trip_id": app.tripFormData.tripNo,
            "uv_id": app.tripFormData.uvId,
            "driver_id": app.tripFormData.driverId,
            "date": app.tripDate,
            "depart_time": app.tripFormData.departTime
          },
          dataType: 'json',
          error: function(){
            _hideLoading();
            toast('Failed', 'Failed to add trip. Something went wrong', 'danger');
          },
          success: function(resp){
            _hideLoading();
            if (resp.status){
              if (app.tripFormData.mode === 'Update'){
                app.tripTable.ajax.reload(null, false);
                toast('Update Success', `Trip number ${app.selectedRowData.value[0]} successfully updated.`, 'success');
                $('#tripFormModal').modal('toggle');
              }else{
                app.refreshTripFormSelectpicker();
                toast('Trip Added', resp.msg, 'success');
              }
              app.clear();
            }else{
              $('#departTime').addClass('is-invalid');
              _alert(
                'Invalid Departure Time',
                `Departure time must be from now and onwards.`,
                'red'
              );
            }
          }
        });
      },
      
      submitFormTrip: function(){
        _showLoading();
        if (this.validateTripForm()){
          if (this.tripFormData.uvId !== null){
            this.checkUvAvailablity();
          }else if (this.tripFormData.driverId !== null){
            this.checkDriverAvailablity();
          }else{
            this.postFormTrip();
          }
        }
        _hideLoading();
      },

      checkDriverAvailablity: function(){
        // Check driver availability
        let app = this;
        if (this.tripFormData.mode === 'Update'){
          if (this.selectedRowData.id[2] === this.tripFormData.driverId){
            this.postFormTrip();
            return;
          }
        }
        $.ajax({
          url: `${BASE_URL}trip_management/checkDriver/${app.tripFormData.driverId}`,
          type: 'GET',
          dataType: 'json',
          success: function(res){
            if (res.status){
              if (res.data){
                app.postFormTrip();
              }else{
                $('#driverId').selectpicker('setStyle', 'form-control is-invalid');
                _alert(
                  'Not Available',
                  `Driver ${$('#driverId option:selected').text()} is not available.`,
                  'red'
                );
              }
            }
          }
        });
      },

      checkUvAvailablity: function(){
        // Check UV Express availability
        let app = this;
        if (this.tripFormData.mode === 'Update'){
          if (this.selectedRowData.id[3] === this.tripFormData.uvId){
            this.checkDriverAvailablity();
            return;
          }
        }
        $.ajax({
          url: `${BASE_URL}trip_management/checkUvExpress/${app.tripFormData.uvId}`,
          type: 'GET',
          dataType: 'json',
          success: function(resp){
            if (resp.status){
              if (resp.data){
                if (app.tripFormData.driverId !== null){
                  app.checkDriverAvailablity();
                }else{
                  app.postFormTrip();
                }
              }else{
                $('#uvId').selectpicker('setStyle', 'form-control is-invalid');
                _alert(
                  'Not Available',
                  `UV Express with plate number ${$('#uvId option:selected').text()} is not available.`,
                  'red'
                );
              }
            }
          }
        });
      },
      // End trip form data ajax

      syncPassnger: function(){
        let app = this;

        $.ajax({
          url: `${BASE_URL}/trip_management/getTripJSON/${this.tripStatus}/${this.tripRouteId}/${this.tripDate}`,
          type: 'GET',
          dataType: 'json',
          error: ()=>{},
          success: function(resp){
            let data = resp.data;
            if (resp.status){
              $.each(data, function(i){
                let resTripId = this.trip_id;
                let resPassCount = this.passenger;
                $('[data-passenger]').each(function(){
                  let tempTripId = $(this).closest('tr').find("td:first").text();
                  if (resTripId === tempTripId){
                    if (resPassCount != $(this).data('passenger')){
                      $(this).children('.badge').text(resPassCount);
                      if ($('#tripInfoModal').is(':visible')){
                        app.passengerTable.ajax.reload(null , true);
                        app.selectedTripNoOfPass = resPassCount;
                      }
                    }
                  }
                });
              });
            }
          } 
        });
      },

      /*
      |---------------------
      | Data Table
      |---------------------
      */
      // Trip data table
      setTripTable: function(){
        let app = this;
        if (app.tripStatus === 'Arrived'){
          colReport = [0, 1, 2, 3, 4];
        }else{
          colReport = [0, 1, 2, 3];
        }

        if (app.tripStatus === 'Pending'){
          status = 'Trip Schedule';
        }else if (app.tripStatus === 'Cancelled'){
          status = 'Cancelled Trip';
        }else if (app.tripStatus === 'Arrived'){
          status = 'Trip History';
        }

        this.tripTable = $('#tripTable').DataTable({
          responsive: true,
          stateSave: true,
          pageLength: 10,
          dom: 'Blfrtip',
          bSort: false,
          buttons: [{
            extend: 'print',
            title: `<div class="d-flex align-items-center justify-content-between">
                        <h4 class="modal-title">${this.route.origin} to ${this.route.destination} ${status}</h4>
                        <h5 class="modal-title">Date: ${this.tripDate}</h5>
                      </div>`,
            exportOptions: {
              columns: colReport
            }
          },{
            extend: 'excel',
            title: `${this.route.origin} to ${this.route.destination} ${status} ${this.tripDate}`,
            exportOptions: {
              columns: colReport
            }
          },{
            extend: 'csv',
            title: `${this.route.origin} to ${this.route.destination} ${status} ${this.tripDate}`,
            exportOptions: {
              columns: colReport
            }
          },{
            extend: 'pdf',
            title: `${this.route.origin} to ${this.route.destination} ${status} ${this.tripDate}`,
            exportOptions: {
              columns: colReport
            }
          }],
          ajax: {
            url: `${BASE_URL}/trip_management/getTripJSON/${this.tripStatus}/${this.tripRouteId}/${this.tripDate}`,
            type: 'GET',
            error: function(e){
              // _hideLoading();
            }
          },
          columns: [
            {'data':'trip_id'},
            {'data':'depart_time'},
            {'data':'arrival_time'},
            {'data':'driver_name'},
            {'data':'plate_no'}
          ],
          columnDefs: [{
            targets: 5,
            render: function(data, type, row, meta){
              html = `<div class="btn-group ml-2" role="group">
                        <button class="btn btn-sm btn-outline-info btn-badge"
                          href="#"
                          title="Click to view passenger list"
                          data-passenger="${row['passenger']}">
                          <i class="fa fa-users"></i>
                          <span class="badge badge-secondary">${row['passenger']}</span>
                        </button>`;
              if (row['status'] === 'Pending'){
                html += `<button class="btn btn-sm btn-outline-success" 
                          data-target="tripFormModal"
                          title="Update Trip"
                          data-update="${row['trip_id']}//${row['depart_time']}//${row['driver_id']}//${row['uv_id']}">
                          <span class="fa fa-edit fa-sm"></span>
                        </button>
                        <button id="${row['trip_id']}"
                          class="btn btn-sm btn-outline-danger cancel-trip" 
                          title="Cancel Trip">
                          <span class="fa fa-times"></span>
                        </button>`;
              }
              if (row['status'] === 'Traveling'){
                html+= `<button class="btn btn-sm btn-outline-primary"
                          title="View Location"
                          data-map="${row['trip_id']}//${row['depart_time']}//${row['driver_id']}//${row['uv_id']}">
                          <i class="fa fa-map-marker fa-sm"></i>
                        </button>`;
              }
              return html;
            }
          }]
        });

        // Print/dowload button
        $('#btnPrint').click(()=>{
         $('#tripTable_wrapper').find('.buttons-print').click();
        });
        $('#btnExcel').click(()=>{
          $('#tripTable_wrapper').find('.buttons-excel').click();
        });
        $('#btnCsv').click(()=>{
          $('#tripTable_wrapper').find('.buttons-csv').click();
        });
        $('#btnPdf').click(()=>{
          $('#tripTable_wrapper').find('.buttons-pdf').click();
        });
      }, // End trip data table

      // Passenger data table modal
      setPassengerTable: function(){
        this.passengerTable = $('#passengerTable').DataTable({
          responsive: true,
          stateSave: true,
          pageLength: 100,
          scrollY: "35vh",
          paging: false,
          scrollCollapse: true,
          dom: 'Blfrtip',
          bSort: false,
          buttons: [{
            extend: 'print',
            title: 'title',
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4 ]
            }
          },{
            extend: 'excel',
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4 ]
            }
          },{
            extend: 'csv',
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4 ]
            }
          },{
            extend: 'pdf',
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4 ]
            }
          }],
          ajax: `${BASE_URL}/trip_management/tripPassenger/-1`,
          columns: [
            {'data':'seat_id'},
            {'data':'full_name'},
            {'data':'seat_no'},
            {'data':'boarding_pass'},
            {'data':'boarding_status'},
            {'data':'contact_no'}
          ]
        });

        // Print/dowload button
        $('#pl-btnPrint').click(()=>{
          $('#passengerTable_wrapper').find('.buttons-print').click();
        });
        $('#pl-btnExcel').click(()=>{
          $('#passengerTable_wrapper').find('.buttons-excel').click();
        });
        $('#pl-btnCsv').click(()=>{
          $('#passengerTable_wrapper').find('.buttons-csv').click();
        });
        $('#pl-btnPdf').click(()=>{
          $('#passengerTable_wrapper').find('.buttons-pdf').click();
        });
      }// End passenger data table modal
    }// End Vue methods
  });// End Vue

})();