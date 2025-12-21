<?php
    define('ROOT_PATH', dirname(__DIR__, 2));
    require ROOT_PATH . '/config/db.php';
    require ROOT_PATH . '/views/layouts/header.php';

    if ($_SESSION['role'] != 'admin') {
        echo "Bạn không có quyền truy cập trang này";
        exit();
    }

    $error = '';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $code = $_POST['student_code'];
        $name = $_POST['full_name'];
        $dob = $_POST['birthday'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $sql = "INSERT INTO students (student_code, full_name, birthday, gender, ";
    }
?>