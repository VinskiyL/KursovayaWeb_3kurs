<?php
header('Access-Control-Allow-Origin: https://localhost:5173');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Укажите методы, которые вы хотите разрешить
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Укажите заголовки, которые вы хотите разрешить

include './classes/DB.php';
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

try {
    if ($_SERVER["REQUEST_METHOD"] === 'GET') {
        $query = $_GET['query'] ?? ''; // Получаем параметр query из URL
        if (!$query) {
            echo json_encode(['error' => 'Параметр query отсутствует.']);
            exit;
        }
        $dbconn3 = new DB("newDB");
        $result = $dbconn3 -> selectComments($query);
        $dbconn3->closeConn();

        // Проверяем, есть ли результаты
        if ($result && count($result) > 0) {
            echo json_encode(['success' => true, 'data' => $result]); // Возвращаем успешный ответ с декодированными данными
        } else {
            echo json_encode([]); // Возвращаем пустой массив
        }
    } else {
        echo json_encode(['error' => 'Некорректный запрос.']);
    }
} catch (Exception $e) {
    // Обработка ошибок декодирования токена
    echo json_encode(['success' => false, 'error' => 'Ошибка декодирования токена: ' . $e->getMessage()]);
}


?>