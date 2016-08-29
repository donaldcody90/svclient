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
			</div>
				
			<?php $this->load->view('_base/message'); ?>
			
            <div class="vps_settings main-body">
				
                <div class="vps_modification">
					<ul>
						<li class="vps_modification1"><a href="">IPv4</a></li>
						<li class="vps_modification2"><a href="">IPv6</a></li>
						<li class="vps_modification3"><a href="">Custom ISO</a></li>
						<li class="vps_modification4"><a href="">Change Hostname</a></li>
						<li class="vps_modification5"><a href="">Change Plan</a></li>
						<li class="vps_modification6"><a href="">Change OS</a></li>
						<li class="vps_modification7"><a href="">Change Application</a></li>
					</ul>
				</div>
				
				<div>
					<div class="modify1">
						<span class="vps_modification_title">Public Network</span><br>
						<span class="vps_modification_subtitle">Need assistance? View our <a href="">networking configuration</a> tips and examples.</span>
						<table>
							<tr class="row1">
								<td class="column1">Address</td>
								<td class="column2">Netmask</td>
								<td class="column3">Gateway</td>
								<td class="column4">Reverse DNS</td>
								<td class="column5"></td>
							</tr>
						
							<tr class="row2">
								<td>45.76.97.110</td>
								<td>255.255.254.0</td>
								<td>45.76.96.1</td>
								<td><a href="#"><img src="<?php echo site_url('static/images').'/logo40.png'; ?>" /></a></td>
								<td>(main ip)</td>
							</tr>
						</table>
					</div>
					
					<div class="modify2">
						<span class="vps_modification_title">Additional IPv4 IP </span><a class="show_details" href="">( Show details )</a><br>
						<a href="#"><button>Add another IPv4 address</button></a>
					</div>
					
					<div class="modify2">
						<span class="vps_modification_title">Private Network </span><a class="show_details" href="">( Show details )</a><br>
						<a href="#"><button>Enable Private Network</button></a>
					</div>
				</div>
				
            </div>
			
            <?php $this->load->view('_base/footer'); ?>

