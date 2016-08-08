<?php $this->load->view('_base/header'); ?>

			<div class="title">List of Data centers</div>
			
			<div class="addnew-ticket">
				
				<span class="addticket-title">Create Ticket</span>
				
				<?php echo form_open(base_url().'support/addnew'); ?>
				
					<div class="button1">
						<?php if($general->status == 1) 
						{
							echo '<label>
									<input type="radio" name="ticket-type" value="'.$general->id.'" checked />
									<div>General Support</div>
								</label>';
						}
						if($billing->status == 1)
						{
							echo '<label>
									<input type="radio" name="ticket-type" value="'.$billing->id.'" />
									<div>Billing Questions</div>
								</label>';
						}
						?>
					</div>
				
					<select>
						<option value="">None</option>
						<option value=""></option>
						<option value=""></option>
					</select>
					
					<input type="text" class="subject" name="ticket-subject" placeholder="Subject" />
					<textarea class="message" name="ticket-message" placeholder="Message" ></textarea>
				
					<div class="upload">
						<span>Attach file(Max file size is 4.1 MB)</span><br>
						<input type="file" name="" />
					</div>
					
					<button class="button3">Open Ticket</button>
				<?php echo form_close(); ?>
				
			</div>
			
			<?php $this->load->view('_base/footer'); ?>