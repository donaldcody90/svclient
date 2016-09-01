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
					<form>
						
						<p>Make a Credit Card Payment</p>
						
						<input class="info info1" type="text" name="cardname" placeholder="Name on Card" />
						<input class="info info1" type="text" name="billingaddress" placeholder="Billing Address" />
						<input class="info info2" type="text" name="billingcity" placeholder="Billing City" />
						<input class="info info3" type="text" name="postalcode" placeholder="Postal Code" />
						
						<select name="country">
							<option value="">Viet Nam</option>
						</select>
						
						<div class="credit_card_details">
							<span>Credit Card Details</span><br>
							<input class="detail1" type="text" name="cardnumber" placeholder="Card Number" />
							<input class="detail2" type="text" name="month" placeholder="Expire Month (MM)" />
							<input class="detail2" type="text" name="year" placeholder="Expire Year (YY)" />
							<input class="detail3" type="text" name="securitycode" placeholder="Security Code" />
						</div>
						
						<div class="billingprice">
							<p>Choose Amount</p>
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
							<label>
								<input type="radio" name="amount_1" value="0" />
								<div class="price2">Link Only ($0 deposit)</div>
							</label>
							<label>
								<input type="radio" name="amount_1" value="" />
								<div class="price2">Choose a different amount</div>
							</label>
						</div>
						
						<input type="submit" name="submit" value="Pay with Credit Card" class="submit"/>
						
					</form>
				</div>
				
			</div>
				
			
            <?php $this->load->view('_base/footer'); ?>

