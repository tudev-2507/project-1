<?php
require_once __DIR__ . '/../../commons/validation.php';
class Discount 
{
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả mã giảm giá
    public function getAll() {
        $sql = "SELECT 
                    km.id, 
                    km.ten_km, 
                    km.ma_km, 
                    km.ngay_bat_dau, 
                    km.ngay_ket_thuc, 
                    km.loai_km, 
                    km.so_tien_giam,
                    km.don_toi_thieu,
                    km.trang_thai
                FROM 
                    khuyen_mai km
                ORDER BY 
                    km.ngay_bat_dau DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy các mã giảm giá hợp lệ dựa trên tổng tiền giỏ hàng
    public function getAvailableDiscounts($subtotal) {
        $now = date('Y-m-d');
        $sql = "SELECT 
                    km.id, 
                    km.ten_km, 
                    km.ma_km, 
                    km.so_tien_giam,
                    km.don_toi_thieu,
                    km.loai_km
                FROM 
                    khuyen_mai km
                WHERE 
                    km.trang_thai = 1
                    AND km.ngay_bat_dau <= :now
                    AND km.ngay_ket_thuc >= :now
                    AND km.don_toi_thieu <= :subtotal
                ORDER BY 
                    km.so_tien_giam DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':now' => $now,
            ':subtotal' => $subtotal
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT 
                    km.id, 
                    km.ten_km, 
                    km.ma_km, 
                    km.ngay_bat_dau, 
                    km.ngay_ket_thuc, 
                    km.loai_km, 
                    km.so_tien_giam,
                    km.don_toi_thieu,
                    km.trang_thai
                FROM 
                    khuyen_mai km
                WHERE 
                    km.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($ten_km, $ma_km, $ngay_bat_dau, $ngay_ket_thuc, $loai_km, $so_tien_giam, $don_toi_thieu, $trang_thai) {
        validateDiscountData(compact('ngay_bat_dau', 'ngay_ket_thuc', 'loai_km', 'so_tien_giam'));
        if ($this->codeExists($ma_km)) {
            throw new InvalidArgumentException('Mã khuyến mãi đã tồn tại.');
        }
        $sql = "INSERT INTO khuyen_mai (ten_km, ma_km, ngay_bat_dau, ngay_ket_thuc, loai_km, so_tien_giam, don_toi_thieu, trang_thai) 
                VALUES (:ten_km, :ma_km, :ngay_bat_dau, :ngay_ket_thuc, :loai_km, :so_tien_giam, :don_toi_thieu, :trang_thai)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ten_km', $ten_km);
        $stmt->bindParam(':ma_km', $ma_km);
        $stmt->bindParam(':ngay_bat_dau', $ngay_bat_dau);
        $stmt->bindParam(':ngay_ket_thuc', $ngay_ket_thuc);
        $stmt->bindParam(':loai_km', $loai_km);
        $stmt->bindParam(':so_tien_giam', $so_tien_giam);
        $stmt->bindParam(':don_toi_thieu', $don_toi_thieu);
        $stmt->bindParam(':trang_thai', $trang_thai);
        return $stmt->execute();
    }
    
    public function update($id, $ten_km, $ma_km, $ngay_bat_dau, $ngay_ket_thuc, $loai_km, $so_tien_giam, $don_toi_thieu, $trang_thai) {
        validateDiscountData(compact('ngay_bat_dau', 'ngay_ket_thuc', 'loai_km', 'so_tien_giam'));
        if ($this->codeExists($ma_km, (int) $id)) {
            throw new InvalidArgumentException('Mã khuyến mãi đã tồn tại.');
        }
        $sql = "UPDATE khuyen_mai 
                SET ten_km = :ten_km, 
                    ma_km = :ma_km, 
                    ngay_bat_dau = :ngay_bat_dau, 
                    ngay_ket_thuc = :ngay_ket_thuc, 
                    loai_km = :loai_km, 
                    so_tien_giam = :so_tien_giam, 
                    don_toi_thieu = :don_toi_thieu, 
                    trang_thai = :trang_thai 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':ten_km', $ten_km);
        $stmt->bindParam(':ma_km', $ma_km);
        $stmt->bindParam(':ngay_bat_dau', $ngay_bat_dau);
        $stmt->bindParam(':ngay_ket_thuc', $ngay_ket_thuc);
        $stmt->bindParam(':loai_km', $loai_km);
        $stmt->bindParam(':so_tien_giam', $so_tien_giam);
        $stmt->bindParam(':don_toi_thieu', $don_toi_thieu);
        $stmt->bindParam(':trang_thai', $trang_thai);
        return $stmt->execute();
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM khuyen_mai WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting discount: " . $e->getMessage());
            return false;
        }
    }

    private function codeExists(string $code, ?int $exceptId = null): bool {
        $sql = "SELECT COUNT(*) FROM khuyen_mai WHERE UPPER(ma_km) = UPPER(:code)";
        $params = [':code' => trim($code)];
        if ($exceptId !== null) {
            $sql .= " AND id <> :id";
            $params[':id'] = $exceptId;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }
}
