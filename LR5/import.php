<?php
include "header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['file_url'];
    $fileType = pathinfo($url, PATHINFO_EXTENSION);
    $tableName = 'products_imported';

    $dsn = 'mysql:host=localhost;dbname=goods;port=3325;charset=utf8mb4';
    $username = 'root';
    $password = '';
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8mb4'");

    $tableExists = $pdo->query("SHOW TABLES LIKE '$tableName'")->rowCount() > 0;

    if (!$tableExists) {
        $createTableQuery = "
        CREATE TABLE $tableName (
            id INT PRIMARY KEY,
            pic_path VARCHAR(255),
            name VARCHAR(255),
            place INT,
            recipe TEXT,
            price DECIMAL(10, 2)
        )";

        $pdo->exec($createTableQuery);
        echo "Таблица $tableName была создана.";
    }

    if ($fileType === 'json') {
        $jsonContent = @file_get_contents($url);
        if ($jsonContent === false) {
            die("Не удалось получить файл по URL: $url");
        }

        if (!mb_check_encoding($jsonContent, 'UTF-8')) {
            $jsonContent = mb_convert_encoding($jsonContent, 'UTF-8', 'auto');
        }

        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Ошибка декодирования JSON: " . json_last_error_msg());
        }
    } else {
        echo "Неверный формат файла.";
        exit;
    }

    $stmtInsert = $pdo->prepare("
        INSERT INTO $tableName (id, pic_path, name, place, recipe, price) 
        VALUES (:id, :pic_path, :name, :place, :recipe, :price) 
        ON DUPLICATE KEY UPDATE 
            pic_path = :pic_path, 
            name = :name, 
            place = :place, 
            recipe = :recipe, 
            price = :price
    ");

    foreach ($data as $row) {
        if (isset($row['id'], $row['image_path'], $row['name'], $row['category_id'], $row['description'], $row['price'])) {
            $stmtInsert->execute([
                ':id' => $row['id'],
                ':pic_path' => $row['image_path'],
                ':name' => $row['name'],
                ':place' => $row['category_id'],
                ':recipe' => $row['description'],
                ':price' => $row['price']
            ]);
        } else {
            echo "Недостаточно данных в строке: " . json_encode($row) . "\n";
        }
    }

    echo "Данные успешно импортированы.";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Импорт файла по URL</title>
</head>
<body>
<div class="container mt-5">
    <h2>Импорт файла по URL</h2>
    <form action="import.php" method="post">
        <div class="form-group">
            <label for="file_url">Введите URL файла:</label>
            <input type="text" class="form-control" id="file_url" name="file_url" placeholder="http://localhost/LR5/data.json" required>
        </div>
        <button type="submit" class="btn btn-primary">Импортировать</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
include "footer.php";
?>
