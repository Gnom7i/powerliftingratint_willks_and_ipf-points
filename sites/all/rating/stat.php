<?php
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/style.php';
// таблица названий ФО РФ
define('DISTRICT', 'federal_district');
// функция посчета колличества спортсменов в базе
function sql_table_stat($fo, $gender)
{
  $sql = "SELECT count(athlete_id) as total FROM ".TABLEATHLETE." WHERE country = 643 ";
  if ($fo) 
  {
    $sql .= " AND fo='{$fo}'";
  }
  if ($gender) 
  {
    $sql .= " AND gender='{$gender}'";
  }
  $sql = mysql_query($sql);
  $sql = mysql_fetch_assoc($sql);
  return $sql['total'];
}
// запрос аббривиатур ФО
$abbr = $DATABASE->selectCol('SELECT abbr FROM '.DISTRICT.' as f, country 
                              WHERE iso = 643 ORDER BY f.id');
// запрос названий ФО
$district = $DATABASE->selectCol('SELECT district FROM '.DISTRICT.' as f, country
                                  WHERE iso = 643 ORDER BY f.id');
// вывод таблицы
print '<table class="tabstyle1">';
print '<caption>Российская Федерация</caption>';
print '<tr><th>Федеральный округ</th><th>Спортсменов</th> <th>Женщин</th> <th>Мужчин</th></tr>';
foreach ($abbr as $k => $v)
{
print '<tr>';
echo "<td>{$district[$k]} ({$v})</td>" ;
print '<td>' . sql_table_stat($v, false) . '</td>';
print '<td>' . sql_table_stat($v, 1) . '</td>';
print '<td>' . sql_table_stat($v, 2) . '</td>';
print '</tr>';
}
print '<tr>
<td>Всего (РФ) </td>
<td>' .sql_table_stat(false, false). '</td>
<td>' .sql_table_stat(false, 1). '</td>
<td>' .sql_table_stat(false, 2).'</td>
</tr>';
print '</table>';
?>