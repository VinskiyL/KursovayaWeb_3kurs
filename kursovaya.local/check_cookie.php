<?php
// Проверка куки
if (isset($_COOKIE['token'])) {
    echo "Кука установлена: " . $_COOKIE['token'];
} else {
    echo "Кука не найдена.";
}
?>
