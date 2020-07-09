$(function(){
  _allRouteTripCallback = ()=>{};
  var logData = null;

  $('#dataTable').DataTable({
    responsive: true,
    stateSave: true,
    pageLength: 10,
    bSort: false,
    dom: 'Blfrtip',
    ajax: {
      url: `${BASE_URL}logs/getAllLogs`,
      dataSrc: function(json){
        logData = json.data;
        console.log(logData);
        return json.data;
      }
    },
    columns:[
      {data: 'id'},
      {data: 'activity'},
      {data: ''},
      {data: 'role'},
      {data: 'created_on'},
      {data: 'ref_id'},
    ],
    columnDefs: [{
      targets: 2,
      render: function(data, type, row, meta){
        return `${row['f_name']} ${row['l_name']}`;
      }
    },{
      targets: 6,
      render: function(data, type, row, meta){
        html = ``;
        if (row.data !== 'None'){
          html = `<button class="btn btn-outline-info btn-sm" title="View Record" data-view="${meta.row}">
                    <i class="fa fa-eye"></i>
                  </button>`;
        }
        return html;
      }
    }],
    buttons: [{
      extend: 'print',
      title: `User Activity`,
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5]
      }
    },{
      extend: 'excel',
      title: `User Activity`,
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5]
      }
    },{
      extend: 'csv',
      title: `User Activity`,
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5]
      }
    },{
      extend: 'pdf',
      title: `User Activity`,
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5]
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

  $(document).on('click', '[data-view]', function(){
    const data = JSON.parse(logData[$(this).data('view')].data);
    $('#recordData').empty();
    $('#recordData').append(
      `<h6 class="p-1">RECORD NAME: <strong class="ml-2">${logData[$(this).data('view')].table.replace('_', ' ').toUpperCase()}</strong>`
    );
    Object.keys(data).forEach(function(key, i, val){
      let k = key.replace('_', ' ').toUpperCase();
      if(data[key] == null){
        data[key] = 'None';
      }

      if (key === 'status' && (data[key] == 1 || data[key] == 0)){
        if(data[key] == 1){
          data[key] = 'Active';
        }else{
          data[key] = 'Deactivated';
        }
      }
      $('#recordData').append(
        `<hr class="mt-2 mb-2"><h6 class="p-1">${k}: <strong class="ml-2">${data[key]}</strong> </h6>`
      );
    });
    $('#logData').modal('toggle');
  });

});