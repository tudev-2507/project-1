<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from themes.pixelstrap.net/katie/template/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 15 Mar 2025 23:37:37 GMT -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Katie" />
    <meta name="keywords" content="Katie" />
    <meta name="author" content="pixelstrap" />

    <title>Zenca - Online Fashion Store</title>
    <!-- Favicon icon-->
    <!-- <link rel="icon" href="/Duan1-main/public/client/assets/images/favicon.png" type="image/x-icon" /> -->
    <!-- <link rel="shortcut icon" href="/Duan1-main/public/client/assets/images/favicon.png" type="image/x-icon" /> -->
    <!-- Google Font Outfit-->
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&amp;display=swap" rel="stylesheet" />
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="/Duan1-main/public/client/assets/css/vendors/fontawesome.css" />
    <!-- Iconsax icon-->
    <link rel="stylesheet" type="text/css" href="/Duan1-main/public/client/assets/css/vendors/iconsax.css" />
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" id="rtl-link"
        href="/Duan1-main/public/client/assets/css/vendors/bootstrap.css" />
    <link rel="stylesheet" type="text/css"
        href="/Duan1-main/public/client/assets/css/vendors/swiper-slider/swiper-bundle.min.css" />
    <link rel="stylesheet" type="text/css" href="/Duan1-main/public/client/assets/css/vendors/toastify.css" />
    <link rel="stylesheet" type="text/css" href="/Duan1-main/public/client/assets/css/style.css" />
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
    .onhover-div {
        position: relative;
    }

    .onhover-show-div {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        z-index: 1000;
        background: #fff;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    }

    .onhover-div:hover .onhover-show-div {
        display: block;
    }
    </style>


</head>

<body class="skeleton_body">
    <header>
        <div class="top_header">
            <p>
                ĐỢT KHUYẾN MÃI LỚN NHẤT NĂM SẮP ĐỔ BỘ TẠI ZENCA
            </p>
        </div>
        <div class="custom-container container header-1">
            <div class="row">
                <div class="col-12">
                    <div class="main-menu">
                        <a class="brand-logo" href="?act=home">
                            <h1>ZENCA</h1>
                        </a>
                        <nav id="main-nav">
                            <ul class="nav-menu sm-horizontal theme-scrollbar" id="sm-horizontal">
                                <li class="mobile-back" id="mobile-back">
                                    Back<i class="fa-solid fa-angle-right ps-2" aria-hidden="true"></i>
                                </li>
                                <li>
                                    <a class="nav-link" href="?act=home">Trang chủ <span></span></a>

                                </li>
                                <li>
                                    <a class="nav-link" href="?act=shop">Tất cả sản phẩm<span></span></a>

                                </li>
                                <?php
                                // Lấy danh sách danh mục từ database cùng với số lượng sản phẩm
                                require_once './models/admin/Category.php';
                                $categoryModel = new Category($GLOBALS['db']);
                                $categories = $categoryModel->getCategoriesWithProductCount();

                                // Hiển thị danh mục trực tiếp trong menu, chỉ hiển thị nếu có sản phẩm
                                foreach ($categories as $category) {
                                    if ($category['product_count'] > 0) {
                                        echo '<li><a class="nav-link" href="?act=shop&category_id=' . htmlspecialchars($category['id']) . '">' . htmlspecialchars($category['ten_dm']) . '<span> </span></a></li>';
                                    }
                                }
                                ?>
                                <li><a class="nav-link" href="?act=contact">Liên hệ</a></li>
                            </ul>
                        </nav>
                        <div class="sub_header">
                            <div class="toggle-nav" id="toggle-nav">
                                <i class="fa-solid fa-bars-staggered sidebar-bar"></i>
                            </div>
                            <ul class="justify-content-end">
                                <li>
                                    <form class="input-group" method="get" action="?act=shop" style="max-width: 250px;">
                                        <input type="hidden" name="act" value="shop">
                                        <input type="text" name="keyword" class="form-control rounded-start"
                                            placeholder="Tìm kiếm..." autocomplete="off" required>
                                        <button class="btn btn-outline-secondary rounded-end" type="submit">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </form>
                                </li>

                                <li class="onhover-div">
                                    <a href="?act=user"><i class="iconsax" data-icon="user-2"></i></a>
                                    <div class="onhover-show-div user position-absolute">
                                        <ul>
                                            <?php if (isset($_SESSION['user'])): ?>
                                            <!-- Nếu đã đăng nhập -->
                                            <li>
                                                <a href="?act=user">
                                                    Xin chào: <?= htmlspecialchars($_SESSION['user']['name']) ?>
                                                </a>
                                            </li>


                                            <li>
                                                <a href="?act=logout">Đăng xuất</a>
                                            </li>
                                            <?php else: ?>
                                            <!-- Nếu chưa đăng nhập -->
                                            <li><a href="?act=login">Login</a></li>
                                            <li><a href="?act=sign_up">Register</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </li>

                                <li class="onhover-div shopping-cart">
                                    <a class="p-0" href="?act=cart_home">
                                        <div class="shoping-prize">
                                            <?php
                                                // Khởi tạo số lượng sản phẩm trong giỏ hàng
                                                $cartItemCount = 0;

                                                // Kiểm tra nếu người dùng đã đăng nhập
                                                if (isset($_SESSION['user'])) {
                                                    $idUser = $_SESSION['user']['id'];
                                                    require_once './models/client/cart.php';
                                                    $cartModel = new Cart($GLOBALS['db']);
                                                    $productCart = $cartModel->getAll($idUser);
                                                    $cartItemCount = count($productCart); // Đếm số lượng sản phẩm
                                                }
                                                ?>
                                            <i class="iconsax pe-2" data-icon="basket-2"></i>
                                            <?= $cartItemCount ?> items
                                        </div>
                                    </a>
                                </li>
                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 0): ?>
                                <!-- Nút dành riêng cho role = 0 -->
                                <li class="onhover-div">
                                    <a href="?act=admin">Admin</a>
                                </li>
                                <?php endif; ?>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>