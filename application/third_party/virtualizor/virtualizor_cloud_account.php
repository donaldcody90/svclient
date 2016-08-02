<?php

// Disable warning messages - in PHP 5.4
//error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);

include_once('virtualizor_cloud_account_conf.php');
include_once('functions.php');
include_once(dirname(__FILE__).'/sdk/admin.php');

function virtualizor_cloud_account_ConfigOptions() {
	
	# Should return an array of the module options for each product - Minimum of 24
    $configarray = array(
	 "Type" => array( "Type" => "text", "Size" => "25", "Description" => "Enter virtualizations allowed : openvz, xen, xenhvm, kvm, xcp, xcphvm"),
	 "Number of VMs" => array( "Type" => "text", "Size" => "25", "Description" => "Zero or empty for unlimited"),
	 "Number of Users" => array( "Type" => "text", "Size" => "25", "Description" => "Zero or empty for unlimited"),
	 "Max Disk Space" => array( "Type" => "text", "Size" => "25", "Description" => "GB"),
	 "Max RAM" => array( "Type" => "text", "Size" => "25", "Description" => "MB"),
	 "Max Burst / Swap" => array( "Type" => "text", "Size" => "25", "Description" => "MB"), 
	 "Max Bandwidth" => array( "Type" => "text", "Size" => "25", "Description" => "GB (Zero or empty for unlimited)"),
	 "Default CPU Weight" => array( "Type" => "text", "Size" => "25", "Description" => ""),
	 "Max Cores" => array( "Type" => "text", "Size" => "25", "Description" => "Max Cores / VM"),
	 "Default CPU %" => array( "Type" => "text", "Size" => "25", "Description" => ""),
	 "Max IPv4" => array( "Type" => "text", "Size" => "25", "Description" => "Number of IPs"),
	 "Max IPv6" => array( "Type" => "text", "Size" => "25", "Description" => "Number of IPv6 allowed"),
	 "Regions Allowed" => array( "Type" => "text", "Size" => "25", "Description" => "Enter comma seperated names of <b>Reseller Region Name</b>"),
	 "Media Groups" => array( "Type" => "text", "Size" => "25", "Description" => "Enter comma seperated names of Media Groups. Leave empty for all Media allowance"),
	 "Max IPv6 Subnets" => array( "Type" => "text", "Size" => "25", "Description" => "Number of IPv6 Subnets allowed"),
	 "Total Cores Allowed" => array( "Type" => "text", "Size" => "25", "Description" => "Total Number of Cores Allowed"),
	);
	
	return $configarray;
}

