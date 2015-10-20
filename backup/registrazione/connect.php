<?php
$dataoggi = date('Ymd');
$oraoggi = date('hi', strtotime('+1 hour'));
$serverName = "localhost";
$connectionInfo = array("Database"=>"TuttoShopping","UID"=>"AdminTuttoShopping", "PWD"=>"VrWqiVyU", "CharacterSet"=>'UTF-8');
$conn = sqlsrv_connect( $serverName, $connectionInfo)or die("Couldn't connect to SQL Server");
?>