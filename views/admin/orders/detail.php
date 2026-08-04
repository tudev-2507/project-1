<div class=" page-content">
    <div class="container-fluid mt-4">
        <h2>Chi tiết đơn hàng #<?= $order['id'] ?></h2>

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

        <div class="row">
            <div class="col-md-6">
                <h4>Thông tin khách hàng</h4>
                <p><strong>Tên khách hàng:</strong> <?= $order['ten_kh'] ?></p>
                <p><strong>Email:</strong> <?= $order['email'] ?></p>
                <p><strong>Số điện thoại:</strong> <?= $order['so_dien_thoai'] ?></p>
                <p><strong>Địa chỉ giao hàng:</strong> <?= $order['dia_chi'] ?></p>
                <p><strong>Ghi chú:</strong> <?= $order['ghi_chu'] ?: 'Không có' ?></p>
            </div>
            <div class="col-md-6">
                <h4>Thông tin đơn hàng</h4>
                <p><strong>Ngày đặt hàng:</strong> <?= $order['ngay_dat'] ?></p>
                <p><strong>Trạng thái hiện tại:</strong>
                    <?php
                switch ($order['trang_thai']) {
                    case 0:
                        echo 'Chờ xác nhận';
                        break;
                    case 1:
                        echo 'Đang xử lý';
                        break;
                    case 2:
                        echo 'Đang giao hàng';
                        break;
                    case 4:
                        echo 'Đã giao';
                        break;
                    case 5:
                        echo 'Hoàn thành';
                        break;
                    case 6:
                        echo 'Đã hủy';
                        break;
                    case 7:
                        echo 'Trả hàng/Hoàn tiền';
                        break;
                    default:
                        echo 'Không xác định';
                }
                ?>
                </p>
                <p><strong>Phương thức vận chuyển:</strong> <?= $order['ten_pt_van_chuyen'] ?></p>
                <p><strong>Phương thức thanh toán:</strong> <?= $order['ten_pt_thanh_toan'] ?></p>
                <p><strong>Mã khuyến mại:</strong> <?= $order['ma_km'] ?: 'Không có' ?></p>
                <p><strong>Tổng tiền:</strong> <?= number_format($order['tong_tien'], 0, ',', '.') ?> VNĐ</p>

                <form method="GET" action="index.php" class="mt-3">
                    <input type="hidden" name="act" value="updateOrderStatus">
                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                    <div class="mb-3">
                        <label for="status" class="form-label">Cập nhật trạng thái</label>
                        <select id="status" name="status" class="form-select">
                            <option value="0" <?= $order['trang_thai'] == 0 ? 'selected' : '' ?>>Chờ xác nhận</option>
                            <option value="1" <?= $order['trang_thai'] == 1 ? 'selected' : '' ?>>Đang xử lý</option>
                            <option value="2" <?= $order['trang_thai'] == 2 ? 'selected' : '' ?>>Đang giao hàng</option>
                            <option value="3" <?= $order['trang_thai'] == 4 ? 'selected' : '' ?>>Đã giao</option>
                            <option value="4" <?= $order['trang_thai'] == 5 ? 'selected' : '' ?>>Hoàn thành</option>
                            <option value="5" <?= $order['trang_thai'] == 6 ? 'selected' : '' ?>>Đã hủy</option>
                            <option value="6" <?= $order['trang_thai'] == 7 ? 'selected' : '' ?>>Trả hàng/Hoàn tiền
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật trạng thái</button>
                </form>
            </div>
        </div>

        <h4>Danh sách sản phẩm</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Màu sắc</th>
                    <th>Kích cỡ</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orderItems)): ?>
                <?php foreach ($orderItems as $item): ?>
                <tr>
                    <td><?= $item['ten_sp'] ?></td>
                    <td><img src="/Duan1-main/public/admin/assets_admin/images/product/<?= $item['hinh_anh'] ?>"
                            alt="<?= $item['ten_sp'] ?>" width="50"></td>
                    <td><?= $item['ten_mau'] ?></td>
                    <td><?= $item['ten_kich_co'] ?></td>
                    <td><?= $item['so_luong'] ?></td>
                    <td><?= number_format($item['don_gia'], 0, ',', '.') ?> VNĐ</td>
                    <td><?= number_format($item['so_luong'] * $item['don_gia'], 0, ',', '.') ?> VNĐ</td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7">Không có sản phẩm trong đơn hàng</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>