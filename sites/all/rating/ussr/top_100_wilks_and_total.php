<?php 
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/style.php';
// функция определениея th HTML таблицы
function table_th_ussr_total_th10($gender, $devizion)
{
  if ($gender  == 1)  { $hr = 'Женщины'; }
  if ($gender  == 2)  { $hr = 'Мужчины'; }
  //if($devizion == 1) $hr .= 'Классическое троеборье'; else $hr .= 'Троеборье';
  return "<tr><th colspan='10'>{$hr}</th></tr>\n";
}
// РАНЖИРУЕТ по {$discipline} - начаало
function output_1000($gender, $devizion, $discipline, $country, $i = 0)
{
  #запрос athlete_id спортсменов
  $sql_id = "
  SELECT DISTINCT a.athlete_id, max({$discipline}) as {$discipline}
  FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c
  WHERE a.athlete_id = c.athlete_id 
  AND country IN(440, 860, 233, 428, 112, 398, 804, 643, 268, 051, 031)
  AND gender = $gender and devizion = $devizion";
  if ($discipline === 'total') { $sql_id .=  ' AND total >= 1000 '; }
  if ($discipline === 'wilks') { $sql_id .=  ' AND wilks >= 600 '; }
  $sql_id .= "
  GROUP BY a.athlete_id 
  ORDER BY {$discipline} DESC, weight 
  LIMIT 0, 100";
  $sql_id = mysql_query($sql_id) or die(mysql_error());
  # вывод таблицы
  print "\n".'<tbody>'."\n";
  echo table_th_ussr_total_th10($gender, $devizion);
  while($athlete = mysql_fetch_assoc($sql_id)) 
  {
    #запрос максимального {$discipline} у спортсмена с $athlete_id
    $query_max_result = 
    "SELECT DISTINCT *, {$discipline}, 
    TRUNCATE(weight, 2) as weight, TRUNCATE(wilks, 2) as wilks, TRUNCATE(total, 1) as total,
    CONCAT(nc.name_ru, ' (', ac.name_ru, ')') as comp
    FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c, " . 
      TABLECOMPETITIONNAME . " as nc, " . TABLECATEGORYAGE . " as ac
    WHERE a.athlete_id = c.athlete_id 
      and c.competition = nc.id
      and c.age_category = ac.id
	  AND c.athlete_id = {$athlete['athlete_id']}
    ";
    if ($discipline === 'total') { $query_max_result .=  ' AND total >= 1000 '; }
    if ($discipline === 'wilks') { $query_max_result .=  ' AND wilks >= 600 '; }
    $query_max_result .= "ORDER BY {$discipline} DESC, weight LIMIT 1";
    $query_max_result = mysql_query($query_max_result) or die(mysql_error());
    print '<tr>';
    while($row_result = mysql_fetch_assoc($query_max_result)) 
    { 
      print '<td>'.++$i.'.</td>';
      print '<td class="name">' . $row_result['name'] . '</td>';
      print '<td>' . $row_result['age'] . '</td>';
      
      $sql_code = mysql_query("select name as country from country where iso = '" . $row_result['country'] . "'");
      $sql_code = mysql_fetch_assoc($sql_code);
      if ($sql_code['country'] !== 'Российская Федерация') { 
        $sql_code['country'] = '<u>' . $sql_code['country'] . '</u>';
      }
      print "<td class='sm'><strong>" . $sql_code['country'] . "</strong></td>\n"; 
      print '<td class="sm">' . $row_result['subject_rf'] . '</td>';
      //print '<td class="sm">' . $row_result['city'] . '</td>';
      print '<td>' . $row_result['weight'] . '</td>';
      print '<td>' . $row_result['total'] . '</td>';
      print '<td>' . $row_result['wilks'] . '</td>';
      echo '<td class="sm">' . str_replace('(Взрослые)', '', $row_result['comp']) .  '</td>';
      print '<td class="sm">' . $row_result['date'] . '</td>';
    }
    print '</tr>';
  }
  print '</tbody>'."\n";
}
// РАНЖИРУЕТ по {$discipline} - конец
print '<table class=tabstyle1>';
echo output_1000($gender, $devizion,  $discipline, $country)."\n";
print '</table>';
?>