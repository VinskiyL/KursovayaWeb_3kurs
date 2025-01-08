
<?php
// Установка куки
setcookie("token", "your_jwt_token", time() + 3600, "/");
echo "Кука установлена. Перейдите на страницу проверки.";
?>
<a href="check_cookie.php">Проверить куку</a>

?>