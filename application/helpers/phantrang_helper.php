<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*if (!function_exists('pagination_helper'))
{
	function pagination($model, $base_url)
	{
		$config['base_url']= $base_url;
		$config['first_page']= 'First';
		$config['last_page']= 'Last';
		$config['num_links']= 2;
		$config['per_page']= 5;
		$config['total_rows']= count($model);
		
		return $config;
	}
}*/

if(!function_exists('vst_pagination')){
	function vst_Pagination(){
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '&laquo; ';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = ' &raquo;';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'Trang sau &raquo;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo; Trang trước';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['per_page'] =20;
		$config['page_query_string'] =true;
		$config['query_string_segment'] ="page";
		$config['base_url'] =vst_currentUrl();
		return $config;
	}
}
if(!function_exists('vst_currentUrl')){
	function vst_currentUrl($withoutPage=true)
	{
		$CI =& get_instance();
		$url = $CI->config->site_url($CI->uri->uri_string());
		$params=$CI->input->get();
		if(!$params)
			$params=array();
		
		if(isset($params['page']) && $withoutPage)
			unset($params['page']);
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



if(!function_exists('vst_buildFilter')){
	function vst_buildFilter($filter)
	{
		$CI =& get_instance();
		if($filter){
			foreach ($filter as $key => $value) {
                switch ($value['condition']) {
                   case 'like':
                        $query = $CI->db->like(array($key=>$value['value']));
                        break;
                   case 'where':
                       $query = $CI->db->where(array($key=>$value['value']));
                       break;
                   default:
                       # code...
                     break;
               }
            }
		}
	}
}