<?php
define('ROOT_PATH', dirname(__DIR__, 2));
require ROOT_PATH . '/config/db.php';
require ROOT_PATH . '/views/layouts/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = ($_POST['']);
    $email = ($_POST['']);
    $password = ($_POST['']);
    $role = (['']);
}
if (empty($user_name) || empty($password)) {
    $error = "Vui lòng nhập đầy đủ thông tin";
} else {
    // kiểm tra username tồn tại chưa
    $stmt = $conn->prepare("SELECT id FROM users FROM users WHERE user_name = ?"); // hàm pepare chuẩn bị câu lệnh
    $stmt->execute([$user_name]);
    if ($stmt->rowCount() > 0) {
        $error = "User đã tồn tại";
    } else {
        // hash mật khẩu
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (user_name");
    }
}
?>
<form action="" method="POST">
    <input type="text" name="" placeholder="User Name">
    <input type="email" name="" placeholder="Email">
    <input type="password" name="" placeholder="Password">
    <button type="" name="submit">Register</button>
</form>