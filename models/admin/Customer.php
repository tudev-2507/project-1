<?php
class Customer {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy tất cả tài khoản
    public function getAll() {
        $query = "SELECT * FROM tai_khoan";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin 1 tài khoản theo ID (dùng cho chỉnh sửa)
    public function getById($id) {
        $query = "SELECT * FROM tai_khoan WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm tài khoản mới
    public function create($name, $email, $mat_khau, $dia_chi, $so_dien_thoai, $role) {
        $query = "INSERT INTO tai_khoan (name, email, mat_khau, dia_chi, so_dien_thoai, role) 
                  VALUES (:name, :email, :mat_khau, :dia_chi, :so_dien_thoai, :role)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mat_khau', $mat_khau);
        $stmt->bindParam(':dia_chi', $dia_chi);
        $stmt->bindParam(':so_dien_thoai', $so_dien_thoai);
        $stmt->bindParam(':role', $role, PDO::PARAM_INT); // Ép kiểu role thành INT
        return $stmt->execute();
    }

    // Cập nhật tài khoản
    public function update($id, $name, $email, $mat_khau, $dia_chi, $so_dien_thoai, $role) {
        $query = "UPDATE tai_khoan SET name = :name, email = :email, dia_chi = :dia_chi, 
                  so_dien_thoai = :so_dien_thoai, role = :role";
        $params = [
            ':name' => $name,
            ':email' => $email,
            ':dia_chi' => $dia_chi,
            ':so_dien_thoai' => $so_dien_thoai,
            ':role' => $role,
            ':id' => $id
        ];

        if ($mat_khau) {
            $query .= ", mat_khau = :mat_khau";
            $params[':mat_khau'] = $mat_khau;
        }

        $query .= " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    // Xóa tài khoản
    public function delete($id) {
        throw new RuntimeException('Không được xóa tài khoản. Hãy chuyển sang Inactive.');
    }

    public function setStatus(int $id, int $status): bool {
        $stmt = $this->db->prepare("UPDATE tai_khoan SET trang_thai = :status WHERE id = :id");
        return $stmt->execute([':status' => $status === 1 ? 1 : 0, ':id' => $id]);
    }

    public function getTotalCustomers() {
        $sql = "SELECT COUNT(*) as total FROM tai_khoan WHERE role = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function searchCustomers($keyword) {
        $sql = "SELECT * FROM tai_khoan 
                WHERE role = 1 
                AND (name LIKE :keyword 
                OR email LIKE :keyword 
                OR so_dien_thoai LIKE :keyword) 
                ORDER BY id DESC 
                LIMIT 10";
        
        $stmt = $this->db->prepare($sql);
        $likeKeyword = "%{$keyword}%";
        $stmt->bindParam(':keyword', $likeKeyword);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
