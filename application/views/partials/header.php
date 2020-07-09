<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="<?php echo base_url();?>assets/img/fmv-icon.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $pageTitle ?></title>

  <!-- Custom fonts for this template-->
  <link href="<?php echo base_url();?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  

  <!-- Custom styles for this template-->
  <link href="<?php echo base_url();?>assets/css/sb-admin-2.css" rel="stylesheet">
  <link href="<?php echo base_url();?>assets/css/jquery-confirm.min.css" rel="stylesheet">
  <link href="<?php echo base_url();?>assets/css/jquery-ui.css" rel="stylesheet">
  <!-- Custom styles for this page -->
  <link href="<?php echo base_url();?>assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="<?php echo base_url();?>assets/css/fmu.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/bootstrap-select.min.css')?>">

</head>

<body class="no-scroll" id="page-top">

  <!-- Page Wrapper -->
  <div class="h-100" id="wrapper" style="min-height: 100vh">

    <!-- Loading -->
  <div class="loading-container">
    <!-- <div class="spinner-border text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div> -->
    <div class="dot-loading text-primary dot-4">
      <span></span>
    </div>
  </div>
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url();?>">
        <div class="sidebar-brand-icon">
          
          <img src="<?php echo base_url();?>/assets/img/icon.png">
        </div>
        <div class="sidebar-brand-text mx-3">FIND ME UV</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- If admin or owner -->
      <?php 
        $role = $this->session->userdata('role');
        if ($role == 'admin' || $role == 'owner')
      { ?>

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo base_url();?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Interface 
      </div>


      <!-- Nav Item - Trip Management -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('booking') ?>">
          <i class="fas fa-fw fa-book"></i>
          <span>Booking</span></a>
      </li>


      <!-- Nav Item - Trip Management -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('trip_management/allTrip') ?>">
          <i class="fas fa-fw fa-road"></i>
          <span>Trip Management</span></a>
      </li>

      <!-- Nav Item - Routes -->
     <!--  <li class="nav-item">
        <a class="nav-link" href="<?= base_url('route/all') ?>">
          <i class="fas fa-fw fa-road"></i>
          <span>Routes</span></a>
      </li>
 -->
      <!-- Nav Item - UV Units -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('uv_unit') ?>">
          <i class="fas fa-fw fa-bus"></i>
          <span>UV Express</span></a>
      </li>


      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-users"></i>
          <span>Employees</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?= base_url('employee/view/Driver') ?>">Drivers</a>
            <a class="collapse-item" href="<?= base_url('employee/view/Clerk') ?>">Clerks</a>
          </div>
        </div>
      </li>

       <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#log-panel" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-list"></i>
          <span>System Logs</span>
        </a>
        <div id="log-panel" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?= base_url('logs/userActivity') ?>">User Activiy</a>
            <a class="collapse-item" href="<?= base_url('logs/allAccident') ?>">Acciddent</a>
            <a class="collapse-item" href="<?= base_url('logs/over_speed') ?>">Over Speed</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('logs/get_feedback/10') ?>">
          <i class="fas fa-fw fa-comment"></i>
          <span>Feedback</span></a>
      </li>
      <!-- Else id if clerk -->
      <?php } elseif ($role == 'clerk') { ?>

      <!-- Nav Item - Bookings -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('booking') ?>">
          <i class="fas fa-fw fa-book"></i>
          <span>Bookings</span></a>
      </li>

      <?php } ?>
      <!-- nd if else -->

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <!-- <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0"></button>
      </div> -->

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow" id="nav-top">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <div class="d-flex justify-content-center w-100">
            <div class="text-white bg-danger shadow-sm" id="serverFailed">
              <i class="fa fa-exclamation-circle fa-fw"></i>
              Server connection failed!
            </div>
          </div>

          <!-- User info -->
          <ul class="navbar-nav  ml-auto" id="topBar">
            <div class="topbar-divider d-none d-sm-block"></div>
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                  <?= $this->session->userdata('f_name').' '.$this->session->userdata('l_name') ?>
                </span>
                <img class="img-profile rounded-circle" id="navUserImg" src="<?php echo base_url($this->session->userdata('img_url'));?>">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?=base_url('user/account/profile')?>">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="<?=base_url('user/account/activity')?>">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->


  <!-- Logout Modal-->
      <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
              <a class="btn btn-primary" href="<?php echo base_url('user/logout'); ?>">Logout</a>
            </div>
          </div>
        </div>
      </div>
