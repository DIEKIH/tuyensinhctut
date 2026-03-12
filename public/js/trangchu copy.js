$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

function formatDate(dateStr) {
    const d = new Date(dateStr);
    const day = d.getDate().toString().padStart(2, '0');
    const month = (d.getMonth() + 1).toString().padStart(2, '0');
    const year = d.getFullYear();
    return `${day}/${month}/${year}`;
}

// banner
$.ajax({
    type: "get",
    url: "/banners",  // Đảm bảo đường dẫn chính xác
    success: function (res) {
        // console.log(res);
        // console.log(res.data); // Kiểm tra dữ liệu từ API
        renderBanners(res.data);

        // if (res.data && Array.isArray(res.data)) {
        //     renderBanners(res.data);
        // } else {
        //     alert('Không có dữ liệu banner.');
        // }
    },
    error: function () {
        alert('Đã có lỗi xảy ra khi tải banner.');
    }
});



function renderBanners(banners) {
    const swiperWrapper = $('#banner-list');  // Chọn phần tử chứa các slide của swiper
    swiperWrapper.empty();  // Xóa tất cả slide cũ nếu có

    banners.forEach(function (banner) {
        // Tạo slide mới với mỗi banner
        const slideHTML = `
            <div class="swiper-slide">
                <a class="d-block" title="">
                    <img class="banner-ts-image img-fluid" src="${banner.image_url}" 
                        alt="" itemprop="contentUrl">
                </a>
            </div>
        `;
        swiperWrapper.append(slideHTML);  // Thêm slide mới vào swiper
    });

    // Khởi động lại Swiper sau khi đã render các banner mới
    initSwiper();  // Đảm bảo Swiper được khởi động lại sau khi thêm slide mới
}

function initSwiper() {
    const bannerSwiper = new Swiper('.bannerSwiper', {
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false
        },
        pagination: {
            el: '.bannerSwiper .swiper-pagination',
            clickable: true
        },
        navigation: {
            nextEl: '.bannerSwiper .swiper-button-next',
            prevEl: '.bannerSwiper .swiper-button-prev'
        }
    });
}

// $.ajax({
//     type: "get",
//     url: "banners",
//     success: function (res) {
//         renderBanners(res.data);
//     }
// });



// tintucnoibat
$.ajax({
    type: "get",
    url: "/tintucnoibat",  // Đảm bảo đường dẫn chính xác
    success: function (res) {
        renderTintucnoibat(res.data); // Gọi hàm render với mảng tin tức
    },
    error: function () {
        alert('Đã có lỗi xảy ra khi tải tin tức.');
    }
});

function renderTintucnoibat(tintucnoibatArray) {
    const container = $('#featured-news');
    container.empty();

    tintucnoibatArray.forEach(tintucnoibat => {
        const html = `
            <div class="news-item">
                    <div class="news-img hover01">
                        <a href="">
                            <figure>
                                <img src="${tintucnoibat.image_url}" alt="#">
                            </figure>
                        </a>
                    </div>
                    <div class="news-content">
                        <div class="news-title one-line ellipsis-text pb-2">
                            <h3><a href="" class="text-hover">${tintucnoibat.title}</a></h3>
                        </div>
                        <div class="news-summary text-sub two-line ellipsis-text">
                            <p>${tintucnoibat.summary}</p>
                        </div>
                    </div>
                    <div class="news-info d-flex justify-content-start gap-3 text-sub">
                        <div class="news-date me-3">
                            <i class="fa-regular fa-clock me-1"></i> ${formatDate(tintucnoibat.publish_date)}
                        </div>
                        <div class="news-views">
                            <i class="fa-regular fa-eye me-1"></i> 1234
                        </div>
                    </div>

                </div>
        `;
        container.append(html);
    });
}


