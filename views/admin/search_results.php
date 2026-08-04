<?php include_once './views/admin/layout/header.php'; ?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Kết quả tìm kiếm cho: "<?= htmlspecialchars($_GET['keyword']) ?>"</h4>
                        <div class="page-title-right">
                            <a href="index.php?act=admin" class="btn btn-primary">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Results -->
            <?php if (!empty($results['orders'])): ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Đơn hàng (<?= count($results['orders']) ?>)</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-nowrap align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Khách hàng</th>
                                            <th>Ngày đặt</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results['orders'] as $order): ?>
                                        <tr>
                                            <td><?= $order['id'] ?></td>
                                            <td><?= $order['ten_kh'] ?></td>
                                            <td><?= $order['ngay_dat'] ?></td>
                                            <td><?= number_format($order['tong_tien']) ?> VNĐ</td>
                                            <td>
                                                <?php
                                                $statusClass = '';
                                                $statusText = '';
                                                switch ($order['trang_thai']) {
                                                    case 0: 
                                                        $statusClass = 'bg-warning-subtle text-warning';
                                                        $statusText = 'Chờ xử lý';
                                                        break;
                                                    case 1: 
                                                        $statusClass = 'bg-info-subtle text-info';
                                                        $statusText = 'Đang xử lý';
                                                        break;
                                                    case 2: 
                                                        $statusClass = 'bg-primary-subtle text-primary';
                                                        $statusText = 'Đang giao';
                                                        break;
                                                    case 3: 
                                                        $statusClass = 'bg-success-subtle text-success';
                                                        $statusText = 'Đã giao';
                                                        break;
                                                    case 4: 
                                                        $statusClass = 'bg-danger-subtle text-danger';
                                                        $statusText = 'Đã hủy';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                            </td>
                                            <td>
                                                <a href="index.php?act=orderDetail&id=<?= $order['id'] ?>"
                                                    class="btn btn-sm btn-soft-info">
                                                    <iconify-icon icon="solar:magnifer-linear"
                                                        class="search-widget-icon"></iconify-icon>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Products Results -->
            <?php if (!empty($results['products'])): ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Sản phẩm (<?= count($results['products']) ?>)</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-nowrap align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Danh mục</th>
                                            <th>Số lượng</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results['products'] as $product): ?>
                                        <tr>
                                            <td><?= $product['id'] ?></td>
                                            <td>
                                                <img src="/Duan1-main/public/admin/assets_admin/images/product/<?= $product['hinh_anh'] ?>"
                                                    alt="<?= $product['ten_sp'] ?>" class="avatar-sm rounded">
                                            </td>
                                            <td><?= $product['ten_sp'] ?></td>
                                            <td><?= $product['ten_dm'] ?></td>
                                            <td><?= $product['so_luong'] ?></td>
                                            <td>
                                                <a href="?act=product_edit&id=<?= $product['id'] ?>"
                                                    class="btn btn-sm btn-soft-info">Sửa</a>
                                                <?php if (($product['so_luong_ban'] ?? 0) == 0): ?>
                                                <a href="?act=product_delete&id=<?= $product['id'] ?>"
                                                    class="btn btn-sm btn-soft-danger"
                                                    onclick="return confirm('Xóa sản phẩm chưa bán này?')">Xóa</a>
                                                <?php else: ?>
                                                <button class="btn btn-sm btn-soft-danger" disabled
                                                    title="Sản phẩm đã bán, không thể xóa">Đã bán</button>
                                                <?php endif; ?>
                                            </td>

                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Customers Results -->
            <?php if (!empty($results['customers'])): ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Khách hàng (<?= count($results['customers']) ?>)</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-nowrap align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên</th>
                                            <th>Email</th>
                                            <th>Số điện thoại</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results['customers'] as $customer): ?>
                                        <tr>
                                            <td><?= $customer['id'] ?></td>
                                            <td><?= $customer['name'] ?></td>
                                            <td><?= $customer['email'] ?></td>
                                            <td><?= $customer['so_dien_thoai'] ?></td>

                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (empty($results['orders']) && empty($results['products']) && empty($results['customers'])): ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-info" role="alert">
                        <i class="ri-information-line me-2 align-middle"></i>
                        Không tìm thấy kết quả nào phù hợp.
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once './views/admin/layout/footer.php'; ?>
