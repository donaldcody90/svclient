<?php $this->load->view('_base/header'); ?>
			
            <div class="title">User Information</div>
            
            <!--<div class="tabs">
                <table>
                    <tr>
                        <td>
                            <ul>
								<?php
									if($this->session->userdata('access') == 'Administrator')
									{	echo '<li class="tab-link"><a href="http://localhost/vultr/users/list_user" >User List</a></li>';	}
								?>
								<li class="tab-link"><a href="<?php echo base_url().'users/profile/'.$row->id; ?>" >Profile</a></li>
                                <li class="tab-link current" data-tab="#zTickets">Edit User</li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>-->

            <div class="edit-view">
			<?php echo form_open(base_url().'users/update/'.$row->id); ?>
                <table class="main-content">
					<tr class="zhang1">
                        <td class="zcot1">Properties</td>
                        <td class="zcot2">Values</td>
                        <td class="zcot3">Edit</td>
                    </tr>
					
                    <tr class="zhang2">
                        <td class="zcot1">Username</td>
                        <td class="zcot2"><?php echo $row->username; ?></td>
                        <td class="zcot3"><input type="text" name="edit_username" placeholder="Change username" /></td>
                    </tr>
					<?php echo form_error('edit_username', '<div class="error">', '</div>'); ?>
					
					<tr class="zhang3">
                        <td class="zcot1">Full Name</td>
                        <td class="zcot2"><?php echo $row->fullname; ?></td>
                        <td class="zcot3"><input type="text" name="edit_fullname" placeholder="Change full name" /></td>
                    </tr>
					<?php echo form_error('edit_fullname', '<div class="error">', '</div>'); ?>
					
					<tr class="zhang5">
                        <td class="zcot1">Email</td>
                        <td class="zcot2"><?php echo $row->email; ?></td>
                        <td class="zcot3"><input type="text" name="edit_email" placeholder="Change email" /></td>
                    </tr>
					<?php echo form_error('edit_email', '<div class="error">', '</div>'); ?>
					
					<tr class="zhang6">
                        <td class="zcot1">Password</td>
                        <td class="zcot2">******</td>
                        <td class="zcot3"><input type="password" name="edit_password" class="edit_user_form" placeholder="Change password" /></td>
                    </tr>
					<?php echo form_error('edit_password', '<div class="error">', '</div>'); ?>
					
					<tr class="zhang7">
                        <td class="zcot1">User type</td>
                        <td class="zcot2">Customer</td>
                        <td class="zcot3"></td>
                    </tr>
					
					<tr class="zhang8">
                        <td class="zcot1"></td>
                        <td class="zcot2"></td>
                        <td class="zcot3"><div class="button">
							<button>Confirm</button>
							<a href="<?php 
									if ($this->session->userdata('access') == 'Administrator'){
										echo base_url().'users/list_user';
									}
									else{
										echo base_url().'users/profile/'.$row->id;
									} ?>
										"><div class="cancel"><center>Cancel</center></div></a>
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

