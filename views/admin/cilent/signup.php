<section class="section-b-space">
    <div class="custom-container container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Đăng ký tài khoản</h2>
                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                </div>
                <?php endif; ?>

                <?php
                 if (session_status() === PHP_SESSION_NONE) session_start();

                if (!empty($_SESSION['sign_up_errors'])): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($_SESSION['sign_up_errors'] as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['sign_up_errors']); ?>
                <?php endif; ?>


                <form action="?act=sign_up" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="mat_khau" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="mat_khau" name="mat_khau" required>
                    </div>
                    <div class="mb-3">
                        <label for="dia_chi" class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" id="dia_chi" name="dia_chi" required>
                    </div>
                    <div class="mb-3">
                        <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                </form>

                <div class="text-center mt-3">
                    <p>Đã có tài khoản? <a href="?act=login">Đăng nhập ngay</a></p>
                </div>
            </div>
        </div>
    </div>
</section>