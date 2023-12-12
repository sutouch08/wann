<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/img/favicon.png"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/main.css">
<script>
	var BASE_URL = "<?php echo base_url(); ?>";
</script>
</head>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-b-20">
				<form class="login100-form validate-form" method="post" action="authentication/validate_credentials">
					<span class="login100-form-title p-b-30" style="display:none;">
						Welcome
					</span>

					<span class="login100-form-avatar">
						<img src="<?php echo base_url(); ?>assets/images/logo.png">
					</span>


					<div class="wrap-input100 validate-input m-t-50 m-b-35" data-validate = "Enter username">
						<input class="input100" type="text" name="uname" id="uname" autocomplete="off" autofocus>
						<span class="focus-input100" data-placeholder="Username"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-35" data-validate="Enter password">
						<input class="input100" type="password" name="pwd" id="pwd" autocomplete="off">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>

					<div class="validate-input m-b-35">
						<input type="checkbox" name="remember" id="remember" value="1" class="input-checkbox100" />
						<label class="label-checkbox100" for="remember"> Remember Me</label>
					</div>


					<div class="container-login100-form-btn">
						<button type="button" class="login100-form-btn" onclick="doLogin()">
							Login
						</button>
					</div>

					<div class="container-login100-form-btn" style="margin-top:30px;">
						<p id="error-label" class="text-center" style="color:red">&nbsp;</p>
					</div>
					<!-- Bypass robot-->
					<span style="display:none;" id="ipwd"></span>
				</form>
			</div>
		</div>
	</div>

<!--===============================================================================================-->
	<script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url(); ?>assets/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
	<script src="<?php echo base_url(); ?>scripts/login.js"></script>

</body>
</html>
