<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function show($slug)
    {
        // Lấy bài viết theo slug
        $post = DB::table('baiviet')->where('slug', $slug)->first();

        if (!$post) {
            abort(404); // nếu không tìm thấy bài viết
        }

        // Lấy tác giả (1 người, nếu cần nhiều thì dùng ->pluck())
        $tacgia = DB::table('baiviet_tacgia')
            ->join('tacgia', 'tacgia.id', '=', 'baiviet_tacgia.tacgia_id')
            ->where('baiviet_tacgia.baiviet_id', $post->id)
            ->value('tacgia.ten');

        // Lấy nextPost: random cùng danh mục, loại trừ bài viết hiện tại
        $nextPost = DB::table('baiviet')
            ->where('danhmuc', $post->danhmuc)
            ->where('id', '!=', $post->id)
            ->inRandomOrder()
            ->first();

        // Tin nổi bật: is_featured = 1, giới hạn 3 bài
        $featuredPosts = DB::table('baiviet')
            ->where('is_featured', 1)
            ->orderBy('ngaydang', 'desc')
            ->limit(3)
            ->get();

        // Tin liên quan: cùng idmenu, loại trừ bài viết hiện tại, giới hạn 3 bài
        $relatedPosts = DB::table('baiviet')
            ->where('idmenu', $post->idmenu)
            ->where('id', '!=', $post->id)
            ->orderBy('ngaydang', 'desc')
            ->limit(3)
            ->get();

        return view('users.pages.chitiet', compact(
            'post',
            'tacgia',
            'nextPost',
            'featuredPosts',
            'relatedPosts'
        ));
    }


    public function chitiet()
    {
        return view('users.pages.chitiet');
    }
}
