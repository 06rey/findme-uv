(function(){

  var app = new Vue({

    el: '#app',
    data: {
      lastId: 0,
      count: null,
      keywords: '',
      loaded: false
    },

    mounted: function(){
      let app = this;
      _allRouteTripCallback = ()=>{};
      $('#tryAgainContainer').hide();
      this.loadFeedback();

      // Toggle reply container
      $(document).on('click', '[data-view]', function(){
        app.togglefeedbackReply($(this).data('view'));
      });

      // Toggle reply field
      $(document).on('click', '[data-reply]', function(){
        app.toggleReplyField($(this).data('reply'));
      });

      // Reply send button
      $(document).on('click', '.btn-send', function(){
        let id = $(this).attr('id').replace('replySendBtn', '');
        if ($(`#replyMsg${id}`).val().trim() !== ''){
          app.sendReply(id, $(`#replyMsg${id}`).val());
        }else{
          $.alert({
            title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
            content: `Please enter a message.`,
          });
        }
      });
    },

    methods: {

      syncReply: function(id){
        let postData = {};
        let replyIds = {};
        let idLen = 0;
        $(`#replyContainer${id}`).children('[data-replyItem]').each(function(i){
          replyIds[`id${i}`] = $(this).attr('data-replyItem');
          idLen++;
        });
        postData['id'] = id;
        postData['idLen'] = idLen;
        postData['replyIds'] = JSON.stringify(replyIds);

        $.ajax({
          url: `${BASE_URL}logs/syncReply`,
          type: 'POST',
          dataType: 'json',
          data: postData,
          success: function(resp){
            if (resp.type === 'count'){
              if (!$(`[data-view="${id}"]`).is(':visible') && (resp.data > 0)){
                $(`[data-view="${id}"]`).show();
                $(`[data-reply="${id}"]`).hide();
              }
            }else{
              if (resp.status){
                app.renderFeedbackReply(resp.data, id);
                $(`#parent${id}`).scrollTop($(`#replyContainer${id}`).height());
              }
            }
          }
        });
      },

      syncFeedBack: function(){
        let app = this;
        let postData = {};
        $('[data-feed]').each(function(i){
          postData[`feed${i}`] = $(this).data('feed');
        });
        postData['filter'] = this.keywords;

        $.ajax({
          url: `${BASE_URL}logs/syncFeedback`,
          type: 'POST',
          dataType: 'json',
          data: postData,
          success: function(resp){
            if (resp.status){
              for(var i=0; i<resp.data.length; i++){
                app.renderFeedback(resp.data[i], false);
              }
            }
          }
        });
      },

      // UI METHODS
      togglefeedbackReply: function(id){
        $(`[data-view="${id}"]`).children('span').text($(`#replyContainer${id}`).is(':visible') ? 'View' : 'Hide');
        function toggleMenu(attr){
          $(`#menuDivider${attr}`).toggle();
          $(`[data-reply="${attr}"]`).toggle();
          if ($(`#replyField${attr}`).is(':visible')){
            app.toggleButton($(`[data-reply="${attr}"]`), $(`#replyField${attr}`));
          }
        }
        if ($(`#replyContainer${id}`).children().length < 1){
          app.loadFeedbackReply(id);
        }else{
          toggleMenu(id);
        }
        app.toggleButton($(`[data-view="${id}"]`), $(`#replyContainer${id}`));
      },

      toggleReplyField: function(id){
        $('[data-reply]').each(function(){
          let rId = $(this).data('reply');
          if (id !== rId && $(`#replyField${rId}`).is(':visible')){
            app.toggleButton($(this), $(`#replyField${rId}`));
          }
        });
        app.toggleButton($(`[data-reply="${id}"]`), $(`#replyField${id}`));
      },

      toggleButton: function(toggleBtn, target){
        if (target != null){
           $(target).slideToggle();
        }
        if ($(toggleBtn).children('i').hasClass('fa-chevron-down')){
          $(toggleBtn).children('i').removeClass('fa-chevron-down');
          $(toggleBtn).children('i').addClass('fa-chevron-up');
        }else{
          $(toggleBtn).children('i').removeClass('fa-chevron-up');
          $(toggleBtn).children('i').addClass('fa-chevron-down');
        }
      },
      // END UI METHODS
      
      searchFeedback: function(){
        if (this.keywords === ''){
          $('#searchFeedback').addClass('is-invalid');
          $.alert({
            title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
            content: `Please enter search keywords.`,
          });
          return false;
        }
        this.lastId = 0;
        this.count = null;
        $('#searchFeedback').removeClass('is-invalid');
        $('#resultHeader').removeClass('d-none');
        $('#feedbackContainer').empty();
        this.loadFeedback();
        $('#resultKeyword').text(this.keywords);
      },

      clearSearch: function(){
        this.lastId = 0;
        this.count = null;
        this.keywords = '';
        $('#resultHeader').addClass('d-none');
        $('#feedbackContainer').empty();
        this.loadFeedback();
      },
      
      sendReply: function(feedbackId, msg){
        let app = this;
        _showLoading();
        $.ajax({
          url: `${BASE_URL}logs/sendReply`,
          type: 'POST',
          dataType: 'json',
          data: {message: msg, feedback_id: feedbackId},
          error: function(){
            _hideLoading();
            $.alert({
              title: '<h6 class="modal-title font-weight-bold">System Message</h6>',
              content: `Message not sent. Try again.`,
            });
          },
          success: function(resp){
            $(`#replyMsg${feedbackId}`).val('');
            _hideLoading();
            if (resp.data.length > 0){
              app.renderFeedbackReply(resp.data, feedbackId);
              if ($(`#replyContainer${feedbackId}`).children().length < 2){
                let toggleBtn = $(`[data-view="${feedbackId}"]`);
                $(toggleBtn).children('span').text('Hide');
                $(toggleBtn).show();
                $(`#menuDivider${feedbackId}`).show();
                app.toggleButton(toggleBtn, null);
              }
              $(`#parent${feedbackId}`).scrollTop($(`#replyContainer${feedbackId}`).height());
            }
          }
        });
      },

      loadFeedbackReply: function(feedbackId){
        let app = this;
        $(`#replyLoadingSpin${feedbackId}`).show();
        $(`[data-view="${feedbackId}"]`).hide();
        $.ajax({
          url: `${BASE_URL}logs/feedbackReply`,
          type: 'POST',
          dataType: 'json',
          data: {feedback_id: feedbackId},
          error: function(){
          },
          success: function(resp){
            if (resp.data.length > 0){
              setTimeout(function(){
                app.renderFeedbackReply(resp.data, feedbackId);
                $(`[data-view="${feedbackId}"]`).show();
                $(`#replyLoadingSpin${feedbackId}`).hide();
                $(`[data-reply="${feedbackId}"]`).show();
                $(`#menuDivider${feedbackId}`).show();
              }, 1000);
            }
          }
        });
      },

      loadFeedback: function(){
        $('#noFeedbackFound').addClass('d-none');
        let app = this;
        $('#loadMore').hide();
        $('#tryAgainContainer').hide();
        $('#loadingSpin').show();
        $.ajax({
          url: `${BASE_URL}logs/loadMoreFeedBack`,
          type: 'POST',
          dataType: 'json',
          data: {last_id: app.lastId, limit: 5, filter: app.keywords},
          error: function(){
            setTimeout(function(){
              $('#loadingSpin').hide();
              $('#tryAgainContainer').show();
            }, 1500);
          },
          success: function(resp){
            setTimeout(function(){
              $('#loadingSpin').hide();
              if (resp.status){
                app.lastId = resp.last_id;
                app.count = parseInt(resp.count);
                if (app.count > 0){
                  $('#loadMore').show();
                }else{
                  $('#loadMore').hide();
                }
                for(var i=0; i<resp.data.length; i++){
                  app.renderFeedback(resp.data[i], true);
                }
              }else{
                $('#noFeedbackFound').removeClass('d-none');
              }
              if (!app.loaded){
                setInterval(function(){
                  app.syncFeedBack();
                }, 5000);
              }
              app.loaded = true;
            }, 1500);
          }
        });
      },

      renderFeedbackReply: function(reply, feedbackId){
        $(`#replyContainer${feedbackId}`).show();
        for(var a=0; a<reply.length; a++){
          let replyBadge = '';
          var hours = Math.abs(new Date() - new Date(reply[a].date_added)) / 3.6e6;
          if (hours < 1){
            replyBadge = `<span class="badge badge-success mt-0 ml-2">New</span>`;
          }
          let senderImgSrc = `${BASE_URL}assets/img/user-gray.png`;
          if (reply[a].img_url !== ''){
            senderImgSrc = BASE_URL+reply[a].img_url;
          }

        html = `<div class="media" data-replyItem="${reply[a].reply_id}">
                  <div class="media-left mr-2">
                    <img src="${senderImgSrc}" class="media-object rounded" width="40px" height="40px">
                  </div>
                  <div class="media-body">
                    <div class="media-heading">
                      <h5 class="mr-2 d-inline font-weight-bold text-gray-800">${ reply[a].sender }</h5>
                      <span class="font-italic">
                        ${ $.format.date(reply[a].date_added, 'MMMM d yyyy, h:mm p') }
                      </span>
                      ${replyBadge}
                    </div>
                    <p>${reply[a].message}</p>
                  </div>
                </div>`;
          $(`#replyContainer${feedbackId}`).append(html);
        }
      },

      renderFeedback: function(row, append){
        var hours = Math.abs(new Date() - new Date(row['date_added'])) / 3.6e6;
        if (hours < 1){
          badge = `<span class="badge badge-success mt-0 ml-2">New</span>`;
        }else{
          badge = '';
        }

        html = `<div class="media mb-3" data-feed="${row.feedback_id}">
                  <div class="media-left mr-2">
                    <img src="${BASE_URL}assets/img/user-gray.png" class="media-object rounded" width="40px" height="40px">
                  </div>
                  <div class="media-body">
                    <div class="media-heading">
                      <h5 class="mr-2 d-inline font-weight-bold text-gray-800">${row.f_name} ${row.l_name}</h5>
                      <span class="font-italic">
                        ${ $.format.date(row['date_added'], 'MMMM d yyyy, h:mm p') }
                      </span>
                      ${badge}
                    </div>
                    <p class="mb-0 pb-0">
                    ${row.message}`;

        html += `</p>
                <div class="reply-container mt-2 mb-1" id="parent${row.feedback_id}">
                  <div id="replyContainer${row.feedback_id}">
                    
                  </div>
                </div>
                <div class="row align-items-center justify-content-center mb-3 mt-4" id="replyLoadingSpin${row.feedback_id}">
                  <div class="spinner-border spinner-border-sm text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </div>`;

        html += `  <a href="javascript:void(0)" class="font-weight-bold text-gray-600" data-view="${row.feedback_id}">
                      <span>View</span>
                      <i class="fa fa-chevron-down fa-sm" style="font-size: 10px"></i>
                    </a>
                    <span class="text-gray-400 ml-2 mr-2" id="menuDivider${row.feedback_id}">|</span>
                    <a href="javascript:void(0)" class="font-weight-bold text-gray-600" data-reply="${row.feedback_id}">
                      Reply
                      <i class="fa fa-chevron-down fa-sm" style="font-size: 10px"></i>
                    </a>

                    <div id="replyField${row.feedback_id}">
                      <div class="form-group">
                        <textarea class="form-control mt-2" placeholder="Type message" id="replyMsg${row.feedback_id}"></textarea>
                      </div>
                      <button class="btn btn-secondary btn-sm btn-send" id="replySendBtn${row.feedback_id}">Send</button>
                    </div>
                  </div>
                </div>
                <hr>`;
        append ? $(`#feedbackContainer`).append(html) : $(`#feedbackContainer`).prepend(html);
        if (row.reply_count < 1){
          $(`[data-view="${row.feedback_id}"]`).hide();
        }else{
          $(`[data-reply="${row.feedback_id}"]`).hide();
        }
        $(`#menuDivider${row.feedback_id}`).hide();
        $(`#replyContainer${row.feedback_id}`).hide();
        $(`#replyField${row.feedback_id}`).hide();
        $(`#replyLoadingSpin${row.feedback_id}`).hide();
        setInterval(function(){
          app.syncReply(row.feedback_id);
        }, 5000);
      }
    }
    // End methods
  });

})();