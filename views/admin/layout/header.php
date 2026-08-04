<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from techzaa.in/larkon/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 15 Mar 2025 17:42:50 GMT -->

<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title><?=$title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully responsive premium admin dashboard template" />
    <meta name="author" content="Techzaa" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <!-- <link rel="shortcut icon" href="/Duan1-main/public/admin/assets_admin/images/favicon.ico"> -->

    <!-- Vendor css (Require in all Page) -->
    <link href="/Duan1-main/public/admin/assets_admin/css/vendor.min.css" rel="stylesheet" type="text/css" />

    <!-- Icons css (Require in all Page) -->
    <link href="/Duan1-main/public/admin/assets_admin/css/icons.min.css" rel="stylesheet" type="text/css" />

    <!-- App css (Require in all Page) -->
    <link href="/Duan1-main/public/admin/assets_admin/css/app.min.css" rel="stylesheet" type="text/css" />

    <!-- Theme Config js (Require in all Page) -->
    <script src="/Duan1-main/public/admin/assets_admin/js/config.js"></script>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <!-- ========== Topbar Start ========== -->
        <header class="topbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="d-flex align-items-center">
                        <!-- Menu Toggle Button -->
                        <div class="topbar-item">
                            <button type="button" class="button-toggle-menu me-2">
                                <iconify-icon icon="solar:hamburger-menu-broken" class="fs-24 align-middle">
                                </iconify-icon>
                            </button>
                        </div>

                        <!-- Menu Toggle Button -->
                        <div class="topbar-item">
                            <h4 class="fw-bold topbar-button pe-none text-uppercase mb-0">Chào mừng đến với trang quản
                                trị!</h4>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-1">
                        <div class="dropdown topbar-item">
                            <a type="button" class="topbar-button" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center gap-2">

                                    <span class="text-nowrap">
                                        Xin chào:
                                        <?= isset($_SESSION['user']['name']) ? htmlspecialchars($_SESSION['user']['name']) : 'Khách' ?>
                                    </span>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="page-header-user-dropdown">
                                <a class="dropdown-item" href="user-info.php">
                                    <i class="fas fa-user me-2"></i> Thông tin
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="?act=logout">
                                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                </a>
                            </div>
                        </div>

                        <!-- App Search-->
                        <form class="app-search d-none d-md-block ms-2" action="index.php" method="GET">
                            <div class="position-relative">
                                <input type="hidden" name="act" value="search">
                                <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm..."
                                    autocomplete="off" required>
                                <iconify-icon icon="solar:magnifer-linear" class="search-widget-icon"></iconify-icon>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Activity Timeline -->
        <div>
            <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-activity-offcanvas"
                style="max-width: 450px; width: 100%;">
                <div class="d-flex align-items-center bg-primary p-3 offcanvas-header">
                    <h5 class="text-white m-0 fw-semibold">Activity Stream</h5>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body p-0">
                    <div data-simplebar class="h-100 p-4">
                        <div class="position-relative ms-2">
                            <span class="position-absolute start-0 border border-dashed h-100"></span>
                            <div class="position-relative ps-4">
                                <div class="mb-4">
                                    <span
                                        class="position-absolute start-0 avatar-sm translate-middle-x bg-danger d-inline-flex align-items-center justify-content-center rounded-circle text-light fs-20">
                                        <iconify-icon icon="iconamoon:folder-check-duotone"></iconify-icon>
                                    </span>
                                    <div class="ms-2">
                                        <h5 class="mb-1 text-dark fw-semibold fs-15 lh-base">Report-Fix / Update </h5>
                                        <p class="d-flex align-items-center">Add 3 files to <span
                                                class=" d-flex align-items-center text-primary ms-1">
                                                <iconify-icon icon="iconamoon:file-light"></iconify-icon> Tasks
                                            </span></p>
                                        <div class="bg-light bg-opacity-50 rounded-2 p-2">
                                            <div class="row">
                                                <div class="col-lg-6 border-end border-light">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="bx bxl-figma fs-20 text-red"></i>
                                                        <a href="#!" class="text-dark fw-medium">Concept.fig</a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="bx bxl-file-doc fs-20 text-success"></i>
                                                        <a href="#!" class="text-dark fw-medium">larkon.docs</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="mt-2 text-muted">Monday , 4:24 PM</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative ps-4">
                                <div class="mb-4">
                                    <span
                                        class="position-absolute start-0 avatar-sm translate-middle-x bg-success d-inline-flex align-items-center justify-content-center rounded-circle text-light fs-20">
                                        <iconify-icon icon="iconamoon:check-circle-1-duotone"></iconify-icon>
                                    </span>
                                    <div class="ms-2">
                                        <h5 class="mb-1 text-dark fw-semibold fs-15 lh-base">Project Status
                                        </h5>
                                        <p class="d-flex align-items-center mb-0">Marked<span
                                                class=" d-flex align-items-center text-primary mx-1">
                                                <iconify-icon icon="iconamoon:file-light"></iconify-icon> Design
                                            </span> as <span
                                                class="badge bg-success-subtle text-success px-2 py-1 ms-1">
                                                Completed</span></p>
                                        <div
                                            class="d-flex align-items-center gap-3 mt-1 bg-light bg-opacity-50 p-2 rounded-2">
                                            <a href="#!" class="fw-medium text-dark">UI/UX Figma Design</a>
                                            <div class="ms-auto">
                                                <a href="#!" class="fw-medium text-primary fs-18"
                                                    data-bs-toggle="tooltip" data-bs-title="Download"
                                                    data-bs-placement="bottom">
                                                    <iconify-icon icon="iconamoon:cloud-download-duotone">
                                                    </iconify-icon>
                                                </a>
                                            </div>
                                        </div>
                                        <h6 class="mt-3 text-muted">Monday , 3:00 PM</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative ps-4">
                                <div class="mb-4">
                                    <span
                                        class="position-absolute start-0 avatar-sm translate-middle-x bg-primary d-inline-flex align-items-center justify-content-center rounded-circle text-light fs-16">UI</span>
                                    <div class="ms-2">
                                        <h5 class="mb-1 text-dark fw-semibold fs-15">Larkon Application UI v2.0.0 <span
                                                class="badge bg-primary-subtle text-primary px-2 py-1 ms-1">
                                                Latest</span>
                                        </h5>
                                        <p>Get access to over 20+ pages including a dashboard layout, charts, kanban
                                            board, calendar, and pre-order E-commerce & Marketing pages.</p>
                                        <div class="mt-2">
                                            <a href="#!" class="btn btn-light btn-sm">Download Zip</a>
                                        </div>
                                        <h6 class="mt-3 text-muted">Monday , 2:10 PM</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative ps-4">
                                <div class="mb-4">
                                    <span
                                        class="position-absolute start-0 translate-middle-x bg-success bg-gradient d-inline-flex align-items-center justify-content-center rounded-circle text-light fs-20"><img
                                            src="/Duan1-main/public/admin/assets_admin/images/users/avatar-7.jpg"
                                            alt="avatar-5" class="avatar-sm rounded-circle"></span>
                                    <div class="ms-2">
                                        <h5 class="mb-0 text-dark fw-semibold fs-15 lh-base">Alex Smith Attached Photos
                                        </h5>
                                        <div class="row g-2 mt-2">
                                            <div class="col-lg-4">
                                                <a href="#!">
                                                    <img src="/Duan1-main/public/admin/assets_admin/images/small/img-6.jpg"
                                                        alt="" class="img-fluid rounded">
                                                </a>
                                            </div>
                                            <div class="col-lg-4">
                                                <a href="#!">
                                                    <img src="/Duan1-main/public/admin/assets_admin/images/small/img-3.jpg"
                                                        alt="" class="img-fluid rounded">
                                                </a>
                                            </div>
                                            <div class="col-lg-4">
                                                <a href="#!">
                                                    <img src="/Duan1-main/public/admin/assets_admin/images/small/img-4.jpg"
                                                        alt="" class="img-fluid rounded">
                                                </a>
                                            </div>
                                        </div>
                                        <h6 class="mt-3 text-muted">Monday 1:00 PM</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative ps-4">
                                <div class="mb-4">
                                    <span
                                        class="position-absolute start-0 translate-middle-x bg-success bg-gradient d-inline-flex align-items-center justify-content-center rounded-circle text-light fs-20"><img
                                            src="/Duan1-main/public/admin/assets_admin/images/users/avatar-6.jpg"
                                            alt="avatar-5" class="avatar-sm rounded-circle"></span>
                                    <div class="ms-2">
                                        <h5 class="mb-0 text-dark fw-semibold fs-15 lh-base">Rebecca J. added a new team
                                            member
                                        </h5>
                                        <p class="d-flex align-items-center gap-1">
                                            <iconify-icon icon="iconamoon:check-circle-1-duotone" class="text-success">
                                            </iconify-icon> Added a new member to Front Dashboard
                                        </p>
                                        <h6 class="mt-3 text-muted">Monday 10:00 AM</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative ps-4">
                                <div class="mb-4">
                                    <span
                                        class="position-absolute start-0 avatar-sm translate-middle-x bg-warning d-inline-flex align-items-center justify-content-center rounded-circle text-light fs-20">
                                        <iconify-icon icon="iconamoon:certificate-badge-duotone"></iconify-icon>
                                    </span>
                                    <div class="ms-2">
                                        <h5 class="mb-0 text-dark fw-semibold fs-15 lh-base">Achievements
                                        </h5>
                                        <p class="d-flex align-items-center gap-1 mt-1">Earned a <iconify-icon
                                                icon="iconamoon:certificate-badge-duotone" class="text-danger fs-20">
                                            </iconify-icon>" Best Product Award"</p>
                                        <h6 class="mt-3 text-muted">Monday 9:30 AM</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="#!" class="btn btn-outline-dark w-100">View All</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-nav">
            <!-- Sidebar Logo -->
            <div class="logo-box">
                <a href="?act=/" class="logo-light">
                    <h1 style="color: white; margin: 20px">ZENCA</h1>
                </a>
            </div>
            <div class="scrollbar" data-simplebar>
                <ul class="navbar-nav" id="navbar-nav">

                    <li class="menu-title">Quản trị</li>

                    <li class="nav-item">
                        <a class="nav-link" href="?act=admin">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon>
                            </span>
                            <hiển class="nav-text"> Bảng điều khiển</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarProducts">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:t-shirt-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Sản phẩm </span>
                        </a>
                        <div class="collapse" id="sidebarProducts">
                            <ul class="nav sub-navbar-nav">
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="?act=product">Danh sách sản
                                        phẩm</a>
                                </li>
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="?act=product_create">Thêm sản
                                        phẩm</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-arrow" href="#sidebarCategory" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarCategory">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:clipboard-list-bold-duotone">
                                </iconify-icon>
                            </span>
                            <span class="nav-text"> Danh mục </span>
                        </a>
                        <div class="collapse" id="sidebarCategory">
                            <ul class="nav sub-navbar-nav">
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="?act=category">Danh sách danh
                                        mục</a>
                                </li>
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="?act=category_create">Thêm danh
                                        mục</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-arrow" href="#sidebarOrders" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarOrders">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:bag-smile-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Đơn hàng </span>
                        </a>
                        <div class="collapse" id="sidebarOrders">
                            <ul class="nav sub-navbar-nav">
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="?act=orders">Danh sách đơn hàng</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-arrow" href="#sidebarDiscounts" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarDiscounts">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:t-shirt-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Mã giảm giá </span>
                        </a>
                        <div class="collapse" id="sidebarDiscounts">
                            <ul class="nav sub-navbar-nav">
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="?act=discount">Danh sách mã giảm
                                        giá</a>
                                </li>
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="?act=discount_create">Thêm mã giảm
                                        giá</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-arrow" href="#sidebarCustomers" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarCustomers">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text">Tài khoản </span>
                        </a>
                        <div class="collapse" id="sidebarCustomers">
                            <ul class="nav sub-navbar-nav">

                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="?act=customer">Danh sách tài khoản </a>
                                </li>
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="?act=customer_create">Thêm tài khoản </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-arrow" href="#sidebarContact" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarContact">
                            <span class="nav-icon">
                                <iconify-icon icon="mdi:phone-classic"></iconify-icon>

                            </span>
                            <span class="nav-text"> Liên hệ </span>
                        </a>
                        <div class="collapse" id="sidebarContact">
                            <ul class="nav sub-navbar-nav">
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="?act=contact_list">Danh sách liên hệ</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                </ul>
            </div>
        </div>