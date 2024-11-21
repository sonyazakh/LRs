<?php
require_once 'logic.php';
include "header.php";

$data = getAllData();

if ($data === null) {
    $data = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data View</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Данные товаров</h2>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Изображение</th>
            <th>Название</th>
            <th>Локация</th>
            <th>Рецептура</th>
            <th>Стоимость</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $item): ?>
            <tr>
                <td><img src="<?php echo htmlspecialchars($item['pic_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="100"></td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo htmlspecialchars($item['address']); ?></td>
                <td><?php echo htmlspecialchars($item['recipe']); ?></td>
                <td><?php echo htmlspecialchars($item['price']); ?> руб.</td>
                <td>
                    <a href="edit.php?id=<?php echo $item['id'] ?? 'default_id'; ?>" class="btn btn-primary">Редактировать</a>
                    <a href="delete.php?id=<?php echo $item['id'] ?? 'default_id'; ?>" class="btn btn-danger">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="add.php" class="btn btn-success mb-3">Добавить новый товар</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
include "footer.php";
?>
