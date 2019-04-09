<?php
# Файл используется для: отдельных стран постсоветского пространства. 
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/style.php';

// функция определение среднего Вилкс у топ 10
function av_wilks_ussr($gender, $devizion, $country){
  $sql_w = "
  SELECT  IF (COUNT(w.wilks) = 10, TRUNCATE( AVG( w.wilks ) , 2 ), ' < 10 спортсменов ') as wilks
  FROM  (
  SELECT max( wilks ) as wilks FROM " . TABLEATHLETE . " AS a,  " . TABLECOMPETITION . " as c
  where a.athlete_id = c.athlete_id ";
  if(FALSE != $country) {$sql_w .= " and country IN({$country}) ";}
  $sql_w .= " 
  and devizion = '{$devizion}' 
  AND a.gender = '{$gender}'
  GROUP BY a.athlete_id
  ORDER BY wilks DESC LIMIT 10) AS w
  ";
  $sql_w = mysql_query($sql_w);
  $sql_w = mysql_fetch_assoc($sql_w);
  return $sql_w['wilks'];
}

// функция определения заголовков th в таблице топ 10 (top10wilks, top_100_wilks_and_total)
function table_th_ussr_10($gender, $devizion){
if($gender  == 1) $hr = 'Женщины. '; else $hr = 'Мужчины. ';
if($devizion == 1) $hr .= 'Классическое троеборье'; else $hr .= 'Троеборье';
return "<tr><th colspan='6'>{$hr}</th></tr>\n";
}


// функция выбирающая основные данные в таблицу Топ 10
function output_top($gender, $devizion, $country, $i = 0){
#запрос athlete_id спортсменов
$sql_athlete = "
  SELECT DISTINCT a.athlete_id, max(wilks) as wilks, gender, devizion
  FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c  
  WHERE a.athlete_id = c.athlete_id ";
  if(FALSE != $country) {$sql_athlete .= " and country IN({$country}) ";}
  $sql_athlete .= "
  AND gender = $gender 
  and devizion = $devizion
  GROUP BY a.athlete_id 
  ORDER BY wilks DESC
  LIMIT 0, 100";
$sql_athlete = mysql_query($sql_athlete); 
if(!$sql_athlete) exit(mysql_error());
# вывод таблицы

echo table_th_ussr_10($gender, $devizion);
while($athlete = mysql_fetch_assoc($sql_athlete)) {
  #запрос максимального wllks у спортсмена с $athlete_id
  $sql_mwilks = mysql_query(
    "SELECT DISTINCT a.* , c.*, cts.city AS city, cts.region AS region,
      CONCAT(nc.name_ru,  ac.name_ru) as comp, c.id AS competition_id
    FROM " . TABLEATHLETE . " AS a
      INNER JOIN " . TABLECOMPETITION . " AS c      ON a.athlete_id = c.athlete_id
      INNER JOIN " . TABLECOMPETITIONNAME . " AS nc ON c.competition = nc.id
      INNER JOIN " . TABLECATEGORYAGE . " AS ac     ON c.age_category = ac.id
      INNER JOIN cities AS cts                      ON a.city = cts.id
	  AND cts.country_id = 2
    AND devizion = $devizion 
    AND gender = $gender 
    AND c.athlete_id = {$athlete['athlete_id']}
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
    echo "<td>" . ++$i . ".</td>";
    echo "<td><a href='/athlete/{$athlete_mwilks['athlete_id']}'>{$athlete_mwilks['name']}</a></td>";
    echo "<td>{$athlete_mwilks['age']}</td>";
    echo "<td>{$athlete_mwilks['region']} <br/> {$athlete_mwilks['city']}</td>";
    echo "<td><strong>{$athlete_mwilks['wilks']}</strong><br />{$athlete_mwilks['total']} / {$athlete_mwilks['weight']}</td>";
    echo '<td>'  . str_replace('(Взрослые)', '', $athlete_mwilks['comp']) . ' '  
                            . $athlete_mwilks['date'] . '</td>';
  }
  print '</tr>';
}
}
//вывод в браузер среднего Вилкс у топ 10 РФ
if (FALSE === $country) { $caption = 'постсоветское пространство'; }
else { $caption = $country; }
print '<table class=tabstyle1>'."\n";
print '<caption>Средний коэффициент топ 10  ' . $caption . '</caption>'."\n";
print '<tr><td> </td><td>Троеборье</td><td>Классическое троеборье</td></tr>'."\n";
echo '<tr><th>Женщины</th>
<td>', av_wilks_ussr(1, 2, $country), '</td><td>'.av_wilks_ussr(1, 1, $country).'</td></tr>'."\n";
echo '<tr><th>Мужчины</th>
<td>', av_wilks_ussr(2, 2, $country), '</td><td>'.av_wilks_ussr(2, 1, $country).'</td></tr>'."\n";
print '</table>'."\n";




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
		
		
		
//вывод в браузер топ 10 РФ
print '<table class=tabstyle1>';
echo "<tr><td colspan=6>{$AdSenseAdaptive}</td></tr>";
//echo '<tr><td><table>' . output_top(1, 2, $country) . '</table></td><td><table>' . output_top(2, 2, $country) . '</table></td></tr>';
echo output_top(1, 2, $country)."\n";
echo "<tr><td colspan=6>{$AdSenseAdaptive}</td></tr>";
echo output_top(2, 2, $country)."\n";
echo output_top(1, 1, $country)."\n";
echo output_top(2, 1, $country)."\n";
print '</table>';
?>