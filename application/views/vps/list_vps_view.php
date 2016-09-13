<?php $this->load->view('_base/header'); ?>
			
            <div class="title">Servers</div>
			
			<div class="nav">
				<ul>
					<li><a href="<?php echo site_url('vps/lists'); ?>">Profile</a></li>
					<li><a href="">Instances</a></li>
					<li><a href="">Snapshots</a></li>
					<li><a href="">ISO</a></li>
					<li><a href="">Startup Scripts</a></li>
					<li><a href="">SSH Keys</a></li>
					<li><a href="">DNS</a></li>
					<li><a href="">Backups</a></li>
					<li><a href="">Block Storage</a></li>
					<li><a href="">Reserved IPs</a></li>
				</ul>
				<a href="<?php echo site_url('deploy'); ?>"><div class="deploy_new_server"><span>+</span></div></a>
			</div>
			
			<?php $this->load->view('_base/message'); ?>
            
			<div class="list-DC">
				<div class="search">
						<?php echo form_open(base_url().'vps/lists', 'method= "GET"'); ?>
							<input type="search" value="<?php echo $this->input->get('filter_vps_ip') ?>" name="filter_vps_ip" placeholder="Search by IP" />
						<?php echo form_close() ?>
				</div>
				
				<div>
					<table class="main-content">
					
						<tr class="zhang1">
							<td class="cot1">ID</td>
							<td class="cot2">VPS Label</td>
							<td class="cot3">OS</td>
							<td class="cot4">Location</td>
							<td class="cot5">Charges</td>
							<td class="cot6">Status</td>
							<td class="cot7"></td>
							<td class="cot8"></td>
						</tr>
						
						<?php
						foreach($result as $row){ ?>
						<tr>
							<td><?php echo $row->id; ?></td>
							<td>
								<a href="<?php echo site_url().'vps/profile/'.$row->id; ?>"><?php echo $row->vps_label; ?></a><br>
								<span><?php echo $row->ram.' MB Server - '.$row->vps_ip; ?></span>
							</td>
							<td></td>
							<td><img class="flag" src="<?php echo site_url('static/images').'/flag_'.$row->location.'.png'; ?>"/>&nbsp;&nbsp;<?php echo $row->label; ?></td>
							<td></td>
							<td></td>
							<td class="cot7"><a href="<?php echo base_url() . 'vps/update/' . $row->id; ?>" >Edit</a></td>
							<td class="cot8"><a href="<?php echo base_url() . 'vps/delete/' . $row->id; ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a></td>
						</tr>
						<?php } ?>
						
						<tr class="zhang-cuoi">
							<td></td>
							<td></td>
							<td></td>
							<td><center><?php echo $link; ?></center></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						
					</table>
					
				</div>
			</div>
			
            <?php $this->load->view('_base/footer'); ?>

