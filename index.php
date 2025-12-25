<?php

/**
 * Router chính - Điểm vào duy nhất của ứng dụng
 * Xử lý tất cả requests và route đến controller phù hợp
 */

// Khởi động session
session_start();

// Load controllers
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/StudentController.php';

// Lấy action từ URL
$action = $_GET['action'] ?? 'home';
$id = $_GET['id'] ?? null;

// Routing
switch ($action) {
    // Auth routes
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'complete_profile':
        $controller = new AuthController();
        $controller->completeProfile();
        break;

    // Student routes
    case 'home':
    case 'students':
        $controller = new StudentController();
        $controller->index();
        break;

    case 'student_add':
        $controller = new StudentController();
        $controller->create();
        break;

    case 'student_edit':
        if ($id) {
            $controller = new StudentController();
            $controller->edit($id);
        } else {
            header('Location: index.php?message=Không tìm thấy sinh viên&type=error');
            exit();
        }
        break;

    case 'student_delete':
        if ($id) {
            $controller = new StudentController();
            $controller->delete($id);
        } else {
            header('Location: index.php?message=Không tìm thấy sinh viên&type=error');
            exit();
        }
        break;

    default:
        // Nếu action không hợp lệ, redirect về trang chủ
        header('Location: index.php');
        exit();
}
