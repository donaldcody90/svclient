<?php $this->load->view('_base/header'); ?>
			
            <div class="title">Billing</div>

			<div class="billing">
			
				<div class="credit_card">
					<p>Credit Card</p>
					<ul>
						<li><a href="">Paypal</a></li>
						<li><a href="">Bitcoin</a></li>
						<li><a href="">Gift Code</a></li>
					</ul>
				</div>
				
				<div class="billing_form">
					<form method="post" action="<?php echo $this->config->item('posturl'); ?>">
						
						<p>Make a PayPal Payment</p>
						
						<div class="billingprice">
						
							<input type="hidden" name="upload" value="1" />
							<input type="hidden" name="return" value="<?php echo $this->config->item('returnurl');?>" />
							<input type="hidden" name="notify_url" value="<?php echo $this->config->item('notifyurl');?>" />
							<input type="hidden" name="cmd" value="_cart" />
							<input type="hidden" name="business" value="<?php echo $this->config->item('business'); ?>" />
							
							<!-- Product 1 -->
							<input type="hidden" name="item_name_1" value="PayPal Payment" />
							<input type="hidden" name="item_number_1" value="1" />
							
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
							
							<input type="hidden" name="quantity_1" value="1" />
						
						</div>
						
						<button type="submit">Pay with PayPal</button>
						
						<!-- <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif"> -->
						
					</form>
				</div>
				
			</div>
				
			
            <?php $this->load->view('_base/footer'); ?>

