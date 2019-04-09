<?php 
# Файл для вывода рейтингов соревнований (Белый ночи, Огни Москвы)
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/rf/model.php'; 
require_once 'sites/all/rating/style.php';




//вывод рейтинга спортсменов
$women = output_top_competition(1, 2, $competition);
$women_raw = output_top_competition(1, 1, $competition);
$men = output_top_competition(2, 2, $competition);
$men_raw = output_top_competition(2, 1, $competition);
require_once 'lib/Smarty-3.1.21/libs/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = getcwd();
$smarty->compile_dir = "/tmp";
$smarty->assign('women', $women);
$smarty->assign('women_raw', $women_raw);
$smarty->assign('men', $men);
$smarty->assign('men_raw', $men_raw);
$smarty->assign('FlagAdmin', $FlagAdmin);
$smarty->display('sites/all/rating/rf/views/competition.tpl');
?>