$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});


//#region Load tất cả bài viết

$.ajax({
    type: "get",
    url: "/admin/tatcabaiviet",
    success: function (res) {
        if (res.data && res.data.length) {
            renderBaivietTable(res.data);
        } else {
            toastr.warning("Không có bài viết nào.");
            $('#baivietTable').DataTable().clear().draw();
        }
    },
    error: function () {
        toastr.error('Không thể tải dữ liệu bài viết.');
    }
});

function renderBaivietTable(baivietArray) {
    $('#baivietTable').DataTable({
        destroy: true,
        data: baivietArray,
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'tieude', title: 'Tiêu đề' },
            { data: 'tacgia_ten', title: 'Tác giả' },
            { data: 'ngaydang', title: 'Ngày đăng' },
            { data: 'status', title: 'Trạng thái' },

            // 👉 Cột thao tác
            {
                data: null,
                title: 'Thao tác',
                orderable: false,
                render: function (data, type, row) {
                    return `
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-outline-primary edit-btn me-1" data-id="${row.id}" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="delete-btn btn btn-sm btn-outline-danger delete-btn" data-id="${row.id}" title="Xóa">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    `;
                }
            }


        ],
        scrollY: '58vh',
        scrollCollapse: true,
        paging: true,
        lengthMenu: [10, 25, 50, 100],
        pageLength: 10,
        fixedHeader: true,
        language: {
            lengthMenu: "Hiển thị &nbsp _MENU_ &nbsp bản ghi",
            search: "Tìm kiếm:",
            info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ bản ghi",
            paginate: {
                first: "Đầu",
                last: "Cuối",
                next: "Sau",
                previous: "Trước"
            },
            emptyTable: "Không có dữ liệu"
        }
    });
}

//#endregion



//#region Load bài viết cho modal con
$.ajax({
    type: "get",
    url: "/admin/tatcabaiviet",
    success: function (res) {
        renderLienQuanTable(res.data); // render bảng trong modal
    },
    error: function () {
        toastr.error('Không thể tải dữ liệu bài viết.');
    }
});


// Hàm render DataTable cho modal
function renderLienQuanTable(baivietArray) {
    $('#tableLienQuan').DataTable({
        destroy: true,
        data: baivietArray,
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'tieude', title: 'Tiêu đề' },
            { data: 'tacgia_ten', title: 'Tác giả' },
            {
                data: null,
                title: 'Chọn',
                orderable: true,
                className: "text-center",
                render: function (data, type, row) {
                    let isChecked = currentLienQuan.includes(row.id.toString());

                    if (type === 'sort') {
                        return isChecked ? 0 : 1;
                    }

                    return `<input type="checkbox" class="chk-lienquan" value="${row.id}" ${isChecked ? 'checked' : ''}>`;
                }
            }
        ],
        order: [[3, 'asc']],
        scrollY: '50vh',
        scrollCollapse: true,
        paging: true,
        lengthMenu: [5, 10, 25, 50],
        pageLength: 10,
        language: {
            lengthMenu: "Hiển thị _MENU_ bản ghi",
            search: "Tìm kiếm:",
            info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ bản ghi",
            paginate: {
                first: "Đầu",
                last: "Cuối",
                next: "Sau",
                previous: "Trước"
            },
            emptyTable: "Không có dữ liệu"
        }
        // ❌ XÓA drawCallback - không cần nữa
    });

    // ✅ Bắt sự kiện click checkbox để cập nhật currentLienQuan
    $('#tableLienQuan').off('change', '.chk-lienquan').on('change', '.chk-lienquan', function () {
        let id = $(this).val();

        if ($(this).is(':checked')) {
            // Thêm vào danh sách nếu chưa có
            if (!currentLienQuan.includes(id)) {
                currentLienQuan.push(id);
            }
        } else {
            // Xóa khỏi danh sách
            currentLienQuan = currentLienQuan.filter(item => item !== id);
        }
    });
}

$('#modalLienQuan').on('shown.bs.modal', function () {
    $('#tableLienQuan').DataTable().columns.adjust();
});

//#endregion




