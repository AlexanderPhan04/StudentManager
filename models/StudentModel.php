<?php
require_once __DIR__ . '/Model.php';

/**
 * Student Model
 * Xử lý các thao tác liên quan đến bảng students
 */
class StudentModel extends Model
{
    protected $table = 'students';

    /**
     * Tìm kiếm sinh viên theo tên hoặc mã
     * @param string $search
     * @param int|null $userId - Nếu có thì chỉ lấy sinh viên của user đó
     * @return array
     */
    public function search($search = '', $userId = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE (full_name LIKE ? OR student_code LIKE ?)";
        $params = ["%$search%", "%$search%"];

        if ($userId !== null) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }

        $sql .= " ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Kiểm tra mã sinh viên đã tồn tại chưa
     * @param string $studentCode
     * @param int|null $excludeId - ID để loại trừ (khi update)
     * @return bool
     */
    public function studentCodeExists($studentCode, $excludeId = null)
    {
        $sql = "SELECT id FROM {$this->table} WHERE student_code = ?";
        $params = [$studentCode];

        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    /**
     * Tạo sinh viên mới
     * @param array $data
     * @return bool
     */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (student_code, full_name, birthday, gender, email, phone, address, user_id) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['student_code'],
            $data['full_name'],
            $data['birthday'],
            $data['gender'],
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['user_id']
        ]);
    }

    /**
     * Cập nhật thông tin sinh viên
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} 
             SET student_code = ?, full_name = ?, birthday = ?, gender = ?, email = ?, phone = ?, address = ? 
             WHERE id = ?"
        );
        return $stmt->execute([
            $data['student_code'],
            $data['full_name'],
            $data['birthday'],
            $data['gender'],
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $id
        ]);
    }

    /**
     * Liên kết sinh viên với user dựa trên student_code
     * @param string $studentCode
     * @param int $userId
     * @return bool
     */
    public function linkStudentToUser($studentCode, $userId)
    {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET user_id = ? WHERE student_code = ? AND user_id IS NULL"
        );
        return $stmt->execute([$userId, $studentCode]);
    }

    /**
     * Tìm sinh viên theo student_code
     * @param string $studentCode
     * @return array|false
     */
    public function findByStudentCode($studentCode)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE student_code = ?");
        $stmt->execute([$studentCode]);
        return $stmt->fetch();
    }

    /**
     * Tìm sinh viên theo user_id
     * @param int $userId
     * @return array|false
     */
    public function findByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}
