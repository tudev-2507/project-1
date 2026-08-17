<?php
class Detail 
{
    // Lấy danh sách tất cả sản phẩm
    public function getAll($id) {
        // Kết nối đến cơ sở dữ liệu
        $conn = connectDB();
        // Câu truy vấn SQL để lấy thông tin sản phẩm, danh mục, giá thấp nhất và cao nhất, số lượng đã bán
        $sql = "SELECT 
                    sp.id, 
                    sp.ten_sp, 
                    sp.hinh_anh, 
                    sp.trang_thai,
                    sp.so_luong, 
                    sp.mo_ta, 
                    dm.ten_dm,
                    dm.id as id_dm,
                    MIN(ct.don_gia) AS gia_thap,
                    MAX(ct.don_gia) AS gia_cao,
                    MIN(ct.gia_km) AS gia_km_thap,
                    MAX(ct.gia_km) AS gia_km_cao,
                    GROUP_CONCAT(DISTINCT ms.ten_mau ORDER BY ms.ten_mau ASC) AS danh_sach_mau,
                    GROUP_CONCAT(DISTINCT kc.ten_kich_co ORDER BY kc.ten_kich_co ASC) AS danh_sach_kich_co
                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.id_dm = dm.id
                LEFT JOIN chi_tiet_sp ct ON sp.id = ct.id_sp
                LEFT JOIN mau_sac ms ON ct.id_mau = ms.id
                LEFT JOIN kich_co kc ON ct.id_kich_co = kc.id
                WHERE sp.id = :id  -- Lọc theo ID sản phẩm
                GROUP BY sp.id, sp.ten_sp, sp.hinh_anh, sp.trang_thai, sp.so_luong, dm.ten_dm;";
        // // Chuẩn bị và thực thi câu truy vấn

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        
        // Trả về danh sách sản phẩm dưới dạng mảng
        return $stmt->fetchAll();
            
    }
    public function getProd($id) {
        $conn = connectDB();
        $sql = "SELECT 
                sp.id AS id_san_pham, 
                sp.ten_sp, 
                sp.hinh_anh, 
                sp.mo_ta, 
                sp.trang_thai, 
                sp.so_luong, 
                MIN(ct.don_gia) AS don_gia, 
                MIN(ct.gia_km) AS gia_km
            FROM san_pham sp
            LEFT JOIN chi_tiet_sp ct ON sp.id = ct.id_sp
            WHERE sp.id_dm = (SELECT id_dm FROM san_pham WHERE id = :id_sp)  
            AND sp.id != :id_sp  
            GROUP BY sp.id, sp.ten_sp, sp.hinh_anh, sp.mo_ta, sp.trang_thai, sp.so_luong
            ORDER BY sp.id DESC;
            ";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_sp', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}