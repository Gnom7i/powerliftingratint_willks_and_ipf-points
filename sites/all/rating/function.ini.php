<?php
// функция определение среднего Вилкс у топ 10
// функция определение среднего Вилкс у топ 10
function av_wilks($gender, $devizion, $part_world = NULL){
  $sql_w = "
  SELECT IF (COUNT(w.wilks) = 10, TRUNCATE( AVG( w.wilks ) , 2 ), ' < 10 athlete ') as wilks 
  FROM  (
  SELECT max( wilks ) as wilks FROM " . 
    TABLEATHLETE . " AS a,  " . TABLECOMPETITION . " as c, " . TABLECOUNTRY . " as cntr 
  where a.athlete_id = c.athlete_id 
  and a.athlete_id = c.athlete_id 
  and cntr.iso = a.country ";
  if (NULL !== $part_world) $sql_w .= " and cntr.part = '{$part_world}' ";
  $sql_w .= "
  and devizion = '{$devizion}' 
  AND a.gender = '{$gender}'
  GROUP BY a.athlete_id
  ORDER BY wilks DESC LIMIT 10) AS w
  ";
  //$t = $sql_w;
  $sql_w = mysql_query($sql_w) or die(mysql_error());
    $sql_w = mysql_fetch_assoc($sql_w);
    return $sql_w['wilks']; // . $t;
}


 function get_in_translate_to_en($string, $gost=false)
{
    if($gost)
    {
        $replace = array("А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
                "Е"=>"E","е"=>"e","Ё"=>"E","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z","И"=>"I","и"=>"i",
                "Й"=>"I","й"=>"i","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n","О"=>"O","о"=>"o",
                "П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t","У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f",
                "Х"=>"Kh","х"=>"kh","Ц"=>"Tc","ц"=>"tc","Ч"=>"Ch","ч"=>"ch","Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch",
                "Ы"=>"Y","ы"=>"y","Э"=>"E","э"=>"e","Ю"=>"Iu","ю"=>"iu","Я"=>"Ia","я"=>"ia","ъ"=>"","ь"=>"");
    }
    else
    {
        $arStrES = array("ае","уе","ое","ые","ие","эе","яе","юе","ёе","ее","ье","ъе","ый","ий");
        $arStrOS = array("аё","уё","оё","ыё","иё","эё","яё","юё","ёё","её","ьё","ъё","ый","ий");        
        $arStrRS = array("а$","у$","о$","ы$","и$","э$","я$","ю$","ё$","е$","ь$","ъ$","@","@");
                    
        $replace = array("А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
                "Е"=>"Ye","е"=>"e","Ё"=>"Ye","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z","И"=>"I","и"=>"i",
                "Й"=>"Y","й"=>"y","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n",
                "О"=>"O","о"=>"o","П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t",
                "У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f","Х"=>"Kh","х"=>"kh","Ц"=>"Ts","ц"=>"ts","Ч"=>"Ch","ч"=>"ch",
                "Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch","Ъ"=>"","ъ"=>"","Ы"=>"Y","ы"=>"y","Ь"=>"","ь"=>"",
                "Э"=>"E","э"=>"e","Ю"=>"Yu","ю"=>"yu","Я"=>"Ya","я"=>"ya","@"=>"y","$"=>"ye");
                
        $string = str_replace($arStrES, $arStrRS, $string);
        $string = str_replace($arStrOS, $arStrRS, $string);
    }
        
    return iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace));
}



function ru2Lat($string)
{
$rus = array('ё','ж','ц','ч','ш','щ','ю','я','Ё','Ж','Ц','Ч','Ш','Щ','Ю','Я');
$lat = array('yo','zh','tc','ch','sh','sh','yu','ya','YO','ZH','TC','CH','SH','SH','YU','YA');
$string = str_replace($rus,$lat,$string);
$string = mb_strstr($string,
     "АБВГДЕЗИЙКЛМНОПРСТУФХЪЫЬЭабвгдезийклмнопрстуфхъыьэ",
     "ABVGDEZIJKLMNOPRSTUFH_I_Eabvgdezijklmnoprstufh_i_e");
  
return ($string);
}


// Функция определения заголовков th в таблице топ 10 ENGLISH. 
// Каталог world: Планета, Группы стран (Slavic), Части света (Европа)
function table_th9_en($gender, $devizion){
if($gender  == 1) $hr = 'Women. '; else $hr = 'Men. ';
if($devizion == 1) $hr .= 'Classic poweilifting'; else $hr .= 'Powerlifting';
return "<tr><th colspan='9'>{$hr}</th>
</tr>\n";
}

// функция определения заголовка
function table_th10_ru($gender, $devizion){
if($gender  == 1) $hr = 'Женщины. '; else $hr = 'Мужчины. ';
if($devizion == 1) $hr .= 'Классическое троеборье'; else $hr .= 'Троеборье';
return "<tr><th colspan='10'>{$hr}</th>
</tr>\n";
}
?>