<?php $this->load->view('_base/header'); ?>
            <div class="title">Administrator</div>
            
			<div class="list-user">
				<div>
					<?php echo form_open(base_url().'users/list_user', 'method= "GET" class= "user-search"'); ?>
						<input type="search" value="<?php echo $this->input->get('filter_id'); ?>" name="filter_id" placeholder="Search by ID" />
					<?php echo form_close() ?>
				
					<a href="<?php echo base_url().'users/add_new_user'; ?>"><button>Add new user</button></a> 
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
							<td class="zcot1">ID</td>
							<td class="zcot2">Username</td>
							<td class="zcot3">First Name</td>
							<td class="zcot4">Last Name</td>
							<td class="zcot5">Email</td>
							<td class="zcot6"></td>
							<td class="zcot7"></td>
						</tr>
						
						<?php foreach($result as $row){ ?>
						<tr>
							<td><?php echo $row->id; ?></td>
							<td><?php echo $row->username; ?></td>
							<td><?php echo $row->firstname; ?></td>
							<td><?php echo $row->lastname; ?></td>
							<td><?php echo $row->email; ?></td>
							<td class="zcot6"><a href="<?php echo base_url() . 'users/update/' . $row->id; ?>" >Edit</a></td>
							<td class="zcot7"><a href="<?php echo base_url() . 'users/delete_user/' . $row->id; ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a></td>
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

