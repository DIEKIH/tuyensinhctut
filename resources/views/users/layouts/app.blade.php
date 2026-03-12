<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <head>
        <meta charset="UTF-8">
        <title>{{ $title ?? 'Trang mặc định' }}</title>

        @if (isset($post))
            <meta property="og:title" content="{{ $post->tieude }}" />
            <meta property="og:description" content="{{ Str::limit(strip_tags($post->tomtat), 150) }}" />
            <meta property="og:image"
                content="{{ $post->image_url ? asset($post->image_url) : asset('images/default.jpg') }}" />
            <meta property="og:url" content="{{ url()->current() }}" />
            <meta property="og:type" content="article" />
        @else
            <meta property="og:title" content="Tiêu đề mặc định" />
            <meta property="og:description" content="Mô tả mặc định" />
            <meta property="og:image" content="{{ asset('images/default.jpg') }}" />
            <meta property="og:url" content="{{ url()->current() }}" />
            <meta property="og:type" content="website" />
        @endif
    </head>




    <title>@yield('title', 'Trang chủ')</title>
    <link rel="shortcut icon" href="/images/favicon.png">

    {{-- CSS chung --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script> --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> --}}
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap/css2.css') }}">



    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/color.css">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">

    <link rel="stylesheet" href="/css/thongbao.css">

    @if (!request()->is('index'))
        <link rel="stylesheet" href="/css/tuyensinh-home.css">
        <link rel="stylesheet" href="/css/tuyensinh-sub.css">
        <link rel="stylesheet" href="/css/tuyensinh-archive.css">
        <link rel="stylesheet" href="/css/tintuc.css">
    @endif


    {{-- Tin tuc --}}
    <style>
        body.loaded .content {
            animation: bounceIn 0.3s ease-out;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: translateY(-30px);
            }

            70% {
                opacity: 1;
                transform: translateY(5px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .image-wrapper {
            position: relative;
            width: 100%;
            padding-top: 75%;
            /* 4:3 ratio => 3 / 4 = 0.75 = 75% */
            overflow: hidden;
            /* tuỳ chọn */
        }

        .image-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
    </style>


    {{-- Thong bao --}}
    <style>
        .archive-pagi .page-item a {
            text-decoration: none;
            color: var(--sub-color);
            cursor: pointer;
        }

        .archive-pagi .active.page-item a {
            color: #fff;
            background: var(--sub-color);
            border: 1px solid var(--sub-color);
        }
    </style>

    {{-- CSS riêng --}}
    @yield('css')
</head>

<body>
    @include('users.layouts.header')

    <main>
        @yield('content')
    </main>

    @include('users.layouts.footer')

    {{-- JS chung --}}

    <script src="{{ asset('js/bootstrap/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap/swiper-bundle.min.js') }}"></script>

    <script src="/js/thongbao.js"></script>


    @yield('js')
</body>

</html>
