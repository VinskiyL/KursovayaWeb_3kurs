<?php
// header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET,PUT,POST');
header('Access-Control-Allow-Credentials: true');

include './classes/DB.php';
//echo '1) Соединение с БД <br><br>';
$dbconn3 = new DB("newDB");
//echo '<br> 2) Копирование данных из таблицы Authors_catalog<br> <br>';
$tb_name = 'kursovaya."Authors_catalog"';
//$dbconn3->infoTable($tb_name);
//echo '<br> <br> 4) GET - запрос <br>';
//$name = "не определено";
//$age = "не определен";
//if(isset($_GET["name"])){

    //$name = $_GET["name"];
//}
//if(isset($_GET["age"])){

    //$age = $_GET["age"];
//}
//echo "<br> Имя: $name <br> Возраст: $age";
//echo "<br> Запрос select <br>";
$dbconn3->selectTable($tb_name, "*", "3");
?>