@php
    use Illuminate\Support\Facades\File;
@endphp

@extends('users.layouts.app')

@section('title', 'Admin Dashboard')

@section('css')
    {{-- CSS riêng cho trang này (nếu cần) --}}
    <!-- Bootstrap CSS -->
    <!-- Summernote CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="{{ asset('css/chitiet.css') }}">

@endsection

@section('content')
    <div class="parent">
        <!-- div1 -->
        @php
            $offcanvasId = 'offcanvas-' . $post->id; // ID duy nhất
            $pdfUrl = !empty($post->file_url) ? asset($post->file_url) : null;
        @endphp

        <div class="div1">
            <div class="page-container">
                <!-- nội dung bài viết -->
                <h1 class="main-title">{{ $post->tieude }}</h1>
                <div class="meta-info">
                    <div class="meta-item"><i class="fas fa-calendar-alt"></i>{{ $post->ngaydang }}</div>

                    <div class="meta-item"><i class="fas fa-user"></i>{{ $tacgia }}</div>

                    <div class="meta-item"><i class="fas fa-eye"></i>{{ number_format($post->views) }} lượt xem</div>
                </div>
                <div class="post-content">{!! $post->noidung !!}</div>
                @if ($post->file_url)
                    <div class="mb-3">
                        <a class="btn-document" data-bs-toggle="offcanvas" href="#{{ $offcanvasId }}">
                            <i class="fas fa-file-pdf"></i>
                            Xem tài liệu đính kèm
                        </a>
                    </div>


                    <div class="offcanvas offcanvas-end col-6" tabindex="-1" id="{{ $offcanvasId }}"
                        aria-labelledby="{{ $offcanvasId }}-label" style="height: 100vh;"
                        data-pdf-url="{{ $pdfUrl }}">

                        <div class="offcanvas-header">
                            <h5 id="{{ $offcanvasId }}-label">{{ $post->tieude }}</h5> <!-- sửa ở đây -->
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
                    </div>
                @endif

            </div>

            @if (isset($nextPost))
                @php
                    // Kiểm tra ảnh của bài viết kế tiếp
                    $nextImage = $nextPost->image_url;
                    $defaultImage = asset('images/system/Rectangle_3897.jpg');

                    if (empty($nextImage) || !File::exists(public_path($nextImage))) {
                        $nextImage = $defaultImage;
                    } else {
                        $nextImage = asset($nextImage);
                    }

                    // ✅ FIX 1: Tạo URL đầy đủ cho bài viết kế tiếp
                    $nextUrl = $nextPost->menuslug . '/' . $nextPost->slug;
                @endphp

                <div class="next-news mt-4">
                    <div class="news-item next-news-horizontal">
                        <a href="{{ route('page.show', $nextUrl) }}">
                            <img src="{{ $nextImage }}" alt="{{ $nextPost->tieude }}">
                            <div class="content">
                                <h4>{{ $nextPost->tieude }}</h4>
                                <p>{{ Str::limit(strip_tags($nextPost->noidung), 100) }}</p>
                                <div class="news-date">
                                    <i class="fas fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($nextPost->ngaydang)->format('d/m/Y') }}
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

        </div>


        <!-- div2 -->
        <div class="div2">
            <h2 class="section-title">{{ $menu->name ?? 'Không rõ menu' }}</h2>
            <div class="suggested-news-container">
                @forelse ($baivietCungMenu as $item)
                    @php
                        // Nếu có image_url thì kiểm tra file có tồn tại không
                        $imageUrl = $item->image_url;
                        $defaultImage = asset('images/system/Rectangle_3897.jpg');

                        if (empty($imageUrl) || !File::exists(public_path($imageUrl))) {
                            $imageUrl = $defaultImage;
                        } else {
                            $imageUrl = asset($imageUrl);
                        }

                        // ✅ FIX 2: Tạo URL đầy đủ cho bài viết cùng menu
                        $articleUrl = $item->menuslug . '/' . $item->slug;
                    @endphp

                    <div class="news-item">
                        <img src="{{ $imageUrl }}" alt="{{ $item->tieude }}">
                        <div class="news-content">
                            <h4>
                                <a href="{{ route('page.show', $articleUrl) }}">{{ $item->tieude }}</a>
                            </h4>
                            <p>{{ Str::limit(strip_tags($item->noidung), 80) }}</p>
                            <div class="news-date">
                                <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($item->ngaydang)->format('d/m/Y') }}
                                <span class="news-menu"> | {{ $item->tenmenu }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Không có bài viết gợi ý.</p>
                @endforelse
            </div>
        </div>




        <!-- div3 -->
        <div class="div3">
            <h2 class="section-title">Tin Tức Liên Quan</h2>
            <div class="related-grid">
                @forelse ($relatedPosts as $rel)
                    @php
                        $relImage = $rel->image_url;
                        $defaultImage = asset('images/system/Rectangle_3897.jpg');

                        if (empty($relImage) || !File::exists(public_path($relImage))) {
                            $relImage = $defaultImage;
                        } else {
                            $relImage = asset($relImage);
                        }

                        // ✅ FIX 3: Tạo URL đầy đủ cho bài viết liên quan
                        $relatedUrl = $rel->menuslug . '/' . $rel->slug;
                    @endphp

                    <div class="news-item">
                        <a href="{{ route('page.show', $relatedUrl) }}">
                            <img src="{{ $relImage }}" alt="{{ $rel->tieude }}">
                            <h4>{{ $rel->tieude }}</h4>
                            <p>{{ Str::limit(strip_tags($rel->noidung), 80) }}</p>
                            <div class="news-date">
                                <i class="fas fa-clock"></i>
                                {{ \Carbon\Carbon::parse($rel->ngaydang)->format('d/m/Y') }}
                            </div>
                        </a>
                    </div>
                @empty
                    <p>Không có bài viết liên quan.</p>
                @endforelse
            </div>
        </div>



        <!-- div4 -->
        <div class="div4">
            <h2 class="section-title"> Tin Tức Nổi Bật</h2>
            <div class="featured-grid">
                @forelse ($featuredPosts as $feat)
                    @php
                        $featImage = $feat->image_url;
                        $defaultImage = asset('images/system/Rectangle_3897.jpg');

                        if (empty($featImage) || !File::exists(public_path($featImage))) {
                            $featImage = $defaultImage;
                        } else {
                            $featImage = asset($featImage);
                        }

                        // ✅ FIX 4: Tạo URL đầy đủ cho tin nổi bật
                        $featuredUrl = $feat->menuslug . '/' . $feat->slug;
                    @endphp

                    <div class="news-item">
                        <a href="{{ route('page.show', $featuredUrl) }}">
                            <img src="{{ $featImage }}" alt="{{ $feat->tieude }}">
                            <h4>{{ $feat->tieude }}</h4>
                            <p>{{ Str::limit(strip_tags($feat->noidung), 80) }}</p>
                            <div class="news-date">
                                <i class="fas fa-clock"></i>
                                {{ \Carbon\Carbon::parse($feat->ngaydang)->format('d/m/Y') }}
                            </div>
                        </a>
                    </div>
                @empty
                    <p>Không có tin tức nổi bật.</p>
                @endforelse
            </div>
        </div>


    </div>

@endsection

@section('js')
    {{-- JS riêng cho trang này (nếu cần) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/lang/summernote-vi-VN.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

@endsection
