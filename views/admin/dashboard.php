<!DOCTYPE html>
<div class="page-content">
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Dashboard Overview</h4>
                    <div class="page-title-right">
                        <div class="d-flex align-items-center">
                            <div class="dropdown d-inline-block me-2">
                                <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-calendar me-1"></i> 
                                    <span class="d-none d-sm-inline-block">Today</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards Row -->
        <div class="row">
            <!-- Orders Card -->
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase font-size-12 mb-1">Tổng số đơn hàng</h6>
                                <h3 class="mb-2 font-weight-bold"><?php echo number_format($dashboardData['totalOrders']); ?></h3>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-soft-success text-success me-2">
                                        <i class="ti ti-trending-up me-1"></i>
                                        <?php echo number_format($dashboardData['recentOrders']); ?>
                                    </span>
                                    <span class="text-muted font-size-13">trong 30 ngày qua</span>
                                </div>
                            </div>
                            <div class="avatar-md">
                                <span class="avatar-title bg-soft-primary rounded-circle">
                                    <i class="ti ti-shopping-cart text-primary font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customers Card -->
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase font-size-12 mb-1">Tổng số khách hàng</h6>
                                <h3 class="mb-2 font-weight-bold"><?php echo number_format($dashboardData['totalCustomers']); ?></h3>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-soft-info text-info">
                                        <i class="ti ti-users me-1"></i>
                                        Người dùng đã đăng ký
                                    </span>
                                </div>
                            </div>
                            <div class="avatar-md">
                                <span class="avatar-title bg-soft-info rounded-circle">
                                    <i class="ti ti-users text-info font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Card -->
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase font-size-12 mb-1">Tổng số sản phẩm</h6>
                                <h3 class="mb-2 font-weight-bold"><?php echo number_format($dashboardData['totalProducts']); ?></h3>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-soft-warning text-warning">
                                        <i class="ti ti-package me-1"></i>
                                        Sản phẩm đang bán
                                    </span>
                                </div>
                            </div>
                            <div class="avatar-md">
                                <span class="avatar-title bg-soft-warning rounded-circle">
                                    <i class="ti ti-package text-warning font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="text-muted text-uppercase font-size-12 mb-1">Tổng doanh thu</h6>
                                <h3 class="mb-2 font-weight-bold"><?php echo number_format($dashboardData['customRevenue']); ?>đ</h3>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-soft-success text-success me-2">
                                        <i class="ti ti-trending-up me-1"></i>
                                        <?php echo number_format($dashboardData['monthlyRevenue']); ?>đ
                                    </span>
                                    <span class="text-muted font-size-13">trong tháng này</span>
                                </div>
                            </div>
                            <div class="avatar-md">
                                <span class="avatar-title bg-soft-success rounded-circle">
                                    <i class="ti ti-report-money text-success font-size-24"></i>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Date Range Selector -->
                        <form method="get" action="?act=admin" class="mt-3">
                            <div class="row g-2">
                                <div class="col-sm-5">
                                    <input type="hidden" name="act" value="admin">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                        <input type="date" class="form-control form-control-sm" name="from_date" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                        <input type="date" class="form-control form-control-sm" name="to_date" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" name="doanhThu" class="btn btn-primary btn-sm w-100">
                                        <i class="ti ti-search me-1"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Tables Row -->
        <div class="row">
            <!-- Top Products Table -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="card-title mb-0">Top 5 sản phẩm bán chạy</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Hình ảnh</th>
                                        <th>Đã bán</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dashboardData['topProducts'] as $product): ?>
                                    <tr>
                                        <td>
                                            <a href="?act=product_edit&id=<?php echo $product['id']; ?>" class="text-body fw-semibold">
                                                <?php echo htmlspecialchars($product['ten_sp']); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <img src="./public/admin/assets_admin/images/product/<?php echo $product['hinh_anh']; ?>" 
                                                alt="product-img" class="rounded" height="40">
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-success text-success">
                                                <?php echo number_format($product['total_sales']); ?> đã bán
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Đang bán</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Status -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Trạng thái đơn hàng</h5>
                            <a href="?act=orders" class="btn btn-sm btn-primary">
                                <i class="ti ti-external-link me-1"></i> Xem tất cả
                            </a>
                        </div>
                        
                        <?php foreach ($dashboardData['pendingOrders'] as $status): ?>
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="avatar-sm me-3">
                                <span class="avatar-title bg-soft-primary rounded-circle">
                                    <i class="ti ti-package text-primary font-size-20"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="font-size-15 mb-1"><?php echo htmlspecialchars($status['ten_trang_thai']); ?></h6>
                                <p class="text-muted mb-0">
                                    <span class="text-success fw-bold"><?php echo number_format($status['so_luong_don_hang']); ?></span> đơn hàng
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Monthly Sales Chart -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Thống kê doanh thu theo tháng</h5>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary active" onclick="showMonthlyChart()">Biểu đồ cột</button>
                                <button type="button" class="btn btn-sm btn-primary" onclick="showMonthlyLineChart()">Đường</button>
                            </div>
                        </div>
                        <canvas id="monthlySalesChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Yearly Sales Chart -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Thống kê doanh thu theo năm</h5>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary active" onclick="showYearlyChart()">Biểu đồ cột</button>
                                <button type="button" class="btn btn-sm btn-primary" onclick="showYearlyLineChart()">Đường</button>
                            </div>
                        </div>
                        <canvas id="yearlySalesChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Required JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ doanh thu theo tháng
    var monthlyCtx = document.getElementById('monthlySalesChart').getContext('2d');
    var monthlyData = <?= json_encode($dashboardData['monthlyStats']) ?>;
    var yearlyData = <?= json_encode($dashboardData['yearlyStats'] ?? [
        ['year' => '2023', 'revenue' => 1500000000, 'orders' => 150],
        ['year' => '2024', 'revenue' => 2000000000, 'orders' => 200],
        ['year' => '2025', 'revenue' => 2500000000, 'orders' => 250],
    ]) ?>;

    var months = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7',
        'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
    ];

    // Định dạng tiền VND
    function formatVND(value) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(value);
    }

    // Cấu hình chung cho biểu đồ
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    borderDash: [2, 4],
                    drawBorder: false
                },
                ticks: {
                    callback: function(value) {
                        return formatVND(value);
                    }
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    };

    // Biểu đồ cột theo tháng
    let monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Doanh thu',
                data: monthlyData.map(item => item.revenue),
                backgroundColor: 'rgba(10, 207, 151, 0.2)',
                borderColor: '#0acf97',
                borderWidth: 1,
                borderRadius: 4,
                maxBarThickness: 32
            }]
        },
        options: commonOptions
    });

    // Biểu đồ theo năm
    var yearlyCtx = document.getElementById('yearlySalesChart').getContext('2d');
    let yearlyChart = new Chart(yearlyCtx, {
        type: 'bar',
        data: {
            labels: yearlyData.map(item => item.year),
            datasets: [{
                label: 'Doanh thu',
                data: yearlyData.map(item => item.revenue),
                backgroundColor: 'rgba(114, 124, 245, 0.2)',
                borderColor: '#727cf5',
                borderWidth: 1,
                borderRadius: 4,
                maxBarThickness: 48
            }]
        },
        options: commonOptions
    });

    // Hàm chuyển đổi biểu đồ tháng
    window.showMonthlyChart = function() {
        monthlyChart.destroy();
        monthlyChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Doanh thu',
                    data: monthlyData.map(item => item.revenue),
                    backgroundColor: 'rgba(10, 207, 151, 0.2)',
                    borderColor: '#0acf97',
                    borderWidth: 1,
                    borderRadius: 4,
                    maxBarThickness: 32
                }]
            },
            options: commonOptions
        });
    };

    window.showMonthlyLineChart = function() {
        monthlyChart.destroy();
        monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Doanh thu',
                    data: monthlyData.map(item => item.revenue),
                    borderColor: '#0acf97',
                    backgroundColor: 'rgba(10, 207, 151, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: commonOptions
        });
    };

    // Hàm chuyển đổi biểu đồ năm
    window.showYearlyChart = function() {
        yearlyChart.destroy();
        yearlyChart = new Chart(yearlyCtx, {
            type: 'bar',
            data: {
                labels: yearlyData.map(item => item.year),
                datasets: [{
                    label: 'Doanh thu',
                    data: yearlyData.map(item => item.revenue),
                    backgroundColor: 'rgba(114, 124, 245, 0.2)',
                    borderColor: '#727cf5',
                    borderWidth: 1,
                    borderRadius: 4,
                    maxBarThickness: 48
                }]
            },
            options: commonOptions
        });
    };

    window.showYearlyLineChart = function() {
        yearlyChart.destroy();
        yearlyChart = new Chart(yearlyCtx, {
            type: 'line',
            data: {
                labels: yearlyData.map(item => item.year),
                datasets: [{
                    label: 'Doanh thu',
                    data: yearlyData.map(item => item.revenue),
                    borderColor: '#727cf5',
                    backgroundColor: 'rgba(114, 124, 245, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: commonOptions
        });
    };
});
</script>