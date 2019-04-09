<?php
$user = 'mashko_kampower';
$pass = '380775478';
$db = 'mashko_kampower';



mysql_connect('localhost', $user, $pass) or die(mysql_error);
mysql_query("SET NAMES utf8");
mysql_select_db($db) or die(mysql_error);
?>