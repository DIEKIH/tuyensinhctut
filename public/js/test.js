$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "GET",
        url: '/chitietnganh/1',
        success: function (res) {
            // Gọi các hàm render để hiển thị dữ liệu
            renderThongTinChung(res.major);
            renderPhuongThucXetTuyen(res.methods);
            renderGioiThieu(res.major.description, res.major.video); // video có thể là null
            renderCoHoiViecLam(res.major.job_position, res.major.workplace_location);
        },
        error: function () {
            alert('Không thể tải dữ liệu chi tiết ngành.');
        }
    });
});

function renderThongTinChung(info) {
    const html = `
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>Tên ngành:</strong> ${info.name}</li>
        <li class="list-group-item"><strong>Thời gian đào tạo:</strong> ${info.duration} năm</li>
        <li class="list-group-item"><strong>Danh hiệu cấp bằng:</strong> ${info.degree}</li>
        <li class="list-group-item"><strong>Ghi chú:</strong> ${info.description}</li>
        <li class="list-group-item"><strong>Mã ngành tuyển sinh:</strong> ${info.code}</li>
    </ul>
    `;
    $('#thongTinChung').html(html);
}

function renderPhuongThucXetTuyen(dsPhuongThuc) {
    const container = $('#accordionPhuongThuc');
    container.empty();

    // Duyệt qua các phương thức xét tuyển
    $.each(dsPhuongThuc, function (method_id, items) {
        let methodTitle = items[0].method_title;
        let methodDescription = items[0].method_description;
        let html = `
    <div class="accordion-item">
        <h2 class="accordion-header" id="pt${method_id}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapse${method_id}">
                ${methodTitle}
            </button>
        </h2>
        <div id="collapse${method_id}" class="accordion-collapse collapse" data-bs-parent="#accordionPhuongThuc">
            <div class="accordion-body">
                <strong>Tổ hợp môn:</strong> ${items[0].combination_subjects}
            </div>
        </div>
    </div>
    `;
        container.append(html);
    });
}

function renderGioiThieu(noi_dung, video_url) {
    if (video_url) {
        $('#gioiThieuVideo').html(`
                    <video controls style="width: 100%;">
                        <source src="${video_url}" type="video/mp4">
                        Trình duyệt của bạn không hỗ trợ video.
                    </video>
                `);
    }
    $('#gioiThieuText').text(noi_dung);
}

function renderCoHoiViecLam(viTriList, noiLamViecList) {
    $('#viTriViecLam').html(
        viTriList.split('\n').map(item => `<li>${item}</li>`).join("")
    );
    $('#noiLamViec').html(
        noiLamViecList.split('\n').map(item => `<li>${item}</li>`).join("")
    );
}