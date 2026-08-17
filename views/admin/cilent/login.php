<section class="section-b-space pt-0 login-bg-img">
    <div class="custom-container container login-page">
        <div class="row align-items-center">
            <div class="col-xxl-7 col-6 d-none d-lg-block">
                <div class="login-img">
                    <img class="img-fluid" src="https://themes.pixelstrap.net/katie/assets/images/login/1.svg" alt="">
                </div>
            </div>
            <div class="col-xxl-4 col-lg-6 mx-auto">
                <div class="log-in-box">
                    <div class="log-in-title">
                        <h4>Đăng nhập</h4>
                    </div>
                    <!-- Hiển thị thông báo lỗi nếu có -->
                    <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    </div>
                    <?php endif; ?>
                    <div class="login-box">
                        <form class="row g-3" action="?act=login" method="POST">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input class="form-control" id="email" name="email" type="email"
                                        placeholder="name@example.com" required>
                                    <label for="email">Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input class="form-control" id="mat_khau" name="mat_khau" type="password"
                                        placeholder="Mật khẩu" value="" required>
                                    <label for="mat_khau">Mật khẩu</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="forgot-box">
                                    <div>
                                        <input id="category1" type="checkbox" name="remember">
                                        <label for="category1">Ghi nhớ mật khẩu</label>
                                    </div>
                                    <a href="forget-password.html">Quên mật khẩu?</a>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn login btn_black sm" type="submit">Đăng nhập</button>
                            </div>
                        </form>
                    </div>
                    <div class="sign-up-box">
                        <p>Bạn chưa có tài khoản?</p><a href="?act=sign_up">Đăng ký</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>