<?php
# Рекорды Соревнований. Эталон "рекорды ФО и региона"
require_once 'sites/all/rating/config.php';
require_once 'sites/all/rating/rf/model.php'; 
require_once 'sites/all/rating/style.php';



function CompetitionRecord($gender, $devizion, $discipline, $competition){
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
    $array[$c] = "
    SELECT  w.*
    FROM  
    (
    SELECT a.*, c.date,
       IF(a.name is null, 'Непонятно', a.name),
    TRUNCATE(c.{$discipline}, 1)  AS {$discipline}, 
    TRUNCATE(c.wilks, 2) AS wilks, TRUNCATE(c.weight, 2) AS weight,
    CONCAT(nc.name_ru, ' (', ac.name_ru, ')') as comp
    FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c, " . 
      TABLECOMPETITIONNAME . " as nc, " . TABLECATEGORYAGE . " as ac
    WHERE a.athlete_id = c.athlete_id 
      and c.competition = nc.id
      and c.age_category = ac.id  
    ";
    $array[$c] .= "
    AND c.competition IN({$competition})
    AND c.devizion = {$devizion}
    AND a.gender = {$gender}
    AND c.weight between {$k} AND {$v}
    -- в 2011 год IPF изменила категории.
    AND c.date >= 2011
    -- в случае одинакового результата 
    -- выводится спортсмен с меньшим весом и старшим (по году) результатом.
    ORDER BY weight, c.date
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



echo "<tr><th colspan='9'>{$hr_gender}. {$hr_discipline}</th></tr>";
foreach ($array as $k => $v) 
{
  $v = mysql_query($v);
  if (mysql_num_rows($v) === 0)
  {
    echo "<td>{$k}</td><td colspan='8'>Требуется талант!<br />
    Для связи с администрацией выделите строку и нажмите &lt;Ctrl> + &lt;Enter>, 
    либо воспользутесь \"<a href='/contact'>Обратной связью</a>\"</td>";
  }
  print "<tr>";
  while ($result = mysql_fetch_assoc($v))
  {
    $result['comp'] = NameComptition($result['comp']);
    echo "<td>{$k}</td>"."\n";
    echo "<td>{$result['name']}</td> \n";
    echo "<td>{$result['age']}</td>\n";
    if (FALSE === ($fo or $region)) {echo "<td>{$result['fo']}</td>\n";}
    echo "<td class='sm'>{$result['subject_rf']}</td>\n";
    echo "<td class='sm'>{$result['city']}</td>\n";
//    echo "<td>{$result['weight']}</td>\n";
    echo "<td>{$result[$discipline]}</td>\n";
    echo "<td class='sm'> {$result['comp']} </td>\n";
    echo "<td class='sm'> {$result['date']} </td>\n";
  }
  print "</tr>\n";
}
print "</tbody>\n";
}
print "<table class='tabstyle1'>\n";
print '<caption>Троеборье</caption>';
echo CompetitionRecord($gender, 2, 'total', $competition);
echo CompetitionRecord($gender, 2, 'squat', $competition);
echo CompetitionRecord($gender, 2, 'brench', $competition);
echo CompetitionRecord($gender, 2, 'deadlift', $competition);
print "</table>\n";


if (!isset($FlagDevizion)) {
  print "<table class='tabstyle1'>\n";
  print '<caption>Классическое троеборье</caption>';
  echo CompetitionRecord($gender, 1, 'total', $competition);
  echo CompetitionRecord($gender, 1, 'squat', $competition);
  echo CompetitionRecord($gender, 1, 'brench', $competition);
  echo CompetitionRecord($gender, 1, 'deadlift', $competition);
  print "</table>\n";
}
?>