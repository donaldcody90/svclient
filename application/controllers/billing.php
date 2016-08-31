<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Billing extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		vkt_checkAuth();
		$this->load->model('billing_model');
	}
	
	function index()
	{
		redirect('billing/creditcard');
	}
	
	function creditcard()
	{
		$this->load->view('billing/creditcard/index');
	}
	
	function paypal()
	{
		$param_where= array('id' => 1);
		$data['paypal']= $this->billing_model->getPaypal($param_where);
		$this->load->view('billing/paypal/index', $data);
	}
	
	function success()
	{
		$result= array(); //$this->verifyWithPayPal ($_GET['tx']);
		$this->load->view('billing/paypal/success', $result);
	}
	
	function ipn()
	{
		$file="ipn.txt";
		
		foreach($this->input->post() as $key => $value) {
			  //echo $key." = ". $value."<br>";
			  $text=$key.'=>'.$value;
				file_put_contents($file,PHP_EOL .$text,FILE_APPEND);
		}

    }
	
	function getListProducts($result) 
	{
		$i= 1;
		$data= array();
		foreach($result as $key => $value)
		{
			if (0 === strpos($key, 'item_number' ))
			{
				$product= array(
							'item_number' => $result['item_number' .$i],
							'item_name' => $result['item_name'. $i],
							'quantity' =>$result ['quantity'. $i],
							'mc_gross' => $result ['mc_gross' . $i]
						);
				array_push ($data, $product);
				$i ++;
			}
		}
		return $data;
	}
	
	function verifyWithPayPal($tx)
	{
		$tx= $_REQUEST['tx'];
		$token= $this->config->item('authtoken');
		$paypal_url= $this->config->item('posturl') . '?cmd=_notify-synch&tx=' . $tx . '&at=' . $token;
		$curl = curl_init ($paypal_url);
		$data=array(
				"cmd" => "_notify-synch",
				"tx" => $tx, 
				"at" => $token
			);
		$data_string= json_encode($data);
		curl_setopt($curl, CURLOPT_HEADER, 0 );
		curl_setopt($curl, CURLOPT_POST, 1 );
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string );
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );
		$headers= array(
				'Content-Type: application/x-www-form-urlencoded',
				'Host: www.sandbox.paypal.com',
				'Connection: close'
			);
		curl_setopt( $curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers);
		$response= curl_exec($curl);
		$lines= explode ("\n", $response);
		$keyarray= array();
		if (strcmp ($lines[0], "SUCCESS") == 0) 
		{
			for($i = 1; $i < count($lines ); $i++)
			{
				list($key, $val)= explode("=", $lines[$i]);
				$keyarray[urldecode($key)] = urldecode ($val);
			}
			$keyarray['listProduct']= $this->getListProducts($keyarray);
		}
		return $keyarray;
		
	}
	
	function bitcoin($price= null)
	{
		if($this->input->post('submit') or $this->input->post('submit2'))
		{
			require_once APPPATH.'third_party\bitcoin\cryptobox.class.php';
			
			//$price= $this->input->post('amount_1');
			$options = array( 
				"public_key"  => "6026AANTIP1Bitcoin77BTCPUBDLgfBeavOlxvRE6pXaLOArYY",         // place your public key from gourl.io
				"private_key" => "6026AANTIP1Bitcoin77BTCPRVexTOI8GIvYgL2FfiDcHcUPOj",         // place your private key from gourl.io
				"webdev_key"  => "",        // optional, gourl affiliate program key
				"orderID"     => "product1", // few your users can have the same orderID but combination 'orderID'+'userID' should be unique
				"userID"      => "",        // optional; place your registered user id here (user1, user2, etc)
						// for example, on premium page you can use for all visitors: orderID="premium" and userID="" (empty) 
						// when userID value is empty - system will autogenerate unique identifier for every user and save it in cookies 
				"userFormat"  => "COOKIE",   // save your user identifier userID in cookies. Available: COOKIE, SESSION, IPADDRESS, MANUAL
				"amount"      => 0,         // amount in cryptocurrency or in USD below
				"amountUSD"   => $price,         // price is 2 USD; it will convert to cryptocoins amount, using Live Exchange Rates
											// For convert fiat currencies Euro/GBP/etc. to USD, use function convert_currency_live()
				"period"      => "24 HOUR",  // payment valid period, after 1 day user need to pay again
				"iframeID"    => "",         // optional; when iframeID value is empty - system will autogenerate iframe html payment box id
				"language"    => "EN"       // text on EN - english, FR - french, please contact us and we can add your language
				);  
				
			// Initialise Bitcoin Payment Class 
			$box = new Cryptobox ($options);

			// Display payment box with custom width = 560 px and big qr code / or successful result
			$payment_box = $box->display_cryptobox(true, 560, 230, "border-radius:15px;border:1px solid #eee;padding:3px 6px;margin:10px;",
							"display:inline-block;max-width:580px;padding:15px 20px;border:1px solid #eee;margin:7px;line-height:25px;"); 
			$data['payment_box']= $payment_box;
			// Log
			$data['message'] = "";
			
			// A. Process Received Payment
			if ($box->is_paid()) 
			{
				$data['message'] .= "A. User will see this message during 24 hours after payment has been made!";
				
				$data['message'] .= "<br>".$box1->amount_paid()." ".$box1->coin_label()."  received<br>";
			//redirect('billing/bitcoin');
				// Your code here to handle a successful cryptocoin payment/captcha verification
				// For example, give user 24 hour access to your member pages
				// ...
			
				// Please use IPN (instant payment notification) function cryptobox_new_payment() for update db records, etc
				// Function cryptobox_new_payment($paymentID = 0, $payment_details = array(), $box_status = "") called every time 
				// when a new payment from any user is received.
				// IPN description: https://gourl.io/api-php.html#ipn 
			}  
			else 
			{
				$data['message'] .= "The payment has not been made yet";
			//redirect('billing/bitcoin');
			}
			$this->load->view('billing/bitcoin/payment', $data);
		}
		else{
			$param_where= array('id' => 1);
			$data['paypal']= $this->billing_model->getPaypal($param_where);
			$this->load->view('billing/bitcoin/index');
		}
	}
	
	function bitcoinpayment()
	{
		
			require_once APPPATH.'third_party\bitcoin\cryptobox.class.php';
			
			$price= $this->input->post('amount_1');
			$options = array( 
				"public_key"  => "6026AANTIP1Bitcoin77BTCPUBDLgfBeavOlxvRE6pXaLOArYY",         // place your public key from gourl.io
				"private_key" => "6026AANTIP1Bitcoin77BTCPRVexTOI8GIvYgL2FfiDcHcUPOj",         // place your private key from gourl.io
				"webdev_key"  => "",        // optional, gourl affiliate program key
				"orderID"     => "product1", // few your users can have the same orderID but combination 'orderID'+'userID' should be unique
				"userID"      => "",        // optional; place your registered user id here (user1, user2, etc)
						// for example, on premium page you can use for all visitors: orderID="premium" and userID="" (empty) 
						// when userID value is empty - system will autogenerate unique identifier for every user and save it in cookies 
				"userFormat"  => "COOKIE",   // save your user identifier userID in cookies. Available: COOKIE, SESSION, IPADDRESS, MANUAL
				"amount"      => 0,         // amount in cryptocurrency or in USD below
				"amountUSD"   => $price,         // price is 2 USD; it will convert to cryptocoins amount, using Live Exchange Rates
											// For convert fiat currencies Euro/GBP/etc. to USD, use function convert_currency_live()
				"period"      => "24 HOUR",  // payment valid period, after 1 day user need to pay again
				"iframeID"    => "",         // optional; when iframeID value is empty - system will autogenerate iframe html payment box id
				"language"    => "EN"       // text on EN - english, FR - french, please contact us and we can add your language
				);  
				
			// Initialise Bitcoin Payment Class 
			$box = new Cryptobox ($options);

			// Display payment box with custom width = 560 px and big qr code / or successful result
			$payment_box = $box->display_cryptobox(true, 560, 230, "border-radius:15px;border:1px solid #eee;padding:3px 6px;margin:10px;",
							"display:inline-block;max-width:580px;padding:15px 20px;border:1px solid #eee;margin:7px;line-height:25px;"); 
			$data['payment_box']= $payment_box;
			// Log
			$data2['message'] = "";
			
			// A. Process Received Payment
			if ($box->is_paid()) 
			{
				$data2['message'] .= "A. User will see this message during 24 hours after payment has been made!";
				
				$data2['message'] .= "<br>".$box1->amount_paid()." ".$box1->coin_label()."  received<br>";
				redirect('billing/bitcoin');
				// Your code here to handle a successful cryptocoin payment/captcha verification
				// For example, give user 24 hour access to your member pages
				// ...
			
				// Please use IPN (instant payment notification) function cryptobox_new_payment() for update db records, etc
				// Function cryptobox_new_payment($paymentID = 0, $payment_details = array(), $box_status = "") called every time 
				// when a new payment from any user is received.
				// IPN description: https://gourl.io/api-php.html#ipn 
			}  
			else 
			{
				$data2['message'] .= "The payment has not been made yet";
				redirect('billing/bitcoin');
			}
			$this->load->view('billing/bitcoin/payment', $data);
		
	}
	
	function history()
	{
		$this->load->view('billing/history');
	}
	
	
	
	
}

