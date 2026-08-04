<div class="page-content">
    <!-- Start Container Fluid -->
    <div class="container-fluid">
        <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Danh sách khuyến mãi</h4>

                        <a href="?act=discount_create" class="btn btn-sm btn-danger">
                            Thêm khuyến mãi
                        </a>
                    </div>

                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>Tên khuyến mãi</th>
                                        <th>Mã khuyến mãi</th>
                                        <th>Loại khuyến mãi</th>
                                        <th>Số tiền giảm</th>
                                        <th>Đơn tối thiểu</th>
                                        <th>Ngày bắt đầu</th>
                                        <th>Ngày kết thúc</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($discounts as $discount): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <p class="text-dark fw-medium fs-15 mb-0">
                                                    <?= htmlspecialchars($discount['ten_km']) ?></p>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($discount['ma_km']) ?></td>
                                        <td><?= $discount['loai_km'] == 0 ? 'Miễn phí vận chuyển' : 'Giảm giá tiền' ?>
                                        </td>
                                        <td><?= number_format($discount['so_tien_giam']) ?> VND</td>
                                        <td><?= number_format($discount['don_toi_thieu']) ?> VND</td>
                                        <td><?= $discount['ngay_bat_dau'] ?></td>
                                        <td><?= $discount['ngay_ket_thuc'] ?></td>
                                        <td><?= $discount['trang_thai'] == 1 ? 'Hoạt động' : 'Không hoạt động' ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="?act=edit_discount&id=<?= $discount['id'] ?>"
                                                    class="btn btn-soft-info btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18">
                                                    </iconify-icon>
                                                </a>
                                                <a href="?act=discount_delete&id=<?= $discount['id'] ?>"
                                                    class="btn btn-soft-danger btn-sm"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                        class="align-middle fs-18"></iconify-icon>
                                                </a>
                                            </div>
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
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm("Bạn có chắc chắn muốn xóa khuyến mãi này?")) {
        window.location.href = "?act=discount_delete&id=" + id;
    }
}
</script>