<div class="page-content">
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Chỉnh sửa tài khoản</h4>
                    </div>
                    <div class="card-body">
                        <form action="?act=customer_edit&id=<?= $customer['id']; ?>" method="POST"
                            enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    value="<?= $customer['name']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="<?= $customer['email']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="mat_khau" class="form-label">Mật khẩu (Để trống nếu không đổi)</label>
                                <input type="password" id="mat_khau" name="mat_khau" class="form-control"
                                    placeholder="Nhập mật khẩu mới">
                            </div>
                            <div class="mb-3">
                                <label for="dia_chi" class="form-label">Địa chỉ</label>
                                <input type="text" id="dia_chi" name="dia_chi" class="form-control"
                                    value="<?= $customer['dia_chi']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                                <input type="text" id="so_dien_thoai" name="so_dien_thoai" class="form-control"
                                    value="<?= $customer['so_dien_thoai']; ?>" pattern="[0-9]{10}" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Vai trò</label>
                                <select id="role" name="role" class="form-control" required>
                                    <option value="1" <?= $customer['role'] == 1 ? 'selected' : ''; ?>>Khách hàng
                                    </option>
                                    <option value="0" <?= $customer['role'] == 0 ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </div>
                            <div class="row justify-content-end g-2">
                                <div class="col-lg-2">
                                    <a href="?act=customer" class="btn btn-outline-secondary w-100">Hủy</a>
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>