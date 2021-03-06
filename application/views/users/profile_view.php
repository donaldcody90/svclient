<?php $this->load->view('_base/header'); ?>
			
            <div class="title">User Information</div>
			
			<div class="nav">
				<ul>
					<li><a href="<?php echo site_url('users/profile').'/'.$row['id']; ?>">Profile</a></li>
					<li><a href="<?php echo site_url('users/update').'/'.$row['id']; ?>">Authentication</a></li>
					<li><a href="">API</a></li>
					<li><a href="">Notifications</a></li>
				</ul>
				<a href="<?php echo site_url('deploy'); ?>"><div class="deploy_new_server"><span>+</span></div></a>
			</div>
			
			<?php $this->load->view('_base/message'); ?>

            <div class="edit-view">
                <table class="main-content">
					<tr class="zhang1">
                        <td class="zcot1">Properties</td>
                        <td class="zcot2">Values</td>
                    </tr>
					
                    <tr class="zhang2">
                        <td class="zcot1">Username</td>
                        <td class="zcot2"><?php echo $row['username']; ?></td>
                    </tr>
					
					<tr class="zhang3">
                        <td class="zcot1">Full Name</td>
                        <td class="zcot2"><?php echo $row['fullname']; ?></td>
                    </tr>
					
					<tr class="zhang5">
                        <td class="zcot1">Email</td>
                        <td class="zcot2"><?php echo $row['email']; ?></td>
                    </tr>
					
					<tr class="zhang6">
                        <td class="zcot1">User type</td>
                        <td class="zcot2">Customer</td>
                    </tr>
                    
                    <tr class="zhang-cuoi">
                        <td></td>
                        <td><a href="<?php echo base_url().'users/update/'. $row['id']; ?>"><button class="edit">Edit</button></a></td>
                    </tr>
                </table>
            </div>
			
            <?php $this->load->view('_base/footer'); ?>

