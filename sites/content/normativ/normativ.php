<?php
include 'config.php';
$sql[] = mysql_fetch_assoc(mysql_query("SELECT * FROM normativ WHERE `age` = 2014 ORDER BY `kategoriya`" ));
foreach($sql as $n){
?>
<tr>
	<td><?=$n['kategoriya']?></td>
	<td><?=$n['norma']?></td>
</tr>
<?php
;}
?>






<!-- 
INSERT INTO `mashko_kampower`.`normativ` (`id`, `federation`, `pol`, `kategoriya`, `norma`, `razryad`)
VALUES 
(NULL, 'фпр', 'м', '59', '', 'мсмк'),
(NULL, 'фпр', 'м', '59', '', 'мс'),
(NULL, 'фпр', 'м', '59', '', 'кмс'),
(NULL, 'фпр', 'м', '59', '', '1'),
(NULL, 'фпр', 'м', '59', '', '2'),
(NULL, 'фпр', 'м', '59', '', '3'),
(NULL, 'фпр', 'м', '59', '', '1ю'),
(NULL, 'фпр', 'м', '59', '', '2ю'),
(NULL, 'фпр', 'м', '59', '', '3ю'); -->