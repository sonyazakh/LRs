<?php
ob_start();
session_start(); // Переместите это в начало файла

global $pdo;
require 'db.php'; // файл для подключения к БД

// Инициализация переменных
$fullname = $dob = $address = $gender = $username = $email = $password = $confirm_password = "";
$errors = [];

// Обработка формы при отправке
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $dob = trim($_POST['dob']);
    $address = trim($_POST['address']);
    $gender = trim($_POST['gender']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Проверка на пустые поля
    if (empty($fullname) || empty($dob) || empty($address) || empty($gender) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "Пожалуйста, заполните все поля.";
    }

    // Проверка валидности email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Некорректный email.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Пароли не совпадают.";
    }

    if (strlen($password) <= 6 ||
        !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[\W_]/', $password)) {
        $errors[] = "Пароль должен содержать больше 6 символов, включать большие и маленькие латинские буквы и спецсимволы.";
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() > 0) {
        $errors[] = "Пользователь с таким email уже существует.";
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);

    if ($stmt->rowCount() > 0) {
        $errors[] = "Пользователь с таким логином уже существует.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (fullname, dob, address, gender, username, email, password) VALUES (:fullname, :dob, :address, :gender, :username, :email, :password)");

        if ($stmt->execute(['fullname' => $fullname, 'dob' => $dob, 'address' => $address, 'gender' => $gender, 'username' => $username, 'email' => $email, 'password' => $hashed_password])) {
            echo "Регистрация успешна! Теперь вы можете <a href='login.php'>войти</a>.";
            exit; // Завершаем выполнение скрипта после успешной регистрации
        } else {
            $errors[] = "Ошибка при регистрации. Попробуйте снова.";
        }
    }
}

// Вывод ошибок, если они есть
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
}

// Проверка, авторизован ли пользователь
if (isset($_SESSION['username'])) {
    header("Location: menu.php");
    exit();
}

// Инициализация переменных
$username = $password = "";
$errors = [];

// Проверка, установлен ли REQUEST_METHOD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $result = login($username, $password);

        if ($result === true) {
            header("Location: menu.php");
            exit();
        } else {
            $errors[] = htmlspecialchars($result);
        }
    }
}

function login($username, $password) {
    global $pdo;

    if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3 && time() < $_SESSION['block_time']) {
        return "Вы заблокированы на 1 час.";
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        return "Логина нет в системе.";
    }

    if (!password_verify($password, $user['password'])) {
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        return "Неверный пароль.";
    }

    // Сбрасываем количество попыток
    unset($_SESSION['login_attempts']);
    unset($_SESSION['block_time']);

    $_SESSION['username'] = $user['username'];
    return true;
}

// Вывод ошибок, если они есть
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
}

function getFilteredData($numericFilter, $selectedIndex, $textFilter, $textFilter1) {
    $db = mysqli_connect("localhost:3325", "root", "", "goods");
    $query = "SELECT p.pic_path, p.name, pl.adress, p.recipe, p.price 
        FROM products p 
        JOIN places pl ON p.place = pl.id 
        WHERE ";

    if (!empty($numericFilter)) {
        $query .= "p.price = '$numericFilter' AND ";
    }
    if (!empty($selectedIndex)) {
        $query .= "p.place = '$selectedIndex' AND ";
    }
    if (!empty($textFilter)) {
        $query .= "p.name LIKE '%$textFilter%' AND ";
    }
    if (!empty($textFilter1)) {
        $query .= "p.recipe LIKE '%$textFilter1%' AND ";
    }
    if (empty($textFilter) && empty($selectedIndex) && empty($textFilter1) && empty($numericFilter)) {
        $query = "SELECT p.pic_path, p.name, pl.adress, p.recipe, p.price 
        FROM products p 
        JOIN places pl ON p.place = pl.id";
    }

    $query = rtrim($query, " AND ");

    $result = mysqli_query($db, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($db));
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_close($db);
    return $data;
}
function getPlaces() {
    $host = 'localhost:3325';
    $database = 'goods';
    $user = 'root';
    $password = '';

    $dsn = "mysql:host=$host;dbname=$database";
    $pdo = new PDO($dsn, $user, $password);

    $query = "SELECT id, adress FROM places";
    $stmt = $pdo->prepare($query);

    if (!$stmt) {
        echo "Error preparing query: " . $pdo->errorInfo()[2];
        return array();
    }

    $stmt->execute();

    if (!$stmt) {
        echo "Error executing query: " . $pdo->errorInfo()[2];
        return array();
    }

    $places = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $places;
}
function getAllData() {
    $db = mysqli_connect("localhost:3325", "root", "", "goods");
    $query = "SELECT p.pic_path, p.name, pl.adress, p.recipe, p.price 
        FROM products p 
        JOIN places pl ON p.place = pl.id";
    $result = mysqli_query($db, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($db));
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_close($db);
    return $data;
}


ob_end_flush();
?>
