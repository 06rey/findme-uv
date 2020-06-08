(function(){

	var app = new Vue({
		el: '#app',
		data: {
			view: VIEW,
			empTable: null,
			empData: [],
			dtIndex: null,

			formMode: null,
			form: {
				f_name: '',
				m_name: '',
				l_name: '',
				license_no: '',
				contact_no: '',
				address: '',
				password: '',
				cpassword: '',
				role: VIEW
			}

		},
		/*
	  |--------------------------------------------------------------------------
	  | MOUNTED
	  |--------------------------------------------------------------------------
	  */
		mounted: function(){
			let app = this;
			_allRouteTripCallback = function(){}

			this.setDriverTable();
			if (VIEW === 'Clerk'){
				this.empTable.column(3).visible(false);
			}

			// Table action button
			$(document).on('click', 'button[data-status]', function(){
				app.dtIndex = $(this).attr('id');
				if ($(this).data('status') == 1){
					_confirm(
						'Are you sure want to activate this user?', 
						function(){
							app.updateStatus();
						},
						()=>{}
					);
				}else{
					_confirm(
						'Are you sure want to deactivate this user?', 
						function(){
							app.updateStatus();
						},
						()=>{}
					);
				}
			});

			$('#btnAddDriver').click(function(){
				app.formMode = 'Add';
				$('#formModal').modal('toggle');
			});

			$(document).on('click', 'button[data-update]', function(){
				app.formMode = 'Update';
				app.dtIndex = $(this).data('update');
				app.setFormData(app.empData[app.dtIndex]);
				$('#formModal').modal('toggle');
			});

			// Driver form img input
			$('#imgInput').on('change', function(){
				if(this.files[0].type=='image/jpeg'||this.files[0].type=='image/png'){
					if(this.files && this.files[0]){
						var reader = new FileReader();
						reader.onload = function(e){
							$('#userImg').attr('src', e.target.result);
						}
						reader.readAsDataURL(this.files[0]);
					}	
				} else {
					toast(
						'Invalid',
						'Invalid image format.',
						'danger'
					);
				}
			});
			$('#userImg').attr('src', `${BASE_URL}assets/img/user-gray.png`);
			// Add img click
			$('#addUserImg').on('click', function(){
				$('#imgInput').click();
			});
			// Remove img click
			$('#removeUserImg').on('click', function(){
				$('#userImg').attr('src', `${BASE_URL}assets/img/user-gray.png`);
				$('#imgInput').val('');
			});

		},
		/*
	  |--------------------------------------------------------------------------
	  | METHODS
	  |--------------------------------------------------------------------------
	  */
		methods: {

			// FORM VALIDATION -------------------------------------------------------------------------
			submitForm: function(){
				let app = this;
				let validate = true;
				if (this.formMode === 'Add'){
					Object.keys(this.form).forEach((key, i)=>{
						if (VIEW === 'Clerk' && key === 'license_no'){
							return;
						}
						if (this.form[key].trim() === ''){
							$(`#${key}`).addClass('is-invalid');
							toast('Required', `${$(`#${key}`).siblings('label').text()} is required!`, 'danger');
							validate = false;
						}
					});
					if (!validate) { return }

					// Check password
					if (this.form.password.trim().length < 8){
						$('#password').addClass('is-invalid');
						toast('Weak Passsword', 'Passsword length must be at least 8 character long.', 'danger');
						return;
					}

					if (this.form.password.trim() !== this.form.cpassword.trim()){
						$('#password').addClass('is-invalid');
						$('#cpassword').addClass('is-invalid');
						toast('Not Match', 'Account password and retype password does not match.', 'danger');
						return;
					}
					$('#password').removeClass('is-invalid');
					$('#cpassword').removeClass('is-invalid');
				}else{
					if (app.form.contact_no.trim() === ''){
						$('#contact_no').addClass('is-invalid');
						toast('Required', `${$(`#contact_no`).siblings('label').text()} is required!`, 'danger');
						validate = false;
					}
					if (app.form.address.trim() === ''){
						$('#address').addClass('is-invalid');
						toast('Required', `${$(`#address`).siblings('label').text()} is required!`, 'danger');
						validate = false;
					}
					if (!validate){ return; }
					$('#address').removeClass('is-invalid');
					$('#contact_no').removeClass('is-invalid');
				}
				if ((app.formMode === 'Add') || (app.formMode === 'Update' && app.form.contact_no !== app.empData[app.dtIndex].contact_no)){
					this.checkMobileNumber();
				}else{
					app.postUpdateForm();
				}
			},

			// Check duplicate
			checkDuplicate: function(){
				_showLoading();
				$.ajax({
					url: `${BASE_URL}employee/checkDuplicate`,
					type: 'POST',
					dataType: 'json',
					data: app.form,
					error: ()=>{toast('Failed', 'Something went wrong there.', 'danger'); _hideLoading();},
					success: function(resp){
						_hideLoading();
						if (resp.data){
							app.postInsertForm();
						}else{
							$('#f_name').addClass('is-invalid');
							$('#m_name').addClass('is-invalid');
							$('#l_name').addClass('is-invalid');
							_confirm(
								`<strong>${app.form.f_name} ${app.form.m_name} ${app.form.l_name}</strong> already exists in the database. Would you like to continue?`,
								function(){
									app.postInsertForm();
								},
								()=>{}
							);
						}
					}
				});
			},

			checkMobileNumber: function(){
				_showLoading();
				$.ajax({
					url: `${BASE_URL}employee/checkMobileNumber`,
					type: 'POST',
					dataType: 'json',
					data: app.form,
					error: ()=>{toast('Failed', 'Something went wrong there.', 'danger'); _hideLoading();},
					success: function(resp){
						_hideLoading();
						if (resp.data){
							$('#contact_no').removeClass('is-invalid');
							if (app.formMode === 'Add'){
								app.checkDuplicate();
							}else{
								app.postUpdateForm();
							}
						}else{
							$('#contact_no').addClass('is-invalid');
							_alert(
								'Information',
								`Contact number <strong>${app.form.contact_no}</strong> already exists!`,
								'red'
							);
						}
					}
				});
			},
			// END FORM VALIDATION -------------------------------------------------------------------------
			
			// Post insert employee form
			postInsertForm: function(){
				let app = this;
				_showLoading();
				let formData = new FormData();
				Object.keys(this.form).forEach((key, i)=>{
					formData.append(key, this.form[key]);
				});

				if ($('#imgInput').prop('files')[0]){
					formData.append('img', $('#imgInput').prop('files')[0]);
				}
				
				$.ajax({
					url: `${BASE_URL}employee/insertEmployee`,
					type: 'POST',
					dataType: 'json',
					processData : false,
   				contentType : false,
					data: formData,
					error: ()=>{ toast('Failed', 'Something went wrong there.', 'danger'); _hideLoading(); },
					success: function(resp){
						if (resp.data){
							_hideLoading();
							app.clearFields();
							toast('Success', `New ${VIEW} successfully added.`, 'success');
							app.empTable.ajax.reload(null, false);
						}else{
							toast('Failed', 'Something went wrong there.', 'danger');
						}
					}
				});
			},

			// Post update employee form
			postUpdateForm: function(){
				_showLoading();
				let formData = new FormData();
				formData.append('f_name', this.form.f_name);
				formData.append('l_name', this.form.l_name);
				formData.append('contact_no', this.form.contact_no);
				formData.append('address', this.form.address);
				formData.append('employee_id', this.empData[this.dtIndex].employee_id);
				if ($('#imgInput').prop('files')[0]){
					formData.append('img', $('#imgInput').prop('files')[0]);
				}
				$.ajax({
					url: `${BASE_URL}employee/updateEmployee`,
					type: 'POST',
					dataType: 'json',
					processData : false,
   				contentType : false,
					data: formData,
					error: ()=>{ toast('Failed', 'Something went wrong there.', 'danger'); _hideLoading(); },
					success: function(resp){
						_hideLoading();
						if (resp.data){
							toast('Success', `${VIEW} information successfully updated.`, 'success');
							app.empTable.ajax.reload(null, false);
						}else{
							toast('Information', 'No changes made.', 'info');
						}
					}
				});
			},
			
			updateStatus: function(){
				let app = this;
				_showLoading();
				$.ajax({
					url: `${BASE_URL}employee/updateStatus`,
					type: 'POST',
					dataType: 'json',
					data: {user_id: this.empData[this.dtIndex].user_id, status: this.empData[this.dtIndex].status, employee_id: this.empData[this.dtIndex].employee_id, f_name: this.empData[this.dtIndex].f_name, l_name: this.empData[this.dtIndex].l_name},
					error: ()=>{toast('Failed', `Failed to update ${VIEW} status. Something went wrong.`, 'danger'); _hideLoading();},
					success: (resp)=>{
						_hideLoading();
						if (resp.status){
							if (resp.data){
								toast('Status Update', `${VIEW} status successfully updated.`, 'success');
								app.empTable.ajax.reload(null, false);
							}else{
								toast('Information', 'No changes made.', 'info');
							}
						}else{
							_alert(
								'Information',
								`Cannot deactivate status. <strong>${this.empData[this.dtIndex].f_name} ${this.empData[this.dtIndex].m_name} ${this.empData[this.dtIndex].l_name}</strong> have trip schedule assigned.`,
								'blue'
							);
						}
					}
				});
			},
			
			clearFields: function(){
				let app = this;
				setTimeout(function(){
					app.form = {
						f_name: '',
						m_name: '',
						l_name: '',
						license_no: '',
						contact_no: '',
						address: '',
						password: '',
						cpassword: '',
						role: VIEW,
					}
					$('#userImg').attr('src', `${BASE_URL}assets/img/user-gray.png`);
					$('#imgInput').val('');
					$('.is-invalid').each(function(){
						$(this).removeClass('is-invalid');
					});
				}, 300);
			},
			setFormData: function(data){
				this.form = {
					f_name: data.f_name,
					m_name: data.m_name,
					l_name: data.l_name,
					license_no: data.license_no,
					contact_no: data.contact_no,
					address: data.address,
					password: '',
					cpassword: '',
					role: 'driver'
				}
				$('#userImg').attr('src', BASE_URL+data.img_url);
			},

			// DATA TABLE -------------------------------------------------------------------------
			// Set driver table
			setDriverTable: function(){
				let app = this;
				let controller = VIEW === 'Driver' ? 'fetchAllDriver' : 'fetchAllClerk';
				let column = VIEW === 'Driver' ? [ 0, 1, 2, 3, 4 ] : [ 0, 1, 2, 4 ];
				let date = $.format.date(new Date(), 'yyyy-MM-dd');

				this.empTable = $('#empTable').DataTable({
          responsive: true,
          stateSave: true,
          pageLength: 10,
          dom: 'Blfrtip',
          buttons: [{
            extend: 'print',
            title: `<div class="d-flex align-items-center justify-content-between">
                      <h4 class="modal-title">${VIEW}s</h4>
                      <h5>Date: ${date}</h5>
                    </div>`,
            exportOptions: {
              columns: column
            }
          },{
            extend: 'excel',
            title: `${VIEW}s - Date: ${date}`,
            exportOptions: {
              columns: column
            }
          },{
            extend: 'csv',
            title: `${VIEW}s - Date: ${date}`,
            exportOptions: {
              columns: column
            }
          },{
            extend: 'pdf',
            title: `${VIEW}s - Date: ${date}`,
            exportOptions: {
              columns: column
            }
          }],
          ajax: {
          	url: `${BASE_URL}employee/${controller}`,
          	dataSrc: function(json){
          		app.empData = json.data;
          		return json.data;
          	}
          },
          columns: [
            {'data':'employee_id'},
            {'data':''},
            {'data':'contact_no'},
            {'data':'license_no'},
            {'data':''}
          ],
          columnDefs: [{
			        targets: '_all',
			        className: 'td-center'
			    },{
          	targets: 1,
          	render: function(data, type, row, meta){
          		if (row['img_url'].trim() === ''){
          			row['img_url'] = `assets/img/user-gray.png`;
          		}
          		return `<img class="rounded border-gray mr-2" height="45px" width="45px" src="${BASE_URL}${row['img_url']}"></img>
          						<strong>${row['f_name']} ${row['m_name']} ${row['l_name']}</strong>
          					`;
          	}
          },{
          	targets: 4,
          	render: function(data, type, row, meta){
          		if (row['status'] == 1){
          			return `<div class="badge badge-success">
          								Active
          							</div>`;
          		}else{
          			return `<div class="badge badge-danger">
          								Deactivated
          							</div>`;
          		}
          	}
          },{
          	targets: 5,
          	render: function(data, type, row, meta){
          		html = `<div class="btn-group ml-2" role="group">
                        <button class="btn btn-sm btn-outline-info"
                          title="Update Driver"
                          data-update="${meta.row}">
                          <i class="fa fa-edit"></i>
                        </button>`;
                    if (row['status'] == 1){
                    	html += `<button class="btn btn-sm btn-outline-danger"
			                          title="Deactivate User"
			                          id="${meta.row}"
			                          data-status="0">
			                          <i class="fa fa-times"></i>
			                        </button>`;
                    }else{
                    	html += `<button class="btn btn-sm btn-outline-success"
			                          title="Activate User"
			                          id="${meta.row}"
			                          data-status="1">
			                          <i class="fa fa-check"></i>
			                        </button>`;
                    }
              html+= `</div>`;
              return html;
          	}
          }]
        });

        // Print/dowload button
        $('#d-btnPrint').click(()=>{
          $('#empTable_wrapper').find('.buttons-print').click();
        });
        $('#d-btnExcel').click(()=>{
          $('#empTable_wrapper').find('.buttons-excel').click();
        });
        $('#d-btnCsv').click(()=>{
          $('#empTable_wrapper').find('.buttons-csv').click();
        });
        $('#d-btnPdf').click(()=>{
          $('#empTable_wrapper').find('.buttons-pdf').click();
        });
      } // End set driver table
			// END DATA TABLE -------------------------------------------------------------------------
		
		} // End methods
	});

})();