<?php

// Any Admin User in WHMCS
$admin_user = "admin";

// The Hask Key in WHMCS
$hash_key = "";

// The Virtualizor Master IP
$connection_ip = "";


///////////////////////////////////
// DO NOT EDIT BEYOND THIS LINE 
///////////////////////////////////

if(empty($hash_key) || empty($connection_ip)){
	virt_callback_die('<error>ERROR: Callback NOT configured</error>');
}

function virt_callback_die($msg){
	die($msg);
}

// Include the WHMCS files
if(file_exists('../../../init.php')){
	require("../../../init.php");
}else{
	require("../../../dbconnect.php");
	require("../../../includes/functions.php");
}

// Get the variables
$hash = $_POST['hash'];
$acts = $_POST['act'];
$vpsid = (int) $_POST['vpsid'];
$extra = $_POST['data'];

if(!empty($extra)){
	$extra_var = unserialize(base64_decode($extra));
}

// Is the request from a valid server ?
if($_SERVER['REMOTE_ADDR'] != $connection_ip){
	virt_callback_die('<error>ERROR: Connection from an INVALID IP !</error>');
}

// Does the KEY MATCH ?
if($hash != $hash_key){
	virt_callback_die('<error>ERROR: Your security HASH does not match</error>');
}

// Is it a valid
if(empty($vpsid) && $vpsid < 1){
	virt_callback_die('<error>ERROR: Invalid VPSID</error>');
}

//=====================
// Get the VPS details
//=====================
$query = mysql_query("SELECT f.id AS field_id, f.type AS field_type, f.fieldname AS field_name, v.fieldid AS value_id, v.value AS value_value, v.relid AS value_productid 
			FROM tblcustomfields f
			JOIN tblcustomfieldsvalues v ON v.fieldid=f.id 
			WHERE f.type = 'product' 
			AND f.fieldname = 'vpsid' 
			AND v.value = '$vpsid'");

// We didnt find it !	
if(mysql_num_rows($query) < 1){
	virt_callback_die('<error>ERROR: VPS does not exist in WHMCS Database</error>');
}

// Get the row
$row = mysql_fetch_array($query);
$hosting_ID = $row['value_productid'];

foreach($acts as $k => $act){
	
	// Do as necessary
	switch ($act){

		//===============
		// VPS Suspended
		//===============
		case 'suspend':

			$values['messagename'] = "Service Suspension Notification";
			$values['id'] = $hosting_ID;

			mysql_query("UPDATE tblhosting SET domainstatus='Suspended' WHERE id='" . $hosting_ID . "'");

			if($extra_var['suspendreason']){
				mysql_real_escape_string($extra_var['suspendreason']);
			}else{
				$extra_var['suspendreason'] = "";
			}

			mysql_query("UPDATE tblhosting SET suspendreason='" . $extra_var['suspendreason'] . "' WHERE id='" . $hosting_ID . "'");

			$output = localAPI('sendemail', $values, $admin_user);

			if($output['result'] == "success"){
				echo "<success>1</success>";
			}else{
				echo "<error>Error: " . $output['message']."</error>";
			}

			break;


		//==================
		// VPS Unsuspended
		//==================
		case 'unsuspend':

			mysql_query("UPDATE tblhosting SET domainstatus='Active' WHERE id='" . $hosting_ID . "'");
			mysql_query("UPDATE tblhosting SET suspendreason='' WHERE id='" . $hosting_ID . "'");

			echo "<success>1</success>";

			break;
			
		//==================
		// VPS Deleted
		//==================
		case 'terminate':

			mysql_query("UPDATE tblhosting SET domainstatus='Terminated' WHERE id='" . $hosting_ID . "'");

			echo "<success>1</success>";

			break;
			
		//==================
		// HostName Changed
		//==================
		case 'changehostname':

			mysql_real_escape_string($extra_var['newhostname']);
			mysql_query("UPDATE tblhosting SET domain='" . $extra_var['newhostname'] . "' WHERE id='" . $hosting_ID . "'");

			echo "<success>1</success>";

			break;

			
		//=============
		// IPs Changed
		//=============
		case 'changeip':

			$ip_list = array();

			if($extra_var['ipv4']){
				mysql_real_escape_string($extra_var['ipv4']);
				$ipv4_list = explode(",", $extra_var['ipv4']);
				foreach ($ipv4_list as $ipv4){
					$ip_list[] = $ipv4;
				}
			}

			if($extra_var['ipv6']){
				mysql_real_escape_string($extra_var['ipv6']);
				$ipv6_list = explode(",", $extra_var['ipv6']);
				foreach ($ipv6_list as $ipv6){
					$ip_list[] = $ipv6;
				}
			}

			if(count($ip_list) > 1){
				$tmplist = $ip_list;
				unset($tmplist[0]);
				$ips = implode("\n", $tmplist);
			}else{
				$ips = "";
			}
			
			mysql_query("UPDATE tblhosting SET dedicatedip='" . $ip_list[0] . "' WHERE id='" . $hosting_ID . "'");
			
			mysql_query("UPDATE tblhosting SET assignedips='" . $ips . "' WHERE id='" . $hosting_ID . "'");

			echo "<success>1</success>";

			break;

			
		//==================
		// Default Scenario
		//==================
		default:
			echo "1";
		
	}// End of Switch
}

?>
