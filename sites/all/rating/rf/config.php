<?php
$user = 'mashko_dvfo';
$pass = 'hcz$I7Ch';
$db = 'mashko_dvfo';



mysql_connect('localhost', $user, $pass) or die(mysql_error());
mysql_query("SET NAMES utf8");
mysql_select_db($db) or die(mysql_error());
?>