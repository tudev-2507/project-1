<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <h2 class="mb-4">Liên hệ với chúng tôi</h2>
            <div class="mb-4">
                <h5>Địa chỉ:</h5>
                <p>F303 DỰ ÁN 1</p>
            </div>
            <div class="mb-4">
                <h5>Số điện thoại:</h5>
                <p><a href="tel:0123456789" class="text-decoration-none">086 86 86 186</a></p>
            </div>
            <div class="mb-4">
                <h5>Email:</h5>
                <p><a href="HFood@gmail.com" class="text-decoration-none">NHOM1DUAN@GMAIL.COM</a></p>
            </div>
            <div class="mb-4">
                <h5>Giờ làm việc:</h5>
                <p>Thứ 2 - Chủ nhật: 10:00 - 24:00</p>
            </div>
            <div class="mb-4">
                <h5>Mạng xã hội:</h5>
                <div class="d-flex gap-3">
                    <a href="https://www.facebook.com/" class="text-decoration-none">
                        <i class="fab fa-facebook fa-2x"></i>
                    </a>
                    <a href="https://www.facebook.com/" class="text-decoration-none">
                        <i class="fab fa-instagram fa-2x"></i>
                    </a>
                    <a href="https://www.facebook.com/" class="text-decoration-none">
                        <i class="fab fa-twitter fa-2x"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="mb-4">Gửi tin nhắn cho chúng tôi</h2>
            <form action="?act=contact" method="POST">
                <?php
                        $old = $_SESSION['old_input'] ?? [];

                        if (isset($_SESSION['contact_success'])) {
                            echo "<div class='alert alert-success'>{$_SESSION['contact_success']}</div>";
                            unset($_SESSION['contact_success']);
                        }

                        if (isset($_SESSION['contact_errors'])) {
                            echo "<div class='alert alert-danger'><ul>";
                            foreach ($_SESSION['contact_errors'] as $err) {
                                echo "<li>" . htmlspecialchars($err) . "</li>";
                            }
                            echo "</ul></div>";
                            unset($_SESSION['contact_errors']);
                            unset($_SESSION['old_input']);
                        }
                        ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Họ và tên</label>
                    <input type="text" name="ten" class="form-control" id="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input type="tel" name="so_dien_thoai" class="form-control" id="phone">
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Chủ đề</label>
                    <input type="text" name="chu_de" class="form-control" id="subject">
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Nội dung tin nhắn</label>
                    <textarea class="form-control" name="noi_dung" id="message" rows="5" required></textarea>
                </div>
                <input type="submit" name="contact" class="btn btn-primary" value="Gửi tin nhắn"></input>
            </form>
        </div>
    </div>

    <div class="mt-5">
        <h2 class="mb-4">Bản đồ</h2>
        <div class="ratio ratio-16x9">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3369.902793207366!2d105.74468687471465!3d21.038134787457576!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313455e940879933%3A0xcf10b34e9f1a03df!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e1!3m2!1svi!2s!4v1743581814294!5m2!1svi!2s"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">