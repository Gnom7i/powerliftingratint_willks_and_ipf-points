<?php
if (getenv("COMSPEC")){
  $user = 'root';
  $pass = '';
  $db = 'ratingwilks';
} else {
  $user = 'mashko_dvfo';
  $pass = 'hcz$I7Ch';
  $db = 'mashko_dvfo';
}


@require_once "DbSimple/Generic.php";
// ������������ � ��.
@$DATABASE = DbSimple_Generic::connect('mysql://'.$user.':'.$pass.'@localhost/'.$db);
// ������������� ���������� ������.
$DATABASE->setErrorHandler('databaseErrorHandler');
// ��� ����������� ������ SQL.
function databaseErrorHandler($message, $info)
{
	// ���� �������������� @, ������ �� ������.
	if (!error_reporting()) return;
	// ������� ��������� ���������� �� ������.
	echo "SQL Error: $message<br><pre>"; 
	print_r($info);
	echo "</pre>";
	exit();
}
define('TABLEATHLETE', 'rating_dvfo_athlete'); 
define('TABLECOMPETITION', 'competition');
define('TABLECOMPETITIONNAME', 'name_competition');
define('TABLECATEGORYAGE', 'age_category');
define('TABLECOUNTRY', 'country');
define('TABLEFO', 'fo_rf');
define('TABLEFEDERALDISTRICT', 'federal_district');
//
global $user;
if ($user->uid == 1) { 
  $FlagAdmin = TRUE;
} else {
  $FlagAdmin = FALSE;
}
?>