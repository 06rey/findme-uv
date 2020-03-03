<?php $this->load->view('partials/ext_header');?>
            
            <div class="p-5" style="height: 300px">
                  <div class="text-center">
	                  <form action="<?php echo base_url('user/search_account');?>" method="post">

	                  	<p><strong>Find Your Account</strong></p>

	                    <?php if (!empty($message)):?>
	                        <div class="alert alert-<?php echo $message['type'] ?>">
	                            <?php echo $message['message'] ?>
	                        </div>
	                    <?php endif ?>

	                    <div class="form-group">
	                      <input name="username" id="username" type="text" class="form-control form-control-user" placeholder="Enter your account username" required>
	                    </div><br>

	                    <div class="form-group" style="float: right;">
	                    	<a href="<?php echo base_url('user/login'); ?>" class="btn btn-secondary">Cancel</a>
	                      	<button type="submit" class="btn btn-primary">Search</button>
	                    </div><br><br>
	                  </form>
                 
                </div>
            </div>

<?php $this->load->view('partials/ext_footer');?>

