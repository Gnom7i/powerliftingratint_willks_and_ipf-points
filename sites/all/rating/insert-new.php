<?php
require_once 'config.php';
require_once 'lib/Smarty-3.1.21/libs/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = getcwd();
$smarty->compile_dir = "/tmp";
require_once 'model.php';
//
if (isset($_REQUEST['doAdd']))
{
  // Запрос на совпадение добовляемого спортсмена в таблице _athlete с: имя, возраст, страна, субъект
  $flag_control = $DATABASE->selectRow("
  SELECT name, athlete_id, age, country, subject_rf FROM " . TABLEATHLETE . " 
  WHERE name=trim(?) 
  AND age=trim(?) 
  AND country=? 
  { AND subject_rf=? }",
  $_REQUEST['name_get'], 
  $_GET['age'], 
  ($_GET['country']? 643 : DBSIMPLE_SKIP),
  $_GET['subject_rf'] );
 
  // Если спортсмен есть в базе данных
  if ($flag_control)
  {  
  // Запрос совподения "название соревнований" и "дату соревнований" у добавляемого спортсмена
      $flag_competition = $DATABASE->selectRow('
      SELECT competition, date, athlete_id FROM ' . TABLECOMPETITION . ' 
      WHERE athlete_id=? 
      AND competition=? AND age_category=? AND devizion=? AND date=trim(?)', 
      $flag_control['athlete_id'], $competition, $_GET['age_category'], 
      $_GET['devizion'], $_GET['date']);

    if ($flag_competition)
    { 
      echo "У спортсмена {$flag_control['name']}, есть в базе данных соревнования 
      \"{$flag_competition['competition']} {$flag_competition['date']}\"";
      exit('<a href="86">К редактору</a>');
    }
      // если спортсмен есть в базе, то добовляем данные только в таблицу _competition
      // Ищу ячейку athlete_id для совпавшего имени
      $sql_athlete_id  = $DATABASE->selectRow('
      SELECT athlete_id, subject_rf FROM ' . TABLEATHLETE . ' 
      WHERE name=trim(?) AND age=trim(?) AND country=? { AND subject_rf=? }',
      $_REQUEST['name_get'], $_GET['age'], ($_GET['country']? 643 : DBSIMPLE_SKIP), $_GET['subject_rf']);
      // Ячейка найдена
      if ($sql_athlete_id)
      {
        // если у атлета в базе не заполненно поле subject_rf, перезаписываем                                         
        $DATABASE->query('UPDATE '.TABLEATHLETE.' 
                          SET subject_rf=? WHERE athlete_id=? and subject_rf=?',
                          $_GET['subject_rf'], $sql_athlete_id['athlete_id'], '');
        // Вставляю данные в таблицу _competition к существующему спортсмену
        $DATABASE->query('INSERT INTO ' . TABLECOMPETITION . ' SET 
                        id=?, athlete_id=?, devizion=?, date=?, competition=?, position=?, weight=?, 
                        squat=?, brench=?, deadlift=?, total=?, wilks=?, trainer=?, age_category=?', 
                        0, $sql_athlete_id['athlete_id'], $devizion, $date, $competition, $position, 
                        $weight, $squat, $brench, $deadlift, $total, $wilks, $trainer, $age_category
                        );
        Header("Location: 86?".time());
        exit();
      }
  } else {
      // Добавление нового спортсмена
      // т.к. данные храняться в двух таблицах, нужно разбить запрос на три части
      // первый запрос: вставляю постоянные данные спортсмена в таблицу rating_dvfo_athlete
      $DATABASE->query('INSERT INTO '.TABLEATHLETE.' SET athlete_id=?, gender=?, name=?, age=?, 
                                                    country=? , { fo=?, } subject_rf=?, city=?',
                                                    0, $gender, $name_get, $age, $country, 
                                                    ($fo? $fo : DBSIMPLE_SKIP), $subject_rf, $city);
      // второй запрос: 
      // вычесляю athlete_id у только-что вставленной подстроки в таблицу rating_dvfo_athlete
      $sql_athlete_id = $DATABASE->selectRow('SELECT athlete_id, name FROM '.TABLEATHLETE.' 
                                             WHERE name=trim(?) AND age=trim(?) AND country=?  AND subject_rf=? ',
                                             $_REQUEST['name_get'], $_GET['age'], $_GET['country'], $_GET['subject_rf']);
      // третий запрос: получив athlete_id из таблицы rating_dvfo_athlete, 
      // вставляю данные соревнований в таблицу rating_dvfo_competition
      $DATABASE->query('INSERT INTO '.TABLECOMPETITION.' SET 
      id=?, athlete_id=?, devizion=?, date=?, competition=?, position=?, weight=?, 
      squat=?, brench=?, deadlift=?, total=?, wilks=?, trainer=?, age_category=?', 
      0, $sql_athlete_id['athlete_id'], $devizion, $date, $competition, 
      $position, $weight, $squat, $brench, $deadlift, $total, $wilks, $trainer, $age_category) 
      or die("tretiy zapros ".mysql_error());
      Header("Location: 86?".time());
      //Header("Location: " . $_SERVER['HTTP_REFERER'] . "?" . time() . '#textarea');
      exit();
    }
}
// Обновление записи
if (isset($_GET['update'])) {
  $DATABASE->query(
                    'UPDATE ' . TABLEATHLETE . 
                    ' SET name=TRIM(?), gender=?, age=?, 
                    country=TRIM(?), fo=TRIM(?), subject_rf=TRIM(?), city=TRIM(?)
                    WHERE athlete_id=?', 
                    $_GET['name'], $_GET['gender'], $_GET['age'], 
                    $_GET['country'], $_GET['fo'], $_GET['subject_rf'], $_GET['city'], 
                    $_GET['athlete_id']);  
  $DATABASE->query(
                    'UPDATE ' . TABLECOMPETITION . 
                    ' SET competition=TRIM(?), devizion=?, age_category=?, date=?, 
                    position=?, 
                    weight=REPLACE(?, ",", "."), squat=REPLACE(?, ",", "."), 
                    brench=REPLACE(?, ",", "."), deadlift=REPLACE(?, ",", "."), 
                    total=REPLACE(?, ",", "."), wilks=REPLACE(?, ",", "."), 
                    trainer=TRIM(?), tstamp=NULL
                    WHERE id=?',
                    $_GET['competition'], $_GET['devizion'], $_GET['age_category'], $_GET['date'],
                    $_GET['position'], $_GET['weight'], $_GET['squat'], $_GET['brench'], 
                    $_GET['deadlift'], $_GET['total'], $_GET['wilks'], $_GET['trainer'],
                    $_GET['competition_id'] 
                  );             
  Header("Location: " . $_GET['PathHistory'] . "?" . time() . '#textarea');
  exit();
}
// Вывод записи для редактирования
if (isset($_GET['athlete_id'])) {
  //
  $ParseURL = parse_url($_SERVER['HTTP_REFERER']);
  //
  $update = $DATABASE->selectRow('SELECT 
  -- Поля таблицы ATHLETE
  a.athlete_id AS athlete_id, a.name AS name, a.gender, a.age, a.fo, a.subject_rf, a.city,
  -- Поля таблицы COMPETITION
  c.id AS competition_row_id, c.competition AS competition, c.devizion AS devizion, c.date AS date,
  position, weight, squat, brench, deadlift, total, wilks, trainer,
                                  country.abbreviated_name AS country, country.iso AS iso,                                  
                                  competition.name_ru AS competitionname,
                                  competition.id AS competitionid,
                                  acat.reduction AS age_cat, acat.id AS age_cat_id                              
                                  FROM ' . TABLEATHLETE . ' AS a 
                                  INNER JOIN ' . TABLECOMPETITION . ' AS c 
                                    ON a.athlete_id = c.athlete_id
                                  INNER JOIN ' . TABLECOUNTRY . ' AS country
                                    ON a.country = country.iso
                                  INNER JOIN ' . TABLECOMPETITIONNAME . ' AS competition
                                    ON c.competition = competition.id
                                  INNER JOIN ' . TABLECATEGORYAGE . ' AS acat
                                    ON c.age_category = acat.id
                                  WHERE a.athlete_id =? AND c.id=?',
                                  $_GET['athlete_id'], $_GET['competition_id']
                                );                         
  $CountrySelect = $DATABASE->select('SELECT iso AS IsoList, abbreviated_name AS CountryList from '. 
                                      TABLECOUNTRY );
  $FoSelect = $DATABASE->select('SELECT abbr AS FoList from ' . TABLEFEDERALDISTRICT);
  $CompetitionSelect = $DATABASE->select('SELECT name_ru AS CompetitionList, id AS CompetitionId from ' . TABLECOMPETITIONNAME);
  $AgeCategorySelect = $DATABASE->select('SELECT reduction AS AgeCategoryList, id AS AgeCategoryId from ' . TABLECATEGORYAGE);
  // Определение переменных Смарти
  $smarty->assign('update', $update);
  $smarty->assign('CountrySelect', $CountrySelect);
  $smarty->assign('FoSelect', $FoSelect);
  $smarty->assign('CompetitionSelect', $CompetitionSelect);
  $smarty->assign('AgeCategorySelect', $AgeCategorySelect);
  $smarty->assign('PathHistory', $ParseURL['path']);
  $smarty->display('sites/all/rating/update.tpl');
  exit();
}
// определение переменных для Smarty
$smarty->assign('option_rf', $op_rf);
$smarty->assign('country', $country_list);
$smarty->assign('competition_list', $competition_list);
$smarty->assign('age_category_list', $age_category_list);
// Подключаю шаблон Smarty
$smarty->display('sites/all/rating/insert_update.tpl');
?>
  <script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>