<?php $this->load->view('partials/header');?>
  <!-- Begin Page Content -->
  <div class="container-fluid" id="app">
    <!-- Page Heading -->
    <h1 class="h3 mt-3 text-gray-800"><?php echo $pageTitle?></h1>
    <hr>

    <input type="text" class="form-control mb-3" name="" placeholder="Search" id="searchRoute">

    <div class="row justify-content-center mt-5 mb-5 w-100 d-none" id="noResult">
      <h4>No result found</h4>
    </div>

    <!-- Content Row -->
    <div class="row" id="routeList">
    <?php foreach ($routes as $route) : ?>
      <div class="col-lg-4 mb-4 route">
        <div class="card shadow h-100 ">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2 place">
                <h5>
                  <li class="fa fa-map-pin fa-fw"></li>
                  <?=$route->origin?>
                </h5>
                <h5>
                  <li class="fa fa-map-marker fa-fw"></li>
                  <?=$route->destination?>
                </h5>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('booking/trip/'.$route->route_id.'/'.date('Y-m-d')) ?>" class="" id="<?= $route->route_id ?>">
              <i class="fa fa-calendar fa-fw fa-sm text-gray-400"></i>
              Trip Schedule
              <span class="font-weight-bold badge badge-info text-white tripCount">0</span>
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    </div>
  </div>
  <!-- End container-fluid -->
</div>
<!-- End of Main Content -->
<?php $this->load->view('partials/footer');?>
<script type="text/javascript" src="<?= base_url('assets/js/app/booking.js') ?>"></script>