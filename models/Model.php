<?php

/**
 * Base Model Class
 * Class cha cho tất cả các Model, cung cấp các phương thức cơ bản
 */
class Model
{
    protected $db;
    protected $table;

    /**
     * Constructor - Khởi tạo kết nối database
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy tất cả bản ghi
     * @return array
     */
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    /**
     * Tìm bản ghi theo ID
     * @param int $id
     * @return array|false
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Xóa bản ghi theo ID
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
