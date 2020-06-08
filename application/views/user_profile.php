<?php $this->load->view('partials/header');?>
  <!-- Begin Page Content -->
  <div class="container-fluid">
    <!-- Accocunt picture/name -->
    <div class="d-flex flex-column align-items-center mt-4 mb-4 account-head">
      <div>
        <div class="rounded-circle position-absolute d-none align-items-center justify-content-center" id="userImgMenu">
          <!-- <button class="btn btn-outline-secondary btn-sm" id="btnImgChange">
            Change Picture
          </button> -->
          <div class="i-img-panel" id="btnImgChange">
            <div>
              <i class="fa fa-image i-img"></i>
            </div>
            <small class="text-center">Change your account profile picture</small>
          </div>
          <div class="d-none" id="imgSpinner">
            <div class="dot-loading dot-3 text-white">
              <span></span>
            </div>
          </div>
        </div>
        <input type="file" id="imgInput" class="invisible position-absolute" name="image_src">
        <img class="rounded-circle" src="<?= base_url($data['img_url']) ?>" width="250px" height="250px" id="userImg">
      </div>
      <div class="d-flex flex-column align-items-center mt-2">
        <h3 class="h3 text-gray-900 mb-0 font-weight-bold"><?= $data['f_name'].' '.$data['l_name'] ?></h3>
        <small class="badge badge-info mt-1"><?=$data['role'] == 'admin' ? 'Administrator':'Booking Clerk'?></small>
      </div>
    </div>
    <!-- Account navbar -->
    <ul class="nav nav-tabs mt-3 mb-3 breadcrumb" id="profile-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link font-weight-bold pr-0 <?= $type == 'profile' ? 'active':''?>" id="account-tab" data-toggle="pill" href="#account" role="tab" aria-controls="account" aria-selected="true">
          Profile
        </a>
      </li>
      <div class="div-v mt-2"></div>
      <li class="nav-item">
        <a class="nav-link font-weight-bold pl-0 <?= $type == 'activity' ? 'active':''?>" id="activity-tab" data-toggle="pill" href="#activity" role="tab" aria-controls="activity" aria-selected="false">
          Activity Logs
        </a>
      </li>
    </ul>
    <!-- Tab content -->
    <div class="tab-content mb-3" id="account-tabContent">
      <!-- Profile tab pane -->
      <div class="tab-pane fade <?= $type == 'profile' ? 'active show':''?>" id="account" role="tabpanel" aria-labelledby="account-tab">
        <div class="card mb-3">
          <div class="card-header">
            <span class="h5 font-weight-bold">Personal</span>
          </div>
          <div class="card-body">
            <div class="col-md-7">
              <div class="form-group">
                <label>First Name</label>
                <input disabled class="form-control" type="text" value="<?=$data['f_name']?>">
              </div> 
              <div class="form-group">
                <label>Middle Name</label>
                <input disabled class="form-control" type="text" value="<?=$data['m_name']?>">
              </div> 
              <div class="form-group">
                <label>Last Name</label>
                <input disabled class="form-control" type="text" value="<?=$data['l_name']?>">
              </div> 
              <div class="form-group">
                <label>Contact Number</label>
                <input class="form-control" type="text" value="<?=$data['contact_no']?>" id="contactNo">
              </div> 
              <div class="form-group">
                <label>Address</label>
                <textarea class="form-control" id="address"><?=$data['address']?></textarea>
              </div>
              <button class="btn btn-info btn-sm" id="btnSaveChange">Save Changes</button>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <span class="h5 font-weight-bold">Change Password</span>
          </div>
          <div class="card-body">
            <div class="col-md-7">
              <div class="form-group">
                <label>Current Password</label>
                <input class="form-control" type="password" id="pass1">
              </div> 
              <div class="form-group">
                <label>New Password</label>
                <input class="form-control" type="password" id="pass2">
              </div> 
              <div class="form-group">
                <label>Retype New Password</label>
                <input class="form-control" type="password" id="pass3">
              </div> 
              <button class="btn btn-success btn-sm" id="btnSavePass">Change Password</button>
            </div>
          </div>
        </div>

      </div>
      <!-- End profile tab pane  -->

      <!-- Activity tab pane -->
      <div class="tab-pane  fade <?= $type == 'activity' ? 'active show':''?>" id="activity" role="tabpanel" aria-labelledby="activity-tab">
        <div class="card mb-3">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
              <h6 class="font-weight-bold">Filter Activity</h6>
              <div>
                <button class="btn btn-outline-secondary btn-sm" id="btnFilter" title="Filter">
                  <i class="fa fa-filter"></i>
                </button>
                <button class="btn btn-outline-secondary btn-sm" id="btnClearFilter" title="Clear Filter">
                  <i class="fa fa-trash"></i>
                </button>
              </div>
            </div>
            <div class="row mb-0 mt-0">
              <div class="form-group col-md-6">
                <label class="mb-1">Category</label>
                <select class="form-control pr-20" id="activityCategory">
                  <option selected value="">All</option>
                  <option value="None">Account Changes</option>
                  <option value="seat">Booking</option>

                <?php if ($this->session->userdata('role') == 'admin') : ?>
                  <option value="employee">Employees</option>
                  <option value="accident_contact">Emergency Contact</option>
                  <option value="trip">Trip Management</option>
                  <option value="uv_unit">UV Express</option>
                <?php endif; ?>

                </select>
              </div>
              <div class="form-group col-md-6">
                <label class="mb-1">Date</label>
                <input class="form-control" type="date" max="<?=date('Y-m-d')?>" id="activityDate">
              </div>
            </div>
          </div>
        </div>

        <div id="activityContainer">
          <!-- Activity content here -->
        </div>

        <!-- Loading pinner -->
        <div class="d-flex align-items-center justify-content-center mb-3" style="height: 50px;">
          <a href="javascript:void(0)" class="font-weight-bold mb-3 mt-3 d-none" 
            id="loadMore">
            See more activity log ...
          </a>
          <!-- Loading dot -->
          <div id="activitySpinner">
            <div class="dot-loading dot-3 text-primary">
              <span></span>
            </div>
          </div>
          <!-- Error loading activity -->
          <div class="d-none" id="tryAgain">
            Error loading activity log.
            <a href="javascript:void(0)" class="font-weight-bold ml-2" id="btnTryAgain">Try again</a>
          </div>
          <!-- No result -->
          <div class="d-none" id="noResult">
            No result found.
          </div>
        </div>

      </div>
      <!-- End activity tab pane -->
    </div>
    <!-- End tab content -->
  </div>
  <!-- End container-fluid -->
</div>

<div class="modal fade" id="logData">
  <div class="modal-dialog modal-md mt-3">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="title">Recorded Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="recordData">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- End of Main Content -->
<?php $this->load->view('partials/footer');?>
<script type="text/javascript">var type = '<?=$type?>';</script>
<script type="text/javascript" src="<?= base_url('assets/js/app/activity.js') ?>"></script>