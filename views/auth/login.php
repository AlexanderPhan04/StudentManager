<?php
    define('ROOT_PATH', dirname(__DIR__, 2));
    require ROOT_PATH . '/config/db.php';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['role'] = $user['role'];
            header('location: index.php');
            exit();
        } else {
            $error = "Tên dăng nhập hoặc mật khẩu không đúng";
        }
    }
?>
<form action="" method="post">
    <input type="text" name="username" placeholder="User Name">
    <input type="password" name="password" placeholder="Password">
    <button type="" name="submit">Login</button>
</form>