<?php

defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('is_logged_in'))
{
	function is_logged_in() {
	
	    $CI =& get_instance();
	    $user = $CI->session->userdata('vkt_currentUser');
		if($user){
			return true;
		}else{
			return false;
		}
	}
}

if ( ! function_exists('vkt_checkAuth'))
{
	function vkt_checkAuth() {
	    $CI =& get_instance();
	    $user = $CI->session->userdata('logged_in');
		if(!$user){
			$CI->load->helper('url');
			redirect('auth/login');
		}/*else{
			$cController=vst_getController();
			$configs=$CI->config->item('site');
			if(!in_array($cController,$configs['role_auth'][$user['role']]))
			{
				die("Access denied");
			}
			
		}*/
	}
}

if ( ! function_exists('vst_getCurrentUser'))
{
	function vst_getCurrentUser() {
	    $CI =& get_instance();
	    $user = $CI->session->userdata('vkt_currentUser');
		return $user;
	}
}
if ( ! function_exists('getRoleText'))
{
	function getRoleText($role)
	{
		$roleText="NA";
		switch($role)
		{
			case 1:
				$roleText="Admin";
				break;
			case 2:
				$roleText="Quản lý";
				break;
			case 3:
				$roleText="Tư vấn";
				break;
			case 4:
				$roleText="Mua hàng";
				break;
			case 5:
				$roleText="Kho TQ";
				break;
			case 5:
				$roleText="Kho VN";
				break;
			default:
				$roleText="NA";
		}
		return $roleText;
	}
}


if ( ! function_exists('getStatusOrder'))
{
	function getStatusOrder($status)
	{
		$statusText="<span class='chuaduyet'>Chưa duyệt</span>";
		switch($status)
		{
			case -1:
				$statusText="<span class='dahuy'>Đã hủy</span>";
				break;
			case 0:
				$statusText="<span class='chuaduyet'>Chưa duyệt</span>";
				break;
			case 1:
				$statusText="<span class='daduyet'>Đã duyệt</span>";
				break;
			case 2:
				$statusText="<span class='dathanhtoan'>Đã thanh toán - chờ mua hàng</span>";
				break;
			case 3:
				$statusText="<span class='damuahang'>Đã mua hàng</span>";
				break;
			case 4:
				$statusText="<span class='hangdave'>Hàng đã về - chờ giao hàng</span>";
				break;
			case 5:
				$statusText="<span class='daketthuc'>Đã kết thúc</span>";
				break;
			default:
				$statusText="<span class='chuaduyet'>Chưa duyệt</span>";
		}
		return $statusText;
	}
}

if ( ! function_exists('getStoreText'))
{
	function getStoreText($store)
	{
		if($store =='1'){
			return "<span class='bold black'>Kho SG</span>";
		}else if($store =="0"){
			return "<span class='bold green'>Kho HN</span>";
		}else{
			return "<span class='bold red'>N/A</span";
		}
	}
}

if ( ! function_exists('vst_currentDate'))
{
	function vst_currentDate($time=true) {
		if($time){
			$dateTime=date("d/m/Y H:i:s");
		}else{
			$dateTime=date("d/m/Y");
		}
		return $dateTime;
	}
}

if ( ! function_exists('starts_with'))
{
	function starts_with($haystack, $needle)
	{
		return substr($haystack, 0, strlen($needle))===$needle;
	}
}

if ( ! function_exists('vst_getIPAddress'))
{
	function vst_getIPAddress() {
		return $_SERVER['REMOTE_ADDR'];;
	}
}


if(!function_exists('message_flash')){
	function message_flash($message = '', $type = 'success'){
		$CI =& get_instance();
		$CI->session->set_flashdata('message_flashdata', array(
			'type' => $type,
			'message' => $message
		));
	}
}

if(!function_exists('vst_password')){
	function vst_password($msg){
		return md5($msg);
	}
}

if(!function_exists('vst_pagination')){
	function vst_Pagination($total){
        $config['per_page'] = 5;
        $config['page_query_string'] =true;
        $config['query_string_segment'] ="page";
		$config['base_url'] =vst_currentUrl();
		$config['total_rows']= $total;
		return $config;
	}
}

