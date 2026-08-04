<?php
class HomeAdmin
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    
    private function checkAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 0) {
            header('Location: ?act=home');
            exit();
        }
    }
    
    public function index()
    {
        $this->checkAdmin();
    
        // Load models
        require_once './models/admin/Order.php';
        require_once './models/admin/Product.php';
        require_once './models/admin/Customer.php';
    
        $orderModel = new Order($this->db);
        $productModel = new Product($this->db);
        $customerModel = new Customer($this->db);
    
        // Lấy ngày từ GET
        $fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : null;
        $toDate = isset($_GET['to_date']) ? $_GET['to_date'] : null;
    
        // Lấy thống kê khác
        $totalOrders = $orderModel->getTotalOrders();
        $pendingOrders = $orderModel->getPendingOrders();
        $totalCustomers = $customerModel->getTotalCustomers();
        $totalProducts = $productModel->getTotalProducts();
    
        // Doanh thu trong tháng hiện tại
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $monthlyRevenue = $orderModel->getTotalRevenue($startDate, $endDate);
    
        // Doanh thu tổng
        $totalRevenue = $orderModel->getTotalRevenue();
    
        // Các sản phẩm bán chạy
        $topProducts = $orderModel->getTopSellingProducts();
      
        
    
        // Đơn hàng gần đây
        $lastMonthStart = date('Y-m-d', strtotime('-30 days'));
        $today = date('Y-m-d');
        $recentOrders = $orderModel->getOrdersByDateRange($lastMonthStart, $today);
    
        // Kiểm tra nếu có khoảng thời gian được chọn
        if ($fromDate && $toDate) {
            $customRevenue = $orderModel->getTotalRevenue($fromDate, $toDate);
        } else {
            // Nếu không chọn khoảng thời gian, lấy doanh thu tổng
            $customRevenue = $orderModel->getTotalRevenue();
        }
    
        // Truyền dữ liệu vào view
        $dashboardData = [
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'totalCustomers' => $totalCustomers,
            'totalProducts' => $totalProducts,
            'monthlyRevenue' => $monthlyRevenue,
            'totalRevenue' => $totalRevenue,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
            'customRevenue' => $customRevenue 
        ];
        
    
        $view = 'dashboard';
        $title = 'Dashboard';
        require_once './views/admin/main.php';
    }
    
    

    public function product()
    {
        $this->checkAdmin();
        require_once './models/admin/Product.php';
        $productModel = new Product($this->db);
        $products = $productModel->getAll();


        $view = 'product/list';
        $title = 'product list';
        require_once './views/admin/main.php';
    }

    public function productEdit()
{
    $this->checkAdmin();
    require_once './models/admin/Product.php';
    require_once './models/admin/Category.php';
    $productModel = new Product($this->db);
    $categoryModel = new Category($this->db);

    // Lấy ID sản phẩm từ URL
    $id = $_GET['id'] ?? null;
    if (!$id) {
        $_SESSION['error_message'] = "Không tìm thấy sản phẩm!";
        header('Location: ?act=product');
        exit();
    }

    // Lấy thông tin sản phẩm
    $product = $productModel->getById($id);
    if (!$product) {
        $_SESSION['error_message'] = "Sản phẩm không tồn tại!";
        header('Location: ?act=product');
        exit();
    }

    // Lấy danh sách danh mục, màu sắc, kích thước và biến thể
    $categories = $categoryModel->getAll();
    $colors = $productModel->getAllColors();
    $sizes = $productModel->getAllSizes();
    $variations = $productModel->getVariations($id);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Xác thực dữ liệu
        if (empty($_POST['ten_sp']) || empty($_POST['id_dm'])) {
            $_SESSION['error_message'] = "Tên sản phẩm và danh mục không được để trống!";
            header('Location: ?act=product_edit&id=' . $id);
            exit();
        }

        // Xử lý file upload (chỉ ảnh chính)
        $uploadDir = './public/admin/assets_admin/images/product/';
        $mainImage = $product['hinh_anh']; // Giữ ảnh cũ nếu không upload ảnh mới

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
            $fileTemp = $_FILES['main_image']['tmp_name'];
            $fileName = time() . '_' . $_FILES['main_image']['name'];
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($fileTemp, $filePath)) {
                $mainImage = $fileName;
            }
        }

        // Xử lý dữ liệu form
        $productData = [
            'ten_sp' => trim($_POST['ten_sp']),
            'id_dm' => (int)$_POST['id_dm'],
            'mo_ta' => trim($_POST['mo_ta'] ?? ''),
            'trang_thai' => isset($_POST['trang_thai']) ? 1 : 0,
        ];

        $variations = [];
        if (isset($_POST['variations']) && is_array($_POST['variations'])) {
            foreach ($_POST['variations'] as $variation) {
                if (!empty($variation['id_mau']) && !empty($variation['id_kich_co'])) {
                    $variations[] = [
                        'id_mau' => (int)$variation['id_mau'],
                        'id_kich_co' => (int)$variation['id_kich_co'],
                        'so_luong' => (int)($variation['so_luong'] ?? 0),
                        'don_gia' => (float)($variation['don_gia'] ?? 0),
                        'gia_km' => !empty($variation['gia_km']) ? (float)$variation['gia_km'] : null
                    ];
                }
            }
        }
        $productData['variations'] = $variations;

        // Cập nhật sản phẩm
        try {
            if ($productModel->update($id, $productData, $mainImage)) {
                $_SESSION['success_message'] = "Cập nhật sản phẩm thành công!";
                header('Location: ?act=product');
                exit();
            } else {
                throw new Exception("Không thể cập nhật sản phẩm. Vui lòng kiểm tra dữ liệu!");
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ?act=product_edit&id=' . $id);
            exit();
        }
    }

    // Truyền dữ liệu vào view
    $view = 'product/edit';
    $title = 'Chỉnh Sửa Sản Phẩm';
    require_once './views/admin/main.php';
}

    public function productCreate()
    {
        $this->checkAdmin();
        require_once './models/admin/Product.php';
        require_once './models/admin/Category.php';
        $productModel = new Product($this->db);
        $categoryModel = new Category($this->db);

        // Lấy dữ liệu danh mục, màu sắc, kích thước
        $categories = $categoryModel->getAll();
        $colors = $productModel->getAllColors();
        $sizes = $productModel->getAllSizes();

        // Khởi tạo biến để lưu dữ liệu form
        $form_data = [
            'ten_sp' => '',
            'id_dm' => '',
            'mo_ta' => '',
            'variations' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Thu thập dữ liệu từ form
            $form_data = [
                'ten_sp' => $_POST['ten_sp'] ?? '',
                'id_dm' => $_POST['id_dm'] ?? '',
                'mo_ta' => $_POST['mo_ta'] ?? '',
                'variations' => $_POST['variations'] ?? []
            ];

            try {
                // Xử lý upload ảnh chính
                $uploadDir = './public/admin/assets_admin/images/product/';
                $image = '';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                    $fileName = time() . '_' . basename($_FILES['main_image']['name']);
                    $targetFile = $uploadDir . $fileName;
                    if (!move_uploaded_file($_FILES['main_image']['tmp_name'], $targetFile)) {
                        throw new Exception("Không thể tải lên ảnh chính!");
                    }
                    $image = $fileName;
                } else {
                    throw new Exception("Vui lòng chọn ảnh chính cho sản phẩm!");
                }

                // Xử lý ảnh phụ
                $additionalImages = [];
                if (isset($_FILES['additional_images']) && !empty($_FILES['additional_images']['name'][0])) {
                    foreach ($_FILES['additional_images']['name'] as $key => $name) {
                        if ($_FILES['additional_images']['error'][$key] === UPLOAD_ERR_OK) {
                            $fileName = time() . '_' . basename($name);
                            $targetFile = $uploadDir . $fileName;
                            if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$key], $targetFile)) {
                                $additionalImages[] = $fileName;
                            }
                        }
                    }
                }

                // Chuẩn bị dữ liệu sản phẩm
                $data = [
                    'ten_sp' => $form_data['ten_sp'],
                    'id_dm' => $form_data['id_dm'],
                    'so_luong' => $_POST['so_luong'] ?? 0,
                    'mo_ta' => $form_data['mo_ta'],
                    'trang_thai' => 1, // Mặc định trạng thái hoạt động
                    'variations' => [],
                    'additional_images' => $additionalImages
                ];

                // Chuẩn bị dữ liệu biến thể
                if (!empty($form_data['variations'])) {
                    foreach ($form_data['variations'] as $variation) {
                        $data['variations'][] = [
                            'id_mau' => $variation['id_mau'] ?? '',
                            'id_kich_co' => $variation['id_kich_co'] ?? '',
                            'so_luong' => $variation['so_luong'] ?? 0,
                            'don_gia' => $variation['don_gia'] ?? 0,
                            'gia_km' => $variation['gia_km'] ?? null
                        ];
                    }
                }

                // Tạo sản phẩm
                $productId = $productModel->create($data, $image);
                if ($productId) {
                    $_SESSION['success_message'] = "Tạo sản phẩm thành công!";
                    header('Location: ?act=product');
                } else {
                    throw new Exception("Có lỗi xảy ra khi tạo sản phẩm!");
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
                // Dữ liệu form đã được lưu trong $form_data, tiếp tục hiển thị form
            }
        }

        // Hiển thị form tạo sản phẩm
        $view = 'product/create';
        $title = 'Tạo sản phẩm';
        require_once './views/admin/main.php';
    }

    public function productDelete()
    {
        $this->checkAdmin();
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            require_once './models/admin/Product.php';
            $productModel = new Product($this->db);
            try {
                $result = $productModel->delete($id);
                $_SESSION['success_message'] = "Xóa sản phẩm thành công!";
            } catch (Throwable $e) {
                $_SESSION['error_message'] = $e->getMessage();
            }
        }
        header('Location: ?act=product');
        exit();
    }

    public function productAttributeCreate()
    {
        $this->checkAdmin();
        require_once './models/admin/Product.php';
        $productModel = new Product($this->db);
        $returnAct = ($_POST['return_act'] ?? '') === 'product_edit'
            ? 'product_edit&id=' . (int) ($_POST['product_id'] ?? 0)
            : 'product_create';

        try {
            if (($_POST['attribute_type'] ?? '') === 'color') {
                $productModel->addColor($_POST['attribute_name'] ?? '');
                $_SESSION['success_message'] = 'Thêm màu thành công.';
            } elseif (($_POST['attribute_type'] ?? '') === 'size') {
                $productModel->addSize($_POST['attribute_name'] ?? '');
                $_SESSION['success_message'] = 'Thêm kích thước thành công.';
            } else {
                throw new InvalidArgumentException('Loại thuộc tính không hợp lệ.');
            }
        } catch (Throwable $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: ?act=' . $returnAct);
        exit();
    }

    public function category()
    {
        $this->checkAdmin();
        require_once './models/admin/Category.php';
        $categoryModel = new Category($this->db);
        $categories = $categoryModel->getAll();

        $title = 'category';
        $view = 'category/list';
        require_once './views/admin/main.php';
    }

    public function categoryCreate()
    {
        $this->checkAdmin();
        require_once './models/admin/Category.php';
        $categoryModel = new Category($this->db);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ten_dm = $_POST['ten_dm'];
            $mo_ta = $_POST['mo_ta'];

            if ($categoryModel->create($ten_dm, $mo_ta)) {
                $_SESSION['success_message'] = "Thêm danh mục thành công!";
                header('Location: ?act=category');
                exit();
            } else {
                $_SESSION['error_message'] = "Không thể thêm danh mục. Vui lòng thử lại!";
            }
        }

        $title = 'category create';
        $view = 'category/create';
        require_once './views/admin/main.php';
    }

    public function categoryEdit()
    {
        $this->checkAdmin();
        require_once './models/admin/Category.php';
        $categoryModel = new Category($this->db);

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $category = $categoryModel->getById($id);

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $ten_dm = $_POST['ten_dm'];
                $mo_ta = $_POST['mo_ta'];

                if ($categoryModel->update($id, $ten_dm, $mo_ta)) {
                    $_SESSION['success_message'] = "Cập nhật danh mục thành công!";
                    header('Location: ?act=category');
                    exit();
                } else {
                    $_SESSION['error_message'] = "Không thể cập nhật danh mục. Vui lòng thử lại!";
                }
            }
        } else {
            $_SESSION['error_message'] = "Không tìm thấy danh mục!";
            header('Location: ?act=category');
            exit();
        }

        $title = 'category edit';
        $view = 'category/edit';
        require_once './views/admin/main.php';
    }

    public function categoryDelete()
    {
        $this->checkAdmin();
        require_once './models/admin/Category.php';
        $categoryModel = new Category($this->db);

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $total = $categoryModel->getTotalCategory($id);

            if ($total['total'] > 0) {
                $_SESSION['error_message'] = "Không thể xóa danh mục này vì vẫn còn sản phẩm!";
            } else {
                if ($categoryModel->delete($id)) {
                    $_SESSION['success_message'] = "Xóa danh mục thành công!";
                } else {
                    $_SESSION['error_message'] = "Không thể xóa danh mục. Vui lòng thử lại!";
                }
            }
        }

        header('Location: ?act=category');
        exit();
    }

    // Danh sách mã giảm giá
    public function discount()
    {
        $this->checkAdmin();
        require_once './models/admin/Discount.php';
        $discountModel = new Discount($this->db);
        $discounts = $discountModel->getAll();
        $view = 'discount/list';
        $title = 'Danh sách khuyến mại';
        require_once './views/admin/main.php';
    }

    // Chỉnh sửa mã giảm giá
    public function edit_discount()
    {
        $this->checkAdmin();
        // Kiểm tra xem id có tồn tại trong URL không
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = "Không tìm thấy mã giảm giá để chỉnh sửa!";
            header('Location: ?act=discount');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_GET['id'];
            $ten_km = $_POST['ten_km'];
            $ma_km = $_POST['ma_km'];
            $ngay_bat_dau = $_POST['ngay_bat_dau'];
            $ngay_ket_thuc = $_POST['ngay_ket_thuc'];
            $loai_km = $_POST['loai_km'];
            $so_tien_giam = $_POST['so_tien_giam'];
            $don_toi_thieu = $_POST['don_toi_thieu'];
            $trang_thai = $_POST['trang_thai'];

            require_once './models/admin/Discount.php';
            $discountModel = new Discount($this->db);

            try {
                $result = $discountModel->update($id, $ten_km, $ma_km, $ngay_bat_dau, $ngay_ket_thuc, $loai_km, $so_tien_giam, $don_toi_thieu, $trang_thai);
                $_SESSION['success_message'] = "Cập nhật khuyến mãi thành công!";
            } catch (Throwable $e) {
                $_SESSION['error_message'] = $e->getMessage();
            }

            header('Location: ?act=discount');
            exit();
        } else {
            // Lấy thông tin khuyến mãi để hiển thị trong form
            $id = $_GET['id'];
            require_once './models/admin/Discount.php';
            $discountModel = new Discount($this->db);
            $discount = $discountModel->getById($id);

            // Kiểm tra xem mã giảm giá có tồn tại không
            if (!$discount) {
                $_SESSION['error_message'] = "Mã giảm giá không tồn tại!";
                header('Location: ?act=discount');
                exit();
            }

            $view = 'discount/edit';
            $title = 'Chỉnh sửa khuyến mãi';
            require_once './views/admin/main.php';
        }
    }

    // Tạo mã giảm giá mới
    public function discountCreate()
    {
        $this->checkAdmin();
        $view = 'discount/create';
        $title = 'Tạo khuyến mãi mới';
        require_once './views/admin/main.php';
    }

    // Xóa mã giảm giá
    public function discountDelete()
    {
        $this->checkAdmin();
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            require_once './models/admin/Discount.php';
            $discountModel = new Discount($this->db);
            $result = $discountModel->delete($id);

            if ($result) {
                $_SESSION['success_message'] = "Xóa khuyến mại thành công!";
            } else {
                $_SESSION['error_message'] = "Không thể xóa khuyến mại. Vui lòng thử lại!";
            }
        }
        header('Location: ?act=discount');
        exit();
    }

    // Xử lý tạo mã giảm giá
    public function create_discount()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ten_km = $_POST['ten_km'];
            $ma_km = $_POST['ma_km'];
            $ngay_bat_dau = $_POST['ngay_bat_dau'];
            $ngay_ket_thuc = $_POST['ngay_ket_thuc'];
            $loai_km = $_POST['loai_km'];
            $so_tien_giam = $_POST['so_tien_giam'];
            $don_toi_thieu = $_POST['don_toi_thieu'];
            $trang_thai = $_POST['trang_thai'];

            if (empty($ten_km) || empty($ma_km) || empty($ngay_bat_dau) || empty($ngay_ket_thuc)) {
                $_SESSION['error_message'] = 'Vui lòng điền đầy đủ thông tin khuyến mại.';
                header('Location: ?act=discount_create');
                exit();
            }

            require_once './models/admin/Discount.php';
            $discountModel = new Discount($this->db);
            try {
                $result = $discountModel->create($ten_km, $ma_km, $ngay_bat_dau, $ngay_ket_thuc, $loai_km, $so_tien_giam, $don_toi_thieu, $trang_thai);
                $_SESSION['success_message'] = "Khuyến mại mới đã được tạo thành công!";
                header('Location: ?act=discount');
            } catch (Throwable $e) {
                $_SESSION['error_message'] = $e->getMessage();
                header('Location: ?act=discount_create');
            }
        }
    }
    public function customer()
    {
        $this->checkAdmin();
        require_once './models/admin/Customer.php';
        $customerModel = new Customer($this->db);
        $customers = $customerModel->getAll();

        $title = 'customer';
        $view = 'customer/list';
        require_once './views/admin/main.php';
    }

    public function customerDelete()
    {
        $this->checkAdmin();
        $_SESSION['error_message'] = "Không được xóa tài khoản. Hãy chuyển tài khoản sang Inactive.";
        header('Location: ?act=customer');
        exit();
    }

    public function customerStatus()
    {
        $this->checkAdmin();
        require_once './models/admin/Customer.php';
        $customerModel = new Customer($this->db);
        $id = (int) ($_GET['id'] ?? 0);
        $status = (int) ($_GET['status'] ?? 0) === 1 ? 1 : 0;
        $customerModel->setStatus($id, $status);
        $_SESSION['success_message'] = $status ? 'Đã kích hoạt tài khoản.' : 'Đã vô hiệu hóa tài khoản.';
        header('Location: ?act=customer');
        exit();
    }

    public function customerCreate()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once './models/admin/Customer.php';
            $customerModel = new Customer($this->db);

            $name = $_POST['name'];
            $email = $_POST['email'];
            $mat_khau = password_hash($_POST['mat_khau'], PASSWORD_DEFAULT); // Mã hóa mật khẩu
            $dia_chi = $_POST['dia_chi'];
            $so_dien_thoai = $_POST['so_dien_thoai'];
            $role = $_POST['role'];

            $result = $customerModel->create($name, $email, $mat_khau, $dia_chi, $so_dien_thoai, $role);

            if ($result) {
                $_SESSION['success_message'] = "Tạo tài khoản thành công!";
            } else {
                $_SESSION['error_message'] = "Không thể tạo tài khoản. Vui lòng thử lại!";
            }

            header('Location: ?act=customer');
            exit();
        }
        // Truyền dữ liệu vào view
        $view = 'customer/create';
        $title = 'Thêm tài khoản mới';
        require_once './views/admin/main.php';
    }

    public function customerEditUpdate()
    {
        $this->checkAdmin();
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            require_once './models/admin/Customer.php';
            $customerModel = new Customer($this->db);

            // Handle POST request for update
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $mat_khau = !empty($_POST['mat_khau']) ? password_hash($_POST['mat_khau'], PASSWORD_DEFAULT) : null;
                $dia_chi = $_POST['dia_chi'];
                $so_dien_thoai = $_POST['so_dien_thoai'];
                $role = $_POST['role'];

                $result = $customerModel->update($id, $name, $email, $mat_khau, $dia_chi, $so_dien_thoai, $role);

                if ($result) {
                    $_SESSION['success_message'] = "Cập nhật tài khoản thành công!";
                } else {
                    $_SESSION['error_message'] = "Không thể cập nhật tài khoản. Vui lòng thử lại!";
                }

                header('Location: ?act=customer');
                exit();
            }

            // Display edit form
            $customer = $customerModel->getById($id);
            if ($customer) {
                $title = 'customer edit';
                $view = 'customer/edit';
                require_once './views/admin/main.php';
            } else {
                $_SESSION['error_message'] = "Tài khoản không tồn tại!";
                header('Location: ?act=customer');
                exit();
            }
        } else {
            $_SESSION['error_message'] = "ID không hợp lệ!";
            header('Location: ?act=customer');
            exit();
        }
    }
    // Danh sách đơn hàng
    public function orders()
    {
        $this->checkAdmin();
        require_once './models/admin/Order.php';
        $orderModel = new Order($this->db);
        $orders = $orderModel->getAll();
        $view = 'orders/list';
        $title = 'Danh sách đơn hàng';
        require_once './views/admin/main.php';
    }

    // Chi tiết đơn hàng
    public function orderDetail()
    {
        $this->checkAdmin();
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = "Không tìm thấy đơn hàng!";
            header('Location: ?act=orders');
            exit();
        }

        $id = $_GET['id'];
        require_once './models/admin/Order.php';
        $orderModel = new Order($this->db);
        $order = $orderModel->getById($id);
        $orderItems = $orderModel->getOrderItems($id);

        if (!$order) {
            $_SESSION['error_message'] = "Đơn hàng không tồn tại!";
            header('Location: ?act=orders');
            exit();
        }

        $view = 'orders/detail';
        $title = 'Chi tiết đơn hàng #' . $id;
        require_once './views/admin/main.php';
    }

    public function updateOrderStatus()
{
    $this->checkAdmin();

    // Kiểm tra dữ liệu đầu vào
    if (!isset($_GET['id']) || !isset($_GET['status'])) {
        $_SESSION['error_message'] = "Thiếu thông tin để cập nhật trạng thái!";
        header('Location: index.php?act=orders');
        exit();
    }

    $id = (int)$_GET['id'];
    $newStatus = (int)$_GET['status'];

    // Kiểm tra trạng thái mới có hợp lệ không
    $validStatuses = $this->getValidStatuses(); // Giả sử phương thức này trả về danh sách trạng thái hợp lệ
    if (!in_array($newStatus, $validStatuses)) {
        $_SESSION['error_message'] = "Trạng thái '$newStatus' không hợp lệ!";
        header('Location: index.php?act=orders');
        exit();
    }

    // Khởi tạo model Order
    require_once './models/admin/Order.php';
    $orderModel = new Order($this->db);
    $order = $orderModel->getById($id);

    if (!$order) {
        $_SESSION['error_message'] = "Đơn hàng không tồn tại!";
        header('Location: index.php?act=orders');
        exit();
    }

    $currentStatus = $order['trang_thai'];

    // Logic kiểm tra khi hủy đơn hàng (giữ nguyên như cũ)
    if ($newStatus == 5) { // Trạng thái "Đã hủy"
        if ($currentStatus != 0 && $currentStatus != 1) {
            $_SESSION['error_message'] = "Chỉ có thể hủy đơn hàng ở trạng thái 'Chờ xác nhận' hoặc 'Đang xử lý'!";
            header('Location: index.php?act=orderDetail&id=' . $id);
            exit();
        }
    }

    // Ngăn cập nhật trạng thái nếu đơn hàng đã bị hủy
    if ($currentStatus == 5 && $newStatus != 5) {
        $_SESSION['error_message'] = "Đơn hàng đã bị hủy, không thể cập nhật trạng thái!";
        header('Location: index.php?act=orderDetail&id=' . $id);
        exit();
    }

    // Logic quan trọng: Khi đơn hàng đã hoàn thành (4), chỉ cho phép chuyển sang "Trả hàng/Hoàn tiền" (6)
    if ($currentStatus == 4 && $newStatus != 6) {
        $_SESSION['error_message'] = "Đơn hàng đã hoàn thành, chỉ có thể chuyển sang 'Trả hàng/Hoàn tiền'!";
        header('Location: index.php?act=orderDetail&id=' . $id);
        exit();
    }

    // Thực hiện cập nhật trạng thái
    $result = $orderModel->updateStatus($id, $newStatus);

    if ($result) {
        // Nếu chuyển sang trạng thái "Hoàn thành" (4), xử lý logic hoàn thành
        if ($newStatus == 4) {
            $orderModel->completeOrder($id);
        }
        $_SESSION['success_message'] = "Cập nhật trạng thái đơn hàng thành công!";
    } else {
        $_SESSION['error_message'] = "Có lỗi xảy ra khi cập nhật trạng thái!";
    }

    header('Location: index.php?act=orderDetail&id=' . $id);
    exit();
}

    // Hàm lấy danh sách trạng thái hợp lệ từ bảng trang_thai_don_hang
    private function getValidStatuses()
    {
        // Trả về mảng các trạng thái hợp lệ từ 0 đến 6
        return [0, 1, 2, 3, 4, 5, 6];
    }

    // Xóa đơn hàng
    public function deleteOrder()
    {
        $this->checkAdmin();
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = "Không tìm thấy đơn hàng để xóa!";
            header('Location: ?act=orders');
            exit();
        }

        $id = $_GET['id'];
        require_once './models/admin/Order.php';
        $orderModel = new Order($this->db);
        $result = $orderModel->delete($id);

        if ($result) {
            $_SESSION['success_message'] = "Xóa đơn hàng thành công!";
        } else {
            $_SESSION['error_message'] = "Có lỗi xảy ra khi xóa đơn hàng!";
        }

        header('Location: ?act=orders');
        exit();
    }

    // Cập nhật thông tin đơn hàng
    public function updateOrder()
    {
        $this->checkAdmin();
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = "Không tìm thấy đơn hàng để cập nhật!";
            header('Location: ?act=orders');
            exit();
        }

        $id = $_GET['id'];
        require_once './models/admin/Order.php';
        $orderModel = new Order($this->db);
        $order = $orderModel->getById($id);

        if (!$order) {
            $_SESSION['error_message'] = "Đơn hàng không tồn tại!";
            header('Location: ?act=orders');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dia_chi_giao = $_POST['dia_chi_giao'];
            $ghi_chu = $_POST['ghi_chu'] ?? '';

            $result = $orderModel->updateOrderInfo($id, $dia_chi_giao, $ghi_chu);

            if ($result) {
                $_SESSION['success_message'] = "Cập nhật thông tin đơn hàng thành công!";
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi cập nhật thông tin đơn hàng!";
            }

            header('Location: ?act=orderDetail&id=' . $id);
            exit();
        }

        $view = 'orders/edit';
        $title = 'Chỉnh sửa đơn hàng #' . $id;
        require_once './views/admin/main.php';
    }

    // Cập nhật sản phẩm trong đơn hàng
    public function updateOrderItems()
    {
        $this->checkAdmin();
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = "Không tìm thấy đơn hàng để cập nhật!";
            header('Location: ?act=orders');
            exit();
        }

        $id = $_GET['id'];
        require_once './models/admin/Order.php';
        $orderModel = new Order($this->db);
        $order = $orderModel->getById($id);

        if (!$order) {
            $_SESSION['error_message'] = "Đơn hàng không tồn tại!";
            header('Location: ?act=orders');
            exit();
        }

        if ($order['trang_thai'] != 1) { // Chỉ cho phép sửa nếu đơn hàng đang ở trạng thái "Chờ xác nhận"
            $_SESSION['error_message'] = "Chỉ có thể sửa đơn hàng ở trạng thái 'Chờ xác nhận'!";
            header('Location: ?act=orderDetail&id=' . $id);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $items = [];
            foreach ($_POST['items'] as $item) {
                $items[] = [
                    'id_sp' => $item['id_sp'],
                    'so_luong' => $item['so_luong'],
                    'don_gia' => $item['don_gia']
                ];
            }

            $result = $orderModel->updateOrderItems($id, $items);

            if ($result) {
                $_SESSION['success_message'] = "Cập nhật sản phẩm trong đơn hàng thành công!";
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi cập nhật sản phẩm trong đơn hàng!";
            }

            header('Location: ?act=orderDetail&id=' . $id);
            exit();
        }

        $orderItems = $orderModel->getOrderItems($id);
        $view = 'orders/edit_items';
        $title = 'Chỉnh sửa sản phẩm đơn hàng #' . $id;
        require_once './views/admin/main.php';
    }

    // Xử lý hoàn tiền/hủy đơn
    public function processRefund()
    {
        $this->checkAdmin();
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = "Không tìm thấy đơn hàng để xử lý!";
            header('Location: ?act=orders');
            exit();
        }

        $id = $_GET['id'];
        require_once './models/admin/Order.php';
        $orderModel = new Order($this->db);
        $order = $orderModel->getById($id);

        if (!$order) {
            $_SESSION['error_message'] = "Đơn hàng không tồn tại!";
            header('Location: ?act=orders');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ly_do = $_POST['ly_do'];
            $hoan_tien = isset($_POST['hoan_tien']) ? 1 : 0;

            $result = $orderModel->processRefund($id, $ly_do, $hoan_tien);

            if ($result) {
                $_SESSION['success_message'] = "Xử lý hoàn tiền/hủy đơn thành công!";
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi xử lý hoàn tiền/hủy đơn!";
            }

            header('Location: ?act=orderDetail&id=' . $id);
            exit();
        }

        $view = 'orders/refund';
        $title = 'Xử lý hoàn tiền/hủy đơn #' . $id;
        require_once './views/admin/main.php';
    }

    // Xuất hóa đơn PDF
    public function exportInvoice()
    {
        $this->checkAdmin();
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = "Không tìm thấy đơn hàng để xuất hóa đơn!";
            header('Location: ?act=orders');
            exit();
        }

        $id = $_GET['id'];
        require_once './models/admin/Order.php';
        $orderModel = new Order($this->db);
        $order = $orderModel->getById($id);
        $orderItems = $orderModel->getOrderItems($id);

        if (!$order) {
            $_SESSION['error_message'] = "Đơn hàng không tồn tại!";
            header('Location: ?act=orders');
            exit();
        }

        // Tạo PDF
        require_once './vendor/autoload.php'; // Cần cài đặt thư viện TCPDF hoặc FPDF

        // Code tạo PDF ở đây
        // ...

        // Tải xuống file PDF
        $pdf->Output('hoa_don_' . $id . '.pdf', 'D');
        exit();
    }

    public function search()
    {
        $this->checkAdmin();
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        
        if (empty($keyword)) {
            header('Location: index.php?act=admin');
            return;
        }

        require_once './models/admin/Order.php';
        require_once './models/admin/Product.php';
        require_once './models/admin/Customer.php';

        $orderModel = new Order($this->db);
        $productModel = new Product($this->db);
        $customerModel = new Customer($this->db);

        $results = [
            'orders' => $orderModel->searchOrders($keyword),
            'products' => $productModel->searchProducts($keyword),
            'customers' => $customerModel->searchCustomers($keyword)
        ];

        require_once './views/admin/search_results.php';
    }

    public function contactList(){
        require_once './models/admin/Contact.php';
        $contactPost = new Contact();
        $contacts = $contactPost->getContact();
        // print_r($contacts);
        // die();

        $view = 'contact/list';
        $title = 'Danh sách liên hệ';
        require_once './views/admin/main.php';
    }
}
