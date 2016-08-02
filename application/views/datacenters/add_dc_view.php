<?php $this->load->view('_base/header'); ?>
			
            <div class="title">Add New Data Center</div>

            <div class="addnew-view">
			
				<?php echo form_open(base_url().'datacenters/add'); ?>
					<table class="main-content">
						<tr class="zhang1">
							<td class="zcot1">Properties</td>
							<td class="zcot2">Edit</td>
						</tr>
						
						<tr class="zhang2">
							<td class="zcot1">IP Address</td>
							<td class="zcot2">
								<input type="text" name="ip" class="edit_user_form" value="" />
								<?php echo form_error('ip', '<div class="error">', '</div>'); ?>
							</td>
						</tr>
						
						<tr class="zhang3">
							<td class="zcot1">Key</td>
							<td class="zcot2">
								<input type="text" name="key" class="edit_user_form" value="" />
								<?php echo form_error('key', '<div class="error">', '</div>'); ?>
							</td>
						</tr>
						
						<tr class="zhang4">
							<td class="zcot1">Password</td>
							<td class="zcot2">
								<input type="text" name="password" class="edit_user_form" value="" />
								<?php echo form_error('password', '<div class="error">', '</div>'); ?>
							</td>
						</tr>
						
						<tr class="zhang6">
							<td class="zcot1"></td>
							<td class="zcot2"><div class="button">
								<button>Add</button>
								<a href="<?php echo base_url().'datacenters/lists'; ?>">
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

