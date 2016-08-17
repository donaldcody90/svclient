<?php $this->load->view('_base/header'); ?>
			
            <div class="title">SUCCESS</div>
            
			First name: <?php echo $first_name; ?>
			<br>
			Last name: <?php echo $last_name; ?>
			<br>
			Payment Status: <?php echo $payment_status; ?>
			<br><br>
			<b>List Product</b>
			<?php 
			foreach( $listProducts as $product)
			{
				echo '<br>Product Id: '. $product['item_number'];
				echo '<br>Product Name: '. $product['item_name'];
				echo '<br>Quantity: '. $product['quantity'];
				echo '<br>Gross: '. $product['mc_gross'];
				echo '<br>====================================';
			}
			?>
			
            <?php $this->load->view('_base/footer'); ?>

