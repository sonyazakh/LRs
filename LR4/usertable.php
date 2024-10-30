<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); } ob_start();

require_once 'db.php';

$errors = [];

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['block_time'])) {
    $_SESSION['block_time'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $dob = trim($_POST['dob']);
    $address = trim($_POST['address']);
    $gender = trim($_POST['gender']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($fullname) || empty($dob) || empty($address) || empty($gender) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "Пожалуйста, заполните все поля.";
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Некорректный email.";
    }


    if ($password !== $confirm_password) {
        $errors[] = "Пароли не совпадают.";
    }

    if (strlen($password) <= 6 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[\W_]/', $password)) {
        $errors[] = "Пароль должен содержать больше 6 символов, включать большие и маленькие латинские буквы и спецсимволы.";
    }

    if (!checkUser_Exists($email, $username)) {
        if (registerUser ($fullname, $dob, $address, $gender, $username, $email, $password)) {
            echo "Регистрация успешна! Теперь вы можете <a href='login.php'>войти</a>.";
            exit;
        } else {
            $errors[] = "Ошибка при регистрации. Попробуйте снова.";
        }
    } else {
        $errors[] = "Пользователь с таким email или логином уже существует.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($_SESSION['login_attempts'] >= 3 && time() < $_SESSION['block_time']) {
        $errors[] = "Вы заблокированы на 1 час.";
    } else {
        if (time() >= $_SESSION['block_time']) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['block_time'] = 0;
        }

        // Попытка входа
        $user = loginUser ($username, $password);
        if ($user) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['login_attempts'] = 0;
            header("Location: menu.php");
            exit();
        } else {
            $_SESSION['login_attempts']++;
            if ($_SESSION['login_attempts'] >= 3) {
                $_SESSION['block_time'] = time() + 3600;
                $errors[] = "Вы заблокированы на 1 час.";
            }
            $errors[] = "Неверный логин или пароль.";
        }
    }
}


if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
}

function registerUser ($fullname, $dob, $address, $gender, $username, $email, $password) {
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        return false;
    }

    $stmt = $pdo->prepare('INSERT INTO users (fullname, dob, address, gender, username, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)');
    return $stmt->execute([$fullname, $dob, $address, $gender, $username, $email, $password]);
}

function checkUser_Exists($email, $username) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
    $stmt->execute(['email' => $email, 'username' => $username]);

    return $stmt->rowCount() > 0;
}

function loginUser ($username, $password) {
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Проверка времени блокировки
        if ($user['block_time'] && strtotime($user['block_time']) > time()) {
            return false; // Пользователь заблокирован
        }

        // Проверка пароля
        if (password_verify($password, $user['password'])) {
            // Сброс попыток и времени блокировки при успешном входе
            $stmt = $pdo->prepare('UPDATE users SET login_attempts = 0, block_time = NULL WHERE username = ?');
            $stmt->execute([$username]);
            return $user;
        }
    }
    return false;
}

function IsLoggedIn() {
    return isset($_SESSION['username']);
}

function login_User($username, $password) {
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Проверка времени блокировки
        if ($user['block_time'] && strtotime($user['block_time']) > time()) {
            return false; // Пользователь заблокирован
        }

        // Проверка пароля
        if (password_verify($password, $user['password'])) {
            // Сброс попыток и времени блокировки при успешном входе
            $stmt = $pdo->prepare('UPDATE users SET login_attempts = 0, block_time = NULL WHERE username = ?');
            $stmt->execute([$username]);
            return $user;
        }
    }
    return false;
}



ob_end_flush();
?>
