<?php $this->load->view('_base/header'); ?>
			
            <div class="title">Add New VPS</div>
			
			<?php $this->load->view('_base/message'); ?>

            <div class="addnew-view">
			
				<?php echo form_open(base_url().'vps/add'); ?>
					<table class="main-content">
						<tr class="zhang1">
							<td class="zcot1">Properties</td>
							<td class="zcot2">Values</td>
						</tr>
						
						<tr class="zhang2">
							<td class="zcot1">Datacenter</td>
							<td class="zcot2">
								<select name="servers" class="edit_user_form">
									<?php foreach($servers as $value){ ?>
										<option value="<?php echo $value['id']; ?>"><?php echo $value['label']; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						
						<tr class="zhang2">
							<td class="zcot1">Label</td>
							<td class="zcot2">
								<input type="text" name="label" class="edit_user_form" value="" />
								<?php echo form_error('label', '<div class="error">', '</div>'); ?>
							</td>
						</tr>
						
						<tr class="zhang3">
							<td class="zcot1">IP Address</td>
							<td class="zcot2">
								<input type="text" name="ip" class="edit_user_form" value="" />
								<?php echo form_error('ip', '<div class="error">', '</div>'); ?>
							</td>
						</tr>
						
						<tr class="zhang4">
							<td class="zcot1">Space (GB)</td>
							<td class="zcot2">
								<input type="text" name="space" class="edit_user_form" value="" />
								<?php echo form_error('space', '<div class="error">', '</div>'); ?>
							</td>
						</tr>
						
						<tr class="zhang5">
							<td class="zcot1">Ram (MB)</td>
							<td class="zcot2">
								<input type="text" name="ram" class="edit_user_form" value="" />
								<?php echo form_error('ram', '<div class="error">', '</div>'); ?>
							</td>
						</tr>
						
						<tr class="zhang6">
							<td class="zcot1"></td>
							<td class="zcot2"><div class="button">
								<input type="submit" class="save" name="save" value="Add" />
								<a href="<?php echo base_url().'vps/lists'; ?>">
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

