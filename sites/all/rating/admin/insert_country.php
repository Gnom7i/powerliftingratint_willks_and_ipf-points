<?php 
require_once 'sites/all/rating/config.php'; 
$TableName = TABLECOUNTRY;
if (isset($_REQUEST['GoAdd'])) {
  $MatchName = mysql_query("SELECT name FROM " . $TableName . " WHERE name='{$_REQUEST['name']}'");
  if (mysql_num_rows($MatchName) > 0) {
    exit('Страна <b>"' . $_REQUEST['name'] . '"</b> уже есть в базе данных');
  }
  //
  $DATABASE->query("INSERT INTO " . $TableName . " SET " . 
                   " id=?, abbreviated_name=trim(?), name=trim(?), english=trim(?), " .
                   " roman_code=trim(?), iso=?, part=trim(?), group_USSR=?", 
                    0, $_REQUEST['abbr'], $_REQUEST['name'], $_REQUEST['english'] , 
                    $_REQUEST['roman'], $_REQUEST['iso'], $_REQUEST['part'], NULL);
  Header("Location: /node/63?" . time());
  exit();
}
?>
<form action="" method="POST">
  <textarea style="width: 100%"></textarea><br />
  <input type="text" required="required" placeholder="Название" name="abbr"><br />
  <input type="text" required="required" placeholder="Полное название" name="name"><br />
  <input type="text" required="required" placeholder="English" name="english"><br />
  <input type="text" required="required" placeholder="Roman cod" name="roman"><br />
  <input type="text" required="required" placeholder="ISO" name="iso"><br />
  <input type="text" required="required" placeholder="Часть света" name="part"><br />
  <input type="submit" name="GoAdd"><br />
</form>
<?php
$part = $DATABASE->selectCol("SELECT DISTINCT part FROM " . $TableName . " ORDER BY part");
print '<table>';
print '<tr>';
foreach ($part as $result){
  $a = $DATABASE->selectCol("SELECT DISTINCT abbreviated_name FROM " 
                            . $TableName
                            . " WHERE part = '{$result}' ORDER BY abbreviated_name
  ");
  print '<td style="vertical-align: top">';  
  print '<h3>' . $result . '</h3>';   
  print '<ul>';
  foreach ($a as $e) {
    print '<li>' . $e . '</li>';
  }
  print '</ul>';
  print '</td>';
}
print '</tr>';
print '</table>';
?>