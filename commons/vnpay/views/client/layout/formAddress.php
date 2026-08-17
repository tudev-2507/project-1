<section class="section-b-space pt-0">
    <div class="custom-container container">
        <form action="?act=form_Address" method="POST">
            <?php if (!empty($_SESSION['payment_error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($_SESSION['payment_error'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php unset($_SESSION['payment_error']); ?>
            <?php endif; ?>
            <div class="row">
                <div class="col-xxl-9 col-lg-8">
                    <div class="left-sidebar-checkout sticky">
                        <div class="address-option">
                            <div class="address-title">
                                <h4>Địa chỉ giao hàng </h4><a href="#" data-bs-toggle="modal"
                                    data-bs-target="#address-modal" title="add product" tabindex="0"><i
                                        class="bi bi-pencil"></i> Địa Chỉ khác</a>
                            </div>

                            <div class="row">
                                <?php if (!empty($userInfo)): ?>
                                <div class="col-xxl-4">
                                    <label for="address-billing-0">
                                        <span class="delivery-address-box">
                                            <span class="form-check">
                                                <input class="custom-radio" id="address-billing-0" type="radio"
                                                    checked="checked" name="shipping_address"
                                                    value="<?= htmlspecialchars($userInfo['name'] . ', ' . $userInfo['dia_chi'] . ', ' . $userInfo['so_dien_thoai']) ?>">
                                            </span>
                                            <span class="address-detail">
                                                <span class="address"><span
                                                        class="address-title"><?= htmlspecialchars($userInfo['name']) ?></span></span>
                                                <span class="address"><span class="address-home"><span
                                                            class="address-tag">Địa
                                                            chỉ:</span><?= htmlspecialchars($userInfo['dia_chi']) ?></span></span>
                                                <span class="address"><span class="address-home"><span
                                                            class="address-tag">Số điện thoại
                                                            :</span><?= htmlspecialchars($userInfo['so_dien_thoai']) ?></span></span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <?php else: ?>
                                <div class="col-xxl-4">
                                    <p>Không có thông tin địa chỉ. Vui lòng thêm địa chỉ mới.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Phương thức vận chuyển -->

                        <div class="address-option">
                            <h4 class="mb-3">Phương thức giao hàng </h4>
                            <div class="row gy-3">
                                <?php foreach ($shippingMethods as $index => $method): ?>
                                <div class="col-sm-6">
                                    <div class="payment-box">
                                        <input class="custom-radio me-2 shipping-method"
                                            id="shipping-<?= $method['id'] ?>" type="radio" name="shipping_method"
                                            value="<?= $method['id'] ?>" data-gia="<?= $method['gia'] ?>"
                                            <?= $index === 0 ? 'checked="checked"' : '' ?>>

                                        <label for="shipping-<?= (int) $method['id'] ?>"><?= htmlspecialchars($method['phuong_thuc'], ENT_QUOTES, 'UTF-8') ?></label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>


                        <!-- Phương thức thanh toán -->
                        <div class="address-option">
                            <h4 class="mb-3">Phương thức thanh toán </h4>
                            <div class="row gy-3">
                                <?php foreach ($paymentMethods as $index => $method): ?>
                                <div class="col-sm-6">
                                    <div class="payment-box">
                                        <input class="custom-radio me-2" id="payment-<?= (int) $method['id'] ?>" type="radio"
                                            name="payment_method" value="<?= (int) $method['id'] ?>"
                                            <?= $index === 0 ? 'checked="checked"' : '' ?>>
                                        <label for="payment-<?= (int) $method['id'] ?>"><?= htmlspecialchars($method['name'], ENT_QUOTES, 'UTF-8') ?></label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4">
                    <div class="right-sidebar-checkout">
                        <h4>Checkout</h4>
                        <div class="cart-listing">
                            <ul>
                                <?php foreach ($productCart as $item): ?>
                                <li>
                                    <img width="50"
                                        src="/Duan1-main/public/admin/assets_admin/images/product/<?= $item['hinh_anh'] ?>"
                                        alt="">
                                    <div>
                                        <h6><?= $item['ten_sp'] ?></h6>
                                        <span>Màu: <?= $item['ten_mau'] ?>, Kích cỡ: <?= $item['ten_kich_co'] ?></span>
                                    </div>
                                    <p><?= number_format($item['don_gia'] * $item['so_luong']) ?> VND</p>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="summary-total">
                                <ul>
                                    <li>
                                        <p>Tổng tiền </p><span><?= number_format($subtotal) ?> VND</span>
                                    </li>
                                    <li>
                                        <p>Phí vận chuyển </p><span><?= number_format($shipping) ?> VND</span>
                                    </li>
                                    <li>
                                        <p>Thế </p><span><?= number_format($tax) ?> VND</span>
                                    </li>
                                    <li>
                                        <p>Phiếu giảm giá </p><span>-<?= number_format($discountAmount) ?> VND</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="total">
                                <h6>Thành tiền : </h6>
                                <h6><?= number_format($total) ?> VND</h6>
                            </div>
                            <div class="order-button">
                                <input type="submit" name="checkOut" id="checkOut"
                                    class="btn btn_black sm w-100 rounded" value="Thanh toán">
                                </input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Modal Thêm Địa Chỉ -->
<div class="modal fade" id="address-modal" tabindex="-1" aria-labelledby="address-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="?act=update_Address" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="address-modalLabel">Địa Chỉ khác </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" name="ten" id="name" required>
                </div>
                <div class="mb-3">
                    <label for="dia_chi" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" name="dia_chi" id="dia_chi" required>
                </div>
                <div class="mb-3">
                    <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" name="so_dien_thoai" id="so_dien_thoai" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <input type="submit" name="submit" value="Lưu Địa Chỉ" class="btn btn-primary"></input>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const shippingRadios = document.querySelectorAll('input[name="shipping_method"]');
    const shippingDisplay = document.querySelector('.summary-total li:nth-child(2) span');
    const totalDisplay = document.querySelector('.total h6:nth-child(2)');
    const subtotal = <?= $subtotal ?>;
    const tax = <?= $tax ?>;
    const discount = <?= $discountAmount ?>; // ← Thêm dòng này

    shippingRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const shipping = parseInt(this.dataset.gia);

            // Cập nhật phần hiển thị phí vận chuyển
            shippingDisplay.textContent = shipping.toLocaleString('vi-VN') + ' VND';

            // Cập nhật lại tổng cộng
            const newTotal = subtotal + shipping + tax - discount;
            totalDisplay.textContent = newTotal.toLocaleString('vi-VN') + ' VND';

        });
    });
});
</script>
