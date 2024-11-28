<?php
require_once 'db.php';

function getPlaces() {
    $query = "SELECT id, address FROM places";
    return executeQuery($query);
}

function getFilteredData($numericFilter, $selectedIndex, $textFilter, $textFilter1) {
    $query = "SELECT p.pic_path, p.name, pl.address, p.recipe, p.price 
              FROM products p 
              JOIN places pl ON p.place = pl.id 
              WHERE 1=1";

    if (!empty($numericFilter)) {
        $query .= " AND p.price = '" . mysqli_real_escape_string(getDbConnection(), $numericFilter) . "'";
    }
    if (!empty($selectedIndex)) {
        $query .= " AND p.place = '" . mysqli_real_escape_string(getDbConnection(), $selectedIndex) . "'";
    }
    if (!empty($textFilter)) {
        $query .= " AND p.name LIKE '%" . mysqli_real_escape_string(getDbConnection(), $textFilter) . "%'";
    }
    if (!empty($textFilter1)) {
        $query .= " AND p.recipe LIKE '%" . mysqli_real_escape_string(getDbConnection(), $textFilter1) . "%'";
    }

    return executeQuery($query);
}

function getAllData() {
    $query = "SELECT p.id, p.pic_path, p.name, pl.address, p.recipe, p.price 
              FROM products p 
              JOIN places pl ON p.place = pl.id";
    return executeQuery($query);
}
function addRecord($name, $address_id, $recipe, $price, $pic_path) {
    $mysqli = getDbConnection();
    $query = "SELECT MAX(id) as max_id FROM products";
    $result = $mysqli->query($query);

    if (!$result) {
        die('Ошибка выполнения запроса: ' . htmlspecialchars($mysqli->error));
    }

    $row = $result->fetch_assoc();
    $new_id = $row['max_id'] ? $row['max_id'] + 1 : 1;

    $stmt = $mysqli->prepare("INSERT INTO products (id, name, place, recipe, price, pic_path) VALUES (?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die('Ошибка подготовки запроса: ' . htmlspecialchars($mysqli->error));
    }

    if (empty($name) || empty($address_id) || empty($recipe) || empty($price) || empty($pic_path)) {
        die('Ошибка: Все поля должны быть заполнены.');
    }

    $stmt->bind_param("issdss", $new_id, $name, $address_id, $recipe, $price, $pic_path);

    if (!$stmt->execute()) {
        die('Ошибка выполнения запроса: ' . htmlspecialchars($stmt->error));
    }

    $stmt->close();
    $mysqli->close();
}



function getRecordById($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $record;
}

function editRecord($id, $name, $address_id, $recipe, $price, $pic_path) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE products SET name=?, place=?, recipe=?, price=?, pic_path=? WHERE id=?");

    if ($stmt === false) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }

    $stmt->bind_param("ssiisi", $name, $address_id, $recipe, $price, $pic_path, $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        echo "Ошибка обновления записи или запись не найдена.";
    } else {
        echo "Запись обновлена успешно!";
    }

    $stmt->close();
    $conn->close();
}

function recordExists($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    return $count > 0;
}

function deleteRecord($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");

    if ($stmt === false) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
    } else {
        echo "Запись с ID $id не найдена или уже удалена.";
    }

    $stmt->close();
    $conn->close();
}

?>
