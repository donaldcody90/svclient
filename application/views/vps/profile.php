<?php $this->load->view('_base/header'); ?>
			
			<div class="title vps_profile_title">
				<img src="<?php echo site_url('static/images').'/logo_centos.png'; ?>" />
				<div>
					<span>Server Information (<?php echo $data['vps_label']; ?>)</span><br>
					<span class="vps_quick_info">
						<?php echo $data['vps_ip']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php echo $data['label']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						CentOS 6 x64
					</span>
				</div>
			</div>
			
			<div class="vps_profile_icon">
				<a href=""><img src="<?php echo site_url('static/images').'/logo31.png'; ?>" /></a>
				<a href=""><img src="<?php echo site_url('static/images').'/logo32.png'; ?>" /></a>
				<a href=""><img src="<?php echo site_url('static/images').'/logo33.png'; ?>" /></a>
				<a href=""><img src="<?php echo site_url('static/images').'/logo34.png'; ?>" /></a>
				<a href=""><img src="<?php echo site_url('static/images').'/logo35.png'; ?>" /></a>
			</div>
			
			<div class="nav">
				<ul>
					<li><a href="<?php echo site_url('vps/profile').'/'.$data['id']; ?>">Overview</a></li>
					<li><a href="<?php echo site_url('vps/settings'); ?>">Settings</a></li>
				</ul>
				<a href="<?php echo site_url('deploynewserver'); ?>"><div class="deploy_new_server"><span>+</span></div></a>
			</div>
			
			<?php $this->load->view('_base/message'); ?>
			
            <div class="vps_profile">
			
                <div class="column">
					<div class="priority">
						<div>
							<span class="property">Bandwidth Usage</span><br>
							<span class="value">1.04GB</span><span class="value2">/1000GB</span>
						</div>
					</div>
					<div class="detail">
						<div class="detail_properties">
							<span>Location:</span><br>
							<span>IP Address:</span><br>
							<span>Username:</span><br>
							<span>Password:</span>
						</div>
						<div class="detail_values">
							<span><img class="flag" src="<?php echo site_url('static/images').'/flag_'.$data['location'].'.png'; ?>" />&nbsp;<?php echo $data['label']; ?></span><br>
							<span><?php echo $data['vps_ip']; ?></span><br>
							<span><?php echo $data['username']; ?></span><br>
							<span>
								*******
								<a href=""><img src="<?php echo site_url('static/images/logo37.png'); ?>" /></a>
								<a href=""><img src="<?php echo site_url('static/images/logo38.png'); ?>" /></a>
							</span>
						</div>
					</div>
				</div>
				
                <div class="column">
					<div class="priority">
						<div>
							<span class="property">CPU Usage</span><br>
							<span class="value">0%</span>
						</div>
						<img src="<?php echo site_url('static/images').'/logo36.png'; ?>" />
					</div>
					<div class="detail">
						<div class="detail_properties">
							<span>CPU:</span><br>
							<span>Ram:</span><br>
							<span>Storage:</span><br>
							<span>Bandwidth:</span>
						</div>
						<div class="detail_values">
							<span><?php echo $data['cpu_core'].' vCore'; ?></span><br>
							<span><?php echo $data['ram'].' MB'; ?></span><br>
							<span><?php echo $data['disk_space'].' GB SSD'; ?></span><br>
							<span><?php echo '1.04 GB of '.$data['bandwidth'].' GB '.'(0%)'; ?></span><br>
							<span>(<a href="#">Show details</a>)</span>
						</div>
					</div>
				</div>
				
                <div class="column">
					<div class="priority">
						<div>
							<span class="property">Current Charges</span><br>
							<span class="value">$0.18</span>
						</div>
					</div>
					<div class="detail">
						<div class="detail_properties">
							<span>Label:</span><br>
							<span>Tag:</span><br>
							<span>OS:</span>
						</div>
						<div class="detail_values">
							<span><a href="#"><?php echo $data['vps_label']; ?></a></span><br>
							<span><a href="#">[Click here to set]</a></span><br>
							<span>CentOS 6 x64</span>
						</div>
					</div>
				</div>
				
            </div>
			
            <?php $this->load->view('_base/footer'); ?>

