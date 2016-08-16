<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Billing extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		vkt_checkAuth();
		$this->load->model('billing_model');
		$this->load->helper('vultr_helper');
	}
	
	
	function index()
	{
		$this->load->view('paypal/index');
	}
	
	function success()
	{
		// $result= $this->verifyWithPayPal ($_GET['tx']);
		
		$this->load->view('paypal/success');
	}
	
	function ipn(){
        //paypal return transaction details array
        $paypalInfo    = $this->input->post();

        // $data['cid'] = $paypalInfo['custom'];
        // $data['type']    = $paypalInfo["item_number"];
        $data['payment'] = $paypalInfo['payment_gross'];
        // $data['txn_id']    = $paypalInfo["txn_id"];
        // $data['currency_code'] = $paypalInfo["mc_currency"];
        // $data['payer_email'] = $paypalInfo["payer_email"];
        // $data['payment_status']    = $paypalInfo["payment_status"];

        //$paypalURL = $this->paypal_lib->paypal_url;        
        //$result    = $this->paypal_lib->curlPost($paypalURL, $paypalInfo);
        
        //check whether the payment is verified
        //if(preg_match("/VERIFIED/i",$result)){
            //insert the transaction data into the database
            $this->product->insertTransaction($data);
        //}
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
	
	
	
	
}

