<?php $this->load->view('_base/header'); ?>
			
            <div class="title">Billing</div>
			
			<div class="nav">
				<ul>
					<li><a href="<?php echo site_url('billing'); ?>">Add Funds</a></li>
					<li><a href="<?php echo site_url('billing/history'); ?>">History</a></li>
				</ul>
				<a href="<?php echo site_url('deploy'); ?>"><div class="deploy_new_server"><span>+</span></div></a>
			</div>
			
			<div class="billing">
			
				<div class="billing_type">
					<ul>
						<li class="creditcard"><a href="<?php echo site_url('billing/creditcard'); ?>">Credit Card</a></li>
						<li class="paypal"><a href="<?php echo site_url('billing/paypal'); ?>">Paypal</a></li>
						<li class="bitcoin"><a href="<?php echo site_url('billing/bitcoin'); ?>">Bitcoin</a></li>
						<li class="giftcode"><a href="">Gift Code</a></li>
					</ul>
				</div>
				
				<div class="billing_form">
					<form action="" method="post">
						
						<p>Make a Bitcoin Payment</p>
						
						<div class="billingprice">
						
							<label>
								<input type="radio" name="amount_1" value="5" checked />
								<div>$5</div>
							</label>
							<label>
								<input type="radio" name="amount_1" value="10" />
								<div>$10</div>
							</label>
							<label>
								<input type="radio" name="amount_1" value="25" />
								<div>$25</div>
							</label>
							<label>
								<input type="radio" name="amount_1" value="50" />
								<div>$50</div>
							</label>
							<label>
								<input type="radio" name="amount_1" value="100" />
								<div>$100</div>
							</label>
							<label>
								<input type="radio" name="amount_1" value="250" />
								<div>$250</div>
							</label>
							
						
						</div>
						
						<input type="submit" name="submit" value="Pay with Bitcoin" class="bitcoin_submit"/>
						
					</form>
				</div>
				
			</div>
				
			
            <?php $this->load->view('_base/footer'); ?>

