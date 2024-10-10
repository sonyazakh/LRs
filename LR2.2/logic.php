<?php
$numericFilter = $_GET['numericFilter'];
$selectedIndex = $_GET['selectFilter'];
$textFilter = $_GET['textFilter'];
$textFilter1 = $_GET['textFilter1'];

$data = getFilteredData($numericFilter, $selectedIndex, $textFilter, $textFilter1);

$html = "<table>";
$html .= "<tr><th>Изображение</th><th>Название</th><th>Локация</th><th>Рецептура</th><th>Стоимость</th></tr>";

if (!empty($data)) {
    foreach ($data as $row) {
        $html .= "<tr>";
        $html .= "<td><img src='pics/" . $row['pic_path'] . "' width='200' height='200'></td>";
        $html .= "<td>" . $row['name'] . "</td>";
        $html .= "<td>" . $row['adress'] . "</td>";
        $html .= "<td>" . $row['recipe'] . "</td>";
        $html .= "<td>" . $row['price'] . "</td>";
        $html .= "</tr>";
    }
} else {
    $html .= "<tr><td colspan='5'>Нет данных</td></tr>";
}

$html .= "</table>";

echo $html;

function getFilteredData($numericFilter, $selectedIndex, $textFilter, $textFilter1) {
    $db = mysqli_connect("localhost:3325", "root", "", "goods");
    $query = "SELECT p.pic_path, p.name, pl.adress, p.recipe, p.price 
        FROM products p 
        JOIN places pl ON p.place = pl.id 
        WHERE ";

    if (!empty($numericFilter)) {
        $query .= "p.price = '$numericFilter' AND ";
    }
    if (!empty($selectFilter)) {
        $query .= "p.place = '$selectedIndex' AND ";
    }
    if (!empty($textFilter)) {
        $query .= "p.name LIKE '%$textFilter%' AND ";
    }
    if (!empty($textFilter1)) {
        $query .= "p.recipe LIKE '%$textFilter1%' AND ";
    }
    if (empty($textFilter) && empty($selectFilter) && empty($textFilter1) && empty($numericFilter))
    {
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
