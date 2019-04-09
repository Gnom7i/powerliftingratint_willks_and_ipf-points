<?php
# Файл используется для: планеты. 
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/style.php';
require_once 'sites/all/rating/function.ini.php';


// функция выбирающая основные данные в таблицу Топ 10
function output_top($gender, $devizion, $i = 0){
#запрос athlete_id спортсменов
$sql_athlete = "
  SELECT DISTINCT a.athlete_id, max(wilks) as wilks, gender, devizion
  FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c  
  WHERE a.athlete_id = c.athlete_id 
  AND gender = $gender 
  and devizion = $devizion
  GROUP BY a.athlete_id 
  ORDER BY wilks DESC
  LIMIT 0, 10";
$sql_athlete = mysql_query($sql_athlete); 
if(!$sql_athlete) exit(mysql_error());
# вывод таблицы
print "\n".'<tbody>'."\n";
echo table_th9_en($gender, $devizion);
while($athlete = mysql_fetch_assoc($sql_athlete)) {
  #запрос максимального wllks у спортсмена с $athlete_id
  $sql_mwilks = mysql_query(
    "SELECT DISTINCT a.* , c.*, a.athlete_id AS athlete_id,
    TRUNCATE(weight, 2) AS weight, TRUNCATE(total, 1) AS total, TRUNCATE(wilks, 2) AS wilks, 
      CONCAT(nc.name_en, ' (', ac.name_en, ')') as comp
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
    echo "<td><a href='/athlete/{$athlete_mwilks['athlete_id']}'>" . get_in_translate_to_en($athlete_mwilks['name']) . '</a></td>';
    print '<td class="sm">' . $athlete_mwilks['age'] . '</td>';
    // Название страны
    $sql_code = mysql_query("select * from country 
                                where iso = '" . $athlete_mwilks['country'] . "'");
    $sql_code = mysql_fetch_assoc($sql_code);
    print '<td class="sm"><strong>' . $sql_code['english'] . '</strong></td>'; 
    print '<td><strong>' . $athlete_mwilks['wilks'] . '</strong></td>';
    print '<td class="sm">' . $athlete_mwilks['total'] . '</td>';
    print '<td class="sm">' . $athlete_mwilks['weight'] . '</td>';
    echo '<td class="sm">' . str_replace('(Open)', '', $athlete_mwilks['comp']) .  '</td>';
    print '<td class="sm">' . $athlete_mwilks['date'] . '</td>';
  }
  print '</tr>';
}
print '</tbody>'."\n";
}
//вывод в браузер среднего Вилкс у топ 10 РФ
print '<table class=tabstyle1>'."\n";
print '<caption>Average w.points top 10  World</caption>'."\n";
print '<tr><td> </td><td>Powerlifting</td><td>Classic powerlifting</td></tr>'."\n";
echo '<tr><th>Women</th>
<td>', av_wilks(1, 2), '</td><td>'.av_wilks(1, 1).'</td></tr>'."\n";
echo '<tr><th>Men</th>
<td>', av_wilks(2, 2), '</td><td>'.av_wilks(2, 1).'</td></tr>'."\n";
print '</table>'."\n";
//вывод в браузер топ 10 РФ
print '<table class=tabstyle1>';
print '
<tr>
	<td>#</td>
	<td class="sm">name</td>
	<td class="sm">age</td>
	<td class="sm">country</td>
	<td class="sm">w.points</td>
	<td class="sm">total</td>
	<td class="sm">weight</td>
	<td class="sm">competition</td>
	<td class="sm">year</td>
</tr>';
echo output_top(1, 2)."\n";
echo output_top(2, 2)."\n";
echo output_top(1, 1)."\n";
echo output_top(2, 1)."\n";
print '</table>';
?>