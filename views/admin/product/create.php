<div class="page-content">
    <div class="container-xxl">
        <!-- Hiển thị thông báo lỗi nếu có -->
        <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
        </div>
        <?php endif; ?>

        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <form action="?act=product_attribute_create" method="POST" class="input-group">
                    <input type="hidden" name="attribute_type" value="color">
                    <input type="hidden" name="return_act" value="product_create">
                    <input type="text" name="attribute_name" class="form-control" placeholder="Thêm màu mới" required>
                    <button class="btn btn-outline-primary" type="submit">Thêm màu</button>
                </form>
            </div>
            <div class="col-md-6">
                <form action="?act=product_attribute_create" method="POST" class="input-group">
                    <input type="hidden" name="attribute_type" value="size">
                    <input type="hidden" name="return_act" value="product_create">
                    <input type="text" name="attribute_name" class="form-control" placeholder="Thêm kích thước mới" required>
                    <button class="btn btn-outline-primary" type="submit">Thêm kích thước</button>
                </form>
            </div>
        </div>

        <form action="?act=product_create" method="POST" enctype="multipart/form-data">
            <div class="row">
                <!-- Phần bên trái: Hiển thị bản xem trước của sản phẩm -->
                <div class="col-xl-3 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <div id="image-preview" class="mb-3">
                                <img src="/Duan1-main/public/admin/assets_admin/images/product/default-product.png"
                                    alt="Ảnh sản phẩm" class="img-fluid rounded shadow-sm" id="main-image-preview"
                                    style="max-height: 200px; object-fit: cover;">
                            </div>
                            <div class="mt-3">
                                <h4 id="product-name-preview" class="text-primary">Tên Sản Phẩm</h4>
                                <h5 class="text-dark fw-medium mt-3">Giá:</h5>
                                <h4
                                    class="fw-semibold text-dark mt-2 d-flex align-items-center justify-content-center gap-2">
                                    <span id="product-price-preview">0</span> VNĐ
                                </h4>
                            </div>
                        </div>
                        <div class="card-footer bg-light-subtle">
                            <div class="row g-2">
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-primary w-100">Tạo Sản Phẩm</button>
                                </div>
                                <div class="col-lg-6">
                                    <a href="?act=product" class="btn btn-outline-secondary w-100">Hủy</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title mb-0">Hình Ảnh Sản Phẩm</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="main-image" class="form-label fw-bold">Hình Ảnh Chính</label>
                                <input type="file" name="main_image" id="main-image" class="form-control"
                                    accept="image/*" required>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title mb-0">Thông Tin Sản Phẩm</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ten_sp" class="form-label fw-bold">Tên Sản Phẩm</label>
                                        <input type="text" id="ten_sp" name="ten_sp" class="form-control"
                                            placeholder="Nhập tên sản phẩm" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="id_dm" class="form-label fw-bold">Danh Mục</label>
                                        <select class="form-control" id="id_dm" name="id_dm" required>
                                            <option value="">Chọn danh mục</option>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>"><?= $category['ten_dm'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="mo_ta" class="form-label fw-bold">Mô Tả</label>
                                        <textarea class="form-control" id="mo_ta" name="mo_ta" rows="5"
                                            placeholder="Nhập mô tả sản phẩm"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="so_luong" class="form-label fw-bold">Tổng Số Lượng</label>
                                        <input type="number" id="so_luong" name="so_luong" class="form-control"
                                            placeholder="Tổng số lượng (tự động tính)" min="0" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div
                            class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Biến Thể Sản Phẩm</h4>
                            <button type="button" class="btn btn-light btn-sm" id="add-variation">
                                <i class="fas fa-plus"></i> Thêm Biến Thể
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="variations-container">
                                <div class="variation-item border rounded p-3 mb-3 shadow-sm">
                                    <div class="row align-items-end">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Màu Sắc</label>
                                                <select class="form-control variation-color"
                                                    name="variations[0][id_mau]" required>
                                                    <option value="">Chọn màu</option>
                                                    <?php foreach ($colors as $color): ?>
                                                    <option value="<?= $color['id'] ?>"><?= $color['ten_mau'] ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Kích Thước</label>
                                                <select class="form-control variation-size"
                                                    name="variations[0][id_kich_co]" required>
                                                    <option value="">Chọn kích thước</option>
                                                    <?php foreach ($sizes as $size): ?>
                                                    <option value="<?= $size['id'] ?>"><?= $size['ten_kich_co'] ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Số Lượng Biến Thể</label>
                                                <input type="number" class="form-control variation-quantity"
                                                    name="variations[0][so_luong]" min="0" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Giá</label>
                                                <input type="number" class="form-control price-input"
                                                    name="variations[0][don_gia]" min="0" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Giá Khuyến Mãi</label>
                                                <input type="number" class="form-control" name="variations[0][gia_km]"
                                                    min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button"
                                                class="btn btn-danger btn-sm remove-variation">Xóa</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-light mb-3 rounded shadow-sm">
                        <div class="row justify-content-end g-2">
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary w-100">Tạo Sản Phẩm</button>
                            </div>
                            <div class="col-lg-2">
                                <a href="?act=product" class="btn btn-outline-secondary w-100">Hủy</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Phần HTML giữ nguyên, chỉ cập nhật phần JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xem trước hình ảnh chính
    document.getElementById('main-image').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('main-image-preview').src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Cập nhật tên sản phẩm xem trước
    document.getElementById('ten_sp').addEventListener('input', function() {
        document.getElementById('product-name-preview').textContent = this.value || "Tên Sản Phẩm";
    });

    // Cập nhật giá sản phẩm xem trước
    function updatePricePreview() {
        document.querySelectorAll('.price-input').forEach(function(input) {
            input.addEventListener('input', function() {
                document.getElementById('product-price-preview').textContent = this.value ||
                    "0";
            });
        });
    }
    updatePricePreview();

    // Cập nhật tổng số lượng từ các biến thể
    function updateTotalQuantity() {
        let total = 0;
        document.querySelectorAll('.variation-quantity').forEach(function(input) {
            total += parseInt(input.value) || 0;
        });
        document.getElementById('so_luong').value = total;
    }

    // Kiểm tra biến thể trùng lặp
    function checkDuplicateVariations() {
        let variations = document.querySelectorAll('.variation-item');
        let combinations = [];
        let isValid = true;

        variations.forEach(function(variation, index) {
            let color = variation.querySelector('.variation-color').value;
            let size = variation.querySelector('.variation-size').value;

            if (color && size) {
                let combination = color + '-' + size;
                if (combinations.includes(combination)) {
                    isValid = false;
                    alert('Biến thể màu ' + variation.querySelector('.variation-color option:checked')
                        .text +
                        ' và kích thước ' + variation.querySelector(
                            '.variation-size option:checked').text +
                        ' đã tồn tại!');
                    variation.querySelector('.variation-color').focus();
                } else {
                    combinations.push(combination);
                }
            }
        });

        return isValid;
    }

    // Kiểm tra giá khuyến mãi thấp hơn giá bán
    function checkPriceValidity() {
        let isValid = true;
        document.querySelectorAll('.variation-item').forEach(function(variation, index) {
            let priceInput = variation.querySelector('.price-input');
            let promoPriceInput = variation.querySelector('input[name*="gia_km"]');
            let price = parseFloat(priceInput.value) || 0;
            let promoPrice = parseFloat(promoPriceInput.value) || 0;

            if (promoPrice < 0) {
                isValid = false;
                alert('Giá khuyến mãi của biến thể ' + (index + 1) + ' không được nhỏ hơn 0!');
                promoPriceInput.focus();
                promoPriceInput.classList.add('is-invalid');
            } else if (promoPrice > 0 && promoPrice >= price) {
                isValid = false;
                alert('Giá khuyến mãi của biến thể ' + (index + 1) + ' phải nhỏ hơn giá bán (' + price +
                    ' VNĐ)!');
                promoPriceInput.focus();
                promoPriceInput.classList.add('is-invalid');
            } else {
                promoPriceInput.classList.remove('is-invalid');
            }
        });
        return isValid;
    }

    // Gắn sự kiện cho các input số lượng biến thể
    document.querySelectorAll('.variation-quantity').forEach(function(input) {
        input.addEventListener('input', updateTotalQuantity);
    });

    // Gắn sự kiện validate khi thay đổi màu sắc, kích thước, giá, hoặc giá khuyến mãi
    function attachValidationEvents() {
        document.querySelectorAll('.variation-color, .variation-size').forEach(function(select) {
            select.addEventListener('change', checkDuplicateVariations);
        });
        document.querySelectorAll('.price-input, input[name*="gia_km"]').forEach(function(input) {
            input.addEventListener('input', checkPriceValidity);
        });
    }
    attachValidationEvents();

    // Thêm biến thể
    let variationIndex = 1;
    document.getElementById('add-variation').addEventListener('click', function() {
        if (!checkDuplicateVariations() || !checkPriceValidity()) {
            return;
        }

        const container = document.getElementById('variations-container');
        const newVariation = document.createElement('div');
        newVariation.className = 'variation-item border rounded p-3 mb-3 shadow-sm';
        newVariation.innerHTML = `
            <div class="row align-items-end">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Màu Sắc</label>
                        <select class="form-control variation-color" name="variations[${variationIndex}][id_mau]" required>
                            <option value="">Chọn màu</option>
                            <?php foreach ($colors as $color): ?>
                            <option value="<?= $color['id'] ?>"><?= $color['ten_mau'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kích Thước</label>
                        <select class="form-control variation-size" name="variations[${variationIndex}][id_kich_co]" required>
                            <option value="">Chọn kích thước</option>
                            <?php foreach ($sizes as $size): ?>
                            <option value="<?= $size['id'] ?>"><?= $size['ten_kich_co'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Số Lượng Biến Thể</label>
                        <input type="number" class="form-control variation-quantity" 
                            name="variations[${variationIndex}][so_luong]" min="0" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Giá</label>
                        <input type="number" class="form-control price-input" 
                            name="variations[${variationIndex}][don_gia]" min="0" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Giá Khuyến Mãi</label>
                        <input type="number" class="form-control promo-price" 
                            name="variations[${variationIndex}][gia_km]" min="0">
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-variation">Xóa</button>
                </div>
            </div>
        `;
        container.appendChild(newVariation);

        // Gắn sự kiện cho input giá và số lượng mới
        newVariation.querySelector('.price-input').addEventListener('input', function() {
            document.getElementById('product-price-preview').textContent = this.value || "0";
        });
        newVariation.querySelector('.variation-quantity').addEventListener('input',
            updateTotalQuantity);

        // Gắn sự kiện validate cho màu sắc, kích thước, giá, và giá khuyến mãi mới
        newVariation.querySelectorAll('.variation-color, .variation-size').forEach(function(select) {
            select.addEventListener('change', checkDuplicateVariations);
        });
        newVariation.querySelectorAll('.price-input, .promo-price').forEach(function(input) {
            input.addEventListener('input', checkPriceValidity);
        });

        variationIndex++;
        updateTotalQuantity();
    });

    // Xóa biến thể
    document.getElementById('variations-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variation')) {
            const variationItems = document.querySelectorAll('.variation-item');
            if (variationItems.length > 1) {
                e.target.closest('.variation-item').remove();
                updateTotalQuantity();
                checkDuplicateVariations();
                checkPriceValidity();
            } else {
                alert('Phải có ít nhất một biến thể!');
            }
        }
    });

    // Validate trước khi submit form
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!checkDuplicateVariations() || !checkPriceValidity()) {
            e.preventDefault();
        }
    });
});
</script>

<!-- Thêm CSS để đánh dấu lỗi (nếu dùng Bootstrap) -->
<style>
.is-invalid {
    border-color: #dc3545 !important;
}

.is-invalid:focus {
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}
</style>
