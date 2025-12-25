<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/UserModel.php';

/**
 * Auth Controller
 * Xử lý đăng nhập, đăng ký, đăng xuất
 */
class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Hiển thị trang đăng nhập và xử lý đăng nhập
     */
    public function login()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validate input
            if (empty($username) || empty($password)) {
                $error = "Vui lòng nhập đầy đủ thông tin";
            } else {
                // Xác thực
                $user = $this->userModel->authenticate($username, $password);
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['user_name'];
                    $_SESSION['role'] = $user['role'];
                    header('Location: index.php');
                    exit();
                } else {
                    $error = "Tên đăng nhập hoặc mật khẩu không đúng";
                }
            }
        }

        // Hiển thị view
        require_once __DIR__ . '/../views/auth/login_view.php';
    }

    /**
     * Hiển thị trang đăng ký và xử lý đăng ký
     */
    public function register()
    {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'user';

            // Validate
            if (empty($username) || empty($email) || empty($password)) {
                $error = "Vui lòng nhập đầy đủ thông tin";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Email không hợp lệ";
            } elseif (!str_ends_with($email, '@vtc.edu.vn')) {
                $error = "Chỉ chấp nhận email trường @vtc.edu.vn";
            } elseif (!in_array($role, ['admin', 'user'])) {
                $error = "Vai trò không hợp lệ";
            } elseif ($this->userModel->exists($username, $email)) {
                $error = "Tên đăng nhập hoặc email đã tồn tại";
            } else {
                // Parse mã sinh viên từ email (ví dụ: quanpn.1140101240014@vtc.edu.vn)
                $studentCode = $this->parseStudentCodeFromEmail($email);

                // Tạo user mới
                $data = [
                    'user_name' => $username,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => $role
                ];

                if ($userId = $this->userModel->createAndReturnId($data)) {
                    // Nếu parse được mã sinh viên, tự động liên kết với record sinh viên
                    if ($studentCode) {
                        require_once __DIR__ . '/../models/StudentModel.php';
                        $studentModel = new StudentModel();
                        $studentModel->linkStudentToUser($studentCode, $userId);
                    }
                    $success = "Đăng ký thành công! Bạn có thể đăng nhập.";
                    if ($studentCode) {
                        $success .= " Tài khoản đã được liên kết với mã sinh viên: $studentCode";
                    }
                } else {
                    $error = "Đăng ký thất bại, vui lòng thử lại";
                }
            }
        }

        // Hiển thị view
        require_once __DIR__ . '/../views/auth/register_view.php';
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit();
    }

    /**
     * Kiểm tra đã đăng nhập chưa
     * @return bool
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Kiểm tra có phải admin không
     * @return bool
     */
    public static function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    /**
     * Yêu cầu đăng nhập
     */
    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }
    }

    /**
     * Yêu cầu quyền admin
     */
    public static function requireAdmin()
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            header('Location: index.php?message=Bạn không có quyền thực hiện thao tác này&type=error');
            exit();
        }
    }

    /**
     * Parse mã sinh viên từ email
     * Ví dụ: quanpn.1140101240014@vtc.edu.vn -> 1140101240014
     * @param string $email
     * @return string|null
     */
    private function parseStudentCodeFromEmail($email)
    {
        // Lấy phần trước @ 
        $localPart = explode('@', $email)[0];

        // Tìm chuỗi số dài nhất (thường là mã sinh viên)
        if (preg_match('/(\d{10,})/', $localPart, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
