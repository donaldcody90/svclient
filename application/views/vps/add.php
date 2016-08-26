<?php $this->load->view('_base/header'); ?>
			
            <div class="title">Deploy New Instance</div>
			
			<?php $this->load->view('_base/message'); ?>
			
            <div class="deploy">
				<form action="<?php echo site_url().'vps/add'; ?>" method="post">
				
					<div class="servers-location deploy_division">
						<div class="deploy_title">
							<div>1</div>
							<span>Server Location</span>
						</div>
						<div class="deploy_body deploy_body1">
						<?php foreach ($servers as $value){ ?>
							<label>
								<input type="radio" name="server" value="<?php echo $value['id']; ?>" />
								<div class="detail">
									<img src="<?php echo site_url().'static/images/flag_'.$value['location'].'.png'; ?>" />
									<div>
										<span class="label"><?php echo $value['label']; ?></span><br>
										<span class="location"><?php echo $value['location']; ?></span>
									</div>
								</div>
							</label>
						<?php } ?>
						</div>
					</div>
					
					
					<div class="server-size deploy_division">
						<div class="deploy_title">
							<div>2</div>
							<span>Server Size</span>
						</div>
						<div class="deploy_body deploy_body2">
						<?php foreach ($plans as $value){ ?>
							
							<label>
								<input type="radio" name="plan" value="<?php echo $value['id']; ?>" />
								<div class="vps">
									<div class="vps_detail">
										<span><?php echo '<span class="name">'.$value['name'].'</span>'; ?></span><br>
										<span><?php echo '<span class="price"><span class="price_value">$'.$value['price'].'</span>/mo</span>'; ?></span><br>
										<span><?php echo '<div class="value division">$'.vst_DivisionMonthtoHour($value['price']).'/hr</div>'; ?></span>
									</div>
									<div class="vps_detail2">
										<span><?php echo '<span class="value"><b>'.$value['cpu_core'].'</b> CPU</span>'; ?></span><br>
										<span><?php echo '<span class="value"><b>'.$value['ram'].'MB</b> Memory</span>'; ?></span><br>
										<span><?php echo '<span class="value"><b>'.$value['bandwidth'].'</b> Bandwidth</span>'; ?></span>
									</div>
								</div>
							</label>
							
						<?php } ?>
						</div>
					</div>
					
					
					<div class="server-hnandlb deploy_division">
						<div class="deploy_title">
							<div>3</div>
							<span>Server IP & Label</span>
						</div>
						<div class="deploy_body deploy_body3">
							<input class="ip" type="text" placeholder="Enter server ip" name="ip" />
							<input class="label" type="text" placeholder="Enter server label" name="label" />
						</div>
					</div>
					
					<div class="consequence">
						<div class="total">
							<span class="summary">Summary:</span><br>
							<span class="order_total"></span>&nbsp;&nbsp;<span class="order_total_hr"></span>
						</div>
						<input class="submit" type="submit" name="deploy" value="Deploy Now" />
					</div>
				</form>
            </div>
			
            <?php $this->load->view('_base/footer'); ?>

