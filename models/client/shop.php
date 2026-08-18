<?php
class Shop 
{
    // Lấy danh sách tất cả sản phẩm
    public function getAll() {
        // Kết nối đến cơ sở dữ liệu
        $conn = connectDB();
        // Câu truy vấn SQL để lấy thông tin sản phẩm, danh mục, giá thấp nhất và cao nhất, số lượng đã bán
        $sql = "SELECT sp.ten_sp, MIN(sp.id) AS id, 
                    sp.hinh_anh, sp.trang_thai, 
                    MIN(ctsp.don_gia) AS don_gia, 
                    MIN(ctsp.gia_km) AS gia_km
                FROM san_pham sp
                    JOIN chi_tiet_sp ctsp ON sp.id = ctsp.id_sp
                GROUP BY sp.ten_sp, sp.hinh_anh, sp.trang_thai
                ORDER BY id DESC LIMIT 4";
        // Chuẩn bị và thực thi câu truy vấn
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        // Trả về danh sách sản phẩm dưới dạng mảng
        return $stmt->fetchAll();
    }

    public function getProdHot(){
        $conn = connectDB();

        $sql = "SELECT sp.id, 
                    sp.ten_sp, 
                    sp.hinh_anh, 
                    sp.trang_thai, 
                    MIN(ctsp.don_gia) AS don_gia,
                    MIN(ctsp.gia_km) AS gia_km, 
                    COALESCE(SUM(ctdh.so_luong), 0) AS so_luong_da_ban
                FROM san_pham sp
                JOIN chi_tiet_sp ctsp ON sp.id = ctsp.id_sp
                LEFT JOIN chi_tiet_don_hang ctdh ON ctsp.id = ctdh.id_ct_sp  
                GROUP BY sp.id, sp.ten_sp, sp.hinh_anh, sp.trang_thai
                ORDER BY so_luong_da_ban DESC LIMIT 4;";
        // Chuẩn bị và thực thi câu truy vấn
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

    }

    public function getCategoryHome2 (){
        $conn = connectDB();
        $sql = "SELECT * FROM danh_muc
            ORDER BY id DESC
            LIMIT 5 OFFSET 5;
            ";
            // Chuẩn bị và thực thi câu truy vấn
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
    }

