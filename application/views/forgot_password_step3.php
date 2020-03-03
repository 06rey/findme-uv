<?php
$this->load->view('partials/ext_header');
?>
            
            <div class="p-5" style="height: 300px">
                  <div class="text-center">
	                  <form action="<?php echo base_url('user/verify_reset_code/'.$data['id']);?>" method="post">

	                  	<p><strong>Password Reset Code</strong></p>

	                    <?php if (!empty($message)):?>
	                        <div class="alert alert-<?php echo $message['type'] ?>">
	                            <?php echo $message['message'] ?>
	                        </div>
	                    <?php endif ?>

	                    <div class="form-group">
	                      <input name="code" id="code" type="number" class="form-control form-control-user" placeholder="Enter password reset code" required>
	                    </div><br>

	                    <p>Did'nt recieve a code?</p>
	                    <a href="<?php echo base_url('user/enter_code/'.$data['username'].'/'.$data['contact_no']);?>">Send Again</a> <br><br>

	                    <div class="form-group" style="float: right;">
	                    	<a href="<?php echo base_url('user/login'); ?>" class="btn btn-secondary">Cancel</a>
	                      	<button type="submit" class="btn btn-primary">Continue</button>
	                    </div><br><br>
	                  </form>
                 
                </div>
            </div>

<?php $this->load->view('partials/ext_footer');?>

