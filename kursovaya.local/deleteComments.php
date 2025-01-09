<?php
header('Access-Control-Allow-Origin: https://localhost:5173');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Укажите методы, которые вы хотите разрешить
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Укажите заголовки, которые вы хотите разрешить

include './classes/DB.php';
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

$token = $_COOKIE['token']; // Получаем токен из cookie
$secretKey = "your_secret_key"; // Секретный ключ
try {
    $jwt = new JWT($secretKey); // Создаем экземпляр JWT с секретным ключом
    $decodedPayload = $jwt->decode($token);
    $username = $decodedPayload['sub'];
    if ($_SERVER["REQUEST_METHOD"] === 'GET') {
        $id = $_GET['id'] ?? ''; // Получаем параметр query из URL
        if (!$id) {
            echo json_encode(['error' => 'Параметр id отсутствует.']);
            exit;
        }
        $dbconn3 = new DB("newDB");
        $result = $dbconn3 -> deleteComments($id, $username);
        $dbconn3->closeConn();
        echo json_encode(['success' => $result]); // Возвращаем успешный ответ с декодированными данными
    } else {
            echo json_encode(['error' => 'Некорректный запрос.']);
        }
} catch (Exception $e) {
    // Обработка ошибок декодирования токена
    echo json_encode(['success' => false, 'error' => 'Ошибка декодирования токена: ' . $e->getMessage()]);
}


?>