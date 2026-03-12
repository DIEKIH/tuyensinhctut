@extends('users.layouts.app')

@section('title', 'Trang chủ')

@section('css')
    {{-- <link rel="stylesheet" href="/css/cacnganh.css"> --}}
@endsection

@section('content')
    <!-- Banner -->
    {{-- <section id="ts-banner-home">
        <div class="container">
            <div class="banner-home">
                <div class="swiper bannerSwiper">
                    <div class="swiper-wrapper" id="banner-list">
                    </div>

                   
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section> --}}

    <section id="ts-banner-home">
        <div class="banner-home">
            <div class="swiper bannerSwiper">
                <div class="swiper-wrapper" id="banner-list">
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <style>
        #ts-banner-home {
            width: 100%;
        }

        .banner-home {
            max-width: 100%
            margin: 0 auto;
            position: relative;
        }

        .banner-home:hover {
            cursor: pointer;
        }

        .bannerSwiper {
            width: 100%;
            max-height: unset; 
        }

        .swiper-slide img {
            width: 100% ;
            height: auto;
            max-height: 500px;
            object-fit: contain;
            display: block;
        }

        .swiper-pagination-bullet {
            background: #c0c0c0;
        }

        @media screen and (max-width: 768px) {
            #ts-banner-home {
                display: none;
            }

            .mobi-mt {
                margin-top: 30px !important;
            }
        }

        #thongbaonho {
            max-height: 370px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: #005da0 #f0f0f0;
        }

        #thongbaonho li {
            margin-bottom: 8px !important;
            line-height: 1.4;
        }

        #thongbaonho::-webkit-scrollbar {
            width: 4px;
        }

        #thongbaonho::-webkit-scrollbar-track {
            background: #f0f0f0;
        }

        #thongbaonho::-webkit-scrollbar-thumb {
            background: #005da0;
            border-radius: 4px;
        }


        /* === TIN TUC NHO === */
        .tin-tuc-nho-wrap {
            display: flex;
            flex-wrap: nowrap;
            gap: 16px;
            align-items: flex-start;
        }

        .col-nho {
            flex: 0 0 calc(20% - 13px);
            min-width: 0;
            width: calc(20% - 13px);
        }

        /* Ép ảnh nhỏ lại */
        .tin-tuc-nho-wrap .ratio-container {
            aspect-ratio: unset !important;
            height: 140px !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .tin-tuc-nho-wrap .ratio-container figure {
            margin: 0;
            width: 100%;
            height: 100%;
        }

        .tin-tuc-nho-wrap .ratio-container img {
            position: absolute !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        /* Sửa lại toàn bộ phần tin-tuc-nho */
        .tin-tuc-nho-wrap {
            align-items: stretch;
        }

        .tin-tuc-nho-wrap .col-nho {
            display: flex;
            flex-direction: column;
        }

        .tin-tuc-nho-wrap .col-nho > div {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .tin-tuc-nho-wrap .news-content {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .tin-tuc-nho-wrap .news-content .news-info {
            margin-top: auto !important;
            padding-top: 8px !important;
        }

        .tin-tuc-nho-wrap .col-nho .fake-news-blur {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .tin-tuc-nho-wrap .col-nho .fake-news-blur .news-content {
            flex: 1;
        }

        /* Title nhỏ */
        .tin-tuc-nho-wrap .news-title a {
            font-size: 13px !important;
            font-weight: 600 !important;
            line-height: 18px !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 3 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }

        @media (max-width: 768px) {
            .tin-tuc-nho-wrap {
                flex-wrap: wrap;
            }
            .col-nho {
                flex: 0 0 calc(50% - 5px);
            }
        }

        @media (max-width: 480px) {
            .col-nho {
                flex: 0 0 100%;
            }
        }
     

        
    </style>

    <!-- Tin tuc -->
    <section class="ts-section mobi-mt mt-60 ">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 pe-lg-5">
                    <div class="ts-title">
                        <div class="vertical-bar"></div>
                        <h2 class="title-text">Tin tức</h2>
                        <div class="line"></div>
                    </div>
                    <div class="mb-4" id="featured-news">
                    </div>
                </div>

                {{-- Thong bao --}}
                <div class="col-lg-4">
                    <div class="col-lg-12 col-md-12">
                        <div class="ts-title">
                            <div class="vertical-bar"></div>
                            <h2 class="title-text">Thông báo</h2>
                            <div class="line"></div>
                        </div>
                        <div class="list-with-marker">
                            <ul class="mb-0 ps-0" id="thongbaonho"></ul>
                            <div id="offcanvas-wrapper"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tin tuc nho - row rieng --}}
            <div class="tin-tuc-nho-wrap mt-4" id="tin-tuc-nho-container">
            </div>
        </div>
    </section>

    <style>
        .fake-news-card {
            position: relative;
            overflow: hidden;
        }

        /* Làm mờ toàn bộ nội dung */
        .fake-news-blur {
            opacity: 0.5;
            pointer-events: none;
            transition: none;
        }

        /* Overlay chữ "Xem thêm" */
        .fake-news-overlay {
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fake-news-card:hover .fake-news-overlay {
            opacity: 1;
            background: rgba(255, 255, 255, 0.15);
            /* lớp kính mờ nhẹ */
            backdrop-filter: saturate(150%) contrast(90%);
            /* tăng độ tương phản, chữ rõ hơn */
            /* backdrop-filter: blur(1px); */

        }


        /* Nút "Xem thêm" rõ ràng, không bị ảnh hưởng bởi overlay mờ */
        .fake-news-overlay .fw-bold {
            opacity: 1;
            background-color: #005da0 !important;
            color: white !important;
            z-index: 1;
        }

        @media (max-width: 991.98px) {
            .fake-news-overlay {
                opacity: 1;
                pointer-events: auto;
            }

            .fake-news-overlay {
                background: rgba(255, 255, 255, 0.3);
                /* backdrop-filter: blur(2px); */
            }
        }

        .ratio-container {
            position: relative;
            aspect-ratio: 4 / 3;
            overflow: hidden;
        }

        .ratio-container img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Giữ tỉ lệ, crop nếu cần */
        }

        .news-img img {
            width: 100%;
            max-height: 370px;
        }

        .news-content {
            margin-top: 10px;
        }

        .news-title a {
            font-size: 16px;
            font-weight: 700;
        }

        .left-title a {
            line-height: 24px;
        }

        .news-info {
            margin-top: 10px;
        }

        .news-date i {
            font-size: 14px;
            /* margin-right: 4px; */
        }

        .news-item-small {
            display: flex !important;
        }

        .hover01 figure img {
            transform: scale(1);
            transition: .3s ease-in-out;
        }

        .hover01 figure:hover img {
            transform: scale(1.2);
        }

        .hover01 figure {
            overflow: hidden;
        }


        .text-sub {
            color: #646464
        }
    </style>
    <style>
        .noti-item:hover {
            cursor: pointer;
        }

        .list-with-marker li {
            position: relative;
            padding-left: 1em;
            font-size: 16px;
            /* text-indent: -0.6em; */
        }

        .list-with-marker li::before {
            content: "•";
            position: absolute;
            font-size: 18px;
            left: 0;
            top: 0;
            /* transform: translate(0, -50%); */
            color: #000;
            font-weight: bold;
        }

        .video-container video {
            width: 100% !important;
            /* Đảm bảo video chiếm toàn bộ chiều rộng */
            height: auto;
            /* Đảm bảo tỷ lệ khung hình không bị thay đổi */
        }

        .more-link a {
            color: var(--primary-color);
        }
    </style>


    <!-- Thong so -->
    <section class="ts-section bg-overlay py-5 text-center">
        <div class="container stats-section">
            <div class="ts-title">
                <div class="line"></div>
            </div>
            <h4 class="fw-bold mb-5">NHỮNG CON SỐ NỔI BẬT</h4>

            <!-- Swiper -->
            <div class="swiper stats-swiper">
                <div class="swiper-wrapper">
                    <!-- Sinh viên -->
                    <div class="swiper-slide">
                        <div class="col-12">
                            <i class="fas fa-users highlight-icon"></i>
                            <div class="highlight-number">20,000</div>
                            <div>Sinh viên đang theo học</div>
                        </div>
                    </div>

                    <!-- Giảng viên -->
                    <div class="swiper-slide">
                        <div class="col-12">
                            <i class="fas fa-chalkboard-teacher highlight-icon"></i>
                            <div class="highlight-number">1,200</div>
                            <div>Giảng viên và cán bộ</div>
                        </div>
                    </div>

                    <!-- Ngành học -->
                    <div class="swiper-slide">
                        <div class="col-12">
                            <i class="fas fa-graduation-cap highlight-icon"></i>
                            <div class="highlight-number">35 +</div>
                            <div>Ngành đào tạo đại học & sau đại học</div>
                        </div>
                    </div>

                    <!-- Học bổng -->
                    <div class="swiper-slide">
                        <div class="col-12">
                            <i class="fas fa-gift highlight-icon"></i>
                            <div class="highlight-number">15 TỶ+</div>
                            <div>Học bổng trao hàng năm</div>
                        </div>
                    </div>
                    <!-- Sinh viên -->
                    <div class="swiper-slide">
                        <div class="col-12">
                            <i class="fas fa-users highlight-icon"></i>
                            <div class="highlight-number">20,000</div>
                            <div>Sinh viên đang theo học</div>
                        </div>
                    </div>

                    <!-- Giảng viên -->
                    <div class="swiper-slide">
                        <div class="col-12">
                            <i class="fas fa-chalkboard-teacher highlight-icon"></i>
                            <div class="highlight-number">1,200</div>
                            <div>Giảng viên và cán bộ</div>
                        </div>
                    </div>

                    <!-- Ngành học -->
                    <div class="swiper-slide">
                        <div class="col-12">
                            <i class="fas fa-graduation-cap highlight-icon"></i>
                            <div class="highlight-number">35 +</div>
                            <div>Ngành đào tạo đại học & sau đại học</div>
                        </div>
                    </div>

                    <!-- Học bổng -->
                    <div class="swiper-slide">
                        <div class="col-12">
                            <i class="fas fa-gift highlight-icon"></i>
                            <div class="highlight-number">15 TỶ+</div>
                            <div>Học bổng trao hàng năm</div>
                        </div>
                    </div>
                    <!-- Sinh viên -->
                    <div class="swiper-slide">
                        <div class="col-12">
                            <i class="fas fa-users highlight-icon"></i>
                            <div class="highlight-number">20,000</div>
                            <div>Sinh viên đang theo học</div>
                        </div>
                    </div>

                    <!-- Giảng viên -->
                    <div class="swiper-slide">
                        <div class="col-12">
                            <i class="fas fa-chalkboard-teacher highlight-icon"></i>
                            <div class="highlight-number">1,200</div>
                            <div>Giảng viên và cán bộ</div>
                        </div>
                    </div>

                    <!-- Ngành học -->
                    <div class="swiper-slide">
                        <div class="col-12">
                            <i class="fas fa-graduation-cap highlight-icon"></i>
                            <div class="highlight-number">35 +</div>
                            <div>Ngành đào tạo đại học & sau đại học</div>
                        </div>
                    </div>

                    <!-- Học bổng -->
                    <div class="swiper-slide">
                        <div class="col-12">
                            <i class="fas fa-gift highlight-icon"></i>
                            <div class="highlight-number">15 TỶ+</div>
                            <div>Học bổng trao hàng năm</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .highlight-icon {
            font-size: 40px;
            color: var(--primary-light-color);
        }

        .highlight-number {
            font-size: 30px;
            font-weight: bold;
            color: var(--primary-light-color);
        }

        .stats-section {
            position: relative;
            z-index: 1;
        }

        /* Swiper customization */
        .stats-swiper {
            padding: 0 20px;
        }

        .stats-swiper .swiper-slide {
            height: auto;
        }

        .swiper-pagination-bullet {
            background: var(--primary-light-color);
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: var(--primary-light-color);
        }

        /* Responsive cho mobile */
        @media (max-width: 768px) {
            .stats-swiper {
                padding: 0 10px;
            }
        }
    </style>



    <!-- Su kien -->
    <section class="ts-section">
        <div class="container">
            <div class="ts-title">
                <div class="vertical-bar"></div>
                <h2 class="title-text">Sự kiện</h2>
                <div class="line"></div>
            </div>
        </div>
        <div class="container mt-5 mb-3">
            <div class="row gy-4" id="sukien-container">



            </div>
        </div>
        {{-- <div class="row mt-lg-0 mtb-50">
            <div class="text-center btn-more-block">
                <a href="" class="more fw-bold fs-5 px-3 py-2 rounded shadow-sm">Sự kiện khác</a>
            </div>
        </div> --}}
        {{-- <div class="row mt-lg-0 mtb-50">
            <div class="text-center  btn-more-block">
                <a class="d-inline-block button">
                    Sự kiện khác
                    <svg fill="currentColor" viewBox="0 0 24 24" class="icon">
                        <path clip-rule="evenodd"
                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z"
                            fill-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div> --}}
    </section>

    <style>
        .date-bg {
            background-color: var(--primary-color);
        }

        .mt-60 {
            margin-top: 60px;
        }

        .py-40 {
            padding-top: 44px;
            padding-bottom: 44px;
        }

        .bg-gray {
            background-color: #ebebeb;
        }
    </style>


    <!-- Nganh -->
    <section class="ts-section mb-5">
        <div class="container">
            <div class="ts-title">
                <div class="vertical-bar"></div>
                <h2 class="title-text">Các ngành tuyển sinh</h2>
                <div class="line"></div>
            </div>
            <div class="swiper nganhSwiper">
                <div class="swiper-wrapper">


                    <!-- Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            <div class="row mt-lg-0 mtb-50">
                <div class="text-center  btn-more-block">
                    <a class="d-inline-block button">
                        Xem thêm
                        <svg fill="currentColor" viewBox="0 0 24 24" class="icon">
                            <path clip-rule="evenodd"
                                d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z"
                                fill-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
    </section>








    <style>
        .nganh-item {
            box-sizing: border-box;
            vertical-align: baseline;
            margin: 0px;
            padding: 0px;
            border-width: 0px;
            border-style: initial;
            border-color: initial;
            border-image: initial;
            font: inherit;
            position: relative;
            cursor: pointer;
            box-shadow: rgba(255, 255, 255, 0.25) 0px 5px 12px 0px;
            /* border-radius: 10px; */
            overflow: hidden;
            transition: 0.5s;
            margin: 20px;
            color: white;
        }

        .nganh-item {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .nganh-item:hover {
            transform: scale(1.02);
            box-shadow: rgb(138, 138, 138) 0px 5px 12px 0px;
        }

        .nganh-item img {
            cursor: pointer;
            width: 100%;
            /* object-fit: cover; */
        }

        .education-program-item__info {
            position: absolute;
            left: 0px;
            bottom: 0px;
            width: 100%;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.78);
            padding: 10px 5px;
        }
    </style>







@endsection


@section('js')
    <script src="/js/trangchu.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

    {{-- Thong so --}}
    <script>
        // Initialize Swiper
        const swiper = new Swiper('.stats-swiper', {
            // Slides per view
            slidesPerView: 1,
            spaceBetween: 30,

            // Responsive breakpoints
            breakpoints: {
                // when window width is >= 576px
                576: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                // when window width is >= 768px
                768: {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
                // when window width is >= 992px
                992: {
                    slidesPerView: 4,
                    spaceBetween: 40
                }
            },
            // Auto play (optional)
            autoplay: {
                delay: 199999000,
                disableOnInteraction: false,
            },

            // Loop
            loop: true,

            // Centered slides
            centeredSlides: false,
        });
    </script>

    {{-- Nganh --}}
    <script>
        const nganhSwiper = new Swiper(".nganhSwiper", {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false
            },
            pagination: {
                el: ".nganhSwiper .swiper-pagination",
                clickable: true
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 16
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 24
                },
                992: {
                    slidesPerView: 3,
                    spaceBetween: 32
                }
            }
        });
    </script>
@endsection
