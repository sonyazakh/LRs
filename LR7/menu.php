<?php
session_start();
ob_start();

require_once 'db.php';
require_once 'usertable.php';
require_once 'logic.php';
if (!IsLoggedIn()) {
    header("Location: login.php");
    exit();
}

$places = getPlaces();

// Обработка фильтров
$numericFilter = $_GET['numericFilter'] ?? '';
$selectFilter = $_GET['selectFilter'] ?? '';
$textFilter = $_GET['textFilter'] ?? '';

// Получение данных с учетом фильтров
if (!empty($numericFilter) || !empty($selectFilter) || !empty($textFilter)) {
    $data = getFilteredData($numericFilter, $selectFilter, $textFilter, ''); // Пустая строка для textFilter1
} else {
    $data = getAllData();
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Меню</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/main.css">
</head>
<body>
<header>
    <?php include 'header.php'; ?>
</header>

<form action="menu.php" method="GET">
    <div class="row">
        <div class="col-md-4">
            <label for="numeric-filter">Фильтрация по цене</label>
            <input type="number" id="numeric-filter" name="numericFilter" class="form-control" placeholder="Введите цену" value="<?= htmlspecialchars($numericFilter) ?>">
        </div>
        <div class="col-md-4">
            <label for="select-filter">Фильтрация по локации</label>
            <select id="select-filter" name="selectFilter" class="form-control">
                <option value="">Выберите локацию</option>
                <?php foreach ($places as $place): ?>
                    <option value="<?= $place['id'] ?>" <?= ($place['id'] == $selectFilter) ? 'selected' : '' ?>><?= htmlspecialchars($place['adress']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="text-filter">Фильтрация по названию</label>
            <input type="text" id="text-filter" name="textFilter" class="form-control" placeholder="Введите название" value="<?= htmlspecialchars($textFilter) ?>">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Фильтровать</button>
    <button type="button" class="btn btn-default" onclick="clearFilters()">Очистить</button>
</form>

<h1>Меню</h1>
<div id="table-container">
    <?php include 'ttable.php'; ?>
</div>
</body>
</html>
