<?php
header('Access-Control-Allow-Origin: https://localhost:5173');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Укажите методы, которые вы хотите разрешить
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Укажите заголовки, которые вы хотите разрешить


include './classes/DB.php';
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

if (isset($_COOKIE['token'])) {
    $token = $_COOKIE['token']; // Получаем токен из cookie
    $secretKey = "your_secret_key"; // Секретный ключ

    try {
        $jwt = new JWT($secretKey); // Создаем экземпляр JWT с секретным ключом
        $decodedPayload = $jwt->decode($token); // Декодируем токен
        echo json_encode(['success' => true, 'data' => $decodedPayload]); // Возвращаем успешный ответ с декодированными данными
    } catch (Exception $e) {
        // Обработка ошибок декодирования токена
        echo json_encode(['success' => false, 'error' => 'Ошибка декодирования токена: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Токен не найден.']);
}


?>