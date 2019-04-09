<?php
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/style.php';
// функция посчета колличества спортсменов в базе
function sql_table_stat($fo, $gender){
  $sql = "SELECT count(athlete_id) as total FROM ".TABLEATHLETE." where 1=1";
  if ($fo) {
    $sql .= " and fo='{$fo}'";
  }
  if ($gender) {
    $sql .= " and gender='{$gender}'";
  }
  $sql = mysql_query($sql);
  $sql = mysql_fetch_assoc($sql);
  return $sql['total'];
}
// запрос аббривиатур ФО
$abbr = $DATABASE->selectCol('select abbr from federal_district order by id');
// запрос названий ФО
$district = $DATABASE->selectCol('select district from federal_district order by id');
// вывод таблицы
print '<table class="tabstyle1">';
print '<tr><th>Федеральный округ</th><th>Спортсменов</th> <th>Женщин</th> <th>Мужчин</th></tr>';
foreach ($abbr as $v)
{
print '<tr>';
echo "<td>{$v}</td>" ;
print '<td>' . sql_table_stat($v, false) . '</td>';
print '<td>' . sql_table_stat($v, 1) . '</td>';
print '<td>' . sql_table_stat($v, 2) . '</td>';
print '</tr>';
}
print '</table>';
?>