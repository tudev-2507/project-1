<?php
class Contact {
    public function postContact($ten, $email, $so_dien_thoai, $chu_de, $noi_dung) {
        try {
            $conn = connectDB();
    
            $sql = "INSERT INTO lien_he (ten, email, so_dien_thoai, chu_de, noi_dung)
                    VALUES (:ten, :email, :so_dien_thoai, :chu_de, :noi_dung)";
            
            $stmt = $conn->prepare($sql);
    
            $stmt->execute([
                ':ten' => $ten,
                ':email' => $email,
                ':so_dien_thoai' => $so_dien_thoai,
                ':chu_de' => $chu_de,
                ':noi_dung' => $noi_dung
            ]);
    
            return true; // Thành công
        } catch (PDOException $e) {
            error_log("Lỗi khi gửi liên hệ: " . $e->getMessage());
            return false; // Thất bại
        }
    }
    
    
}
?>