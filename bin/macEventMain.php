#!/usr/bin/php
<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	include dirname(__DIR__) . '/bin/macEvent.inc.php';

	$pgmName = basename(__FILE__);

	$pgmLckFile = $pgmCfgDir . basename($pgmName, '.php') . '.lck';
	$pgmPidFile = $pgmCfgDir . basename($pgmName, '.php') . '.pid';

	$fpLock = fopen($pgmLckFile, 'w+');
	/* Activate the LOCK_NB option on an LOCK_EX operation */
	if(!flock($fpLock, LOCK_EX | LOCK_NB)) {
	//
	// Already running exit;
	//
		writeLog('Already running exit');
		exit();
	}
	writePidInfo($pgmPidFile);

///////////////////////////////////////////////////////////////////////////////////

	$config = parse_ini_file($pgmCfgFile, true);
	
	if(!$config){
		writeLog("Cound Not open $pgmCfgFile");
		exit;
	}

///////////////////////////////////////////////////////////////////////////////////

	while( $mqttMessage = fgets(STDIN) ) {
		
	  echo "$mqttMessage";
  	$mqttMessage = trim($mqttMessage);
		writeLog("mqttMessage:$mqttMessage");
	  @list($mTopic, $mMac, $junk) = explode(" ", $mqttMessage, 3);
	  
		$mMac = strtoupper($mMac);
		writeLog("Mac:$mMac");
		
		$mMac = formatMac($mMac);
		
		if(isset($config['mac_' . $mMac])){
			if($config['mac_' . $mMac]['debug']){
				writeLog(
				 "MAC:        $mMac"                                     . PHP_EOL .
				 'active:     ' . $config['mac_' . $mMac]['active']      . PHP_EOL .
				 'debug:      ' . $config['mac_' . $mMac]['debug']       . PHP_EOL .
				 'buttonname: ' . $config['mac_' . $mMac]['buttonName']  . PHP_EOL .
				 'description:' . $config['mac_' . $mMac]['description'] . PHP_EOL .
				 'system:     ' . $config['mac_' . $mMac]['system']      . PHP_EOL .
				 'action:     ' . $config['mac_' . $mMac]['action']      . PHP_EOL .
				 'command:    ' . $config['mac_' . $mMac]['command']     . PHP_EOL .
				 'urlpath:    ' . $config['mac_' . $mMac]['urlpath']         
				);
			}
			if(isset($config['mac_' . $mMac])){
				if($config['mac_' . $mMac]['debug']) writeLog("Found Mac:$mMac");
				if($config['mac_' . $mMac]['active']){
					if($config['mac_' . $mMac]['debug']) writeLog("Active Mac:$mMac");
						$logFileName = date("omd") . '.txt';
					 	$cmd = 'nohup ' . $pgmBinDir . 'macEventWork.php ' . escapeshellarg($mMac) . ' 2>&1 >>' . $pgmLogDir . $logFileName . ' &';
//					 	writeLog($cmd);
					system($cmd);
				}
			}
		} else {
			writeLog("Mac not in ini:$mMac");
		}
	}

///////////////////////////////////////////////////////////////////////////////////
function writePidInfo($fileName){
	
	$myPid = getmypid();
	$fpPid = popen("cat /proc/{$myPid}/cmdline", 'r');
	$pidName = fgets($fpPid);
	pclose($fpPid);
	
	$fpPid = fopen("$fileName", 'w');
//	fputs($fpPid, $myPid .'|'. $pidName);
	fputs($fpPid, $myPid);
	fclose($fpPid);
}

