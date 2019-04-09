<?php
require_once 'sites/all/rating/config.php'; 
require_once 'sites/all/rating/rf/model.php';
require_once 'sites/all/rating/style.php';


$result = mysql_query('
SELECT r.name AS region FROM subject AS r, rating_dvfo_athlete AS a
WHERE a.subject_rf = r.name 
GROUP by r.name
ORDER BY r.name') 
or die('Ошибка в запросе: список регионов. ' . mysql_error());

print '<table class="tabstyle1" style="">';
print "<tr><th colspan='3'>Средний коэффициент Вилкса регионов РФ</th></tr>";
print "<tr><th>Регион</th><th>Троеборье</th><th>Троеборье классическое</th></tr>";
while ($list = mysql_fetch_assoc($result)) {
  print "<tr>";
  echo "<td>{$list['region']}</td>";
  $avg =  mysql_query("SELECT 
  IF (COUNT(w.wilks) >= 10, TRUNCATE( AVG( w.wilks ) , 2 ), ' < 10 спортсменов ') AS wilks
  FROM (SELECT max(c.wilks) AS wilks
    FROM subject AS r 
    INNER JOIN rating_dvfo_athlete AS a ON a.subject_rf = r.name
    INNER JOIN competition AS c ON c.athlete_id = a.athlete_id
    WHERE r.name = '{$list['region']}' AND a.gender = {$gender} AND c.devizion = 2
    GROUP by a.name
    ORDER bY wilks DESC
    LIMIT 10) 
  AS w")
  or die('Ошибка в запросе: срединий вилкс. ' . mysql_error());
  while ($row =  mysql_fetch_assoc($avg)) {
    echo "<td>{$row['wilks']}</td>";
  }
  
  
  $avgRaw =  mysql_query("SELECT 
  IF (COUNT(w.wilks) >= 10, TRUNCATE( AVG( w.wilks ) , 2 ), ' < 10 спортсменов ') AS wilks
  FROM (SELECT max(c.wilks) AS wilks
    FROM subject AS r 
    INNER JOIN rating_dvfo_athlete AS a ON a.subject_rf = r.name
    INNER JOIN competition AS c ON c.athlete_id = a.athlete_id
    WHERE r.name = '{$list['region']}' AND a.gender = {$gender} AND c.devizion = 1
    GROUP by a.name
    ORDER bY wilks DESC
    LIMIT 10) 
  AS w")
  or die('Ошибка в запросе: срединий вилкс. ' . mysql_error());
  while ($rowRaw =  mysql_fetch_assoc($avgRaw)) {
    echo "<td>{$rowRaw['wilks']}</td>"; 
  }
  //echo ($row['wilks'] + $rowRaw['wilks']) / 2;
  print "</tr>";
}
print '</table>';


?>
<p />
<p />
<p />
<p />