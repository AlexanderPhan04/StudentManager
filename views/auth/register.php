<?php
    require_once 'config\db.php';
    require_once 'views\layouts\header.php';
    
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