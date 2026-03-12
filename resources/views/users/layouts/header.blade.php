<!-- header.php -->
<section>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            {{-- <img src="/images/system/logo (2).png" alt=""> --}}
            <a href="#">
                <img src="/images/system/logo (2).png" alt="Logo CTUT" class="logo-img me-2" />
            </a>
            {{-- <a class="navbar-brand text-white fw-bold" href="#">CTUT</a> --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Các menu item -->
                    <li class="nav-item"><a class="nav-link" href="#">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Giới thiệu</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link " href="#" role="button">Đào tạo</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Đại học chính quy</a></li>
                            <li><a class="dropdown-item" href="#">Đại học văn bằng 2</a></li>
                            <li><a class="dropdown-item" href="#">Sau đại học</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Tin tức</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Thông báo</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Quy chế</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Liên hệ</a></li>
                </ul>


                {{-- <form method="POST" action="{{ route('change.theme') }}" class="theme-form" id="themeForm">
                    <div class="theme-selector-container" onclick="toggleColorPalette()">
                        <i class="bi bi-palette theme-icon"></i>
                        <span class="theme-text">Giao diện</span>
                        <div class="color-preview" id="colorPreview"></div>

                        <div class="color-palette" id="colorPalette">
                            <div class="palette-title">Chọn màu giao diện</div>
                            <div class="color-grid">
                                <div class="color-item" style="background: #0e4582" data-color="#0e4582"></div>
                                <div class="color-item" style="background: #dc3545" data-color="#dc3545"></div>
                                <div class="color-item" style="background: #198754" data-color="#198754"></div>
                                <div class="color-item" style="background: #fd7e14" data-color="#fd7e14"></div>
                                <div class="color-item" style="background: #6f42c1" data-color="#6f42c1"></div>
                                <div class="color-item" style="background: #d63384" data-color="#d63384"></div>
                                <div class="color-item" style="background: #0dcaf0" data-color="#0dcaf0"></div>
                                <div class="color-item" style="background: #20c997" data-color="#20c997"></div>
                                <div class="color-item" style="background: #ffc107" data-color="#ffc107"></div>
                                <div class="color-item" style="background: #6c757d" data-color="#6c757d"></div>
                                <div class="color-item" style="background: #212529" data-color="#212529"></div>
                                <div class="color-item" style="background: #495057" data-color="#495057"></div>
                            </div>
                            <div class="custom-color-section">
                                <button type="button" class="custom-color-btn" onclick="openCustomColor()">Màu tùy
                                    chỉnh</button>
                            </div>
                        </div>
                    </div>

                    <input type="color" name="theme_color" id="colorPicker" class="color-picker-hidden"
                        value="{{ session('theme_color', '#0e4582') }}" onchange="updateColorAndSubmit(this.value)">
                </form> --}}

                {{-- <form class="d-flex ms-3">
                    <div class="search-box">
                        <input type="text" placeholder="Tìm kiếm...">
                        <button type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form> --}}
            </div>
        </div>
    </nav>
</section>
<style>
    .logo-img {
        height: 40px;
        /* hoặc auto nếu bạn muốn theo tỉ lệ gốc */
        width: auto;
        object-fit: contain;
    }
</style>
