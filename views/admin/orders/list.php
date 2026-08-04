<div class="page-content">
    <div class="container-fluid mt-4">
        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success_message'];
            unset($_SESSION['success_message']); ?>
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error_message'];
            unset($_SESSION['error_message']); ?>
        </div>
        <?php endif; ?>

        <h2 class="mb-4">Danh sách đơn hàng</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Tổng tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['ten_kh'] ?></td>
                        <td><?= $order['ngay_dat'] ?></td>
                        <td>
                            <?php
                            $trang_thai = $order['trang_thai'];
                            switch ($trang_thai) {
                                case 0:
                                    echo '<span class="badge bg-secondary">Chờ xác nhận</span>';
                                    break;
                                case 1:
                                    echo '<span class="badge bg-info">Đang xử lý</span>';
                                    break;
                                case 2:
                                    echo '<span class="badge bg-warning">Đang giao hàng</span>';
                                    break;
                                case 4:
                                    echo '<span class="badge bg-success">Đã giao</span>';
                                    break;
                                case 5:
                                    echo '<span class="badge bg-primary">Hoàn thành</span>';
                                    break;
                                case 6:
                                    echo '<span class="badge bg-danger">Đã hủy</span>';
                                    break;
                                case 7:
                                    echo '<span class="badge bg-dark">Trả hàng/Hoàn tiền</span>';
                                    break;
                                default:
                                    echo '<span class="badge bg-secondary">Không xác định</span>';
                            }
                            ?>
                        </td>
                        <td><?= number_format($order['tong_tien'], 0, ',', '.') ?> VNĐ</td>
                        <td>
                            <a href="?act=orderDetail&id=<?= $order['id'] ?>" class="btn btn-info btn-sm">Xem chi
                                tiết</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>