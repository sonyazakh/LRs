<?php
ob_start(); // Начинаем буферизацию вывода

$host = 'localhost:3325';
$db = 'goods';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$data = []; // Инициализация массива

if ($result->num_rows > 0) {
    // Извлечение данных из результата и добавление в массив
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "Нет данных для экспорта.";
    exit;
}

if (isset($_POST['file_type'])) {
    $file_type = $_POST['file_type'];

    switch ($file_type) {
        case 'json':
            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename="products_exported.json"');

            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;

        case 'csv':
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="products_exported.csv"');
            $output = fopen('php://output', 'w');
            fputcsv($output, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
            exit;

        case 'xml':
            header('Content-Type: text/xml');
            header('Content-Disposition: attachment; filename="products_exported.xml"');
            $xml = new SimpleXMLElement('<root/>');
            array_walk_recursive($data, array($xml, 'addChild'));
            print $xml->asXML();
            exit;

        default:
            echo "Неверный формат.";
            exit;
    }
}

include "header.php";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Экспорт данных</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Экспорт данных</h1>
    <form method="post" class="mt-4">
        <div class="form-group">
            <label for="file_type">Выберите формат для экспорта:</label>
            <select name="file_type" id="file_type" class="form-control">
                <option value="json">JSON</option>
                <option value="csv">CSV</option>
                <option value="xml">XML</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Экспорт</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
include "footer.php";
$conn->close();
ob_end_flush(); // Завершаем буферизацию вывода
?>
