<?php
# Рекорды России, Федеральных округов
require_once 'sites/all/rating/config.php';
require_once 'sites/all/rating/rf/model.php'; 
require_once 'sites/all/rating/style.php';



if (!isset($fo)) {$fo = FALSE;}
if (!isset($region)) {$region = FALSE;}
function record_db($gender, $devizion, $discipline, $fo, $region){
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
		SELECT a.*, 
			c.{$discipline}, CONCAT ( LEFT ( nc.name_ru, 22 ), ac.name_ru, ' `', RIGHT( c.date, 2 ) ) AS comp  
		FROM rating_dvfo_athlete AS a
			INNER JOIN competition AS c USING (athlete_id)
			INNER JOIN name_competition AS nc ON  c.competition = nc.id
			INNER JOIN age_category AS ac ON c.age_category = ac.id  
		WHERE  country IN(643)
			AND c.weight BETWEEN {$k} AND {$v}
			AND c.devizion = {$devizion}
			AND a.gender = {$gender}
			AND c.discipline = 'powerlifting'
			-- в 2011 год IPF изменила категории.
			AND c.date >= 2011 ";
			if (FALSE !== $fo) {$array[$c] .= " AND fo = '{$fo}' ";}
			if (FALSE !== $region) {$array[$c] .= " AND subject_rf = '{$region}' ";}
		$array[$c] .= "	
		-- в случае одинакового результата 
		-- выводится спортсмен с меньшим весом и старшим (по году) результатом.
		ORDER BY {$discipline} DESC, date, weight
		LIMIT 1";
  }
}




if ($gender == 1) {$hr_gender = 'Женщины';}
if ($gender == 2) {$hr_gender = 'Мужчины';}
if ($discipline == 'total') $hr_discipline = 'Сумма';
if ($discipline == 'squat') $hr_discipline = 'Приседание';
if ($discipline == 'brench') $hr_discipline = 'Жим лежа';
if ($discipline == 'deadlift') $hr_discipline = 'Становая тяга';
// Размер заголовка таблицы
if (FALSE === ($fo or $region)) { $colspan = 7; } 
else { $colspan = 6; }


echo "<tr><td colspan='{$colspan}' style='font-weight: bold; font-size: medium; text-align: center;'>{$hr_discipline}</td></tr>";
foreach ($array as $k => $v) 
{
  $v = mysql_query($v);
  if (mysql_num_rows($v) === 0)
  {
    echo "<td>{$k}</td><td colspan='5'>Требуется талант!<br />
    Для связи с администрацией выделите строку и нажмите &lt;Ctrl> + &lt;Enter>, 
    либо воспользутесь \"<a href='/contact'>Обратной связью</a>\"</td>";
  }
  print "<tr>";
  while ($result = mysql_fetch_assoc($v))
  {
    $result['comp'] = NameComptition($result['comp']);
    echo "<td>{$k}</td>"."\n";
    echo "<td><a href='/athlete/{$result['athlete_id']}'>{$result['name']}</a></td> \n";
    echo "<td>{$result['age']}</td>\n";
//    if (FALSE === ($fo or $region)) {echo "<td>{$result['fo']}</td>\n";}
    echo "<td class='sm'>{$result['subject_rf']} <br /> {$result['city']}</td>\n";
	echo "<td>{$result[$discipline]}</td>\n";
    echo "<td class='sm'> {$result['comp']} </td>\n";
	}
  print "</tr>\n";
}
}


//print '<table><tr><td>';
print "<table class='MyTable'>\n";
	print "<tr><td colspan='2'>{$AdSenseAdaptive}</td></tr>";
	print '<tr><td><table>';
		print '<th colspan="6">Троеборье</th>';
		echo record_db($gender, 2, 'total', $fo, $region);
		echo record_db($gender, 2, 'squat', $fo, $region);
		echo record_db($gender, 2, 'brench', $fo, $region);
		echo record_db($gender, 2, 'deadlift', $fo, $region);
	print "</table></td>";
//print '</td><td>';
	print '<td><table>';
		print '<th colspan="6">Троеборье классическое</th>';
		echo record_db($gender, 1, 'total', $fo, $region);
		echo record_db($gender, 1, 'squat', $fo, $region);
		echo record_db($gender, 1, 'brench', $fo, $region);
		echo record_db($gender, 1, 'deadlift', $fo, $region);
	print "</table></td></tr>";
	print "<tr><td colspan='2'><center>{$AdSense1}</center></td></tr>";
print '</table>';
//pint '</td></tr></table>';
?>