//#region Load modal cha
$(function () {
    // Summernote
    $('#summernote').summernote({
        height: 500,
        lang: 'vi-VN',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video', 'hr']],
            ['view', ['fullscreen', 'codeview', 'help']],
            ['misc', ['undo', 'redo']]
        ],
        fontNames: ['Arial', 'Tahoma', 'Times New Roman', 'Courier New', 'Helvetica', 'Verdana'],
        fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '24', '36', '48'],
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        disableDragAndDrop: true,
        callbacks: {
            onInit: function () {
                var $editor = $(this).next('.note-editor');
                var $editable = $editor.find('.note-editable');
                if ($editable.length && $editable.text().trim().length === 0) {
                    $editable.html('<p><br></p>');
                }
            }
        }
    });

    // Datepicker (gộp luôn container cho modal)
    $('#datetimepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        language: 'vi',
        orientation: 'bottom auto',
        todayBtn: 'linked',
        clearBtn: true,
        container: '#baivietModal', // quan trọng để không bay ra ngoài modal
        templates: {
            leftArrow: '<i class="fas fa-chevron-left"></i>',
            rightArrow: '<i class="fas fa-chevron-right"></i>'
        }
    });
});


// Preview ảnh
function previewImage(event) {
    const imagePreview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    if (file) {
        imagePreview.src = URL.createObjectURL(file);
        imagePreview.style.display = 'block';
    } else {
        imagePreview.style.display = 'none';
        imagePreview.src = '#';
    }
}

// Preview PDF
function previewPDF(event) {
    const pdfPreview = document.getElementById('pdf-preview');
    const file = event.target.files[0];

    if (!file) {
        pdfPreview.innerHTML = "Chưa chọn PDF";
        return;
    }

    const fileReader = new FileReader();
    fileReader.onload = function () {
        const typedarray = new Uint8Array(this.result);

        pdfjsLib.getDocument(typedarray).promise.then(function (pdf) {
            pdf.getPage(1).then(function (page) {
                const viewport = page.getViewport({ scale: 1 });
                const scale = 150 / viewport.height; // scale sao cho chiều cao = 150px
                const scaledViewport = page.getViewport({ scale });

                const canvas = document.createElement("canvas");
                const context = canvas.getContext("2d");
                canvas.height = scaledViewport.height;
                canvas.width = scaledViewport.width;

                page.render({
                    canvasContext: context,
                    viewport: scaledViewport
                }).promise.then(function () {
                    pdfPreview.innerHTML = "";
                    pdfPreview.appendChild(canvas);
                });
            });
        });
    };
    fileReader.readAsArrayBuffer(file);
}


// Clear form
function clearForm(showToast = true) {
    const form = $('#postForm');

    // 1️⃣ Reset toàn bộ input, select, textarea cơ bản
    form[0].reset();

    // 2️⃣ Clear các lỗi <sup> hoặc thông báo
    form.find('sup').text('');

    // 3️⃣ Clear Select2 (tác giả)
    form.find('.select22').val(null).trigger('change');

    // 4️⃣ Clear Summernote
    $('#summernote').summernote('code', '');

    // 5️⃣ Clear vùng contenteditable (tiêu đề)
    $('#titleInput').text('');

    // 6️⃣ Clear ảnh preview
    $('#imagePreview').hide().attr('src', '');

    // 7️⃣ Clear PDF preview
    $('#pdf-preview').text('Chưa chọn PDF');

    // 8️⃣ Clear hidden input (id, liên quan)
    $('#postId').val('');
    $('input[name="lienquan"]').val('');

    // 9️⃣ Reset datetimepicker (nếu có plugin datepicker)
    $('#datetimepicker').val('');

    // 🔟 Reset select menu cấp 1-2-3, loại tin, danh mục
    $('#menu1, #menu2, #menu3, #loaitin, #danhmuc').val('').trigger('change');
    if (showToast && typeof toastr !== 'undefined') {
        toastr.info('Form đã được làm mới!');
    }
}

$('#baivietModal').on('hidden.bs.modal', function () {
    if (!isChildModalOpen) {
        clearForm(false); // ❌ Không hiện thông báo
    }
});



// Close
function closePopup() {
    window.location.href = '#';
}

$('.short-dropdown').select2({
    width: '100%',
    dropdownParent: $('#baivietModal .modal-body'),
    minimumResultsForSearch: 0 // ẩn thanh tìm kiếm
});

$('#baivietModal .modal-body').on('scroll', function () {
    $('.short-dropdown').each(function () {
        if ($(this).data('short-dropdown')) {
            $(this).data('short-dropdown').dropdown._positionDropdown();
        }
    });
});

// Submit form
// document.getElementById('postForm').addEventListener('submit', function (e) {
//     e.preventDefault();
//     alert("Bài viết đã được lưu!");
// });

