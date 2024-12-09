<?php

include './classes/DB.php';

header('Access-Control-Allow-Methods: GET, PUT, POST');
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === 'GET') {
    $query = $_GET['query'] ?? ''; // Получаем параметр query из URL
    if (!$query) {
        echo json_encode(['error' => 'Параметр query отсутствует.']);
        exit;
    }
    $dbconn3 = new DB("newDB");

    // Выполняем поиск в базе данных
    $results = $dbconn3->selectTable($query);
    $dbconn3->closeConn();

    // Проверяем, есть ли результаты
    if ($results && count($results) > 0) {
        echo json_encode($results);
    } else {
        echo json_encode([]); // Возвращаем пустой массив
    }
} else {
    echo json_encode(['error' => 'Некорректный запрос.']);
}
?>