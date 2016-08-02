<?php

// Last Updated : 04/05/2016
// Version : 2.0.5

// Disable warning messages - in PHP 5.4
//error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);

include_once('virtualizor_conf.php');
include_once(dirname(__FILE__).'/functions.php');

if(!empty($_GET['virt_net_speed'])){
	ob_start();
}

function virtualizor_ConfigOptions() {
	
	global $virtualizor_conf, $whmcsmysql;
	
	// Get the Servers
	$res = mysql_query("SELECT * FROM tblservers WHERE `type` = 'virtualizor'");
					
	if(mysql_num_rows($res) < 1){
		echo '<font color="#FF0000">The virtualizor servers could not be found. Please add the Virtualizor Server and Server group to proceed</font>';
		return;
	}
	$server_list = array();
	for($i = 0; $row = mysql_fetch_assoc($res); $i++){
		$server_list[$row['id']] = $row['id'].' - '.trim($row['name']);
		$server_data[$row['id']] = $row;
	}
	
	# Should return an array of the module options for each product - Minimum of 24
    $config_array = array(
	 "Type" => array( "Type" => "dropdown", "Options" => "OpenVZ,Xen PV,Xen HVM,KVM,XCP HVM,XCP PV"),
	 "DiskSpace" => array( "Type" => "text", "Size" => "25", "Description" => "GB"),
	 "Inodes" => array( "Type" => "text", "Size" => "25", "Description" => " (OpenVZ)"),
	 "Guaranteed RAM" => array( "Type" => "text", "Size" => "25", "Description" => "MB"),
	 "Burstable RAM" => array( "Type" => "text", "Size" => "25", "Description" => "MB (OpenVZ)"), 
	 "SWAP RAM" => array( "Type" => "text", "Size" => "25", "Description" => "MB (Xen, XCP and KVM)"), 
	 "Bandwidth" => array( "Type" => "text", "Size" => "25", "Description" => "GB (Zero or empty for unlimited)"),
	 "CPU Units" => array ( "Type" => "text", "Size" => "25", "Description" => "Units"), 
	 "CPU Cores" => array( "Type" => "text", "Size" => "25", "Description" => ""),
	 "CPU%" => array( "Type" => "text", "Size" => "25", "Description" => ""),
	 "I/O Priority" => array( "Type" => "dropdown", "Options" => "0,1,2,3,4,5,6,7", "Description" => "(OpenVZ)"),
	 "VNC" => array( "Type" => "yesno", "Description" => "Enable VNC (Xen, XCP and KVM)" ),
	 "IPs" => array( "Type" => "text", "Size" => "25", "Description" => "Number of IPs"),
	 "Network Speed" => array( "Type" => "text", "Size" => "25", "Description" => "KB/s (Zero or empty for unlimited)"),
	 "Server" => array( "Type" => "text", "Size" => "25", "Description" => "Slave Servers name if any"),
	 "Server Group" => array( "Type" => "text", "Size" => "25", "Description" => "To choose a server"),
	 "IPv6" => array( "Type" => "text", "Size" => "25", "Description" => "Number of IPv6 Address"),
	 "IPv6 Subnets" => array( "Type" => "text", "Size" => "25", "Description" => "Number of IPv6 Subnets"),
	 "Internal IP Address" => array( "Type" => "text", "Size" => "25", "Description" => "Number of Internal IP Address"),
	);
	
	// Get the product ID
	$pid = (int) $_GET['id'];
	
	// First get the configoption1 to check if the user is on OLD method or New method.
	$res = mysql_query("SELECT * FROM tblproducts WHERE id = $pid", $whmcsmysql);
	
	$row = mysql_fetch_assoc($res);
	//rprint($row);
	$configarray = array(
		'Virtualizor Servers' => array("Type" => "dropdown", "Options" => implode(',', array_values($server_list))),
		'Type' => array("Type" => "dropdown", "Options" => 'OpenVZ,Xen PV,Xen HVM,KVM,XCP HVM,XCP PV'),
		'Select Plan' => array("Type" => "dropdown", "Options" => ''),
	);
	
	// If this is filled up then user is using the OLD method
	if((!empty($row['configoption1']) && in_array($row['configoption1'], array('OpenVZ', 'Xen PV', 'Xen HVM', 'KVM', 'XCP HVM', 'XCP PV'))) || !empty($virtualizor_conf['no_virt_plans'])){
		
		//array_values($server_list)
		$tmp_type = array('OpenVZ', 'Xen PV', 'Xen HVM', 'KVM', 'XCP HVM', 'XCP PV');
		array_push($tmp_type, implode(',', array_values($server_list)));
		
		$config_array['Type']['Options'] = implode(',', $tmp_type);
		$configarray = $config_array;
	
	// If we get the Virtualizor server in configoption1, we will make an API call and load other fields
	}elseif(!empty($row['configoption1']) && in_array($row['configoption1'], array_values($server_list))){
		
		// Get the server ID
		$ser_id = array_search($row['configoption1'], $server_list);
		$ser_data = $server_data[$ser_id];
		
		//$configarray['Virtualizor Servers'] = array("Type" => "dropdown", "Options" => implode(',', array_values($server_list)));
		
		// Get the data from virtualizor
		$data = Virtualizor_Curl::make_api_call($ser_data["ipaddress"], get_server_pass_from_whmcs($ser_data["password"]), 'index.php?act=addvs');

		//rprint($data);
		//rprint($row);
		
		if(empty($data)){
			//echo '<font color="red">Could not load the server data.'.Virtualizor_Curl::error($ser_data["ipaddress"]).'</font>';
			return $configarray;
		}
		
		$virttype = (preg_match('/xen/is', $data['resources']['virt']) ? 'xen' : (preg_match('/xcp/is', $data['resources']['virt']) ? 'xcp' : strtolower($data['resources']['virt'])));
		
		$hvm = (preg_match('/hvm/is', $row['configoption2']) ? 1 : 0);
		
		// Build the options list to show Plans
		foreach($data['plans'] as $k => $v){
			$tmp_plans[$v['plid']] = $v['plid'].' - '.$v['plan_name'];
		}
		
		//rprint($data['oses']);
		if(!empty($row['configoption2']) && in_array($row['configoption2'], array('OpenVZ', 'Xen PV', 'Xen HVM', 'KVM', 'XCP HVM', 'XCP PV'))){
			
			// Build the options list to show OS
			foreach($data['oses'] as $ok => $ov){

				// If we do not get the virttype Which
				if(!preg_match('/'.$virttype.'/is', $ov['type'])){
					continue;
				}
				
				// Xen/XCP Stuff!
				if($virttype == 'xen' || $virttype == 'xcp'){
				
					// Xen/XCP HVM templates
					if(!empty($hvm) && empty($ov['hvm'])){
						continue;
						
					// Xen/XCP PV templates
					}elseif(empty($hvm) && !empty($ov['hvm'])){
						continue;
					}
				}
				
				$tmp_oses[$ok] = $ok.' - '.$ov['name'];
			}
		}
		//rprint($tmp_oses);
		
		// Build the default node / group field
		$tmp_default_node_grp['Auto Select Server'] = 'Auto Select Server';
		
		foreach ($data['servergroups'] as $k => $v){
			
			$tmp_default_node_grp[$k] = $k.' - [G] '.$v['sg_name'];
			
			foreach ($data['servers'] as $m => $n){
				if($n['sgid'] == $k){
					$tmp_default_node_grp[$n['server_name']] = $m." - ".$n['server_name'];
				}
			}
		}
		
		$configarray['Select Plan'] = array("Type" => "dropdown", "Options" => implode(',', $tmp_plans));
		$configarray['Default Node/ Group'] = array("Type" => "dropdown", "Options" => implode(',', array_values($tmp_default_node_grp)), "Description" => '[G] = Group Name');
		//$configarray['Operating System'] = array("Type" => "dropdown", "Options" => ' -- ,'.implode(',', $tmp_oses));
		
	}
	
	return $configarray;
}

