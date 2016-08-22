<?php $this->load->view('_base/header'); ?>
			
            <div class="title">Add New User</div>
			
			<?php $this->load->view('_base/message'); ?>

            <div class="addnew-view">
			
				<?php echo form_open(base_url().'users/add_new_user'); ?>
					<table class="main-content">
						<tr class="zhang1">
							<td class="zcot1">Properties</td>
							<td class="zcot2">Edit</td>
						</tr>
						
						<tr class="zhang2">
							<td class="zcot1">Username</td>
							<td class="zcot2">
								<input type="text" name="username" value="" />
								<?php echo form_error('username', '<div class="error">', '</div>'); ?>
							</td>
						</tr>
						
						<tr class="zhang3">
							<td class="zcot1">Full Name</td>
							<td class="zcot2">
								<input type="text" name="fullname" value="" />
								<?php echo form_error('fullname', '<div class="error">', '</div>'); ?>
							</td>
						</tr>
						
						<tr class="zhang5">
							<td class="zcot1">Email</td>
							<td class="zcot2">
								<input type="text" name="email" value="" />
								<?php echo form_error('email', '<div class="error">', '</div>'); ?>
							</td>
						</tr>
						
						<tr class="zhang6">
							<td class="zcot1">Password</td>
							<td class="zcot2">
								<input type="text" name="password" value="" />
								<?php echo form_error('password', '<div class="error">', '</div>'); ?>
							</td>
						</tr>
						
						<tr class="zhang7">
							<td class="zcot1">Password Confirm</td>
							<td class="zcot2">
								<input type="text" name="passconf" value="" />
								<?php echo form_error('passconf', '<div class="error">', '</div>'); ?>
							</td>
						</tr>
						
						<tr class="zhang8">
							<td class="zcot1"></td>
							<td class="zcot2"><div class="button">
								<button>Add</button>
								<a href="<?php echo base_url().'users/list_user'; ?>">
									<div class="cancel"><center>Cancel</center></div>
								</a>
							</div></td>
						</tr>
						
						<tr class="zhang-cuoi">
							<td></td>
							<td></td>
						</tr>
					</table>
				<?php echo form_close(); ?>
				
            </div>
			
            <?php $this->load->view('_base/footer'); ?>

