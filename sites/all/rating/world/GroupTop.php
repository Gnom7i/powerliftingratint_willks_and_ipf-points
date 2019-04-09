<?php
# Файл используется для: частей света. (Slavic, Nordic country)
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/style.php';
require_once 'sites/all/rating/function.ini.php';


//функция определения среднего Вилкс у групп стран (Славянские государства, Северные страны и т.д.)
function av_wilks_group($gender, $devizion, $CountryGroup){
  $sql_w = "
  SELECT IF (COUNT(w.wilks) = 10, TRUNCATE(AVG(w.wilks) , 2), ' < 10 athlete ') AS wilks 
  FROM  (
  SELECT MAX(wilks) AS wilks FROM " . 
    TABLEATHLETE . " AS a,  " . 
    TABLECOMPETITION . " AS c, " . 
    TABLECOUNTRY . " AS cntr, country_{$CountryGroup} AS {$CountryGroup} 
  WHERE a.athlete_id = c.athlete_id 
  AND a.athlete_id = c.athlete_id 
  AND cntr.iso = a.country 
  AND cntr.iso = {$CountryGroup}.iso
  AND devizion = '{$devizion}' 
  AND a.gender = '{$gender}'
  GROUP BY a.athlete_id
  ORDER BY wilks DESC LIMIT 10) AS w
  ";
  $sql_w = mysql_query($sql_w) or die(mysql_error());
    $sql_w = mysql_fetch_assoc($sql_w);
    return $sql_w['wilks'];
}


// функция выбирающая основные данные в таблицу Топ 10
function output_top($CountryGroup, $gender, $devizion, $i = 0){
#запрос athlete_id спортсменов
$sql_athlete = "
  SELECT DISTINCT a.athlete_id, MAX(wilks) AS wilks, gender, devizion
  FROM " . TABLEATHLETE . " AS a, " . TABLECOMPETITION . " AS c, 
    country AS cntr,
    country_{$CountryGroup} AS {$CountryGroup}  
  WHERE a.athlete_id = c.athlete_id 
  AND a.athlete_id = c.athlete_id 
  AND cntr.iso = a.country
  AND cntr.iso = {$CountryGroup}.iso
  AND gender = $gender 
  AND devizion = $devizion
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
    "SELECT DISTINCT a.* , c.*,
    TRUNCATE(weight, 2) AS weight, TRUNCATE ( total, 1 ) AS total, TRUNCATE ( wilks, 2 ) AS wilks, 
      CONCAT ( nc.name_en, ' (', ac.name_en, ')' ) AS comp
    FROM " . TABLEATHLETE . " AS a, " . TABLECOMPETITION . " AS c, " . 
      TABLECOMPETITIONNAME . " AS nc, " . TABLECATEGORYAGE . " AS ac
    WHERE a.athlete_id = c.athlete_id 
      AND c.competition = nc.id
      AND c.age_category = ac.id
	  AND devizion = $devizion AND gender = $gender AND c.athlete_id = {$athlete['athlete_id']}
    AND wilks IN 
	   (SELECT MAX(wilks) AS bestwilks FROM " . TABLECOMPETITION . " AS c 
      WHERE c.athlete_id = {$athlete['athlete_id']} 
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
    echo '<td>' . get_in_translate_to_en(htmlspecialchars($athlete_mwilks['name'], ENT_QUOTES, 'UTF-8')) . '</td>';
    print '<td class="sm">' . $athlete_mwilks['age'] . '</td>';
    // Название страны
    $sql_code = mysql_query("SELECT * FROM country 
                                WHERE iso = '" . $athlete_mwilks['country'] . "'");
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
print '<caption>Average w.points top 10  ' . $CountryGroup . ' </caption>'."\n";
print '<tr><td> </td><td>Powerlifting</td><td>Classic powerlifting</td></tr>'."\n";
echo '
<tr>
  <th>Women</th>
  <td>' . av_wilks_group(1, 2, $CountryGroup) . '</td>
  <td>' . av_wilks_group(1, 1, $CountryGroup) . '</td>
</tr>'."\n";
echo 
'<tr><th>Men</th>
  <td>' . av_wilks_group(2, 2, $CountryGroup) . '</td>
  <td>' . av_wilks_group(2, 1, $CountryGroup) . '</td>
</tr>'."\n";
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
echo output_top($CountryGroup, 1, 2)."\n";
echo output_top($CountryGroup, 2, 2)."\n";
echo output_top($CountryGroup, 1, 1)."\n";
echo output_top($CountryGroup, 2, 1)."\n";
print '</table>';
?>