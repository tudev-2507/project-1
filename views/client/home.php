<section class="pt-0 home-section-3 text-center">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-2 d-none ">
            </div>
            <div class="col pe-0">
                <div class="home-banner p-right">
                    <img class="img-fluid" src="/Duan1-main/public/client/assets/images/layout-3/1.jpg" alt="" />
                    <div class="contain-banner">
                        <div>
                            <h4> Khuyến mãi hấp dẫn <span>Bắt đầu ngay hôm nay </span></h4>
                            <h1>Khám phá phong cách sáng tạo đích thực của bạn.</h1>
                            <div class="link-hover-anim underline">
                                <a class="btn btn_underline " href="?act=shop">Hiên thị ngay
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<section class="section-t-space">
    <div class="custom-container container service">
        <ul>
            <li>
                <div class="service-block">
                    <img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/1.svg" alt="" />
                    <div>
                        <h6>Miễn phí vận chuyển trên toàn</h6>

                    </div>
                </div>
            </li>
            <li>
                <div class="service-block">
                    <img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/2.svg" alt="" />
                    <div>
                        <h6>Trả lại & Đổi hàng</h6>
                    </div>
                </div>
            </li>
            <li>
                <div class="service-block">
                    <img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/3.svg" alt="" />
                    <div>
                        <h6>Hỗ trợ kỹ thuật</h6>
                    </div>
                </div>
            </li>
            <li>
                <div class="service-block border-0">
                    <img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/4.svg" alt="" />
                    <div>
                        <h6>Phiếu quà tặng hàng ngày</h6>

                    </div>
                </div>
            </li>
        </ul>
    </div>
