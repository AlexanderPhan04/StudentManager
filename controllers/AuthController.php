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
                    $_SESSION['user_email'] = $user['email'];

                    // Kiểm tra nếu là sinh viên (email @vtc.edu.vn) và chưa hoàn thiện thông tin
                    if (str_ends_with($user['email'], '@vtc.edu.vn')) {
                        require_once __DIR__ . '/../models/StudentModel.php';
                        $studentModel = new StudentModel();
                        $student = $studentModel->findByUserId($user['id']);

                        // Nếu có record sinh viên nhưng chưa điền đủ thông tin
                        if ($student && $this->isStudentInfoIncomplete($student)) {
                            header('Location: index.php?action=complete_profile');
                            exit();
                        }
                    }

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

                if ($userId = $this->userModel->createAndReturnId($data)) {
                    // Nếu là email trường @vtc.edu.vn, tự động tạo record sinh viên
                    if (str_ends_with($email, '@vtc.edu.vn')) {
                        require_once __DIR__ . '/../models/StudentModel.php';
                        $studentModel = new StudentModel();

                        // Parse mã sinh viên từ email
                        $studentCode = $this->parseStudentCodeFromEmail($email);

                        // Parse tên từ email (phần trước dấu .)
                        $nameParts = explode('.', explode('@', $email)[0]);
                        $fullName = ucfirst($nameParts[0] ?? 'Student');

                        if ($studentCode) {
                            // Tạo record sinh viên với thông tin cơ bản
                            $studentData = [
                                'student_code' => $studentCode,
                                'full_name' => $fullName,
                                'birthday' => '2000-01-01', // Giá trị mặc định, sẽ cập nhật sau
                                'gender' => 'Male', // Giá trị mặc định
                                'email' => $email,
                                'phone' => null,
                                'address' => null,
                                'user_id' => $userId
                            ];
                            $studentModel->create($studentData);
                        }
                    }

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

    /**
     * Kiểm tra thông tin sinh viên chưa hoàn thiện
     * @param array $student
     * @return bool
     */
    private function isStudentInfoIncomplete($student)
    {
        // Kiểm tra các trường bắt buộc
        return empty($student['full_name']) ||
            $student['full_name'] === 'Student' ||
            empty($student['birthday']) ||
            $student['birthday'] === '2000-01-01' ||
            empty($student['phone']) ||
            empty($student['address']);
    }

    /**
     * Hiển thị và xử lý form hoàn thiện thông tin sinh viên
     */
    public function completeProfile()
    {
        self::requireLogin();

        $error = '';
        $success = '';

        require_once __DIR__ . '/../models/StudentModel.php';
        $studentModel = new StudentModel();
        $student = $studentModel->findByUserId($_SESSION['user_id']);

        if (!$student) {
            header('Location: index.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim($_POST['full_name'] ?? '');
            $birthday = trim($_POST['birthday'] ?? '');
            $gender = trim($_POST['gender'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            // Validate
            if (empty($fullName) || empty($birthday) || empty($gender) || empty($phone) || empty($address)) {
                $error = "Vui lòng điền đầy đủ thông tin";
            } else {
                $data = [
                    'student_code' => $student['student_code'],
                    'full_name' => $fullName,
                    'birthday' => $birthday,
                    'gender' => $gender,
                    'email' => $student['email'],
                    'phone' => $phone,
                    'address' => $address
                ];

                if ($studentModel->update($student['id'], $data)) {
                    $success = "Cập nhật thông tin thành công!";
                    header('Location: index.php?message=Hoàn thiện thông tin thành công&type=success');
                    exit();
                } else {
                    $error = "Cập nhật thất bại, vui lòng thử lại";
                }
            }
        }

        require_once __DIR__ . '/../views/students/complete_profile.php';
    }
}
