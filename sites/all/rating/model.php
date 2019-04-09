<?php 
//выборка федеральных округов
$tsfo = array_diff($DATABASE->selectCol("select ts from fo_rf"), array(null));
$sfo = array_diff($DATABASE->selectCol("select s from fo_rf"), array(null));
$ufo = array_diff($DATABASE->selectCol("select u from fo_rf"), array(null));
$pfo = array_diff($DATABASE->selectCol("select p from fo_rf"), array(null));
$szfo = array_diff($DATABASE->selectCol("select sz from fo_rf"), array(null));
$yufo = array_diff($DATABASE->selectCol("select yu from fo_rf"), array(null));
$skfo = array_diff($DATABASE->selectCol("select sk from fo_rf"), array(null));
$dvfo = array_diff($DATABASE->selectCol("select dv from fo_rf"), array(null));
$kfo = array_diff($DATABASE->selectCol("select k from fo_rf"), array(null));
// Области стран постсоветского пространства
$ukr = array_diff($DATABASE->selectCol("select UKR from fo_rf"), array(null));
$blr = array_diff($DATABASE->selectCol("select BLR from fo_rf"), array(null));
// Все области
//$AllRegion = $DATABASE->selectCol("SELECT DISTINCT region FROM `cities` WHERE country_id IN(1,2) ORDER BY region");
//преобразование GET-переменных
if (@$_REQUEST['doAdd'])
{
  // ПЕРЕМЕННЫЕ КУКИ
  // Половая принадлежнасть
  if($_GET['gender']) {
  $gender = $_GET['gender'];
  setcookie("gender", $_GET['gender'], time()+3600);
  //if ($_COOKIE["gender"] != $_GET['gender']) $_COOKIE["gender"] = $_GET['gender'];
  }
  // Страна
  if (isset($_GET['country'])) {
    $country = $_GET['country'];
    setcookie("country", $country, time()+3600);    
  }
  // Название соревнований
  if ($_GET['competition']) {
    $competition = $_GET['competition'];
    setcookie("competition", $_GET['competition'], time()+3600);
  }
  // Возрастная категория
  if(isset($_GET['age_category'])) {
    $age_category = $_GET['age_category'];
    setcookie("age_category", $age_category, time()+3600);
  }
  // Год соревнований
  if(isset($_GET['date'])) {
    $date = $_GET['date'];
    setcookie("date", $_GET['date'], time()+3600);
  }
  // Девизион
  
  if(isset($_GET['devizion'])) {
    $devizion = $_GET['devizion'];    
    setcookie("devizion", $devizion, time()+3600);
  }  
  //if ($devizion === NULL) { $devizion = 2; }
  // ОБЫЧНЫЕ ПЕРЕМЕННЫЕ
   if (empty($devizion)) { 
    $devizion = 2; 
    // $_COOKIE["devizion"] = 2;
   }
  $name_get = trim($_REQUEST['name_get']);
  $age = $_REQUEST['age'];
  $id_a = $_REQUEST['athlete_id'];
  $subject_rf = trim($_REQUEST['subject_rf']);
  $city = trim($_REQUEST['city']);
  $position = $_REQUEST['position'];
  // замена запятых на точки
  $weight = str_replace(",", ".", $_REQUEST['weight']);
  $squat = str_replace(",", ".", $_REQUEST['squat']);
  $brench = str_replace(",", ".", $_REQUEST['brench']);
  $deadlift = str_replace(",", ".", $_REQUEST['deadlift']);
  $total = str_replace(",", ".", $_REQUEST['total']);
  $wilks = str_replace(",", ".", $_REQUEST['wilks']);
  $trainer = @$_REQUEST['trainer'];

  //определение федерального округа
  $fo = NULL;
  if (FALSE !== array_search($subject_rf, $yufo)) $fo = 'ЮФО';
  if (FALSE !== array_search($subject_rf, $skfo)) $fo = 'СКФО';
  if (FALSE !== array_search($subject_rf, $dvfo)) $fo = 'ДВФО';
  if (FALSE !== array_search($subject_rf, $sfo)) $fo = 'СФО';
  if (FALSE !== array_search($subject_rf, $ufo)) $fo = 'УФО';
  if (FALSE !== array_search($subject_rf, $szfo)) $fo = 'СЗФО';
  if (FALSE !== array_search($subject_rf, $pfo)) $fo = 'ПФО';
  if (FALSE !== array_search($subject_rf, $tsfo)) $fo = 'ЦФО';
  if (FALSE !== array_search($subject_rf, $kfo)) $fo = 'КФО'; 
}
if(isset($_COOKIE["gender"])) {
  $smarty->assign('c_gender', $_COOKIE["gender"]);
}
else {
  $smarty->assign('c_gender', 2);
}
if(isset($_COOKIE["devizion"])) {
  $smarty->assign('c_devizion', $_COOKIE["devizion"]);
}
else {
  $smarty->assign('c_devizion', 2);
}
if (isset($_COOKIE["competition"])) {
  $name_competition = $DATABASE->selectCell('select name_ru from name_competition where id = ?', $_COOKIE["competition"]);
  $smarty->assign('name_comp', $name_competition);
  $smarty->assign('c_competition', $_COOKIE["competition"]);
}
if (isset($_COOKIE["age_category"])) {
  $name_age_category = $DATABASE->selectCell('select reduction from ' . TABLECATEGORYAGE . 
                                             ' where id = ?', $_COOKIE["age_category"]
                                            );
  $smarty->assign('age_category_name', $name_age_category);
  $smarty->assign('age_category_c_id', $_COOKIE["age_category"]);
}
else {
  $smarty->assign('age_category_name', 'O');
  $smarty->assign('age_category_c_id', 7);
}


