<?php

	$pgmBaseDir = dirname(__DIR__) . '/';

	$pgmBinDir = $pgmBaseDir . 'bin/';
	$pgmCfgDir = $pgmBaseDir . 'cfg/';
	$pgmLogDir = $pgmBaseDir . 'log/';
	
	$pgmCfgFile = $pgmCfgDir . 'config.ini';
	
	$pgmMqttClientSub = 'mosquitto_sub -v -t macEvent';
	
	
	
///////////////////////////////////////////////////////////////////////////////////
function formatMac(&$iMac){

	$rMac = '';
	$rMac .= substr($iMac, 0, 2) ;
	foreach([2,4,6,8,10] as $cut){
		$rMac .= ':' . substr($iMac, $cut, 2) ;
	}
	return $rMac;
}
///////////////////////////////////////////////////////////////////////////////////
function isValidMac($mac){
  if(preg_match('/([a-fA-F0-9]{2}[:|\-]?){6}/', $mac) == 1){
  	return true;
  } else {
  	return false;
  }
}
///////////////////////////////////////////////////////////////////////////////////
function writeLog($iMessage){
	echo(date("o/m/d-H:i:s")."|$iMessage".PHP_EOL);
}
