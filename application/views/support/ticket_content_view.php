<?php $this->load->view('_base/header'); ?>

			<div class="title">
				<?php echo $info->title; ?><br>
				<p class="conv-info">
					Ticket #<?php echo $info->cid; ?>&nbsp;&nbsp;
					Opened <?php echo $info->openingdate; ?>&nbsp;&nbsp;
					Status: <?php 
							if($info->status == 'closed'){
									echo $info->status.' (If your issue is not resolved, you can reopen by adding a reply)';
								}else{
									echo $info->status;
									} ?>
				</p>
			</div>
			
			<div class="ticket">
			
				<div>
					
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
					<div class="<?php echo $row->role== 'Administrator' ? 'message2' : 'message1'; ?>">
						<span><b><?php echo $row->username; ?>&nbsp;</b></span>
						<img class="image2" src="<?php echo $row->role== 'Administrator' ? site_url('static/images/logo29.png') : '' ; ?>" />
						<span class="date"><?php echo $row->date; ?></span><br>
						<p class="content"><?php echo $row->content; ?></p>
					</div>
				<?php } ?>
				
			</div>
			
			<?php $this->load->view('_base/footer'); ?>