<?php $this->load->view('_base/header'); ?>
			
            <div class="title">List of Data centers</div>
            
			<div class="list-DC">
				<div>
					<a href="<?php echo base_url().'datacenters/add'; ?>"><button>Add new Data Centers</button></a>
					
					<div class="search">
						<?php echo form_open(base_url().'datacenters/lists', 'method= "GET"'); ?>
							<input type="search" value="<?php echo $this->input->get('filter_ip') ?>" name="filter_ip" placeholder="Search by IP" />
						<?php echo form_close() ?>
					</div>
				</div>
				
				<div>
					<center><?php 
						if ($this->session->flashdata('success'))
						{
							echo '<div class="error change_message">Successful!</div>';
						}
						if ($this->session->flashdata('error'))
						{
							echo '<div class="error change_message">Failed!</div>';
						}
					?></center>
				</div>
				
				<div>
					<table class="main-content">
					
						<tr class="zhang1">
							<td class="cot1">ID</td>
							<td class="cot2">IP Address</td>
							<td class="cot3">Key</td>
							<td class="cot4">Password</td>
							<td class="cot7"></td>
							<td class="cot8"></td>
						</tr>
						
						<?php
						foreach($result as $row){ ?>
						<tr>
							<td><?php echo $row->id; ?></td>
							<td><?php echo $row->ip; ?></td>
							<td><?php echo $row->sv_key; ?></td>
							<td><?php echo $row->sv_pass; ?></td>
							<!--<td>
								<?php 
									//$svAPI=initServerAPI($row->ip,$row->sv_key,$row->sv_pass);
									
									//echo count($svAPI->servers());
								?>
							</td>
							<td>
								<a href="<?php echo base_url().'datacenters/restart/'.$row->id; ?>">Restart</a><br>
								<a href="<?php echo base_url().'datacenters/stop/'.$row->id; ?>">Stop</a><br>
								<a href="<?php echo base_url().'datacenters/start/'.$row->id; ?>">Start</a>
							</td>-->
							<td class="cot7"><a href="<?php echo base_url() . 'datacenters/update/' . $row->id; ?>" >Edit</a></td>
							<td class="cot8"><a href="<?php echo base_url() . 'datacenters/deletedc/' . $row->id; ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a></td>
						</tr>
						<?php } ?>
						
						<tr class="zhang-cuoi">
							<td></td>
							<td></td>
							<td><center><?php echo $link; ?></center></td>
							<td></td>
							<td></td>
						</tr>
						
					</table>
					
				</div>
			</div>
			
            <?php $this->load->view('_base/footer'); ?>

