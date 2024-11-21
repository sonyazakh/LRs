<?php
require_once 'db.php';

class UserTable {
    public function registerUser ($fullname, $dob, $address, $gender, $username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (fullname, dob, address, gender, username, email, password) 
                  VALUES ('$fullname', '$dob', '$address', '$gender', '$username', '$email', '$hashedPassword')";
        return executeQuery($query);
    }

    public function checkUser_Exists($email, $username) {
        $query = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
        $result = executeQuery($query);
        return count($result) > 0;
    }

public function loginUser ($username, $password) {
    $query = "SELECT * FROM users WHERE username = '$username'";
    $user = executeQuery($query);

    if (!empty($user) && password_verify($password, $user[0]['password'])) {
        return $user[0];
    }
    return false;
}
}
?>
