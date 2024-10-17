<?php
include 'menu.php';

if (isset($_GET['numericFilter']) || isset($_GET['selectFilter']) || isset($_GET['textFilter']) || isset($_GET['textFilter1'])) {
    $data = getFilteredData($_GET['numericFilter'], $_GET['selectFilter'], $_GET['textFilter'], $_GET['textFilter1']);
} else {
    $data = getAllData();
}

if (!empty($data)) {
    echo "<table>";
    echo "<tr>";
    echo "<th>Изображение</th>";
    echo "<th>Название</th>";
    echo "<th>Локация</th>";
    echo "<th>Рецептура</th>";
    echo "<th>Стоимость</th>";
    echo "</tr>";

    foreach ($data as $row) {
        echo "<tr>";
        echo "<td><img src='pics/" . $row['pic_path'] . "' width='200' height='200'></td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['adress'] . "</td>";
        echo "<td>" . $row['recipe'] . "</td>";
        echo "<td>" . $row['price'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "Нет данных";
}
?>