function virtualizor_cloud_account_CreateAccount($params) {

	global $virtcloud_acc;

    # ** The variables listed below are passed into all module functions **
	
	$loglevel = (int) @$_REQUEST['loglevel'];
	
	if(!empty($virtcloud_acc['loglevel'])){
		$loglevel = $virtcloud_acc['loglevel'];
	}
	
    $serviceid = $params["serviceid"]; # Unique ID of the product/service in the WHMCS Database
    $pid = $params["pid"]; # Product/Service ID
    $producttype = $params["producttype"]; # Product Type: hostingaccount, reselleraccount, server or other
    $domain = $params["domain"];
	$username = $params["username"];
	$password = $params["password"];
    $clientsdetails = $params["clientsdetails"]; # Array of clients details - firstname, lastname, email, country, etc...
    $customfields = $params["customfields"]; # Array of custom field values for the product
    $configoptions = $params["configoptions"]; # Array of configurable option values for the product
	
	if(!empty($params["customfields"]['uid'])){
		return 'The User exists';
	}

    # Additional variables if the product/service is linked to a server
    $server = $params["server"]; # True if linked to a server
    $serverid = $params["serverid"];
    $serverip = $params["serverip"];
    $serverusername = $params["serverusername"];
    $serverpassword = $params["serverpassword"];
    $serveraccesshash = $params["serveraccesshash"];
    $serversecure = $params["serversecure"]; # If set, SSL Mode is enabled in the server config
	
	// Virts allowed
	$virts = cexplode(',', $params['configoption1']);
	foreach($virts as $k => $v){
		$post[$v] = 'on';
	}
	
	// User Details
	$post['newemail'] = $params['clientsdetails']['email'];
	$post['newpass'] = $params["password"];
	$post['priority'] = 2;
	
	// Number of VMs
	$post['num_vs'] = (empty($params['configoptions'][vc_fn('num_vs')]) ? $params['configoption2'] : $params['configoptions'][vc_fn('num_vs')]);
	
	// Number of Users
	$post['num_users'] = (empty($params['configoptions'][vc_fn('num_users')]) ? $params['configoption3'] : $params['configoptions'][vc_fn('num_users')]);
	
	// Max Disk Space
	$post['space'] = (empty($params['configoptions'][vc_fn('space')]) ? $params['configoption4'] : $params['configoptions'][vc_fn('space')]);
	
	// Max Ram
	$post['ram'] = (empty($params['configoptions'][vc_fn('ram')]) ? $params['configoption5'] : $params['configoptions'][vc_fn('ram')]);
	
	// Max Burst
	$post['burst'] = (empty($params['configoptions'][vc_fn('burst')]) ? $params['configoption6'] : $params['configoptions'][vc_fn('burst')]);
	
	// Max Bandwidth
	$post['bandwidth'] = (empty($params['configoptions'][vc_fn('bandwidth')]) ? $params['configoption7'] : $params['configoptions'][vc_fn('bandwidth')]);
	
	// Default CPU Weight
	$post['cpu'] = (empty($params['configoptions'][vc_fn('cpu')]) ? $params['configoption8'] : $params['configoptions'][vc_fn('cpu')]);
	
	// Max Cores
	$post['cores'] = (empty($params['configoptions'][vc_fn('cores')]) ? $params['configoption9'] : $params['configoptions'][vc_fn('cores')]);
	
	// Default CPU %
	$post['cpu_percent'] = (empty($params['configoptions'][vc_fn('cpu_percent')]) ? $params['configoption10'] : $params['configoptions'][vc_fn('cpu_percent')]);
	
	// Total Number of Cores
	$post['num_cores'] = (empty($params['configoptions'][vc_fn('num_cores')]) ? $params['configoption16'] : $params['configoptions'][vc_fn('num_cores')]);
	
	// Number of IPv4
	$post['num_ipv4'] = (empty($params['configoptions'][vc_fn('num_ipv4')]) ? $params['configoption11'] : $params['configoptions'][vc_fn('num_ipv4')]);
	
	// Number of IPv6
	$post['num_ipv6'] = (empty($params['configoptions'][vc_fn('num_ipv6')]) ? $params['configoption12'] : $params['configoptions'][vc_fn('num_ipv6')]);
	
	// Number of IPv6 Subnet
	$post['num_ipv6_subnet'] = (empty($params['configoptions'][vc_fn('num_ipv6_subnet')]) ? $params['configoption15'] : $params['configoptions'][vc_fn('num_ipv6_subnet')]);
	
	/////////////////
	// Get the Data
	/////////////////
	$data = VirtCloud_Account_Curl::call($params["serverip"], $params["serverusername"], $params["serverpassword"], 'index.php?act=adduser');
	
	if(empty($data)){
		return 'Could not load the server data.'.VirtCloud_Account_Curl::error($params["serverip"]);
	}
		
	if($loglevel > 2) logActivity('Virt Cloud Account Data Loaded : '.var_export($data, 1));
	
	// Server Groups
	$sgs = (empty($params['configoptions'][vc_fn('sgs')]) ? $params['configoption13'] : $params['configoptions'][vc_fn('sgs')]);
	$sgs = cexplode(',', $sgs);
	
	if($loglevel > 2) logActivity('Virt Cloud Account Data SGS : '.var_export($sgs, 1));
	
	// Find the server groups
	foreach($sgs as $k => $v){
		
		if(empty($v)){ // cexplode can return empty stuff
			continue;
		}
		
		$found = 0;
		
		foreach($data['servergroups'] as $sk => $sv){
			
			if($sv['sg_reseller_name'] == $v){
				
				$found = $sk;
				
			}
			
		}
		
		if(empty($found)){
			return 'Could not find the Region - '.$v.'. Please correct the <b>Product / Service</b> with the right region name.';
		}
		
		$post['sgs'][$found] = $found;
		
	}
	
	// Media Groups
	$mgs = (empty($params['configoptions'][vc_fn('mgs')]) ? $params['configoption14'] : $params['configoptions'][vc_fn('mgs')]);
	$mgs = cexplode(',', $mgs);
	
	if($loglevel > 2) logActivity('Virt Cloud Account Data MGS : '.var_export($mgs, 1));
	
	// Find the media groups
	foreach($mgs as $k => $v){
		
		if(empty($v)){ // cexplode can return empty stuff
			continue;
		}
		
		$found = 0;
		
		foreach($data['mgs'] as $sk => $sv){
			
			if($sv['mg_name'] == $v){
				
				$found = $sk;
				
			}
			
		}
		
		if(empty($found)){
			return 'Could not find the Media Group - '.$v.'. Please correct the <b>Product / Service</b> with the right Media Group name.';
		}
		
		$post['mgs'][$found] = $found;
		
	}
	
	$post['adduser'] = 1;
	
	if($loglevel > 0) logActivity('Virtualizor Cloud Account Params : '.var_export($post, 1));
	
	//return "Debug";
	
	$ret = VirtCloud_Account_Curl::call($params["serverip"], $params["serverusername"], $params["serverpassword"], 'index.php?act=adduser', $post);
		
	if($loglevel > 1) logActivity('Return Values : '.var_export($ret, 1));
	
	// Was the VPS Inserted
	if(!empty($ret['done'])){
		
		// vpsid of virtualizor
		$query = mysql_query("SELECT `id` FROM `tblcustomfields` WHERE `relid` = '$pid' AND `fieldname` = 'uid'");
		$res = mysql_fetch_array($query);
		
		mysql_query("UPDATE `tblcustomfieldsvalues` SET `value` = '".$ret['done']."' WHERE `relid` = '$serviceid' AND `fieldid` = '$res[id]'") or mysql_error();
		
		// Change the Username to the email
		mysql_query("UPDATE `tblhosting` SET `username` = '".$params['clientsdetails']['email']."' WHERE `id` = '$serviceid';");
		
		// Did it start ?
		if(!empty($ret['done'])){		
			return 'success';	
		}else{
			return 'Errors : '.implode('<br>', $ret['error']);
		}
		
	} else {
		return 'Errors : '.implode('<br>', $ret['error']);
	}
	
}


