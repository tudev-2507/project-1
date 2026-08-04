<?php
class Order {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy danh sách tất cả đơn hàng
    public function getAll() {
        $query = "SELECT dh.id, dh.ngay_dat, dh.tong_tien, dh.trang_thai, dh.dia_chi, dh.ghi_chu, 
                         tk.name AS ten_kh, dh.so_dien_thoai, km.ma_km 
                  FROM don_hang dh 
                  LEFT JOIN tai_khoan tk ON dh.id_tai_khoan = tk.id 
                  LEFT JOIN khuyen_mai km ON dh.id_km = km.id 
                  ORDER BY dh.id ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin chi tiết đơn hàng theo ID
    public function getById($id) {
        $query = "SELECT dh.id, dh.ngay_dat, dh.tong_tien, dh.trang_thai, dh.dia_chi, dh.so_dien_thoai, dh.ghi_chu, 
                         tk.name AS ten_kh, tk.email, km.ma_km, km.loai_km, km.so_tien_giam,
                         ptvc.phuong_thuc AS ten_pt_van_chuyen, pttt.name AS ten_pt_thanh_toan 
                  FROM don_hang dh 
                  LEFT JOIN tai_khoan tk ON dh.id_tai_khoan = tk.id 
                  LEFT JOIN khuyen_mai km ON dh.id_km = km.id 
                  LEFT JOIN pt_van_chuyen ptvc ON dh.id_pt_van_chuyen = ptvc.id 
                  LEFT JOIN pt_thanh_toan pttt ON dh.id_pt_thanh_toan = pttt.id 
                  WHERE dh.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách sản phẩm trong đơn hàng
    public function getOrderItems($orderId) {
        $query = "SELECT ctdh.so_luong, ctdh.don_gia, ctdh.id_ct_sp, 
                         sp.ten_sp, m.ten_mau, kc.ten_kich_co, sp.hinh_anh 
                  FROM chi_tiet_don_hang ctdh 
                  JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id 
                  JOIN san_pham sp ON ctsp.id_sp = sp.id 
                  JOIN mau_sac m ON ctsp.id_mau = m.id 
                  JOIN kich_co kc ON ctsp.id_kich_co = kc.id 
                  WHERE ctdh.id_dh = :id_dh";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_dh' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        try {
            $query = "UPDATE don_hang SET trang_thai = :trang_thai WHERE id = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':trang_thai' => $status,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi khi cập nhật trạng thái: " . $e->getMessage());
            return false;
        }
    }
    

