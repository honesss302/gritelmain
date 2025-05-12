<?php
$mysql = new mysqli('localhost', 'root', 'root', 'login-bd');
if ($mysql->connect_error) {
    die("Ошибка подключения к базе данных: " . $mysql->connect_error);
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    header("Location: login.html");
    exit();
}

$stmt = $mysql->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: login.html");
    exit();
}

$user = $result->fetch_assoc();

if ($user['password'] === $password) {
    setcookie('user_id', $user['id'], time() + 86400, '/');
    setcookie('user_login', $user['username'], time() + 86400, '/');

    header("Location: index.html");
    exit();
} else {
    header("Location: login.html");
    exit();
}

$mysql->close();
?>
