<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); } ob_start();

require_once 'usertable.php';
require_once 'logic.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['numericFilter']) || isset($_GET['selectFilter']) || isset($_GET['textFilter'])) {
    $data = getFilteredData(
        $_GET['numericFilter'] ?? '',
        $_GET['selectFilter'] ?? '',
        $_GET['textFilter'] ?? '',
        ''
    );
} else {
    $data = getAllData();
}

if (!empty($data)) {
    echo "<table>";
    echo "<tr>";
    echo "<th>Изображение</th>";
    echo "<th>Название</th>";
    echo "<th>Локация</th>";
    echo "<th>Рецептура</th>";
    echo "<th>Стоимость</th>";
    echo "</tr>";

    foreach ($data as $row) {
        echo "<tr>";
        echo "<td><img src='pics/" . htmlspecialchars($row['pic_path']) . "' width='200' height='200'></td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['adress']) . "</td>";
        echo "<td>" . htmlspecialchars($row['recipe']) . "</td>";
        echo "<td>" . htmlspecialchars($row['price']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "Нет данных";
}
?>
