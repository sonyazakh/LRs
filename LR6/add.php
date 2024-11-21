<?php
include "header.php";
require 'logic.php';

$places = getPlaces();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['address']) && isset($_POST['recipe']) && isset($_POST['price']) && isset($_POST['pic_path'])) {
        $name = $_POST['name'];
        $address_id = $_POST['address'];
        $recipe = $_POST['recipe'];
        $price = $_POST['price'];
        $pic_path = $_POST['pic_path'];

        addRecord($name, $address_id, $recipe, $price, $pic_path);
        echo "<div class='alert alert-success'>Запись добавлена успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Пожалуйста, заполните все поля.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить запись</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Добавить запись</h1>
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="form-group">
            <label for="address">Адрес:</label>
            <select class="form-control" name="address" required>
                <option value="">Выберите адрес</option>
                <?php foreach ($places as $place): ?>
                    <option value="<?php echo htmlspecialchars($place['id']); ?>">
                        <?php echo htmlspecialchars($place['address']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="recipe">Рецепт:</label>
            <textarea class="form-control" name="recipe" required></textarea>
        </div>

        <div class="form-group">
            <label for="price">Цена:</label>
            <input type="number" class="form-control" name="price" required>
        </div>

        <div class="form-group">
            <label for="pic_path">Путь к изображению:</label>
            <input type="text" class="form-control" name="pic_path" required>
        </div>

        <input type="submit" class="btn btn-primary" value="Добавить">
    </form>
    <a href="view.php">Назад к списку</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
include "footer.php";
?>
