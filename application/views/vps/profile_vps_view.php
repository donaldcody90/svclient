<?php $this->load->view('_base/header'); ?>
			
            <div class="title">User Information</div>

            <div class="edit-view">
                <table class="main-content">
					<tr class="zhang1">
                        <td class="zcot1">Properties</td>
                        <td class="zcot2">Values</td>
                    </tr>
					
                    <tr class="zhang2">
                        <td class="zcot1">IP Address</td>
                        <td class="zcot2"><?php echo $row['vps_ip']; ?></td>
                    </tr>
					
					<tr class="zhang3">
                        <td class="zcot1">Label</td>
                        <td class="zcot2"><?php echo $row['vps_label']; ?></td>
                    </tr>
					
					<tr class="zhang4">
                        <td class="zcot1">Rootpass</td>
                        <td class="zcot2"><?php echo $row['rootpass']; ?></td>
                    </tr>
                    
                    <tr class="zhang-cuoi">
                        <td></td>
                        <td><a href="<?php echo base_url().'vps/update/'. $row['id']; ?>"><button>Edit</button></a></td>
                    </tr>
                </table>
            </div>
			
            <?php $this->load->view('_base/footer'); ?>

