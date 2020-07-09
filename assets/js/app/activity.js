$(function(){

  _allRouteTripCallback = ()=>{};

  /*
  |--------------------------------------------------------------------------
  | ACTIVITY
  |--------------------------------------------------------------------------
  */
  var lastId = 0;
  var limit = 1;
  var isActivityLoaded = false;
  var activityRecord = [];

  if (type === 'activity'){
    $('#activitySpinner').hide();
    if (!isActivityLoaded){
      loadActivity();
    }
  }

  $('#activity-tab').click(function(){
    $('#activitySpinner').hide();
    if (!isActivityLoaded){
      loadActivity();
    }
  });

  $('#btnTryAgain').click(function(){
    $('#tryAgain').addClass('d-none');
    loadActivity();
  });

  $('#loadMore').click(function(){
    $(this).addClass('d-none');
    loadActivity();
  });

  $('#btnFilter').click(function(){
    filterActivity();
  });

  $('#btnClearFilter').click(function(){
    $('#activityCategory').val('');
    $('#activityDate').val('');
    filterActivity();
  });

  function filterActivity(){
    lastId = 0;
    activityRecord = [];
    $('#loadMore').addClass('d-none');
    $('#tryAgain').addClass('d-none');
    $('#noResult').addClass('d-none');
    $('#activityContainer').empty();
    loadActivity();
  }

  // Load activity
  function loadActivity(){
    $('#activitySpinner').toggle();
    $.ajax({
      url: `${BASE_URL}logs/accountActivity`,
      type: 'POST',
      dataType: 'json',
      data: {last_id: lastId, limit: limit, filter: $('#activityCategory').val(), date: $('#activityDate').val()},
      error: function(){
        setTimeout(function(){
          $('#activitySpinner').toggle();
          $('#tryAgain').removeClass('d-none');
        }, 1000);
      },
      success: function(resp){
        isActivityLoaded = true;
        setTimeout(function(){
          $('#activitySpinner').toggle();
          if (resp.data != false){
            lastId = resp.data[resp.data.length - 1][resp.data[resp.data.length - 1].length - 1].id;
            renderActivity(resp);
            if (resp.count > 0){
              $('#loadMore').removeClass('d-none');
            }
          }else{
            $('#noResult').removeClass('d-none');
          }
        }, 1000);
      }
    });
  }
  // End load activity
  
  function renderActivity(resp){
    for (var i=0; i<resp.data.length; i++){
      $date = $.format.date(new Date(resp.data[i][0].created_on), 'MMM d, yyyy');
      if (i == 0){
        if ($.format.date(new Date(resp.data[i][0].created_on), 'yyyy-MM-dd') === $.format.date(new Date(), 'yyyy-MM-dd')){
          $date = 'Today';
        }
      }
      let id = resp.data[i][0].id;
      html = `<div class="card mb-3">
                <div class="card-header d-flex align-items-center">
                  <strong>${ $date }</strong>
                  <button class="btn btn-outline-default btn-sm ml-auto mr-0 btn-toggle" id="#activity${id}">
                    <i class="fa fa-chevron-down" id="fa${id}"></i>
                  </button>
                </div>
                <div class="card-body" id="activity${id}">`;

              for(var a=0; a<resp.data[i].length; a++){
                if (a > 0){
                  html += `<hr>`;
                }

                const activity = resp.data[i][a].activity.toUpperCase();

                let bgClass = 'bg-secondary';
                if(activity.includes('DEACTIVATE')){
                  bgClass = 'bg-warning';
                }else if (activity.includes('ADDED') || activity.includes('ACTIVATE')){
                  bgClass = 'bg-success';
                }else if(activity.includes('UPDATE') || activity.includes('REPLIED')){
                  bgClass = 'bg-info';
                }else if(activity.includes('CANCELLED') || activity.includes('DELETE')){
                  bgClass = 'bg-danger';
                }

                let faClass = 'fa-tasks';
                if (resp.data[i][a].table === 'trip' || resp.data[i][a].table === 'route'){
                  faClass = 'fa-road';
                }else if (resp.data[i][a].table === 'seat'){
                  faClass = 'fa-book';
                }else if (resp.data[i][a].table === 'uv_unit'){
                  faClass = 'fa-bus';
                }else if (resp.data[i][a].table === 'employee'){
                  faClass = 'fa-users';
                }else if (resp.data[i][a].table === 'accident_contact'){
                  faClass = 'fa-phone';
                }else if (resp.data[i][a].table === 'feedback'){
                  faClass = 'fa-comment';
                }else if (resp.data[i][a].table === 'None'){
                  faClass = 'fa-user';
                  bgClass = 'bg-primary';
                }

                const icon = `<div class="d-flex rounded-circle ${bgClass}"  style="min-height: 40px; min-width: 40px">
                              <i class="fa ${faClass} text-white m-auto"></i>
                            </div>`;
                
                let table = '';
                if (resp.data[i][a].table !== 'None'){
                  activityRecord.push(resp.data[i][a]);
                  table = `<button class="btn btn-outline-secondary btn-sm data-view" title="View recorded data" id="${activityRecord.length - 1}">
                              <i class="fa fa-list fa-sm"></i>
                            </button>`;
                }
                html+= `<div class="d-flex align-items-center activity-item">
                          ${icon}
                          <div class="ml-3">
                            <h6 class="text-gray-900 mb-0">
                              ${resp.data[i][a].activity}                            </h6>
                            <small class="mt-0">
                              ${$.format.date(new Date(resp.data[i][a].created_on), 'h:mm a')}
                            </small>
                          </div>
                          <div class="ml-auto mr-0">
                            ${table}
                          </div>
                        </div>`;
              }
      html +=   `</div>
              </div>`;

      $('#activityContainer').append(html);
      if ($('#activityContainer').children().length > 1){
        $(`#activity${id}`).toggle();
      }else{
        toggleBtn($(`#fa${id}`));
      }
      
    }
  }

  $(document).on('click', '.data-view', function(){
    let log = activityRecord[$(this).attr('id')];
    $('#recordData').empty();
    $('#recordData').append(
      `<h6 class="p-1">RECORD NAME: <strong class="ml-2">${log.table.replace('_', ' ').toUpperCase()}</strong>`
    );
    let data = JSON.parse(log.data);
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

  $(document).on('click', '.btn-toggle', function(){
    $($(this).attr('id')).toggle();
    toggleBtn($(this).children('i'));
  });

  function toggleBtn(el){
    if ($(el).hasClass('fa-chevron-down')){
      $(el).removeClass('fa-chevron-down');
      $(el).addClass('fa-chevron-up');
    }else{
      $(el).removeClass('fa-chevron-up');
      $(el).addClass('fa-chevron-down');
    }
  }

  /*
  |--------------------------------------------------------------------------
  | ACCOUNT
  |--------------------------------------------------------------------------
  */
  var userImgSrc = $('#userImg').attr('src');
  var uploading = false;

  function showUserImgMenu(){
    if (!uploading){
      $('#userImgMenu').removeClass('d-none');
      $('#userImgMenu').addClass('d-flex');
    }
  }
  function hideUserImgMenu(){
    if (!uploading){
      $('#userImgMenu').removeClass('d-flex');
      $('#userImgMenu').addClass('d-none');
    }
  }
  function showUploadSpinner(){
    $('#btnImgChange').hide();
    $('#imgSpinner').removeClass('d-none');
    showUserImgMenu();
    uploading = true;
  }
  function hideUploadSpinner(){
    uploading = false;
    $('#btnImgChange').show();
    $('#imgSpinner').addClass('d-none');
    hideUserImgMenu();
    $('#imgInput').val('');
  }

  // Account image
  $('#userImg').mouseenter(function(){
    showUserImgMenu();
  });
  $('#userImgMenu').mouseleave(function(){
    hideUserImgMenu();
  });

  $('#btnImgChange').click(function(){
    $('#imgInput').click();
  });

  $('#imgInput').on('change', function(){
    if(this.files[0].type=='image/jpeg'||this.files[0].type=='image/png'){
      if(this.files && this.files[0]){
        var reader = new FileReader();
        reader.onload = function(e){
          uploadImage(e.target.result);
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

  function uploadImage(src){
    let formData = new FormData();
    if ($('#imgInput').prop('files')[0]){
      formData.append('img', $('#imgInput').prop('files')[0]);
    }
    showUploadSpinner();
    $.ajax({
      url: `${BASE_URL}user/changeImage`,
      type: 'POST',
      dataType: 'json',
      processData : false,
      contentType : false,
      data: formData,
      error: function(){
        setTimeout(function(){
          hideUploadSpinner();
          $.alert({
            title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
            content: `Uploading failed. Try again.`,
          });
        }, 1000);
      },
      success: function(resp){
        setTimeout(function(){
          if (resp.status){
            if (resp.data){
              $('#userImg').attr('src', src);
              $('#navUserImg').attr('src', src);
            }else{
              $.alert({
                title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
                content: `No changes made.`,
              });
            }
          }else{
            $.alert({
              title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
              content: `Image not found.`,
            });
          }
          hideUploadSpinner();
        }, 1000);
      }
    });
  }

  // Save changes buton
  $('#btnSaveChange').click(function(){
    let hasError = false;
    if ($('#contactNo').val().trim() === ''){
      $('#contactNo').addClass('is-invalid');
      toast('Required', 'Contact number is required!', 'danger');
      hasError = true;
    }
    if ($('#address').val().trim() === ''){
      $('#address').addClass('is-invalid');
      toast('Required', 'Address is required!', 'danger');
      hasError = true;
    }

    if (hasError){
      return false;
    }

    $('#contactNo').removeClass('is-invalid');
    $('#address').removeClass('is-invalid');

    _showLoading();

    $.ajax({
      url: `${BASE_URL}user/updateProfile`,
      type: 'POST',
      dataType: 'json',
      data: {contact_no: $('#contactNo').val(), address: $('#address').val()},
      error: function(){
        _hideLoading();
        $.alert({
          title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
          content: `Failed to update account.`,
        });
      },
      success: function(resp){
        _hideLoading();
        if (resp.status){
          if (resp.data){
            _success({
              title: 'Success',
              content: 'Account successfully updated.',
              confirm: {
                text: 'Ok',
                btnClass: 'btn-blue',
                action: ()=>{}
              }
            });
          }else{
            $.alert({
              title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
              content: `No changes made.`,
            });
          }
        }else{
          $.alert({
            title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
            content: `Contact number already exists.`,
          });
        }
      }
    });
  });

  // Change password button
  $('#btnSavePass').click(function(){
    let hasError = false;
    if ($('#pass1').val().trim() === ''){
      $('#pass1').addClass('is-invalid');
      toast('Required', 'Please enter your current password.', 'danger');
      hasError = true;
    }
    if ($('#pass2').val().trim() === ''){
      $('#pass2').addClass('is-invalid');
      toast('Required', 'Please enter your new password.', 'danger');
      hasError = true;
    }
    if ($('#pass1').val().trim() !== '' && $('#pass1').val().trim() === $('#pass2').val().trim()){
      toast('Required', 'New password and current password should not match.', 'danger');
      return false;
    }
    if ($('#pass3').val().trim() === ''){
      $('#pass3').addClass('is-invalid');
      toast('Required', 'Please retype your new password.', 'danger');
      hasError = true;
    }
    if ($('#pass2').val().trim() !== $('#pass3').val().trim()){
      $('#pass3').addClass('is-invalid');
      toast('Required', 'Retype password does not match your new password. Try Again.', 'danger');
      hasError = true;
    }

    if ($('#pass2').val().length < 8){
      $('#pass3').addClass('is-invalid');
      toast('Required', 'Password must be atleast 8 characters long.', 'danger');
      hasError = true;
    }

    if (hasError){
      return false;
    }

    $('#pass1').removeClass('is-invalid');
    $('#pass2').removeClass('is-invalid');
    $('#pass3').removeClass('is-invalid');

    _showLoading();
    $.ajax({
      url: `${BASE_URL}user/changeAccountPassword`,
      type: 'POST',
      dataType: 'json',
      data: {new_pass: $('#pass2').val(), old_pass: $('#pass1').val()},
      error: function(){
        _hideLoading();
        $.alert({
          title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
          content: `Failed to change password.`,
        });
      },
      success: function(resp){
        _hideLoading();
        if (resp.data){
          $('#pass1').val('');
          $('#pass2').val('');
          $('#pass3').val('');
          _success({
            title: 'Success',
            content: 'Account password successfully changed.',
            confirm: {
              text: 'Ok',
              btnClass: 'btn-blue',
              action: ()=>{}
            }
          });
        }else{
          $.alert({
            title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
            content: `Wrong current password. Try again.`,
          });
          $('#pass1').addClass('is-invalid');
        }
      }
    })
  });
  

});