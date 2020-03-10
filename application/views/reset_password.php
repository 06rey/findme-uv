<?php
$this->load->view('partials/ext_header');
?>
            
            <div class="p-5" style="height: 300px">
                  <div class="text-center">
	                  <form action="<?php echo base_url('user/change_forgot_password/'.$user_id);?>" method="post">

	                  	<p><strong>Reset Password</strong></p>

	                    <?php if (!empty($message)):?>
	                        <div class="alert alert-<?php echo $message['type'] ?>">
	                            <?php echo $message['message'] ?>
	                        </div>
	                    <?php endif ?>

	                    <div class="form-group">
	                      <input name="password" id="password" type="password" class="form-control form-control-user" placeholder="Enter new password" required><br>
	                      <input name="password2" id="password2" type="password" class="form-control form-control-user" placeholder="Confirm new password" required>
	                    </div><br><br>

	                    <div class="form-group" style="float: right;">
	                    	<a href="<?php echo base_url('user/login'); ?>" class="btn btn-secondary">Cancel</a>
	                      	<button type="submit" class="btn btn-primary">Reset Password</button>
	                    </div><br><br>
	                  </form>
                 
                </div>
            </div>

<?php $this->load->view('partials/ext_footer');?>

