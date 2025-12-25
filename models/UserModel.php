<?php
require_once __DIR__ . '/Model.php';

/**
 * User Model
 * Xử lý các thao tác liên quan đến bảng users
 */
class UserModel extends Model
{
    protected $table = 'users';

    /**
     * Tìm user theo username
     * @param string $username
     * @return array|false
     */
    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_name = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    /**
     * Tìm user theo email
     * @param string $email
     * @return array|false
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Kiểm tra username hoặc email đã tồn tại chưa
     * @param string $username
     * @param string $email
     * @return bool
     */
    public function exists($username, $email)
    {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE user_name = ? OR email = ?");
        $stmt->execute([$username, $email]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Tạo user mới
     * @param array $data
     * @return bool
     */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (user_name, email, password, role) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['user_name'],
            $data['email'],
            $data['password'],
            $data['role'] ?? 'user'
        ]);
    }

    /**
     * Tạo user mới và trả về ID
     * @param array $data
     * @return int|false - ID của user vừa tạo hoặc false nếu thất bại
     */
    public function createAndReturnId($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (user_name, email, password, role) VALUES (?, ?, ?, ?)"
        );
        $success = $stmt->execute([
            $data['user_name'],
            $data['email'],
            $data['password'],
            $data['role'] ?? 'user'
        ]);

        if ($success) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Xác thực đăng nhập
     * @param string $username
     * @param string $password
     * @return array|false
     */
    public function authenticate($username, $password)
    {
        $user = $this->findByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
