<?php
include './classes/DB.class.php';
echo '1) Соединение с БД <br><br>';
$dbconn3 = new DB("newDB");
echo '<br> 2) Копирование данных из таблицы Authors_catalog<br> <br>';
$tb_name = 'kursovaya."Authors_catalog"';
$dbconn3->infoTable($tb_name);
echo '<br> <br> 4) GET - запрос <br>';
$name = "не определено";
$age = "не определен";
if(isset($_GET["name"])){

    $name = $_GET["name"];
}
if(isset($_GET["age"])){

    $age = $_GET["age"];
}
echo "<br> Имя: $name <br> Возраст: $age";
?>