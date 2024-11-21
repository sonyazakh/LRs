<?php
include "header.php";
require 'logic.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (recordExists($id)) {
        deleteRecord($id);
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
<p>Запись с ID <?php echo htmlspecialchars($id); ?> была удалена.</p>
<a href="view.php">Назад к списку</a>
</body>
</html>

<?php
include "footer.php";
?>
