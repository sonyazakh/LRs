<?php
include "header.php";
?>

    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Регистрация</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5">
        <h2>Регистрация</h2>
        <form action="logic.php" method="POST">
            <div class="form-group">
                <label for="fullname">ФИО:</label>
                <input type="text" class="form-control" name="fullname" required>
            </div>

            <div class="form-group">
                <label for="dob">Дата рождения:</label>
                <input type="date" class="form-control" name="dob" required>
            </div>

            <div class="form-group">
                <label for="address">Адрес:</label>
                <input type="text" class="form-control" name="address" required>
            </div>

            <div class="form-group">
                <label for="gender">Пол:</label>
                <select class="form-control" name="gender" required>
                    <option value="male">Мужской</option>
                    <option value="female">Женский</option>
                    <option value="other">Другой</option>
                </select>
            </div>

            <div class="form-group">
                <label for="username">Имя пользователя:</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Подтверждение пароля:</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </form>
    </div>

    <!-- Подключение Bootstrap JS и jQuery (необязательно, но рекомендуется для некоторых компонентов) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
    </html>


<?php
include "footer.php";
?>