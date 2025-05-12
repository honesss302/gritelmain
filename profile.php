<?php
// Включаем буферизацию вывода — важно для работы header() ниже
ob_start();

// Подключение к БД
$mysqli = new mysqli("localhost", "root", "", "gritel");

if ($mysqli->connect_errno) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

// Получаем ID пользователя из cookie
if (!isset($_COOKIE['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = (int)$_COOKIE['user_id'];
$new_email = trim($_POST['new_email']);
$new_password = trim($_POST['new_password']);

// Если оба поля пустые — не продолжаем
if ($new_email === '' && $new_password === '') {
    header("Location: profile.html");
    exit();
}

// Обновляем данные
if ($new_email !== '' && $new_password !== '') {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("UPDATE users SET email = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssi", $new_email, $hashed_password, $user_id);
} elseif ($new_email !== '') {
    $stmt = $mysqli->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $new_email, $user_id);
} elseif ($new_password !== '') {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $user_id);
}

// Выполняем обновление
if ($stmt->execute()) {
    header("Location: profile.html?success=true");
    exit();
} else {
    echo "Ошибка при обновлении данных: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
ob_end_flush(); // Завершаем буферизацию
?>
