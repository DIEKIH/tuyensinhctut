<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;


class Trangchu extends Controller
{
    // Banner
    function banners(Request $request)
    {
        $data = DB::table('banners')->get();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'Không có banner nào.'], 404);
        } else {
            $json_data['data'] = $data;
            return response()->json($json_data);
            // return response()->json(['data' => $banners], 200);
        }
    }


    function tintucnoibat(Request $request)
    {
        $data = DB::table('baiviet')
            ->where('idmenu', 2)            // 2 = tin tức
            ->where('is_featured', 1)       // nổi bật
            ->where('status', 'show')
            ->orderBy('ngaydang', 'desc')
            ->limit(1)
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Không có tin tức nổi bật.'], 404);
        }
        return response()->json(['data' => $data]);
    }

    // Tin tức nhỏ (3 tin mới nhất)
    function tintucnho(Request $request)
    {
        $data = DB::table('baiviet')
            ->where('idmenu', 2)            // 2 = tin tức
            ->where('is_featured', 0)
            ->where('status', 'show')
            ->orderBy('ngaydang', 'desc')
            ->limit(5)
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Không có tin tức.'], 404);
        }
        return response()->json(['data' => $data]);
    }

    // Sự kiện nhỏ (6 sự kiện sắp diễn ra)
    function sukiennho(Request $request)
    {
        $today = now();

        $data = DB::table('baiviet')
            ->where('idmenu', 3)            // 3 = sự kiện
            ->where('status', 'show')
            // ->where('thoigian_bd', '>=', $today) // chỉ lấy sự kiện chưa bắt đầu
            ->orderBy('thoigian_bd', 'asc')
            ->limit(6)
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Không có sự kiện sắp tới.'], 404);
        }
        return response()->json(['data' => $data]);
    }

    // Thông báo nhỏ (12 thông báo mới nhất)
    function thongbaonho(Request $request)
    {
        $data = DB::table('baiviet')
            ->where('idmenu', 1)            // 1 = thông báo
            ->where('status', 'show')
            ->orderBy('ngaydang', 'desc')
            ->limit(12)
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Không có thông báo.'], 404);
        }
        return response()->json(['data' => $data]);
    }




    // Nganh nho
    function nganhnho(Request $request)
    {
        $data = DB::table('majors')
            ->select('id', 'name', 'image_url')
            ->orderBy('id', 'asc')
            // ->limit(6)
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Không có sự kiện nào.'], 404);
        } else {
            $json_data['data'] = $data;
            return response()->json($json_data);
        }
    }
    // Hien trang
    function index()
    {
        return view(
            'users.pages.index'
        );
    }
}