if(!function_exists('vst_currentUrl')){
	function vst_currentUrl($withoutPage=true)
	{
		$CI =& get_instance();
		$url = $CI->config->site_url($CI->uri->uri_string());
		$params=$CI->input->get();
		if(isset($params) and $params != '')
		{
			if(isset($params['page']) && $withoutPage)
			{	unset($params['page']);		}
		}
		else{
			$params= array(
				'filter_' => '',
				'page' => ''
			);
		}
		$http_query=http_build_query($params, '', "&");
		return $http_query ? $url.'?'.$http_query : $url;
	}
}

if(!function_exists('vst_filterData')){
	function vst_filterData($likeFields=array())
	{
		$CI =& get_instance();
		$params=$CI->input->get();
		unset($params['page']);
		$filterData=array();
		if($params){
			foreach($params as $key=>$value){
				if($value!=''){
					if(in_array($key,$likeFields))
					{
						$filterData[str_replace("filter_","",$key)]=array('value'=>trim($value),'condition'=>'like');
					}else{
						$filterData[str_replace("filter_","",$key)]=array('value'=>trim($value),'condition'=>'where');
					}
				}
			}
		}
		
		return $filterData;
		
	}
}

if(!function_exists('vst_getData')){
	function vst_postData()
	{
		$CI =& get_instance();
		$params=$CI->input->post();
		$filterData=array();
		if($params){
			foreach($params as $key=>$value){
				if( $key!='save' && $value!='' && $key!='password'){
					$filterData[$key]  = $value;
				}
				if( $key=='password' ){
					$filterData[$key]  = vst_password($value);
				}
			}
		}
		return $filterData;
	}
}

if(!function_exists('vst_buildFilter')){
	function vst_buildFilter($filter=array())
	{
		$CI =& get_instance();
		if($filter){
			foreach ($filter as $key => $value) {
                switch ($value['condition']) {
                   case 'like':
                        $query = $CI->db->like(array($key=>$value['value']));
                        break;
				   case 'or_like':
                        $query = $CI->db->or_like(array($key=>$value['value']));
                        break;
                   case 'where':
                       $query = $CI->db->where(array($key=>$value['value']));
                       break;
				   case 'or_where':
						$CI->db->or_where(array($key=>$value['value']));
						break;
                   default:
                       # code...
                     break;
               }
            }
		}
	}
}


if(!function_exists('vst_getController')){
	function vst_getController()
	{
		$CI =& get_instance();
		return $CI->router->class;
	}
}

if(!function_exists('vst_getMethod')){
	function vst_getMethod()
	{
		$CI =& get_instance();
		return $CI->router->method;
	}
}

if(!function_exists('vst_getBodyClass')){
	function vst_getBodyClass()
	{
		$CI =& get_instance();
		$class="page_".$CI->router->class;
		if($CI->router->method)
			$class .=" view_".$CI->router->method;
		return $class;
	}
}

if(!function_exists('vst_authAjaxPost')){
	function vst_authAjaxPost()
	{
	  $CI =& get_instance();
	  if($CI->input->post('postAjax') == null || !$CI->input->post('postAjax'))
	  {	
			
		  	$CI->load->helper('url');
			redirect(site_url('404'));
	  }
	}
}

if(!function_exists('vst_abc')){
	function vst_abc($param)
	{
	  $CI =& get_instance();
	  $data= "$thix->$CI->db->where($param);";
	  if($CI->session->userdata('access') == 'Customer')
		{	
			return $data;
		}
		
	}
}

/* $param= array('email'=> $email, 'subject'=> $subject, 'message'=> $message); */

if(!function_exists('vst_sendmail')){
	function vst_sendmail($param)
	{
		$CI =& get_instance();
		$config = Array(
		    'protocol' => 'smtp',
		    'smtp_host' => 'ssl://smtp.googlemail.com',
		    'smtp_port' => 465,
		    'smtp_user' => 'tajddawngtoanf@gmail.com', // change it to yours
		    'smtp_pass' => 'ABC1234567890' // change it to yours
		);

        //$CI->email->clear();
        $CI->load->library('email', $config);
        $CI->email->set_newline("\r\n");
        $CI->email->from($config['smtp_user'], 'Vultr Support'); // change it to yours
        $CI->email->to($param['email']);// change it to yours
        $CI->email->subject($param['subject']);
        $CI->email->message($param['message']);
		
        if($CI->email->send())
        {
			return true;
		}
		else
		{
			return show_error($CI->email->print_debugger());
		}
		
	}
}
		
		
		
		
		
		
		
		
		
		
		