</section>
<section class="section-t-space">
    <div class="custom-container container">
        <div class="row">
            <div class="col-xxl-5 col-lg-8 offer-box-1">
                <div class="row gy-4 ratio_45">
                    <div class="col-12">
                        <div class="collection-banner p-left">
                            <img class="bg-img" src="/Duan1-main/public/client/assets/images/banner/banner-7.jpg"
                                alt="" />
                            <div class="contain-banner">
                                <div>
                                    <h4>Giảm đến 60%</h4>
                                    <h3>Áo nam mới hàng hiệu </h3>
                                    <div class="link-hover-anim underline">
                                        <a class="btn btn_underline " href="?act=shop">Bộ sưu
                                            tập cửa hàng
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="collection-banner p-right">
                            <img class="bg-img" src="/Duan1-main/public/client/assets/images/banner/banner-8.jpg"
                                alt="" />
                            <div class="contain-banner">
                                <div>
                                    <h4>Giảm đến 60%</h4>
                                    <h3>Áo thu đông mới </h3>
                                    <div class="link-hover-anim underline">
                                        <a class="btn btn_underline " href="?act=shop">Bộ sưu
                                            tập cửa hàng
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-4 d-none d-lg-block">
                <div class="special-offer-slider">
                    <h4>Thời trang mùa hè</h4>
                    <div class="swiper special-offer-slide">
                        <a href="?act=shop">
                            <img class="" src="/Duan1-main/public/client/assets/images/product/product-3/12.jpg"
                                alt="product" /></a>
                    </div>
                </div>
            </div>
            <div class="col-4 d-none d-xxl-block">
                <div class="offer-banner-3 ratio1_3">
                    <a href="?act=shop">
                        <img class="bg-img" src="/Duan1-main/public/client/assets/images/banner/banner-9.jpg" alt="" />
                        <div>
                            <img src="/Duan1-main/public/client/assets/images/banner/2.png" alt="" />
                            <h6>Giảm đến 70%</h6>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-t-space">
    <div class="custom-container container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="title-1 mt-5">
                    <h3>Sản phẩm mới nhất</h3>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="tab-content mt-5">
                <div class="tab-pane fade show active" id="new-product" role="tabpanel" tabindex="0">
                    <div class="row ratio1_3 gy-4 gx-3 gx-sm-4">
                        <?php foreach($productHome as $prod):?>
                        <div class="col-lg-3 col-md-4 col-6">
                            <div class="product-box-3">
                                <div class="img-wrapper">
                                    <div class="product-image">
                                        <a class="" href="?act=detail&id=<?php echo $prod['id']; ?>">
                                            <img class="bg-img"
                                                src="/Duan1-main/public/admin/assets_admin/images/product/<?php echo $prod['hinh_anh']; ?>"
                                                alt="product" /></a>
                                    </div>

                                </div>
                                <div class="product-detail">
                                    <a href="?act=detail&id=<?php echo $prod['id']; ?>">
                                        <h5><?php echo $prod['ten_sp']; ?></h5>
                                    </a>
                                    <br>
                                    <?php echo number_format($prod['don_gia']). ' VND'; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<section class="section-t-space ">
    <div class="custom-container container">
        <div class="title mt-5">
            <h3>Danh mục </h3>
        </div>
        <div class="swiper top-categories-slide mt-5">
            <div class="swiper-wrapper ratio_square-3">
                <div class="swiper-slide top-categories">
                    <div class="row align-items-center justify-content-center gy-3">
                        <div class="col-sm-6 col-12 order-2 order-sm-1">
                            <ul>
                                <?php foreach($categoryHome2 as $cate): ?>
                                <li> <a href="?act=shop&category_id=<?=$cate['id'] ?>">
                                        <h6><?=$cate['ten_dm'] ?></h6>
                                    </a>
                                    <p><?=$cate['mo_ta'] ?></h6>
                                    </p>
                                </li>
                                <?php endforeach; ?>

                            </ul>
                        </div>
                        <div class="col-sm-6 col-12 order-1 order-sm-2">
                            <div class="categories-img"><img class="bg-img"
                                    src="/Duan1-main/public/client/assets/images/product/product-2/jackets/2.jpg"
                                    alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide top-categories">
                    <div class="row align-items-center justify-content-center gy-3">
                        <div class="col-sm-6 col-12 order-2 order-sm-1">
                            <ul>
                                <?php foreach($categoryHome as $cat): ?>
                                <li> <a href="?act=shop&category_id=<?=$cat['id'] ?>">
                                        <h6><?=$cat['ten_dm'] ?></h6>
                                    </a>
                                    <p><?=$cat['mo_ta'] ?></h6>
                                    </p>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-sm-6 col-12 order-1 order-sm-2">
                            <div class="categories-img"><img class="bg-img"
                                    src="/Duan1-main/public/client/assets/images/top-categories/1.jpg" alt=""></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-t-space">
    <div class="custom-container container">
        <div class="row  text-center">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="title-1 ">
                        <h3>Sản phẩm bán chạy nhất</h3>
                    </div>
                </div>
                <div class="col-12">
                    <div class="tab-content mt-5">
                        <div class="tab-pane fade show active " id="featured-product" role="tabpanel" tabindex="0">
                            <div class="row ratio1_3 gy-4">
                                <?php foreach($productBestSelling as $prod1):?>
                                <div class="col-lg-3 col-md-4 col-6">
                                    <div class="product-box-3">
                                        <div class="img-wrapper">
                                            <div class="product-image">
                                                <a class="" href="?act=detail&id=<?php echo $prod1['id']; ?>">
                                                    <img class="bg-img"
                                                        src="/Duan1-main/public/admin/assets_admin/images/product/<?php echo $prod1['hinh_anh']; ?>"
                                                        alt="product" /></a>
                                            </div>

                                        </div>
                                        <div class="product-detail">
                                            <a href="?act=detail&id=<?php echo $prod1['id']; ?>">
                                                <h5><?php echo $prod1['ten_sp']; ?></h5>
                                            </a>
                                            <br>
                                            <?php echo number_format($prod1['don_gia']). ' VND'; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
</section>
<section class="section-t-space text-center">
    <div class="home-banner p-right mb-5">
        <img class="img-fluid" src="/Duan1-main/public/client/assets/images/banner/banner-12.jpg" alt="" />
    </div>
</section>