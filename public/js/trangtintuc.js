$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});



$.ajax({
    type: "get",
    url: "/news",  // Đảm bảo đường dẫn chính xác
    success: function (res) {
        if (res.data && res.data.length > 0) {
            renderNews(res.data); // Gọi hàm render với mảng tin tức
        } else {
            alert('Không có ngành nào.');
        }
    },
    error: function () {
        alert('Đã có lỗi xảy ra khi tải banner.');
    }
});

function renderNews(news) {
    const container = $('#news-list');
    container.empty();

    news.forEach(news => {
        const html = `
            <div class="divto col-lg-4 col-6">
                <div class="card news-item">
                    <a href="http://">
                        <div class="image-wrapper">
                            <img src="${news.image_url}" alt="Image">
                        </div>
                    </a>

                    <div class="card-content">
                        <a class="text-decoration-none  two-line ellipsis-text" href="">
                            <h5 class="text-hover">${news.title}</h5>
                        </a>
                        <div class="events-date">
                            <img alt="icon calendar DNC" style="height: 25px; width: 25px;" src="./images/system/calendar.png">
                            <div class="date" itemprop="startDate">${news.publish_date}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.append(html);
    });
}

