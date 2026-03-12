@php
    $ngaydang = \Carbon\Carbon::parse($baiviet->ngaydang)->format('d/m/Y');
@endphp

@switch($baiviet->loaitin)
    @case(1)
        @php
            $offcanvasId = 'offcanvas-' . $baiviet->id; // id duy nhất cho từng bài viết
            $views = $baiviet->views ?? 0;
            $isNewBadge = $baiviet->new == 1 ? '<img src="/images/system/new.gif" alt="New" class="me-1">' : '';
            $publishDate = \Carbon\Carbon::parse($baiviet->ngaydang)->format('d/m/Y');
            $pdfUrl = !empty($baiviet->file_url) ? asset($baiviet->file_url) : null;
        @endphp

        {{-- Khu vực hiển thị tiêu đề --}}
        <div class="announcement-clickable mb-4">
            <div class="row">
                <div class="col-md-10 d-flex">
                    <div class="col-md-12 d-flex flex-column justify-content-between">
                        <div>
                            <h2 class="announcement-title" data-bs-toggle="offcanvas" data-bs-target="#{{ $offcanvasId }}">
                                <a href="{{ url(menu_full_slug($baiviet->idmenu) . '/' . $baiviet->slug) }}"
                                    class="text-decoration-none text-dark">
                                    {!! $isNewBadge !!}{{ $baiviet->tieude }}
                                </a>
                            </h2>

                        </div>
                    </div>
                </div>
                <div class="col-md-2 d-flex flex-column justify-content-center align-items-start">
                    <div class="mt-auto">
                        <div class="announcement-views mb-1 text-dark">
                            <i class="fas fa-eye me-1"></i>{{ $views }} lượt xem
                        </div>
                        <div class="announcement-date text-dark">
                            <i class="fas fa-calendar-alt me-1 mt-2"></i>{{ $publishDate }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        {{-- Offcanvas hiển thị PDF --}}
        {{-- <div class="offcanvas offcanvas-end col-6" tabindex="-1" id="{{ $offcanvasId }}"
            aria-labelledby="{{ $offcanvasId }}-label" style="height: 100vh;" data-pdf-url="{{ $pdfUrl }}">

            <div class="offcanvas-header">
                <h5 id="{{ $offcanvasId }}-label">{{ $baiviet->tieude }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
            </div>

            <div class="offcanvas-body p-0" style="height: calc(100vh - 56px); overflow-y: auto;">
                <div id="{{ $offcanvasId }}-pdf" class="p-3">
                    @if ($pdfUrl)
                        <p>Đang tải file PDF...</p>
                    @else
                        <p class="text-danger">Không có file PDF đính kèm.</p>
                    @endif
                </div>
            </div>
        </div> --}}
    @break

    @case(2)
        <div class="divto col-lg-4 col-6">
            <div class="card news-item">
                <a href="{{ url(menu_full_slug($baiviet->idmenu) . '/' . $baiviet->slug) }}">
                    <div class="image-wrapper">
                        <img src="{{ $baiviet->image_url }}" alt="Image">
                    </div>
                </a>

                <div class="card-content">
                    <a class="text-decoration-none two-line ellipsis-text center-title"
                        href="{{ route('page.show', menu_full_slug($baiviet->idmenu) . '/' . $baiviet->slug) }}">
                        <h5 class="text-hover">{{ $baiviet->tieude }}</h5>
                    </a>
                    <div class="events-date">
                        <img alt="icon calendar DNC" style="height: 25px; width: 25px;" src="./images/system/calendar.png">
                        <div class="date" itemprop="startDate">{{ $baiviet->ngaydang }}</div>
                    </div>
                </div>
            </div>
        </div>
    @break

    @default
    @break
@endswitch
