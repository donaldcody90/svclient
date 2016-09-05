<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('billing_model');
	}
	
	function autoinvoice()
	{
		$result= $this->billing_model->servercharge();
		if($result==true){
			$result_2= $this->billing_model->moneydecrease();
			if($result_2==false){
				echo "Failed";
			}
		}
	}
	
	function resetservercharge()
	{
		$result= $this->billing_model->noteservercharge();
		if($result==true){
			$result_2= $this->billing_model->resetservercharge();
			if($result_2==false){
				echo "Failed";
			}
		}
	}
	
	
	
	
	
}

