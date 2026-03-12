<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;


class MenuController extends Controller
{

    public function show($slug)
    {
        try {
            $slugs = explode('/', trim($slug, '/'));
            $lastSlug = end($slugs);

            // DEBUG: Log để xem giá trị
            Log::info('=== DEBUG START ===');
            Log::info('Full slug: ' . $slug);
            Log::info('Slugs array: ' . json_encode($slugs));
            Log::info('Last slug: ' . $lastSlug);

            // --- 1. Kiểm tra xem slug cuối có phải là bài viết không ---
            $post = DB::table('baiviet')->where('slug', $lastSlug)->first();
            if ($post) {
                // Tìm menu của bài viết
                $menu = DB::table('menus')->where('id', $post->idmenu)->first();

                // Lấy tác giả
                $tacgia = null;
                if (!empty($post->tacgia)) {
                    $tacgia_ids = explode(',', $post->tacgia);

                    $tacgia = DB::table('tacgia')
                        ->whereIn('id', $tacgia_ids)
                        ->pluck('ten')
                        ->implode(', ');
                } else {
                    $tacgia = 'Không rõ tác giả';
                }


                // Lấy các bài viết khác cùng menu (kèm tên menu)
                $baivietCungMenu = DB::table('baiviet')
                    ->join('menus', 'menus.id', '=', 'baiviet.idmenu')
                    ->select('baiviet.*', 'menus.name as tenmenu')
                    ->where('baiviet.idmenu', $post->idmenu)
                    ->where('baiviet.id', '!=', $post->id) // bỏ bài hiện tại
                    ->where('baiviet.status', 'show')
                    ->orderBy('baiviet.ngaydang', 'desc')
                    ->limit(5)
                    ->get();


                // Bài viết kế tiếp cùng danh mục
                $nextPost = DB::table('baiviet')
                    ->where('danhmuc', $post->danhmuc)
                    ->where('id', '!=', $post->id)
                    ->inRandomOrder()
                    ->first();

                // Tin nổi bật
                $featuredPosts = DB::table('baiviet')
                    ->where('is_featured', 1)
                    ->orderBy('ngaydang', 'desc')
                    ->limit(3)
                    ->get();

                // Tin liên quan
                $relatedPosts = [];
                if (!empty($post->bv_lienquan)) {
                    $relatedIds = explode(',', $post->bv_lienquan);
                    $relatedPosts = DB::table('baiviet')
                        ->whereIn('id', $relatedIds)
                        ->limit(3)

                        ->get();
                }

                return view('users.pages.chitiet', compact(
                    'menu',
                    'post',
                    'tacgia',
                    'nextPost',
                    'featuredPosts',
                    'relatedPosts',
                    'baivietCungMenu'
                ));
            }

            $level = count($slugs);

            // Menu cáp 1
            $menus = DB::table('menus')->where('id_cha', 0)->get();

            $level1 = DB::table('menus')->where('slug', $slugs[0])->first();
            $loaimanhinh =  $level1->loaimanhinh;
            switch ($loaimanhinh) {

                case '1':
                    $baiviet_posts = DB::table('baiviet')
                        ->join('danhmuc', 'danhmuc.id', '=', 'baiviet.danhmuc')
                        ->where('baiviet.idmenu', $level1->id)
                        ->orderBy('baiviet.ngaydang', 'desc')
                        ->select('baiviet.*', 'danhmuc.tendanhmuc') // 👈 chọn id từ baiviet
                        ->paginate(5);

                    $danhmuc_posts = DB::table('baiviet')
                        ->join('danhmuc', 'danhmuc.id', '=', 'baiviet.danhmuc')
                        ->select('tendanhmuc', 'danhmuc')
                        ->where('idmenu', $level1->id)
                        ->distinct('danhmuc')
                        ->get();
                    $posts = [
                        'baiviet_posts' => $baiviet_posts,
                        'danhmuc_posts' => $danhmuc_posts,
                    ];
                    return view('users.menus.shows', compact('level1', 'menus', 'posts', 'loaimanhinh', 'slug'));
                    break;
                case '2':
                    $baiviet_posts = DB::table('baiviet')
                        ->join('danhmuc', 'danhmuc.id', '=', 'baiviet.danhmuc')
                        ->where('baiviet.idmenu', $level1->id)
                        ->orderBy('baiviet.ngaydang', 'desc')
                        ->select('baiviet.*', 'danhmuc.tendanhmuc') // 👈 chọn id từ baiviet
                        ->get();

                    $danhmuc_posts = DB::table('baiviet')
                        ->join('danhmuc', 'danhmuc.id', '=', 'baiviet.danhmuc')
                        ->select('tendanhmuc', 'danhmuc')
                        ->where('idmenu', $level1->id)
                        ->distinct('danhmuc')
                        ->get();
                    $posts = [
                        'baiviet_posts' => $baiviet_posts,
                        'danhmuc_posts' => $danhmuc_posts,
                    ];
                    return view('users.menus.shows', compact('level1', 'menus', 'posts', 'loaimanhinh', 'slug'));
                    break;
                case '3':
                    $menu_khoitao = collect([
                        (object)[
                            'id' => -1,
                            'name' => "",
                            'slug' => "",
                        ]
                    ]);

                    //Menu cấp 2
                    $level2 = DB::table('menus')
                        ->where('id_cha', $level1->id)
                        ->orderBy('thutu')
                        ->get();
                    if ($level2->isEmpty()) {
                        $level2 = $menu_khoitao;
                    }
                    $level >= 2 ? $id_level2 = DB::table('menus')->where('slug', $slugs[1])->first()->id : $id_level2 = $level2[0]->id;

                    //Menu cấp 3   
                    $level3 = DB::table('menus')
                        ->where('id_cha',  $id_level2)
                        ->orderBy('thutu')
                        ->get();
                    if ($level3->isEmpty()) {
                        $level3 = $menu_khoitao;
                    }
                    if ($level >= 3) {
                        $l3 = DB::table('menus')->where('slug', $slugs[2])->first();
                        $id_level3 =  $l3->id;
                        $danhmuc_name = $l3->name;
                    } else {
                        $id_level3 = $level3[0]->id;
                        $danhmuc_name = "";
                    }

                    //bÀI VIẾT
                    $baiviet_posts = DB::table('baiviet')
                        ->join('danhmuc', 'danhmuc.id', '=', 'baiviet.danhmuc')
                        ->where('idmenu', $id_level3)
                        ->orderBy('ngaydang', 'desc')
                        ->get();
                    $posts = [
                        'baiviet_posts' => $baiviet_posts,
                        'danhmuc_posts' => '',
                        'danhmuc_name'  => $danhmuc_name,
                    ];

                    // Trả về view
                    return view('menus.shows', compact(
                        'menus',
                        'level1',
                        'id_level2',
                        'level2',
                        'id_level3',
                        'level3',
                        'posts',
                        'loaimanhinh'
                    ));

                    break;

                default:
                    # code...
                    break;
            }

            // dd($posts);
















        } catch (\Exception $e) {
            Log::error('Lỗi khi hiển thị menu: ' . $e->getMessage());
            return response()->view('users.errors.menu-not-found', ['error' => $e->getMessage()], 404);
        }
    }
}