//#endregion



// Sửa bài viết
$('#baivietTable').on('click', '.edit-btn', function () {
    let postId = $(this).data('id');

    $.ajax({
        type: "get",
        url: "/admin/baiviet/" + postId,
        success: function (res) {
            // if (!res.data) {
            //     toastr.warning('Không tìm thấy dữ liệu bài viết.');
            //     return;
            // }

            let bv = res.data;

            // --- Clear trước ---
            $('#imagePreview').hide().attr('src', '');
            $('#pdf-preview').html('');

            // Gán vào các input
            $('#postId').val(bv.id);
            $('#titleInput').html(bv.tieude);
            $('textarea[name="tomtat"]').val(bv.tomtat);
            $('#loaitin').val(bv.loaitin).trigger('change');
            $('#danhmuc').val(bv.danhmuc).trigger('change');
            $('#is_featured').val(bv.is_featured).trigger('change');
            $('#datetimepicker').val(bv.ngaydang);

            if (bv.menu1_id) {
                $('#menu1').val(bv.menu1_id).trigger('change');
            }

            // Menu cấp 2
            if (bv.menu2_id) {
                $('#menu2').val(bv.menu2_id).trigger('change');
            }

            // Menu cấp 3
            if (bv.menu3_id) {
                $('#menu3').val(bv.menu3_id).trigger('change');
            }
            // Tác giả
            if (bv.tacgia_ids) {
                let ids = bv.tacgia_ids.split(',');
                $('.select22').val(ids).trigger('change');
            }



            // Ảnh
            if (bv.image_url) {
                $('#imagePreview').attr('src', bv.image_url).show();
            }

            // PDF
            if (bv.file_url) {
                $('#pdf-preview').html(`<a href="${bv.file_url}" target="_blank">Xem PDF hiện có</a>`);
            }

            // Nội dung
            $('#summernote').summernote('code', bv.noidung || '');

            // Hiện modal
            $('#baivietModal').modal('show');

            console.log("Image URL:", bv.image_url);

        },

        error: function () {
            toastr.error('Không thể tải dữ liệu bài viết.');
        }
    });
});





// Lưu bài viết
$(document).ready(function () {
    $('#postForm').on('submit', function (e) {
        e.preventDefault();

        // Xóa lỗi cũ
        $('sup[id]').text("");

        // Lấy dữ liệu từ form
        let form = new FormData(this);
        form.set('tieude', $('#titleInput').text().trim());
        form.set('noidung', $('#summernote').summernote('code'));

        $.ajax({
            url: '/admin/store',
            type: 'POST',
            data: form,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                console.log('Response:', res);

                if (res.status === false && res.errors) {
                    // ====== Hiển thị từng lỗi cụ thể ======
                    if (res.errors['ngaydang']) $('#ngaydang').text('* ' + res.errors['ngaydang'][0]);
                    if (res.errors['tieude']) $('#tieude').text('* ' + res.errors['tieude'][0]);
                    if (res.errors['tomtat']) $('#tomtat').text('* ' + res.errors['tomtat'][0]);
                    if (res.errors['tacgia']) $('#tacgia').text('* ' + res.errors['tacgia'][0]);
                    if (res.errors['menu1']) $('#menucap1').text('* ' + res.errors['menu1'][0]);
                    if (res.errors['loaitin']) $('#loaitinsup').text('* ' + res.errors['loaitin'][0]);
                    if (res.errors['danhmuc']) $('#danhmucsup').text('* ' + res.errors['danhmuc'][0]);
                    if (res.errors['image']) $('#imagesup').text('* ' + res.errors['image'][0]);

                    toastr.error('Có lỗi trong dữ liệu, vui lòng kiểm tra form.');
                }
                else if (res.success) {
                    toastr.success(res.message, 'Thành công');

                    // Đóng modal
                    $('#baivietModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('overflow', 'auto');

                    // Reset form
                    clearForm(false);

                    // Reload table nếu có
                    if (typeof renderBaivietTable === 'function') {
                        $.get('/admin/tatcabaiviet', function (res) {
                            renderBaivietTable(res.data);
                        });
                    }
                }
            },
            error: function (xhr) {
                console.log('Error:', xhr.responseText);
                toastr.error('Không thể lưu bài viết. Kiểm tra console.');
            }
        });
    });
});



// Xóa bài viết


