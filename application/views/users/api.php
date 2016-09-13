<?php $this->load->view('_base/header'); ?>
			
            <div class="title">Edit User Information</div>
			
			<div class="nav">
				<ul>
					<li><a href="<?php //echo site_url('users/profile').'/'.$row['id']; ?>">Profile</a></li>
					<li><a href="<?php //echo site_url('users/update').'/'.$row['id']; ?>">Authentication</a></li>
					<li><a href="<?php //echo site_url('users/api'); ?>">API</a></li>
					<li><a href="">Notifications</a></li>
				</ul>
				<a href="<?php //echo site_url('deploy'); ?>"><div class="deploy_new_server"><span>+</span></div></a>
			</div>
            
			<?php $this->load->view('_base/message'); ?>
			
            <div class="edit-view">
				<div class="demo"></div>
				<script>
				$('.demo').croppie({
					url: "<?php echo site_url('static/images/demo-2.jpg'); ?>",
					viewport: {
						width: 200,
						height: 200
					},
					boundary: { width: 350, height: 250 },
				});
				</script>
				
            </div>
			
            <?php $this->load->view('_base/footer'); ?>