function virtualizor_cloud_account_TerminateAccount($params) {

	global $virtcloud_acc;

	$data = VirtCloud_Account_Curl::call($params["serverip"], $params["serverusername"], $params["serverpassword"], 'index.php?act=users&delete='.$params['customfields']['uid']);
			
	if(empty($data)){
		return 'Could not load the server data.'.VirtCloud_Account_Curl::error($params["serverip"]);
	}
		
	if($loglevel > 1) logActivity('Terminate Return Values : '.var_export($ret, 1));	
	
	// If the VPS has been deleted
    if(!empty($data['done'])){
	
		// vpsid of virtualizor
		$query = mysql_query("SELECT `id` FROM `tblcustomfields` WHERE `relid` = '".$params["pid"]."' AND `fieldname` = 'uid'");
		$res = mysql_fetch_array($query);
		mysql_query("UPDATE `tblcustomfieldsvalues` SET `value` = '' WHERE `relid` = '".$params["serviceid"]."' AND `fieldid` = '$res[id]'") or mysql_error();
		
		$result = "success";
	} else {
		$result = "There was some error deleting the account";
	}
	
	return $result;
}

function virtualizor_cloud_account_SuspendAccount($params) {
	return;
	$data = VirtCloud_Account_Curl::call($params["serverip"], $params["serverusername"], $params["serverpassword"], 'index.php?act=listvs&suspend='.$params['customfields']['vpsid']);
			
	if(empty($data)){
		return 'Could not load the server data.'.VirtCloud_Account_Curl::error($params["serverip"]);
	}

    if ($data['suspend']['done']) {
		$result = "success";
	} else {
		$result = "There was some error suspending the VPS";
	}
	
	return $result;
}

function virtualizor_cloud_account_UnsuspendAccount($params) {
	return;
	$data = VirtCloud_Account_Curl::call($params["serverip"], $params["serverusername"], $params["serverpassword"], 'index.php?act=listvs&unsuspend='.$params['customfields']['vpsid']);
			
	if(empty($data)){
		return 'Could not load the server data.'.VirtCloud_Account_Curl::error($params["serverip"]);
	}

    if ($data['unsuspend']['done']) {
		$result = "success";
	} else {
		$result = "There was some error unsuspending the VPS";
	}
	
	return $result;
}

