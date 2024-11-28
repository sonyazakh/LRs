<?php
include "header.php";
require 'logic.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (recordExists($id)) {
        $record = getRecordById($id);
        $pic_path = $record['pic_path'];
        deleteRecord($id);

        if (!empty($pic_path)) {
            unlink($pic_path);
        }

        echo "<p>Запись с ID $id была удалена.</p>";
    } else {
        echo "<p>Запись с ID $id не найдена.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Удалить запись</title>
</head>
<body>
<h1>Удалить запись</h1>
<a href="view.php">Назад к списку</a>
</body>
</html>

<?php
include "footer.php";
?>
