<?php
session_start();
session_unset(); // Удаляем все переменные сессии
session_destroy(); // Уничтожаем сессию
header("Location: login.php"); // Перенаправляем на страницу авторизации
exit();
?>