/*function virtualizor_cloud_account_ChangePassword($params) {

	logActivity('ChangePassword : '.var_export($params, 1));
	
	$post['changepass'] = 1;
	$post['newpass'] = $params['password'];
	$post['conf'] = $params['password'];

	$data = VirtCloud_Account_Curl::call($params["serverip"], $params["serverusername"], $params["serverpassword"], 'index.php?act=userpassword', $post);
	
	logActivity('ChangePassword : '.var_export($data, 1));
			
	if(empty($data)){
		return 'Could not load the server data.'.VirtCloud_Account_Curl::error($params["serverip"]);
	}

    if ($data['done']) {
		$result = "success";
	} else {
		$result = "There was some error changing the password";
	}
	
	return $result;
}*/

// Updates the USER
function virtualizor_cloud_account_ChangePackage($params) {

	global $virtcloud_acc;

    # ** The variables listed below are passed into all module functions **
	
	$loglevel = (int) @$_REQUEST['loglevel'];
	
	if(!empty($virtcloud_acc['loglevel'])){
		$loglevel = $virtcloud_acc['loglevel'];
	}

	// Virts allowed
	$virts = cexplode(',', $params['configoption1']);
	foreach($virts as $k => $v){
		$post[$v] = 'on';
	}
	
	
	$post['newemail'] = $params['clientsdetails']['email'];
	$post['dnsplan_id'] = 0;
	$post['priority'] = 2;
	
	// Number of VMs
	$post['num_vs'] = (empty($params['configoptions'][vc_fn('num_vs')]) ? $params['configoption2'] : $params['configoptions'][vc_fn('num_vs')]);
	
	// Number of Users
	$post['num_users'] = (empty($params['configoptions'][vc_fn('num_users')]) ? $params['configoption3'] : $params['configoptions'][vc_fn('num_users')]);
	
	// Max Disk Space
	$post['space'] = (empty($params['configoptions'][vc_fn('space')]) ? $params['configoption4'] : $params['configoptions'][vc_fn('space')]);
	
	// Max Ram
	$post['ram'] = (empty($params['configoptions'][vc_fn('ram')]) ? $params['configoption5'] : $params['configoptions'][vc_fn('ram')]);
	
	// Max Burst
	$post['burst'] = (empty($params['configoptions'][vc_fn('burst')]) ? $params['configoption6'] : $params['configoptions'][vc_fn('burst')]);
	
	// Max Bandwidth
	$post['bandwidth'] = (empty($params['configoptions'][vc_fn('bandwidth')]) ? $params['configoption7'] : $params['configoptions'][vc_fn('bandwidth')]);
	
	// Default CPU Weight
	$post['cpu'] = (empty($params['configoptions'][vc_fn('cpu')]) ? $params['configoption8'] : $params['configoptions'][vc_fn('cpu')]);
	
	// Max Cores
	$post['cores'] = (empty($params['configoptions'][vc_fn('cores')]) ? $params['configoption9'] : $params['configoptions'][vc_fn('cores')]);
	
	// Default CPU %
	$post['cpu_percent'] = (empty($params['configoptions'][vc_fn('cpu_percent')]) ? $params['configoption10'] : $params['configoptions'][vc_fn('cpu_percent')]);
	
	// Total Number of Cores
	$post['num_cores'] = (empty($params['configoptions'][vc_fn('num_cores')]) ? $params['configoption16'] : $params['configoptions'][vc_fn('num_cores')]);
	
	// Number of IPv4
	$post['num_ipv4'] = (empty($params['configoptions'][vc_fn('num_ipv4')]) ? $params['configoption11'] : $params['configoptions'][vc_fn('num_ipv4')]);
	
	// Number of IPv6
	$post['num_ipv6'] = (empty($params['configoptions'][vc_fn('num_ipv6')]) ? $params['configoption12'] : $params['configoptions'][vc_fn('num_ipv6')]);
	
	// Number of IPv6 Subnet
	$post['num_ipv6_subnet'] = (empty($params['configoptions'][vc_fn('num_ipv6_subnet')]) ? $params['configoption15'] : $params['configoptions'][vc_fn('num_ipv6_subnet')]);
	
	/////////////////
	// Get the Data
	/////////////////

	$data = VirtCloud_Account_Curl::call($params["serverip"], $params["serverusername"], $params["serverpassword"], 'index.php?act=edituser&uid='.$params['customfields']['uid']);
			
	if(empty($data)){
		return 'Could not load the server data.'.VirtCloud_Account_Curl::error($params["serverip"]);
	}
		
	if($loglevel > 1) logActivity('Virt Cloud Account ChanePackage Orig Values : '.var_export($data, 1));
	
	// Server Groups
	$sgs = (empty($params['configoptions'][vc_fn('sgs')]) ? $params['configoption13'] : $params['configoptions'][vc_fn('sgs')]);
	$sgs = cexplode(',', $sgs);
	
	if($loglevel > 2) logActivity('Virt Cloud Account Data ChangePackage SGS : '.var_export($sgs, 1));
	
	// Find the server groups
	foreach($sgs as $k => $v){
		
		if(empty($v)){ // cexplode can return empty stuff
			continue;
		}
		
		$found = 0;
		
		foreach($data['servergroups'] as $sk => $sv){
			
			if($sv['sg_reseller_name'] == $v){
				
				$found = $sk;
				
			}
			
		}
		
		if(empty($found)){
			return 'Could not find the Region - '.$v.'. Please correct the <b>Product / Service</b> with the right region name.';
		}
		
		$post['sgs'][$sk] = $sk;
		
	}
	
	// Media Groups
	$mgs = (empty($params['configoptions'][vc_fn('mgs')]) ? $params['configoption14'] : $params['configoptions'][vc_fn('mgs')]);
	$mgs = cexplode(',', $mgs);
	
	if($loglevel > 2) logActivity('Virt Cloud Account ChangePackage Data MGS : '.var_export($mgs, 1));
	
	// Find the media groups
	foreach($mgs as $k => $v){
		
		if(empty($v)){ // cexplode can return empty stuff
			continue;
		}
		
		$found = 0;
		
		foreach($data['mgs'] as $sk => $sv){
			
			if($sv['mg_name'] == $v){
				
				$found = $sk;
				
			}
			
		}
		
		if(empty($found)){
			return 'Could not find the Media Group - '.$v.'. Please correct the <b>Product / Service</b> with the right Media Group name.';
		}
		
		$post['mgs'][$sk] = $sk;
		
	}
	
	$post['edituser'] = 1;
	
	if($loglevel > 0) logActivity('Virtualizor Cloud Account ChangePackage Params : '.var_export($post, 1));
	
	//return "Debug";
	
	$ret = VirtCloud_Account_Curl::call($params["serverip"], $params["serverusername"], $params["serverpassword"], 'index.php?act=edituser&uid='.$params['customfields']['uid'], $post);
		
	if($loglevel > 1) logActivity('Virtualizor Cloud Account ChangePackage Return Values : '.var_export($ret, 1));
	
	// Was the Action Successful
	if(!empty($ret['done'])){
		$result = "success";
	} else {
		return 'Errors : '.implode('<br>', $ret['error']);
	}
	
	return $result;
}

