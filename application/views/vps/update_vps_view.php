<?php $this->load->view('_base/header'); ?>
			
            <div class="title">Data Center Information</div>

            <div class="edit-view">
			<?php echo form_open(base_url().'vps/update/'.$row['id']); ?>
                <table class="main-content">
					<tr class="zhang1">
                        <td class="zcot1">Properties</td>
                        <td class="zcot2">Values</td>
                        <td class="zcot3">Edit</td>
                    </tr>
					
					<tr class="zhang3">
                        <td class="zcot1">IP Address</td>
                        <td class="zcot2"><?php echo $row->ip; ?></td>
                        <td class="zcot3"><input type="text" name="edit_ip" placeholder="Change IP Address" /></td>
                    </tr>
					<?php echo form_error('edit_ip', '<div class="error">', '</div>'); ?>
					
					<tr class="zhang4">
                        <td class="zcot1">Key</td>
                        <td class="zcot2"><?php echo $row->svkey; ?></td>
                        <td class="zcot3"><input type="text" name="edit_key" placeholder="Change Key" /></td>
                    </tr>
					<?php echo form_error('edit_key', '<div class="error">', '</div>'); ?>
					
					<tr class="zhang5">
                        <td class="zcot1">Password</td>
                        <td class="zcot2"><?php echo $row->svpass; ?></td>
                        <td class="zcot3"><input type="text" name="edit_password" class="edit_user_form" placeholder="Change Password" /></td>
                    </tr>
					<?php echo form_error('edit_password', '<div class="error">', '</div>'); ?>
					
					<tr class="zhang6">
                        <td class="zcot1"></td>
                        <td class="zcot2"></td>
                        <td class="zcot3"><div class="button">
							<button>Confirm</button>
							<a href="<?php echo site_url().'vps/lists/'.$row['cuid']; ?>" ><div class="cancel"><center>Cancel</center></div></a>
						</div></td>
                    </tr>
                    
                    <tr class="zhang-cuoi">
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
			<?php echo form_close(); ?>
            </div>
			
            <?php $this->load->view('_base/footer'); ?>

