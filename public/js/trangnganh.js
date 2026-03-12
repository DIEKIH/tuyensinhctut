$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});



$.ajax({
    type: "get",
    url: "/majors",  // Đảm bảo đường dẫn chính xác
    success: function (res) {
        if (res.data && res.data.length > 0) {
            renderMajors(res.data); // Gọi hàm render với mảng tin tức
        } else {
            alert('Không có ngành nào.');
        }
    },
    error: function () {
        alert('Đã có lỗi xảy ra khi tải banner.');
    }
});

function renderMajors(majors) {
    const container = $('#majors-list');
    container.empty();

    majors.forEach(major => {
        const html = `
            <a href="" class="col-lg-3 col-6">
                <div class="nganh-item">
                    <div class="image-wrapper">
                        <img src="${major.image_url}" alt="">
                    </div>
                    <div class="education-program-item__info">
                        <h3 class="education-program-item__title two-lines">
                            ${major.name}
                        </h3>
                    </div>
                </div>
            </a>
        `;
        container.append(html);
    });
}

