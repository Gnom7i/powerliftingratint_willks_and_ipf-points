<?php
// Функция определение среднего Вилкс у топ 10 для ФО РФ
function av_wilks_fo($gender, $devizion, $region){
  $sql_w = mysql_query("
  SELECT  IF (COUNT(w.wilks) >= 10, TRUNCATE( AVG( w.wilks ) , 2 ), ' < 10 спортсменов ') as wilks
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


// Функция определение среднего Вилкс у топ 10 для региона РФ
function av_wilks_region($gender, $devizion, $region){
  $sql_w = mysql_query("
  SELECT  IF (COUNT(w.wilks) >= 10, TRUNCATE( AVG( w.wilks ) , 2 ), ' < 10 спортсменов ') as wilks
  FROM  (
  SELECT max( wilks ) as wilks FROM " . 
    TABLEATHLETE . " AS a,  " . 
    TABLECOMPETITION . " as c
  where a.athlete_id = c.athlete_id
  and devizion = '{$devizion}' 
  AND a.gender = '{$gender}'
  AND a.subject_rf =  '{$region}'
  GROUP BY a.athlete_id
  ORDER BY wilks DESC LIMIT 10) AS w
  ");
  $sql_w = mysql_fetch_assoc($sql_w);
  return $sql_w['wilks'];
}




// Функция определения TH заголовков таблиц (Женщина/Мужчина)
function table_th_ru($gender, $devizion){
if($gender  == 1) $hr = 'Женщины. '; else $hr = 'Мужчины. ';
if($devizion == 1) $hr .= 'Классическое троеборье'; else $hr .= 'Троеборье';
return "<tr><th colspan='10'>{$hr}</th></tr>\n";
}




// функция формирования данных для HTML таблицы.
function output_top_competition($gender, $devizion, $competition, $i = 0){
#запрос athlete_id спортсменов
$sql_athlete = mysql_query("
  SELECT DISTINCT a.athlete_id , max(wilks) as wilks, gender, devizion
  FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c 
  WHERE a.athlete_id = c.athlete_id 
  AND c.competition IN({$competition})
  AND gender = $gender and devizion = $devizion
  GROUP BY a.athlete_id 
  ORDER BY wilks DESC
  LIMIT 100
  "
); 
// Проверка на пустой результат
if (mysql_num_rows($sql_athlete) == 0) return FALSE;
while($athlete = mysql_fetch_assoc($sql_athlete)) {
  #запрос максимального wllks у спортсмена с $athlete_id
  $sql_mwilks = mysql_query(
    "SELECT DISTINCT *, a.athlete_id AS athlete_id, c.id AS competition_row_id,
    TRUNCATE(weight, 2) as weight, TRUNCATE(wilks, 2) as wilks, TRUNCATE(total, 1) as total,
    CONCAT(nc.name_ru, ' (', ac.name_ru, ')') as comp
    FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c, " . 
      TABLECOMPETITIONNAME . " as nc, " . TABLECATEGORYAGE . " as ac
    WHERE a.athlete_id = c.athlete_id 
      and c.competition = nc.id
      and c.age_category = ac.id
    AND devizion = {$devizion} 
    AND gender = {$gender} 
    AND c.athlete_id = {$athlete['athlete_id']}
    AND c.competition IN({$competition})
    AND wilks in 
      (select max(wilks) as bestwilks FROM " . TABLECOMPETITION . " as c 
      where c.athlete_id = {$athlete['athlete_id']} 
      AND c.competition IN({$competition})
      AND devizion = $devizion 
      AND gender = $gender 
      ORDER BY bestwilks DESC)
    ORDER BY wilks DESC
	 "
  );
  while($athlete_mwilks = mysql_fetch_assoc($sql_mwilks)) { 
    // Изменение названий соревнований. Российские особенности. Здесь зачем???
    $athlete_mwilks['comp'] = str_replace('(Взрослые)', '', $athlete_mwilks['comp']);
    $athlete_mwilks['comp'] = str_replace('Чемпионат мира (Юниоры)', 'Первенство мира (Юниоры)', $athlete_mwilks['comp']);
    $array[] = $athlete_mwilks;
  } 
}
return $array;
}






//Функция замены "Чемпионата мира" на "Первенство мира" для всех возрастных категорий краоме Открытой
function NameComptition($name) {
$search = array(
'(Взрослые)',
'Чемпионат мира (Юниоры)',
'Чемпионат мира (Юноши)',
'Чемпионат мира (Ветераны 40-49)',
'Чемпионат мира (Ветераны 50-59)',
'Чемпионат мира (Ветераны 60-69)',
'Чемпионат мира (Ветераны 70+)');
$replace = array(
'',
'Первенство мира (Юниоры)',
'Первенство мира (Юноши)',
'Первенство мира (Ветераны 40-49)',
'Первенство мира (Ветераны 50-59)',
'Первенство мира (Ветераны 60-69)',
'Первенство мира (Ветераны 70+)');
$name = str_replace($search, $replace, $name);
return $name;
}


// AdSense
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
	
	
$AdSense1 = '										
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<!-- HEAD PowerliftingRating -->
	<ins class="adsbygoogle"
		style="display:inline-block;width:728px;height:90px"
		data-ad-client="ca-pub-3589435131109855"
		data-ad-slot="6305874791"></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script>';
?>