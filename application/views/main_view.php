<?php $this->load->view('include/header'); ?>
	<div class="row">
	  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-center">
	    <h1>Hello! <?php echo get_cookie('displayName'); ?></h1>
	    <h5>Good to see you here</h5>
	  </div>
	  <div class="divider-hidden"></div>
		<div class="divider"></div>
	</div>
	<div class="row">
		<?php
			$code = "TFQP-1234567890";
			$prefix = preg_replace('/[^a-zA-Z]/', '', $code);
			$docNum = preg_replace('/[^0-9]/', '', $code);

			echo $prefix;
			echo "</br>";
			echo $docNum;

		 ?>
	</div>

<?php $this->load->view('include/footer'); ?>
