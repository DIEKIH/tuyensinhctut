@extends('admins.layouts.app')

@section('title', 'Quản lý Bài viết')

@section('css')
    {{-- DataTables CSS --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css"> --}}
    <link rel="stylesheet" href="/css/admins/baiviet.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap Datepicker -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">
    <!-- Select2 CSS/JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <h1 class="page-title">Danh sách Bài viết</h1>
            <button id="addBaiVietBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#baivietModal">
                Thêm Bài viết +
            </button>
        </div>
        <div class="table-responsive d-flex flex-column">
            {{-- Bảng DataTable --}}
            <table id="baivietTable" class="display table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data sẽ được load bằng Ajax --}}
                </tbody>
            </table>
        </div>
    </div>




    {{-- <div class="modal-content"> --}}
    <div class="modal fade" id="baivietModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header py-2 px-3">
                    <h5 class="modal-title">Tạo mới bài viết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <form id="postForm">
                        <input type="text" style="display:none;" name="postId" id="postId" value="">
                        <input type="hidden" name="lienquan" value="">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-2 left-col">
                                <div class="mb-3">
                                    <label class="form-label">Menu cấp 1<sup id="menucap1">*</sup></label>
                                    <select id="menu1" name="menu1" class="form-select form-select-sm short-dropdown">
                                        <option value="">Chọn menu cấp 1</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Menu cấp 2</label>
                                    <select id="menu2" name="menu2" class="form-select form-select-sm short-dropdown">
                                        <option value="">Chọn menu cấp 2</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Menu cấp 3</label>
                                    <select id="menu3" name="menu3" class="form-select form-select-sm short-dropdown">
                                        <option value="">Chọn menu cấp 3</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Loại tin<sup id="loaitinsup">*</sup></label>
                                    <select id="loaitin" name="loaitin" class="form-select form-select-sm short-dropdown">
                                        <option value="">Chọn loại tin</option>
                                        <option value="1">Loại tin 1</option>
                                        <option value="2">Loại tin 2</option>
                                        <option value="3">Loại tin 3</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Danh mục<sup id="danhmucsup">*</sup></label>
                                    <select id="danhmuc" name="danhmuc" class="form-select form-select-sm short-dropdown">

                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nổi bật<sup>*</sup></label>
                                    <select id="is_featured" name="is_featured"
                                        class="form-select form-select-sm short-dropdown">
                                        <option value="1">Nổi bật</option>
                                        <option value="0">Không nổi bật</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary col-12" data-bs-toggle="modal"
                                        data-bs-target="#modalLienQuan">
                                        <i class="fas fa-link"></i> Bài viết liên quan
                                    </button>
                                </div>

                            </div>

                            <!-- Right Column -->
                            <div class="col-md-10 right-col">
                                <div class="mb-3">
                                    <label class="form-label">Tiêu đề<sup>*</sup></label>
                                    <div class="form-control form-control-sm" id="titleInput" name="tieude"
                                        contenteditable="true" data-placeholder="Nhập tiêu đề"></div>
                                    <sup id="tieude"
                                        style="color:red;font-size:small;font-weight:light;position: absolute; top: 72px; right: 23px;"></sup>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tóm tắt<sup>*</sup></label>
                                    <textarea name="tomtat" class="form-control form-control-sm" rows="3" placeholder="Nhập tóm tắt"></textarea>
                                    <sup id="tomtat"
                                        style="color:red;font-size:small;font-weight:light;position: absolute; top: 185px; right: 23px;"></sup>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Tác giả<sup>*</sup></label>
                                        <div class="input-group input-group-sm">
                                            <select id="listtacgia" name="tacgia[]"
                                                class="form-select form-control select22"
                                                style="border: var(--bs-border-width) solid var(--bs-border-color);"
                                                multiple="multiple">

                                            </select>
                                            <sup id="tacgia"
                                                style="color:red;font-size:small;font-weight:light;position: absolute; top: 25px; right: 4px;"></sup>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ngày đăng<sup>*</sup></label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="text" id="datetimepicker" name="ngaydang"
                                                class="form-control form-control-sm" placeholder="Chọn ngày đăng"
                                                autocomplete="off"> <!-- ✅ Tắt gợi ý -->
                                        </div>
                                        <sup id="ngaydang"
                                            style="color:red;font-size:small;font-weight:light;position: absolute; top: 256px; right: 23px;"></sup>
                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Chọn hình ảnh<sup>*</sup></label>
                                        <input type="file" class="form-control form-control-sm" id="customFile"
                                            name="image" accept="image/*" onchange="previewImage(event)">
                                        <img id="imagePreview" class="img-preview img-fluid mt-2" src="#"
                                            alt="Image Preview" style="display: none;">
                                        <sup id="imagesup"
                                            style="color:red;font-size:small;font-weight:light;position: absolute; top: 355px; right: 520px;"></sup>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Chọn PDF<sup>*</sup></label>
                                        <input type="file" class="form-control form-control-sm" id="customPDF"
                                            name="file" accept="application/pdf" onchange="previewPDF(event)">
                                        <div id="pdf-preview" class="pdf-preview mt-2 small text-muted"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nội dung</label>
                                    <div id="summernote" name="noidung"></div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-outline-secondary btn-sm me-2"
                                        onclick="clearForm()">Clear</button>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="far fa-save me-1"></i> Lưu bài viết
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="modalLienQuan" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chọn bài viết liên quan</h5>

                    <!-- group nút bên phải -->
                    <div class="d-flex ms-auto align-items-center">
                        <!-- Nút Xác nhận màu đen -->
                        <button type="button" class="btn btn-dark btn-sm me-2" id="confirmLienQuan">
                            Xác nhận
                        </button>

                        <!-- Nút đóng -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>

                <div class="modal-body table-responsive d-flex flex-column">
                    <table id="tableLienQuan" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr></tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>





@endsection

@section('js')
    {{-- JS riêng --}}
    {{-- <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-vi-VN.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>

    <!-- Datepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.vi.min.js">
    </script>

    <script src="{{ asset('js/admins/baiviet.js') }}"></script>

@endsection
