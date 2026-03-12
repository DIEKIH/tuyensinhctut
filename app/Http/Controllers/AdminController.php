<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class AdminController extends Controller
{

    public function tatcabaiviet(Request $request)
    {
        $data = DB::table('baiviet')
            ->orderBy('ngaydang', 'desc')
            ->get();

        // Lấy tên tác giả
        foreach ($data as $bv) {
            if ($bv->tacgia) {
                // tacgia là chuỗi "1,2,3"
                $ids = explode(',', $bv->tacgia);

                // Lấy tên tác giả tương ứng
                $ten_tacgia = DB::table('tacgia')
                    ->whereIn('id', $ids)
                    ->pluck('ten')  // lấy cột ten
                    ->toArray();

                $bv->tacgia_ten = implode(', ', $ten_tacgia);
            } else {
                $bv->tacgia_ten = null;
            }
        }

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Không có bài viết nào.'], 404);
        }

        return response()->json(['data' => $data]);
    }


    public function getBaiviet($id)
    {
        $baiviet = DB::table('baiviet')
            ->leftJoin('menus as current_menu', 'baiviet.idmenu', '=', 'current_menu.id')
            ->select('baiviet.*', 'current_menu.id as current_menu_id', 'current_menu.id_cha as current_menu_parent')
            ->where('baiviet.id', $id)
            ->first();

        if (!$baiviet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy bài viết',
                'data' => null
            ], 404);
        }

        // --- Xử lý tác giả ---
        if ($baiviet->tacgia) { // tacgia lưu dạng "1,2,3"
            $ids = explode(',', $baiviet->tacgia);
            $ten_tacgia = DB::table('tacgia')->whereIn('id', $ids)->pluck('ten')->toArray();
            $baiviet->tacgia_ten = implode(', ', $ten_tacgia);
            $baiviet->tacgia_ids = $baiviet->tacgia;
        } else {
            $baiviet->tacgia_ten = null;
            $baiviet->tacgia_ids = null;
        }

        // --- Xử lý menu 1, 2, 3 ---
        $menu1_id = $menu2_id = $menu3_id = null;
        if ($baiviet->current_menu_id) {
            if ($baiviet->current_menu_parent == 0) {
                $menu1_id = $baiviet->current_menu_id;
            } else {
                $parent = DB::table('menus')->where('id', $baiviet->current_menu_parent)->first();
                if ($parent && $parent->id_cha == 0) {
                    $menu1_id = $parent->id;
                    $menu2_id = $baiviet->current_menu_id;
                } else {
                    $grandparent = DB::table('menus')->where('id', $parent->id_cha)->first();
                    if ($grandparent) {
                        $menu1_id = $grandparent->id;
                        $menu2_id = $parent->id;
                        $menu3_id = $baiviet->current_menu_id;
                    }
                }
            }
        }

        // --- Link ảnh và file ---
        if ($baiviet->image_url) {
            $baiviet->image_url = asset($baiviet->image_url);
        }
        if ($baiviet->file_url) {
            $baiviet->file_url = asset($baiviet->file_url);
        }

        // --- Xử lý bài viết liên quan ---
        $baiviet->bv_lienquan_data = [];
        if (!empty($baiviet->bv_lienquan)) {
            $ids = array_filter(explode(',', $baiviet->bv_lienquan));
            $related = DB::table('baiviet')
                ->whereIn('id', $ids)
                ->select('id', 'tieude', 'image_url', 'ngaydang')
                ->get()
                ->map(function ($item) {
                    if ($item->image_url) {
                        $item->image_url = asset($item->image_url);
                    }
                    return $item;
                });
            $baiviet->bv_lienquan_data = $related;
        }

        // --- Gán menu ---
        $baiviet->menu1_id = $menu1_id;
        $baiviet->menu2_id = $menu2_id;
        $baiviet->menu3_id = $menu3_id;

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy bài viết thành công',
            'data' => $baiviet
        ]);
    }







    public function getMenus()
    {
        // Lấy tất cả menus
        $menus = DB::table('menus')->get();

        // Đệ quy build tree
        $menuTree = $this->buildTree($menus);

        return response()->json([
            'success' => true,
            'data' => $menuTree
        ]);
    }


    public function buildTree($menus, $parentId = 0)
    {
        $branch = [];

        foreach ($menus as $menu) {
            if ($menu->id_cha == $parentId) {
                $children = $this->buildTree($menus, $menu->id);

                $item = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'slug' => $menu->slug,
                    'loaimanhinh' => $menu->loaimanhinh,
                    'thutu' => $menu->thutu,
                ];

                if ($children) {
                    $item['children'] = $children;
                }

                $branch[] = $item;
            }
        }

        return $branch;
    }


    public function getDanhmuc()
    {
        // Lấy tất cả danh mục
        $danhmuc = DB::table('danhmuc')->get();

        return response()->json([
            'success' => true,
            'data' => $danhmuc
        ]);
    }

    public function getTacgia()
    {
        // Lấy tất cả tác giả
        $tacgia = DB::table('tacgia')->get();

        return response()->json([
            'success' => true,
            'data' => $tacgia
        ]);
    }


    // mới
    public function suggest(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $content = trim($request->input('content'));

        // Shorten to avoid quá dài (tùy bạn điều chỉnh)
        $content = Str::limit($content, 6000, '');

        $apiKey = config('services.openrouter.key');
        $url = config('services.openrouter.url');

        if (empty($apiKey)) {
            return response()->json(['success' => false, 'message' => 'OpenRouter API key not configured.'], 500);
        }

        // Prompt (tiếng Việt) -> Yêu cầu trả về đúng JSON { "title": "...", "summary": "..." }
        $system = "Bạn là một trợ lý viết tiêu đề và tóm tắt ngắn gọn bằng tiếng Việt.";
        $user = "Dưới đây là nội dung của một bài viết. Hãy trả về DUY NHẤT một đối tượng JSON hợp lệ với 2 khoá: \"title\" và \"summary\". " .
            "Yêu cầu: title tối đa 500 ký tự; summary là 1-2 câu, súc tích, tối đa ~1000 ký tự, không chứa ký tự đặc biệt. " .
            "KHÔNG thêm bất kỳ chữ nào khác ngoài object JSON. Nội dung bài viết: \n\n" . $content;

        $payload = [
            'model' => 'openai/gpt-oss-20b:free', //'deepseek/deepseek-chat-v3.1:free', //openai/gpt-3.5-turbo , google/gemini-2.0-flash-exp:free, openai/gpt-oss-20b:free
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $user],
            ],
            // đề xuất để ổn định output
            'max_tokens' => 500,
            'temperature' => 0.2,
            // OpenRouter hỗ trợ response_format để ép JSON (tùy model)
            'response_format' => ['type' => 'json_object'],
        ];

        try {
            $resp = Http::withToken($apiKey)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    // Optional: 'HTTP-Referer' => url('/'), 'X-Title' => 'MyApp',
                ])
                ->post($url, $payload);

            if (!$resp->successful()) {
                Log::error('OpenRouter error: ' . $resp->body());
                return response()->json(['success' => false, 'message' => 'OpenRouter API error', 'detail' => $resp->body()], 500);
            }

            $json = $resp->json();

            // Lấy phần text trả về từ model
            $raw = data_get($json, 'choices.0.message.content', '') ?? '';

            // Thử decode trực tiếp
            $parsed = json_decode($raw, true);

            // Nếu decode fail, cố gắng extract JSON bằng regex (fallback)
            if (json_last_error() !== JSON_ERROR_NONE) {
                if (preg_match('/\{.*\}/s', $raw, $matches)) {
                    $parsed = json_decode($matches[0], true);
                } else {
                    // cuối cùng: gửi raw text về để debug
                    Log::warning('OpenRouter raw response: ' . $raw);

                    return response()->json([
                        'success' => false,
                        'message' => 'Không parse được JSON từ mô hình',
                        'raw' => $raw
                    ], 500);
                }
            }

            $title = $parsed['title'] ?? '';
            $summary = $parsed['summary'] ?? '';

            // Trim và bảo đảm độ dài
            $title = mb_substr(trim($title), 0, 500);
            $summary = mb_substr(trim($summary), 0, 1000);

            return response()->json([
                'success' => true,
                'title' => $title,
                'summary' => $summary,
                'raw' => $raw // tùy bạn có muốn trả raw để debug
            ]);
        } catch (\Exception $e) {
            Log::error('Suggest exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi gọi OpenRouter: ' . $e->getMessage()], 500);
        }
        if ($resp->status() == 429) {
            return response()->json([
                'success' => false,
                'message' => 'Model đang bị giới hạn tạm thời, vui lòng thử lại sau.'
            ], 429);
        }
    }


    // tác giả
    function tacgia()
    {
        return view('admins.pages.tacgia');
    }

    function tacgia_danhsach()
    {
        $data = DB::table('tacgia')->orderBy('id', 'desc')->get();

        foreach ($data as $index => $item) {
            $item->stt = $index + 1;
        }

        return response()->json(['data' => $data]);
    }

    function tacgia_them(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tacgia_ten' => 'required|regex:/^[\p{L}0-9 _-]+$/u',
            ],
            [
                'tacgia_ten.required' => 'Tên tác giả không được để trống',
                'tacgia_ten.regex' => 'Tên tác giả không chứa ký tự đặc biệt',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            DB::beginTransaction();
            $inserted = DB::table('tacgia')->insert([
                'ten' => $request->input('tacgia_ten'),
            ]);

            if ($inserted) {
                DB::commit();
                return 1;
            }

            DB::rollback();
            return 0;
        } catch (Exception $e) {
            DB::rollback();
            return -1;
        }
    }

    function tacgia_xoa(Request $request)
    {
        $id = $request->input('id');

        try {
            DB::beginTransaction();
            $deleted = DB::table('tacgia')->where('id', $id)->delete();

            if ($deleted) {
                DB::commit();
                return response()->json(['status' => 'success']);
            }

            DB::rollback();
            return response()->json(['status' => 'error']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    function tacgia_load(Request $request)
    {
        try {
            $data = DB::table('tacgia')->where('id', $request->input('id'))->first();
            if (!$data) {
                return response()->json(['status' => 'error', 'message' => 'Không tìm thấy tác giả']);
            }
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function tacgia_capnhat(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'update_tacgia_ten' => 'required|regex:/^[\p{L}0-9 _-]+$/u',
            ],
            [
                'update_tacgia_ten.required' => 'Tên tác giả không được để trống',
                'update_tacgia_ten.regex' => 'Tên tác giả không chứa ký tự đặc biệt',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $id = $request->input('id');

        try {
            DB::beginTransaction();
            $updated = DB::table('tacgia')
                ->where('id', $id)
                ->update([
                    'ten' => $request->input('update_tacgia_ten'),
                ]);

            // ✅ Cho phép commit nếu không lỗi, dù giá trị không thay đổi
            if ($updated !== false) {
                DB::commit();
                return 1;
            }

            DB::rollback();
            return 0;
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
    //#endregion



    public function store(Request $request)
    {
        // Validate cơ bản
        if (!$request->has('postId')) {
            $postId = 0;
        } else {
            $postId = $request->postId;
        }

        $baiviet_old = null;
        if ($postId > 0) {
            $baiviet_old = DB::table('baiviet')->where('id', $postId)->first();
        }

        $imageRule = 'required|image|mimes:jpg,jpeg,png,gif';

        if ($request->filled('postId')) {
            $baiviet_old = DB::table('baiviet')->find($request->postId);
            // Nếu bài cũ có ảnh rồi → không bắt buộc upload lại
            if ($baiviet_old && $baiviet_old->image_url) {
                $imageRule = 'nullable|image|mimes:jpg,jpeg,png,gif';
            }
        }

        $validator = Validator::make($request->all(), [
            'tieude' => 'required|string|max:255|regex:/^[\pL\s0-9.,!?:()"-]+$/u',
            'tomtat' => 'required|string|max:1000|regex:/^[\pL\s0-9.,!?:()"-]+$/u',
            'noidung' => 'nullable|string',
            'ngaydang' => 'required',
            'image' => $imageRule,
            'file' => 'nullable|mimes:pdf|max:5120',
            'tacgia' => 'required|array|min:1',
            'menu1' => 'required',
            'loaitin' => 'required',
            'danhmuc' => 'required',
        ], [
            'tieude.required' => 'Tiêu đề không được để trống',
            'tieude.string' => 'Tiêu đề phải là chuỗi ký tự hợp lệ',
            'tieude.max' => 'Tiêu đề tối đa 255 ký tự',
            'tieude.regex' => 'Tiêu đề chứa ký tự không hợp lệ',
            'tomtat.required' => 'Tóm tắt không được để trống',
            'tomtat.string' => 'Tóm tắt phải là chuỗi ký tự hợp lệ',
            'tomtat.max' => 'Tóm tắt tối đa 1000 ký tự',
            'tomtat.regex' => 'Tóm tắt chứa ký tự không hợp lệ',
            'noidung.string' => 'Nội dung phải là chuỗi ký tự hợp lệ',
            'ngaydang.required' => 'Ngày đăng không được để trống',
            'image.required' => 'Ảnh không được để trống',
            'image.image' => 'File tải lên phải là ảnh',
            'image.mimes' => 'Ảnh phải có định dạng: jpg, jpeg, png, gif',
            'file.mimes' => 'File tải lên phải có định dạng: pdf',
            'file.max' => 'Kích thước file tối đa là 5MB',
            'tacgia.required' => 'Tác giả không được để trống',
            'tacgia.array' => 'Dữ liệu tác giả không hợp lệ',
            'tacgia.min' => 'Phải chọn ít nhất 1 tác giả',
            'tacgia.max' => 'Tên tác giả tối đa 100 ký tự',
            'menu1.required' => 'Bắt buộc chọn menu cấp 1',
            'loaitin.required' => 'Bắt buộc chọn loại tin',
            'danhmuc.required' => 'Bắt buộc chọn danh mục',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 200);
        }

        // Chuyển đổi ngày từ d/m/Y sang Y-m-d
        try {
            $ngaydang = \Carbon\Carbon::createFromFormat('d/m/Y', $request->ngaydang)->format('Y-m-d');
        } catch (\Exception $e) {
            $ngaydang = now()->format('Y-m-d');
        }

        // Khởi tạo biến để lưu đường dẫn file tạm
        $tempImagePath = null;
        $tempFilePath = null;
        $oldImagePath = $baiviet_old->image_url ?? null;
        $oldFilePath = $baiviet_old->file_url ?? null;

        DB::beginTransaction();

        try {
            // ===== XỬ LÝ UPLOAD ẢNH =====
            $imagePath = $oldImagePath;

            if ($request->hasFile('image')) {
                $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
                $destinationPath = public_path('images/baiviet');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $request->file('image')->move($destinationPath, $imageName);
                $tempImagePath = 'images/baiviet/' . $imageName;
                $imagePath = $tempImagePath;
            }

            // ===== XỬ LÝ UPLOAD FILE PDF =====
            $filePath = $oldFilePath;

            if ($request->hasFile('file')) {
                $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
                $destinationPath = public_path('pdf');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $request->file('file')->move($destinationPath, $fileName);
                $tempFilePath = 'pdf/' . $fileName;
                $filePath = $tempFilePath;
            }

            // ===== XÁC ĐỊNH IDMENU =====
            $idmenu = null;
            if (!empty($request->menu3)) {
                $idmenu = $request->menu3;
            } elseif (!empty($request->menu2)) {
                $idmenu = $request->menu2;
            } else {
                $idmenu = $request->menu1;
            }

            $tacgia_str = null;
            if (!empty($request->tacgia)) {
                $tacgia_str = implode(',', $request->tacgia);
            }

            $bvlq = $request->lienquan ? implode(',', explode(',', $request->lienquan)) : null;

            // ===== INSERT/UPDATE DATABASE =====
            DB::table('baiviet')->updateOrInsert(
                ["id" => $postId],
                [
                    'idmenu' => $idmenu,
                    'loaitin' => $request->loaitin,
                    'danhmuc' => $request->danhmuc,
                    'tieude' => $request->tieude,
                    'slug' => Str::slug($request->tieude),
                    'noidung' => $request->noidung,
                    'tomtat' => $request->tomtat,
                    'tacgia' => $tacgia_str,
                    'ngaydang' => $ngaydang,
                    'status' => 'show',
                    'image_url' => $imagePath,
                    'file_url' => $filePath,
                    'is_featured' => $request->is_featured ?? 0,
                    'new' => 1,
                    'views' => 0,
                    'bv_lienquan' => $bvlq
                ]
            );

            // ===== LẤY ID BÀI VIẾT =====
            if ($postId == 0) {
                $baiviet_id = DB::getPdo()->lastInsertId();
            } else {
                $baiviet_id = $postId;
            }

            // ===== XÓA FILE CŨ SAU KHI LƯU THÀNH CÔNG =====
            if ($tempImagePath && $oldImagePath && file_exists(public_path($oldImagePath))) {
                unlink(public_path($oldImagePath));
            }

            if ($tempFilePath && $oldFilePath && file_exists(public_path($oldFilePath))) {
                unlink(public_path($oldFilePath));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lưu bài viết thành công!',
                'id' => $baiviet_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // ===== XÓA FILE MỚI NẾU CÓ LỖI =====
            if ($tempImagePath && file_exists(public_path($tempImagePath))) {
                unlink(public_path($tempImagePath));
            }

            if ($tempFilePath && file_exists(public_path($tempFilePath))) {
                unlink(public_path($tempFilePath));
            }

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        } finally {
            // Có thể thêm các tác vụ cleanup hoặc logging ở đây nếu cần
        }
    }




    public function destroy($id)
    {
        try {
            // Xóa bài viết theo ID
            $deleted = DB::table('baiviet')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Xóa bài viết thành công!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy bài viết để xóa.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa bài viết: ' . $e->getMessage()
            ]);
        }
    }



    public function dashboard()
    {
        return view('admins.pages.dashboard');
    }

    public function baiviet()
    {
        return view('admins.pages.baiviet');
    }
}
