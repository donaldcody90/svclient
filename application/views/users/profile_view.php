<?php $this->load->view('_base/header'); ?>
			
            <div class="title">User Information</div>

            <div class="edit-view">
                <table class="main-content">
					<tr class="zhang1">
                        <td class="zcot1">Properties</td>
                        <td class="zcot2">Values</td>
                    </tr>
					
                    <tr class="zhang2">
                        <td class="zcot1">Username</td>
                        <td class="zcot2"><?php echo $row->username; ?></td>
                    </tr>
					
					<tr class="zhang3">
                        <td class="zcot1">First Name</td>
                        <td class="zcot2"><?php echo $row->firstname; ?></td>
                    </tr>
					
					<tr class="zhang4">
                        <td class="zcot1">Last Name</td>
                        <td class="zcot2"><?php echo $row->lastname; ?></td>
                    </tr>
					
					<tr class="zhang5">
                        <td class="zcot1">Email</td>
                        <td class="zcot2"><?php echo $row->email; ?></td>
                    </tr>
					
					<tr class="zhang6">
                        <td class="zcot1">User type</td>
                        <td class="zcot2">Customer</td>
                    </tr>
                    
                    <tr class="zhang-cuoi">
                        <td></td>
                        <td><a href="<?php echo base_url().'users/update/'. $row->id; ?>"><button>Edit</button></a></td>
                    </tr>
                </table>
            </div>
			
            <?php $this->load->view('_base/footer'); ?>

