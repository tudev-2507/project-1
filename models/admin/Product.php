<?php
require_once __DIR__ . '/../../commons/validation.php';
class Product
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Lấy danh sách tất cả sản phẩm
    public function getAll($offset = 0, $limit = 20)
    {
        $sql = "SELECT
                    sp.id, 
                    sp.ten_sp, 
                    sp.hinh_anh, 
                    sp.trang_thai,
                    sp.so_luong, 
                    dm.ten_dm,
                    MIN(ct.don_gia) as gia_thap,
                    MAX(ct.don_gia) as gia_cao,
                    (SELECT COUNT(*) 
                     FROM chi_tiet_don_hang ctdh 
                     JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id 
                     WHERE ctsp.id_sp = sp.id) as so_luong_ban
                FROM 
                    san_pham sp
                LEFT JOIN 
                    danh_muc dm ON sp.id_dm = dm.id
                LEFT JOIN 
                    chi_tiet_sp ct ON sp.id = ct.id_sp
                GROUP BY 
                    sp.id, sp.ten_sp, sp.hinh_anh, sp.trang_thai, sp.so_luong, dm.ten_dm
                ORDER BY 
                    sp.id DESC
                LIMIT :limit OFFSET :offset";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
        {
            $sql = "SELECT COUNT(DISTINCT sp.id) as total
                    FROM san_pham sp
                    LEFT JOIN chi_tiet_sp ct ON sp.id = ct.id_sp";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        }

    

    // Lấy sản phẩm theo danh mục
    public function getByCategory($category_id, $offset = 0, $limit = 20) {
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
                    sp.id_dm = :category_id
                GROUP BY 
                    sp.id, sp.ten_sp, sp.hinh_anh, sp.trang_thai, sp.so_luong, dm.ten_dm
                ORDER BY 
                    sp.id DESC
                LIMIT :limit OFFSET :offset";
    
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countByCategory($category_id) {
        $conn = connectDB();
    
        $sql = "SELECT COUNT(*) AS total FROM (
                    SELECT sp.id
                    FROM san_pham sp
                    WHERE sp.id_dm = :category_id
                    GROUP BY sp.id
                ) AS temp";
    
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    

    // Lấy danh sách tất cả danh mục
    public function getAllCategories()
    {
        $sql = "SELECT id, ten_dm FROM danh_muc ORDER BY ten_dm";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy thông tin chi tiết của một sản phẩm theo ID
    public function getById($id)
    {
        $sql = "SELECT 
                    sp.*, 
                    dm.ten_dm 
                FROM 
                    san_pham sp
                LEFT JOIN 
                    danh_muc dm ON sp.id_dm = dm.id
                WHERE 
                    sp.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Lấy danh sách kích thước của một sản phẩm theo ID sản phẩm
    public function getSizes($productId)
    {
        $sql = "SELECT DISTINCT kc.id, kc.ten_kich_co 
                FROM kich_co kc
                JOIN chi_tiet_sp ct ON kc.id = ct.id_kich_co
                WHERE ct.id_sp = :id_sp";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_sp', $productId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy danh sách tất cả màu sắc
    public function getAllColors()
    {
        $sql = "SELECT id, ten_mau FROM mau_sac ORDER BY ten_mau";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy danh sách tất cả kích thước
    public function getAllSizes()
    {
        $sql = "SELECT id, ten_kich_co FROM kich_co ORDER BY ten_kich_co";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Xóa một sản phẩm theo ID
    public function delete($id)
    {
        try {
            $this->db->beginTransaction();

            $soldStatement = $this->db->prepare(
                "SELECT COUNT(*) FROM chi_tiet_don_hang ctdh
                 JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id
                 WHERE ctsp.id_sp = :id"
            );
            $soldStatement->execute([':id' => $id]);
            if ((int) $soldStatement->fetchColumn() > 0) {
                throw new RuntimeException('Sản phẩm đã phát sinh đơn hàng nên không thể xóa.');
            }

            $sql_ct = "DELETE FROM chi_tiet_sp WHERE id_sp = :id";
            $stmt_ct = $this->db->prepare($sql_ct);
            $stmt_ct->bindParam(':id', $id);
            $stmt_ct->execute();

            $imageTable = $this->db->query("SHOW TABLES LIKE 'anh_sp'")->fetchColumn();
            if ($imageTable) {
                $stmt_anh = $this->db->prepare("DELETE FROM anh_sp WHERE id_sp = :id");
                $stmt_anh->execute([':id' => $id]);
            }

            $sql = "DELETE FROM san_pham WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            error_log("Error deleting product: " . $e->getMessage());
            throw $e;
        }
    }

    // Tạo một sản phẩm mới
    public function create($data, $image)
    {
        try {
            $this->db->beginTransaction();
            validateProductVariations($data['variations'] ?? []);

            // Kiểm tra biến thể trùng lặp
            if (!empty($data['variations'])) {
                $combinations = [];
                foreach ($data['variations'] as $index => $variation) {
                    if (empty($variation['id_mau']) || empty($variation['id_kich_co'])) {
                        throw new Exception("Biến thể tại vị trí " . ($index + 1) . " thiếu màu sắc hoặc kích thước!");
                    }

                    $combination = $variation['id_mau'] . '-' . $variation['id_kich_co'];
                    if (in_array($combination, $combinations)) {
                        // Lấy tên màu và kích thước để hiển thị thông báo chi tiết
                        $colorName = $this->getColorName($variation['id_mau']);
                        $sizeName = $this->getSizeName($variation['id_kich_co']);
                        throw new Exception("Biến thể màu '$colorName' và kích thước '$sizeName' đã tồn tại!");
                    }
                    $combinations[] = $combination;
                }
            } else {
                throw new Exception("Không có biến thể nào được cung cấp!");
            }

            // Lưu sản phẩm
            $sql = "INSERT INTO san_pham (ten_sp, id_dm, so_luong, mo_ta, hinh_anh, trang_thai) 
                    VALUES (:ten_sp, :id_dm, :so_luong, :mo_ta, :hinh_anh, :trang_thai)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ten_sp', $data['ten_sp']);
            $stmt->bindParam(':id_dm', $data['id_dm']);
            $stmt->bindParam(':so_luong', $data['so_luong']);
            $stmt->bindParam(':mo_ta', $data['mo_ta']);
            $stmt->bindParam(':hinh_anh', $image);
            $stmt->bindParam(':trang_thai', $data['trang_thai']);
            $stmt->execute();

            $productId = $this->db->lastInsertId();

            // Lưu ảnh phụ
            if (!empty($data['additional_images'])) {
                foreach ($data['additional_images'] as $img) {
                    $sql_img = "INSERT INTO anh_sp (id_sp, hinh_anh) VALUES (:id_sp, :hinh_anh)";
                    $stmt_img = $this->db->prepare($sql_img);
                    $stmt_img->bindParam(':id_sp', $productId);
                    $stmt_img->bindParam(':hinh_anh', $img);
                    $stmt_img->execute();
                }
            }

            // Lưu biến thể
            foreach ($data['variations'] as $variation) {
                // Kiểm tra dữ liệu biến thể trước khi insert
                $gia_km = !empty($variation['gia_km']) ? (float) $variation['gia_km'] : 0;

                $sql_var = "INSERT INTO chi_tiet_sp (id_sp, id_mau, id_kich_co, so_luong, don_gia, gia_km) 
                            VALUES (:id_sp, :id_mau, :id_kich_co, :so_luong, :don_gia, :gia_km)";
                $stmt_var = $this->db->prepare($sql_var);
                $stmt_var->bindParam(':id_sp', $productId);
                $stmt_var->bindParam(':id_mau', $variation['id_mau']);
                $stmt_var->bindParam(':id_kich_co', $variation['id_kich_co']);
                $stmt_var->bindParam(':so_luong', $variation['so_luong']);
                $stmt_var->bindParam(':don_gia', $variation['don_gia']);
                $stmt_var->bindParam(':gia_km', $gia_km);
                $stmt_var->execute();
            }

            $this->db->commit();
            return $productId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error creating product: " . $e->getMessage());
            throw $e; // Ném lại ngoại lệ để controller xử lý
        }
    }

    // Hàm hỗ trợ lấy tên màu
    private function getColorName($colorId)
    {
        $sql = "SELECT ten_mau FROM mau_sac WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $colorId);
        $stmt->execute();
        return $stmt->fetchColumn() ?: "Không xác định";
    }

    // Hàm hỗ trợ lấy tên kích thước
    private function getSizeName($sizeId)
    {
        $sql = "SELECT ten_kich_co FROM kich_co WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $sizeId);
        $stmt->execute();
        return $stmt->fetchColumn() ?: "Không xác định";
    }

    // Lấy danh sách biến thể của một sản phẩm theo ID
    public function getVariations($productId)
    {
        $sql = "SELECT ct.*, m.ten_mau, kc.ten_kich_co 
                FROM chi_tiet_sp ct 
                JOIN mau_sac m ON ct.id_mau = m.id 
                JOIN kich_co kc ON ct.id_kich_co = kc.id 
                WHERE ct.id_sp = :id_sp";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_sp', $productId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Cập nhật thông tin một sản phẩm theo ID
    public function update($id, $data, $image)
    {
        try {
            $this->db->beginTransaction();
            validateProductVariations($data['variations'] ?? []);

            // Kiểm tra xem có đơn hàng nào tham chiếu đến biến thể của sản phẩm không
            $sql_check = "SELECT COUNT(*) FROM chi_tiet_don_hang ctdh
                      JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id
                      WHERE ctsp.id_sp = :id_sp";
            $stmt_check = $this->db->prepare($sql_check);
            $stmt_check->bindParam(':id_sp', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();

            if ($count > 0) {
                throw new Exception("Không thể cập nhật sản phẩm vì có đơn hàng đang tham chiếu đến các biến thể của nó!");
            }

            // Tính tổng số lượng từ các biến thể
            $totalQuantity = 0;
            if (!empty($data['variations'])) {
                foreach ($data['variations'] as $variation) {
                    $totalQuantity += (int)($variation['so_luong'] ?? 0);
                }
            }

            // Kiểm tra biến thể trùng lặp
            if (!empty($data['variations'])) {
                $combinations = [];
                foreach ($data['variations'] as $index => $variation) {
                    if (empty($variation['id_mau']) || empty($variation['id_kich_co'])) {
                        throw new Exception("Biến thể tại vị trí " . ($index + 1) . " thiếu màu sắc hoặc kích thước!");
                    }

                    $combination = $variation['id_mau'] . '-' . $variation['id_kich_co'];
                    if (in_array($combination, $combinations)) {
                        $colorName = $this->getColorName($variation['id_mau']);
                        $sizeName = $this->getSizeName($variation['id_kich_co']);
                        throw new Exception("Biến thể màu '$colorName' và kích thước '$sizeName' đã tồn tại!");
                    }
                    $combinations[] = $combination;
                }
            } else {
                throw new Exception("Phải có ít nhất một biến thể!");
            }

            // Cập nhật thông tin sản phẩm trong bảng san_pham
            $sql = "UPDATE san_pham 
                SET ten_sp = :ten_sp, 
                    id_dm = :id_dm, 
                    so_luong = :so_luong, 
                    mo_ta = :mo_ta, 
                    hinh_anh = :hinh_anh, 
                    trang_thai = :trang_thai 
                WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ten_sp', $data['ten_sp']);
            $stmt->bindParam(':id_dm', $data['id_dm']);
            $stmt->bindParam(':so_luong', $totalQuantity, PDO::PARAM_INT);
            $stmt->bindParam(':mo_ta', $data['mo_ta']);
            $stmt->bindParam(':hinh_anh', $image);
            $stmt->bindParam(':trang_thai', $data['trang_thai'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Xóa các biến thể cũ (nếu không có đơn hàng tham chiếu)
            $sql_delete = "DELETE FROM chi_tiet_sp WHERE id_sp = :id_sp";
            $stmt_delete = $this->db->prepare($sql_delete);
            $stmt_delete->bindParam(':id_sp', $id, PDO::PARAM_INT);
            $stmt_delete->execute();

            // Thêm các biến thể mới
            foreach ($data['variations'] as $variation) {
                // Kiểm tra dữ liệu biến thể
                $gia_km = !empty($variation['gia_km']) ? (float) $variation['gia_km'] : 0;

                $sql_var = "INSERT INTO chi_tiet_sp (id_sp, id_mau, id_kich_co, so_luong, don_gia, gia_km) 
                            VALUES (:id_sp, :id_mau, :id_kich_co, :so_luong, :don_gia, :gia_km)";
                $stmt_var = $this->db->prepare($sql_var);
                $stmt_var->bindParam(':id_sp', $id, PDO::PARAM_INT);
                $stmt_var->bindParam(':id_mau', $variation['id_mau'], PDO::PARAM_INT);
                $stmt_var->bindParam(':id_kich_co', $variation['id_kich_co'], PDO::PARAM_INT);
                $stmt_var->bindParam(':so_luong', $variation['so_luong'], PDO::PARAM_INT);
                $stmt_var->bindParam(':don_gia', $variation['don_gia']);
                $stmt_var->bindParam(':gia_km', $gia_km);
                $stmt_var->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error updating product ID = $id: " . $e->getMessage());
            throw $e; // Ném lỗi để controller xử lý
        }
    }

    public function searchProducts($keyword)
    {
        $sql = "SELECT sp.*, dm.ten_dm,
                    (SELECT COUNT(*)
                     FROM chi_tiet_don_hang ctdh
                     JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id
                     WHERE ctsp.id_sp = sp.id) AS so_luong_ban
                FROM san_pham sp 
                LEFT JOIN danh_muc dm ON sp.id_dm = dm.id 
                WHERE sp.ten_sp LIKE :keyword 
                OR dm.ten_dm LIKE :keyword 
                OR sp.id LIKE :keyword 
                ORDER BY sp.id DESC 
                LIMIT 10";
        
        $stmt = $this->db->prepare($sql);
        $likeKeyword = "%{$keyword}%";
        $stmt->bindParam(':keyword', $likeKeyword);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addColor(string $name): bool
    {
        $name = trim($name);
        if ($name === '') {
            throw new InvalidArgumentException('Tên màu không được để trống.');
        }
        $stmt = $this->db->prepare(
            "INSERT INTO mau_sac (ten_mau)
             SELECT :name WHERE NOT EXISTS (SELECT 1 FROM mau_sac WHERE LOWER(ten_mau) = LOWER(:check_name))"
        );
        $stmt->execute([':name' => $name, ':check_name' => $name]);
        if ($stmt->rowCount() === 0) {
            throw new InvalidArgumentException('Màu này đã tồn tại.');
        }
        return true;
    }

    public function addSize(string $name): bool
    {
        $name = trim($name);
        if ($name === '') {
            throw new InvalidArgumentException('Tên kích thước không được để trống.');
        }
        $stmt = $this->db->prepare(
            "INSERT INTO kich_co (ten_kich_co)
             SELECT :name WHERE NOT EXISTS (SELECT 1 FROM kich_co WHERE LOWER(ten_kich_co) = LOWER(:check_name))"
        );
        $stmt->execute([':name' => $name, ':check_name' => $name]);
        if ($stmt->rowCount() === 0) {
            throw new InvalidArgumentException('Kích thước này đã tồn tại.');
        }
        return true;
    }

    public function getTotalProducts() {
        $sql = "SELECT COUNT(*) as total FROM san_pham WHERE trang_thai = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    



}
