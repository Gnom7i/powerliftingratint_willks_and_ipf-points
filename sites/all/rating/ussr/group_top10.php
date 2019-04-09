<?php
# Файл используется для груп стран постсоветского пространства. 
# постсоветское пространство: 440, 860, 233, 428, 112, 398, 804, 643, 268, 051, 031
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/style.php';

// функция определение среднего Вилкс у топ 10
function av_wilks_group_country($gender, $devizion, $country){
  $sql_w = "
  SELECT  IF (COUNT(w.wilks) = 10, TRUNCATE( AVG( w.wilks ) , 2 ), ' < 10 спортсменов ') as wilks
  FROM  (
  SELECT max( wilks ) as wilks FROM " . TABLEATHLETE . " AS a,  " . TABLECOMPETITION . " as c
  where a.athlete_id = c.athlete_id  
  and country IN({$country})  
  and devizion = '{$devizion}' 
  AND a.gender = '{$gender}'
  GROUP BY a.athlete_id
  ORDER BY wilks DESC LIMIT 10) AS w
  ";
  $sql_w = mysql_query($sql_w);
  $sql_w = mysql_fetch_assoc($sql_w);
  return $sql_w['wilks'];
}
// функция определения заголовков th в таблице топ 10
function table_th_ussr_group_th11($gender, $devizion){
if($gender  == 1) $hr = 'Женщины. '; else $hr = 'Мужчины. ';
if($devizion == 1) $hr .= 'Классическое троеборье'; else $hr .= 'Троеборье';
return "<tr><th colspan='11'>{$hr}</th>
</tr>\n";
}
// функция выбирающая основные данные в таблицу Топ 10
function output_top($gender, $devizion, $country, $i = 0){
#запрос athlete_id спортсменов
$sql_athlete = "
  SELECT DISTINCT a.athlete_id, max(wilks) as wilks, gender, devizion
  FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c  
  WHERE a.athlete_id = c.athlete_id
  and country IN({$country})
  AND gender = $gender 
  and devizion = $devizion
  GROUP BY a.athlete_id 
  ORDER BY wilks DESC
  LIMIT 0, 10";
$sql_athlete = mysql_query($sql_athlete); 
if(!$sql_athlete) exit(mysql_error());
# вывод таблицы
print "\n".'<tbody>'."\n";
echo table_th_ussr_group_th11($gender, $devizion);
while($athlete = mysql_fetch_assoc($sql_athlete)) {
  #запрос максимального wllks у спортсмена с $athlete_id
  $sql_mwilks = mysql_query(
    "SELECT DISTINCT a.* , c.*,
    TRUNCATE(weight, 2) AS weight, TRUNCATE(total, 1) AS total, TRUNCATE(wilks, 2) AS wilks, 
      CONCAT(nc.name_ru, ' (', ac.name_ru, ')') as comp
    FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c, " . 
      TABLECOMPETITIONNAME . " as nc, " . TABLECATEGORYAGE . " as ac
    WHERE a.athlete_id = c.athlete_id 
      and c.competition = nc.id
      and c.age_category = ac.id
	  AND devizion = $devizion AND gender = $gender AND c.athlete_id = {$athlete['athlete_id']}
    AND wilks in 
	   (select max(wilks) as bestwilks from " . TABLECOMPETITION . " as c 
      where c.athlete_id = {$athlete['athlete_id']} 
      AND devizion = $devizion 
      AND gender = $gender 
      ORDER BY bestwilks DESC)
    ORDER BY wilks DESC
	"
  );
  if(!$sql_mwilks) exit(mysql_error());
  print '<tr>';
  while($athlete_mwilks = mysql_fetch_assoc($sql_mwilks)) { 
    print '<td class="sm">'.++$i.'.</td>';
    print '<td>' . $athlete_mwilks['name'] . '</td>';
    print '<td class="sm">' . $athlete_mwilks['age'] . '</td>';
    // Название страны
    $sql_code = mysql_query("select * from country 
                                where iso = '" . $athlete_mwilks['country'] . "'");
    $sql_code = mysql_fetch_assoc($sql_code);
    print '<td class="sm"><strong>' . $sql_code['abbreviated_name'] . '</strong></td>'; 
    // Название субъекта
    print '<td class="sm">' . $athlete_mwilks['subject_rf'] . '</td>';
    // Название города
    print '<td class="sm">' . $athlete_mwilks['city'] . '</td>';
    print '<td><strong>' . $athlete_mwilks['wilks'] . '</strong></td>';
    print '<td class="sm">' . $athlete_mwilks['total'] . '</td>';
    print '<td class="sm">' . $athlete_mwilks['weight'] . '</td>';
    echo '<td class="sm">' . str_replace('(Взрослые)', '', $athlete_mwilks['comp']) .  '</td>';
    print '<td class="sm">' . $athlete_mwilks['date'] . '</td>';
  }
  print '</tr>';
}
print '</tbody>'."\n";
}
//вывод в браузер среднего Вилкс у топ 10 РФ
if (FALSE === $country) { $caption = 'постсоветское пространство'; }
else { $caption = $country; }
print '<table class=tabstyle1>'."\n";
print '<caption>Средний коэффициент топ 10  <br />' . $caption . '</caption>'."\n";
print '<tr><td> </td><td>Троеборье</td><td>Классическое троеборье</td></tr>'."\n";
echo '<tr><th>Женщины</th>
<td>', av_wilks_group_country(1, 2, $country), '</td><td>'.av_wilks_group_country(1, 1, $country).'</td></tr>'."\n";
echo '<tr><th>Мужчины</th>
<td>', av_wilks_group_country(2, 2, $country), '</td><td>'.av_wilks_group_country(2, 1, $country).'</td></tr>'."\n";
print '</table>'."\n";
//вывод в браузер топ 10 РФ
print '<table class=tabstyle1>';
echo output_top(1, 2, $country)."\n";
echo output_top(2, 2, $country)."\n";
echo output_top(1, 1, $country)."\n";
echo output_top(2, 1, $country)."\n";
print '</table>';
?>