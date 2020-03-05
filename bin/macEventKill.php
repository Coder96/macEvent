#!/usr/bin/php
<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	include dirname(__DIR__) . '/bin/macEvent.inc.php';
	
	$pgmName = basename(__FILE__);
	
	$logFileName = ''. date("omd") . '.log';
	
	$cmd = 'nohup ' . $pgmMqttClientSub . ' | ' . $pgmBinDir . 'macEventMain.php 2>&1 >>' . $pgmLogDir . $logFileName . ' &' ;#

	$ps = shell_exec("ps a -o pid,command | grep  ". escapeshellarg($pgmMqttClientSub) ." | grep -v 'grep'");
//	echo $ps.PHP_EOL;
	
	$ps = trim($ps);
	$pidList = explode(PHP_EOL, $ps);
	foreach($pidList as $line){

		$line = trim($line);
		list($mPid, $mCmd) = explode(" ", $line, 2);
		
		writeLog('Killing:'.$line);

		$ps = shell_exec('kill -9 '. escapeshellarg($mPid) );
	}

// 'nohup ' . 
//	echo($cmd);
//	exec($cmd);
	

//
// ps a -o pid,command | grep  'mosquitto_sub -v -t macEvent' | grep -v 'grep'
//
// 2385 mosquitto_sub -v -t macEvent
//