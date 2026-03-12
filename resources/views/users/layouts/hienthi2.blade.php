<div class="backlink-v2 content">
    <a href="#">Trang chủ</a>
    <span>/</span>
    {{-- <a class="last-a">Tin tức</a> --}}
    <a class="last-a">{{ $level1->name }}</a>

</div>

<section id="ts-news" class="ts-section-news content" style="margin-top: 30px;">
    <div class="container">
        <div class="row news-col" id="news-list">
            <div class="col-12 title-topic">
                {{-- <h2>TIN TỨC</h2> --}}
                <h2>{{ mb_strtoupper($level1->name, 'UTF-8') }}</h2>
            </div>

            @foreach ($posts['baiviet_posts'] as $baiviet)
                @include('users.partials.baiviet', ['baiviet' => $baiviet])
            @endforeach

        </div>
    </div>
</section>

{{-- <script src="js/trangtintuc.js"></script> --}}

<script>
    window.addEventListener("load", function() {
        document.body.classList.add("loaded");
    });
</script>