    public function getCategoryHome(){
        $conn = connectDB();
        $sql = "SELECT * FROM danh_muc
            ORDER BY id DESC
            LIMIT 5;
            ";
            // Chuẩn bị và thực thi câu truy vấn
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
    }


    
// Cập nhật phương thức getNewProducts để xử lý phân trang
    public function getNewProducts($offset, $limit)
    {
        $conn = connectDB();
        $sql = "SELECT 
                    sp.id, 
                    sp.ten_sp, 
                    sp.hinh_anh, 
                    sp.trang_thai,
                    sp.so_luong, 
                    dm.ten_dm,
                    MIN(ct.don_gia) as gia_thap,
                    MAX(ct.don_gia) as gia_cao
                FROM 
                    san_pham sp
                LEFT JOIN 
                    danh_muc dm ON sp.id_dm = dm.id
                LEFT JOIN 
                    chi_tiet_sp ct ON sp.id = ct.id_sp
                WHERE 
                    sp.trang_thai = 1
                GROUP BY 
                    sp.id, sp.ten_sp, sp.hinh_anh, sp.trang_thai, sp.so_luong, dm.ten_dm
                ORDER BY 
                    sp.id DESC
                LIMIT :offset, :limit";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalProducts()
{
    $conn = connectDB();
    $sql = "SELECT COUNT(DISTINCT sp.id) as total 
            FROM san_pham sp
            LEFT JOIN danh_muc dm ON sp.id_dm = dm.id
            LEFT JOIN chi_tiet_sp ct ON sp.id = ct.id_sp
            WHERE sp.trang_thai = 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}


public function getProductsASC($offset = 0, $limit = 20) {
    $conn = connectDB();
    $sql = "SELECT 
                sp.id, 
                sp.ten_sp, 
                sp.hinh_anh, 
                sp.trang_thai,
                sp.so_luong, 
                dm.ten_dm,
                MIN(ct.don_gia) AS gia_thap,
                MAX(ct.don_gia) AS gia_cao,
                (SELECT COUNT(*) 
                 FROM chi_tiet_don_hang ctdh 
                 JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id 
                 WHERE ctsp.id_sp = sp.id) AS so_luong_ban
            FROM 
                san_pham sp
            LEFT JOIN 
                danh_muc dm ON sp.id_dm = dm.id
            LEFT JOIN 
                chi_tiet_sp ct ON sp.id = ct.id_sp
            GROUP BY 
                sp.id, sp.ten_sp, sp.hinh_anh, sp.trang_thai, sp.so_luong, dm.ten_dm
            ORDER BY 
                gia_thap ASC
            LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getProductsDESC($offset = 0, $limit = 20) {
    $conn = connectDB();
    $sql = "SELECT 
                sp.id, 
                sp.ten_sp, 
                sp.hinh_anh, 
                sp.trang_thai,
                sp.so_luong, 
                dm.ten_dm,
                MIN(ct.don_gia) AS gia_thap,
                MAX(ct.don_gia) AS gia_cao,
                (SELECT COUNT(*) 
                 FROM chi_tiet_don_hang ctdh 
                 JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id 
                 WHERE ctsp.id_sp = sp.id) AS so_luong_ban
            FROM 
                san_pham sp
            LEFT JOIN 
                danh_muc dm ON sp.id_dm = dm.id
            LEFT JOIN 
                chi_tiet_sp ct ON sp.id = ct.id_sp
            GROUP BY 
                sp.id, sp.ten_sp, sp.hinh_anh, sp.trang_thai, sp.so_luong, dm.ten_dm
            ORDER BY 
                gia_thap DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



public function getProductsPrice($gia_min, $gia_max, $offset = 0, $limit = 20) {
    $conn = connectDB();
    $sql = "SELECT 
                sp.id, 
                sp.ten_sp, 
                sp.hinh_anh, 
                sp.trang_thai,
                sp.so_luong, 
                dm.ten_dm,
                MIN(ct.don_gia) AS gia_thap,
                MAX(ct.don_gia) AS gia_cao,
                (SELECT COUNT(*) 
                 FROM chi_tiet_don_hang ctdh 
                 JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id 
                 WHERE ctsp.id_sp = sp.id) AS so_luong_ban
            FROM 
                san_pham sp
            LEFT JOIN 
                danh_muc dm ON sp.id_dm = dm.id
            LEFT JOIN 
                chi_tiet_sp ct ON sp.id = ct.id_sp
            GROUP BY 
                sp.id, sp.ten_sp, sp.hinh_anh, sp.trang_thai, sp.so_luong, dm.ten_dm
            HAVING 
                gia_thap BETWEEN :gia_min AND :gia_max
            ORDER BY 
                gia_thap DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':gia_min', $gia_min, PDO::PARAM_INT);
    $stmt->bindValue(':gia_max', $gia_max, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function countProductsByPrice($gia_min, $gia_max) {
    $conn = connectDB();
    $sql = "SELECT COUNT(*) AS total FROM (
                SELECT sp.id, MIN(ct.don_gia) AS gia_thap
                FROM san_pham sp
                LEFT JOIN chi_tiet_sp ct ON sp.id = ct.id_sp
                GROUP BY sp.id
                HAVING gia_thap BETWEEN :gia_min AND :gia_max
            ) AS temp";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':gia_min', $gia_min, PDO::PARAM_INT);
    $stmt->bindValue(':gia_max', $gia_max, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}



    function searchProduct($keyword, $offset = 0, $limit = 20) {
        $conn = connectDB();

        $sql = "SELECT 
                    sp.id, 
                    sp.ten_sp, 
                    sp.hinh_anh, 
                    sp.trang_thai,
                    sp.so_luong, 
                    dm.ten_dm,
                    MIN(ct.don_gia) AS gia_thap,
                    MAX(ct.don_gia) AS gia_cao,
                    (
                        SELECT COUNT(*) 
                        FROM chi_tiet_don_hang ctdh 
                        JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id 
                        WHERE ctsp.id_sp = sp.id
                    ) AS so_luong_ban
                FROM 
                    san_pham sp
                LEFT JOIN 
                    danh_muc dm ON sp.id_dm = dm.id
                LEFT JOIN 
                    chi_tiet_sp ct ON sp.id = ct.id_sp
                WHERE 
                    sp.ten_sp LIKE :keyword
                GROUP BY 
                    sp.id, sp.ten_sp, sp.hinh_anh, sp.trang_thai, sp.so_luong, dm.ten_dm
                ORDER BY 
                    sp.id DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $conn->prepare($sql);
        $search = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $search, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    function countSearchProduct($keyword) {
        $conn = connectDB();
    
        $sql = "SELECT COUNT(*) AS total FROM (
                    SELECT sp.id
                    FROM san_pham sp
                    LEFT JOIN chi_tiet_sp ct ON sp.id = ct.id_sp
                    WHERE sp.ten_sp LIKE :keyword
                    GROUP BY sp.id
                ) AS temp";
    
        $stmt = $conn->prepare($sql);
        $search = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $search, PDO::PARAM_STR);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    
    public function getProductsPaged($offset, $limit, $category_id = null, $gia_min = 0, $gia_max = 99999999, $keyword = '')
{
    // Khởi tạo câu truy vấn cơ bản
    $sql = "SELECT 
                sp.id, 
                sp.ten_sp, 
                sp.hinh_anh, 
                sp.trang_thai,
                sp.so_luong, 
                dm.ten_dm,
                MIN(ct.don_gia) as gia_thap,
                MAX(ct.don_gia) as gia_cao,
                (SELECT COUNT(*) FROM chi_tiet_don_hang ctdh JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id WHERE ctsp.id_sp = sp.id) as so_luong_ban
            FROM 
                san_pham sp
            LEFT JOIN 
                danh_muc dm ON sp.id_dm = dm.id
            LEFT JOIN 
                chi_tiet_sp ct ON sp.id = ct.id_sp
            WHERE 
                sp.trang_thai = 1"; // Chỉ lấy sản phẩm đang kích hoạt

    // Áp dụng lọc theo danh mục nếu có
    if ($category_id) {
        $sql .= " AND sp.id_dm = :category_id";
    }

    // Áp dụng lọc theo khoảng giá nếu có
    if ($gia_min > 0 || $gia_max < 99999999) {
        $sql .= " AND (ct.don_gia BETWEEN :gia_min AND :gia_max)";
    }

    // Áp dụng lọc theo từ khóa nếu có
    if ($keyword) {
        $sql .= " AND (sp.ten_sp LIKE :keyword OR dm.ten_dm LIKE :keyword)";
    }

    $sql .= " GROUP BY 
                sp.id, sp.ten_sp, sp.hinh_anh, sp.trang_thai, sp.so_luong, dm.ten_dm
              ORDER BY 
                sp.id DESC
              LIMIT :offset, :limit";

    $stmt = $this->db->prepare($sql);

    // Bind các tham số
    if ($category_id) {
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    }
    if ($gia_min > 0 || $gia_max < 99999999) {
        $stmt->bindParam(':gia_min', $gia_min, PDO::PARAM_INT);
        $stmt->bindParam(':gia_max', $gia_max, PDO::PARAM_INT);
    }
    if ($keyword) {
        $stmt->bindValue(':keyword', "%{$keyword}%", PDO::PARAM_STR);
    }

    // Bind các tham số phân trang
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

    // Thực thi câu truy vấn
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    
}