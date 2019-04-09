<?php 
function av_wilks_fo($gender, $devizion, $region){
  $sql_w = mysql_query("
  SELECT  IF (COUNT(w.wilks) = 10, TRUNCATE( AVG( w.wilks ) , 2 ), ' < 10 спортсменов ') as wilks
  FROM  (
  SELECT max( wilks ) as wilks FROM " . 
    TABLEATHLETE . " AS a,  " . 
    TABLECOMPETITION . " as c
  where a.athlete_id = c.athlete_id
  and devizion = '{$devizion}' 
  AND a.gender = '{$gender}'
  AND a.fo =  '{$region}'
  GROUP BY a.athlete_id
  ORDER BY wilks DESC LIMIT 10) AS w
  ");
  $sql_w = mysql_fetch_assoc($sql_w);
  return $sql_w['wilks'];
}


function topfo($g, $d){
  if ($g == 1) $title = 'Женщины. '; else $title = 'Мужчины. ';
  if ($d == 1) $title .= 'Классическое троеборье<br />'; else $title .= 'Троеборье<br />';
  $fo = array(
  'Центральный федеральный округ' => av_wilks_fo($g, $d, 'ЦФО'), 
  'Северо-Западный  федеральный округ' => av_wilks_fo($g, $d, 'СЗФО'),
  'Сибирский федеральный округ' => av_wilks_fo($g, $d, 'СФО'), 
  'Уральский федеральный округ' => av_wilks_fo($g, $d, 'УФО'), 
  'Дальневосточный федеральный округ' => av_wilks_fo($g, $d, 'ДВФО'), 
  'Приволжский федеральный округ' => av_wilks_fo($g, $d, 'ПФО'),
  "Южный федеральный округ" => av_wilks_fo($g, $d, "ЮФО"), 
  "Северо-Кавказский федеральный округ" => av_wilks_fo($g, $d, 'СКФО'),
//  "Крымский федеральный округ" => av_wilks_fo($g, $d, 'КФО')
  );
  arsort($fo);
  //echo '<table>';
  echo "<tr><th colspan=2>$title</th></tr>\n";
  foreach ($fo as $k=>$v){
    echo "<tr><td>{$k}</td><td>{$v}</td></tr>\n";
  }
  //echo '</table>';
}
echo "<table class=tabstyle1>\n";
echo '<caption>Топ федеральных округов Российской Федерации</caption>';
echo '<tr><th>Федеральный округ</th><th>к. Вилкса</th></tr>';
echo "<tr><td>".topfo(1, 2)."</td><td>".topfo(2, 2)."</td></tr>\n";
echo "<tr><td>".topfo(1, 1)."</td><td>".topfo(2, 1)."</td></tr>\n";
echo "</table>\n";
?>

