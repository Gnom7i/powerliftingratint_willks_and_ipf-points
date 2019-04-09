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
    SELECT a.*, c.date, cts.city AS citys, cts.region AS region,
    TRUNCATE(c.{$discipline}, 1) AS {$discipline}, 
    CONCAT(nc.name_ru, ' (', ac.name_ru, ')') AS comp
    FROM " . TABLEATHLETE . " as a
      INNER JOIN " . TABLECOMPETITION . " AS c      ON a.athlete_id = c.athlete_id
      INNER JOIN " . TABLECOMPETITIONNAME . " AS nc ON c.competition = nc.id
      INNER JOIN " . TABLECATEGORYAGE . " AS ac     ON c.age_category = ac.id
      INNER JOIN cities AS cts                      ON a.city = cts.id
    WHERE cts.country_id = 2
    AND country IN(804)
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

echo "<tr><th colspan='6'>{$hr_gender}. {$hr_discipline}</th></tr>";
foreach ($array as $k => $v) 
{
  $v = mysql_query($v);
  print "<tr>";
  while ($result = mysql_fetch_assoc($v))
  {
    echo "<td>{$k}</td>"."\n";
    echo "<td class='name'>{$result['name']}</td> \n";
    echo "<td>{$result['age']}</td>\n";
    echo "<td class=''>{$result['region']} <br/> {$result['citys']}</td>\n";
    echo "<td><strong>{$result[$discipline]}</strong></td>\n";
    echo "<td class=''>" . str_replace('(Взрослые)', '', $result['comp']) . " {$result['date']}</td>\n";
  }
  print "</tr>\n";
}
print "</tbody>\n";
}
print "<table class='tabstyle1'>\n";
print '<caption>Троеборье</caption>';
echo record_db($gender, 2, 'squat', $country);
echo record_db($gender, 2, 'brench', $country);
echo record_db($gender, 2, 'deadlift', $country);
echo record_db($gender, 2, 'total', $country);
print "</table>\n";
print "<table class='tabstyle1'>\n";
print '<caption>Классическое троеборье</caption>';
echo record_db($gender, 1, 'squat', $country);
echo record_db($gender, 1, 'brench', $country);
echo record_db($gender, 1, 'deadlift', $country);
echo record_db($gender, 1, 'total', $country);
print "</table>\n";
?>