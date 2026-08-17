<section class="section-b-space pt-0">
    <div class="custom-container container">
        <div class="row g-4">
            <div class="col-xxl-9 col-xl-8">
                <div class="cart-table">
                    <div class="table-responsive theme-scrollbar">
                        <?php if (empty($productCart)): ?>
                        <div class="empty-cart text-center" style="padding: 50px 0;">
                            <i class="fas fa-cart-plus" style="font-size: 50px;"></i>
                            <h4 style="color: #333; margin-bottom: 15px;">Giỏ hàng của bạn đang trống!</h4>
                            <p style="color: #666; margin-bottom: 20px;">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua
                                sắm.</p>
                            <a href="?act=shop" class="btn btn-primary"
                                style="background-color: #007bff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px;">Tiếp
                                tục mua sắm</a>
                        </div>
                        <?php else: ?>
                        <table class="table" id="cart-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $tongTien = 0;
                                    foreach ($productCart as $prod):
                                        $phiVC = 30000;
                                        $thanhTien = $prod['don_gia'] * $prod['so_luong'];
                                        $tongTien += $thanhTien;
                                    ?>
                                <tr>
                                    <td>
                                        <div class="cart-box">
                                            <a href="product.html">
                                                <img src="/Duan1-main/public/admin/assets_admin/images/product/<?= htmlspecialchars($prod['hinh_anh']) ?>"
                                                    alt="" style="width: 50px; height: 50px; object-fit: cover;">
                                            </a>
                                            <div>
                                                <a href="product.html">
                                                    <h5><?= htmlspecialchars($prod['ten_sp']) ?></h5>
                                                </a>
                                                <p>Màu: <span><?= htmlspecialchars($prod['ten_mau']) ?></span>, Kích cỡ:
                                                    <span><?= htmlspecialchars($prod['ten_kich_co']) ?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= number_format($prod['don_gia']) . ' VND'; ?></td>
                                    <td>
                                        <div class="quantity">
                                            <button class="minus" type="button"><i
                                                    class="fa-solid fa-minus"></i></button>
                                            <input type="number" name="so_luong" value="<?= $prod['so_luong'] ?>"
                                                min="1" max="<?= $prod['so_luong_ton'] ?>" readonly>
                                            <input type="hidden" name="id_ct_sp" value="<?= $prod['id_ct_sp'] ?>">
                                            <button class="plus" type="button"><i class="fa-solid fa-plus"></i></button>
                                        </div>
                                    </td>
                                    <td><?= number_format($thanhTien) . ' VND'; ?></td>
                                    <td>
                                        <a class="deleteButton" href="?act=cart_home&delete=<?= $prod['id_ct_sp'] ?>"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')">
                                            <i class="iconsax" data-icon="trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($productCart)): ?>
            <div class="col-xxl-3 col-xl-4">
                <div class="cart-items">
                    <div class="cart-body">
                        <h6>Chi tiết đơn giá</h6>
                        <ul>
                            <li>
                                <p>Tổng</p><span id="subtotal"><?= number_format($tongTien) ?></span>
                            </li>
                            <li>
                                <p>Phiếu giảm giá</p><span id="discountAmount" class="Coupon">0</span>
                            </li>
                            <li>
                                <p>Vận chuyển</p><span id="shipping"><?= number_format($phiVC) ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="cart-bottom">
                        <h6>Thanh toán <span id="finalTotal"><?= number_format($tongTien + $phiVC) ?> VND</span></h6>
                    </div>
                    <form action="?act=get_discount" method="post">
                        <div class="coupon-box">
                            <h6>Mã khuyến mãi</h6>
                            <ul>
                                <li>
                                    <select id="discount" name="discount" class="form-control" onchange="updateTotal()">
                                        <option value="">Chọn mã giảm giá</option>
                                        <?php if (!empty($availableDiscounts)): ?>
                                        <?php foreach ($availableDiscounts as $discount): ?>
                                        <option value="<?php echo $discount['id']; ?>"
                                            data-amount="<?php echo $discount['so_tien_giam']; ?>"
                                            data-type="<?php echo $discount['loai_km']; ?>">
                                            <?php echo htmlspecialchars($discount['ma_km'] . ' - ' . $discount['ten_km'] . ' (Giảm ' . ($discount['loai_km'] == 0 ? 'phí vận chuyển' : number_format($discount['so_tien_giam']) . ' VNĐ')); ?>
                                        </option>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <option value="" disabled>Không có mã giảm giá hợp lệ</option>
                                        <?php endif; ?>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <input name="discountSubmit" type="submit" value="Đặt hàng"
                            class="btn btn_black w-100 rounded sm"></input>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!empty($productCart)): ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Gọi updateTotal() khi trang tải
    updateTotal();

    // Khi nhấn nút tăng
    $('.plus').click(function() {
        let $input = $(this).siblings('input[name="so_luong"]');
        let currentVal = parseInt($input.val());
        if (currentVal < parseInt($input.attr('max'))) {
            $input.val(currentVal + 1);
            updateRow($(this).closest('tr'));
            updateCart($input);
        }
    });

    // Khi nhấn nút giảm
    $('.minus').click(function() {
        let $input = $(this).siblings('input[name="so_luong"]');
        let currentVal = parseInt($input.val());
        if (currentVal > parseInt($input.attr('min'))) {
            $input.val(currentVal - 1);
            updateRow($(this).closest('tr'));
            updateCart($input);
        }
    });

    // Hàm cập nhật số lượng giỏ hàng qua AJAX
    function updateCart($input) {
        let id_ct_sp = $input.siblings('input[name="id_ct_sp"]').val();
        let so_luong = $input.val();

        $.ajax({
            url: '?act=update_cart_quantity',
            method: 'POST',
            data: {
                id_ct_sp: id_ct_sp,
                so_luong: so_luong
            },
            success: function(response) {
                let result = JSON.parse(response);
                if (result.success) {
                    console.log('Cập nhật số lượng thành công');
                } else {
                    alert('Có lỗi xảy ra khi cập nhật số lượng!');
                }
            },
            error: function() {
                alert('Lỗi kết nối server!');
            }
        });
    }

    // Hàm cập nhật thành tiền & tổng
    function updateRow($row) {
        let unitPriceText = $row.find('td:eq(1)').text().replace(/[^0-9]/g, '');
        let unitPrice = parseInt(unitPriceText);
        let quantity = parseInt($row.find('input[name="so_luong"]').val());

        let newTotal = unitPrice * quantity;
        $row.find('td:eq(3)').text(newTotal.toLocaleString('vi-VN') + ' VND');

        // Cập nhật tổng tiền
        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        $('#cart-table tbody tr').each(function() {
            let priceText = $(this).find('td:eq(3)').text().replace(/[^0-9]/g, '');
            let price = parseInt(priceText);
            if (!isNaN(price)) {
                total += price;
            }
        });

        let shipping = 30000;
        let discount = 0;

        // Lấy thông tin mã giảm giá được chọn
        const discountSelect = document.getElementById('discount');
        const selectedOption = discountSelect.options[discountSelect.selectedIndex];
        const discountAmount = selectedOption ? parseFloat(selectedOption.getAttribute('data-amount')) : 0;
        const discountType = selectedOption ? parseInt(selectedOption.getAttribute('data-type')) : null;

        // Áp dụng mã giảm giá
        if (discountType === 1) {
            // Giảm giá theo số tiền cố định
            discount = discountAmount;
        } else if (discountType === 0) {
            // Miễn phí vận chuyển
            shipping = 0;
        }

        let finalTotal = total + shipping - discount;

        // Cập nhật giao diện
        $('#subtotal').text(total.toLocaleString('vi-VN') + ' VND');
        $('#shipping').text(shipping.toLocaleString('vi-VN') + ' VND');
        $('#discountAmount').text(discount.toLocaleString('vi-VN') + ' VND');
        $('#finalTotal').text(finalTotal.toLocaleString('vi-VN') + ' VND');
    }
});
</script>
<?php endif; ?>