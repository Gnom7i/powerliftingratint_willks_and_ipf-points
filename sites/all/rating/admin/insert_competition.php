<?php 
require_once 'sites/all/rating/config.php'; 
$TableName = TABLECOMPETITIONNAME;
if (isset($_REQUEST['GoAdd'])) {
  $MatchName = mysql_query("SELECT name_ru FROM " . $TableName . " WHERE name_ru='{$_REQUEST['name_ru']}'");
  if (mysql_num_rows($MatchName) > 0) {
    exit('Соревнование <b>"' . $_REQUEST['name_ru'] . '"</b> уже есть в базе данных');
  }
  //
  $DATABASE->query("INSERT INTO " . $TableName . " SET " . 
                   " id=?, name_ru=trim(?), name_en=trim(?), rang=trim(?), " .
                   " popular=?, comment=trim(?) ", 
                    0, $_REQUEST['name_ru'], $_REQUEST['name_en'], $_REQUEST['rang'] , 
                    $_REQUEST['popular'], $_REQUEST['comment']
                  );
  Header("Location: /node/64?" . time());
  exit();
}
?>
<form action="" method="POST">
  <textarea style="width: 100%"></textarea><br />
  <input type="text" required="required" placeholder="Название RU" name="name_ru"><br />
  <input type="text" required="required" placeholder="Name EN" name="name_en"><br />
  <?php
  $SelectRang = $DATABASE->selectCol('SELECT DISTINCT rang FROM ' . $TableName);
  print '<select name="rang">';
  foreach ($SelectRang as $SR) {
    print "<option value='{$SR}'>{$SR}</option>";
  }
  print '</select>';
  print '<br />';
  ?>
  <input type="number" min='1' max='9' required="required" placeholder="Популярность" name="popular"><br />
  <input type="text" required="required" placeholder="Комментарий" name="comment"><br />
  <input type="submit" name="GoAdd"><br />
</form>
<?php
$part = $DATABASE->selectCol("SELECT DISTINCT rang FROM " . $TableName . " ORDER BY rang");
print '<table style="font-size: x-small">';
print '<tr>';
foreach ($part as $result){
  $a = $DATABASE->selectCol("SELECT DISTINCT name_ru FROM " 
                            . $TableName
                            . " WHERE rang = '{$result}' ORDER BY popular, rang, name_ru
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