// tintucnho
$.ajax({
    type: "get",
    url: "/tintucnho",  // Đảm bảo đường dẫn chính xác
    success: function (res) {
        // if (res.data && res.data.length > 0) {
        renderTintucnho(res.data); // Gọi hàm render với mảng tin tức
        // } else {
        //     alert('Không có tin tức nào.');
        // }
    },
    error: function () {
        alert('Đã có lỗi xảy ra khi tải tin tuc.');
    }
});
function renderTintucnho(tintucnhoArray) {
    const container = $('#tin-tuc-nho-container');
    container.empty();

    tintucnhoArray.forEach(function (tintucnho, index) {
        const isLast = index === tintucnhoArray.length - 1;

        const html = `
            <div class="col-4 mb-4">
                <div class="position-relative ${isLast ? 'fake-news-card' : ''}">
                    ${isLast ? `
                    <div class="fake-news-blur">` : ''
            }

                    <div class="ratio-container news-img hover01 mb-3">
                        <a href="/tin-tuc/${tintucnho.id}">
                            <figure>
                                <img class="img-fluid" src="${tintucnho.image_url}" alt="">
                            </figure>
                        </a>
                    </div>

                    <div class="news-content mt-0">
                        <div class="news-title left-title">
                            <h3>
                                <a class="ellipsis-text two-line text-hover" href="/tin-tuc/${tintucnho.id}">
                                    ${tintucnho.title}
                                </a>
                            </h3>
                        </div>
                        <div class="news-summary two-line ellipsis-text text-sub">
                            <p>${tintucnho.summary}</p>
                        </div>
                        <div class="news-info mt-3 d-flex justify-content-between align-items-center text-sub">
                            <div class="news-date">
                                <i class="fa-regular fa-clock me-1"></i> 12/04/2025
                            </div>
                            <div class="news-views">
                                <i class="fa-regular fa-eye"></i> 1234
                            </div>
                        </div>
                    </div>

                    ${isLast ? `
                    </div>
                    <a href="/tin-tuc/${tintucnho.id}" class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center fake-news-overlay text-decoration-none">
                        <div class="fw-bold fs-5 text-dark bg-white px-3 py-2 rounded shadow-sm">Xem thêm</div>
                    </a>` : ''
            }
                </div>
            </div>
        `;

        container.append(html);
    });
}



$.ajax({
    type: "get",
    url: "/sukiennho",
    success: function (res) {
        renderSukiennho(res.data);
    },
    error: function () {
        alert('Đã có lỗi xảy ra khi tải sự kiện.');
    }
});


function renderSukiennho(sukiennhoArray) {
    const container = $('#sukien-container');
    container.empty();

    sukiennhoArray.forEach(sukien => {
        const formattedDate = formatDate(sukien.start_date);
        const html = `
            <div class="col-md-6 col-lg-6">
        <div class="d-flex" style="align-items: stretch;">

            <!-- Thay cột ngày bằng hình ảnh -->
            <div class="me-3 col-4 text-center d-flex flex-column" style="min-height: 150px; width: 200px;">
                <img src="images/20250110-cbo_2004.jpg" alt="Hình sự kiện"
                    style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <!-- Nội dung -->
            <div class="flex-grow-1 d-flex flex-column">
                <div class="mb-2 fw-semibold ellipsis-text two-line">
                    <a href="/su-kien/${sukien.id}" class="text-hover">
                        ${sukien.title}
                    </a>
                </div>
                <div class="ellipsis-text two-line mb-1">
                    <i class="fa-regular fa-clock"></i> Ngày diễn ra: ${formattedDate}
                </div>
                <div class="ellipsis-text two-line">
                    <i class="fa-solid fa-location-dot"></i> ${sukien.location}
                </div>
            </div>

        </div>
    </div>
        `;
        container.append(html);
    });
}

// thongbaonho
$.ajax({
    type: "get",
    url: "/thongbaonho", // API lấy danh sách thông báo
    success: function (res) {
        renderThongbaonho(res.data);
    },
    error: function () {
        alert('Không thể tải thông báo.');
    }
});

