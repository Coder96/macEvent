#!/usr/bin/php
<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	include dirname(__DIR__) . '/bin/macEvent.inc.php';
	
	$pgmName = basename(__FILE__);
	
	$logFileName = date("omd") . '.log';
	
	$cmd = 'nohup ' . $pgmMqttClientSub . ' | ' . $pgmBinDir . 'macEventMain.php 2>&1 >>' . $pgmLogDir . $logFileName . ' &' ;#
// 'nohup ' . 
//	echo($cmd);
	exec($cmd);
	


