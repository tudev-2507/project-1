<?php
class OrderModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy đơn hàng cuối cùng của người dùng
    public function getLastOrder($idUser) {
        $queryOrder = "SELECT * FROM don_hang WHERE id_tai_khoan = :id_tai_khoan ORDER BY id DESC LIMIT 1";
        $stmtOrder = $this->db->prepare($queryOrder);
        $stmtOrder->execute([':id_tai_khoan' => $idUser]);
        return $stmtOrder->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết đơn hàng
    public function getOrderItems($orderId) {
        $queryOrderItems = "SELECT 
                                sp.ten_sp,
                                sp.hinh_anh,
                                kc.ten_kich_co,
                                ms.ten_mau,
                                ctdh.so_luong AS so_luong,
                                ctdh.don_gia,
                                ctdh.thanh_tien,
                                ctsp.gia_km AS gia_km,
                                vc.gia AS gia_van_chuyen  

                            FROM chi_tiet_don_hang ctdh
                            JOIN don_hang dh ON ctdh.id_dh = dh.id
                            JOIN pt_van_chuyen vc ON vc.id = dh.id_pt_van_chuyen
                            JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id
                            JOIN san_pham sp ON ctsp.id_sp = sp.id
                            JOIN kich_co kc ON ctsp.id_kich_co = kc.id
                            JOIN mau_sac ms ON ctsp.id_mau = ms.id
                            WHERE ctdh.id_dh = :id_dh;
                            ";
        $stmtOrderItems = $this->db->prepare($queryOrderItems);
        $stmtOrderItems->execute([':id_dh' => $orderId]);
        return $stmtOrderItems->fetchAll(PDO::FETCH_ASSOC);
    }
}