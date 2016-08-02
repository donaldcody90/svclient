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
	
    <body class="body">
		<div class="signup-page">
			<?php echo form_open(base_url() . 'auth/signup', 'class="signup-form"'); ?>
				<img class="login-logo" src="<?php echo base_url(); ?>static/images/logo.png" />
				<input type="text" name="firstname" placeholder="First Name" />
				<?php echo form_error('firstname', '<div class="error">', '</div>'); ?>
				<input type="text" name="lastname" placeholder="Last Name" />
				<?php echo form_error('lastname', '<div class="error">', '</div>'); ?>
				<input type="text" name="username" placeholder="Username" />
				<?php echo form_error('username', '<div class="error">', '</div>'); ?>
				<input type="password" name="password" placeholder="Password" />
				<?php echo form_error('password', '<div class="error">', '</div>'); ?>
				<input type="password" name="passconf" placeholder="Confirm password" />
				<?php echo form_error('passconf', '<div class="error">', '</div>'); ?>
				<input type="email" name="email" placeholder="Email Address" />
				<?php
					if ($this->session->flashdata('signup_error'))
					{
						echo '<div class="error">Cannot register right now!</div>';
					}
					
					echo form_error('email', '<div class="error">', '</div>'); 
				?>
				
				<button>Sign Up</button>
				<center><div class="message">Already a member? <a href="<?php echo base_url() . 'auth/login'; ?>">Log In</a></div></center>
			<?php echo form_close(); ?>
        </div>
    </body>
</html>