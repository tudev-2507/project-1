<div class="page-content">
    <div class="container-fluid">
        <!-- Hiển thị thông báo -->
        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Danh Sách Sản Phẩm</h4>

                        <a href="?act=product_create" class="btn btn-sm btn-primary">
                            Thêm Sản Phẩm
                        </a>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>

                                        <th>Tên Sản Phẩm & Kích Thước</th>
                                        <th>Giá</th>
                                        <th>Kho Hàng</th>
                                        <th>Danh Mục</th>
                                        <th>Trạng Thái</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <?php 
                                            // Lấy thông tin kích thước cho sản phẩm
                                            $sizes = $productModel->getSizes($product['id']);
                                            $sizeText = '';
                                            foreach ($sizes as $size) {
                                                $sizeText .= $size['ten_kich_co'] . ' , ';
                                            }
                                            $sizeText = rtrim($sizeText, ' , ');
                                            
                                        ?>
                                    <tr>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div
                                                    class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                    <img src="/Duan1-main/public/admin/assets_admin/images/product/<?php echo htmlspecialchars($product['hinh_anh'] ? $product['hinh_anh'] : 'p-1.png'); ?>"
                                                        alt="<?php echo $product['ten_sp']; ?>" class="avatar-md">
                                                </div>
                                                <div>
                                                    <a href="?act=product_edit&id=<?php echo $product['id']; ?>"
                                                        class="text-dark fw-medium fs-15"><?php echo $product['ten_sp']; ?></a>
                                                    <p class="text-muted mb-0 mt-1 fs-13"><span>Kích Thước:
                                                        </span><?php echo $sizeText ? $sizeText : 'Không Có'; ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo number_format($product['gia_thap'], 0, ',', '.'); ?>
                                            VNĐ<?php echo ($product['gia_cao'] > $product['gia_thap']) ? ' - ' . number_format($product['gia_cao'], 0, ',', '.') . ' VNĐ' : ''; ?>
                                        </td>
                                        <td>
                                            <p class="mb-1 text-muted"><span
                                                    class="text-dark fw-medium"><?php echo $product['so_luong']; ?>
                                                    Sản Phẩm</span>
                                            <p class="mb-0 text-muted"><?php echo $product['so_luong_ban'] ?? 0; ?> Đã
                                                Bán
                                            </p>
                                        </td>
                                        <td><?php echo $product['ten_dm']; ?></td>
                                        <td>
                                            <span class="badge <?= $product['trang_thai'] ? 'bg-success' : 'bg-secondary' ?>">
                                                <?= $product['trang_thai'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="?act=product_edit&id=<?php echo $product['id']; ?>"
                                                    class="btn btn-soft-info btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18">
                                                    </iconify-icon>
                                                </a>
                                                <a href="<?= ($product['so_luong_ban'] ?? 0) > 0 ? 'javascript:void(0)' : '?act=product_delete&id=' . $product['id'] ?>"
                                                    class="btn btn-soft-danger btn-sm <?= ($product['so_luong_ban'] ?? 0) > 0 ? 'disabled' : '' ?>"
                                                    title="<?= ($product['so_luong_ban'] ?? 0) > 0 ? 'Sản phẩm đã bán, không thể xóa' : 'Xóa sản phẩm' ?>"
                                                    onclick="<?= ($product['so_luong_ban'] ?? 0) > 0 ? 'return false' : 'return confirmDelete(' . $product['id'] . ')' ?>">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                        class="align-middle fs-18"></iconify-icon>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>

                                    <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Không Tìm Thấy Sản Phẩm</td>
                                    </tr>
                                    <?php endif; ?>
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
    if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này không?")) {
        window.location.href = "?act=product_delete&id=" + id;
    }
}
</script>
