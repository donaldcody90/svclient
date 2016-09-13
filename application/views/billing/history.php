<?php $this->load->view('_base/header'); ?>
			
            <div class="title">Billing</div>
			
			<div class="nav">
				<ul>
					<li><a href="<?php echo site_url('billing'); ?>">Add Funds</a></li>
					<li><a href="<?php echo site_url('billing/history'); ?>">History</a></li>
				</ul>
				<a href="<?php echo site_url('deploy'); ?>"><div class="deploy_new_server"><span>+</span></div></a>
			</div>
			
			<div class="billing_settings">
			
				<div class="balance_description">
					<div class="balance">
						<span class="balance_value">$<?php echo $pendingcharges['amount'] + $pendingcharges['total']; ?></span><br>
						<span class="balance_name">Previous Balance</span>
					</div>
					
					<span class="operator">-</span>
					
					<div class="balance">
						<span class="balance_value charge">$<?php echo $pendingcharges['total']; ?></span><br>
						<span class="balance_name">Charges This Month</span>
					</div>
					
					<span class="operator">=</span>
					
					<div class="balance">
						<span class="balance_value">$<?php echo $pendingcharges['amount']; ?></span><br>
						<span class="balance_name">Current Balance</span>
					</div>
				</div>
				
				<div class="">
					<table class="detail">
						<tr>
							<td class="c1">Description</td>
							<td class="c2">Date</td>
							<td class="c3">Amount</td>
							<td class="c4">Balance</td>
							<td class="c5"></td>
							<td class="c6"></td>
						</tr>
					
						<tr>
							<td>Pending Charges</td>
							<td>---</td>
							<td><?php echo '$'.$pendingcharges['total']; ?></td>
							<td><?php echo '$'.$pendingcharges['amount']; ?></td>
							<td></td>
							<td><a href=""><img src="<?php echo site_url('static/images').'/logo39.png'; ?>" /></a></td>
						</tr>
						<?php foreach($billing as $value){ ?>
						<tr>
							<td><?php echo $value['description']; ?></td>
							<td><?php echo $value['created_date']; ?></td>
							<td>$<?php echo $value['amount']; ?></td>
							<td>$<?php echo $value['balance']; ?></td>
							<td>completed</td>
							<td><a href=""><img src="<?php echo site_url('static/images').'/logo39.png'; ?>" /></a></td>
						</tr>
						<?php } ?>
					</table>
				</div>
				
				<div class="notification">
					<span>New Invoice Notification</span><br>
					<select class="" name="" >
						<option value="">None</option>
						<option value=""></option>
						<option value=""></option>
					</select>
					<input type="submit" class="" name="" value="Save"/>
				</div>
				
			</div>
				
			
            <?php $this->load->view('_base/footer'); ?>

