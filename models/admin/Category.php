<?php
class Category
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả danh mục
    public function getAll()
    {
        $query = "SELECT * FROM danh_muc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Lấy tất cả danh mục cùng với số lượng sản phẩm
    public function getCategoriesWithProductCount()
    {
        $query = "SELECT dm.*, 
                    (SELECT COUNT(*) FROM san_pham sp WHERE sp.id_dm = dm.id) AS product_count 
                FROM danh_muc dm 
                LIMIT 4;
                ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Lấy một danh mục theo ID
    public function getById($id)
    {
        $query = "SELECT * FROM danh_muc WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo danh mục mới
    public function create($ten_dm, $mo_ta)
    {
        $query = "INSERT INTO danh_muc (ten_dm, mo_ta) VALUES (:ten_dm, :mo_ta)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ten_dm', $ten_dm);
        $stmt->bindParam(':mo_ta', $mo_ta);
        return $stmt->execute();
    }

    // Cập nhật danh mục
    public function update($id, $ten_dm, $mo_ta)
    {
        $query = "UPDATE danh_muc SET ten_dm = :ten_dm, mo_ta = :mo_ta WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':ten_dm', $ten_dm);
        $stmt->bindParam(':mo_ta', $mo_ta);
        return $stmt->execute();
    }

    // Xóa danh mục
    public function delete($id)
    {
        $query = "DELETE FROM danh_muc WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Kiểm tra số lượng sản phẩm trong danh mục
    public function getTotalCategory($id)
    {
        $query = "SELECT COUNT(*) as total FROM san_pham WHERE id_dm = :id"; // Sửa id_danh_muc thành id_dm
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getCategories() {
        try {
            $conn = connectDB();  

            
            $sql = "SELECT * FROM danh_muc";

            
            $stmt = $conn->prepare($sql);
            
            $stmt->execute();

            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $categories;  
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy danh mục: " . $e->getMessage());
            return false; 
        }
    }
}