function createThongbaonho(item, offcanvasId) {
    const newBadge = item.new === 1 ? `<img src="/images/system/new.gif" alt="New" class="me-1">` : '';
    return `
        <li class="ellipsis-text two-line mb-4 noti-item"
            data-bs-toggle="offcanvas"
            data-bs-target="#${offcanvasId}">
            ${newBadge}${item.title}
        </li>
    `;
}



function createOffcanvas(id, title) {
    return `
        <div class="offcanvas offcanvas-end col-6" tabindex="-1" id="${id}"
            aria-labelledby="${id}-label" style="height: 100vh;">
            <div class="offcanvas-header">
                <h5 id="${id}-label">${title}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
            </div>
            <div class="offcanvas-body p-0" style="height: calc(100vh - 56px); overflow-y: auto;">
                <div id="${id}-pdf" class="p-3"></div>
            </div>
        </div>
    `;
}


function renderThongbaonho(data) {
    const container = $('#thongbaonho');
    const offcanvasWrapper = $('#offcanvas-wrapper');
    container.empty();
    offcanvasWrapper.empty();

    data.forEach(function (item) {
        const offcanvasId = `offcanvas${item.id}`;
        const pdfContainerId = `${offcanvasId}-pdf`;

        const itemHtml = createThongbaonho(item, offcanvasId);
        const offcanvasHtml = createOffcanvas(offcanvasId, item.title);

        container.append(itemHtml);
        offcanvasWrapper.append(offcanvasHtml);

        if (item.pdf_url) {
            renderPDF(item.pdf_url, pdfContainerId);
        }
    });
}

function renderPDF(pdfUrl, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = ''; // Clear trước khi render

    const loadingTask = pdfjsLib.getDocument(pdfUrl);
    loadingTask.promise.then(function (pdf) {
        const totalPages = pdf.numPages;

        for (let pageNumber = 1; pageNumber <= totalPages; pageNumber++) {
            pdf.getPage(pageNumber).then(function (page) {
                // Lấy viewport gốc với scale = 1
                const unscaledViewport = page.getViewport({ scale: 1 });
                const containerWidth = container.clientWidth;

                // Tự động scale theo chiều rộng container
                const scale = containerWidth / unscaledViewport.width;
                const viewport = page.getViewport({ scale });

                // Tạo canvas để vẽ
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                // CSS hiển thị đẹp
                canvas.style.width = '100%';
                canvas.style.marginBottom = '20px';
                canvas.style.display = 'block';

                container.appendChild(canvas);

                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };

                page.render(renderContext);
            });
        }
    }).catch(function (error) {
        console.error('Lỗi khi tải PDF:', error);
        container.innerHTML = `<p class="text-danger p-3">Không thể hiển thị file PDF.</p>`;
    });
}

// Chỉ gán 1 lần cho toàn bộ offcanvas
document.addEventListener('shown.bs.offcanvas', function (e) {
    const offcanvasId = e.target.id;
    const trigger = document.querySelector(`[data-bs-target="#${offcanvasId}"]`);
    const pdfUrl = trigger?.getAttribute('data-pdf-url');
    const pdfContainer = document.getElementById(`${offcanvasId}-pdf`);
    if (pdfUrl && pdfContainer) {
        loadPDF(pdfUrl, pdfContainer);
    }
});



// nganhnho
$.ajax({
    type: "get",
    url: "/nganhnho", // API lấy danh sách thông báo
    success: function (res) {
        renderNganhnho(res.data);
    },
    error: function () {
        alert('Không thể tải thông báo.');
    }
});


function renderNganhnho(data) {
    const container = $('.nganhSwiper .swiper-wrapper');
    container.empty();

    data.forEach(function (item) {
        const html = `
            <div class="swiper-slide col-lg-4 col-12">
                <a href="/cacnganh_chitiet/${item.id}">
                    <div class="nganh-item">
                        <img src="${item.image_url}" alt="">
                        <div class="education-program-item__info">
                            <h3 class="education-program-item__title two-lines">${item.name}</h3>
                        </div>
                    </div>
                </a>
            </div>
        `;
        container.append(html);
    });
}










