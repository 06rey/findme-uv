<?php 

$this->load->view('partials/ext_header');

//echo "$data->contact_no";
//print_r($data);

?>


<div class="p-5" >
      <div class="text-center">

          <form action="<?php echo base_url('user/enter_code/'.$data->username);?>" method="post">

          	<p><strong>Reset Your Account Password</strong></p><br>

            <?php if (!empty($message)):?>
                <div class="alert alert-<?php echo $message['type'] ?>">
                    <?php echo $message['message'] ?>
                </div>
            <?php endif ?>

            <?php echo $this->session->flashdata('msg');?>

            <div>
            	<img style="width: 70px; height: 70px;" src="<?php echo base_url('assets/img/user.png') ?>">
            	<p style="margin: 0px"><strong><?php echo $data->f_name. ' ' .$data->l_name; ?></strong></p>
            	<p><?php echo substr_replace($data->contact_no,"*******",2 , 7); ?></p>
              	<p>A password reset code will be sent to your account contact number.</p>
            </div>
            <br>

            <div class="row" style="float: right;">
              <a style="margin-right: 10px" href="<?php echo base_url('user/login'); ?>" class="btn btn-secondary">Cancel</a>
              <button type="submit" class="btn btn-primary">Get Code</button>
            </div><br>
          </form>
     
    </div>
</div>

<?php $this->load->view('partials/ext_footer');?>

