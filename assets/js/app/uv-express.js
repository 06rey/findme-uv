(function(){

var app = new Vue({
	el: '#app',
	data: {
		uvTable: null,
		formMode: null,
		form: {
			plate_no: '',
			max_pass: '',
			franchise_no: '',
			model: '',
			brand_name: ''
		},

		selectedRow: [],
	},
	/*
  |--------------------------------------------------------------------------
  | MOUNTED
  |--------------------------------------------------------------------------
  */
	mounted: function(){
		let app = this;
		_allRouteTripCallback = function(){};

		this.setUvTable();

		$('#btnAddUv').click(function(){
			app.formMode = 'Add';
			$('#formModal').modal('toggle');
		});

		$(document).on('click', 'button[data-update]', function(){
			app.formMode = 'Update';
			app.selectedRow = [];
			$(this).closest('tr').children().each(function(){
				app.selectedRow.push($(this).text());
			});
			app.setFormData(app.selectedRow);
			$('#formModal').modal('toggle');
		});

		$('#formModal').on('hidden.bs.modal', function() {
      if (app.formMode === 'Update'){
      	app.clearFields();
      }
    });

    $('.is-invalid').change(function(){
    	$(this).removeClass('is-invalid');
    });	

	},
	/*
  |--------------------------------------------------------------------------
  | METHODS
  |--------------------------------------------------------------------------
  */
	methods: {
		// Forms
		clearFields: function(){
			app.form = {
				plate_no: '',
				max_pass: '',
				franchise_no: '',
				model: '',
				brand_name: ''
			}
			$('.is-invalid').each(function(){
				$(this).removeClass('is-invalid');
			});
		},

		submitForm: function(){
			let app = this;
			let validate = true;
			Object.keys(this.form).forEach((key, i)=>{
				if (this.form[key].trim() === ''){
					$(`#${key}`).addClass('is-invalid');
					toast('Required', `${$(`#${key}`).siblings('label').text()} is required!`, 'danger');
					validate = false;
				}
			});
			if (!validate) { return }
			_showLoading();
			$.ajax({
				url: `${BASE_URL}uv_unit/${(this.formMode === 'Add') ? 'insertUvExpress' : `updateUvExpress/${app.selectedRow[0]}`}`,
				type: 'POST',
				dataType: 'json',
				data: app.form,
				error: ()=>{ toast('Failed', 'Failed to save UV Express. Something went wrong.', 'danger') },
				success: function(resp){
					_hideLoading();
					if (resp.data){
						toast('Success', 'UV Express successfully saved.', 'success');
						app.uvTable.ajax.reload(null, false);
						app.formMode === 'Add' ? app.clearFields() : $('#formModal').modal('toggle');
					}else{
						toast('Information', 'No changes made', 'info');
					}
				}
			});
		},

		setFormData: function(data){
			this.form = {
				plate_no: data[1],
				max_pass: data[5],
				franchise_no: data[2],
				model: data[3],
				brand_name: data[4]
			}
		},

		// set uv table
		setUvTable: function(){
			let date = $.format.date(new Date(), 'yyyy-MM-dd');
			this.uvTable = $('#uvTable').DataTable({
          responsive: true,
          stateSave: true,
          pageLength: 10,
          dom: 'Blfrtip',
          buttons: [{
            extend: 'print',
            title: `<div class="d-flex align-items-center justify-content-between">
                      <h4 class="modal-title">UV Express</h4>
                      <h5>Date: ${date}</h5>
                    </div>`,
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4, 5 ]
            }
          },{
            extend: 'excel',
            title: `UV Express - Date: ${date}`,
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4, 5 ]
            }
          },{
            extend: 'csv',
            title: `UV Express - Date: ${date}`,
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4, 5 ]
            }
          },{
            extend: 'pdf',
            title: `UV Express - Date: ${date}`,
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4, 5 ]
            }
          }],
          ajax: `${BASE_URL}/uv_unit/fetchUvExpress`,
          columns: [
            {'data':'uv_id'},
            {'data':'plate_no'},
            {'data':'franchise_no'},
            {'data':'model'},
            {'data':'brand_name'},
            {'data':'max_pass'}
          ],
          columnDefs: [{
          	targets: 6,
          	render: function(data, type, row, meta){
          		return `<div class="btn-group ml-2" role="group">
                        <button class="btn btn-sm btn-outline-info"
                          href="#"
                          title="Update UV Express"
                          data-update="${row['uv_id']}">
                          <i class="fa fa-edit"></i>
                        </button>`;
          	}
          }]
        });

        // Print/dowload button
        $('#uv-btnPrint').click(()=>{
          $('#uvTable_wrapper').find('.buttons-print').click();
        });
        $('#uv-btnExcel').click(()=>{
          $('#uvTable_wrapper').find('.buttons-excel').click();
        });
        $('#uv-btnCsv').click(()=>{
          $('#uvTable_wrapper').find('.buttons-csv').click();
        });
        $('#uv-btnPdf').click(()=>{
          $('#uvTable_wrapper').find('.buttons-pdf').click();
        });
		}, // Set uv table end
	}
});

})()