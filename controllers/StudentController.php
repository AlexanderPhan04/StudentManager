<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/StudentModel.php';
require_once __DIR__ . '/AuthController.php';

/**
 * Student Controller
 * Xử lý các thao tác CRUD sinh viên
 */
class StudentController
{
    private $studentModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->studentModel = new StudentModel();
    }

    /**
     * Hiển thị danh sách sinh viên (Dashboard)
     */
    public function index()
    {
        AuthController::requireLogin();

        $search = $_GET['search'] ?? '';
        $isAdmin = AuthController::isAdmin();
        $userId = $_SESSION['user_id'];

        // Admin xem tất cả, User chỉ xem của mình
        if ($isAdmin) {
            $students = $this->studentModel->search($search);
        } else {
            $students = $this->studentModel->search($search, $userId);
        }

        $message = $_GET['message'] ?? '';
        $type = $_GET['type'] ?? '';

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/students/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Hiển thị form thêm sinh viên
     */
    public function create()
    {
        AuthController::requireAdmin();

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'student_code' => trim($_POST['student_code'] ?? ''),
                'full_name' => trim($_POST['full_name'] ?? ''),
                'birthday' => $_POST['birthday'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'user_id' => $_SESSION['user_id']
            ];

            // Validate
            if (empty($data['student_code']) || empty($data['full_name']) || empty($data['birthday']) || empty($data['gender'])) {
                $error = "Vui lòng nhập đầy đủ thông tin bắt buộc";
            } elseif (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "Email không hợp lệ";
            } elseif (!in_array($data['gender'], ['Male', 'Female'])) {
                $error = "Giới tính không hợp lệ";
            } elseif ($this->studentModel->studentCodeExists($data['student_code'])) {
                $error = "Mã sinh viên đã tồn tại";
            } else {
                if ($this->studentModel->create($data)) {
                    header('Location: index.php?message=Thêm sinh viên thành công&type=success');
                    exit();
                } else {
                    $error = "Thêm sinh viên thất bại";
                }
            }
        }

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/students/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Hiển thị form sửa sinh viên
     */
    public function edit($id)
    {
        AuthController::requireAdmin();

        $student = $this->studentModel->find($id);
        if (!$student) {
            header('Location: index.php?message=Không tìm thấy sinh viên&type=error');
            exit();
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'student_code' => trim($_POST['student_code'] ?? ''),
                'full_name' => trim($_POST['full_name'] ?? ''),
                'birthday' => $_POST['birthday'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? '')
            ];

            // Validate
            if (empty($data['student_code']) || empty($data['full_name']) || empty($data['birthday']) || empty($data['gender'])) {
                $error = "Vui lòng nhập đầy đủ thông tin bắt buộc";
            } elseif (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "Email không hợp lệ";
            } elseif (!in_array($data['gender'], ['Male', 'Female'])) {
                $error = "Giới tính không hợp lệ";
            } elseif ($this->studentModel->studentCodeExists($data['student_code'], $id)) {
                $error = "Mã sinh viên đã tồn tại";
            } else {
                if ($this->studentModel->update($id, $data)) {
                    header('Location: index.php?message=Cập nhật sinh viên thành công&type=success');
                    exit();
                } else {
                    $error = "Cập nhật thất bại";
                }
            }
        }

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/students/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Xóa sinh viên
     */
    public function delete($id)
    {
        AuthController::requireAdmin();

        if ($this->studentModel->delete($id)) {
            header('Location: index.php?message=Xóa sinh viên thành công&type=success');
        } else {
            header('Location: index.php?message=Xóa sinh viên thất bại&type=error');
        }
        exit();
    }
}
