<br>
<br>
<div class="ts-main-content">
    <?php $this->load->view('partials/header');?>
    <div class="content-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <h2 class="page-title"><?php echo $pageTitle?></h2>
              <div class="row">
                <div class="col-md-12">
                  <?php if (!empty($message)):?>
                    <div class="alert alert-<?php echo $message['type'] ?>">
                      <?php echo $message['message'] ?>
                    </div>
                  <?php endif ?>

                  <div class="panel panel-primary">
                    <div class="panel-heading">Fill all Info</div>
                      <div class="panel-body">
                        <form method="post" action="<?php echo base_url('user/register_validation');?>" class="form-horizontal">

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Username : </label>
                            <div class="col-sm-8">
                              <input type="text" name="username" id="username"  class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Password : </label>
                            <div class="col-sm-8">
                              <input type="password" name="password" id="password"  class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Password Confirmation : </label>
                            <div class="col-sm-8">
                              <input type="password" name="cpassword" id="cpassword"  class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Status : </label>
                            <div class="col-sm-8">
                              <input type="text" name="status" id="status"  class="form-control">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Role : </label>
                            <div class="col-sm-8">
                              <input type="text" name="role" id="role"  class="form-control">
                            </div>
                          </div>
                                
                          <div class="form-group">
                            <label class="col-sm-2 control-label">User ID : </label>
                            <div class="col-sm-8">
                              <input type="text" name="user_id" id="user_id"  class="form-control">
                            </div>
                          </div> 


                          <div class="col-sm-6 col-sm-offset-4">
                            <button class="btn btn-default" type="reset">Cancel</button>
                            <input type="submit" name="submit" Value="Register" class="btn btn-primary">
                          </div>
                        </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
    
      
     
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap-select.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.dataTables.min.js"></script>
  <script src="js/dataTables.bootstrap.min.js"></script>
  <script src="js/Chart.min.js"></script>
  <script src="js/fileinput.js"></script>
  <script src="js/chartData.js"></script>
  <script src="js/main.js"></script>
</body>

</html>

