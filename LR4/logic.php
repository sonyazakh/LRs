<?php
function getPlaces() {
    $host = 'localhost:3325';
    $database = 'goods';
    $user = 'root';
    $password = '';

    $link = mysqli_connect($host, $user, $password, $database);

    if (!$link) {
        die('Ошибка подключения к базе данных: ' . mysqli_connect_error());
    }

    $query = "SELECT id, adress FROM places";
    $result = mysqli_query($link, $query);

    $places = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $places[] = $row;
    }

    mysqli_close($link);

    return $places;
}

function getFilteredData($numericFilter, $selectedIndex, $textFilter, $textFilter1) {
    $db = mysqli_connect("localhost:3325", "root", "", "goods");

    if (!$db) {
        die('Ошибка подключения к базе данных: ' . mysqli_connect_error());
    }

    $query = "SELECT p.pic_path, p.name, pl.adress, p.recipe, p.price 
              FROM products p 
              JOIN places pl ON p.place = pl.id 
              WHERE 1=1";

    if (!empty($numericFilter)) {
        $query .= " AND p.price = '" . mysqli_real_escape_string($db, $numericFilter) . "'";
    }
    if (!empty($selectedIndex)) {
        $query .= " AND p.place = '" . mysqli_real_escape_string($db, $selectedIndex) . "'";
    }
    if (!empty($textFilter)) {
        $query .= " AND p.name LIKE '%" . mysqli_real_escape_string($db, $textFilter) . "%'";
    }
    if (!empty($textFilter1)) {
        $query .= " AND p.recipe LIKE '%" . mysqli_real_escape_string($db, $textFilter1) . "%'";
    }

    // Выполнение запроса
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


function getAllData() {
    $db = mysqli_connect("localhost:3325", "root", "", "goods");

    if (!$db) {
    die('Ошибка подключения к базе данных: ' . mysqli_connect_error());
}

$query = "SELECT p.pic_path, p.name, pl.adress, p.recipe, p.price 
FROM products p JOIN places pl ON p.place = pl.id";

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
?>
