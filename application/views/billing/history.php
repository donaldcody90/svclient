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
						<span class="balance_value">-$5.00</span><br>
						<span class="balance_name">Previous Balance</span>
					</div>
					
					<span class="operator">+</span>
					
					<div class="balance">
						<span class="balance_value charge">$0.54</span><br>
						<span class="balance_name">Charges This Month</span>
					</div>
					
					<span class="operator">=</span>
					
					<div class="balance">
						<span class="balance_value">-$4.46</span><br>
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
							<td>0.54</td>
							<td>-$4.46</td>
							<td></td>
							<td><a href=""><img src="<?php echo site_url('static/images').'/logo39.png'; ?>" /></a></td>
						</tr>
					
						<tr>
							<td>Paypal</td>
							<td>Aug 22 2016</td>
							<td>-$5.00</td>
							<td>-$5.00</td>
							<td>completed</td>
							<td><a href=""><img src="<?php echo site_url('static/images').'/logo39.png'; ?>" /></a></td>
						</tr>
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

