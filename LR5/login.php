<?php
global $pdo;
session_start();
ob_start();

include "header.php";
require_once 'db.php';
require_once 'usertable.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user = loginUser($username, $password);
    if ($user) {
        $_SESSION['username'] = $user['username'];
        header('Location: menu.php');
        exit;
    } else {
        $stmt = $pdo->prepare('UPDATE users SET login_attempts = login_attempts + 1 WHERE username = ?');
        $stmt->execute([$username]);

        $stmt = $pdo->prepare('SELECT login_attempts FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $attempts = $stmt->fetchColumn();

        if ($attempts >= 3) {
            $stmt = $pdo->prepare('UPDATE users SET block_time = NOW() + INTERVAL 1 HOUR WHERE username = ?');
            $stmt->execute([$username]);
            $error_message = "Вы заблокированы на 1 час.";
        } else {
            $error_message = "Неверный логин или пароль.";
        }
    }
}
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
    <h1 class="text-center">Авторизация</h1>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
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
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
include "footer.php";
?>
