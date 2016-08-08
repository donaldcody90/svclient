<?php $this->load->view('_base/header'); ?>

			<div class="title">Support</div>
			
			<div class="ticketlist">
			
				<div>
					<a href="<?php echo base_url().'support/addnew'; ?>">
						<button>Open New Ticket</button>
					</a>
					
					<div class= "search">
						<?php echo form_open(base_url().'support/lists', 'method= "GET"'); ?>
							<input type="search" value="<?php echo $this->input->get('filter_title'); ?>" placeholder="Search by Title" name="filter_title" />
						<?php echo form_close(); ?>
					</div>
				</div>
				
				<table class="main-content">
				
					<tr class="hang1">
						<td class="cot1">Subject</td>
						<td class="cot2">Last Reply</td>
						<td class="cot3">Status</td>
						<td class="cot4"></td>
					</tr>
					
					<?php foreach($result as $row){ ?>
						<tr>
							<td><a href="<?php echo base_url().'support/ticket/'.$row->cid; ?>"><?php echo $row->title.'<br>'.$row->cid; ?></a></td>
							<td><?php echo substr($row->content, 0, 20); ?></td>
							<td><?php echo $row->status; ?></td>
							<td><img src="<?php echo base_url().'static/images/logo17.png'; ?>" /></td>
						</tr>
					<?php } ?>
					
					<tr class="zhang-cuoi">
						<td></td>
						<td><?php echo $link; ?></td>
						<td></td>
						<td></td>
					</tr>
					
				</table>
				
			</div>
			
<?php $this->load->view('_base/footer'); ?>