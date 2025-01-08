<?php

header('Access-Control-Allow-Methods: GET, PUT, POST, OPTIONS');
header('Content-Type: application/json');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Origin: https://localhost:5173');
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Укажите заголовки, которые вы хотите разрешить

include './classes/DB.php';
require 'vendor/autoload.php'; // Убедитесь, что вы установили библиотеку для работы с JWT
use \Firebase\JWT\JWT;

if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    $dbconn3 = new DB("newDB");
    $username = htmlspecialchars(trim($_GET['username']));
    $password = htmlspecialchars(trim($_GET['password']));

    if (!empty($username) && !empty($password)) {
        // Выполняем запрос к базе данных для проверки пользователя
        $result = $dbconn3->login($username);

        // Проверяем, существует ли пользователь
        if (count($result) > 0) {
            // Получаем хеш пароля из базы
            $user = $result[0]; // Предполагая, что $result - это массив, содержащий строки
            $hashedPassword = $user['password'];

            // Проверяем, совпадает ли введенный пароль с хешом
            if (password_verify($password, $hashedPassword)) {
                $jwt = new JWT('your_secret_key');
                $payload = [
                       "iat" => time(),
                       "exp" => time() + 3600, // токен будет действителен 1 час
                       "sub" => $username
                   ];

                $token = $jwt->encode($payload);

                // Сохраняем токен в базе данных
                $dbconn3->saveToken($username, $token);
                $dbconn3->closeConn();

                // Устанавливаем куку с токеном
                setcookie('token', $token, [
                    'expires' => time() + 3600,
                    'path' => '/',
                    'domain' => '',
                    'secure' => true,
                    'httponly' => true, // Убедитесь, что это значение false, если хотите доступ к кукам через JS
                    'samesite' => 'None'
                ]);

                // Возвращаем успешный ответ с данными пользователя
                echo json_encode(['success' => true, 'data' => $result]);
            } else {
                $error = "Неверный логин или пароль.";
            }
        } else {
            $error = "Пользователь не найден (проверьте логин и пароль)";
        }
    } else {
        $error = "Пожалуйста, заполните все поля.";
    }

    // Если есть ошибка, отправим её в формате JSON
    if (isset($error)) {
        echo json_encode(["success" => false, "error" => $error]);
    }
}
?>





