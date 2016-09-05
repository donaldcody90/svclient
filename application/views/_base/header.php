<?php
	$this->output->enable_profiler(TRUE);

?>
<!DOCTYPE html>
<html>
    
    <head>
		<title><?php $siteSconfig = $this->config->item('site'); $cController=vst_getController();
					echo $cController.' - '.$siteSconfig['title']; ?></title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="<?php echo base_url(); ?>static/css/style.css" type="text/css">
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/jquery-1.12.3.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/my.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>static/js/welcome_message.js"></script>
	</head>
	
    <body>
		
		<div class="page">
		
		<?php 
			$cController=vst_getController();
			$cMedthod=vst_getMethod();
		?>
			<div class="main-menu">
				<center><ul>
					<a href="<?php echo site_url(); ?>"><li class="logo"><img src="<?php echo base_url(); ?>static/images/logo1.png"></li></a>
					<a href="<?php echo site_url().'vps'; ?>"><li><img src="<?php echo $cController == 'vps' ? site_url('static/images/logo12.png') : site_url('static/images/logo2.png'); ?>"><p <?php echo $cController == 'vps' ? 'class="menu2"':'class="menu1"'; ?> >Servers</p></li></a>
					<a href="<?php echo site_url().'billing'; ?>"><li><img src="<?php echo  $cController == 'billing' ? site_url('static/images/logo13.png') : site_url('static/images/logo3.png'); ?>"><p <?php echo $cController == 'billing' ? 'class="menu2"':'class="menu1"'; ?> >Billing</p></li></a>
					<a href="<?php echo site_url().'support/lists'; ?>"><li><img src="<?php echo $cController == 'support' ? site_url('static/images/logo14.png') :  site_url('static/images/logo4.png'); ?>"><p <?php echo $cController == 'support' ? 'class="menu2"' : 'class="menu1"'; ?>>Support</p></li></a>
					<a href="#"><li><img src="<?php echo site_url(); ?>static/images/logo5.png"><p class="menu1">Affiliate</p></li></a>
					<a href="<?php echo site_url().'users'; ?>"><li><img src="<?php echo $cController == 'users' ? site_url('static/images/logo16.png') : site_url('static/images/logo6.png'); ?>"><p <?php echo $cController == 'users' ? 'class="menu2"':'class="menu1"'; ?> >Account</p></li></a>
				</ul></center>
			</div>
			
			
			<section class="main">
				<div class="wrapper-demo">
					<div id="dd" class="wrapper-dropdown-3" tabindex="1">
						<p class="zp1">Welcome back, &nbsp; <span class="zselect1"><?php echo $this->session->userdata('username'); ?></span></p>
						<ul class="dropdown">
							<li><a href="<?php echo site_url() . 'users/profile/' . $this->session->userdata('user_id'); ?>">Profile</a></li>
							<li><a href="<?php echo site_url() . 'users/update/' . $this->session->userdata('user_id'); ?>">Users</a></li>
							<li><a href="<?php echo site_url() . 'auth/logout'; ?>">Log Out</a></li>
						</ul>
					</div>
				​</div>
			</section>