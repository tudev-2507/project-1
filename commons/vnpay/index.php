<?php
// Bắt đầu session
session_start();

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ
require_once './commons/validation.php';

// Keep every legacy CSS, JS and image URL on the configured BASE_URL flow.
ob_start('rewrite_legacy_base_urls');

// Kết nối database
$db = connectDB();

// Require toàn bộ file Controllers
require_once './controllers/HomeAdmin.php';
require_once './controllers/HomeClient.php';

// Require toàn bộ file Models
require_once './models/client/cart.php';
require_once './models/client/detail.php';
require_once './models/client/OrderModel.php';
require_once './models/client/shop.php';
require_once './models/client/UserModel.php';

// Route
$act = $_GET['act'] ?? '/';

// Danh sách các hành động của admin
$adminActions = [
    'admin',
    'product',
    'product_edit',
    'product_create',
    'product_delete',
    'product_attribute_create',
    'category',
    'category_edit',
    'category_create',
    'category_delete',
    'discount',
    'discount_create',
    'create_discount',
    'edit_discount',
    'discount_delete',
    'customer',
    'customer_delete',
    'customer_create',
    'customer_edit',
    'customer_status',
    // Thêm các action quản lý đơn hàng
    'orders',
    'orderDetail',
    'updateOrderStatus',
    'deleteOrder',
    'updateOrder',
    'updateOrderItems',
    'processRefund',
    'exportInvoice',
    'search',
];

// Kiểm tra quyền truy cập cho các hành động của admin
if (in_array($act, $adminActions)) {

    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 0) {
        // Nếu không phải admin, chuyển hướng về trang chủ client
        header('Location: ?act=home');
        exit();
    }
}
// Đăng nhập mới vào được các trang này
$protectedClientActions = ['cart', 'form_Address', 'order_success', 'user'];
if (in_array($act, $protectedClientActions) && !isset($_SESSION['user'])) {
    header('Location: ?act=login');
    exit();
}

match ($act) {
    // Trang chủ
    '/' => (new HomeClient($db))->index(),
    'home' => (new HomeClient($db))->index(),

    'admin' => (new HomeAdmin($db))->index(),
    'product' => (new HomeAdmin($db))->product(),
    'product_edit' => (new HomeAdmin($db))->productEdit(),
    'product_create' => (new HomeAdmin($db))->productCreate(),
    'product_delete' => (new HomeAdmin($db))->productDelete(),
    'product_attribute_create' => (new HomeAdmin($db))->productAttributeCreate(),
    'category' => (new HomeAdmin($db))->category(),
    'category_edit' => (new HomeAdmin($db))->categoryEdit(),
    'category_create' => (new HomeAdmin($db))->categoryCreate(),
    'category_delete' => (new HomeAdmin($db))->categoryDelete(),
    'discount' => (new HomeAdmin($db))->discount(),
    'discount_create' => (new HomeAdmin($db))->discountCreate(),
    'create_discount' => (new HomeAdmin($db))->create_discount(),
    'edit_discount' => (new HomeAdmin($db))->edit_discount(),
    'discount_delete' => (new HomeAdmin($db))->discountDelete(),
    'customer' => (new HomeAdmin($db))->customer(),
    'customer_delete' => (new HomeAdmin($db))->customerDelete(),
    'customer_create' => (new HomeAdmin($db))->customerCreate(),
    'customer_edit' => (new HomeAdmin($db))->customerEditUpdate(),
    'customer_status' => (new HomeAdmin($db))->customerStatus(),
    // Thêm các route cho quản lý đơn hàng
    'orders' => (new HomeAdmin($db))->orders(),
    'orderDetail' => (new HomeAdmin($db))->orderDetail(),
    'updateOrderStatus' => (new HomeAdmin($db))->updateOrderStatus(),
    'deleteOrder' => (new HomeAdmin($db))->deleteOrder(),
    'updateOrder' => (new HomeAdmin($db))->updateOrder(),
    'updateOrderItems' => (new HomeAdmin($db))->updateOrderItems(),
    'processRefund' => (new HomeAdmin($db))->processRefund(),
    'exportInvoice' => (new HomeAdmin($db))->exportInvoice(),
    'search' => (new HomeAdmin($db))->search(),
    'contact_list' => (new HomeAdmin($db))->contactList(),

    //---------------------------------------------------------------------------
    'shop' => (new HomeClient($db))->shop(),
    'shopNew' => (new HomeClient($db))->shopNew(),
    'detail' => (new HomeClient($db))->detail(),
    'cart' => (new HomeClient($db))->cart(),
    'cart_home' => (new HomeClient($db))->cartHome(),
    'form_Address' => (new HomeClient($db))->form_Address(),
    'update_Address' => (new HomeClient($db))->update_Address(),
    'order_success' => (new HomeClient($db))->order_success(),
    'user' => (new HomeClient($db))->user(),
    'login' => (new HomeClient($db))->login(),
    'sign_up' => (new HomeClient($db))->sign_up(),
    'logout' => (new HomeClient($db))->logout(),
    'contact' => (new HomeClient($db))->contact(),
    'vnPay_return' => (new HomeClient($db))->vnPayReturn(),
    'update_password' => (new HomeClient($db))->updatePassword(),
    'update_email' => (new HomeClient($db))->updateEmail(),
    'update_cart_quantity' => (new HomeClient($db))->updateCartQuantity(),
    'cancel_order' => (new HomeClient($db))->cancelOrder(),
    'get_discount' => (new HomeClient($db))->getDiscount(),

    default => throw new Exception("Invalid action: $act"),
};
