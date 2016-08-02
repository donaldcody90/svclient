<?php $this->load->view('_base/header'); ?>

			<div class="title">
				<?php echo $info[0]->c_title; ?><br>
				<p class="conv-info">
					Ticket #<?php echo $info[0]->c_id; ?>&nbsp;&nbsp;
					Opened <?php echo $info[0]->c_openingdate; ?>&nbsp;&nbsp;
					Status: <?php 
							if($info[0]->c_status == 'closed'){
									echo $info[0]->c_status.' (If your issue is not resolved, you can reopen by adding a reply)';
								}else{
									echo $info[0]->c_status;
									} ?>
				</p>
			</div>
			
			<div class="ticket">
			
				<div>
					<?php echo form_open(base_url().'support/close_ticket/'.$info[0]->c_id, 'class="close-ticket"'); ?>
						<?php if($info[0]->c_status == 'opening'){ echo '<button>Close this ticket</button>'; } ?>
					</form>
					
					<?php echo form_open(current_url()); ?>
						<textarea type="text" placeholder="Reply..." name="reply" ></textarea><br>
						<button>Post Reply</button>
						<label>
							<input type="file" name="upload" />
							<img src="<?php echo base_url().'static/images/logo28.png'; ?>" />
						</label>
					</form>
				</div>
				
				<div class="title2">Ticket Messages</div>
				
				<?php foreach($result as $row) { ?>
					<div class="<?php echo $row->role== 'Customer' ? 'message1' : 'message2'; ?>">
						<span><b><?php echo $row->username; ?>&nbsp;</b></span>
						<img class="image2" src="<?php echo $row->role== 'Administrator' ? site_url('static/images/logo29.png') : '' ; ?>" />
						<span class="date"><?php echo $row->m_date; ?></span><br>
						<p class="content"><?php echo $row->m_content; ?></p>
					</div>
				<?php } ?>
				
			</div>
			
			<?php $this->load->view('_base/footer'); ?>