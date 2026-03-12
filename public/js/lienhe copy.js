$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});


// lienhe

function sendContactForm() {
    // Hiện loading ngay lập tức
    $('#loadingOverlay').show();

    // Xóa các lỗi cũ
    $('.text-danger').text('');

    var name = $('#HoTen').val();
    var phone = $('#SoDienThoai').val();
    var email = $('#Email').val();
    var title = $('#TieuDe').val();
    var message = $('#NoiDung').val();

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
            if (response.status === 'success') {
                toastr.success(response.message);

                // Reset form
                $('#HoTen, #SoDienThoai, #Email, #TieuDe, #NoiDung').val('');
            } else if (response.status === 'error') {
                toastr.error('Có lỗi xảy ra: ' + response.message);
            } else {
                // Hiển thị lỗi validation
                for (const key in response) {
                    $('#err_' + key).text(response[key][0]);
                }
            }
        },
        error: function (xhr, status, error) {
            toastr.error('Lỗi hệ thống: ' + error);
        },
        complete: function () {
            // Luôn ẩn loading sau khi xử lý xong
            setTimeout(() => $('#loadingOverlay').hide(), 500);
        }
    });
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