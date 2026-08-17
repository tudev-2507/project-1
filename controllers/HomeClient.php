<?php

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
        $productCart = [];
        $subtotal = 0;
        $shipping = 30000; // Phí vận chuyển cố định
        $tax = 0;
        $total = 0;
        $discount = 0;

        $discountId = isset($_SESSION['idDiscount']) && is_numeric($_SESSION['idDiscount']) 
              ? (int)$_SESSION['idDiscount'] 
              : null;

        

        if (!isset($_SESSION['user'])) {
            $_SESSION['error_message'] = "Vui lòng đăng nhập để đặt hàng!";
            header('Location: ?act=login');
            exit();
        }

        $idUser = $_SESSION['user']['id'];

        require_once './models/client/cart.php';
        $cartModel = new Cart($this->db);
        $productCart = $cartModel->getAll($idUser);

        if (empty($productCart)) {
           
            header('Location: ?act=cart_home');
            exit();
        }

        // Tính toán tổng tiền
        foreach ($productCart as $item) {
            $subtotal += $item['don_gia'] * $item['so_luong'];
        }
        $tax = $subtotal * 0.02; // Thuế 2%
        

        // Lấy danh sách mã giảm giá hợp lệ
        require_once './models/admin/Discount.php';
        $discountModel = new Discount($this->db);
        $availableDiscounts = $discountModel->getAvailableDiscounts($subtotal);

        // Truy vấn phương thức vận chuyển
        $queryShipping = "SELECT * FROM pt_van_chuyen";
        $stmtShipping = $this->db->prepare($queryShipping);
        $stmtShipping->execute();
        $shippingMethods = $stmtShipping->fetchAll(PDO::FETCH_ASSOC);


        // Truy vấn phương thức thanh toán
        $queryPayment = "SELECT id, name FROM pt_thanh_toan";
        $stmtPayment = $this->db->prepare($queryPayment);
        $stmtPayment->execute();
        $paymentMethods = $stmtPayment->fetchAll(PDO::FETCH_ASSOC);

        // Truy vấn thông tin địa chỉ của người dùng
        $queryUser = "SELECT name, dia_chi, so_dien_thoai FROM tai_khoan WHERE id = :id";
        $stmtUser = $this->db->prepare($queryUser);
        $stmtUser->execute([':id' => $idUser]);
        $userInfo = $stmtUser->fetch(PDO::FETCH_ASSOC);

        $discountAmount = 0; // giá trị mặc định
            foreach ($availableDiscounts as $discount) {
                if ($discount['id'] == $discountId) {
                    $discountAmount = (int) $discount['loai_km'] === 1
                        ? $subtotal * ((float) $discount['so_tien_giam'] / 100)
                        : (float) $discount['so_tien_giam'];
                    $discountAmount = min($discountAmount, $subtotal);
                    break;
                }
            }
         $_SESSION['discountAmount'] = $discountAmount;

            $total = $subtotal + $shipping + $tax - $discountAmount;

        // Xử lý khi form được submit
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['payment_method'] == 1) {
            $shippingAddress = $_POST['shipping_address'];
            $paymentMethod = $_POST['payment_method'];
            $shippingMethod = $_POST['shipping_method'];

            if($discountAmount > 0 ){
                $total1z = $subtotal + $tax - $discountAmount;
            }


            if($shippingMethod == 2 ){
                $total1  = $subtotal + 45000 + $tax - $discountAmount;
            }else{
                $total1  = $subtotal + 30000 + $tax - $discountAmount;
            }
            
            // Lưu vào bảng don_hang
            $query = "INSERT INTO don_hang (id_tai_khoan, ngay_dat, trang_thai, dia_chi, so_dien_thoai, tong_tien, id_pt_van_chuyen, id_km, id_pt_thanh_toan)
                      VALUES (:id_tai_khoan, :ngay_dat, 0, :dia_chi, :so_dien_thoai, :tong_tien, :id_pt_van_chuyen, :id_km, :id_pt_thanh_toan)";
            $stmt = $this->db->prepare($query);

            $addressParts = explode(',', $shippingAddress);
            $phone = trim(end($addressParts));
            

            $stmt->execute([
                ':id_tai_khoan' => $idUser,
                ':ngay_dat' => date('Y-m-d'),
                ':dia_chi' => $shippingAddress,
                ':so_dien_thoai' => $phone,
                ':tong_tien' => $total1,
                ':id_pt_van_chuyen' => $shippingMethod,
                ':id_km' => $discountId,
                ':id_pt_thanh_toan' => $paymentMethod
            ]);

            $orderId = $this->db->lastInsertId();

            // Lưu chi tiết đơn hàng
            $queryDetail = "INSERT INTO chi_tiet_don_hang (id_dh, id_ct_sp, so_luong, don_gia, thanh_tien)
                            VALUES (:id_dh, :id_ct_sp, :so_luong, :don_gia, :thanh_tien)";
            $stmtDetail = $this->db->prepare($queryDetail);
            $totalItems = $item['so_luong']*$item['don_gia'];

            foreach ($productCart as $item) {
                $stmtDetail->execute([
                    ':id_dh' => $orderId,
                    ':id_ct_sp' => $item['id_ct_sp'],
                    ':so_luong' => $item['so_luong'],
                    ':don_gia' => $item['don_gia'],
                    ':thanh_tien' => $totalItems
                ]);
            }

            // Xóa giỏ hàng sau khi đặt hàng thành công
            $queryDelete = "DELETE FROM gio_hang WHERE id_tai_khoan = :id_tai_khoan";
            $stmtDelete = $this->db->prepare($queryDelete);
            $stmtDelete->execute([':id_tai_khoan' => $idUser]);
            unset($_SESSION['idDiscount']);

            header('Location: ?act=order_success');
            exit();
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['payment_method'] == 3) {
            $shippingAddress = $_POST['shipping_address'];
            $paymentMethod = $_POST['payment_method'];
            $shippingMethod = $_POST['shipping_method'];

            // Áp dụng mã giảm giá nếu có

            $_SESSION['productCart'] = $productCart;
            $_SESSION['shippingAddress'] = $shippingAddress;
            $_SESSION['paymentMethod'] = $paymentMethod;
            $_SESSION['shippingMethod'] = $shippingMethod;
            $_SESSION['discountId'] = $discountId;
            $_SESSION['total'] = $total;

            $this->vnPay();
        }

        // Truyền dữ liệu vào view
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

    public function vnPay(){
        
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = app_url('?act=vnPay_return');
        $vnp_TmnCode = "ZDWRLG3V";//Mã website tại VNPAY 
        $vnp_HashSecret = "SFBZ78S83BOM4V4RE8JWHW6R7GI2JG4T"; //Chuỗi bí mật
        $vnp_TxnRef = rand(0,99999); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này 
        $vnp_OrderInfo = 'zenca';
        $vnp_OrderType = 'Thanh toán vnPay ';
        $vnp_Amount = $_POST['total'] * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params of 2.0.1 Version
       
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            
        );
        
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }
        
        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array('code' => '00'
            , 'message' => 'success'
            , 'data' => $vnp_Url);
            if ($_POST['payment_method'] == 3) {
                header('Location: ' . $vnp_Url);
                die();
            } else {
                echo json_encode($returnData);
            }
        
        }
    
        public function vnPayReturn(){
            if(isset($_GET['vnp_ResponseCode'])) {
                // Nếu thanh toán thành công
                if($_GET['vnp_ResponseCode'] == '00'){
                    $shippingAddress = $_SESSION['shippingAddress'];
                    $paymentMethod = $_SESSION['paymentMethod'];
                    $shippingMethod = $_SESSION['shippingMethod'];
                    $discountId = $_SESSION['discountId'];
                    $total = $_SESSION['total'];
                    $productCart = $_SESSION['productCart'];
                    
                    $idUser = $_SESSION['user']['id'];
        
                    $query = "INSERT INTO don_hang (id_tai_khoan, ngay_dat, trang_thai, dia_chi, so_dien_thoai, tong_tien, id_pt_van_chuyen, id_km, id_pt_thanh_toan)
                      VALUES (:id_tai_khoan, :ngay_dat, 0, :dia_chi, :so_dien_thoai, :tong_tien, :id_pt_van_chuyen, :id_km, :id_pt_thanh_toan)";
                        $stmt = $this->db->prepare($query);
        
                        // Lấy số điện thoại từ địa chỉ (giả sử định dạng: "Tên, Địa chỉ, Số điện thoại")
                        $addressParts = explode(',', $shippingAddress);
                        $phone = trim(end($addressParts));
        
                        $stmt->execute([
                            ':id_tai_khoan' => $idUser,
                            ':ngay_dat' => date('Y-m-d'),
                            ':dia_chi' => $shippingAddress,
                            ':so_dien_thoai' => $phone,
                            ':tong_tien' => $total,
                            ':id_pt_van_chuyen' => $shippingMethod,
                            ':id_km' => $discountId, // Lưu id mã giảm giá (nếu có)
                            ':id_pt_thanh_toan' => $paymentMethod
                        ]);
        
                        $queryDelete = "DELETE FROM gio_hang WHERE id_tai_khoan = :id_tai_khoan";
                        $stmtDelete = $this->db->prepare($queryDelete);
                        $stmtDelete->execute([':id_tai_khoan' => $idUser]);
        
                        $query = "SELECT id FROM don_hang WHERE id_tai_khoan = :idUser ORDER BY id DESC LIMIT 1";
                        $stmt = $this->db->prepare($query);
                        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
                        $stmt->execute();
                        $orderId = $stmt->fetchColumn();
        
                        // Lưu chi tiết đơn hàng
                        $queryDetail = "INSERT INTO chi_tiet_don_hang (id_dh, id_ct_sp, so_luong, don_gia, thanh_tien)
                                        VALUES (:id_dh, :id_ct_sp, :so_luong, :don_gia, :thanh_tien)";
                        $stmtDetail = $this->db->prepare($queryDetail);
                
                        foreach ($productCart as $item) {
                            $itemTotal = $item['don_gia'] * $item['so_luong'];
                            $stmtDetail->execute([
                                ':id_dh' => $orderId,
                                ':id_ct_sp' => $item['id_ct_sp'],
                                ':so_luong' => $item['so_luong'],
                                ':don_gia' => $item['don_gia'],
                                ':thanh_tien' => $itemTotal
                            ]);
                        }
        
                        unset($_SESSION['shippingAddress']);
                        unset($_SESSION['paymentMethod']);
                        unset($_SESSION['discountId']);
                        unset($_SESSION['shippingMethod']);
                        unset($_SESSION['total']);
                        unset($_SESSION['idDiscount']);
        
                        header('Location: ?act=order_success');
                        exit();
                } else {
                    // Nếu thanh toán bị hủy hoặc thất bại
                    header('Location: ?act=cart_home'); // hoặc trang chủ: ?act=home
                    exit();
                }
            } else {
                // Nếu không có mã phản hồi
                header('Location: ?act=cart_home'); // hoặc trang chủ: ?act=home
                exit();
            }
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
