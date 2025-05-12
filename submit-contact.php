<?php
$mysql = new mysqli('localhost', 'root', 'root', 'login-bd');
if ($mysql->connect_error) {
    die("Ошибка подключения: " . $mysql->connect_error);
}

$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$email = trim($_POST['email']);
$subject = trim($_POST['subject']);
$message = trim($_POST['message']);

if (empty($name) || empty($phone) || empty($email) || empty($subject) || empty($message)) {
    die("Пожалуйста, заполните все поля формы.");
}

$stmt = $mysql->prepare("INSERT INTO contact_requests (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $phone, $email, $subject, $message);

if ($stmt->execute()) {
    echo "Ваша заявка успешно отправлена!";
} else {
    echo "Ошибка при отправке: " . $stmt->error;
}

$stmt->close();
$mysql->close();
?>