    // Xóa đơn hàng và chi tiết đơn hàng
    public function delete($id) {
        try {
            // Xóa chi tiết đơn hàng trước
            $query = "DELETE FROM chi_tiet_don_hang WHERE id_dh = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);

            // Xóa đơn hàng
            $query = "DELETE FROM don_hang WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting order: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật thông tin đơn hàng
     * 
     * @param int $id ID đơn hàng
     * @param string $dia_chi Địa chỉ giao hàng
     * @param string $ghi_chu Ghi chú
     * @return bool Kết quả cập nhật
     */
    public function updateOrderInfo($id, $dia_chi, $ghi_chu) {
        try {
            $sql = "UPDATE don_hang SET dia_chi = :dia_chi, ghi_chu = :ghi_chu WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':dia_chi', $dia_chi);
            $stmt->bindParam(':ghi_chu', $ghi_chu);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật thông tin đơn hàng: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật sản phẩm trong đơn hàng
     * 
     * @param int $id ID đơn hàng
     * @param array $items Mảng sản phẩm cần cập nhật (mỗi item chứa id_ct_sp, so_luong)
     * @return bool Kết quả cập nhật
     */
    public function updateOrderItems($id, $items) {
        try {
            $this->db->beginTransaction();
            
            // Xóa các sản phẩm cũ
            $sql = "DELETE FROM chi_tiet_don_hang WHERE id_dh = :id_dh";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_dh', $id);
            $stmt->execute();
            
            // Thêm các sản phẩm mới
            $sql = "INSERT INTO chi_tiet_don_hang (id_dh, id_ct_sp, so_luong, don_gia) 
                    SELECT :id_dh, :id_ct_sp, :so_luong, ctsp.don_gia 
                    FROM chi_tiet_sp ctsp WHERE ctsp.id = :id_ct_sp";
            $stmt = $this->db->prepare($sql);
            
            foreach ($items as $item) {
                $stmt->bindParam(':id_dh', $id);
                $stmt->bindParam(':id_ct_sp', $item['id_ct_sp']);
                $stmt->bindParam(':so_luong', $item['so_luong']);
                $stmt->execute();
            }
            
            // Cập nhật tổng tiền đơn hàng
            $this->updateOrderTotal($id);
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Lỗi cập nhật sản phẩm đơn hàng: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xử lý hoàn tiền/hủy đơn
     * 
     * @param int $id ID đơn hàng
     * @param string $ly_do Lý do hoàn tiền/hủy đơn
     * @param bool $hoan_tien Có hoàn tiền hay không
     * @return bool Kết quả xử lý
     */
    public function processRefund($id, $ly_do, $hoan_tien) {
        try {
            $this->db->beginTransaction();
            
            // Cập nhật trạng thái đơn hàng và ghi chú lý do
            $trang_thai = $hoan_tien ? 6 : 5; // 6: Trả hàng/Hoàn tiền, 5: Đã hủy
            $sql = "UPDATE don_hang SET trang_thai = :trang_thai, ghi_chu = CONCAT(ghi_chu, ' - Lý do hủy/hoàn tiền: ', :ly_do) WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':trang_thai', $trang_thai);
            $stmt->bindParam(':ly_do', $ly_do);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            // Nếu hoàn tiền, cập nhật tồn kho
            if ($hoan_tien) {
                $orderItems = $this->getOrderItems($id);
                foreach ($orderItems as $item) {
                    $sql = "UPDATE chi_tiet_sp SET so_luong = so_luong + :so_luong WHERE id = :id_ct_sp";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':so_luong', $item['so_luong']);
                    $stmt->bindParam(':id_ct_sp', $item['id_ct_sp']);
                    $stmt->execute();
                }
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Lỗi xử lý hoàn tiền/hủy đơn: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật tổng tiền đơn hàng
     * 
     * @param int $id ID đơn hàng
     * @return bool Kết quả cập nhật
     */
    private function updateOrderTotal($id) {
        try {
            // Lấy tổng tiền từ chi tiết đơn hàng
            $sql = "SELECT SUM(so_luong * don_gia) as tong_tien FROM chi_tiet_don_hang WHERE id_dh = :id_dh";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_dh', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $tong_tien = $result['tong_tien'] ?? 0;
            
            // Lấy thông tin mã giảm giá
            $sql = "SELECT km.* FROM don_hang dh 
                    LEFT JOIN khuyen_mai km ON dh.id_km = km.id 
                    WHERE dh.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $discount = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Tính tiền sau giảm giá
            $tien_sau_giam = $tong_tien;
            if ($discount) {
                if ($discount['loai_km'] == 1) { // Giảm theo phần trăm
                    $tien_sau_giam = $tong_tien * (1 - $discount['so_tien_giam'] / 100);
                } else { // Giảm theo số tiền
                    $tien_sau_giam = $tong_tien - $discount['so_tien_giam'];
                }
            }
            
            // Cập nhật tổng tiền đơn hàng
            $sql = "UPDATE don_hang SET tong_tien = :tong_tien WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':tong_tien', $tien_sau_giam);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật tổng tiền đơn hàng: " . $e->getMessage());
            return false;
        }
    }
    public function completeOrder($id) {
        try {
            // Lấy danh sách sản phẩm trong đơn hàng
            $orderItems = $this->getOrderItems($id); // Giả sử phương thức này trả về mảng các sản phẩm
            
            foreach ($orderItems as $item) {
                // Giảm tồn kho cho từng sản phẩm
                $sql = "UPDATE chi_tiet_sp SET so_luong = so_luong - :so_luong WHERE id = :id_ct_sp";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':so_luong', $item['so_luong']);
                $stmt->bindParam(':id_ct_sp', $item['id_ct_sp']);
                $stmt->execute();
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi khi hoàn thành đơn hàng: " . $e->getMessage());
            return false;
        }
    }

    public function getTotalOrders() {
        $sql = "SELECT COUNT(*) as total FROM don_hang";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function getOrdersByDateRange($startDate, $endDate) {
        $sql = "SELECT COUNT(*) as total FROM don_hang WHERE ngay_dat BETWEEN :start_date AND :end_date";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
        return $stmt->fetch()['total'];
    }

    public function getPendingOrders() {
        $sql = "SELECT 
                    ttdh.id,
                    ttdh.ten_trang_thai,
                    COUNT(dh.id) AS so_luong_don_hang
                FROM 
                    trang_thai_don_hang ttdh
                LEFT JOIN 
                    don_hang dh ON ttdh.id = dh.trang_thai
                GROUP BY 
                    ttdh.id, ttdh.ten_trang_thai
                ORDER BY 
                    ttdh.id;";
                    
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getTotalRevenue($from = null, $to = null) {
        if ($from && $to) {
            $sql = "SELECT SUM(tong_tien) AS total FROM don_hang 
                    WHERE ngay_dat BETWEEN :from AND :to";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':from', $from);
            $stmt->bindParam(':to', $to);
        } else {
            $sql = "SELECT SUM(tong_tien) AS total FROM don_hang";
            $stmt = $this->db->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    

    public function getTopSellingProducts() {
        $sql = "SELECT sp.id, sp.ten_sp, sp.hinh_anh, COUNT(ctdh.id_ct_sp) as total_sales 
            FROM san_pham sp 
            LEFT JOIN chi_tiet_sp ctsp ON sp.id = ctsp.id_sp
            LEFT JOIN chi_tiet_don_hang ctdh ON ctsp.id = ctdh.id_ct_sp 
            LEFT JOIN don_hang dh ON ctdh.id_dh = dh.id 
            WHERE dh.trang_thai >= 1 
            GROUP BY sp.id, sp.ten_sp, sp.hinh_anh 
            ORDER BY total_sales DESC 
            LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMonthlyStats($year = null) {
        if (!$year) {
            $year = date('Y');
        }
        
        $sql = "SELECT 
                    MONTH(ngay_dat) as month,
                    COUNT(*) as total_orders,
                    SUM(tong_tien) as total_revenue,
                    SUM(CASE WHEN trang_thai = 0 THEN 1 ELSE 0 END) as pending_orders,
                    SUM(CASE WHEN trang_thai >= 1 THEN 1 ELSE 0 END) as completed_orders
                FROM don_hang 
                WHERE YEAR(ngay_dat) = :year
                GROUP BY MONTH(ngay_dat)
                ORDER BY MONTH(ngay_dat)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Initialize data for all months
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = [
                'month' => $i,
                'total_orders' => 0,
                'total_revenue' => 0,
                'pending_orders' => 0,
                'completed_orders' => 0
            ];
        }
        
        // Fill in actual data
        foreach ($results as $row) {
            $monthlyData[$row['month']] = $row;
        }
        
        return array_values($monthlyData);
    }

    public function searchOrders($keyword) {
        $sql = "SELECT dh.*, tk.name AS ten_kh 
                FROM don_hang dh 
                LEFT JOIN tai_khoan tk ON dh.id_tai_khoan = tk.id 
                WHERE dh.id LIKE :keyword 
                OR tk.name LIKE :keyword_like 
                OR dh.dia_chi LIKE :keyword_like 
                OR dh.so_dien_thoai LIKE :keyword_like 
                ORDER BY dh.ngay_dat DESC 
                LIMIT 10";
        
        $stmt = $this->db->prepare($sql);
        $likeKeyword = "%{$keyword}%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->bindParam(':keyword_like', $likeKeyword);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>