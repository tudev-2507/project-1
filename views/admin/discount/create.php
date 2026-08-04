<div class="page-content">
    <div class="container-xxl">
        <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Khuyến Mãi Information</h4>
                    </div>
                    <div class="card-body">
                        <form action="?act=create_discount" method="POST">
                            <!-- Tên khuyến mãi -->
                            <div class="mb-3">
                                <label for="ten_km" class="form-label">Tên khuyến mãi</label>
                                <input type="text" id="ten_km" name="ten_km" class="form-control"
                                    placeholder="Ví dụ: Miễn phí vận chuyển" required>
                            </div>

                            <!-- Mã khuyến mãi -->
                            <div class="mb-3">
                                <label for="ma_km" class="form-label">Mã khuyến mãi</label>
                                <input type="text" id="ma_km" name="ma_km" class="form-control"
                                    placeholder="Ví dụ: FREESHIP04" required>
                            </div>

                            <!-- Loại khuyến mãi -->
                            <div class="mb-3">
                                <label for="loai_km" class="form-label">Loại khuyến mãi</label>
                                <select id="loai_km" name="loai_km" class="form-control" required>
                                    <option value="0">Giảm số tiền cố định</option>
                                    <option value="1">Giảm theo phần trăm đơn hàng</option>
                                </select>
                            </div>

                            <!-- Số tiền giảm -->
                            <div class="mb-3">
                                <label for="so_tien_giam" class="form-label">Giá trị giảm</label>
                                <input type="number" id="so_tien_giam" name="so_tien_giam" class="form-control"
                                    placeholder="Nhập số tiền hoặc phần trăm" value="0" min="0" required>
                            </div>

                            <!-- Đơn tối thiểu -->
                            <div class="mb-3">
                                <label for="don_toi_thieu" class="form-label">Đơn tối thiểu (VND)</label>
                                <input type="number" id="don_toi_thieu" name="don_toi_thieu" class="form-control"
                                    placeholder="Ví dụ: 500000" value="0" required>
                            </div>

                            <!-- Ngày bắt đầu -->
                            <div class="mb-3">
                                <label for="ngay_bat_dau" class="form-label">Ngày bắt đầu</label>
                                <input type="date" id="ngay_bat_dau" name="ngay_bat_dau" class="form-control" required>
                            </div>

                            <!-- Ngày kết thúc -->
                            <div class="mb-3">
                                <label for="ngay_ket_thuc" class="form-label">Ngày kết thúc</label>
                                <input type="date" id="ngay_ket_thuc" name="ngay_ket_thuc" class="form-control"
                                    required>
                            </div>

                            <!-- Trạng thái -->
                            <div class="mb-3">
                                <label for="trang_thai" class="form-label">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control" required>
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Không hoạt động</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="row justify-content-end g-2">
                                <div class="col-lg-2">
                                    <a href="?act=discount" class="btn btn-outline-secondary w-100">Hủy</a>
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-primary w-100">Tạo mã giảm giá</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
document.querySelector('form').addEventListener('submit', function (event) {
    const start = document.getElementById('ngay_bat_dau').value;
    const end = document.getElementById('ngay_ket_thuc').value;
    if (start && end && end < start) {
        event.preventDefault();
        alert('Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.');
    }
});
</script>
