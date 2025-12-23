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
            } elseif (!in_array($role, ['admin', 'user'])) {
                $error = "Vai trò không hợp lệ";
            } elseif ($this->userModel->exists($username, $email)) {
                $error = "Tên đăng nhập hoặc email đã tồn tại";
            } else {
                // Tạo user mới
                $data = [
                    'user_name' => $username,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => $role
                ];

                if ($this->userModel->create($data)) {
                    $success = "Đăng ký thành công! Bạn có thể đăng nhập.";
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
}