$(document).ready(function () {
    $('#baivietTable').on('click', '.delete-btn', function () {
        let baivietId = $(this).data('id');

        Swal.fire({
            title: 'Xóa bài viết?',
            text: "Bạn có chắc chắn muốn xóa bài viết này?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/baiviet/' + baivietId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res.success) {
                            toastr.success('Bài viết đã được xóa thành công');
                            // Reload bảng
                            $.get('/admin/tatcabaiviet', function (res) {
                                renderBaivietTable(res.data);
                            });
                        } else {
                            toastr.error('Không thể xóa bài viết');
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        toastr.error('Đã xảy ra lỗi khi xóa bài viết');
                    }
                });
            }
        });
    });
});










//#region Load menu

$.ajax({
    type: "get",
    url: "/admin/menus",
    success: function (res) {
        if (res.success) {
            loadMenuCap1(res.data); // truyền dữ liệu vào hàm load menu cấp 1
        }
    },
    error: function () {
        toastr.error("Không thể tải danh sách menu. Vui lòng thử lại sau!");
    }
});

// Hàm load cấp 1
function loadMenuCap1(data) {
    let $menu1 = $('#menu1');
    $menu1.empty().append('<option value="">Chọn menu cấp 1</option>');
    data.forEach(function (item) {
        let option = new Option(item.name, item.id, false, false);
        $(option).data("children", item.children || []);
        $menu1.append(option);
    });
    $menu1.trigger('change');
}

// Hàm load cấp 2
function loadMenuCap2(data) {
    let $menu2 = $('#menu2');
    $menu2.empty().append('<option value="">Chọn menu cấp 2</option>');
    data.forEach(function (item) {
        let option = new Option(item.name, item.id, false, false);
        $(option).data("children", item.children || []);
        $menu2.append(option);
    });
    $menu2.trigger('change');
}

// Hàm load cấp 3
function loadMenuCap3(data) {
    let $menu3 = $('#menu3');
    $menu3.empty().append('<option value="">Chọn menu cấp 3</option>');
    data.forEach(function (item) {
        let option = new Option(item.name, item.id, false, false);
        $menu3.append(option);
    });
    $menu3.trigger('change');
}

// Xử lý sự kiện thay đổi
$('#menu1').on('change', function () {
    let children = $(this).find("option:selected").data("children") || [];
    loadMenuCap2(children);
    $('#menu3').empty().append('<option value="">Chọn menu cấp 3</option>').trigger('change');
});

$('#menu2').on('change', function () {
    let children = $(this).find("option:selected").data("children") || [];
    loadMenuCap3(children);
});
//#endregion



//#region Load tác giả

$(function () {
    $('.select22').select2({
        placeholder: "Chọn tác giả",
        width: '100%' // đảm bảo select2 chiếm đầy đủ chiều rộng của container
    });
});

$.ajax({
    type: "get",
    url: "/admin/tacgia", // route Laravel trả về getTacgia()
    success: function (res) {
        if (res.success) {
            loadTacgia(res.data);
        }
    },
    error: function () {
        toastr.error("Không thể tải danh sách tác giả. Vui lòng thử lại sau!");
    }
});


function loadTacgia(data) {
    let $tg = $('#listtacgia'); // select có name="tacgia[]"
    $tg.empty();

    data.forEach(function (item) {
        let option = new Option(item.ten, item.id, false, false);
        $tg.append(option);
    });

    // Làm mới select2 (nếu có dùng)
    $tg.trigger('change');
}

//#endregion


//#region Load danh mục
$.ajax({
    type: "get",
    url: "/admin/danhmuc",   // route Laravel trả về getDanhmuc()
    success: function (res) {
        if (res.success) {
            loadDanhmuc(res.data);
        }
    },
    error: function () {
        toastr.error("Không thể tải danh mục. Vui lòng thử lại sau!");
    }
});


function loadDanhmuc(data) {
    let $dm = $('#danhmuc');
    $dm.empty().append('<option value="">Chọn danh mục</option>');
    data.forEach(function (item) {
        let option = new Option(item.tendanhmuc, item.id, false, false);
        $dm.append(option);
    });
    $dm.trigger('change');
}
//#endregion






//#region Load modal con

// Khi mở modal con → ẨN modal cha
let isChildModalOpen = false;
let currentLienQuan = [];

