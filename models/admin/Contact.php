<?php
class Contact {
    public function getContact() {
        try {
            $conn = connectDB();
            
           
            $sql = "SELECT * FROM lien_he ORDER BY id DESC"; 
            $stmt = $conn->query($sql);

            
            $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $contacts; // Trả về danh sách các liên hệ
        } catch (PDOException $e) {
            error_log("Lỗi khi truy vấn dữ liệu liên hệ: " . $e->getMessage());
            return [];// Trả về mảng rỗng nếu có lỗi
        }
    }
}
    

?>