if (isset($_COOKIE["date"])) {
  $smarty->assign('c_date', $_COOKIE["date"]);
} 
else {
  $smarty->assign('c_date', 2016);
}


if(isset($_COOKIE["country"])) {
  $name_country = $DATABASE->selectCell('select abbreviated_name from country where iso = ?', $_COOKIE["country"]);
  $smarty->assign('c_country_name', $name_country);
  $smarty->assign('c_country_iso', $_COOKIE["country"]);
}
else {
  $smarty->assign('c_country_name', 'Россия');
  $smarty->assign('c_country_iso', 643);
}
// ЛИСТЫ ДЛЯ ВЫПАДАЮЩИХ СПИСКОВ
$competition_list = $DATABASE->select('select DISTINCT id, name_ru from '.TABLECOMPETITIONNAME.' order by popular, rang, name_ru');
$age_category_list = $DATABASE->select('select DISTINCT id, reduction from '.TABLECATEGORYAGE);
$country_list = $DATABASE->select('select DISTINCT abbreviated_name, iso from '.TABLECOUNTRY.' order by abbreviated_name');
//названия субъектов РФ для выпадающего списка
$op_rf = array_merge($tsfo, $sfo, $ufo, $pfo, $szfo, $yufo, $skfo, $dvfo, $kfo, $ukr, $blr);
//$op_rf = $AllRegion;
// Последние 10 строк в базе
$NewRow = $DATABASE->select('SELECT DISTINCT *,
                              IF(devizion = 1, "БЭ", "ЭК") AS devizion,
                              IF(gender = 1, "Ж", "М") AS gender,
                              a.name AS name, c.id AS competitionid,
                              country.abbreviated_name AS country, cn.name_ru AS competition, 
                              ca.reduction AS age_category
                              FROM ' . TABLEATHLETE . ' AS a INNER JOIN ' . TABLECOMPETITION . ' AS c 
                                ON a.athlete_id = c.athlete_id
                              INNER JOIN ' . TABLECOUNTRY . ' AS country
                                ON a.country = country.iso
                              INNER JOIN ' . TABLECOMPETITIONNAME . ' AS cn
                                ON c.competition = cn.id
                              INNER JOIN ' . TABLECATEGORYAGE . ' AS ca
                                ON c.age_category = ca.id
                              ORDER BY c.tstamp DESC
                              LIMIT 20');
$smarty->assign('NewRow', $NewRow);


// Функция определения TH заголовков таблиц (Женщина/Мужчина)
function table_th($gender, $devizion){
if($gender  == 1) $hr = 'Женщины. '; else $hr = 'Мужчины. ';
if($devizion == 1) $hr .= 'Классическое троеборье'; else $hr .= 'Троеборье';
return "<tr><th colspan='10'>{$hr}</th></tr>\n";
}
?>