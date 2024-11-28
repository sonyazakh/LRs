<?php
function getDbConnection() {
    $host = 'localhost:3325';
    $database = 'goods';
    $user = 'root';
    $password = '';

    $mysqli = mysqli_connect($host, $user, $password, $database);


    if ($mysqli->connect_error) {
        die("Ошибка подключения: " . $mysqli->connect_error);
    }

    return $mysqli;
}

function executeQuery($query) {
    $db = getDbConnection();
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


function IsLoggedIn()
{
    return isset($_SESSION['username']);
}

?>
