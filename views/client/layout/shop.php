<section class="section-b-space pt-0">
    <div class="custom-container container">
        <div class="row">
            <div class="col-3">
                <div class="custom-accordion left-box">
                    <div class="accordion" id="accordionPanelsStayOpenExample">

                        <!-- Danh mục -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-category">
                                    <span>Danh mục</span>
                                </button>
                            </h2>
                            <div class="accordion-collapse collapse show" id="collapse-category">
                                <div class="accordion-body">
                                    <ul class="catagories-side">
                                        <?php if (!empty($categories1)) : ?>
                                        <?php foreach ($categories1 as $category) : ?>
                                        <?php
                                $active = (isset($_GET['category_id']) && $_GET['category_id'] == $category['id']) ? 'active' : '';
                            ?>
                                        <li>
                                            <a href="?act=shop&category_id=<?= $category['id'] ?>"
                                                class="<?= $active ?>">
                                                <?= htmlspecialchars($category['ten_dm']) ?>
                                            </a>
                                        </li>
                                        <?php endforeach; ?>
                                        <?php else : ?>
                                        <li>Không có danh mục nào.</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Sắp xếp -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-sort">
                                    <span>Sắp xếp theo</span>
                                </button>
                            </h2>
                            <div class="accordion-collapse collapse show" id="collapse-sort">
                                <div class="accordion-body">
                                    <ul class="catagories-side">
                                        <!-- Sắp xếp theo sản phẩm mới nhất -->
                                        <li><a href="?act=shop&shop=1">Mới nhất</a></li>
                                        <!-- Sắp xếp theo giá tăng dần -->
                                        <li><a href="?act=shop&shop=2">Giá tăng dần</a></li>
                                        <!-- Sắp xếp theo giá giảm dần -->
                                        <li><a href="?act=shop&shop=3">Giá giảm dần</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                        <!-- Khoảng giá -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-price">
                                    <span>Khoảng giá</span>
                                </button>
                            </h2>
                            <div class="accordion-collapse collapse show" id="collapse-price">
                                <div class="accordion-body">
                                    <form action="?act=shop" method="GET">
                                        <input type="hidden" name="act" value="shop">
                                        <div class="mb-2">
                                            <label>Từ:</label>
                                            <input type="number" name="min_price" class="form-control" placeholder="0">
                                        </div>
                                        <div class="mb-2">
                                            <label>Đến:</label>
                                            <input type="number" name="max_price" class="form-control"
                                                placeholder="1000000">
                                        </div>
                                        <input type="submit" name="loc" value="Lọc"
                                            class="btn btn-sm btn-primary mt-2"></input>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-9">
                <div class="sticky">
                    <div class="product-tab-content ratio1_3">
                        <div class="row-cols-lg-4 row-cols-md-3 row-cols-2 grid-section view-option row g-3 g-xl-4">
                            <?php foreach ($productShop as $product): ?>
                            <div>
                                <a href="?act=detail&id=<?php echo $product['id']; ?>" class="product-link">
                                    <div class="product-box-3">
                                        <div class="img-wrapper">
                                            <div class="label-block">
                                            </div>
                                            <div class=""><a href="?act=detail&id=<?php echo $product['id']; ?>"
                                                    class="pro-first">
                                                    <img class="bg-img"
                                                        src="/Duan1-main/public/admin/assets_admin/images/product/<?php echo $product['hinh_anh']; ?>"
                                                        alt="product"></a>
                                            </div>
                                        </div>
                                        <div class="product-detail">

                                            <h5><?= $product['ten_sp'] ?></h5>
                                            <?php
                                                // Hiển thị giá khuyến mãi và giá gốc
                                                $gia_km = isset($product['gia_thap']) ? $product['gia_thap'] : 0;
                                                $don_gia = isset($product['gia_cao']) ? $product['gia_cao'] : 0;
                                                echo number_format($gia_km) . ' VND';
                                                ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Pagination -->
                        <nav aria-label="Page navigation example" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <!-- Nút prev -->
                                <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">«</a>
                                </li>
                                <?php else: ?>
                                <li class="page-item disabled"><span class="page-link">«</span></li>
                                <?php endif; ?>

                                <!-- Các nút số -->
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                                <?php endfor; ?>

                                <!-- Nút next -->
                                <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">»</a>
                                </li>
                                <?php else: ?>
                                <li class="page-item disabled"><span class="page-link">»</span></li>
                                <?php endif; ?>
                            </ul>
                        </nav>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<style>
.catagories-side {
    max-height: none !important;
    overflow: visible !important;
}

.accordion-body {
    max-height: none !important;
    overflow: visible !important;
}

.catagories-side a {
    text-decoration: none;
    color: #333;
    display: inline-block;
    padding: 5px 0;
    transition: all 0.3s ease;
}

.catagories-side a:hover {
    color: #c89f76;
    /* màu nâu nhạt giống hình */
    text-decoration: none;
    /* không gạch chân */
}

.catagories-side a.active {
    font-weight: bold;
    color: #c89f76;
}
</style>