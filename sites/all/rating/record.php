<?php
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/style.php';
function record_db($gender, $devizion, $discipline, $country){
if ($gender == 1)
  {
    $cat = array(47 => array(20 => 47), 52 => array('47.1' => 52), 57 => array('52.1' => 57), 
      63 => array('57.1' => 63), 72 => array('63.1' => 72), 84 => array('72.1' => 84),
      '+84' => array('84.1' => 250));
  }
if ($gender == 2) 
{
  $cat = array(
    59 => array(20 => 59), 66 => array('59.1' => 66), 74 => array('66.1' => 74),
    83 => array('74.1' => 83), 93 => array('83.1' => 93), 105 => array('93.1' => 105), 
    120 => array('105.1' => 120), '+120' => array('120.1' => 250));
  } 
foreach ($cat as $c => $i) 
{
  foreach ($i as $k => $v) 
  { 
    // запрос разбивается на две части
    $array[$c] = "
    SELECT  w.*
    FROM  
    (
    SELECT a.*, c.date, 
    TRUNCATE(c.{$discipline}, 1) AS {$discipline}, 
    TRUNCATE(c.wilks, 2) AS wilks, TRUNCATE(c.weight, 2) AS weight,
    CONCAT(nc.name_ru, ' (', ac.name_ru, ')') as comp
    FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c, " . 
      TABLECOMPETITIONNAME . " as nc, " . TABLECATEGORYAGE . " as ac
    WHERE a.athlete_id = c.athlete_id 
      and c.competition = nc.id
      and c.age_category = ac.id ";
    // если задана страна
    if (FALSE !== $country) { $array[$c] .= " AND country = '{$country}' "; }
    // продолжение запроса
    $array[$c] .= " 
    AND c.devizion = {$devizion}
    AND a.gender = {$gender}
    AND c.weight between {$k} AND {$v}
    AND c.date >= 2011
    ) AS w
    ORDER BY {$discipline} DESC LIMIT 1";
  }
}
print "<tbody> \n";
if ($gender == 1) {$hr_gender = 'Женщины';}
if ($gender == 2) {$hr_gender = 'Мужчины';}
if ($discipline == 'total') $hr_discipline = 'Сумма';
if ($discipline == 'squat') $hr_discipline = 'Приседание';
if ($discipline == 'brench') $hr_discipline = 'Жим лежа';
if ($discipline == 'deadlift') $hr_discipline = 'Становая тяга';
if ($country == '') 
{
  $colspan = 10;
} else {
  $colspan = 9;
}
echo "<tr><th colspan='{$colspan}'>{$hr_gender}. {$hr_discipline}</th></tr>";
foreach ($array as $k => $v) 
{
  $v = mysql_query($v);
  print "<tr>";
  while ($result = mysql_fetch_assoc($v))
  {
    echo "<td>{$k}</td>"."\n";
    echo "<td class='name'>{$result['name']}</td> \n";
    echo "<td>{$result['age']}</td>\n";
    if (FALSE === $country) 
    { 
      $sql_code = mysql_query("select name from country 
                                where iso = '" . $result['country'] . "'") or die(mysql_error());
      $sql_code = mysql_fetch_assoc($sql_code);
      echo "<td class='sm'>" . $sql_code['name'] . "</td>\n"; 
    }
    echo "<td class='sm'>{$result['subject_rf']}</td>\n";
    echo "<td class='sm'>{$result['city']}</td>\n";
    echo "<td><strong>{$result[$discipline]}</strong></td>\n";
    echo "<td>{$result['weight']}</td>\n";
    echo "<td class='sm'>" . str_replace('(Взрослые)', '', $result['comp']) . "</td>\n";
    echo "<td class='sm'>{$result['date']}</td>\n";
  }
  print "</tr>\n";
}
print "</tbody>\n";
}
//print '<table><tr><td>';
print "<table class='tabstyle1'>\n";
print '<caption>Троеборье</caption>';
echo record_db($gender, 2, 'squat', $country);
echo record_db($gender, 2, 'brench', $country);
echo record_db($gender, 2, 'deadlift', $country);
echo record_db($gender, 2, 'total', $country);
print "</table>\n";
//print '</td><td>';
print "<table class='tabstyle1'>\n";
print '<caption>Классическое троеборье</caption>';
echo record_db($gender, 1, 'squat', $country);
echo record_db($gender, 1, 'brench', $country);
echo record_db($gender, 1, 'deadlift', $country);
echo record_db($gender, 1, 'total', $country);
print "</table>\n";
//print '</td></tr></table>';
?>