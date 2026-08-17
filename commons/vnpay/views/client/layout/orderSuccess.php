<section class="section-b-space py-0">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 px-0">
                <div class="order-box-1">
                    <img src="/Duan1-main/public/client/assets/images/gif/success.gif" alt="">
                    <h4>Đặt hàng thành công</h4>
                    <p>Thanh toán đã được xử lý thành công và đơn hàng của bạn đang trên đường giao!</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space">
    <div class="custom-container container order-success">
        <div class="row gy-4">
            <div class="col-xl-8">
                <div class="order-items sticky">
                    <h4>Thông tin đơn hàng</h4>
                    <div class="order-table">
                        <div class="table-responsive theme-scrollbar">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng cộng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderItems as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="cart-box">
                                                <a href="product.html">
                                                    <img src="/Duan1-main/public/admin/assets_admin/images/product/<?= htmlspecialchars($item['hinh_anh']) ?>"
                                                        alt="">
                                                </a>
                                                <div>
                                                    <a href="product.html">
                                                        <h5><?= htmlspecialchars($item['ten_sp']) ?></h5>
                                                    </a>
                                                    <p>Được bán bởi: <span>Zenca</span></p>
                                                    <p>Kích cỡ:
                                                        <span><?= htmlspecialchars($item['ten_kich_co']) ?></span>
                                                    </p>
                                                    <p>Màu sắc: <span><?= htmlspecialchars($item['ten_mau']) ?></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= number_format($item['don_gia']) ?> VND</td>
                                        <td><?= $item['so_luong'] ?></td>
                                        <td><?= number_format($item['thanh_tien']) ?> VND</td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="total fw-bold">Tổng cộng:</td>
                                        <td class="total fw-bold"><?= number_format($subtotal) ?> VND</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="summery-box">
                    <div class="sidebar-title">
                        <div class="loader-line"></div>
                        <h4>Chi tiết đơn hàng</h4>
                    </div>
                    <div class="summery-content">
                        <ul>
                            <li>
                                <p class="fw-semibold">Tổng sản phẩm (<?= count($orderItems) ?>)</p>
                                <h6><?= number_format($subtotal) ?> VND</h6>
                            </li>
                            <li>
                                <p>Giao đến</p>
                                <span><?= htmlspecialchars($order['dia_chi']) ?></span>
                            </li>
                        </ul>
                        <ul>
                            <li>
                                <p>Phí vận chuyển</p>
                                <span><?= number_format($orderItems['0']['gia_van_chuyen']) ?> VND</span>
                            </li>
                            <li>
                                <p>Tổng trước thuế</p>
                                <span><?= number_format($subtotal) ?> VND</span>
                            </li>
                            <li>
                                <p>Thuế VAT 2%</p>
                                <span><?= number_format($tax) ?> VND</span>
                            </li>
                            <li>
                                <p>Mã giảm giá</p>
                                <span>-<?= number_format($discount) ?> VND</span>
                            </li>
                        </ul>
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Tổng cộng (VND)</h6>
                            <h5><?= number_format($order['tong_tien']) ?> VND</h5>
                        </div>
                        <div class="note-box">
                            <p><?= $order['ghi_chu'] ? htmlspecialchars($order['ghi_chu']) : 'Không có ghi chú.' ?></p>
                        </div>
                    </div>
                </div>
                <div class="summery-footer">
                    <div class="sidebar-title">
                        <div class="loader-line"></div>
                        <h4>Địa chỉ giao hàng</h4>
                    </div>
                    <ul>
                        <li>
                            <?php $dia_chi  = explode(',', $order['dia_chi']);?>
                            <h6><?= htmlspecialchars($dia_chi['0']) ?></h6>
                            <h6>Địa chỉ:<?= htmlspecialchars($dia_chi['1']) ?></h6>
                            <h6>Số điện thoại: <?= htmlspecialchars($order['so_dien_thoai']) ?></h6>
                        </li>
                        <li>
                            <h6>Ngày giao hàng dự kiến: </h6>
                        </li>
                        <li>
                            <h5><?= date('d/m/Y', strtotime($order['ngay_dat'] . ' + 5 days')) ?></h5>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>