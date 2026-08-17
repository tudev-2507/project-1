<?php
class UserModel {
    // kết nối db
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy thông tin người dùng
    public function getUserInfo($idUser) {
        $queryUser = "SELECT name, email, dia_chi, so_dien_thoai FROM tai_khoan WHERE id = :id";
        $stmtUser = $this->db->prepare($queryUser);
        $stmtUser->execute([':id' => $idUser]);
        return $stmtUser->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tổng số đơn hàng
    public function getTotalOrders($idUser) {
        $queryOrders = "SELECT COUNT(*) as total_orders FROM don_hang WHERE id_tai_khoan = :id";
        $stmtOrders = $this->db->prepare($queryOrders);
        $stmtOrders->execute([':id' => $idUser]);
        $orderCount = $stmtOrders->fetch(PDO::FETCH_ASSOC);
        return $orderCount['total_orders'];
    }

    // Lấy danh sách đơn hàng
    public function getOrderList($idUser) {
        $queryOrderList = "SELECT dh.id, dh.ngay_dat, ttdh.ten_trang_thai, dh.tong_tien, ptvc.phuong_thuc as van_chuyen, pttt.name as thanh_toan
                           FROM don_hang dh
                           LEFT JOIN trang_thai_don_hang ttdh ON dh.trang_thai = ttdh.id
                           LEFT JOIN pt_van_chuyen ptvc ON dh.id_pt_van_chuyen = ptvc.id
                           LEFT JOIN pt_thanh_toan pttt ON dh.id_pt_thanh_toan = pttt.id
                           WHERE dh.id_tai_khoan = :id
                           ORDER BY dh.id DESC";
        $stmtOrderList = $this->db->prepare($queryOrderList);
        $stmtOrderList->execute([':id' => $idUser]);
        return $stmtOrderList->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateEmail($idUser, $newEmail) {
        try {
            // Kiểm tra email đã tồn tại chưa
            $checkQuery = "SELECT COUNT(*) FROM tai_khoan WHERE email = :email AND id != :id";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->execute([
                ':email' => $newEmail,
                ':id' => $idUser
            ]);
            
            if ($checkStmt->fetchColumn() > 0) {
                return false; // Email đã tồn tại
            }

            // Cập nhật email
            $query = "UPDATE tai_khoan SET email = :email WHERE id = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':email' => $newEmail,
                ':id' => $idUser
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật email: " . $e->getMessage());
            return false;
        }
    }

    public function updatePassword($idUser, $newPassword) {
        try {
            // Mã hóa mật khẩu mới
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Cập nhật mật khẩu
            $query = "UPDATE tai_khoan SET mat_khau = :mat_khau WHERE id = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':mat_khau' => $hashedPassword,
                ':id' => $idUser
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật mật khẩu: " . $e->getMessage());
            return false;
        }
    }
    public function updateAddress($idUser, $ten, $dia_chi, $so_dien_thoai) {
        try {
            
            // Cập nhật địa chỉ
            $query = "UPDATE tai_khoan SET name = :ten, dia_chi = :dia_chi, so_dien_thoai = :so_dien_thoai WHERE id = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':ten' => $ten,
                ':dia_chi' => $dia_chi,
                ':so_dien_thoai' => $so_dien_thoai,
                ':id' => $idUser
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật mật khẩu: " . $e->getMessage());
            return false;
        }
    }

    public function getUserById($idUser) {
        try {
            $query = "SELECT * FROM tai_khoan WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $idUser]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy thông tin người dùng: " . $e->getMessage());
            return false;
        }
    }

    public function checkEmailExists($email, $excludeUserId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM tai_khoan WHERE email = :email";
            $params = [':email' => $email];
            
            if ($excludeUserId !== null) {
                $sql .= " AND id != :id";
                $params[':id'] = $excludeUserId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error in checkEmailExists: " . $e->getMessage());
            return false;
        }
    }
    public function getOrderItems($orderId) {
        $query = "SELECT 
                    sp.ten_sp,
                    sp.hinh_anh,
                    kc.ten_kich_co,
                    ms.ten_mau,
                    ctdh.so_luong,
                    ctdh.don_gia,
                    ctdh.thanh_tien
                  FROM chi_tiet_don_hang ctdh
                  JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id
                  JOIN san_pham sp ON ctsp.id_sp = sp.id
                  JOIN kich_co kc ON ctsp.id_kich_co = kc.id
                  JOIN mau_sac ms ON ctsp.id_mau = ms.id
                  WHERE ctdh.id_dh = :id_dh";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_dh' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}