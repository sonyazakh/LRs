<?php
include "header.php";
require 'logic.php';

$places = getPlaces();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['address']) && isset($_POST['recipe']) && isset($_POST['price']) && isset($_FILES['pic_path'])) {
        $name = $_POST['name'];
        $address_id = $_POST['address'];
        $recipe = htmlspecialchars($_POST['recipe']);
        $price = $_POST['price'];

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["pic_path"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["pic_path"]["tmp_name"]);
        if($check === false) {
            echo "<div class='alert alert-danger'>Файл не является изображением.</div>";
            $uploadOk = 0;
        }

        if (file_exists($target_file)) {
            echo "<div class='alert alert-danger'>Изображение уже существует.</div>";
            $uploadOk = 0;
        }

        if ($_FILES["pic_path"]["size"] > 500000) {
            echo "<div class='alert alert-danger'>Изображение слишком большое.</div>";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "<div class='alert alert-danger'>Разрешены только JPG, JPEG, PNG и GIF файлы.</div>";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["pic_path"]["tmp_name"], $target_file)) {
                $relative_path = $target_file;
                addRecord($name, $address_id, $recipe, $price, $relative_path);
                echo "<div class='alert alert-success'>Запись добавлена успешно!</div>";
            } else {
                echo "<div class='alert alert-danger'>Произошла ошибка при загрузке файла.</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Пожалуйста, заполните все поля.</div>";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Название:</label>
        <input type="text" class="form-control" name="name" required>
    </div>
    <div class="form-group">
        <label for="address">Адрес:</label>
        <select class="form-control" name="address" required>
            <?php foreach ($places as $place): ?>
                <option value="<?php echo $place['id']; ?>"><?php echo $place['address']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="recipe">Рецепт:</label>
        <textarea type="text" class="form-control" name="recipe" required></textarea>
    </div>
    <div class="form-group">
        <label for="price">Цена:</label>
        <input type="number" class="form-control" name="price" required>
    </div>
    <div class="form-group">
        <label for="pic_path">Выберите изображение:</label>
        <input type="file" class="form-control-file" name="pic_path" accept="image/*" id="fileInput" required onchange="updateFilePath()">
        <input type="text" class="form-control mt-2" id="filePath" readonly placeholder="Выбранный файл будет отображен здесь">
    </div>
    <button type="submit" class="btn btn-primary">Добавить</button>
</form>

<script>
    function updateFilePath() {
        const fileInput = document.getElementById('fileInput');
        const filePath = document.getElementById('filePath');
        if (fileInput.files.length > 0) {
            filePath.value = fileInput.files[0].name;
        } else {
            filePath.value = '';
        }
    }
</script>


<?php
include "footer.php";
?>

