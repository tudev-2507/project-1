<section class="section-b-space pt-0 product-thumbnail-page">
    <div class="custom-container container">
        <div class="row gy-4">
            <!-- ---------------------------------------------------------------------------- -->

            <div class="col-lg-6">
                <div class="row sticky">
                    <div class="col-sm-2 col-3">
                        <div class="swiper product-slider product-slider-img">
                            <div class="swiper-wrapper">
                                <?php foreach($productDetail as $prod): ?>
                                <div class="swiper-slide"> <img
                                        src="/Duan1-main/public/admin/assets_admin/images/product/<?= $prod['hinh_anh']?>"
                                        alt="">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10 col-9">
                        <div class="swiper product-slider-thumb product-slider-img-1">
                            <div class="swiper-wrapper ratio_square-2">
                                <?php foreach($productDetail as $prod): ?>
                                <div class="swiper-slide">
                                    <img class="bg-img"
                                        src="/Duan1-main/public/admin/assets_admin/images/product/<?= $prod['hinh_anh']?>"
                                        alt="">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product-detail-box">
                    <div class="product-option">
                        <form action="?act=cart" method="POST">
                            <?php foreach($productDetail as $prod): 
                                $danh_sach_mau = explode(',', $prod['danh_sach_mau']);
                                $danh_sach_kich_co = explode(',', $prod['danh_sach_kich_co']);
                            ?>
                            <!-- Hidden ID -->
                            <input type="hidden" name="id" value="<?= $prod['id'] ?>">

                            <h3><?= $prod['ten_sp'] ?></h3>
                            <h5><?= number_format($prod['gia_thap'] , 0, ',', '.')?>
                                <del><?= number_format($prod['gia_km_cao'], 0, ',', '.') ?> đ
                                </del><span class="offer-btn"></span>
                            </h5>
                            <div class="rating">
                                <p><?= $prod['mo_ta'] ?></p>
                            </div>

                            <!-- Size -->
                            <div class="d-flex">
                                <div>
                                    <h5>Size:</h5>
                                    <div class="">
                                        <ul class="selected">
                                            <?php foreach($danh_sach_kich_co as $kich_co): ?>
                                            <li>
                                                <label>
                                                    <input type="radio" name="kich_co" value="<?=$kich_co?>">
                                                    <?= $kich_co ?>
                                                </label>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Color -->
                            <div>
                                <h5>Color:</h5>
                                <div class="">
                                    <ul class="color-variant">
                                        <?php foreach($danh_sach_mau as $mau): ?>
                                        <li>
                                            <label>
                                                <input type="radio" name="mau_sac" value="<?= $mau ?>">
                                                <?= $mau ?>
                                            </label>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php
                            if (isset($_SESSION['errorCart'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['errorCart']; unset($_SESSION['errorCart']); ?>
                            </div>
                            <?php endif; ?>

                            <!-- Quantity -->
                            <div class="quantity-box d-flex align-items-center gap-3">

                                <div class="d-flex align-items-center gap-3 w-100">
                                    <input type="submit" class="btn btn_black sm" value="Thêm vào giỏ hàng"
                                        name="submit">
                                    </input>
                                    <input type="submit" class="btn btn_outline sm" value="Mua" name="submit">
                                </div>
                            </div>

                            <!-- Thông tin thêm -->
                            <div class="dz-info">
                                <ul>
                                    <li>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6>Có sẵn: </h6>
                                            <p>Đặt hàng trước</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6>Danh mục: </h6>
                                            <p><?= $prod['ten_dm'] ?> - <?= $prod['id_dm'] ?></p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6>Số lượng:</h6>
                                            <p><?= $prod['so_luong'] ?></p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <?php endforeach; ?>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>
<section class="section-b-space pt-0">
    <div class="custom-container container product-contain">
        <div class="title text-start">
            <h3>Sản phẩm liên quan</h3>
            <svg>
                <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#main-line"></use>
            </svg>
        </div>
        <div class="swiper special-offer-slide-2">
            <div class="swiper-wrapper ratio1_3">
                <?php foreach($relatedProduct as $related ): ?>
                <div class="swiper-slide">

                    <div class="product-box-3">
                        <div class="img-wrapper">
                            <div class="product-image">
                                <a class="" href="?act=detail&id=<?=$related['id_san_pham']?>"> <img class="bg-img"
                                        src="/Duan1-main/public/admin/assets_admin/images/product/<?php echo $related['hinh_anh']; ?>"
                                        alt="product"></a>
                            </div>

                        </div>
                        <div class="product-detail">
                            <a href="?act=detail&id=<?=$related['id_san_pham']?>">
                                <h6><?php echo $related['ten_sp']; ?></h6>
                            </a>
                            <p><?php echo $related['don_gia']; ?>
                                <del><?php echo $related['gia_km']; ?></del><span>-20%</span>
                            </p>
                        </div>
                    </div>

                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</section>