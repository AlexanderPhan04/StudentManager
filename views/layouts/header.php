<?php
// Kiểm tra session và phân quyền
session_start();
$current_page = basename($_SERVER['PHP_SELF']);

// Nếu chưa đăng nhập và không phải trang login/register thì chuyển về login
$action = $_GET['action'] ?? '';
if (!isset($_SESSION['user_id']) && !in_array($action, ['login', 'register'])) {
    header('Location: index.php?action=login');
    exit();
}

$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
$username = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sinh viên</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .header {
            background: #007bff;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 22px;
        }

        .nav {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .nav a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .nav .username {
            font-weight: bold;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }

        .btn:hover {
            background: #0056b3;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #1e7e34;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        table tr:hover {
            background: #f8f9fa;
        }

        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 10px;
            }

            .nav {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }

            table {
                font-size: 14px;
            }

            table th,
            table td {
                padding: 8px;
            }
        }
    </style>
</head>

<body>
    <?php if ($is_logged_in): ?>
        <div class="header">
            <h1>📚 Quản lý sinh viên</h1>
            <div class="nav">
                <a href="index.php">Trang chủ</a>
                <?php if ($is_admin): ?>
                    <a href="index.php?action=student_add">Thêm sinh viên</a>
                <?php endif; ?>
                <span class="username">👤 <?= htmlspecialchars($username) ?> (<?= $is_admin ? 'Admin' : 'User' ?>)</span>
                <a href="index.php?action=logout">Đăng xuất</a>
            </div>
        </div>
    <?php endif; ?>