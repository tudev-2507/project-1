<?php

require_once __DIR__ . '/../commons/payment.php';

class HomeClient
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function checkAdmin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] == 0;
    }
    public function index()
    {
        require_once './models/client/shop.php';
        $view = 'home';
        $title = 'home';
        $productModel = new Shop();
        $productHome = $productModel->getAll();
        $productBestSelling = $productModel->getProdHot();
        $categoryModel = new Shop();
        $categoryHome = $categoryModel->getCategoryHome();
        $categoryHome2 = $categoryModel->getCategoryHome2();
        require_once './views/client/main.php';
    }
    public function contact()
    {
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['contact'])) {
            $ten = trim($_POST['ten']);
            $email = trim($_POST['email']);
            $so_dien_thoai = trim($_POST['so_dien_thoai']);
            $chu_de = trim($_POST['chu_de']);
            $noi_dung = trim($_POST['noi_dung']);
    
            $errors = [];
    
            // Validate tên
            if (empty($ten)) {
                $errors[] = "Vui lòng nhập tên.";
            }
    
            // Validate email
            if (empty($email)) {
                $errors[] = "Vui lòng nhập email.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không hợp lệ.";
            }
    
            // Validate số điện thoại
            if (empty($so_dien_thoai)) {
                $errors[] = "Vui lòng nhập số điện thoại.";
            } elseif (!preg_match('/^[0-9]{9,11}$/', $so_dien_thoai)) {
                $errors[] = "Số điện thoại không hợp lệ (chỉ gồm 9-11 số).";
            }
    
            // Validate chủ đề
            if (empty($chu_de)) {
                $errors[] = "Vui lòng nhập chủ đề.";
            }
    
            // Validate nội dung
            if (empty($noi_dung)) {
                $errors[] = "Vui lòng nhập nội dung.";
            }
    
            if (empty($errors)) {
                require_once './models/client/ContactModel.php';
                $contactPost = new Contact();
                $success = $contactPost->postContact($ten, $email, $so_dien_thoai, $chu_de, $noi_dung);
    
                if ($success) {
                    $_SESSION['contact_success'] = "Liên hệ thành công!";
                } else {
                    $_SESSION['contact_errors'] = ["Gửi liên hệ thất bại. Vui lòng thử lại."];
                    $_SESSION['old_input'] = $_POST;
                }
                header("Location: ?act=contact");
                exit();
            } else {
                $_SESSION['contact_errors'] = $errors;
                $_SESSION['old_input'] = $_POST;
                header("Location: ?act=contact");
                exit();
            }
        }
    
        $view = 'contact';
        $title = 'contact';
        require_once './views/client/main.php';
    }
    
    public function shop()
    {
        // Load model Product thay vì Shop để đồng bộ với logic lọc theo danh mục
        require_once './models/admin/Product.php';
        $productModel = new Product($this->db ); // Lưu ý: Product.php không dùng constructor với $db
        $limit = 12;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;
        // Kiểm tra category_id từ URL
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
        $shopID = isset($_GET['shop']) ? (int)$_GET['shop'] : null;
    
        $gia_min = 0; // Giá trị nhỏ nhất mặc định
        $gia_max = 99999999; // Giá trị lớn nhất mặc định
    
        // Kiểm tra và gán giá trị dựa trên dữ liệu người dùng nhập
        if (isset($_GET['min_price']) && is_numeric($_GET['min_price']) && $_GET['min_price'] >= 0) {
            $gia_min = (int)$_GET['min_price'];
        }
    
        if (isset($_GET['max_price']) && is_numeric($_GET['max_price']) && $_GET['max_price'] >= 0) {
            $gia_max = (int)$_GET['max_price'];
        }
    
        // Kiểm tra trường hợp người dùng chỉ nhập một trong hai giá trị
        if (isset($_GET['min_price']) && !isset($_GET['max_price'])) {
            // Chỉ nhập min_price, max_price để mặc định lớn
            $gia_max = 99999999;
        } elseif (!isset($_GET['min_price']) && isset($_GET['max_price'])) {
            // Chỉ nhập max_price, min_price để mặc định nhỏ
            $gia_min = 0;
        } elseif (isset($_GET['min_price']) && isset($_GET['max_price'])) {
            // Nhập cả hai, kiểm tra nếu min_price > max_price thì hoán đổi
            if ($gia_min > $gia_max) {
                $temp = $gia_min;
                $gia_min = $gia_max;
                $gia_max = $temp;
            }
        } else {
            // Không nhập gì, giữ nguyên giá trị mặc định
            $gia_min = 0;
            $gia_max = 99999999;
        }
    
        // Kiểm tra bộ lọc
        $loc = isset($_GET['loc']) ? $_GET['loc'] : 0; 
        $keyword = $_GET['keyword'] ?? '';
    
        // Lấy dữ liệu theo bộ lọc (Danh mục, giá, từ khóa, v.v.)
        if ($category_id) {
            
             // Lấy tổng số sản phẩm thuộc danh mục
            $totalProducts = $productModel->countByCategory($category_id);
            $totalPages = ceil($totalProducts / $limit);

            // Lấy sản phẩm theo danh mục và trang
            $productShop = $productModel->getByCategory($category_id, $offset, $limit);
        } else {
            // Lọc sản phẩm tất cả nếu không có category_id
            $productShop = $productModel->getAll($offset, $limit);
            // Lấy tổng số sản phẩm
            $totalProducts = $productModel->countAll();
            // Tính tổng số trang
            $totalPages = ceil($totalProducts / $limit);
        }
    

       
        if ($shopID === 1) {
            require_once './models/client/shop.php';
            $productModel = new Shop($this->db);
            // Lấy sản phẩm mới nhất
            $productShop = $productModel->getNewProducts($offset, $limit);
            // Lấy tổng số sản phẩm
            $totalProducts = $productModel->getTotalProducts($offset, $limit);
            // Tính tổng số trang
            $totalPages = ceil($totalProducts / $limit);
        }
    
        if($shopID === 2 ){
            require_once './models/client/shop.php';
            $productModel = new Shop($this->db);
            // Lấy sản phẩm giá tăng dần 
            $productShop = $productModel->getProductsASC($offset, $limit);
            // Lấy tổng số sản phẩm
            $totalProducts = $productModel->getTotalProducts();
            // Tính tổng số trang
            $totalPages = ceil($totalProducts / $limit);
        }
    
        if($shopID === 3 ){
            require_once './models/client/shop.php';
            $productModel = new Shop($this->db);
            // Lấy sản phẩm giá giảm dần 
            $productShop = $productModel->getProductsDESC($offset, $limit);
            // Lấy tổng số sản phẩm
            $totalProducts = $productModel->getTotalProducts();
            // Tính tổng số trang
            $totalPages = ceil($totalProducts / $limit);
        }
    
        if( $loc === 'Lọc' ){
            require_once './models/client/shop.php';
            $productModel = new Shop($this->db);
            // Lọc sản phẩm theo giá
            $productShop = $productModel->getProductsPrice($gia_min, $gia_max, $offset, $limit);
            // Lấy tổng số sản phẩm
            $totalProducts = $productModel->countProductsByPrice($gia_min, $gia_max);
            $totalPages = ceil($totalProducts / $limit);
        }
    
        if ($keyword) {
            require_once './models/client/shop.php';
            $productModel = new Shop($this->db);
        
            // Lấy tổng sản phẩm để phân trang
            $totalProducts = $productModel->countSearchProduct($keyword);
            $totalPages = ceil($totalProducts / $limit);
        
            // Lấy sản phẩm theo trang
            $productShop = $productModel->searchProduct($keyword, $offset, $limit);
        }
        
    
        // Lấy danh mục cho menu
        require_once './models/admin/Category.php';
        $categoryModel = new Category($this->db);
        $categories = $categoryModel->getCategoriesWithProductCount();
        $categories1 = $categoryModel->getAll();
    
        // Phân trang
        // $totalProducts = $productModel->getTotalProducts($category_id, $gia_min, $gia_max, $keyword);
       
       
    
        $view = 'layout/shop';
        $title = 'shop';
        require_once './views/client/main.php';
    }
    


    public function detail()
    {
        require_once './models/client/detail.php';
        $view = 'layout/detail';
        $title = 'detail';
        $id = $_GET['id'];

        if (!$id) {
            header('Location: ?act=shop');
            exit();
        } else {
            $productModel = new Detail($this->db);
            $productDetail = $productModel->getAll($id); // Lấy chi tiết sản phẩm
            $relatedProduct = $productModel->getProd($id); // Lấy sản phẩm liên quan
            require_once './views/client/main.php';
        }
    }

    public function cartHome()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error_message'] = "Vui lòng đăng nhập để xem giỏ hàng!";
            header('Location: ?act=login');
            exit();
        }

        $idUser = $_SESSION['user']['id'];

        require_once './models/client/cart.php';
        $cart = new Cart($this->db);

        if (isset($_GET['delete'])) {
            $idBienThe = $_GET['delete'];
            $result = $cart->deleteFromCart($idUser, $idBienThe);
           
            header("Location: ?act=cart_home");
            exit();
        }

        $productCart = $cart->getAll($idUser);

        // Tính tổng tiền để lấy mã giảm giá hợp lệ
        $subtotal = 0;
        foreach ($productCart as $item) {
            $subtotal += $item['don_gia'] * $item['so_luong'];
        }

        // Lấy danh sách mã giảm giá hợp lệ
        require_once './models/admin/Discount.php';
        $discountModel = new Discount($this->db);
        $availableDiscounts = $discountModel->getAvailableDiscounts($subtotal);

        $view = 'layout/cart';
        $title = 'Giỏ hàng';
        require_once './views/client/main.php';
    }
    public function cart()
    {

        $idUser = $_SESSION['user']['id'];

        require_once './models/client/cart.php';
        require_once './models/client/detail.php';

        $cart = new Cart($this->db);
        $productCart = $cart->getAll($idUser);
        


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $kich_co = $_POST['kich_co'];
            $mau_sac = $_POST['mau_sac'];

            $productModel = new Cart($this->db);
            $productDetailCart = $productModel->getDetailCart($id, $kich_co, $mau_sac);
            $idBienThe = $productDetailCart[0]['id_bien_the'];


            if (empty($productDetailCart)) {
                $_SESSION['errorCart'] = 'Sản phẩm đã hết size hoặc màu';
                // Giữ lại ở trang chi tiết
                header("Location: ?act=detail&id=$id");
                exit;
            } else {
                $soLuong = 1;
                // Xử lý thêm vào giỏ hàng
                $addCart = $cart->addToCart($idUser, $idBienThe, $soLuong);
                // Redirect sang trang giỏ hàng
                header("Location: ?act=cart_home");
                exit;
            }
        }
    }
    public function form_Address()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để đặt hàng!';
            header('Location: ?act=login');
            exit();
        }

        $idUser = (int) $_SESSION['user']['id'];
        $discountId = isset($_SESSION['idDiscount']) && is_numeric($_SESSION['idDiscount'])
            ? (int) $_SESSION['idDiscount']
            : null;

        require_once './models/client/cart.php';
        $productCart = (new Cart($this->db))->getAll($idUser);
        if (empty($productCart)) {
            header('Location: ?act=cart_home');
            exit();
        }

        $baseTotals = calculateCheckoutTotals($productCart, 0);
        $subtotal = $baseTotals['subtotal'];

        require_once './models/admin/Discount.php';
        $availableDiscounts = (new Discount($this->db))->getAvailableDiscounts($subtotal);
        $discountAmount = 0;
        foreach ($availableDiscounts as $discount) {
            if ((int) $discount['id'] === $discountId) {
                $discountAmount = (int) $discount['loai_km'] === 1
                    ? (int) round($subtotal * ((float) $discount['so_tien_giam'] / 100))
                    : (int) round((float) $discount['so_tien_giam']);
                break;
            }
        }

        $shippingMethods = $this->db->query(
            'SELECT id, phuong_thuc, gia FROM pt_van_chuyen ORDER BY id'
        )->fetchAll(PDO::FETCH_ASSOC);
        $paymentMethods = $this->db->query(
            'SELECT id, name FROM pt_thanh_toan ORDER BY id'
        )->fetchAll(PDO::FETCH_ASSOC);

        $stmtUser = $this->db->prepare(
            'SELECT name, dia_chi, so_dien_thoai FROM tai_khoan WHERE id = :id'
        );
        $stmtUser->execute([':id' => $idUser]);
        $userInfo = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (empty($shippingMethods) || empty($paymentMethods)) {
            $_SESSION['payment_error'] = 'Chưa cấu hình phương thức giao hàng hoặc thanh toán.';
            header('Location: ?act=cart_home');
            exit();
        }

        $shipping = (int) $shippingMethods[0]['gia'];
        $totals = calculateCheckoutTotals($productCart, $shipping, $discountAmount);
        $tax = $totals['tax'];
        $discountAmount = $totals['discount'];
        $total = $totals['total'];
        $_SESSION['discountAmount'] = $discountAmount;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $shippingAddress = trim((string) ($_POST['shipping_address'] ?? ''));
            $paymentMethod = filter_var($_POST['payment_method'] ?? null, FILTER_VALIDATE_INT);
            $shippingMethod = filter_var($_POST['shipping_method'] ?? null, FILTER_VALIDATE_INT);

            $selectedShipping = null;
            foreach ($shippingMethods as $method) {
                if ((int) $method['id'] === $shippingMethod) {
                    $selectedShipping = $method;
                    break;
                }
            }

            $allowedPaymentIds = array_map('intval', array_column($paymentMethods, 'id'));
            if ($shippingAddress === '' || $selectedShipping === null
                || !in_array($paymentMethod, $allowedPaymentIds, true)) {
                $_SESSION['payment_error'] = 'Thông tin giao hàng hoặc phương thức thanh toán không hợp lệ.';
                header('Location: ?act=form_Address');
                exit();
            }

            $shipping = (int) $selectedShipping['gia'];
            $totals = calculateCheckoutTotals($productCart, $shipping, $discountAmount);
            $subtotal = $totals['subtotal'];
            $tax = $totals['tax'];
            $total = $totals['total'];

            if ($paymentMethod === 1) {
                $addressParts = explode(',', $shippingAddress);
                $phone = trim((string) end($addressParts));

                try {
                    $this->db->beginTransaction();
                    $stmt = $this->db->prepare(
                        'INSERT INTO don_hang
                        (id_tai_khoan, ngay_dat, trang_thai, dia_chi, so_dien_thoai, tong_tien,
                         id_pt_van_chuyen, id_km, id_pt_thanh_toan)
                        VALUES (:id_tai_khoan, :ngay_dat, 0, :dia_chi, :so_dien_thoai, :tong_tien,
                                :id_pt_van_chuyen, :id_km, :id_pt_thanh_toan)'
                    );
                    $stmt->execute([
                        ':id_tai_khoan' => $idUser,
                        ':ngay_dat' => date('Y-m-d'),
                        ':dia_chi' => $shippingAddress,
                        ':so_dien_thoai' => $phone,
                        ':tong_tien' => $total,
                        ':id_pt_van_chuyen' => $shippingMethod,
                        ':id_km' => $discountId,
                        ':id_pt_thanh_toan' => $paymentMethod,
                    ]);

                    $orderId = (int) $this->db->lastInsertId();
                    $stmtDetail = $this->db->prepare(
                        'INSERT INTO chi_tiet_don_hang
                        (id_dh, id_ct_sp, so_luong, don_gia, thanh_tien)
                        VALUES (:id_dh, :id_ct_sp, :so_luong, :don_gia, :thanh_tien)'
                    );
                    foreach ($productCart as $item) {
                        $stmtDetail->execute([
                            ':id_dh' => $orderId,
                            ':id_ct_sp' => $item['id_ct_sp'],
                            ':so_luong' => $item['so_luong'],
                            ':don_gia' => $item['don_gia'],
                            ':thanh_tien' => (int) round($item['don_gia'] * $item['so_luong']),
                        ]);
                    }

                    $stmtDelete = $this->db->prepare(
                        'DELETE FROM gio_hang WHERE id_tai_khoan = :id_tai_khoan'
                    );
                    $stmtDelete->execute([':id_tai_khoan' => $idUser]);
                    $this->db->commit();
                    unset($_SESSION['idDiscount']);
                    header('Location: ?act=order_success');
                    exit();
                } catch (Throwable $e) {
                    if ($this->db->inTransaction()) {
                        $this->db->rollBack();
                    }
                    $_SESSION['payment_error'] = 'Không thể tạo đơn hàng. Vui lòng thử lại.';
                    header('Location: ?act=form_Address');
                    exit();
                }
            }

            if ($paymentMethod === 3) {
                $txnRef = $idUser . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(3));
                $_SESSION['vnpay_payment'] = [
                    'txn_ref' => $txnRef,
                    'amount' => $total,
                    'user_id' => $idUser,
                    'cart' => $productCart,
                    'shipping_address' => $shippingAddress,
                    'shipping_method' => $shippingMethod,
                    'discount_id' => $discountId,
                    'payment_method' => $paymentMethod,
                ];

                $this->vnPay($txnRef, $total);
            }
        }

        $view = 'layout/formAddress';
        $title = 'Thanh toán';
        require_once './views/client/main.php';
    }

    public function getDiscount(){
        if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['discountSubmit']){
            $idDiscount = $_POST['discount'] ?? 0 ;
            $_SESSION['idDiscount'] = $idDiscount;
            // echo($_SESSION['idDiscount']);
            // die();
        };
        header('Location: ?act=form_Address');
        exit();
        
    }

    public function order_success()
{
    // Kiểm tra đăng nhập
    if (!isset($_SESSION['user'])) {
        $_SESSION['error_message'] = "Vui lòng đăng nhập để xem thông tin đơn hàng!";
        header('Location: ?act=login');
        exit();
    }

    $idUser = $_SESSION['user']['id'];

    // Khởi tạo OrderModel
    require_once './models/client/OrderModel.php';
    $orderModel = new OrderModel($this->db);

    // Lấy đơn hàng cuối cùng
    $order = $orderModel->getLastOrder($idUser);
    if (!$order) {
        $_SESSION['error_message'] = "Không tìm thấy đơn hàng nào!";
        header('Location: ?act=cart_home');
        exit();
    }



    // Lấy chi tiết đơn hàng
    $orderItems = $orderModel->getOrderItems($order['id']);

    // print_r($orderItems);
    // die();
    // Tính toán các giá trị tổng
    $subtotal = 0;
    foreach ($orderItems as $item) {
        $subtotal += $item['thanh_tien'];
    }
    

    // print_r($orderItems);
    // die();
    $tax = $subtotal * 0.02; // Thuế 2%
    $discount = $_SESSION['discountAmount'] ?? 0 ; // Nếu có mã giảm giá, bạn có thể lấy từ bảng khuyen_mai

    // Lấy thông tin người dùng từ UserModel
    require_once './models/client/UserModel.php';
    $userModel = new UserModel($this->db);
    $user = $userModel->getUserInfo($idUser);
    $order['ten_kh'] = $user['name']; // Thêm tên khách hàng vào mảng order

    // Truyền dữ liệu vào view
    $view = 'layout/orderSuccess';
    $title = 'orderSuccess';
    require_once './views/client/main.php';
}