// Khi nhấn sửa bài viết
$('#baivietTable').on('click', '.edit-btn', function () {
    let postId = $(this).data('id');

    $.ajax({
        type: "get",
        url: "/admin/baiviet/" + postId,
        success: function (res) {
            let bv = res.data;

            // --- Lưu danh sách bài viết liên quan ---
            currentLienQuan = [];
            if (bv.bv_lienquan) {
                currentLienQuan = bv.bv_lienquan.split(',').map(id => id.trim());
                $('input[name="lienquan"]').val(currentLienQuan.join(','));
            }

            // ... các đoạn code fill dữ liệu khác ...

            $('#baivietModal').modal('show');
        },
        error: function () {
            toastr.error('Không thể tải dữ liệu bài viết.');
        }
    });
});


// 👉 Khi mở modal “Bài viết liên quan”
$('#modalLienQuan').on('show.bs.modal', function () {
    // Ẩn modal cha
    isChildModalOpen = true;
    $('#baivietModal').modal('hide');
    $(this).data('from-parent', true);

    // 🔹 Bỏ tick tất cả trước
    $('.chk-lienquan').prop('checked', false);

    // 🔹 Tick lại các checkbox trong danh sách currentLienQuan
    currentLienQuan.forEach(id => {
        $('.chk-lienquan[value="' + id + '"]').prop('checked', true);
    });
});

// Khi modal con mở xong
$('#modalLienQuan').on('show.bs.modal', function () {
    isChildModalOpen = true;
    $('#baivietModal').modal('hide');
    $(this).data('from-parent', true);

    // ✅ Load lại dữ liệu và render bảng
    $.ajax({
        type: "get",
        url: "/admin/tatcabaiviet",
        success: function (res) {
            renderLienQuanTable(res.data);
        },
        error: function () {
            toastr.error('Không thể tải dữ liệu bài viết.');
        }
    });
});


// Khi modal con đóng
$('#modalLienQuan').on('hidden.bs.modal', function () {
    if ($(this).data('from-parent')) {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        setTimeout(() => $('#baivietModal').modal('show'), 100);
        $(this).data('from-parent', false);
        isChildModalOpen = false;
    }
});


// 👉 Nút “Xác nhận”
$('#confirmLienQuan').on('click', function () {
    let selectedIds = $('.chk-lienquan:checked').map(function () {
        return $(this).val();
    }).get();

    $('#postForm').find('input[name="lienquan"]').val(selectedIds.join(','));
    currentLienQuan = selectedIds; // Cập nhật lại danh sách hiện tại

    closeModalLienQuan();
});


// 👉 Hàm đóng modal con
function closeModalLienQuan() {
    $('#modalLienQuan').data('from-parent', true);
    $('#modalLienQuan').modal('hide');
}



//#endregion




//#region Mới: Gợi ý tiêu đề và tóm tắt
// CSS cho loading overlay

// Event handler cho nút gợi ý
$('#suggestBtn').on('click', function (e) {
    e.preventDefault();
    const $btn = $(this);

    // Disable button và đổi text
    $btn.prop('disabled', true).text('Đang gợi ý...');

    // Hiện loading overlay
    $('#loadingOverlay').addClass('show');

    // Lấy nội dung từ Summernote (HTML) -> chuyển thành text
    let contentHtml = $('#summernote').summernote('code') || '';
    // Loại bỏ thẻ để model đọc được text thuần
    let contentText = $('<div>').html(contentHtml).text().trim();

    // Kiểm tra nội dung rỗng
    if (!contentText) {
        toastr.warning('Vui lòng nhập nội dung trước khi gợi ý.');
        $('#loadingOverlay').removeClass('show');
        $btn.prop('disabled', false).text('Gợi ý');
        return;
    }

    $.ajax({
        url: '/admin/suggest',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ content: contentText }),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            if (res.success) {
                $('#titleInput').text(res.title || '');
                $('textarea[name="tomtat"]').val(res.summary || '');
                toastr.success('Đã tạo gợi ý tiêu đề và tóm tắt!');
            } else {
                toastr.warning(res.message || 'Không lấy được gợi ý.');
                console.log(res);
            }
        },
        error: function (xhr) {
            let msg = 'Lỗi khi lấy gợi ý.';
            if (xhr.responseJSON && xhr.responseJSON.message)
                msg += '\n' + xhr.responseJSON.message;
            toastr.error(msg);
            console.log(xhr.responseText);
        },


        complete: function () {
            // Ẩn loading overlay
            $('#loadingOverlay').removeClass('show');

            // Enable button và khôi phục text
            $btn.prop('disabled', false).text('Gợi ý');
        }
    });
});
//#endregion