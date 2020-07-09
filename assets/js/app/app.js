// GLOBAL VARIABLES
var BASE_URL = `${window.location.origin}/findme-uv/`;
var LOCATION = {lat: 11.240988, lng: 125.002026};
var G_MAP;
var STICKY_NAV;

// GLOBAL METHODS
var _showLoading;
var _hideLoading;
var _confirm;
var _alert;
var _success;
var _allRouteTripSync;
var _allRouteTripCallback;
var _getAllRouteTripSessionStorage;

(function(){

  // Document ready
  $(document).ready(function(){
    /*
    |--------------------------------------------------------------------------
    | Init global methods
    |--------------------------------------------------------------------------
    */
   
    // Hide loading
    _hideLoading = function(){
      setTimeout(function(){
        $('.loading-container').hide();
        $('body').removeClass('no-scroll');
      }, 300);
    }
    //  Show loading
    _showLoading = function(){
      $('.loading-container').show();
    }

    // Confirm dialog
    _confirm = function(message, yesCallback, noCallback){ // Start confirm
      $.confirm({
        title: 'Confirm',
        content: message,
        icon: 'fa fa-question-circle',
        theme: 'modern',
        draggable: true,
        closeIcon: false,
        animation: 'scale',
        type: 'red',
        escapeKey: 'No',
        autoClose: '',
        buttons: {
          confirm: {
            text: 'Yes',
            keys: ['enter'],
            action: function(){
              yesCallback();
            }
          },
          No: function(){
            noCallback()
          }
        }
      });
    } // End confirm

    _alert = function(title, message, typeColor){
      $.alert({
          title: title,
          content: message,
          icon: 'fa fa-exclamation-circle',
          theme: 'modern',
          closeIcon: true,
          animation: 'scale',
          type: typeColor,
          escapeKey: 'No',
          buttons: {  
            Ok: function(){}
          }
      });
    }
    _success = function(obj){
        $.confirm({
          title: obj.title,
          content: obj.content,
          icon: 'fa fa-check',
          theme: 'modern',
          closeIcon: false,
          animation: 'scale',
          type: 'green',
          buttons: {
            confirm: obj.confirm
          }
        });
      }

      
    _getAllRouteTripSessionStorage = ()=>{ return JSON.parse(sessionStorage.getItem('all_route_trip')); }

    /*
    |--------------------------------------------------------------------------
    | Error custom event handler
    |--------------------------------------------------------------------------
    */
   
    // Data table error handler
    if($.fn.dataTable !== undefined){
      sessionStorage.setItem('dt-error', '');
      $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {
          _hideLoading();
          _alert('System Error', message, 'red');
      };
    }

    // Ajax error handler
    $( document ).ajaxError(function(event, jqxhr, settings, thrownError) {
      if (!$('#serverFailed').is(':visible')){
        $('#serverFailed').show();
      }
    });
    // Ajax success handler
    $( document ).ajaxSuccess(function() {
      if ($('#serverFailed').is(':visible')){
        $('#serverFailed').hide();
      }
    });

    /*
    |--------------------------------------------------------------------------
    | Global ui
    |--------------------------------------------------------------------------
    */
   
    _hideLoading();
    setTimeout(function(){
      $('.loading-container').addClass('load-circle');
    }, 300);

    // Load Library
    $('.selectpicker').selectpicker('setStyle', 'select-gray');

    /*
    |--------------------------------------------------------------------------
    | Event handler
    |--------------------------------------------------------------------------
    */
   
    // Delete record callback
    $('.delete').click(function(){
      link = this.id
      if (confirm("Are you sure you want to delete record?")) {
        window.location.replace(link);
      }
    });

    $('.msg').click(function(){
      msg = this.id;
      $('.p-msg').text(msg);
      $('#modal-msg').modal('show');
    });

    $('#modal-msg-close').click(function(){
      $('#modal-msg').modal('toggle');
    });

    // Remove error class on input
    $('.form-control').each(function(){
      $(this).change(()=>{
        $(this).removeClass('is-invalid');
        $(`button[data-id="${$(this).attr('id')}"]`).removeClass('is-invalid');
      });
    });

    $('[data-tooltip]').each(function(){
      $(this).attr('rel', 'tooltip');
      $(this).attr('data-toggle', 'tooltip');
      $(this).attr('title', $(this).data('tooltip'));
      $(this).attr('data-placement', 'top');
    });

    /*
    |--------------------------------------------------------------------------
    | AJAX
    |--------------------------------------------------------------------------
    */
   
    // Get all route trip
    let isAllRouteTripInit = false;
    var param = {
      func: 'tripCount'
    };
    function getAllRouteTrip(){
      $.ajax({
        url: `${BASE_URL}trip_management/getAllRouteTrip`,
        type: 'GET',
        dataType: 'json',
        error: function(){
          setTimeout(function(){
            getAllRouteTrip();
          }, 5000);
        },
        success: function(resp){
          setTimeout(function(){
            getAllRouteTrip();
          }, 5000);
          if (resp.status){
            let data = resp.data;
            _allRouteTripCallback(data);

            if(isAllRouteTripInit){
              $.each(data , function(i){ // Check data if new, toast if
                $.each(_getAllRouteTripSessionStorage() , function(){
                  if (this.route_id === data[i].route_id){
                    if (this.count.pending != data[i].count.pending){
                      if (this.count.pending <  data[i].count.pending){
                        toast(
                          'New Trip Schedule',
                          `${data[i].origin} to ${data[i].destination} have new pending trip.`,
                          'info'
                        );
                      }
                    }
                  }
                });
              }); // End toast if
            }

            sessionStorage.setItem('all_route_trip', JSON.stringify(resp.data));
            isAllRouteTripInit = true;
          }
        }
      });
    }
    getAllRouteTrip();

  });//Documnet ready end

})();