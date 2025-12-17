<?php
    // cấu hình DB
    $host = 'localhost';
    $dbname = '';
    $username = 'root';
    $pass = '';

    try {
        // thực hiện kết nối mysql sử dụng PDO
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $pass);
        // kiểm tra lỗi kết nối
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) { // xử lý lỗi trả ra biến e để báo lỗi
        die("Lỗi kết nối: " . $e->getMessage()); // biến e in ra lỗi
    }
?>