<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = filter_var(trim($_POST['username']));
$email = filter_var(trim($_POST['email']));
$password = filter_var(trim($_POST['password']));

// Проверка длины логина и пароля
if (mb_strlen($username) < 5 || mb_strlen($username) > 90) {
    header("Location: registr.html?error=username_length");
    exit();
}

if (mb_strlen($password) < 3 || mb_strlen($password) > 10) {
    header("Location: registr.html?error=password_length");
    exit();
}

// Подключение к базе данных
$mysql = new mysqli('localhost', 'root', 'root', 'login-bd');

// Проверка на существующий username или email
$result = $mysql->query("SELECT * FROM `users` WHERE `username` = '$username' OR `email` = '$email'");
if ($result->num_rows > 0) {
    $mysql->close();
    header("Location: registr.html?error=exists");
    exit();
}

// Хешируем пароль перед сохранением
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Вставка нового пользователя
$success = $mysql->query("INSERT INTO `users` (`username`, `email`, `password`) VALUES('$username', '$email', '$password')");

$mysql->close();

if ($success) {
    header("Location: index.html");
} else {
    header("Location: registr.html?error=database");
}
exit();
?>
