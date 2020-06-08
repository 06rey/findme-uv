<?php $this->load->view('partials/header');?>
  <!-- Begin Page Content -->
  <div class="container-fluid" id="app">
    <!-- Page Heading -->
    <h1 class="h3 mt-3 text-gray-800"><?php echo $pageTitle?></h1>
    <hr>
    <!-- DataTales Example -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search Feedback" id="searchFeedback" v-model="keywords">
          <div class="input-group-append">
            <button class="btn btn-secondary btn-sm" v-on:click="searchFeedback">
              <i class="fa fa-search"></i> 
            </button>
          </div>
        </div>
        
        <div class="breadcrumb d-none mt-3 pb-2" id="resultHeader">
          <h5 class="font-weight-bold">
            Result for: 
            <span class="font-weight-normal" id="resultKeyword">
            </span>
            <span class="ml-3 text-gray-400">
              |<i class="fa fa-times fa-sm text-danger ml-3" title="Clear Result" data-toggle="tooltip" rel="tooltip" v-on:click="clearSearch"></i>
            </span>
          </h5>
        </div>
        <hr>
        <div id="feedbackContainer">
        </div>

        <div class="d-flex align-items-center justify-content-center mb-3" style="height: 50px;">
          <a href="javascript:void(0)" class="font-weight-bold mb-3 mt-3 d-none" 
            id="loadMore"
            v-on:click="loadFeedback">
            Load More ...
          </a>
          <!-- Loading dot -->
          <div id="loadingSpin">
            <div class="dot-loading dot-3 text-primary">
              <span></span>
            </div>
          </div>
          <!-- Error loading activity -->
          <div class="d-none" id="tryAgainContainer">
            Error loading feedback.
            <a href="javascript:void(0)" class="font-weight-bold ml-2" v-on:click="loadFeedback">Try again</a>
          </div>
          <!-- No result -->
          <div class="d-none" id="noFeedbackFound">
            No result found.
          </div>
        </div>

        <!-- <div class="row align-items-center justify-content-center mb-3 mt-4" id="loadingSpin">
          <div class="spinner-border spinner-border-sm text-secondary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>

        <button class="btn btn-outline-info btn-sm font-weight-bold mb-3" 
                id="loadMore"
                v-on:click="loadFeedback">
          Load More ...
        </button>
        <div class="row align-items-center justify-content-center mb-3" id="tryAgainContainer">
          Error loading feedback. Something went wrong.
          <a href="javascript:void(0)" class="font-weight-bold ml-2" v-on:click="loadFeedback">Try again</a>
        </div>
        <div class="row align-items-center justify-content-center mb-3 d-none" id="noFeedbackFound">
          No result found.
        </div> -->
      </div>
    </div>

  </div>
  <!-- End container-fluid -->
</div>
<!-- End of Main Content -->

<?php $this->load->view('partials/footer');?>
<script type="text/javascript" src="<?= base_url('assets/js/app/feedback.js') ?>"></script>