public function user()
{
    // Kiểm tra đăng nhập
    if (!isset($_SESSION['user'])) {
        $_SESSION['error_message'] = "Vui lòng đăng nhập để xem trang này!";
        header('Location: ?act=login');
        exit();
    }

    $idUser = $_SESSION['user']['id'];

    // Khởi tạo UserModel
    require_once './models/client/UserModel.php';
    $userModel = new UserModel($this->db);

    // Lấy thông tin người dùng
    $userInfo = $userModel->getUserInfo($idUser);
    if (!$userInfo) {
        $_SESSION['error_message'] = "Không tìm thấy thông tin người dùng!";
        header('Location: ?act=login');
        exit();
    }

    // Lấy tổng số đơn hàng
    $totalOrders = $userModel->getTotalOrders($idUser);

    // Lấy danh sách đơn hàng
    $orderList = $userModel->getOrderList($idUser);

    // Lấy chi tiết sản phẩm cho từng đơn hàng
    foreach ($orderList as &$order) {
        $order['items'] = $userModel->getOrderItems($order['id']);
    }

    // Truyền dữ liệu vào view
    $view = 'user';
    $title = 'Trang cá nhân';
    require_once './views/client/main.php';
}
public function cancelOrder()
{
    // Kiểm tra đăng nhập
    if (!isset($_SESSION['user'])) {
        $_SESSION['error_message'] = "Vui lòng đăng nhập để thực hiện hành động này!";
        header('Location: ?act=login');
        exit();
    }

    $idUser = $_SESSION['user']['id'];
    $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    // echo($orderId);
    // die();

    // Kiểm tra tính hợp lệ của orderId
    if ($orderId <= 0) {
        $_SESSION['error_message'] = "Đơn hàng không hợp lệ!";
        header('Location: ?act=user');
        exit();
    }

    // Load model và lấy thông tin đơn hàng
    require_once './models/admin/Order.php';
    $orderModel = new Order($this->db);
    $order = $orderModel->getById($orderId);


    // Kiểm tra đơn hàng tồn tại, thuộc về người dùng và ở trạng thái "Chờ xác nhận"
    // if (!$order || $order['id_tai_khoan'] != $idUser || $order['trang_thai'] != 0) {
    //     $_SESSION['error_message'] = "Không thể hủy đơn hàng này!";
    //     header('Location: ?act=user');
    //     exit();
    // }

    // Cập nhật trạng thái đơn hàng thành "Đã hủy" (giả sử 5 là "Đã hủy")
    $result = $orderModel->updateStatus($orderId, 6);


    if ($result) {
        // Hoàn lại số lượng tồn kho
        $orderItems = $orderModel->getOrderItems($orderId);
        foreach ($orderItems as $item) {
            $query = "UPDATE chi_tiet_sp SET so_luong = so_luong + :so_luong WHERE id = :id_ct_sp";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':so_luong' => $item['so_luong'],
                ':id_ct_sp' => $item['id_ct_sp']
            ]);
        }
    } else {
        $_SESSION['error_message'] = "Có lỗi xảy ra khi hủy đơn hàng!";
    }
    $_SESSION['success_update_order'] = "Đơn hàng đã được hủy thành công!";
    header('Location: ?act=user');
    exit();
}



    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $mat_khau = $_POST['mat_khau'];

            require_once './models/admin/Customer.php';
            $customerModel = new Customer($this->db);

            // Tìm người dùng theo email
            $query = "SELECT * FROM tai_khoan WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Kiểm tra thông tin đăng nhập
            if ($user && (int) ($user['trang_thai'] ?? 1) !== 1) {
                $_SESSION['error_message'] = "Tài khoản đang Inactive. Vui lòng liên hệ quản trị viên!";
                header('Location: ?act=login');
                exit();
            }

            if ($user && password_verify($mat_khau, $user['mat_khau'])) {
                // Đăng nhập thành công, lưu thông tin vào session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'role' => $user['role'],
                ];

                // Chuyển hướng dựa trên vai trò
                if ($user['role'] == 0) {
                    // Admin
                    header('Location: ?act=admin');
                } else {
                    // Khách hàng
                    header('Location: ?act=home');
                }
                exit();
            } else {
                // Đăng nhập thất bại
                $_SESSION['error_message'] = "Email hoặc mật khẩu không đúng!";
                header('Location: ?act=login');
                exit();
            }
        }

        $view = 'login';
        $title = 'Đăng nhập';
        require_once './views/client/main.php';
    }

    // Xử lý đăng ký
    public function sign_up()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $mat_khau_raw = $_POST['mat_khau'];
            $dia_chi = trim($_POST['dia_chi']);
            $so_dien_thoai = trim($_POST['so_dien_thoai']);
            $role = 1; // Mặc định là khách hàng
    
            $errors = [];
    
            // Validate tên
            if (empty($name)) {
                $errors[] = "Vui lòng nhập họ tên.";
            }
    
            // Validate email
            if (empty($email)) {
                $errors[] = "Vui lòng nhập email.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không đúng định dạng.";
            }
    
            // Validate mật khẩu
            if (empty($mat_khau_raw)) {
                $errors[] = "Vui lòng nhập mật khẩu.";
            } elseif (strlen($mat_khau_raw) < 6) {
                $errors[] = "Mật khẩu phải có ít nhất 6 ký tự.";
            }
    
            // Validate địa chỉ
            if (empty($dia_chi)) {
                $errors[] = "Vui lòng nhập địa chỉ.";
            }
    
            // Validate số điện thoại
            if (empty($so_dien_thoai)) {
                $errors[] = "Vui lòng nhập số điện thoại.";
            } elseif (!preg_match('/^[0-9]{10,11}$/', $so_dien_thoai)) {
                $errors[] = "Số điện thoại không hợp lệ";
            }            
    
            // Nếu có lỗi, trả về lại form
            if (!empty($errors)) {
                $_SESSION['sign_up_errors'] = $errors;
                $_SESSION['sign_up_old'] = $_POST; // Lưu dữ liệu đã nhập
                header('Location: ?act=sign_up');
                exit();
            }
    
            $mat_khau = password_hash($mat_khau_raw, PASSWORD_DEFAULT);
    
            require_once './models/admin/Customer.php';
            $customerModel = new Customer($this->db);
    
            // Kiểm tra email đã tồn tại chưa
            $query = "SELECT * FROM tai_khoan WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['error_message'] = "Email đã tồn tại!";
                header('Location: ?act=sign_up');
                exit();
            }
    
            // Tạo tài khoản
            $result = $customerModel->create($name, $email, $mat_khau, $dia_chi, $so_dien_thoai, $role);
    
            if ($result) {
                $_SESSION['success_message'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                header('Location: ?act=login');
                exit();
            } else {
                $_SESSION['error_message'] = "Đăng ký thất bại. Vui lòng thử lại!";
                header('Location: ?act=sign_up');
                exit();
            }
        }
    
        $view = 'signUp';
        $title = 'Đăng ký';
        require_once './views/client/main.php';
    }
    
    public function logout()
    {
        // Xóa thông tin người dùng khỏi session
        unset($_SESSION['user']);

        // Chuyển hướng về trang đăng nhập
        header('Location: ?act=login');
        exit();
    }

    public function vnPay(string $txnRef, int $amount): void
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $config = getVnPayConfig();
        if ($config['tmn_code'] === '' || $config['hash_secret'] === '') {
            unset($_SESSION['vnpay_payment']);
            $_SESSION['payment_error'] = 'VNPay chưa được cấu hình. Vui lòng liên hệ quản trị viên.';
            header('Location: ?act=form_Address');
            exit();
        }

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $config['tmn_code'],
            "vnp_Amount" => $amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            "vnp_Locale" => 'vn',
            "vnp_OrderInfo" => 'Thanh toan don hang ' . $txnRef,
            "vnp_OrderType" => 'other',
            "vnp_ReturnUrl" => app_url('?act=vnPay_return'),
            "vnp_TxnRef" => $txnRef,
            "vnp_ExpireDate" => date('YmdHis', strtotime('+15 minutes')),
        ];

        header('Location: ' . buildVnPayUrl($config['url'], $inputData, $config['hash_secret']));
        exit();
    }

    public function vnPayReturn(): void
    {
        $pending = $_SESSION['vnpay_payment'] ?? null;
        $config = getVnPayConfig();

        if (!is_array($pending) || $config['hash_secret'] === ''
            || !verifyVnPayReturn(
                $_GET,
                $config['hash_secret'],
                (string) ($pending['txn_ref'] ?? ''),
                (int) ($pending['amount'] ?? 0)
            )) {
            unset($_SESSION['vnpay_payment']);
            $_SESSION['payment_error'] = 'Kết quả trả về từ VNPay không hợp lệ.';
            header('Location: ?act=cart_home');
            exit();
        }

        if (($_GET['vnp_ResponseCode'] ?? '') !== '00'
            || ($_GET['vnp_TransactionStatus'] ?? '') !== '00') {
            unset($_SESSION['vnpay_payment']);
            $_SESSION['payment_error'] = 'Thanh toán VNPay chưa thành công hoặc đã bị hủy.';
            header('Location: ?act=cart_home');
            exit();
        }

        $shippingAddress = (string) $pending['shipping_address'];
        $addressParts = explode(',', $shippingAddress);
        $phone = trim((string) end($addressParts));

        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare(
                'INSERT INTO don_hang
                (id_tai_khoan, ngay_dat, trang_thai, dia_chi, so_dien_thoai, tong_tien,
                 id_pt_van_chuyen, id_km, id_pt_thanh_toan)
                VALUES (:id_tai_khoan, :ngay_dat, 0, :dia_chi, :so_dien_thoai, :tong_tien,
                        :id_pt_van_chuyen, :id_km, :id_pt_thanh_toan)'
            );
            $stmt->execute([
                ':id_tai_khoan' => $pending['user_id'],
                ':ngay_dat' => date('Y-m-d'),
                ':dia_chi' => $shippingAddress,
                ':so_dien_thoai' => $phone,
                ':tong_tien' => $pending['amount'],
                ':id_pt_van_chuyen' => $pending['shipping_method'],
                ':id_km' => $pending['discount_id'],
                ':id_pt_thanh_toan' => $pending['payment_method'],
            ]);

            $orderId = (int) $this->db->lastInsertId();
            $stmtDetail = $this->db->prepare(
                'INSERT INTO chi_tiet_don_hang
                (id_dh, id_ct_sp, so_luong, don_gia, thanh_tien)
                VALUES (:id_dh, :id_ct_sp, :so_luong, :don_gia, :thanh_tien)'
            );
            foreach ($pending['cart'] as $item) {
                $stmtDetail->execute([
                    ':id_dh' => $orderId,
                    ':id_ct_sp' => $item['id_ct_sp'],
                    ':so_luong' => $item['so_luong'],
                    ':don_gia' => $item['don_gia'],
                    ':thanh_tien' => (int) round($item['don_gia'] * $item['so_luong']),
                ]);
            }

            $stmtDelete = $this->db->prepare(
                'DELETE FROM gio_hang WHERE id_tai_khoan = :id_tai_khoan'
            );
            $stmtDelete->execute([':id_tai_khoan' => $pending['user_id']]);
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $_SESSION['payment_error'] = 'Không thể hoàn tất đơn hàng sau khi thanh toán.';
            header('Location: ?act=cart_home');
            exit();
        }

        unset($_SESSION['vnpay_payment'], $_SESSION['idDiscount']);
        header('Location: ?act=order_success');
        exit();
    }

    public function updateEmail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $idUser = $_SESSION['user']['id'] ?? 0;

            if (empty($email) || empty($idUser)) {
                $_SESSION['error_update_email'] = 'Vui lòng nhập đầy đủ thông tin';
                header('Location: ?act=user');
                exit;
            }

            $userModel = new UserModel($this->db);
            $user = $userModel->getUserById($idUser);

            if (!$user) {
                $_SESSION['error_update_email'] = 'Không tìm thấy thông tin người dùng';
                header('Location: ?act=user');
                exit;
            }

            if ($userModel->checkEmailExists($email, $idUser)) {
                $_SESSION['error_update_email'] = 'Email đã tồn tại';
                header('Location: ?act=user');
                exit;
            }

            if ($userModel->updateEmail($idUser, $email)) {
                $_SESSION['success_update_email'] = 'Cập nhật email thành công';
                $_SESSION['user']['email'] = $email;
            } else {
                $_SESSION['error'] = 'Cập nhật email thất bại';
            }
        }
        header('Location: ?act=user');
        exit;
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $idUser = $_SESSION['user']['id'] ?? 0;

            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword) || empty($idUser)) {
                $_SESSION['error_update_pass'] = 'Vui lòng nhập đầy đủ thông tin';
                header('Location: ?act=user');
                exit;
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error_update_pass'] = 'Mật khẩu mới không khớp';
                header('Location: ?act=user');
                exit;
            }

            $userModel = new UserModel($this->db);
            $user = $userModel->getUserById($idUser);

            if (!$user) {
                $_SESSION['error_update_pass'] = 'Không tìm thấy thông tin người dùng';
                header('Location: ?act=user');
                exit;
            }

            if (!password_verify($currentPassword, $user['mat_khau'])) {
                $_SESSION['error_update_pass'] = 'Mật khẩu hiện tại không đúng';
                header('Location: ?act=user');
                exit;
            }

            if ($userModel->updatePassword($idUser, $newPassword)) {
                $_SESSION['success_update_pass'] = 'Cập nhật mật khẩu thành công';
            } else {
                $_SESSION['error_update_pass '] = 'Cập nhật mật khẩu thất bại';
            }
        }
        header('Location: ?act=user');
        exit;
    }
    public function updateCartQuantity() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_ct_sp = $_POST['id_ct_sp'] ?? null;
            $so_luong = $_POST['so_luong'] ?? null;
            $idUser = $_SESSION['user']['id'] ?? null;
    
            if (!$id_ct_sp || !$so_luong || !$idUser) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                exit;
            }
    
            require_once './models/client/cart.php';
            $cartModel = new Cart($this->db);
    
            // Cập nhật số lượng trong bảng gio_hang
            $query = "UPDATE gio_hang SET so_luong = :so_luong 
                      WHERE id_tai_khoan = :id_tai_khoan AND id_ct_sp = :id_ct_sp";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([
                ':so_luong' => $so_luong,
                ':id_tai_khoan' => $idUser,
                ':id_ct_sp' => $id_ct_sp
            ]);
    
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại']);
            }
            exit;
        }
    }

    public function update_Address()    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_dia_chi'])) {
            $idUser = $_SESSION['user']['id'] ?? null;
            $ten = $_SESSION['user']['name'] ?? '';
            $dia_chi = trim($_POST['dia_chi'] ?? '');
            $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
    
            // Khởi tạo mảng lỗi
            $errors = [];
    
            // Validate địa chỉ
            if (empty($dia_chi)) {
                $errors[] = "Địa chỉ không được để trống.";
            } elseif (strlen($dia_chi) > 255) {
                $errors[] = "Địa chỉ quá dài.";
            }
    
            // Validate số điện thoại
            if (empty($so_dien_thoai)) {
                $errors[] = "Số điện thoại không được để trống.";
            } elseif (!preg_match('/^0[0-9]{9}$/', $so_dien_thoai)) {
                $errors[] = "Số điện thoại không hợp lệ. Vui lòng nhập 10 chữ số bắt đầu bằng 0.";
            }
    
            // Nếu có lỗi
            if (!empty($errors)) {
                $_SESSION['update_address_errors'] = $errors;
                header('Location: ?act=user');
                exit;
            }
    
            // Không có lỗi, tiến hành cập nhật
           
            $userModel = new UserModel($this->db);
            $success = $userModel->updateAddress($idUser, $ten, $dia_chi, $so_dien_thoai);
    
            if ($success) {
                // Cập nhật session nếu cần
                $_SESSION['user']['dia_chi'] = $dia_chi;
                $_SESSION['user']['so_dien_thoai'] = $so_dien_thoai;
                $_SESSION['update_address_success'] = "Cập nhật địa chỉ thành công!";
            } else {
                $_SESSION['update_address_errors'] = ["Có lỗi xảy ra khi cập nhật. Vui lòng thử lại."];
            }
    
            header('Location: ?act=user');
            exit;
        }
    }
    

}
