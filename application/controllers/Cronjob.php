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
		$date= date("Y-m-d h:i:s");
		$result= $this->billing_model->servercharge($date);
		if($result==true){
			$result_2= $this->billing_model->moneydecrease();
			if($result_2==false){
				echo "Failed";
			}
		}
	}
	
	function resetservercharge()
	{
		$month= date("m");
		$year= date("Y");
		$date= date("Y-m-d");
		$result= $this->billing_model->noteservercharge($month, $year, $date);
		if($result==true){
			$date_time= date("Y-m-d h:i:s");
			$result_2= $this->billing_model->resetservercharge($month, $year, $date_time);
			if($result_2==false){
				echo "Failed";
			}
		}
	}
	
	
	
	
	
}

