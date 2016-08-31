<?php $this->load->view('_base/header'); ?>

			<div class="title">Open New Support Ticket</div>
			
			<div class="nav">
				<ul>
					<li><a href="">Knowledgebase</a></li>
					<li><a href="<?php echo site_url('support/lists'); ?>">Tickets</a></li>
				</ul>
				<a href="<?php echo site_url('deploy'); ?>"><div class="deploy_new_server"><span>+</span></div></a>
			</div>
			
			<div class="addnew-ticket">
				
				<div class="addticket-title">Create Ticket</div>
				
				<?php echo form_open(base_url().'support/addnew'); ?>
				
					<div class="button1">
						<?php foreach($cat as $value)
						{
							if($value['status'] == 1)
							{	echo '<label>
									<input type="radio" name="ticket-type" value="'.$value['id'].'" />
									<div>'.$value['name'].'</div>
								</label>';
							}
						}
						?>
					</div>
				
					<select name="vpsid">
						<option value="">None</option>
						<?php foreach($vps as $value){ ?>
							<option value="<?php echo $value['id']; ?>"><?php echo $value['vps_label']; ?></option>
						<?php } ?>
					</select>
					
					<input type="text" class="subject" name="ticket-subject" placeholder="Subject" />
					<textarea class="message" name="ticket-message" placeholder="Message" ></textarea>
				
					<div class="upload">
						<span>Attach file(Max file size is 4.1 MB)</span><br>
						<input type="file" name="" />
					</div>
					
					<input class="button3" type="submit" value="Open Ticket" name="open">
				<?php echo form_close(); ?>
				
			</div>
			
			<?php $this->load->view('_base/footer'); ?>