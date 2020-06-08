(function(){

	var app = new Vue({

		el: '#app',
		data: {

			tripRouteId: ROUTE_ID,
      tripDate: $.format.date(new Date(TRIP_DATE), 'yyyy-MM-dd'),
      pageUrl: `${BASE_URL}booking/trip/${ROUTE_ID}/${$.format.date(new Date(TRIP_DATE), 'yyyy-MM-dd')}`,
      departDate: {
        day: $.format.date(new Date($.format.date(new Date(TRIP_DATE), 'yyyy-MM-dd')), 'E'),
        dayNo: $.format.date(new Date($.format.date(new Date(TRIP_DATE), 'yyyy-MM-dd')), 'dd'),
        month: $.format.date(new Date($.format.date(new Date(TRIP_DATE), 'yyyy-MM-dd')), 'MMMM'),
        year: $.format.date(new Date($.format.date(new Date(TRIP_DATE), 'yyyy-MM-dd')), 'yyyy'),
      },

      route: {
        origin: ORIGIN,
        destination: DESTINATION
      },

      tripTable: null,
      passengerTable: null,
      tableData: [],
      dtIndex: null,
      bookingData: [],
      bookIndex: null,
      availableSeat: 0,

      uvSeats: [],

      // Booking
      bookNoOfPass: 1,
      bookSeats: [],
      bookQueueId: null,
      getOccupiedSeatInterval: null,
      noOfSelectedSeat: 0,
		},
		/*
	  |--------------------------------------------------------------------------
	  | MOUNTED
	  |--------------------------------------------------------------------------
	  */
		mounted: function(){
			let app = this;
			_allRouteTripCallback = (resp)=>{};
			// Set table
			this.setTripTable();
			this.setPassengerTable();
			$('#tripInfoModal').on('shown.bs.modal', function() {
        $(this).trigger('resize');
        setTimeout(function(){
          $('#tripInfoModal').css('visibility', 'visible');
        }, 200);
      });
			$('#passengerTable_wrapper').find('.dataTables_info').hide();

			// Trip table action button
			$(document).on('click', '.passenger-list', function(){
				app.dtIndex = $(this).attr('id');
				app.availableSeat = 14 - parseInt(app.tableData[app.dtIndex].passenger);
				$('#tripInfoModal').css('visibility', 'hidden');
				$('#tripNo').text(app.tableData[app.dtIndex].trip_id);
				$('#plateNo').text(app.tableData[app.dtIndex].plate_no);
				$('#driverName').text(app.tableData[app.dtIndex].driver_name);
				$('#dTime').text(app.tableData[app.dtIndex].depart_time);
				$('#countPass').text(app.tableData[app.dtIndex].passenger);
				$('#vacantSeat').text(app.availableSeat);
				app.passengerList();
			});

			setInterval(function(){
				app.syncTrip();
				app.syncPassnger();
			}, 2000);

      // BOOKING 
      
      $('#bookingModal').on('shown.bs.modal', function() {
        for(i=1; i<15; i++){
          $(`#seat${i}`).data('vacant', true);
          $(`#seat${i}`).data('selected', false);
          $(`#seat${i}`).css('background', 'blue');
        }
        $('#bookingModal').css('z-index', parseInt($('#tripInfoModal').css('z-index')) + 1000);
        $('.uv-overlay').show();
      });

      $('#bookingModal').on('hidden.bs.modal', function() {
        clearInterval(app.getOccupiedSeatInterval);
        $('body').css('padding-right', '6px');
        $('#passengerData').empty();
        app.bookSeats = [];
        app.bookNoOfPass = 1;
        app.noOfSelectedSeat = 0;
        app.deleteQueue();
        app.bookQueueId = null;
        for(i=1; i<15; i++){
          $(`#seat${i}`).data('vacant', true);
          $(`#seat${i}`).data('selected', false);
          $(`#seat${i}`).css('background', 'blue');
        }
      });

      $(document).on('click', '.cancel-booking', function(){
        let row = $(this).attr('id');
        app.bookIndex = row;
        _confirm(
          `Are you sure you want to cancel <strong>${app.bookingData[row].full_name}'s</strong> seat reservation?`,
          function(){
            app.cancelReservation(row);
          },
          ()=>{}
        );
      });

      // Seat reservation
      $(document).on('click', '.seat-reservation', function(){
        let row = $(this).attr('id');
        app.dtIndex = row;
        $('#noOfPassModal').modal('toggle');
      });

      $('#noOfPassModal').on('hidden.bs.modal', function() {
        $('#inputNoPass').val('1');
      });

      this.setDateButtons();

		},
		/*
	  |--------------------------------------------------------------------------
	  | METHODS
	  |--------------------------------------------------------------------------
	  */
		methods: {

      /*
      |--------------------------------------------------------------------------
      | BOOKING MODAL
      |--------------------------------------------------------------------------
      */
   
			// BOOKING MODALS
      openBookingModal: function(){
        if (app.bookNoOfPass === '' || app.bookNoOfPass < 1){
          $('#inputNoPass').addClass('is-invalid');
          $.alert({
            title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
            content: 'Please enter a valid number.',
          });
          return false;
        }
        $('#inputNoPass').removeClass('is-invalid');

        app.getAvailableSeat({
          tripId: app.tableData[app.dtIndex].trip_id, 
          callback: function(){
            if (app.bookNoOfPass <= app.availableSeat){
              app.insertBookingQueue();
            }else{
              if (app.availableSeat < 1){
                $.alert({
                  title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
                  content: `Trip number ${app.tableData[app.dtIndex].trip_id} has no more available seat.`,
                });
              }else{
                $.alert({
                  title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
                  content: `Only ${app.availableSeat} seats available.`,
                });
              }
            }
          }
        });
      },

      // Booking ajax
      insertBookingQueue: function(){
        _showLoading();
        $.ajax({
          url: `${BASE_URL}/booking/insert_queue/${this.bookNoOfPass}/${this.tableData[this.dtIndex].trip_id}`,
          type: 'GET',
          dataType: 'json',
          error: ()=>{
            _hideLoading();
            $.alert({
                title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
                content: 'Failed to connect to server.',
            });
          },
          success: function(resp){
            if (resp.data){
              app.bookQueueId = resp.data;
              app.getOccupiedSeatInterval = setInterval(function(){
                app.getOccupiedSeat();
              }, 1000);
              for(i=0; i<app.bookNoOfPass; i++){
                app.bookSeats.push(null);
                $('#passengerData').append(
                 `<div class="row">
                    <div class="form-group col-md-6">
                      <label class="control-label">Passenger ${app.bookSeats.length} Name</label>
                      <input class="form-control name" type="text" id="name${app.bookSeats.length}">
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Contact #</label>
                      <input class="form-control contact" type="text" id="contact${app.bookSeats.length}">
                    </div>
                  </div>`
                );
              }
              $('#noOfPassModal').modal('toggle');
              setTimeout(function(){
                _hideLoading();
                $('#timeD').text(app.tableData[app.dtIndex].depart_time);
                $('#bookingModal').modal('toggle');
              }, 500);
            }else{
              $.alert({
                title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
                content: 'Failed to connect to server.',
              });
            }
          }
        });
      },

      deleteQueue: function(){
        $.ajax({
          url: `${BASE_URL}booking/delete_book/${this.bookQueueId}`,
          type: 'GET'
        });
      },

      getOccupiedSeat: function(){
        $.ajax({
          url: `${BASE_URL}booking/get_seat/${this.tableData[this.dtIndex].trip_id}/${this.bookQueueId}`,
          type: 'GET',
          dataType: 'json',
          error: ()=>{ $('.uv-overlay').show() },
          success: function(resp){
            $('.uv-overlay').hide();
            let seats = resp.data[0];
            for(i=1; i<15; i++){
              if (!$(`#seat${i}`).data('selected')){
                $(`#seat${i}`).data('vacant', true);
                $(`#seat${i}`).data('selected', false);
                $(`#seat${i}`).css('background', 'blue');
              }
            }
            if (resp.status){
              Object.keys(seats).forEach((key, i)=>{
                $(`#seat${seats[key]}`).data('vacant', false);
                $(`#seat${seats[key]}`).data('selected', false);
                $(`#seat${seats[key]}`).css('background', 'red');
              });
            }
          }
        });
      },

      getAvailableSeat: function(obj){
        let app = this;
        _showLoading();
        $.ajax({
          url: `${BASE_URL}booking/count_available_seat/${obj.tripId}`,
          type: 'GET',
          dataType: 'json',
          error: ()=>{ 
          },
          success: function(resp){
            _hideLoading();
            app.availableSeat = 14 - parseInt(resp.data);
            obj.callback();
          }
        });
      },

      selectSeat: function(seatNo){
        let app = this;
        if ($(`#seat${seatNo}`).data('vacant')){
          if ($(`#seat${seatNo}`).data('selected')){
            $(`#seat${seatNo}`).css('background', 'blue');
            this.deleteSeat(seatNo);
            $(`#seat${seatNo}`).data('selected', !$(`#seat${seatNo}`).data('selected'));
            this.noOfSelectedSeat--;
          }else{
            if (this.noOfSelectedSeat == this.bookNoOfPass){
              $.alert({
                title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
                content: `You have already selected <strong>${this.bookNoOfPass}</strong> seat(s).`
              });
            }else{
              $.each(this.bookSeats, function(i){
                if (app.bookSeats[i] == null){
                  app.insertSeat(seatNo);
                  $(`#seat${seatNo}`).css('background', 'green');
                  $(`#seat${seatNo}`).data('selected', !$(`#seat${seatNo}`).data('selected'));
                  app.noOfSelectedSeat++;
                  return false;
                }
              });
            }
          }
        }
      },

      deleteSeat: function(seatNo){
        let app = this;
        $('.uv-overlay').show();
        $.ajax({
          url: `${BASE_URL}booking/delete_seat/${seatNo}/${this.bookQueueId}`,
          type: 'GET',
          dataType: 'json',
          error: function(){
            app.deleteSeat(seatNo);
          },
          success: function(resp){
             $('.uv-overlay').hide();
            if (!resp.data){
              $(`#seat${seatNo}`).css('background', 'red');
              $(`#seat${seatNo}`).data('selected', true);
            }else{
              $(`#seat${seatNo}`).css('background', 'blue');
              $(`#seat${seatNo}`).data('selected', false);
              $.each(app.bookSeats, function(i){
                if (app.bookSeats[i] == seatNo){
                  app.bookSeats[i] = null;
                  return false;
                }
              });
            }
          }
        });
      },

      insertSeat: function(seatNo){
        let app = this;
        $('.uv-overlay').show();
        $.ajax({
          url: `${BASE_URL}booking/insert_seat/${seatNo}/${this.bookQueueId}/${this.tableData[this.dtIndex].trip_id}`,
          type: 'GET',
          dataType: 'json',
          error: function(){
            app.insertSeat(seatNo);
          },
          success: function(resp){
            $('.uv-overlay').hide();
            if (!resp.data){
              $(`#seat${seatNo}`).css('background', 'blue');
              $(`#seat${seatNo}`).data('selected', false);
              $.alert({
                title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
                content: `Seat no. <strong>${seatNo}</strong> is not available.`,
              });
            }else{
              $.each(app.bookSeats, function(i){
                if (app.bookSeats[i] == null){
                  app.bookSeats[i] = seatNo;
                  return false;
                }
              });
            }
          }
        });
      },

      saveBooking: function(){
        if (this.checkPassengerDataFields()){

          let form = {};
          form['trip_id'] =  app.tableData[app.dtIndex].trip_id;
          form['no_of_pass'] = parseInt(app.bookNoOfPass);
          for (i=0; i<app.bookNoOfPass; i++){
            form[`seat${i}`] = app.bookSeats[i];
            form[`fullname${i}`] = $(`#name${i+1}`).val();
            form[`contact${i}`] = $(`#contact${i+1}`).val();
          }
          _showLoading();
          $.ajax({
            url: `${BASE_URL}booking/save_booking`,
            type: 'POST',
            dataType: 'json',
            data: form,
            error: function(){
              _hideLoading();
              $.alert({
                title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
                content: 'Failed to saved reservation. Try again.',
              });
            },
            success: function(resp){
              _hideLoading();
              if (resp.data){
                $.confirm({
                  title: 'Information',
                  content: 'New reservation successfully saved.',
                  icon: 'fa fa-check',
                  theme: 'modern',
                  closeIcon: false,
                  animation: 'scale',
                  type: 'green',
                  buttons: {
                    confirm: {
                      text: 'New Reservation',
                      btnClass: 'btn-blue',
                      action: function(){
                        $('#bookingModal').modal('toggle');
                        $('#noOfPassModal').modal('toggle');
                      }
                    },
                    close: function () {
                      $('#bookingModal').modal('toggle');
                    }
                  }
                });
              }else{
                $.alert({
                  title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
                  content: 'Failed to saved reservation.',
                });
              }
            }
          });
        }
      },

      // Booking validation
      checkPassengerDataFields: function(){
        let hasNameContact = false;
        let hasComplete = true;
        $('#passengerData').children('.row').each(function(){
          if ($(this).find('input.name').val().trim() !== '' && $(this).find('input.contact').val().trim() !== ''){
            hasNameContact = true;
          }
          if ($(this).find('input.name').val().trim() === ''){
            hasComplete = false;
          }
        });
        if (!hasComplete){
          $.alert({
            title: '<h6 class="modal-title font-weight-bold">Sytem Message</h6>',
            content: 'Please fill all passenger name fileds.',
          });
          return false;
        }

        if (!hasNameContact){
          $.alert({
            title: '<h6 class="modal-title font-weight-bold">Sytem Message</h6>',
            content: 'Please provide atleast one contact number of passenger.',
          });
          return false;
        }

        for(i=0;i<app.bookSeats.length; i++){
          if (app.bookSeats[i] == null){
            $.alert({
              title: '<h6 class="modal-title font-weight-bold">Sytem Message</h6>',
              content: `Please select ${app.bookNoOfPass} seat(s).`,
            });
            return false;
          }
        }
        return true;
      },

      // END BOOKING
      
      /*
      |--------------------------------------------------------------------------
      | TRIP INFO MODAL
      |--------------------------------------------------------------------------
      */

			cancelReservation: function(i){
				let app = this;
				_showLoading();
				$.ajax({
					url: `${BASE_URL}booking/cancelReservation`,
					type: 'POST',
					dataType: 'json',
					data: {seat_id: this.bookingData[i].seat_id},
					error: ()=>{
						_hideLoading(); 
						toast('Failed', `Failed to cancel <strong>${app.bookingData[i].full_name}</strong> reservation. Something went wrong.`, 'danger');
					},
					success: function(resp){
						if (resp.data){
							_hideLoading();
							toast('Information', `Seat reservation of <strong>${app.bookingData[i].full_name}</strong> successfully cancelled.`, 'success');
							app.passengerTable.ajax.reload(null, false);
						}else{
							toast('Information', `Cannot cancel <strong>${app.bookingData[i].full_name}</strong> reservation.`, 'info');
						}
					}
				});
			},
			
      /*
      |--------------------------------------------------------------------------
      | TRIP SCHEDULE
      |--------------------------------------------------------------------------
      */
     
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
          _alert('Information', 'Date must be greather than today.', 'blue');
          return;
        }else if($('#dateInput').val() === this.tripDate){
          _alert('Information', `Today's all trip information is already in the table.`, 'blue');
          return;
        }
        this.getTrip($('#dateInput').val());
      },

      getTrip: function(date){
        let app = this;
        _showLoading();
        this.tripTable.ajax.url(`${BASE_URL}/trip_management/getTripJSON/Pending/${this.tripRouteId}/${date}`).load(function(){
            app.tripDate = date;
            app.departDate.day = $.format.date(new Date($.format.date(new Date(app.tripDate), 'yyyy-MM-dd')), 'E');
            app.departDate.dayNo = $.format.date(new Date($.format.date(new Date(app.tripDate), 'yyyy-MM-dd')), 'dd');
            app.departDate.month = $.format.date(new Date($.format.date(new Date(app.tripDate), 'yyyy-MM-dd')), 'MMMM');
            app.departDate.year = $.format.date(new Date($.format.date(new Date(app.tripDate), 'yyyy-MM-dd')), 'yyyy');
            app.setDateButtons();
            app.pageUrl = `${BASE_URL}booking/trip/${app.tripRouteId}/${app.tripDate}`;
            window.history.pushState('', '', app.pageUrl);
            _hideLoading();
        });
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
             $('#btnDecreaseDate').attr('disabled', true);
          }
        }
        if (this.tripDate > $.format.date(new Date(), 'yyyy-MM-dd')){
          $('#btnToday').attr('disabled', false);
          $('#btnDecreaseDate').attr('disabled', false);
        }
        if (this.tripDate < $.format.date(new Date(), 'yyyy-MM-dd')){
          $('#btnToday').attr('disabled', false);
          $('#btnIncreaseDate').attr('disabled', false);
        }
      },

      syncTrip: function(){
        let app = this;
        $.ajax({
          url: `${BASE_URL}trip_management/numberOfTrip/${this.tripDate}/${this.tripRouteId}/Pending`,
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

      // Show modal with passenger table
      passengerList: function(){
        _showLoading();
        this.passengerTable.ajax.url(`${BASE_URL}trip_management/tripPassenger/${app.tableData[app.dtIndex].trip_id}`).load(function(){
          _hideLoading();
          $('#tripInfoModal').modal();
        });
      },

      syncPassnger: function(){
        let app = this;
        $.ajax({
          url: `${BASE_URL}/trip_management/getTripJSON/Pending/${this.tripRouteId}/${this.tripDate}`,
          type: 'GET',
          dataType: 'json',
          error: ()=>{},
          success: function(resp){
            let data = resp.data;
            if (resp.status){
              $.each(data, function(i){
                let resTripId = this.trip_id;
                let resCountPass = this.passenger;
                $.each(app.tableData, function(i){
                	if ((resTripId === app.tableData[i].trip_id) && (resCountPass !== app.tableData[i].passenger)){
                		app.tableData[i].passenger = resCountPass;
                		$(`#${i}`).children('.badge').text(resCountPass);
                		if ($('#tripInfoModal').is(':visible')){
                			app.passengerTable.ajax.reload(null , true);
                      app.availableSeat = 14 - app.tableData[app.dtIndex].passenger;
                      $('#vacantSeat').text(app.availableSeat);
                			$('#countPass').text(resCountPass);
                	 	}
                	}
                });
              });
            }
          } 
        });
      },

      /*
      |--------------------------------------------------------------------------
      | DATATABLES
      |--------------------------------------------------------------------------
      */

      // Trip data table
      setTripTable: function(){
        let app = this;

        this.tripTable = $('#tripTable').DataTable({
          responsive: true,
          stateSave: true,
          pageLength: 10,
          dom: 'Blfrtip',
          bSort: false,
          ajax: {
          	url: `${BASE_URL}/trip_management/getTripJSON/Pending/${this.tripRouteId}/${this.tripDate}`,
          	dataSrc: function(json){
          		app.tableData = json.data;
          		return json.data;
          	}
          },
          columns: [
            {'data':'trip_id'},
            {'data':'depart_time'},
            {'data':'driver_name'},
            {'data':'plate_no'}
          ],
          columnDefs: [{
            targets: 4,
            render: function(data, type, row, meta){
              return `<span>${14 - parseInt(row['passenger'])}</span>`;
            }
          },{
            targets: 5,
            render: function(data, type, row, meta){
              return `<div class="btn-group ml-2" role="group">
                        <button class="btn btn-sm btn-outline-info btn-badge passenger-list"
                          href="#"
                          title="Click to view passenger"
                          id="${meta.row}">
                          <i class="fa fa-users"></i>
                          <span class="badge badge-secondary">${row['passenger']}</span>
                        </button>
                        <button class="btn btn-sm btn-outline-success seat-reservation"
                          href="#"
                          title="Add Seat Reservation"
                          id="${meta.row}">
                          <i class="fa fa-plus fa-sm"></i><i class="fa fa-user"></i>
                        </button>
                      </div>`;
            }
          }]
        });
      }, // End trip data table

      // Passenger data table modal
      setPassengerTable: function(){
      	let app = this;
        this.passengerTable = $('#passengerTable').DataTable({
          responsive: true,
          stateSave: true,
          pageLength: 10,
          scrollY: "35vh",
          paging: false,
          scrollCollapse: true,
          dom: 'Blfrtip',
          bSort: false,
          ajax: {
          	url: `${BASE_URL}/trip_management/tripPassenger/-1`,
          	dataSrc: function(json){
          		app.bookingData = json.data;
          		return json.data;
          	}
          },
          columns: [
            {'data':'seat_id'},
            {'data':'full_name'},
            {'data':'boarding_pass'},
            {'data':'contact_no'},
            {'data':'seat_no'}
          ],
          columnDefs: [{
            targets: 5,
            render: function(data, type, row, meta){
              return `<div class="btn-group ml-2" role="group">
                        <button class="btn btn-sm btn-outline-danger cancel-booking"
                          href="#"
                          title="Cancel"
                          id="${meta.row}">
                          <i class="fa fa-times"></i>
                        </button>
                      </div>`;
            }
          }]
        });
      }// End passenger data table

		}

	});

})();