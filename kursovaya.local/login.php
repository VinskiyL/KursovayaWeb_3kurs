<?php
include './classes/DB.php';
require 'vendor/autoload.php'; // Убедитесь, что вы установили библиотеку для работы с JWT

use \Firebase\JWT\JWT;

header('Access-Control-Allow-Methods: GET, PUT, POST');
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    $dbconn3 = new DB("newDB");
    $username = htmlspecialchars(trim($_GET['username']));
    $password = htmlspecialchars(trim($_GET['password']));

    if (!empty($username) && !empty($password)) {
        // Выполняем запрос к базе данных для проверки пользователя
        $result = $dbconn3->login($username);
        // Проверяем, существует ли пользователь
        if ($result->rowCount() > 0) {
            // Получаем хеш пароля из базы
            $user = pg_fetch_assoc($result);
            $hashedPassword = $user['password']; // Предполагается, что в базе хранятся хеши паролей

            // Проверяем, совпадает ли введенный пароль с хешом
            if (password_verify($password, $hashedPassword)) {
                // Успешная аутентификация
                // Создаем токен
                $key = "your_secret_key"; // Секретный ключ для подписи токена
                $payload = [
                    'iat' => time(), // Время создания токена
                    'exp' => time() + 3600, // Время истечения токена (1 час)
                    'username' => $username // Пользовательские данные
                ];

                $jwt = JWT::encode($payload, $key);

                // Сохраняем токен в базе данных
                $stmt = $dbconn3->saveToken($username, $jwt);

                $dbconn3->closeConn();
                // Устанавливаем куку с токеном
                setcookie("token", $jwt, time() + 3600, "/", "", true, true);

                echo json_encode([
                    "message" => "Успешно аутентифицированы",
                    "token" => $jwt // Возвращаем JWT клиенту
                ]);
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
        echo json_encode(["error" => $error]);
    }
}
?>




