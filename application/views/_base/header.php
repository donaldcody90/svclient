<?php
	$this->output->enable_profiler(TRUE);

?>
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
	
    <body>
		
		<div class="page">
		
		<?php 
			$cController=vst_getController();
			$cMedthod=vst_getMethod();
		?>
			<div class="main-menu">
				<center><ul>
					<li class="logo"><a href="#"><img src="<?php echo base_url(); ?>static/images/logo1.png"></a></li>
					<li><a href="<?php echo site_url().'vps'; ?>"><img src="<?php echo base_url(); ?>static/images/logo12.png"><p <?php echo $cController == 'vps' ? 'class="menu2"':'class="menu1"'; ?> >Servers</p></a></li>
					<li><a href="<?php echo site_url().'billing'; ?>"><img src="<?php echo base_url(); ?>static/images/logo3.png"><p <?php echo $cController == 'billing' ? 'class="menu2"':'class="menu1"'; ?> >Billing</p></a></li>
					<li><a href="<?php echo site_url().'support/lists'; ?>"><img src="<?php echo base_url(); ?>static/images/logo4.png"><p <?php echo $cController == 'support' ? 'class="menu2"' : 'class="menu1"'; ?>>Support</p></a></li>
					<li><a href="#"><img src="<?php echo site_url(); ?>static/images/logo5.png"><p class="menu1">Affiliate</p></a></li>
					<li><a href="<?php echo site_url().'users'; ?>"><img src="<?php echo base_url(); ?>static/images/logo6.png"><p <?php echo $cController == 'users' ? 'class="menu2"':'class="menu1"'; ?> >Account</p></a></li>
				</ul></center>
			</div>
			
			
			<section class="main">
				<div class="wrapper-demo">
					<div id="dd" class="wrapper-dropdown-3" tabindex="1">
						<p class="zp1">Welcome back, &nbsp; <span class="zselect1"><?php echo $this->session->userdata('username'); ?></span></p>
						<ul class="dropdown">
							<li><a href="<?php echo base_url() . 'users/profile/' . $this->session->userdata('user_id'); ?>"><i><img src="<?php echo base_url().'static/images/logo21.png'; ?>" /></i>Profile</a></li>
							<li><a href="<?php echo base_url() . 'users/update/' . $this->session->userdata('user_id'); ?>"><i><img src="<?php echo base_url().'static/images/logo22.png'; ?>" /></i>Settings</a></li>
							<li><a href="<?php echo base_url() . 'auth/logout'; ?>"><i><img src="<?php echo base_url().'static/images/logo23.png'; ?>" /></i>Log Out</a></li>
						</ul>
					</div>
				​</div>
			</section>