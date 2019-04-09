<?php 
require_once 'sites/all/rating/config.php';
require_once 'sites/all/rating/rf/model.php';
require_once 'sites/all/rating/style.php';

$AdSenseAdaptive = '
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- HEAD Rating -->
		<ins class="adsbygoogle"
			 style="display:block"
			 data-ad-client="ca-pub-3589435131109855"
			 data-ad-slot="5272531152"
			 data-ad-format="auto"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>';


# также используется для Украины http://powerliftingrating.ru/top_20_Ukraine_total_1000


// РАНЖИРУЕТ по Wilks - начало
// функция определениея th HTML таблицы
function table_th_total($gender, $devizion){
if($gender  == 2)  $hr = 'Мужчины. Сумма &ge; 1000 кг';
//if($devizion == 1) $hr .= 'Классическое троеборье'; else $hr .= 'Троеборье';
return "<tr><th colspan='10'>{$hr}</th>
</tr>\n";
}
// функция формирующая выборку и вывод HTML таблицы
function output_top_total($gender, $devizion, $country, $i = 0){
#запрос athlete_id спортсменов
$sql_athlete = mysql_query("
  SELECT DISTINCT a.athlete_id, max(wilks) as wilks, gender, devizion
  FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c  
  WHERE a.athlete_id = c.athlete_id 
  AND country = '{$country}'
  AND gender = $gender and devizion = $devizion
  AND total >= 1000
  GROUP BY a.athlete_id 
  ORDER BY wilks DESC
  LIMIT 0, 50"
); 
if(!$sql_athlete) exit(mysql_error());
# вывод таблицы
print '<table>';
print "<tr><th colspan='10'>Сумма &ge; 1000 кг. Сортировка по Wilks</th></tr>";
while($athlete = mysql_fetch_assoc($sql_athlete)) {
  #запрос максимального wllks у спортсмена с $athlete_id
  $sql_mwilks = mysql_query(
    "SELECT DISTINCT * , a.athlete_id AS athlete_id, 
    TRUNCATE(weight, 2) AS weight, TRUNCATE(total, 1) AS total, TRUNCATE(wilks, 2) AS wilks, 
      CONCAT(nc.name_ru, ac.name_ru) as comp
    FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c , " . 
      TABLECOMPETITIONNAME . " as nc, " . TABLECATEGORYAGE . " as ac 
    WHERE a.athlete_id = c.athlete_id 
      and c.competition = nc.id
      and c.age_category = ac.id
    AND country = '{$country}'
    AND devizion = $devizion AND gender = $gender 
    AND c.athlete_id = {$athlete['athlete_id']}
    AND wilks in 
      (select max(wilks) as bestwilks from " . TABLECOMPETITION . " as c
      where c.athlete_id = {$athlete['athlete_id']} 
      AND country = '{$country}'
      AND devizion = $devizion 
      AND gender = $gender 
      ORDER BY bestwilks DESC)
    ORDER BY wilks DESC
	"
  );
  if(!$sql_mwilks) exit(mysql_error());
  print '<tr>';
  while($athlete_mwilks = mysql_fetch_assoc($sql_mwilks)) { 
    $athlete_mwilks['comp'] = NameComptition($athlete_mwilks['comp']);
    print "<td>" . ++$i . ". <a href='/athlete/{$athlete_mwilks['athlete_id']}'>{$athlete_mwilks['name']}</a></td>"; 
	print '
    <td>' . $athlete_mwilks['age'] . '</td>
    <td class="sm">' . $athlete_mwilks['subject_rf'] . '</td>  
    <td><strong>' . $athlete_mwilks['wilks'] . '</strong> <br/>' . $athlete_mwilks['total'] . '<br/>' . $athlete_mwilks['weight'] .'</td>
    <td class="sm">' .$athlete_mwilks['comp'] . '<br/>' . $athlete_mwilks['date'] .'</td>';
  }
  echo "</tr>";
}
print '</table>';
}
// РАНЖИРУЕТ по Wilks - конец






// РАНЖИРУЕТ по Total - начаало
function output_1000($gender, $devizion, $country, $i = 0){
#запрос athlete_id спортсменов
$sql_athlete = mysql_query("
  SELECT DISTINCT a.athlete_id, max(total) as total
  FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c
  WHERE a.athlete_id = c.athlete_id 
--  AND gender = $gender and devizion = $devizion
  AND country = '{$country}'
  AND total >= 1000
  GROUP BY a.athlete_id 
  ORDER BY total DESC, weight 
  LIMIT 0, 50"
); 
if(!$sql_athlete) exit(mysql_error());
# вывод таблицы
print '<table>';
print "<tr><th colspan='10'>Сумма &ge; 1000 кг. Сортировка по кг</th></tr>";
while($athlete = mysql_fetch_assoc($sql_athlete)) {
  #запрос максимального total у спортсмена с $athlete_id
  $sql_mwilks = mysql_query(
    "SELECT DISTINCT 
      a.*, c.*, c.athlete_id, TRUNCATE(weight, 2) AS weight, 
      TRUNCATE(total, 1) AS total, 
      CONCAT(nc.name_ru,  ac.name_ru) as comp      
    FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c, " . 
      TABLECOMPETITIONNAME . " as nc, " . TABLECATEGORYAGE . " as ac      
    WHERE a.athlete_id = c.athlete_id 
      and c.competition = nc.id
      and c.age_category = ac.id
    AND country = '{$country}'
	  AND c.athlete_id = {$athlete['athlete_id']}
    ORDER BY total DESC, weight
    LIMIT 1
	"
  ) or die("Ошибка в запросе: ". mysql_error());
  if(!$sql_mwilks) exit(mysql_error());
  print '<tr>';
  while($athlete_mwilks = mysql_fetch_assoc($sql_mwilks)) { 
    $athlete_mwilks['comp'] = NameComptition($athlete_mwilks['comp']);
    print "<td>" . ++$i . ". <a href='/athlete/{$athlete_mwilks['athlete_id']}'>{$athlete_mwilks['name']}</a></td>";
    print '<td>' . $athlete_mwilks['age'] . '</td>
    <td class="sm">' . $athlete_mwilks['subject_rf'] . '</td>
    <td><strong>' . $athlete_mwilks['total'] . '</strong> <br/>' . $athlete_mwilks['wilks'] . '<br/>' . $athlete_mwilks['weight'] .'</td>
    <td class="sm">' . $athlete_mwilks['comp'] . '<br/>' . $athlete_mwilks['date'] .'</td>';
  }
  print '</tr>';
}
print '</table>';
}
// РАНЖИРУЕТ по Total - конец



print '<table class="MyTable">';
print "<tr><td colspan='2'>{$AdSenseAdaptive}</td></tr>";
print '<tr><td>';
output_1000(2, 2, $country);
print '</td>';
print '<td>';
output_top_total(2, 2, $country);
print '</td></tr>';
print "<tr><td colspan='2'>{$AdSenseAdaptive}</td></tr>";
print '</table>';
?>