$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});


// lienhe

function sendContactForm() {
    $('.text-danger').text(''); // Xóa thông báo lỗi trước đó

    var name = $('#HoTen').val().trim();
    var phone = $('#SoDienThoai').val().trim();
    var email = $('#Email').val().trim();
    var title = $('#TieuDe').val().trim();
    var message = $('#NoiDung').val().trim();

    let hasError = false;

    if (!name) {
        $('#err_HoTen').text('Vui lòng nhập họ tên');
        hasError = true;
    } else if (!/^[\p{L}0-9 _-]+$/u.test(name)) {
        $('#err_HoTen').text('Họ và tên không chứa ký tự đặc biệt');
        hasError = true;
    }

    // Số điện thoại: bắt đầu bằng 0, đủ 10 số
    if (!phone) {
        $('#err_SoDienThoai').text('Vui lòng nhập số điện thoại');
        hasError = true;
    } else if (!/^0\d{9}$/.test(phone)) {
        $('#err_SoDienThoai').text('Số điện thoại phải bắt đầu bằng 0 và gồm 10 chữ số');
        hasError = true;
    }

    // Email: bắt buộc, đúng định dạng
    if (!email) {
        $('#err_Email').text('Vui lòng nhập email');
        hasError = true;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        $('#err_Email').text('Email không hợp lệ');
        hasError = true;
    }

    // Tiêu đề: bắt buộc, không ký tự đặc biệt
    if (!title) {
        $('#err_TieuDe').text('Vui lòng nhập tiêu đề');
        hasError = true;
    } else if (!/^[\p{L}0-9 _-]+$/u.test(title)) {
        $('#err_TieuDe').text('Tiêu đề không được chứa ký tự đặc biệt');
        hasError = true;
    }

    // Nội dung: bắt buộc, không chứa ký tự đặc biệt nguy hiểm
    if (!message) {
        $('#err_NoiDung').text('Vui lòng nhập nội dung');
        hasError = true;
    } else if (!/^[\p{L}0-9 _.,!?()"\r\n-]+$/u.test(message)) {
        $('#err_NoiDung').text('Nội dung không được chứa ký tự đặc biệt');
        hasError = true;
    }

    if (hasError) return;

    // Chỉ khi không có lỗi mới hiện overlay và gửi AJAX
    $('#loadingOverlay').show();

    // Gửi AJAX sau 500ms
    setTimeout(() => {
        $.ajax({
            type: "POST",
            url: "/send",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                HoTen: name,
                SoDienThoai: phone,
                Email: email,
                TieuDe: title,
                NoiDung: message
            },
            success: function (response) {
                $('#loadingOverlay').hide();

                switch (response.status) {
                    case 'success':
                        toastr.success(response.message);
                        $('#HoTen, #SoDienThoai, #Email, #TieuDe, #NoiDung').val('');
                        break;

                    case 'error':
                        toastr.error('Có lỗi xảy ra: ' + response.message);
                        break;

                    default:
                        var keys = Object.keys(response);
                        for (var i = 0; i < keys.length; i++) {
                            var value = response[keys[i]];
                            $('#err_' + keys[i]).text(value[0]);
                        }
                        break;
                }
            },
            error: function (xhr, status, error) {
                $('#loadingOverlay').hide();
                toastr.error('Lỗi hệ thống: ' + error);
            }
        });
    }, 500);
}





$(document).ready(function () {
    $('#HoTen, #SoDienThoai, #Email, #TieuDe, #NoiDung').on('input', function () {
        let id = $(this).attr('id');
        $('#err_' + id).text('');
    });
});

// $(document).ready(function () {
//     $('#submitBtn').on('click', function () {
//         console.log("Nút submit đã được bấm");
//         sendContactForm();
//     });
// });

// $.ajax({
//     type: "get",
//     url: "/contact_submit",  // URL gọi đến controller Laravel
//     data: {
//         _token: $('meta[name="csrf-token"]').attr('content'), // Đảm bảo gửi CSRF token
//         HoTen: name,
//         SoDienThoai: phone,
//         Email: email,
//         TieuDe: title,
//         NoiDung: message
//     },
//     success: function (response) {
//         $('#modal_event').hide();
//         switch (response.status) {
//             case 'success':
//                 toastr.success('Thông tin đã được gửi thành công');
//                 // Nếu bạn cần làm gì đó sau khi gửi thành công (reload bảng chẳng hạn)
//                 // location.reload(); hoặc sử dụng một method reload tùy chỉnh
//                 break;
//             case 'error':
//                 toastr.error('Có lỗi xảy ra: ' + response.message);
//                 break;
//             default:
//                 // Nếu response chứa lỗi validation
//                 var keys = Object.keys(response.errors);
//                 for (var i = 0; i < keys.length; i++) {
//                     var value = response.errors[keys[i]];
//                     $('#err_' + keys[i]).text(value);
//                 }
//                 break;
//         }
//     },
//     error: function (xhr, status, error) {
//         $('#modal_event').hide();
//         toastr.error('Lỗi hệ thống: ' + error);
//     }
// });