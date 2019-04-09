<?php 
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/rf/model.php'; 
require_once 'sites/all/rating/style.php';


if ($fo == 'ЦФО') {$sql_fo = 'ts';}
if ($fo == 'СЗФО') {$sql_fo = 'sz';}
if ($fo == 'УФО') {$sql_fo = 'u';}
if ($fo == 'ДВФО') {$sql_fo = 'dv';}
if ($fo == 'СФО') {$sql_fo = 's';}
if ($fo == 'ПФО') {$sql_fo = 'p';}
if ($fo == 'КФО') {$sql_fo = 'k';}
//
// $tsfo = array_diff($DATABASE->selectCol("select {$sql_fo} FROM " . TABLEFO), array(null));
//
function output_top_fo($gender, $devizion, $fo, $i = 0){
#запрос athlete_id спортсменов
$sql_athlete = mysql_query("
  SELECT DISTINCT a.athlete_id , max(wilks) as wilks, gender, devizion
  FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c 
  WHERE a.athlete_id = c.athlete_id 
  AND fo = '{$fo}'
  AND gender = $gender and devizion = $devizion
  GROUP BY a.athlete_id 
  ORDER BY wilks DESC
  LIMIT 50
  "
); 
while($athlete = mysql_fetch_assoc($sql_athlete)) {
  #запрос максимального wllks у спортсмена с $athlete_id
  $sql_mwilks = mysql_query(
    "SELECT DISTINCT *, a.athlete_id AS athlete_id, c.id AS competition_row_id,
    TRUNCATE(weight, 2) as weight, TRUNCATE(wilks, 2) as wilks, TRUNCATE(total, 1) as total,
    CONCAT ( LEFT ( nc.name_ru, 18 ) , ac.name_ru ) AS comp,
	CONCAT(a.subject_rf, '<br />', a.city) AS region
	
    FROM " . TABLEATHLETE . " as a, " . TABLECOMPETITION . " as c, " . 
      TABLECOMPETITIONNAME . " as nc, " . TABLECATEGORYAGE . " as ac
    WHERE a.athlete_id = c.athlete_id 
      and c.competition = nc.id
      and c.age_category = ac.id
    AND devizion = $devizion 
    AND gender = $gender 
    AND c.athlete_id = {$athlete['athlete_id']}
    AND wilks in 
      (select max(wilks) as bestwilks FROM " . TABLECOMPETITION . " as c 
      where c.athlete_id = {$athlete['athlete_id']} 
      AND devizion = $devizion 
      AND gender = $gender 
      ORDER BY bestwilks DESC)
    ORDER BY wilks DESC 
	LIMIT 1
	 "
  );
  while($athlete_mwilks = mysql_fetch_assoc($sql_mwilks)) { 
    // Изменение названий соревнований. Российские особенности.
    $athlete_mwilks['comp'] = NameComptition($athlete_mwilks['comp']);
    // Вывод данных в массив
    $array[] = $athlete_mwilks;
  } 
}
return $array;
}
//блок "состав ФО"
//
// print 'Состав '.$fo.': ' . join(', ', $tsfo) . '. ';
//
//вывод среднего Вилкс у топ 10
print '<table class=tabstyle1>'."\n";
print '<caption>Средний коэффициент топ 10  '.$fo.'</caption>'."\n";
print '<tr><td> </td><th>Женщины</th><th>Мужчины</th></tr>'."\n";
echo '<tr><td>Троеборье</td>
<td>', av_wilks_fo(1, 2, $fo), '</td><td>' . av_wilks_fo(2, 2, $fo) . '</td></tr>'."\n";
echo '<tr><td>Классическое троеборье</td>
<td>', av_wilks_fo(1, 1, $fo) , '</td><td>' . av_wilks_fo(2, 1, $fo) . '</td></tr>'."\n";
print '<tr><th colspan="3"><a href="/Russia/FO/rating">Рейтинг Федеральных Округов РФ</a></th></tr>';
print '</table>'."\n";
print "<p />";



?>

<script type="text/javascript">
$(document).ready(function(){
  $('.spoiler').hide();
  $('<input type="button" class="revealer" value="Tell Me!"/>').insertBefore('.spoiler');
  $('.revealer').click(function(){
    $(this).hide();
    $(this).next().fadeIn();
  });
});
</script>

<?php
//вывод рейтинга спортсменов
$women = output_top_fo(1, 2, $fo);
$women_raw = output_top_fo(1, 1, $fo);
$men = output_top_fo(2, 2, $fo);
$men_raw = output_top_fo(2, 1, $fo);
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