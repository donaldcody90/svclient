<?php $this->load->view('_base/header'); ?>
			
            <div class="title">List of VPS</div>
			
			<?php $this->load->view('_base/message'); ?>
            
			<div class="list-DC">
				<div>
					<a href="<?php echo base_url().'vps/add'; ?>"><button>Add new Data Centers</button></a>
					
					<div class="search">
						<?php echo form_open(base_url().'vps/lists', 'method= "GET"'); ?>
							<input type="search" value="<?php echo $this->input->get('filter_vps_ip') ?>" name="filter_vps_ip" placeholder="Search by IP" />
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
							<td class="cot2">VPS Label</td>
							<td class="cot3">VPS IP</td>
							<td class="cot4">Datacenters</td>
							<td class="cot5">Create Date</td>
							<td class="cot7"></td>
							<td class="cot8"></td>
						</tr>
						
						<?php
						foreach($result as $row){ ?>
						<tr>
							<td><?php echo $row->id; ?></td>
							<td><a href="<?php echo site_url().'vps/profile/'.$row->id; ?>"><?php echo $row->vps_label; ?></a></td>
							<td><?php echo $row->vps_ip; ?></td>
							<td><?php echo $row->label; ?></td>
							<td><?php echo $row->create_date; ?></td>
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
						</tr>
						
					</table>
					
				</div>
			</div>
			
            <?php $this->load->view('_base/footer'); ?>

