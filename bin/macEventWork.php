#!/usr/bin/php 
<?PHP

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	include dirname(__DIR__) . '/bin/macEvent.inc.php';

	$pgmName = basename(__FILE__);

	$config = parse_ini_file($pgmCfgFile, true);

	$iMacAddress = $argv[1];
	
	if(!isset($config['mac_' . $iMacAddress])){
		echo("Mac $iMacAddress not found.");
		exit();
	}
	
	if(!isset($config['mac_' . $iMacAddress]['system']) AND $config['mac_' . $iMacAddress]['debug']){
		echo("system for mac $iMacAddress not found.");
		exit();
	}
	$system = $config['mac_' . $iMacAddress]['system']; 
	
	if(!isset($config['mac_' . $iMacAddress]['action']) AND $config['mac_' . $iMacAddress]['debug']){
		echo("action for mac $iMacAddress not found.");
		exit();
	}
	$action = $config['mac_' . $iMacAddress]['action'];
	
	if($action == 'urlpath'){
		$url = $config['mac_' . $iMacAddress]['urlpath'];
	} else {
		echo("No urlpath found\n");
		exit();
	}
	$servicUrl = $config['system_' . $system]['url'] . $url;
	$servicUser = $config['system_' . $system]['user'];
	$servicPass = $config['system_' . $system]['pass'];
/*
	$options = array(
		CURLOPT_RETURNTRANSFER => true,   // return web page
		CURLOPT_HEADER         => false,  // don't return headers
		CURLOPT_FOLLOWLOCATION => true,   // follow redirects
		CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
		CURLOPT_ENCODING       => "",     // handle compressed
		CURLOPT_USERAGENT      => "test", // name of client
		CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
		CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
		CURLOPT_TIMEOUT        => 120,    // time-out on response
	);
*/

	$curl = curl_init($servicUrl);
	curl_setopt($curl, CURLOPT_USERPWD, $servicUser . ":" . $servicPass);
 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	$curl_response = curl_exec($curl);
	$status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
#	print_r("$status_code|$curl_response\n");
   
   
	if ($curl_response === false){
    print_r('Curl error: ' . curl_error($curl));
	}
   
  curl_close($curl);
