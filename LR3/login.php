<?php
include "logic.php"; // Сначала включаем логику, чтобы инициализировать сессию
include "header.php"; // Затем включаем заголовок
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Авторизация</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <?php if (isset($_SESSION['username'])): ?>
        <h1 class="text-center">Вы уже авторизованы как <?php echo htmlspecialchars($_SESSION['username']); ?>.</h1>
        <p class="text-center">
            <a href="logout.php">Выйти</a>
        </p>
    <?php else: ?>
        <h1 class="text-center">Авторизация</h1>
        <form method="post" class="mt-4">
            <div class="form-group">
                <label for="username">Логин:</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="login">Войти</button>
        </form>
        <p class="text-center mt-3">
            <a href="register.php">Зарегистрироваться</a>
        </p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
include "footer.php"; // Включаем подвал
?>

