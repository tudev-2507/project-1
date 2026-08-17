<?php
class Cart {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($id) {
        $query = "SELECT 
                    sp.ten_sp,
                    sp.hinh_anh,
                    kc.ten_kich_co,
                    ms.ten_mau,
                    ctsp.don_gia,
                    ctsp.so_luong AS so_luong_ton,
                    SUM(gh.so_luong) AS so_luong,
                    gh.id_ct_sp
                  FROM gio_hang gh
                  JOIN chi_tiet_sp ctsp ON gh.id_ct_sp = ctsp.id
                  JOIN san_pham sp ON ctsp.id_sp = sp.id
                  JOIN kich_co kc ON ctsp.id_kich_co = kc.id
                  JOIN mau_sac ms ON ctsp.id_mau = ms.id
                  WHERE gh.id_tai_khoan = :id
                  GROUP BY gh.id_ct_sp, sp.ten_sp, sp.hinh_anh, kc.ten_kich_co, ms.ten_mau, ctsp.don_gia";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    public function getDetailCart($id, $kich_co, $mau_sac) {
        $query = "SELECT 
                        sp.id AS id_san_pham, 
                        sp.ten_sp, 
                        sp.hinh_anh, 
                        sp.mo_ta, 
                        sp.trang_thai, 
                        sp.so_luong, 
                        ct.id AS id_bien_the,
                        ct.don_gia, 
                        ct.gia_km,
                        ms.ten_mau, 
                        kc.ten_kich_co
                    FROM san_pham sp
                    JOIN chi_tiet_sp ct ON sp.id = ct.id_sp
                    JOIN mau_sac ms ON ct.id_mau = ms.id
                    JOIN kich_co kc ON ct.id_kich_co = kc.id
                    WHERE sp.id = :id_sp
                    AND ms.ten_mau = :ten_mau
                    AND kc.ten_kich_co = :ten_kich_co";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_sp', $id, PDO::PARAM_INT);
        $stmt->bindParam(':ten_mau', $mau_sac, PDO::PARAM_STR);
        $stmt->bindParam(':ten_kich_co', $kich_co, PDO::PARAM_STR);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function addToCart($idUser, $idBienThe, $soLuong) {
        $conn = connectDB();
        $sql = "INSERT INTO gio_hang (id_tai_khoan , id_ct_sp, so_luong)
                VALUES (:id_user, :id_bien_the, :so_luong)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_user', $idUser, PDO::PARAM_INT);
        $stmt->bindParam(':id_bien_the', $idBienThe, PDO::PARAM_INT);
        $stmt->bindParam(':so_luong', $soLuong, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    public function deleteFromCart($idUser, $idBienThe) {
        $query = "DELETE FROM gio_hang WHERE id_tai_khoan = :id_tai_khoan AND id_ct_sp = :id_ct_sp";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_tai_khoan', $idUser, PDO::PARAM_INT);
        $stmt->bindParam(':id_ct_sp', $idBienThe, PDO::PARAM_INT);
        return $stmt->execute();
    }

}