<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('initServerAPI'))
{
	function initServerAPI($ip, $key, $pass) 
	{
		require_once APPPATH.'third_party/virtualizor/sdk/admin.php';
		
		$admin = new Virtualizor_Admin_API($ip, $key, $pass);
		return $admin;	    
	}
}

if ( ! function_exists('sv_delete'))
{
	function sv_delete($ip, $key, $pass) 
	{
		require_once APPPATH.'third_party/virtualizor/sdk/admin.php';
		
		$admin = new Virtualizor_Admin_API($ip, $key, $pass);
		$output = $admin->delete_vs(1011);
			//$output = $admin->servers();
			
		if($output == 1){
			return true;
		}
	    
	}
}

if ( ! function_exists('sv_status'))
{
	function sv_status($ip, $key, $pass)
	{
		require_once APPPATH.'third_party/virtualizor/sdk/admin.php';
		
		$admin = new Virtualizor_Admin_API($ip, $key, $pass);
		$output = $admin->status(1002);
		
		return $output;
	}
}


if ( ! function_exists('sv_restart'))
{
	function sv_restart($ip, $key, $pass)
	{
		require_once APPPATH.'third_party/virtualizor/sdk/admin.php';
		
		$admin = new Virtualizor_Admin_API($ip, $key, $pass);
		$output = $admin->restart(1991);
		
		return $output;
	}
}


if ( ! function_exists('sv_stop'))
{
	function sv_stop($ip, $key, $pass)
	{
		require_once APPPATH.'third_party/virtualizor/sdk/admin.php';
		
		$admin = new Virtualizor_Admin_API($ip, $key, $pass);
		$output = $admin->stop(1991);
		
		return $output;
	}
}
	
if ( ! function_exists('sv_start'))
{
	function sv_start($ip, $key, $pass)
	{
		require_once APPPATH.'third_party/virtualizor/sdk/admin.php';
		
		$admin = new Virtualizor_Admin_API($ip, $key, $pass);
		$output = $admin->start(1002);
		
		return $output;
	}
}

if(!function_exists('sv_getlist'))
{
function sv_getlist($ip, $key, $pass)
	{
		require_once APPPATH.'third_party/virtualizor/sdk/admin.php';

		$admin = new Virtualizor_Admin_API($ip, $key, $pass);
		$output = $admin->listvs(1, 20);
		
		return $output;
	}
}
	
	
	
	
	
	
	