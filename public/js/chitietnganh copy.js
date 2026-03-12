$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});


// $.ajax({
//     type: "GET",
//     url: "/chitietnganh/id",
//     success: function (res) {
//         renderThongTinChung(res.thong_tin_chung);
//         renderPhuongThucXetTuyen(res.phuong_thuc_xet_tuyen);
//         renderGioiThieu(res.gioi_thieu, res.video_url);
//         renderCoHoiViecLam(res.vi_tri_viec_lam, res.noi_lam_viec);
//     },
//     error: function () {
//         alert('Không thể tải dữ liệu chi tiết ngành.');
//     }
// });

const majorId = 1;  // Giả sử bạn đang làm việc với ngành có ID là 1

$.ajax({
    type: "GET",
    url: `/chitietnganh/${majorId}`,  // Gọi đúng URL với ID ngành
    success: function (res) {
        renderThongTinChung(res.thong_tin_chung);
        renderPhuongThucXetTuyen(res.phuong_thuc_xet_tuyen);
        renderGioiThieu(res.gioi_thieu, res.video_url);
        renderCoHoiViecLam(res.vi_tri_viec_lam, res.noi_lam_viec);
    },
    error: function () {
        alert('Không thể tải dữ liệu chi tiết ngành.');
    }
});

function renderThongTinChung(info) {
    const html = `
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Tên ngành:</strong> ${info.ten_nganh}</li>
            <li class="list-group-item"><strong>Thời gian đào tạo:</strong> ${info.thoi_gian}</li>
            <li class="list-group-item"><strong>Danh hiệu cấp bằng:</strong> ${info.danh_hieu}</li>
            <li class="list-group-item"><strong>Ghi chú:</strong> ${info.ghi_chu}</li>
            <li class="list-group-item"><strong>Mã ngành tuyển sinh:</strong> ${info.ma_nganh}</li>
        </ul>
    `;
    $('#thongTinChung').html(html);
}


function renderPhuongThucXetTuyen(dsPhuongThuc) {
    const container = $('#accordionPhuongThuc');
    container.empty();
    dsPhuongThuc.forEach(function (item, index) {
        const html = `
        <div class="accordion-item">
            <h2 class="accordion-header" id="pt${index}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse${index}">
                    ${item.tieu_de}
                </button>
            </h2>
            <div id="collapse${index}" class="accordion-collapse collapse" data-bs-parent="#accordionPhuongThuc">
                <div class="accordion-body">${item.noi_dung}</div>
            </div>
        </div>`;
        container.append(html);
    });
}


function renderGioiThieu(noi_dung, video_url) {
    $('#gioiThieuVideo').html(`
        <video controls style="width: 100%;">
            <source src="${video_url}" type="video/mp4">
            Trình duyệt của bạn không hỗ trợ video.
        </video>
    `);
    $('#gioiThieuText').text(noi_dung);
}

function renderCoHoiViecLam(viTriList, noiLamViecList) {
    $('#viTriViecLam').html(
        viTriList.map(item => `<li>${item}</li>`).join("")
    );
    $('#noiLamViec').html(
        noiLamViecList.map(item => `<li>${item}</li>`).join("")
    );
}
