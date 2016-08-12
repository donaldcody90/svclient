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
		<div class="login-page">
			<?php echo form_open(base_url() . 'auth/login', 'class="login-form"'); ?>
				<img class="login-logo" src="<?php echo base_url(); ?>static/images/logo.png" />
				<?php 
					if ($this->session->flashdata('signup_success'))
					{
						echo '<center><div class="signup-success">Registration Successful. Login now!</div></center>';
					}
				?>
				<input type="text" name="username" placeholder="Username" />
				<?php echo form_error('username', '<div class="error">', '</div>'); ?>
				<input type="password" name="password" placeholder="Password" />
				<?php
					if ($this->session->flashdata('login_error'))
					{
						echo '<div class="error">You entered a incorrect username or password</div>';
					}
					
					echo form_error('password', '<div class="error">', '</div>'); 
				?>
				
				<input class="btn_login" type="submit" name="btn_login" value="Login" />
				
				<div class="message">
					<center>Help, I <a href="#">forgot my password</a></center>
				</div>
			<?php echo form_close(); ?>
			
			<table class="separate_line">
					<tr><td>&nbsp;</td></tr>
			</table>
			
			<div class="message">
				<center>Don't have a Vultr account yet?</center>
			</div>
			
			<?php echo form_open(base_url().'auth/signup', 'class="signup-form"'); ?>
				<button>Sign Up</button>
			<?php echo form_close(); ?>
			
        </div>
    </body>
</html>