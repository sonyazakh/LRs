<?php
include "header.php";
require 'logic.php';

$id = $_GET['id'];
$item = getRecordById($id);

$places = getPlaces();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address_id = $_POST['address'];
    $recipe = $_POST['recipe'];
    $price = $_POST['price'];
    $pic_path = $_POST['pic_path'];

    editRecord($id, $name, $address_id, $recipe, $price, $pic_path);
    echo "<div class='alert alert-success'>Запись обновлена успешно!></div>";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Редактировать запись</title>
</head>
<body>
<div class="container mt-5">
    <h1>Редактировать запись</h1>
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="address">Адрес:</label>
            <select class="form-control" name="address" required> <!-- Изменено на 'address' -->
                <option value="">Выберите адрес</option>
                <?php foreach ($places as $place): ?>
                    <option value="<?php echo htmlspecialchars($place['id']); ?>" <?php echo ($place['id'] == $item['place']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($place['address']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="recipe">Рецепт:</label>
            <textarea class="form-control" name="recipe" required><?php echo htmlspecialchars($item['recipe']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Цена:</label>
            <input type="number" class="form-control" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" required>
        </div>

        <div class="form-group">
            <label for="pic_path">Путь к изображению:</label>
            <input type="text" class="form-control" name="pic_path" value="<?php echo htmlspecialchars($item['pic_path']); ?>" required>
        </div>

        <input type="submit" class="btn btn-primary" value="Обновить">
    </form>
    <a href="view.php">Назад к списку</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php
include "footer.php";
?>
