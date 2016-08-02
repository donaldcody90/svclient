<!DOCTYPE html>
<html>
	
    <head>
		<title><?php $siteSconfig = $this->config->item('site');   echo $siteSconfig['title']; ?></title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="<?php echo base_url(); ?>static/css/style.css" type="text/css">
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/jquery-1.12.3.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/my.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/welcome_message.js"></script>
	</head>
	
    <body class="body0">
		<div class="login-page">
			<img class="login-logo" src="<?php echo base_url(); ?>static/images/logo.png">
			<img class="success-logo" src="<?php echo base_url(); ?>static/images/logo18.png">
			<p>You have been successfully logged in to Vultr!</p>
			<form action="<?php echo base_url(); ?>auth/logout">
				<button class="logout-button">Logout</button>
			</form>
        </div>
    </body>
</html>