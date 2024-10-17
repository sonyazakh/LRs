<?php
$host = 'localhost:3325'; // Адрес сервера базы данных
$db = 'goods'; // Имя базы данных
$user = 'root'; // Имя пользователя базы данных
$pass = ''; // Пароль пользователя базы данных
$charset = 'utf8mb4'; // Кодировка

$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; // DSN (Data Source Name)
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Устанавливаем режим ошибок
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Устанавливаем режим получения данных PDO::ATTR_EMULATE_PREPARES   => false, // Отключаем эмуляцию подготовленных запросов
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options); // Создаем новое соединение
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode()); // Обработка ошибок подключения
}
?>

