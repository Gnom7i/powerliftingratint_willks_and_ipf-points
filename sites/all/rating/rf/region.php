<?php 
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/rf/model.php'; 
require_once 'sites/all/rating/style.php';


function output_top_region($gender, $devizion, $region, $i = 0){
#запрос athlete_id спортсменов
$sql_athlete = mysql_query("
  SELECT DISTINCT a.athlete_id , max(wilks) as wilks, gender, devizion
  FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c 
  WHERE a.athlete_id = c.athlete_id 
  and subject_rf = '{$region}'
  AND gender = $gender and devizion = $devizion
  GROUP BY a.athlete_id 
  ORDER BY wilks DESC
  "
); 
while($athlete = mysql_fetch_assoc($sql_athlete)) {
  #запрос максимального wllks у спортсмена с $athlete_id
  $sql_mwilks = mysql_query(
    "SELECT DISTINCT *, a.athlete_id AS athlete_id, c.id AS competition_row_id,
    TRUNCATE(weight, 2) as weight, TRUNCATE(wilks, 2) as wilks, TRUNCATE(total, 1) as total,
    CONCAT ( LEFT ( nc.name_ru, 18 ), ac.name_ru ) AS comp,
	a.city AS region
    FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c, " . 
      TABLECOMPETITIONNAME . " as nc, " . TABLECATEGORYAGE . " as ac
    WHERE a.athlete_id = c.athlete_id 
      and c.competition = nc.id
      and c.age_category = ac.id
    AND devizion = $devizion 
    AND gender = $gender 
    AND c.athlete_id = {$athlete['athlete_id']}
    AND wilks in 
      (SELECT max(wilks) as bestwilks FROM " . TABLECOMPETITION . " as c 
      WHERE c.athlete_id = {$athlete['athlete_id']} 
      AND devizion = $devizion 
      AND gender = $gender 
      ORDER BY bestwilks DESC)
    ORDER BY wilks DESC  
	LIMIT 1
	 "
  );
  while($athlete_mwilks = mysql_fetch_assoc($sql_mwilks)) { 
    $athlete_mwilks['comp'] = str_replace('(Взрослые)', '', $athlete_mwilks['comp']);
    $athlete_mwilks['comp'] = 
      str_replace('Чемпионат мира (Юниоры)', 'Первенство мира (Юниоры)', $athlete_mwilks['comp']);
    $athlete_mwilks['comp'] = 
      str_replace('Чемпионат мира (Юноши)', 'Первенство мира (Юноши)', $athlete_mwilks['comp']);
    // Вывод данных в массив
    $array[] = $athlete_mwilks;
  } 
}
return $array;
}


//вывод среднего Вилкс у топ 10
print '<table class=tabstyle1>'."\n";
print '<caption>Средний коэффициент топ 10  '.$region.'</caption>'."\n";
print '<tr><td> </td><td>Троеборье</td><td>Классическое троеборье</td></tr>'."\n";
echo '<tr><th>Женщины</th>
<td>', av_wilks_region(1, 2, $region), '</td><td>'.av_wilks_region(1, 1, $region).'</td></tr>'."\n";
echo '<tr><th>Мужчины</th>
<td>', av_wilks_region(2, 2, $region), '</td><td>'.av_wilks_region(2, 1, $region).'</td></tr>'."\n";
print '<tr><th colspan="3">
<a href="/node/116">Средний коэффициент Вилкса регионов РФ среди женщин</a><br />
<a href="/node/113">Средний коэффициент Вилкса регионов РФ среди мужчин</a>
</th></tr>';
print '</table>'."\n";
print "<p /><p /><p />";



//
//вывод рейтинга спортсменов
$women = output_top_region(1, 2, $region);
$women_raw = output_top_region(1, 1, $region);
$men = output_top_region(2, 2, $region);
$men_raw = output_top_region(2, 1, $region);
require_once 'lib/Smarty-3.1.21/libs/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = getcwd();
$smarty->compile_dir = "/tmp";
$smarty->assign('women', $women);
$smarty->assign('women_raw', $women_raw);
$smarty->assign('men', $men);
$smarty->assign('men_raw', $men_raw);
$smarty->assign('FlagAdmin', $FlagAdmin);
$smarty->display('sites/all/rating/rf/views.tpl');
?>