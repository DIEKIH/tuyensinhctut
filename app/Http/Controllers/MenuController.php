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
            $post = DB::table('baiviet')
                ->where('slug', $lastSlug)
                ->where('status', 'show')
                ->first();

            Log::info('Post found: ' . ($post ? 'YES (ID: ' . $post->id . ')' : 'NO'));

            if ($post) {
                Log::info('Post menu ID: ' . $post->idmenu);

                // Tìm menu của bài viết
                $menu = DB::table('menus')->where('id', $post->idmenu)->first();

                Log::info('Menu found: ' . ($menu ? 'YES (Name: ' . $menu->name . ')' : 'NO'));

                if (!$menu) {
                    throw new \Exception('Menu không tồn tại cho bài viết này');
                }

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

                // Lấy các bài viết khác cùng menu (kèm tên menu và slug menu)
                $baivietCungMenu = DB::table('baiviet')
                    ->join('menus', 'menus.id', '=', 'baiviet.idmenu')
                    ->select('baiviet.*', 'menus.name as tenmenu', 'menus.slug as menuslug')
                    ->where('baiviet.idmenu', $post->idmenu)
                    ->where('baiviet.id', '!=', $post->id)
                    ->where('baiviet.status', 'show')
                    ->whereNotNull('baiviet.slug')
                    ->orderBy('baiviet.ngaydang', 'desc')
                    ->limit(5)
                    ->get();

                // Bài viết kế tiếp cùng danh mục
                $nextPost = DB::table('baiviet')
                    ->join('menus', 'menus.id', '=', 'baiviet.idmenu')
                    ->select('baiviet.*', 'menus.slug as menuslug')
                    ->where('baiviet.danhmuc', $post->danhmuc)
                    ->where('baiviet.id', '!=', $post->id)
                    ->where('baiviet.status', 'show')
                    ->whereNotNull('baiviet.slug')
                    ->inRandomOrder()
                    ->first();

                // Tin nổi bật
                $featuredPosts = DB::table('baiviet')
                    ->join('menus', 'menus.id', '=', 'baiviet.idmenu')
                    ->select('baiviet.*', 'menus.slug as menuslug')
                    ->where('baiviet.is_featured', 1)
                    ->where('baiviet.status', 'show')
                    ->whereNotNull('baiviet.slug')
                    ->orderBy('baiviet.ngaydang', 'desc')
                    ->limit(3)
                    ->get();

                // Tin liên quan
                $relatedPosts = [];
                if (!empty($post->bv_lienquan)) {
                    $relatedIds = explode(',', $post->bv_lienquan);
                    $relatedPosts = DB::table('baiviet')
                        ->join('menus', 'menus.id', '=', 'baiviet.idmenu')
                        ->select('baiviet.*', 'menus.slug as menuslug')
                        ->whereIn('baiviet.id', $relatedIds)
                        ->where('baiviet.status', 'show')
                        ->whereNotNull('baiviet.slug')
                        ->limit(3)
                        ->get();
                }

                Log::info('=== Rendering chitiet view ===');

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

            // Nếu không phải bài viết, xử lý menu
            Log::info('Not a post, processing as menu');

            $level = count($slugs);

            // Menu cấp 1
            $menus = DB::table('menus')->where('id_cha', 0)->get();

            $level1 = DB::table('menus')->where('slug', $slugs[0])->first();

            if (!$level1) {
                Log::error('Level 1 menu not found for slug: ' . $slugs[0]);
                throw new \Exception('Menu không tồn tại');
            }

            Log::info('Level 1 menu: ' . $level1->name . ' (ID: ' . $level1->id . ')');
            Log::info('Loai man hinh: ' . $level1->loaimanhinh);

            $loaimanhinh = $level1->loaimanhinh;

            switch ($loaimanhinh) {
                case '1':
                    Log::info('Processing loaimanhinh = 1');
                    $baiviet_posts = DB::table('baiviet')
                        ->join('danhmuc', 'danhmuc.id', '=', 'baiviet.danhmuc')
                        ->where('baiviet.idmenu', $level1->id)
                        ->where('baiviet.status', 'show')
                        ->whereNotNull('baiviet.slug')
                        ->orderBy('baiviet.ngaydang', 'desc')
                        ->select('baiviet.*', 'danhmuc.tendanhmuc')
                        ->paginate(5);

                    $danhmuc_posts = DB::table('baiviet')
                        ->join('danhmuc', 'danhmuc.id', '=', 'baiviet.danhmuc')
                        ->select('tendanhmuc', 'danhmuc')
                        ->where('idmenu', $level1->id)
                        ->where('baiviet.status', 'show')
                        ->distinct('danhmuc')
                        ->get();

                    $posts = [
                        'baiviet_posts' => $baiviet_posts,
                        'danhmuc_posts' => $danhmuc_posts,
                    ];

                    Log::info('Total posts found: ' . $baiviet_posts->count());

                    return view('users.menus.shows', compact('level1', 'menus', 'posts', 'loaimanhinh', 'slug'));
                    break;

                case '2':
                    Log::info('Processing loaimanhinh = 2');
                    $baiviet_posts = DB::table('baiviet')
                        ->join('danhmuc', 'danhmuc.id', '=', 'baiviet.danhmuc')
                        ->where('baiviet.idmenu', $level1->id)
                        ->where('baiviet.status', 'show')
                        ->whereNotNull('baiviet.slug')
                        ->orderBy('baiviet.ngaydang', 'desc')
                        ->select('baiviet.*', 'danhmuc.tendanhmuc')
                        ->paginate(9);

                    $danhmuc_posts = DB::table('baiviet')
                        ->join('danhmuc', 'danhmuc.id', '=', 'baiviet.danhmuc')
                        ->select('tendanhmuc', 'danhmuc')
                        ->where('idmenu', $level1->id)
                        ->where('baiviet.status', 'show')
                        ->distinct('danhmuc')
                        ->get();

                    $posts = [
                        'baiviet_posts' => $baiviet_posts,
                        'danhmuc_posts' => $danhmuc_posts,
                    ];

                    Log::info('Total posts found: ' . $baiviet_posts->count());

                    return view('users.menus.shows', compact('level1', 'menus', 'posts', 'loaimanhinh', 'slug'));
                    break;

                case '3':
                    Log::info('Processing loaimanhinh = 3');
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
                        ->where('id_cha', $id_level2)
                        ->orderBy('thutu')
                        ->get();

                    if ($level3->isEmpty()) {
                        $level3 = $menu_khoitao;
                    }

                    if ($level >= 3) {
                        $l3 = DB::table('menus')->where('slug', $slugs[2])->first();
                        $id_level3 = $l3->id;
                        $danhmuc_name = $l3->name;
                    } else {
                        $id_level3 = $level3[0]->id;
                        $danhmuc_name = "";
                    }

                    //BÀI VIẾT
                    $baiviet_posts = DB::table('baiviet')
                        ->join('danhmuc', 'danhmuc.id', '=', 'baiviet.danhmuc')
                        ->where('idmenu', $id_level3)
                        ->where('baiviet.status', 'show')
                        ->whereNotNull('baiviet.slug')
                        ->orderBy('ngaydang', 'desc')
                        ->get();

                    $posts = [
                        'baiviet_posts' => $baiviet_posts,
                        'danhmuc_posts' => '',
                        'danhmuc_name' => $danhmuc_name,
                    ];

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
                    Log::error('Invalid loaimanhinh: ' . $loaimanhinh);
                    throw new \Exception('Loại màn hình không hợp lệ');
                    break;
            }
        } catch (\Exception $e) {
            Log::error('=== ERROR ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('Error file: ' . $e->getFile());
            Log::error('Error line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->view('users.errors.menu-not-found', [
                'error' => $e->getMessage(),
                'slug' => $slug ?? 'unknown'
            ], 404);
        }
    }
}
