<?php 
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/rf/model.php';
require_once 'sites/all/rating/style.php';


require_once 'sites/all/rating/function.ini.php';



$country = 643;
// функция формирования HTML таблицы
function output_top($gender, $devizion, $country, $i = 0){
#запрос athlete_id спортсменов
$sql_athlete = mysql_query("
  SELECT DISTINCT a.athlete_id, max(wilks) as wilks, gender, devizion
  FROM ".TABLEATHLETE." as a, ".TABLECOMPETITION." as c  
  WHERE a.athlete_id = c.athlete_id 
  AND country = '{$country}'
  AND gender = $gender and devizion = $devizion
  AND date = 2016
  GROUP BY a.athlete_id 
  ORDER BY wilks DESC
  LIMIT 0, 10"
); 
if(!$sql_athlete) exit(mysql_error());
# вывод таблицы
print "\n".'<tbody>'."\n";
//echo table_th10_ru($gender, $devizion);
if ($gender === 1) $thG = 'Women'; 
	else $thG = 'Men';
if ($devizion === 1) $thD = 'Classic'; 
	else $thD = 'EQ';
echo "<tr><th colspan=6>{$thG} {$thD}</th></td>";
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
    AND country = '{$country}'
	  AND devizion = $devizion 
    AND gender = $gender 
    AND c.athlete_id = {$athlete['athlete_id']}
    AND wilks in 
	   (select max(wilks) as bestwilks FROM " . TABLECOMPETITION . " 
     where ".TABLECOMPETITION.".athlete_id = {$athlete['athlete_id']} 
     AND country = '{$country}'
     AND date = 2016
     AND devizion = $devizion 
     AND gender = $gender 
     ORDER BY bestwilks DESC)
    ORDER BY wilks DESC
	"
  );
  if (!$sql_mwilks) exit(mysql_error());
  print '<tr>';
  for ( ; $athlete_mwilks = mysql_fetch_assoc($sql_mwilks);  ) { 
    $athlete_mwilks = NameComptition($athlete_mwilks);
    print '<td class="sm">' . ++$i . '.</td>';
    print '<td class="name">' . $athlete_mwilks['name'] . '</td>';
    print '<td class="sm">' . $athlete_mwilks['age'] . '</td>';
    print '<td class="sm">' . $athlete_mwilks['subject_rf'] .'<br />' . $athlete_mwilks['city'] .'</td>';
    //print '<td class="sm">' . '' . '</td>';
    //print '<td class="sm">' . '' . '</td>';
    print '<td class="sm"><strong>' . $athlete_mwilks['wilks'] . '</strong></td>';
    //print '<td class="sm"> </td>';
    //print '<td class="sm"> </td>';
    print '<td class="sm">' .  $athlete_mwilks['comp'] .  '</td>';
    
  }
  print '</tr>';
}
print '</tbody>'."\n";
}


print '<table class="tabstyle1" style="">';
print "<thead><tr><td align='center' colspan='6'>Сильнейшие спортсмены Российской Федерации 
                                  за <b>2016</b> год</td></tr></thead>";
print "<tfoot><tr><td align='center' colspan='6'><a href='/top_10_RF'>
                                  Сильнейшие спортсмены Российской Федерации 
                                  за все время</a></td></tr></tfoot>";

echo output_top(1, 2, $country)."\n";
echo output_top(2, 2, $country)."\n";
echo output_top(1, 1, $country)."\n";
echo output_top(2, 1, $country)."\n";
print '</table>';




print '<p /><p /><p />';

//print '<table>';
//echo '<tr><td><table>' . output_top(1, 2, $country) . '</table></td><td><table>' . output_top(2, 2, $country) . '<table></td></tr>';
//print '</table>';
 



// функция определения заголовков th в таблице топ 10
function table_th_world($gender, $devizion){
if($gender  == 1) $hr = 'Women. '; else $hr = 'Men. ';
if($devizion == 1) $hr .= 'Classic poweilifting'; else $hr .= 'Powerlifting';
return "<tr><th colspan='8'>{$hr}</th>
</tr>\n";
}
// функция выбирающая основные данные в таблицу Топ 10
function output_top_world($gender, $devizion, $n = 0){
#запрос athlete_id спортсменов
$sql_athlete = "
  SELECT DISTINCT a.athlete_id, max(wilks) as wilks, gender, devizion
  FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c  
  WHERE a.athlete_id = c.athlete_id 
  AND gender = $gender 
  and devizion = $devizion
  AND date = 2016
  GROUP BY a.athlete_id 
  ORDER BY wilks DESC
  LIMIT 0, 10";
$sql_athlete = mysql_query($sql_athlete); 
if(!$sql_athlete) exit(mysql_error());
# вывод таблицы
print "\n".'<tbody>'."\n";
echo table_th_world($gender, $devizion);
while($athlete = mysql_fetch_assoc($sql_athlete)) {
  #запрос максимального wllks у спортсмена с $athlete_id
  $sql_mwilks = mysql_query(
    "SELECT DISTINCT a.* , c.*,
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
      AND date = 2016
      AND devizion = $devizion 
      AND gender = $gender 
      ORDER BY bestwilks DESC)
    ORDER BY wilks DESC
	"
  );
  if(!$sql_mwilks) exit(mysql_error());
  print '<tr>';
  for ( ; $athlete_mwilks = mysql_fetch_assoc($sql_mwilks); ) { 
    
    echo '<td class="sm">'. ++$n .'.</td>';
    echo '<td class="name">' . get_in_translate_to_en($athlete_mwilks['name']) . '</td>';
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
  }
  print '</tr>';
}
print '</tbody>'."\n";
}
print '<table class="tabstyle1" style="">';
print "<thead><tr><td  align='center' colspan='8'>Сильнейшие спортсмены мира 
                                  за <b>2016</b> год</td></tr></thead>";
print "<tfoot><tr><td  align='center' colspan='8'><a href='/World/top_10'>Сильнейшие спортсмены мира 
                                  за все время</a></td></tr></tfoot>";
echo output_top_world(1, 2)."\n";
echo output_top_world(2, 2)."\n";
echo output_top_world(1, 1)."\n";
echo output_top_world(2, 1)."\n";
print '</table>';


/*
*
*
*
*
*/




































?>