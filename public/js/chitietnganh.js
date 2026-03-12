$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
function getIdFromURL() {
    const pathParts = window.location.pathname.split('/');
    return pathParts[pathParts.length - 1]; // Lấy id từ phần cuối URL
}

const id = getIdFromURL(); // Lấy id từ URL

$.ajax({
    type: "GET",
    url: '/chitietnganh/' + id,  // Dùng id đã lấy từ URL

    success: function (res) {
        $('#tenNganh').text('Ngành ' + res.major.name); // Gán tên ngành vào phần tử có id 'tenNganh'
        renderBanner(res.major.banner);
        renderThongTinChung(res.major);
        renderPhuongThucXetTuyen(res.methods);
        renderGioiThieu(res.major.description, res.major.video);
        renderCoHoiViecLam(res.major.job_position, res.major.workplace_location);
    },
    error: function () {
        alert('Không thể tải dữ liệu chi tiết ngành.');
    }
});


// $.ajax({
//     type: "GET",
//     url: '/chitietnganh/' + id,

//     success: function (res) {
//         $('#tenNganh').text('Ngành ' + res.major.name); // <-- thêm dòng này
//         renderBanner(res.major.banner);
//         renderThongTinChung(res.major);
//         renderPhuongThucXetTuyen(res.methods);
//         renderGioiThieu(res.major.description, res.major.video);
//         renderCoHoiViecLam(res.major.job_position, res.major.workplace_location);
//     },
//     error: function () {
//         alert('Không thể tải dữ liệu chi tiết ngành.');
//     }
// });




function renderBanner(imageUrl) {
    if (imageUrl) {
        $('#bannerImage').attr('src', imageUrl);
    } else {
        $('#bannerImage').attr('src', './images/system/Rectangle_ngang.jpg'); // fallback nếu không có ảnh
    }
}





function renderThongTinChung(info) {
    const description = info.description.replace(/\n/g, '<br>');

    const html = `
        <li class="list-group-item"><strong>Tên ngành:</strong> ${info.name}</li>
        <li class="list-group-item"><strong>Thời gian đào tạo:</strong> ${info.duration} năm</li>
        <li class="list-group-item"><strong>Danh hiệu cấp bằng:</strong> ${info.degree}</li>
        <li class="list-group-item"><strong>Mã ngành tuyển sinh:</strong> ${info.code}</li>
    `;

    $('#listThongTinChung').html(html);
}






function renderPhuongThucXetTuyen(dsPhuongThuc) {
    const container = $('#accordionPhuongThuc');
    container.empty();

    dsPhuongThuc.forEach(function (item, index) {
        let methodHtml = `
            <div class="accordion-item">
                <h2 class="accordion-header" id="pt${index + 1}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse${index + 1}" aria-expanded="false" aria-controls="collapse${index + 1}">
                        ${item.method_title}
                    </button>
                </h2>
                <div id="collapse${index + 1}" class="accordion-collapse collapse" aria-labelledby="pt${index + 1}" data-bs-parent="#accordionPhuongThuc">
                    <div class="accordion-body">
        `;

        let contentHtml = '';

        // Chỉ hiển thị nếu có tổ hợp
        if (item.combinations && item.combinations.length > 0 && item.combinations[0].combination_code) {
            const allCombinations = item.combinations.map(function (combination) {
                const combos = combination.combination_subjects ? combination.combination_subjects.split(',').map(s => s.trim()) : [];
                return `${combos.join(', ')} (${combination.combination_code})`;
            }).join('; ');

            contentHtml += `<strong>Tổ hợp xét tuyển:</strong> ${allCombinations}<br>`;
        }

        // Chỉ hiển thị nếu có mô tả
        if (item.method_description) {
            contentHtml += `${item.method_description}<br>`;
        }

        // Nếu không có cả tổ hợp lẫn mô tả thì không hiển thị gì
        if (contentHtml === '') {
            contentHtml = '<em>Không có thông tin chi tiết.</em>';
        }

        methodHtml += contentHtml + '</div></div></div>';

        container.append(methodHtml);
    });
}






function renderGioiThieu(noi_dung, video_url) {
    // Thay thế các ký tự xuống dòng \n thành thẻ <br> để hiển thị xuống dòng
    const description = noi_dung.replace(/\n/g, '<br>');

    // Chèn video vào trang
    $('#gioiThieuVideo').html(`
        <video controls style="width: 100%;">
            <source src="${video_url}" type="video/mp4">
            Trình duyệt của bạn không hỗ trợ video.
        </video>
    `);

    // Chèn nội dung mô tả vào trang
    $('#gioiThieuText').html(description);
}



function renderCoHoiViecLam(viTriList, noiLamViecList) {
    $('#viTriViecLam').html(
        viTriList.split('\n').map(item => `<li>${item}</li>`).join("")
    );
    $('#noiLamViec').html(
        noiLamViecList.split('\n').map(item => `<li>${item}</li>`).join("")
    );
}