function virtualizor_cloud_account_AdminLink($params) {
	$code = '<a href="https://'.$params["serverip"].':4085/index.php?act=login" target="_blank">Virtualizor Panel</a>';
	return $code;
}

function virtualizor_cloud_account_LoginLink($params) {
	echo "<a href=\"https://".$params["serverip"].":4083/\" target=\"_blank\" style=\"color:#cc0000\">Login to Virtualizor</a>";
}


function virtualizor_cloud_account_ClientArea($params) {
	return "<a href=\"https://".$params["serverip"].":4083/\" target=\"_blank\" style=\"color:#cc0000\">Login to Cloud Account</a>";
}

/*function virtualizor_cloud_account_AdminCustomButtonArray() {
	# This function can define additional functions your module supports, the example here is a reboot button and then the reboot function is defined below
    $buttonarray = array(
	 "Start VPS" => "admin_start",
	 "Reboot VPS" => "admin_reboot",
 	 "Stop VPS"=> "admin_stop",
	 "Poweroff VPS"=> "admin_poweroff"
	);
	return $buttonarray;
}*/


class VirtCloud_Account_Curl {
	
	function error($ip = ''){
		
		$err = '';
		
		if(!empty($GLOBALS['virt_curl_err'])){
			$err .= ' Curl Error: '.$GLOBALS['virt_curl_err'];
		}
		
		if(!empty($ip)){
			$err .= ' (Server IP : '.$ip.')';
		}
		
		return $err;
	}
	
	function call($ip, $userkey, $pass, $path, $post = array(), $cookies = array()){

		$v = new Virtualizor_Admin_API($ip, $userkey, $pass);
		
		return $v->call($path, array(), $post, $cookies);
		
	}

} // class VirtCloud_Account_Curl ends

?>