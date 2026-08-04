<div class="page-content">
    <div class="container-xxl">
        <div class="row">
            <div class="container">
                <!-- Hiển thị thông báo -->
                <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                </div>
                <?php endif; ?>


                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sửa danh mục</h4>
                    </div>
                    <div class="card-body">
                        <!-- Form sửa danh mục -->
                        <form method="POST" action="?act=category_edit&id=<?php echo $category['id']; ?>">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ten_dm" class="form-label">tên danh mục</label>
                                        <input type="text" id="ten_dm" name="ten_dm" class="form-control"
                                            value="<?php echo htmlspecialchars($category['ten_dm']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-0">
                                        <label for="mo_ta" class="form-label">Mô tả danh mục</label>
                                        <textarea class="form-control bg-light-subtle" id="mo_ta" name="mo_ta" rows="7"
                                            placeholder="Type description"><?php echo htmlspecialchars($category['mo_ta']); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 bg-light mb-3 rounded">
                                <div class="row justify-content-end g-2">
                                    <div class="col-lg-2">
                                        <button type="submit" class="btn btn-success w-100">Cập nhật</button>
                                    </div>
                                    <div class="col-lg-2">
                                        <a href="?act=category" class="btn btn-danger w-100">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>