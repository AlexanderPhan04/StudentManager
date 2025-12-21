<?php
    require_once 'config/db.php';
    require_once 'views\layouts\header.php';

    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $sql = "SELECT * FROM students WHERE full_name LIKE ? OR student_code LIKE ? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["%$search%", "%$search%"]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>