<?php $this->load->view('_base/header'); ?>
			
            <div class="title">Billing</div>
			
			<div class="nav">
				<ul>
					<li><a href="<?php echo site_url('billing/paypal'); ?>">Add Funds</a></li>
					<li><a href="<?php echo site_url('billing/history'); ?>">History</a></li>
				</ul>
				<a href="<?php echo site_url('deploynewserver'); ?>"><div class="deploy_new_server"><span>+</span></div></a>
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
					<form>
						
						<p>Make a Credit Card Payment</p>
						
						
						<!-- <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif"> -->
						
					</form>
				</div>
				
			</div>
				
			
            <?php $this->load->view('_base/footer'); ?>

