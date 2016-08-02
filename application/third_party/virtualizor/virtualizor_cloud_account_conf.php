<?php

//////////////////////////////////////////////////////////////
//===========================================================
// softaculous_extra.php
//===========================================================
// SOFTACULOUS 
// Version : 1.1
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Alons
// Date:       10th Jan 2009
// Time:       21:00 hrs
// Site:       http://www.softaculous.com/ (SOFTACULOUS)
// ----------------------------------------------------------
// Please Read the Terms of use at http://www.softaculous.com
// ----------------------------------------------------------
//===========================================================
// (c)Softaculous Inc.
//===========================================================
//////////////////////////////////////////////////////////////

global $virtcloud_acc;


// If You want to give custom names to the Custom Fields
// Uncomment the fields and change the values as per the discription on the wiki : http://virtualizor.com/wiki/WHMCS_Module


// Configurable Options
$virtcloud_acc['fields']['num_vs'] = 'Number of VMs';
$virtcloud_acc['fields']['num_users'] = 'Number of Users';
$virtcloud_acc['fields']['space'] = 'Space';
$virtcloud_acc['fields']['ram'] = 'RAM';
$virtcloud_acc['fields']['burst'] = 'Burst / Swap';
$virtcloud_acc['fields']['bandwidth'] = 'Bandwidth';
$virtcloud_acc['fields']['cpu'] = 'Default CPU Weight';
$virtcloud_acc['fields']['cores'] = 'Max Cores';
$virtcloud_acc['fields']['cpu_percent'] = 'Default CPU %';
$virtcloud_acc['fields']['num_cores'] = 'Total Cores';
$virtcloud_acc['fields']['ips'] = 'Number of IPs';
$virtcloud_acc['fields']['ips6'] = 'Number of IPv6 Address';
$virtcloud_acc['fields']['num_ipv4'] = 'Number of IPv4';
$virtcloud_acc['fields']['num_ipv6'] = 'Number of IPv6';
$virtcloud_acc['fields']['num_ipv6_subnet'] = 'Number of IPv6 Subnet';
$virtcloud_acc['fields']['sgs'] = 'Server Groups';
$virtcloud_acc['fields']['mgs'] = 'Media Groups';

// Log Level
$virtcloud_acc['loglevel'] = 0;

?>