function virtualizor_CreateAccount($params) {

	global $virtualizor_conf, $whmcsmysql;

    # ** The variables listed below are passed into all module functions **
	
	$loglevel = (int) @$_REQUEST['loglevel'];
	
	if(!empty($virtualizor_conf['loglevel'])){
		$loglevel = $virtualizor_conf['loglevel'];
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
	
	if(!empty($params["customfields"]['vpsid'])){
		return 'The VPS exists';
	}
	
	// New Module detection
	// If it is a new module then it will not have KVM or OPENVZ....
	if(!in_array($params['configoption1'], array('OpenVZ', 'Xen PV', 'Xen HVM', 'KVM', 'XCP HVM', 'XCP PV'))){
		
		$server_group = '';
		$slave_server = '';
		
		// Is it a Server group ?
		if(preg_match('/\[G\]/s', $params['configoption4'])){
			//$server_group = str_replace('[G] ', '', $params['configoption4']);
			//$server_group = trim($server_group);
			$tmp_sg = array();
			$tmp_sg = explode('- [', $params['configoption4']);
			$server_group = trim($tmp_sg[0]);
		}
		
		// If we do not get server group we will search it for slave server
		if($server_group == ''){
			// Is user wants auto selection from server?
			if($params['configoption4'] == 'Auto Select Server'){
				
				$slave_server = 'auto';
				
			// Or is it a particular Slave server ?
			}else{
				
				$tmp_ss = array();
				$tmp_ss = explode("-", (string)$params['configoption4']);
				$slave_server = trim($tmp_ss[0]);
			}
		}
		
		$post['server_group'] = $server_group;
		$post['slave_server'] = $slave_server;
		
		// Now get the plan ID to post
		$tmp_plid = explode('-', $params['configoption3']);
		$post['plid'] = trim($tmp_plid[0]);
		$virttype = (preg_match('/xen/is', $params['configoption2']) ? 'xen' : (preg_match('/xcp/is', $params['configoption2']) ? 'xcp' : strtolower($params['configoption2'])));
		
		//logActivity('Params : '.var_export($params, 1));
		
		if(empty($virtualizor_conf['vps_control']['custom_hname'])){
			$post['hostname'] = $params['domain'];
		}else{
			
			// Select the Order ID
			$res = mysql_query("SELECT * FROM `tblhosting` WHERE `id` = '".$params['serviceid']."';", $whmcsmysql);
			
			$hosting_details = mysql_fetch_assoc($res);
			
			$post['hostname'] = str_replace('{ID}', $hosting_details['orderid'], $virtualizor_conf['vps_control']['custom_hname']);
			if(preg_match('/(\{RAND(\d{1,3})\})/is', $post['hostname'], $matches)){
				$post['hostname'] = str_replace($matches[1], generateRandStr($matches[2]), $post['hostname']);
			}
			
			// Change the Hostname to the email
			mysql_query("UPDATE `tblhosting` SET `domain` = '".$post['hostname']."' WHERE `id` = '".$params['serviceid']."';", $whmcsmysql);
			
		}
		
		$post['rootpass'] = $params['password'];
		
		// Pass the user details 
		$post['user_email'] = $params["clientsdetails"]['email'];
		$post['user_pass'] = $params["password"];
		
		$post['fname'] = $params["clientsdetails"]['firstname'];
		$post['lname'] = $params["clientsdetails"]['lastname'];
		
		if($loglevel > 0) logActivity('params : '.var_export($params, 1));
		
		// Set the OS
		// Get the OS from the fields set
		$OS = strtolower(trim($params['configoptions'][v_fn('OS')]));
		if(empty($OS)){
			$OS = strtolower(trim($params["customfields"]['OS']));
		}
		
		if($OS != 'none'){
			$post['os_name'] = $OS;
		}
		
		if(!empty($params["customfields"]['iso']) && strtolower($params["customfields"]['iso']) != 'none'){
			$post['iso'] = $params["customfields"]['iso'];
		}
		
		if(!empty($params['configoptions'][v_fn('ips')])){
			$post['num_ips'] = $params['configoptions'][v_fn('ips')];
		}
		
		if(!empty($params['configoptions'][v_fn('ips_int')])){
			$post['num_ips_int'] = $params['configoptions'][v_fn('ips_int')];
		}
		
		if(!empty($params['configoptions'][v_fn('ips6')])){
			$post['num_ips6'] = $params['configoptions'][v_fn('ips6')];
		}
		
		if(!empty($params['configoptions'][v_fn('ips6_subnet')])){
			$post['num_ips6_subnet'] = $params['configoptions'][v_fn('ips6_subnet')];
		}
        
        	if(!empty($params['configoptions']['ippoolid'])){
			$post['ippoolid'] = $params['configoptions']['ippoolid'];
		}
		
		if(!empty($params['configoptions'][v_fn('space')])){
			$post['space'] = $params['configoptions'][v_fn('space')];
		}
		
		if(!empty($params['configoptions'][v_fn('ram')])){
			$post['ram'] = $params['configoptions'][v_fn('ram')];
		}
		
		if(!empty($params['configoptions'][v_fn('bandwidth')])){
			$post['bandwidth'] = $params['configoptions'][v_fn('bandwidth')];
		}
		
		if(!empty($params['configoptions'][v_fn('cores')])){
			$post['cores'] = $params['configoptions'][v_fn('cores')];
		}
		
		if(!empty($params['configoptions'][v_fn('network_speed')])){
			$post['network_speed'] = $params['configoptions'][v_fn('network_speed')];
		}
		
		if(!empty($params['configoptions'][v_fn('OS')])){
			$post['OS'] = $params['configoptions'][v_fn('OS')];
		}
		
		if(!empty($params['configoptions'][v_fn('ctrlpanel')])){
			$post['ctrlpanel'] = $params['configoptions'][v_fn('ctrlpanel')];
		}
		
		if(!empty($params['configoptions'][v_fn('slave_server')])){
			$post['slave_server'] = $params['configoptions'][v_fn('slave_server')];
		}
		
		if(!empty($params['configoptions'][v_fn('server_group')])){
			$post['server_group'] = $params['configoptions'][v_fn('server_group')];
		}
		
		// Are there any configurable options
		if(!empty($params['configoptions'])){
			foreach($params['configoptions'] as $k => $v){
				if(!isset($post[$k])){
					$post[$k] = $v;
				}
			}
		}
		
		// Any custom code ?
		if(file_exists(dirname(__FILE__).'/custom.php')){
			include_once(dirname(__FILE__).'/custom.php');
			
			if(!empty($custom_error)){
				return $custom_error;
			}
			
		}
		
		// No emails
		if(!empty($params["customfields"]['noemail'])){
			$post['noemail'] = 1;
		}
		
		$post['node_select'] = 1;
		$post['addvps'] = 1;
		
		if($loglevel > 0) logActivity('POST : '.var_export($post, 1));
		
		$ret = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=addvs&virt='.$virttype, array(), $post, array());
		
		//logActivity('data to be posted: '.var_export($post, 1));
		
		if(empty($ret)){
			return 'Could not load the slave server data';
		}
		
		if(!empty($ret['error'])){
			return implode('<br>*', array_values($ret['error']));
		}
		
		logActivity('New module Return data after post : '.var_export($ret['newvs'], 1));
		
		// Fill the variables as per the OLD module as it will be inserted to WHMCS. Like ips, ips6, etc..
		if(!empty($ret['newvs']['ips'])){
			$_ips = $ret['newvs']['ips'];
		}
		
		if(!empty($ret['newvs']['ipv6'])){
			$_ips6 = $ret['newvs']['ipv6'];
		}
		
		if(!empty($ret['newvs']['ipv6_subnet'])){
			$_ips6_subnet = $ret['newvs']['ipv6_subnet'];
		}
		
		$ctrlpanel = (empty($params['configoptions'][v_fn('ctrlpanel')]) ? -1 : strtolower(trim($params['configoptions'][v_fn('ctrlpanel')])));
		
		// Setup cPanel licenses if cPanel configurable option is set
		if($ctrlpanel != -1 && $ctrlpanel != 'none'){
		
			if($ctrlpanel == 'cpanel' && !empty($virtualizor_conf['cp']['buy_cpanel_login']) && !empty($virtualizor_conf['cp']['buy_cpanel_apikey'])){
				logActivity("CPANEL : cPanel issued for ip $_ips[0] of ordertype $cpanel");
				
				$url = 'https://www.buycpanel.com/api/order.php?';
				$login = 'login='.$virtualizor_conf['cp']['buy_cpanel_login'].'&';
				$key = 'key='.$virtualizor_conf['cp']['buy_cpanel_apikey'].'&';
				$domain = 'domain='.$params['domain'].'&';
				$serverip = 'serverip='.$_ips[0].'&';
				$ordertype = 'ordertype=10';
				
				$url .= $login.$key.$domain.$serverip.$ordertype;
				
				$ret_ctrlpanel = file_get_contents($url);
				
				$ret_ctrlpanel = json_decode($ret_ctrlpanel);
				
				if($ret_ctrlpanel->success == 0){
					return 'Errors : cPanel Licensing : '.$ret_ctrlpanel->faultstring;
				}
			}
		}
		
	// Old Module compatibility	
	}else{
	
		# Additional variables if the product/service is linked to a server
		$server = $params["server"]; # True if linked to a server
		$serverid = $params["serverid"];
		$serverip = $params["serverip"];
		$serverusername = $params["serverusername"];
		$serverpassword = $params["serverpassword"];
		$serveraccesshash = $params["serveraccesshash"];
		$serversecure = $params["serversecure"]; # If set, SSL Mode is enabled in the server config
		
		$virttype = (preg_match('/xen/is', $params['configoption1']) ? 'xen' : (preg_match('/xcp/is', $params['configoption1']) ? 'xcp' : strtolower($params['configoption1'])));
		$hvm = (preg_match('/hvm/is', $params['configoption1']) ? 1 : 0);
		$numips = (empty($params['configoptions'][v_fn('ips')]) || $params['configoptions'][v_fn('ips')] == 0 ? $params['configoption13'] : $params['configoptions'][v_fn('ips')]);
		$numips_int = (empty($params['configoptions'][v_fn('ips_int')]) || $params['configoptions'][v_fn('ips_int')] == 0 ? $params['configoption19'] : $params['configoptions'][v_fn('ips_int')]);
		$numips6 = (empty($params['configoptions'][v_fn('ips6')]) || $params['configoptions'][v_fn('ips6')] == 0 ? $params['configoption17'] : $params['configoptions'][v_fn('ips6')]);
		$numips6_subnet = (empty($params['configoptions'][v_fn('ips6_subnet')]) || $params['configoptions'][v_fn('ips6_subnet')] == 0 ? $params['configoption18'] : $params['configoptions'][v_fn('ips6_subnet')]);
		$ctrlpanel = (empty($params['configoptions'][v_fn('ctrlpanel')]) ? -1 : strtolower(trim($params['configoptions'][v_fn('ctrlpanel')])));
		
		// Fixes for SolusVM imported ConfigOptions
		if(empty($numips) && !empty($params['configoptions']['Extra IP Address'])){
			$numips = $params['configoptions']['Extra IP Address'];
		}
		
		if($loglevel > 0) logActivity('VIRT : '.$virttype.' - '.$hvm);
		if($loglevel > 0) logActivity(var_export($params, 1));
		
		if(!empty($params['configoptions']['ippoolid'])){
			$post['ippoolid'] = $params['configoptions']['ippoolid'];
		}
		
		// Get the Data
		$data = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=addvs&virt='.$virttype, array(), $post);
				
		if(empty($data)){
			return 'Could not load the server data.'.Virtualizor_Curl::error($params["serverip"]);
		}
	
		$cookies = array();
		
		$slave_server = (empty($params['configoptions'][v_fn('slave_server')]) ? $params['configoption15'] : $params['configoptions'][v_fn('slave_server')]);
		$server_group = (empty($params['configoptions'][v_fn('server_group')]) ? $params['configoption16'] : $params['configoptions'][v_fn('server_group')]);
		
		// Overcommit RAM
		foreach($data['servers'] as $k => $v){
			$data['servers'][$k]['_ram'] = !empty($v['overcommit']) ? ($v['overcommit'] - $v['alloc_ram']) : $v['ram'];
		}
		
		// Post Variables
		$post = array();
		$post['space'] = (empty($params['configoptions'][v_fn('space')]) || $params['configoptions'][v_fn('space')] == 0 ? $params['configoption2'] : $params['configoptions'][v_fn('space')]);
		$post['ram'] = (empty($params['configoptions'][v_fn('ram')]) || $params['configoptions'][v_fn('ram')] == 0 ? $params['configoption4'] : $params['configoptions'][v_fn('ram')]);
		if($loglevel > 0) logActivity('GET DATA : '.var_export($data, 1));
		// Is there a Slave server ?
		if(!empty($slave_server) && $slave_server != 'localhost'){
			
			// Do we have to Auto Select
			if($slave_server == 'auto'){
				
				foreach($data['servers'] as $k => $v){
					
					// Master servers cannot be here
					if(empty($k)) continue;
					
					// Only the Same type of Virtualization is supported
					if(!in_array($virttype, $v['virts'])){
						continue;
					}
					
					// Xen HVM additional check
					if(!empty($hvm) && empty($v['hvm'])){
						continue;
					}
					
					// Do you have enough space
					if($v['space'] < $post['space']){
						continue;
					}
					
					// Is the server locked ?
					if(!empty($v['locked'])){
						continue;
					}
					
					$ser_setting = unserialize($v['settings']);
				
					// Reached the limit of vps creation ?
					if(!empty($ser_setting['vpslimit']) && $v['numvps'] >= $ser_setting['vpslimit']){
						continue;
					}
					
					// Do you have enough RAM
					if($v['_ram'] < $post['ram']){
						continue;
					}
					
					if(isset($params["customfields"]['node_ram_select']) || !empty($virtualizor_conf['node_ram_select'])){
						$tmpsort[$k] = -$v['_ram'];
					}else{
						$tmpsort[$k] = $v['numvps'];
					}
					
				}
				
				// Did we get a list of Slave Servers
				if(empty($tmpsort)){
					return 'No server present in the Cluster which is of the Virtualization Type : '.$params['configoption1'];
				}
				
				asort($tmpsort);
				
				$newserid = key($tmpsort);
				//return 'Tests'.$newserid.var_export($tmpsort, 1);
				
			}else{
			
				foreach($data['servers'] as $k => $v){
					if(trim(strtolower($v['server_name'])) == trim(strtolower($slave_server))){
						$newserid = $k;
					}
				}
			
			}
			
			// Is there a valid slave server ?
			if(empty($newserid)){
				return 'There is no slave server - '.$slave_server.'. Please correct the <b>Product / Service</b> with the right slave server name.';
			}
		
			if($loglevel > 1) logActivity('Slave Server : '.$newserid);
		
		// Is there a Server Group ?
		}elseif(!empty($server_group)){
			
			foreach($data['servergroups'] as $k => $v){
				
				// Match the Server Group
				if(trim(strtolower($v['sg_name'])) == trim(strtolower($server_group))){					
					$sgid = $k;					
				}
				
			}
		
			// OH SHIT ! We didnt find anything 
			if(!isset($sgid)){
				return 'Could not find the server group - '.$server_group.'. Please correct the <b>Product / Service</b> with the right slave server name.';
			}
			
			// Make an array of available servers in this group
			foreach($data['servers'] as $k => $v){
				
				// Do you belong to this group
				if($v['sgid'] != $sgid){
					continue;
				}
				
				// Is the server locked ?
				if(!empty($v['locked'])){
					continue;
				}
				
				$ser_setting = unserialize($v['settings']);
				
				// Reached the limit of vps creation ?
				if(!empty($ser_setting['vpslimit']) && $v['numvps'] >= $ser_setting['vpslimit']){
					continue;
				}
				
				// Only the Same type of Virtualization is supported
				if(!in_array($virttype, $v['virts'])){
					continue;
				}
				
				// Xen HVM additional check
				if(!empty($hvm) && empty($v['hvm'])){
					continue;
				}
				
				//logActivity('Slave Server Selection Ram : '.$v['_ram'].' '.$v['overcommit'].' '.$v['alloc_ram'].' '.$post['ram'].' Space : '.$v['space'].' '.$post['space']);
				
				// Do you have enough space
				if($v['space'] < $post['space']){
					continue;
				}
				
				// Do you have enough RAM
				if($v['_ram'] < $post['ram']){
					continue;
				}
				
				if(isset($params["customfields"]['node_ram_select']) || !empty($virtualizor_conf['node_ram_select'])){
					$tmpsort[$k] = -$v['_ram'];
				}else{
					$tmpsort[$k] = $v['numvps'];
				}
				
			}
			
			asort($tmpsort);
			
			// Is there a valid slave server ?
			if(empty($tmpsort)){
				return 'No server present in the Server Group which is of the Virtualization Type : '.$params['configoption1'].'. Please correct the <b>Product / Service</b> with the right slave server name.';
			}
			
			$newserid = key($tmpsort);
			
			if($loglevel > 1) logActivity('Slave Group Server Chosen : '.$newserid);
			if($loglevel > 1) logActivity('Slave Server Details : '.var_export($data['servers'][$newserid], 1));
		}
		
		if(!empty($params['configoptions']['ippoolid'])){
			$post['ippoolid'] = $params['configoptions']['ippoolid'];
		}
		
		// If a new server ID was found. Even if its 0 (Zero) then there is no need to reload data as the DATA is by default of 0
		if(!empty($newserid)){
			
			$cookies[$data['globals']['cookie_name'].'_server'] = $newserid;
			
                
			$data = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=addvs&virt='.$virttype, array(), $post, $cookies);
			
			if(empty($data)){
				return 'Could not load the slave server data';
			}
		
		}
		
		if($loglevel > 2) logActivity(var_export($data, 1));
		
		// Search does the user exist
		foreach($data['users'] as $k => $v){
			if(strtolower($v['email']) == strtolower($params["clientsdetails"]['email'])){
				$post['uid'] = $v['uid'];
			}
		}
		
		// Was the user there ?
		if(empty($post['uid'])){
			$post['user_email'] = $params["clientsdetails"]['email'];
			$post['user_pass'] = $params["password"];
			
			// Just add teh fname and lname
			$post['fname'] = $params["clientsdetails"]['firstname'];
			$post['lname'] = $params["clientsdetails"]['lastname'];
		}
		
		// Get the OS from the fields set
		$OS = strtolower(trim($params['configoptions'][v_fn('OS')]));
		if(empty($OS)){
			$OS = strtolower(trim($params["customfields"]['OS']));
		}
		
		// Search the OS ID
		if($OS != 'none'){
		
			foreach($data['oslist'][$virttype] as $k => $v){
				foreach($v as $kk => $vv){
					
					// Xen/XCP Stuff!
					if($virttype == 'xen' || $virttype == 'xcp'){
					
						// Xen/XCP HVM templates
						if(!empty($hvm) && empty($vv['hvm'])){
							continue;
							
						// Xen/XCP PV templates
						}elseif(empty($hvm) && !empty($vv['hvm'])){
							continue;
						}
					}
					
					// Does the String match ?
					if(strtolower($vv['name']) == $OS){
						$post['osid'] = $kk;
					}
					
				}
			}
		
		}
		
		// Is the OS template there
		if(empty($post['osid']) && $OS != 'none'){
			return 'Could not find the OS Template '.$OS;
		}
		
		// Search the ISO
		if(!empty($params["customfields"]['iso']) && strtolower($params["customfields"]['iso']) != 'none'){
			
			// ISO restricted in OVZ and XEN-PV
			if($virttype == 'openvz' || (($virttype == 'xen' || $virttype == 'xcp') && empty($hvm))){
				return 'You can not select ISO for OpenVZ, XEN-PV and XCP-PV VPS';
			}
		
			foreach($data['isos'] as $k => $v){
			
				foreach($v as $kk => $vv){
					
					//echo $vv['name'].' - '.$params["customfields"]['iso'].'<br>';
					
					// Does the String match ?
					if(strtolower($vv) == strtolower(trim($params["customfields"]['iso']))){
						$post['iso'] = $vv;
					}
				}
			}
			
			// Is the ISO there
			if(empty($post['iso'])){
				return 'Could not find the ISO '.$params["customfields"]['iso'];
			}
		}
		
		// If ISO and OS both not selected ?
		if(empty($post['iso']) && empty($post['osid']) && strtolower($params["customfields"]['iso']) == 'none' && $OS == 'none'){
			return 'ISO or OS is not selected';
		}
		
		// No emails
		if(!empty($params["customfields"]['noemail'])){
			$post['noemail'] = 1;
		}
		
		// Are there any IPv4 to assign ?
		if($numips > 0){
		
			// Assign the IPs
			foreach($data['ips'] as $k => $v){
				$i = $numips;
				$_ips[] = $v['ip'];
				
				if($i == count($_ips)){
					break;
				}
			}
			
			// Were there enough IPs
			if(empty($_ips) || count($_ips) < $numips){
				return 'There are insufficient IPs on the server';
			}
		
		}
		
		// Are there any Inernal IPs to assign ?
		if($numips_int > 0){
		
			// Assign the IPs
			foreach($data['ips_int'] as $k => $v){
				$i = $numips_int;
				$_ips_int[] = $v['ip'];
				
				if($i == count($_ips_int)){
					break;
				}
			}
			
			// Were there enough IPs
			if(empty($_ips_int) || count($_ips_int) < $numips_int){
				return 'There are insufficient Internal IPs on the server';
			}
		
		}
		
		// Are there any IPv6 to assign ?
		if($numips6 > 0){
			
			$_ips6 = array();
			
			// Assign the IPs
			foreach($data['ips6'] as $k => $v){
				
				if($numips6 == count($_ips6)){
					break;
				}
				
				$_ips6[] = $v['ip'];
			}
			
			// Were there enough IPs
			if(empty($_ips6) || count($_ips6) < $numips6){
				return 'There are insufficient IPv6 Addresses on the server';
			}
		
		}
		
		// Are there any IPv6 Subnets to assign ?
		if($numips6_subnet > 0){
			
			$_ips6_subnet = array();
			
			// Assign the IPs
			foreach($data['ips6_subnet'] as $k => $v){
				
				if($numips6_subnet == count($_ips6_subnet)){
					break;
				}
				
				$_ips6_subnet[] = $v['ip'];
			}
			
			// Were there enough IPs
			if(empty($_ips6_subnet) || count($_ips6_subnet) < $numips6_subnet){
				return 'There are insufficient IPv6 Subnets on the server';
			}
		
		}
	
		if(empty($virtualizor_conf['vps_control']['custom_hname'])){
			$post['hostname'] = $params['domain'];
		}else{
			
			// Select the Order ID
			$res = mysql_query("SELECT * FROM `tblhosting` WHERE `id` = '".$params['serviceid']."';", $whmcsmysql);
			
			$hosting_details = mysql_fetch_assoc($res);
			
			$post['hostname'] = str_replace('{ID}', $hosting_details['orderid'], $virtualizor_conf['vps_control']['custom_hname']);
			if(preg_match('/(\{RAND(\d{1,3})\})/is', $post['hostname'], $matches)){
				$post['hostname'] = str_replace($matches[1], generateRandStr($matches[2]), $post['hostname']);
			}
			
			// Change the Hostname to the email
			mysql_query("UPDATE `tblhosting` SET `domain` = '".$post['hostname']."' WHERE `id` = '".$params['serviceid']."';", $whmcsmysql);
			
		}
		
		$post['rootpass'] = $params['password'];
		$post['bandwidth'] = (empty($params['configoptions'][v_fn('bandwidth')]) || $params['configoptions'][v_fn('bandwidth')] == 0 ? (empty($params['configoption7']) ? '0' : $params['configoption7']) : $params['configoptions'][v_fn('bandwidth')]);
		$post['cores'] = (empty($params['configoptions'][v_fn('cores')]) || $params['configoptions'][v_fn('cores')] == 0 ? $params['configoption9'] : $params['configoptions'][v_fn('cores')]);
		$post['network_speed'] = (empty($params['configoptions'][v_fn('network_speed')]) || $params['configoptions'][v_fn('network_speed')] == 0 ? $params['configoption14'] : $params['configoptions'][v_fn('network_speed')]);
		$post['cpu_percent'] = (empty($params['configoptions'][v_fn('cpu_percent')]) || $params['configoptions'][v_fn('cpu_percent')] == 0 ? $params['configoption10'] : $params['configoptions'][v_fn('cpu_percent')]);
		$post['cpu'] = $params['configoption8'];
		$post['addvps'] = 1;
		$post['band_suspend'] = 1;
		
		// Fixes for SolusVM imported ConfigOptions
		if(empty($post['ram']) && !empty($params['configoptions']['Memory'])){
			$post['ram'] = (int)$params['configoptions']['Memory'];
		}
		if(empty($post['space']) && !empty($params['configoptions']['Disk Space'])){
			$post['space'] = $params['configoptions']['Disk Space'];
		}
		if(empty($post['cores']) && !empty($params['configoptions']['CPU'])){
			$post['cores'] = $params['configoptions']['CPU'];
		}
		
		if(!empty($params['customfields']['hostname'])){
			$post['hostname'] = $params['customfields']['hostname'];
		}
		
		if(!empty($params['configoptions']['ippoolid'])){
			$post['ippoolid'] = $params['configoptions']['ippoolid'];
		}
		
		// Control Panel
		$control_panel = trim(strtolower($params['configoptions']['control_panel']));
		$post['control_panel'] = ((empty($control_panel) || $control_panel == 'none') ? 0 : $control_panel);
		
		// Is is OpenVZ
		if($virttype == 'openvz'){
		
			$post['inodes'] = $params['configoption3'];
			$post['burst'] = $params['configoption5'];
			$post['priority'] = $params['configoption11'];
			
		// Is it Xen PV?
		}elseif(($virttype == 'xen' || $virttype == 'xcp') && empty($hvm)){
			
			$post['swapram'] = (empty($params['configoptions'][v_fn('swapram')]) || $params['configoptions'][v_fn('swapram')] == 0 ? (empty($params['configoption6']) ? '0' : $params['configoption6']) : $params['configoptions'][v_fn('swapram')]);
			if($params['configoption12'] == 'yes' || $params['configoption12'] == 'on'){
				$post['vnc'] = 1;
				$post['vncpass'] = generateRandStr(8);
			}
			
		// Is it Xen HVM?
		}elseif(($virttype == 'xen' || $virttype == 'xcp') && !empty($hvm)){
			
			$post['hvm'] = 1;
			$post['shadow'] = 8;
			$post['swapram'] = (empty($params['configoptions'][v_fn('swapram')]) || $params['configoptions'][v_fn('swapram')] == 0 ? (empty($params['configoption6']) ? '0' : $params['configoption6']) : $params['configoptions'][v_fn('swapram')]);
			if($params['configoption12'] == 'yes' || $params['configoption12'] == 'on'){
				$post['vnc'] = 1;
				$post['vncpass'] = generateRandStr(8);
			}
			
		// Is it KVM ?
		}elseif($virttype == 'kvm'){
		
			$post['swapram'] = (empty($params['configoptions'][v_fn('swapram')]) || $params['configoptions'][v_fn('swapram')] == 0 ? (empty($params['configoption6']) ? '0' : $params['configoption6']) : $params['configoptions'][v_fn('swapram')]);
			if($params['configoption12'] == 'yes' || $params['configoption12'] == 'on'){
				$post['vnc'] = 1;
				$post['vncpass'] = generateRandStr(8);
			}
			
		}
		
		// Suspend on bandwidth
		//$post['band_suspend'] = 1;
		
		// Add the IPs
		if(!empty($_ips)){
			$post['ips'] = $_ips;
		}
		
		// Add the Internal IPs
		if(!empty($_ips_int)){
			$post['ips_int'] = $_ips_int;
		}
		
		// Add the IPv6
		if(!empty($_ips6)){
			$post['ipv6'] = $_ips6;
		}
		
		// Add the IPv6 Subnet
		if(!empty($_ips6_subnet)){
			$post['ipv6_subnet'] = $_ips6_subnet;
		}
		
		if($loglevel > 0) logActivity('configoption : '.var_export($params['configoptions'], 1));
		
		// Are there any configurable options
		if(!empty($params['configoptions'])){
			foreach($params['configoptions'] as $k => $v){
				if(!isset($post[$k])){
					$post[$k] = $v;
				}
			}
		}
		
		// Any custom code ?
		if(file_exists(dirname(__FILE__).'/custom.php')){
			include_once(dirname(__FILE__).'/custom.php');
			
			if(!empty($custom_error)){
				return $custom_error;
			}
			
		}
		
		if($loglevel > 0) logActivity('POST : '.var_export($post, 1));
		
	 //echo "<pre>";print_r($cookies);echo "</pre>";
	 //echo "<pre>";print_r($post);echo "</pre>";
	// return 'TEST'.var_export($params, 1);
		
		// Setup cPanel licenses if cPanel configurable option is set
		if($ctrlpanel != -1 && $ctrlpanel != 'none'){
		
			if($ctrlpanel == 'cpanel' && !empty($virtualizor_conf['cp']['buy_cpanel_login']) && !empty($virtualizor_conf['cp']['buy_cpanel_apikey'])){
				logActivity("CPANEL : cPanel issued for ip $_ips[0] of ordertype $cpanel");
				
				$url = 'https://www.buycpanel.com/api/order.php?';
				$login = 'login='.$virtualizor_conf['cp']['buy_cpanel_login'].'&';
				$key = 'key='.$virtualizor_conf['cp']['buy_cpanel_apikey'].'&';
				$domain = 'domain='.$params['domain'].'&';
				$serverip = 'serverip='.$_ips[0].'&';
				$ordertype = 'ordertype=10';
				
				$url .= $login.$key.$domain.$serverip.$ordertype;
				
				$ret = file_get_contents($url);
				
				$ret = json_decode($ret);
				
				if($ret->success == 0){
					return 'Errors : cPanel Licensing : '.$ret->faultstring;
				}
			}
		}
		
		$ret = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=addvs&virt='.$virttype, array(), $post, $cookies);
		
		if($loglevel > 0) logActivity('RETURN POST AFTER CREATION: '.var_export($ret['newvs'], 1));
		
	}// End of old module
	
	// Was the VPS Inserted
	if(!empty($ret['newvs']['vpsid'])){
		
		if($loglevel > 0) logActivity('Virtualizor DONE ? : '.var_export($ret['done'], 1));
		
		// vpsid of virtualizor
		$query = mysql_query("SELECT `id` FROM `tblcustomfields` WHERE `relid` = '$pid' AND `fieldname` = 'vpsid'", $whmcsmysql);
		$res = mysql_fetch_array($query);
		
		// We will check if there is an entry if not we will insert it.
		$query = mysql_query("SELECT `relid` FROM `tblcustomfieldsvalues` WHERE `relid` = '$serviceid' AND `fieldid` = '$res[id]'", $whmcsmysql);
		$sel_res = mysql_fetch_assoc($query);
		
		if($loglevel > 0) logActivity('Did we found anything : '.var_export($sel_res, 1));
		
		// We will insert it if not found anything
		if(empty($sel_res['relid'])){
	
			mysql_query("INSERT INTO `tblcustomfieldsvalues` SET `value` = '".$ret['newvs']['vpsid']."', `relid` = '$serviceid', `fieldid` = '$res[id]'", $whmcsmysql);
			if($loglevel > 0) logActivity('After Updating tblcustomfieldsvalues : '.var_export(mysql_error($whmcsmysql), 1));
			
		}else{
			
			mysql_query("UPDATE `tblcustomfieldsvalues` SET `value` = '".$ret['newvs']['vpsid']."' WHERE `relid` = '$serviceid' AND `fieldid` = '$res[id]'", $whmcsmysql) or mysql_error($whmcsmysql);
			if($loglevel > 0) logActivity("UPDATE `tblcustomfieldsvalues` SET `value` = '".$ret['newvs']['vpsid']."' WHERE `relid` = '$serviceid' AND `fieldid` = '$res[id]'");
		}
			
		// Change the Username to the email
		mysql_query("UPDATE `tblhosting` SET `username` = '".$params['clientsdetails']['email']."' WHERE `id` = '$serviceid';", $whmcsmysql);

		// The Dedicated IP
		mysql_query("UPDATE `tblhosting` SET `dedicatedip` = '".(!empty($_ips[0]) ? $_ips[0] : $_ips6[0])."' WHERE `id` = '$serviceid'", $whmcsmysql);
		
		$tmp_ips = empty($_ips) ? array() : $_ips;
		
		if(!empty($_ips6_subnet)){
			foreach($_ips6_subnet as $k => $v){
				$tmp_ips[] = $v;
			}
		}
		
		if(!empty($_ips6)){
			foreach($_ips6 as $k => $v){
				$tmp_ips[] = $v;
			}
		}
		
		// Extra IPs
		if(count($tmp_ips) > 1){
			unset($tmp_ips[0]);
			mysql_query("UPDATE `tblhosting` SET `assignedips` = '".implode("\n", $tmp_ips)."' WHERE `id` = '$serviceid'", $whmcsmysql);
		}
		
		// Did it start ?
		if(!empty($ret['done'])){
			return 'success';	
		}else{
			return 'Errors : '.implode('<br>', $ret['error']);
		}
		
	}else {
		return 'Errors : '.implode('<br>', $ret['error']);
	}
	
}

function virtualizor_AdminServicesTabFields($params) {
	
	if(!empty($_GET['vapi_mode'])){
		ob_end_clean();
	}
	
	$code = virtualizor_newUI($params, 'clientsservices.php?vapi_mode=1&userid='.$params['userid'], '../modules/servers'); 
	
	$fieldsarray = array(
	 'VPS Information' => '<div style="width:100%" id="tab1"></div>'.$code,
	);
	
	return $fieldsarray;

}


function virtualizor_TerminateAccount($params) {

	global $virtualizor_conf, $whmcsmysql;
	
	$loglevel = (int) @$_REQUEST['loglevel'];
	$serviceid = $params["serviceid"]; # Unique ID of the product/service in the WHMCS Database
	
	if(!empty($virtualizor_conf['loglevel'])){
		$loglevel = $virtualizor_conf['loglevel'];
	}
	
	$ctrlpanel = (empty($params['configoptions'][v_fn('ctrlpanel')]) ? -1 : $params['configoptions'][v_fn('ctrlpanel')]);
	
	if(!empty($virtualizor_conf['admin_ui']['disable_terminate'])){
		return 'Termination has been disabled by the Global Administrator';
	}

	// Setup cPanel licenses if cPanel configurable option is set
	if($ctrlpanel != -1 && $ctrlpanel != 'none'){
		
		if($ctrlpanel == 'cpanel' && !empty($virtualizor_conf['cp']['buy_cpanel_login']) && !empty($virtualizor_conf['cp']['buy_cpanel_apikey'])){
		
			$data = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=vs');

			$data = $data['vs'][$params['customfields']['vpsid']]['ips'];
		
			list($cpanel_ip_id, $cpanel_ip) = array_shift($data);
			
			logActivity("CPANEL : cPanel delete for ip $cpanel_ip");
			
			$url = 'https://www.buycpanel.com/api/cancel.php?';
			$login = 'login='.$virtualizor_conf['cp']['buy_cpanel_login'].'&';
			$key = 'key='.$virtualizor_conf['cp']['buy_cpanel_apikey'].'&';
			$currentip = 'currentip='.$cpanel_ip.'&';
			$url .= $login.$key.$currentip;
			
			$ret = file_get_contents($url);
			
			$ret = json_decode($ret);
			
			if($ret->success == 0){
				return 'Errors : cPanel Licensing : '.$ret->faultstring;
			}
		}
	}

	$data = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=vs&delete='.$params['customfields']['vpsid']);
			
	if(empty($data)){
		return 'Could not load the server data.'.Virtualizor_Curl::error($params["serverip"]);
	}

// echo "<pre>";print_r($params);echo "</pre>";
// echo "<pre>";print_r($data);echo "</pre>";
	
	// If the VPS has been deleted
    if ($data['done']) {
		
		if($loglevel > 0) logActivity('Data after termination : '.var_dump($data, 1));
		
		// vpsid of virtualizor
		$query = mysql_query("SELECT `id` FROM `tblcustomfields` WHERE `relid` = '".$params["pid"]."' AND `fieldname` = 'vpsid'", $whmcsmysql);
		$res = mysql_fetch_array($query);
		mysql_query("UPDATE `tblcustomfieldsvalues` SET `value` = '' WHERE `relid` = '".$params["serviceid"]."' AND `fieldid` = '$res[id]'", $whmcsmysql) or mysql_error($whmcsmysql);
		
		if($loglevel > 0) logActivity("UPDATE `tblcustomfieldsvalues` SET `value` = '' WHERE `relid` = '".$params["serviceid"]."' AND `fieldid` = '$res[id]'");
		
		if($loglevel > 0) logActivity('Terminate -> After updating vpsid mysqlerr: '.mysql_error($whmcsmysql));
		
		// The Dedicated IP
		mysql_query("UPDATE `tblhosting` SET `dedicatedip` = '' WHERE `id` = '".$params["serviceid"]."'", $whmcsmysql);
		
		mysql_query("UPDATE `tblhosting` SET `assignedips` = '' WHERE `id` = '".$params["serviceid"]."'", $whmcsmysql);
		
		$result = "success";
	} else {
		$result = empty($data['error_msg']) ? "There was some error deleting the VPS" : $data['error_msg'];
	}
	
	return $result;
}

function virtualizor_SuspendAccount($params) {

	$data = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=vs&suspend='.$params['customfields']['vpsid']);
			
	if(empty($data)){
		return 'Could not load the server data.'.Virtualizor_Curl::error($params["serverip"]);
	}

// echo "<pre>";print_r($params);echo "</pre>";
// echo "<pre>";print_r($data);echo "</pre>";

    if ($data['done']) {
		$result = "success";
	} else {
		$result = "There was some error suspending the VPS";
	}
	return $result;
}

function virtualizor_UnsuspendAccount($params) {

	$data = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=vs&unsuspend='.$params['customfields']['vpsid']);
			
	if(empty($data)){
		return 'Could not load the server data.'.Virtualizor_Curl::error($params["serverip"]);
	}

// echo "<pre>";print_r($params);echo "</pre>";
// echo "<pre>";print_r($data);echo "</pre>";

    if ($data['done']) {
		$result = "success";
	} else {
		$result = "There was some error unsuspending the VPS";
	}
	return $result;
}

function virtualizor_ChangePassword($params) {

	# Code to perform action goes here...
	
	$data = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=editvs&vpsid='.$params['customfields']['vpsid']);
	
	if(empty($data)){
		return 'Could not load the server data.'.Virtualizor_Curl::error($params["serverip"]);
	}
	
	$post_vps = $data['vps'];
	
	$post_vps['editvps'] = 1;
	
	$post_vps['rootpass'] = $params['password'];
	
	logActivity('Post Array : '.var_export($params, 1));
	
	if($loglevel > 0) logActivity('Post Array : '.var_export($post_vps, 1));
	
	$ret = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=editvs&vpsid='.$params['customfields']['vpsid'], array(), $post_vps);
	
	unset($ret['scripts']);
	unset($ret['iscripts']);
	unset($ret['ostemplates']);
	unset($ret['isos']);
	
	if($loglevel > 0) logActivity('Post Result : '.var_export($ret, 1));
			
	if(empty($ret)){
		return 'Could not load the server data after processing.'.Virtualizor_Curl::error($params["serverip"]);
	}

    if(!empty($ret['done'])){
		
		$result = "success";
	}else{
		
		if(!empty($ret['error'])){
			return 'Errors : '.implode('<br>', $ret['error']);
		}
		
		$result = 'Unknown error occured. Please check logs';
	}

	return $result;
}

function virtualizor_ChangePackage($params) {

	global $virtualizor_conf;
	
	$loglevel = (int) @$_REQUEST['loglevel'];
	$serviceid = $params["serviceid"]; # Unique ID of the product/service in the WHMCS Database
	
	if(!empty($virtualizor_conf['loglevel'])){
		$loglevel = $virtualizor_conf['loglevel'];
	}
	
	// Get the Data
	$data = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=editvs&vpsid='.$params['customfields']['vpsid']);
			
	if(empty($data)){
		return 'Could not load the server data.'.Virtualizor_Curl::error($params["serverip"]);
	}
	
	$post_vps = $data['vps'];
	
	if($loglevel > 0) logActivity('Change Package Params : '.var_export($params, 1));
	if($loglevel > 0) logActivity('Orig VPS : '.var_export($post_vps, 1));
	
	// Are you using New module ?
	if(!in_array($params['configoption1'], array('OpenVZ', 'Xen PV', 'Xen HVM', 'KVM', 'XCP HVM', 'XCP PV'))){
		$post_vps = array();
				
		// Now get the plan ID to post
		$tmp_plid = explode('-', $params['configoption3']);
		$post_vps['plid'] = trim($tmp_plid[0]);
		$virttype = $data['vps']['virt'];
		$post_vps['user_email'] = $params["clientsdetails"]['email'];
		
		//logActivity('Params : '.var_export($params, 1));
		
		if($loglevel > 0) logActivity('params : '.var_export($params, 1));
		
		// Fixes for SolusVM imported ConfigOptions
		if(empty($post_vps['ram']) && !empty($params['configoptions']['Memory'])){
			$post_vps['ram'] = $params['configoptions']['Memory'];
		}
		if(empty($post_vps['space']) && !empty($params['configoptions']['Disk Space'])){
			$post_vps['space'] = $params['configoptions']['Disk Space'];
		}
		if(empty($post_vps['cores']) && !empty($params['configoptions']['CPU'])){
			$post_vps['cores'] = $params['configoptions']['CPU'];
		}
		
		if(!empty($params['configoptions'][v_fn('ips')])){
			$post_vps['num_ips'] = $params['configoptions'][v_fn('ips')];
		}
		
		if(!empty($params['configoptions'][v_fn('ips_int')])){
			$post_vps['num_ips_int'] = $params['configoptions'][v_fn('ips_int')];
		}
		
		if(!empty($params['configoptions'][v_fn('ips6')])){
			$post_vps['num_ips6'] = $params['configoptions'][v_fn('ips6')];
		}
		
		if(!empty($params['configoptions'][v_fn('ips6_subnet')])){
			$post_vps['num_ips6_subnet'] = $params['configoptions'][v_fn('ips6_subnet')];
		}
		
		if(!empty($params['configoptions'][v_fn('space')])){
			$post_vps['space'] = $params['configoptions'][v_fn('space')];
		}
		
		if(!empty($params['configoptions'][v_fn('ram')])){
			$post_vps['ram'] = $params['configoptions'][v_fn('ram')];
		}
		
		if(!empty($params['configoptions'][v_fn('bandwidth')])){
			$post_vps['bandwidth'] = $params['configoptions'][v_fn('bandwidth')];
		}
		
		if(!empty($params['configoptions'][v_fn('cores')])){
			$post_vps['cores'] = $params['configoptions'][v_fn('cores')];
		}
		
		if(!empty($params['configoptions'][v_fn('network_speed')])){
			$post_vps['network_speed'] = $params['configoptions'][v_fn('network_speed')];
		}
				
		// Are there any configurable options
		if(!empty($params['configoptions'])){
			foreach($params['configoptions'] as $k => $v){
				if(!isset($post_vps[$k])){
					$post_vps[$k] = $v;
				}
			}
		}
		
		$post_vps['hostname'] = $data['vps']['hostname'];
		
		$post_vps['editvps'] = 1;
		
		if($loglevel > 0) logActivity('Post Array : '.var_export($post_vps, 1));
	
		$ret = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=editvs&vpsid='.$params['customfields']['vpsid'], array(), $post_vps);
		
		//if($loglevel > 0) logActivity('Return after Edit: '.var_export($ret, 1));
		
		// Fill the variables as per the OLD module as it will be inserted to WHMCS. Like ips, ips6, etc..
		if(!empty($ret['vps']['ips'])){
			$post_vps['ips'] = $ret['vps']['ips'];
		}
		
		if(!empty($ret['vps']['ipv6'])){
			$post_vps['ipv6'] = $ret['vps']['ipv6'];
		}
		
		if(!empty($ret['vps']['ipv6_subnet'])){
			$post_vps['ipv6_subnet'] = $ret['vps']['ipv6_subnet'];
		}
		
	// This is old method
	}else{
	
		// POST Variables
		$post_vps['space'] = (empty($params['configoptions'][v_fn('space')]) || $params['configoptions'][v_fn('space')] == 0 ? $params['configoption2'] : $params['configoptions'][v_fn('space')]);
		$post_vps['ram'] = (empty($params['configoptions'][v_fn('ram')]) || $params['configoptions'][v_fn('ram')] == 0 ? $params['configoption4'] : $params['configoptions'][v_fn('ram')]);
		$post_vps['bandwidth'] = (empty($params['configoptions'][v_fn('bandwidth')]) || $params['configoptions'][v_fn('bandwidth')] == 0 ? (empty($params['configoption7']) ? '0' : $params['configoption7']) : $params['configoptions'][v_fn('bandwidth')]);
		$post_vps['cores'] = (empty($params['configoptions'][v_fn('cores')]) || $params['configoptions'][v_fn('cores')] == 0 ? $params['configoption9'] : $params['configoptions'][v_fn('cores')]);
		$post_vps['network_speed'] = (empty($params['configoptions'][v_fn('network_speed')]) || $params['configoptions'][v_fn('network_speed')] == 0 ? $params['configoption14'] : $params['configoptions'][v_fn('network_speed')]);
		$post_vps['cpu_percent'] = (empty($params['configoptions'][v_fn('cpu_percent')]) || $params['configoptions'][v_fn('cpu_percent')] == 0 ? $params['configoption10'] : $params['configoptions'][v_fn('cpu_percent')]);
		$post_vps['cpu'] = $params['configoption8'];
	
		$post_vps['inodes'] = $params['configoption3'];
		$post_vps['burst'] = $params['configoption5'];
		$post_vps['priority'] = $params['configoption11'];
		$post_vps['swapram'] = $params['configoption6'];
		
		// Fixes for SolusVM imported ConfigOptions
		if(empty($post_vps['ram']) && !empty($params['configoptions']['Memory'])){
			$post_vps['ram'] = $params['configoptions']['Memory'];
		}
		if(empty($post_vps['space']) && !empty($params['configoptions']['Disk Space'])){
			$post_vps['space'] = $params['configoptions']['Disk Space'];
		}
		if(empty($post_vps['cores']) && !empty($params['configoptions']['CPU'])){
			$post_vps['cores'] = $params['configoptions']['CPU'];
		}
		
		if($params['configoption12'] == 'yes' || $params['configoption12'] == 'on'){
			$post_vps['vnc'] = 1;
			if(empty($vps['vnc'])){
				$post_vps['vncpass'] = generateRandStr(8);
			}
		}
		
		$virttype = $post_vps['virt'];
		
		// IPs are the same always
		$post_vps['ips'] = $post_vps['ips'];
		
		// Add the IPv6
		if(!empty($post_vps['ips6'])){
			$post_vps['ipv6'] = $post_vps['ips6'];
		}
		
		// Add the IPv6 Subnet
		if(!empty($post_vps['ips6_subnet'])){
			$post_vps['ipv6_subnet'] = $post_vps['ips6_subnet'];
			foreach($post_vps['ipv6_subnet'] as $k => $v){
				$tmp = explode('/', $v);
				$post_vps['ipv6_subnet'][$k] = $tmp[0];
			}
		}
		
		$numips = (empty($params['configoptions'][v_fn('ips')]) || $params['configoptions'][v_fn('ips')] == 0 ? $params['configoption13'] : $params['configoptions'][v_fn('ips')]);
		$numips6 = (empty($params['configoptions'][v_fn('ips6')]) || $params['configoptions'][v_fn('ips6')] == 0 ? $params['configoption17'] : $params['configoptions'][v_fn('ips6')]);
		$numips6_subnet = (empty($params['configoptions'][v_fn('ips6_subnet')]) || $params['configoptions'][v_fn('ips6_subnet')] == 0 ? $params['configoption18'] : $params['configoptions'][v_fn('ips6_subnet')]);
		
		// Fixes for SolusVM imported ConfigOptions
		if(empty($numips) && !empty($params['configoptions']['Extra IP Address'])){
			$numips = $params['configoptions']['Extra IP Address'];
		}
		
		// Remove some IPs
		if($numips < count($post_vps['ips'])){
			
			$i = 0;
			$newips = array();
			
			foreach($post_vps['ips'] as  $k => $v){
				
				// We have completed
				if($numips == $i){
					break;
				}
				
				$newips[$k] = $v;
				$i++;
			}
			
			$post_vps['ips'] = $newips;
		
		// Add some IPs
		}elseif($numips > count($post_vps['ips'])){
			
			$toadd = $numips - count($post_vps['ips']);
			
			// Assign the IPs
			foreach($data['ips'] as $k => $v){
				
				if(in_array($v['ip'], $post_vps['ips'])){
					continue;
				}
				
				$post_vps['ips'][$k] = $v['ip'];
				
				if($numips == count($post_vps['ips'])){
					break;
				}
			}
			
			// Were there enough IPs
			if(count($post_vps['ips']) < $numips){
				return 'There are insufficient IPs on the server';
			}
			
		}
		
		// Remove some IPv6 Subnets
		if($numips6_subnet < count($post_vps['ipv6_subnet'])){
			
			$i = 0;
			$newips = array();
			
			foreach($post_vps['ipv6_subnet'] as  $k => $v){
				
				// We have completed
				if($numips6_subnet == $i){
					break;
				}
				
				$newips[$k] = $v;
				$i++;
				
			}
			
			$post_vps['ipv6_subnet'] = $newips;
		
		// Add some IP Subnet
		}elseif($numips6_subnet > count($post_vps['ipv6_subnet'])){
			
			$toadd = $numips6_subnet - count($post_vps['ipv6_subnet']);
			
			// Assign the IP Subnets
			foreach($data['ips6_subnet'] as $k => $v){
				
				if(in_array($v['ip'], $post_vps['ipv6_subnet'])){
					continue;
				}
				
				$post_vps['ipv6_subnet'][$k] = $v['ip'];
				
				if($numips6_subnet == count($post_vps['ipv6_subnet'])){
					break;
				}
			}
			
			// Were there enough IPs
			if(count($post_vps['ipv6_subnet']) < $numips6_subnet){
				return 'There are insufficient IPv6 Subnets on the server';
			}
			
		}
		
		// Remove some IPv6
		if($numips6 < count($post_vps['ipv6'])){
			
			$i = 0;
			$newips = array();
			
			foreach($post_vps['ipv6'] as  $k => $v){
				
				// We have completed
				if($numips6 == $i){
					break;
				}
				
				$newips[$k] = $v;
				$i++;
				
			}
			
			$post_vps['ipv6'] = $newips;
		
		// Add some IPs
		}elseif($numips6 > count($post_vps['ipv6'])){
			
			$toadd = $numips6 - count($post_vps['ipv6']);
			
			// Assign the IPs
			foreach($data['ips6'] as $k => $v){
				
				if(in_array($v['ip'], $post_vps['ipv6'])){
					continue;
				}
				
				$post_vps['ipv6'][$k] = $v['ip'];
				
				if($numips6 == count($post_vps['ipv6'])){
					break;
				}
			}
			
			// Were there enough IPs
			if(count($post_vps['ipv6']) < $numips6){
				return 'There are insufficient IPv6 Addresses on the server';
			}
			
		}
		
		// Are there any configurable options
		if(!empty($params['configoptions'])){
			foreach($params['configoptions'] as $k => $v){
				if(!isset($post_vps[$k])){
					$post_vps[$k] = $v;
				}
			}
		}
		
		$post_vps['editvps'] = 1;
		
		if($loglevel > 0) logActivity('Post Array : '.var_export($post_vps, 1));
		
		$ret = Virtualizor_Curl::make_api_call($params["serverip"], $params["serverpassword"], 'index.php?act=editvs&vpsid='.$params['customfields']['vpsid'], array(), $post_vps);
	
	}// End of OLD module
	
	unset($ret['scripts']);
	unset($ret['iscripts']);
	unset($ret['ostemplates']);
	unset($ret['isos']);
	
	if($loglevel > 0) logActivity('Post Result : '.var_export($ret, 1));
			
	if(empty($ret)){
		return 'Could not load the server data after processing.'.Virtualizor_Curl::error($params["serverip"]);
	}

    if(!empty($ret['done'])){
		
		$result = "success";
		
		$tmp_ips = array();
		
		if(!empty($post_vps['ips'])){
			foreach($post_vps['ips'] as $k => $v){
				$tmp_ips[] = $v;
			}
		}
		
		if(!empty($post_vps['ipv6_subnet'])){
			foreach($post_vps['ipv6_subnet'] as $k => $v){
				$tmp_ips[] = $v;
			}
		}
		
		if(!empty($post_vps['ipv6'])){
			foreach($post_vps['ipv6'] as $k => $v){
				$tmp_ips[] = $v;
			}
		}
		
		//logActivity(var_export($tmp_ips, 1));
		
		// The Dedicated IP
		mysql_query("UPDATE `tblhosting` SET `dedicatedip` = '".$tmp_ips[0]."' WHERE `id` = '$serviceid'");
		
		// Extra IPs
		$tmp_cnt = count($tmp_ips);
		if(!empty($tmp_cnt)){
			unset($tmp_ips[0]);
			mysql_query("UPDATE `tblhosting` SET `assignedips` = '".implode("\n", $tmp_ips)."' WHERE `id` = '$serviceid'");
		}

	}else{
			
		if(!empty($ret['error'])){
			return 'Errors : '.implode('<br>', $ret['error']);
		}
		
		$result = 'Unknown error occured. Please check logs';
		
	}
	
	return $result;
	
}

function virtualizor_AdminLink($params) {
	$code = '<a href="https://'.$params["serverip"].':4085/index.php?act=login" target="_blank">Virtualizor Admin Panel</a>';
	return $code;
}

function virtualizor_LoginLink($params) {
	$code = "<a href=\"https://".$params["serverip"].":4083/\" target=\"_blank\" style=\"color:#cc0000\">Login to Virtualizor</a>";
	return $code;
	
}

function virtualizor_AdminCustomButtonArray() {
	# This function can define additional functions your module supports, the example here is a reboot button and then the reboot function is defined below
    $buttonarray = array(
	 "Start VPS" => "start",
	 "Reboot VPS" => "reboot",
 	 "Stop VPS"=> "stop",
	 "Poweroff VPS"=> "poweroff"
	);
	return $buttonarray;
}


function virtualizor_ClientAreaCustomButtonArray() {
	# This function can define additional functions your module supports, the example here is a reboot button and then the reboot function is defined below
    $buttonarray = array(
	 "Start VPS" => "start",
	 "Reboot VPS" => "reboot",
 	 "Stop VPS"=> "stop",
	 "Poweroff VPS"=> "poweroff",
	);
	return $buttonarray;
}


class Virtualizor_Curl {
	
	public static function error($ip = ''){
		
		$err = '';
		
		if(!empty($GLOBALS['virt_curl_err'])){
			$err .= ' Curl Error: '.$GLOBALS['virt_curl_err'];
		}
		
		if(!empty($ip)){
			$err .= ' (Server IP : '.$ip.')';
		}
		
		return $err;
	}
	
	public static function make_api_call($ip, $pass, $path, $data = array(), $post = array(), $cookies = array()){
		
		global $virtualizor_conf, $whmcsmysql;
		
		$key = generateRandStr(8);
		$apikey = make_apikey($key, $pass);
		
		$url = 'https://'.$ip.':4085/'.$path;	
		$url .= (strstr($url, '?') ? '' : '?');	
		$url .= '&api=serialize&apikey='.rawurlencode($apikey).'&skip_callback=1';
		
		// Pass some data if there
		if(!empty($data)){
			$url .= '&apidata='.rawurlencode(base64_encode(serialize($data)));
		}
	
		if($virtualizor_conf['loglevel'] > 0){
			logActivity('URL : '. $url);
		}
		
		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
			
		// Time OUT
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		
		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			
		// UserAgent
		curl_setopt($ch, CURLOPT_USERAGENT, 'Softaculous');
		
		// Cookies
		if(!empty($cookies)){
			curl_setopt($ch, CURLOPT_COOKIESESSION, true);
			curl_setopt($ch, CURLOPT_COOKIE, http_build_query($cookies, '', '; '));
		}
		
		if(!empty($post)){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		// Get response from the server.
		$resp = curl_exec($ch);
		
		if(empty($resp)){
			$GLOBALS['virt_curl_err'] = curl_error($ch);
		}
			
		curl_close($ch);
		
		// The following line is a method to test
		//if(preg_match('/sync/is', $url)) echo $resp;
		
		if(empty($resp)){
			return false;
		}
		
		// As a security prevention measure - Though this cannot happen
		$resp = str_replace($pass, '12345678901234567890123456789012', $resp);
		
		$r = _unserialize($resp);
		
		if(empty($r)){
			return false;
		}
		
		return $r;
	}	

	public static function e_make_api_call($ip, $pass, $vid, $path, $post = array()){
		$key = generateRandStr(8);
		$apikey = make_apikey($key, $pass);
		
		$url = 'https://'.$ip.':4083/'.$path;	
		$url .= (strstr($url, '?') ? '' : '?');	
		$url .= '&svs='.$vid.'&api=serialize&apikey='.rawurlencode($apikey).'&skip_callback=1';
		
		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		
		// Time OUT
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		
		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			
		// UserAgent and Cookies
		curl_setopt($ch, CURLOPT_USERAGENT, 'Softaculous');
		
		if(!empty($post)){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		// Get response from the server.
		$resp = curl_exec($ch);
		curl_close($ch);
		
		// The following line is a method to test
		//if(preg_match('/os/is', $url)) echo $resp;
		
		if(empty($resp)){
			return false;
		}
		
		// As a security prevention measure - Though this cannot happen
		$resp = str_replace($pass, '12345678901234567890123456789012', $resp);
		
		$r = _unserialize($resp);
		
		if(empty($r)){
			return false;
		}
		
		return $r;
	}	
	
	public static function action($params, $action, $post = array()){
		
		global $virt_verify, $virt_errors;
		
		// Make the call
		$response = Virtualizor_Curl::e_make_api_call($params["serverip"], $params["serverpassword"], $params['customfields']['vpsid'], 'index.php?'.$action, $post);

		if(empty($response)){
			$virt_errors[] = 'The action could not be completed as no response was received.';
			return false;
		}
		
		return $response;
	
	} // function virt_curl_action ends	

} // class virtualizor_curl ends


function virtualizor_newUI($params, $url_prefix = 'clientarea.php?action=productdetails', $modules_url = 'modules/servers'){
	
	global $virt_action_display, $virt_errors, $virt_resp, $virtualizor_conf, $whmcsmysql;
	
	// Is the VPS there ?
	if(empty($params['customfields']['vpsid'])){
		return 'VPS not provisioned';
	}
	
	// New method of Virtualizor Module
	if(isset($_GET['give'])){
	
		//error_reporting(-1);
		
		$var['APP'] = 'Virtualizor'; // NOT USED
		$var['site_name'] = 'WHMCS';
		$var['API'] = $url_prefix.'&id='.$params['serviceid'].'&api=json&';
		$var['giver'] = $url_prefix.'&id='.$params['serviceid'].'&';
		$var['url'] = $url_prefix.'&id='.$params['serviceid'].'&';
		$var['copyright'] = 'Virtualizor';
		$var['version'] = '2.0.2';
		$var['logo'] = '';
		$var['theme'] = $modules_url.'/virtualizor/ui/';
		$var['theme_path'] = dirname(__FILE__).'/ui/';
		$var['images'] = $var['theme'].'images/';
	
		if($_GET['give'] == 'index.html'){
			
			// We are zipping if possible
			if(function_exists('ob_gzhandler')){
				ob_start('ob_gzhandler');
			}
	
			// Read the file
			$data = file_get_contents($var['theme_path'].'index.html');
			
			$filetime = filemtime($var['theme_path'].'index.html');
			
		}
	
		if($_GET['give'] == 'combined.js'){
		
			// Read the file
			$data = '';
			$jspath = $var['theme_path'].'js2/';
			$files = array('jquery.min.js',
							'jquery.dataTables.min.js',
							'jquery-ui.custom.min.js',
							'jquery.bpopup.min.js',
							'jquery.tablesorter.min.js',
							'jquery.flot.min.js',
							'jquery.flot.pie.min.js',
							'jquery.flot.stack.min.js',
							'jquery.flot.time.min.js',
							'jquery.flot.tooltip.min.js',
							'jquery.flot.symbol.min.js',
							'jquery.flot.axislabels.js',
							'jquery.flot.selection.min.js',
							'jquery.flot.resize.min.js',
							'jquery.slimscroll.min.js',
							'tiptip.js',
							'bootstrap.min.js',
							'icheck.min.js',
							'virtualizor.js',
						);
			
			foreach($files as $k => $v){
				//echo $k.'<br>';
				$data .= file_get_contents($jspath.'/'.$v)."\n\n";
			}
			
			// We are zipping if possible
			if(function_exists('ob_gzhandler')){
				ob_start('ob_gzhandler');
			}
			
			// Type javascript
			header("Content-type: text/javascript; charset: UTF-8");
	
			// Set a zero Mtime
			$filetime = filemtime($var['theme_path'].'/js2/virtualizor.js');
			
		}
	
		if($_GET['give'] == 'style.css'){
		
			// Read the file
			$data = '';
			$jspath = $var['theme_path'].'css2/';
			$files = array('bootstrap.min.css',
				'font-awesome.min.css',
				'grey.css',
				'jquery-ui.min.css',
				'jquery.dataTables.css',
				'style.css',
			);
			
			foreach($files as $k => $v){
				//echo $k.'<br>';
				$data .= file_get_contents($jspath.'/'.$v)."\n\n";
			}
			
			// Type CSS
			header("Content-type: text/css; charset: UTF-8");
			
			// We are zipping if possible
			if(function_exists('ob_gzhandler')){
				ob_start('ob_gzhandler');
			}
			
		}
		
		foreach($var as $k => $v){			
			$data = str_replace('[['.$k.']]', $v, $data);
		}
	
		// Parse the languages
		vload_lang();
		echo vparse_lang($data);
		
		die();
		exit(0);
		
	}
	
	if($_REQUEST['api'] == 'json'){
		
		// Overwrite certain variables
		$_GET['svs'] = $params['customfields']['vpsid'];
		$_GET['SET_REMOTE_IP'] = $_SERVER['REMOTE_ADDR'];
		
		$res = Virtualizor_Curl::action($params, http_build_query($_GET), $_POST);
		
		$res['uid'] = 0;
		
		echo json_encode($res);
		die();
		exit(0);
	}
	
	if($_GET['b'] == 'novnc' || (!empty($_REQUEST['novnc'])) && $_REQUEST['act'] == 'vnc'){
	
		$data = Virtualizor_Curl::action($params, 'act=vnc&novnc=1');
		
		// Find the servers hostname
		$res = mysql_query("SELECT hostname FROM `tblservers` WHERE `id` = '".$params['serverid']."';", $whmcsmysql);		
		$server_details = mysql_fetch_assoc($res);
		$params['serverhostname'] = $server_details['hostname'];
		
		// fetch the novnc file
		$novnc_viewer = file_get_contents($modules_url.'/virtualizor/novnc/novnc.html');
		$novnc_password = $data['info']['password']; 
		$vpsid = $params['customfields']['vpsid'];
		$novnc_serverip = empty($params['serverhostname']) ? $params['serverip'] : $params['serverhostname'];
		$proto = 'http';
		$port = 4081;
		$virt_port = 4082;
		$websockify = 'websockify';
		if(!empty($_SERVER['HTTPS']) || @$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'){
			$proto = 'https';
			$port = 4083;
			$virt_port = 4083;
			$websockify = 'novnc/';
			$novnc_serverip = empty($params['serverhostname']) ? $params['serverip'] : $params['serverhostname'];
		}
		
		if($data['info']['virt'] == 'xcp'){
			$vpsid .= '-'.$data['info']['password'];
		}
		
		echo $novnc_viewer = vlang_vars_name($novnc_viewer, array('HOST' => $novnc_serverip,
															'PORT' => $port,
															'VIRTPORT' => $virt_port,
															'PROTO' => $proto,
															'WEBSOCKET' => $websockify,
															'TOKEN' => $vpsid,
															'PASSWORD' => $novnc_password));
															
													
		die();
	}
	
	// Java VNC
	if($_REQUEST['act'] == 'vnc' && !empty($_REQUEST['launch'])){
	
		$response = Virtualizor_Curl::action($params, 'act=vnc&launch=1&giveapplet=1', '', true);
		
		if(empty($response)){
			return false;
		}
		
		// Is the applet code in the API Response ?
		if(!empty($response['info']['applet'])){
			
			$applet = $response['info']['applet'];
			
		}else{
	
			$virttype = preg_match('/xcp/is', $params['configoption1']) ? 'xcp' : strtolower($params['configoption1']);
		
			// NonXCP
			if($virttype != 'xcp'){
				
				if(!empty($response['info']['port']) && !empty($response['info']['ip']) && !empty($response['info']['password'])){				
					$applet = '<APPLET ARCHIVE="https://s2.softaculous.com/a/virtualizor/files/VncViewer.jar" CODE="com.tigervnc.vncviewer.VncViewer" WIDTH="1" HEIGHT="1">
						<PARAM NAME="HOST" VALUE="'.$response['info']['ip'].'">
						<PARAM NAME="PORT" VALUE="'.$response['info']['port'].'">
						<PARAM NAME="PASSWORD" VALUE="'.$response['info']['password'].'">
						<PARAM NAME="Open New Window" VALUE="yes">
					</APPLET>';	
				}
			
			// XCP
			}else{
				
				if(!empty($response['info']['port']) && !empty($response['info']['ip'])){
					$applet = '<APPLET ARCHIVE="https://s2.softaculous.com/a/virtualizor/files/TightVncViewer.jar" CODE="com.tightvnc.vncviewer.VncViewer" WIDTH="1" HEIGHT="1">
						<PARAM NAME="SOCKETFACTORY" value="com.tightvnc.vncviewer.SshTunneledSocketFactory">
						<PARAM NAME="SSHHOST" value="'.$response['info']['ip'].'">
						<PARAM NAME="HOST" value="localhost">
						<PARAM NAME="PORT" value="'.$response['info']['port'].'">
						<PARAM NAME="Open New Window" VALUE="yes">
					</APPLET>';
				}
				
			}
		
		}
		
		echo $applet;
		
		die();
	
	}
	
	if(!empty($virtualizor_conf['client_ui']['direct_login'])){
		return "<center><a href=\"https://".$params["serverip"].":4083/\" target=\"_blank\">Login to Virtualizor</a></center>";
	}

	$code .= '<script type="text/javascript">
		
function iResize(){
	try{
		document.getElementById("virtualizor_manager").style.height = 
		document.getElementById("virtualizor_manager").contentWindow.document.body.offsetHeight + "px";
	}catch(e){ };
}

setInterval("iResize()", 1000);

$(document).ready(function(){
	
	var divID = "tab1";
	if (!document.getElementById(divID)) {
        divID = "domain";
    }
	
	var myDiv = document.createElement("div");
	myDiv.id = "virtualizor_load_div";
	myDiv.innerHTML = \'<center style="padding:10px; background-color: #FAFBD9;">Loading Panel options ...</center><br /><br /><br />\';
	document.getElementById(divID).appendChild(myDiv);
	
	var iframe = document.createElement("iframe");
	iframe.id = "virtualizor_manager";
	iframe.width = "100%";
	iframe.style.display = "none";
	iframe.style.border = "none";
	iframe.scrolling = "no";
	iframe.src = "'.$url_prefix.'&id='.$params['serviceid'].'&give=index.html#act=vpsmanage";
	document.getElementById(divID).appendChild(iframe);
	
	$("#virtualizor_manager").load(function(){
		$("#virtualizor_load_div").hide();
		$(this).show();
		iResize();
	});
	
	$(".moduleoutput").each(function(){
		this.style.display = "none";
	});
	
});

</script>';

	return $code;
		
}


function virtualizor_ClientArea($params) {
	
	global $virt_action_display, $virt_errors, $virt_resp, $virtualizor_conf, $whmcsmysql;

	// The new UI
	return virtualizor_newUI($params);	

}

function virtualizor_start($params) {
	
	global $virt_action_display, $virt_errors;
	
	$virt_resp = Virtualizor_Curl::action($params, 'act=start&do=1');
	
	if(empty($virt_resp['done'])){
		$virt_action_display = 'The VPS failed to start';
		return $virt_action_display;
	}
	
	// Done
	return "success";

}

function virtualizor_stop($params) {
	
	global $virt_action_display, $virt_errors;
	
	$virt_resp = Virtualizor_Curl::action($params, 'act=stop&do=1');
	
	if(empty($virt_resp)){
		$virt_action_display = 'Failed to stop the VPS';
		return $virt_action_display;
	}
	
	// Done
	return "success";

}

function virtualizor_reboot($params) {
	
	global $virt_action_display, $virt_errors;
	
	$virt_resp = Virtualizor_Curl::action($params, 'act=restart&do=1');
	
	if(empty($virt_resp)){
		$virt_action_display = 'Failed to reboot the VPS';
		return $virt_action_display;
	}
	
	// Done
	return "success";

}


function virtualizor_poweroff($params) {
	
	global $virt_action_display, $virt_errors;
	
	$virt_resp = Virtualizor_Curl::action($params, 'act=poweroff&do=1');
	
	if(empty($virt_resp)){
		$virt_action_display = 'Failed to poweroff the VPS';
		return $virt_action_display;
	}
	
	// Done
	return "success";

}

/*
function r_print($re){
	echo '<pre>';
	print_r($re);
	echo '</pre>';	
}
function died(){
	print_r(error_get_last());
}

register_shutdown_function('died');
*/

?>