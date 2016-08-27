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
		redirect('billing/paypal');
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
	
	function settings()
	{
		$this->load->view('billing/settings');
	}
	
	
	
	
}

