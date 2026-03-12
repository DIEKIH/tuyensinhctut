<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
// use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\UsersController;
// use App\Http\Controllers\Admin\LoginController;

use App\Http\Controllers\MenuController;
use App\Http\Controllers\Trangchu;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// //Dashboard
// Route::prefix('/')->group(function(){
//     Route::get('/login',[LoginController::class,'index'])->name('login');
// });


// //Dashboard
// Route::prefix('/')->group(function(){
//     Route::get('/dashboard',[DashboardController::class,'dashboard'])->name('dashboard');
// });



// //Users
// Route::prefix('users')->group(function(){
//     Route::get('/',[UsersController::class,'index'])->name('users');
// });




// //Menu
// Route::prefix('menu')->group(function(){
//     Route::get('/',[MenuController::class,'index'])->name('menu');
// });







// //Homepage
// Route::prefix('homepage')->group(function(){
//     Route::get('/',[HomepageController::class,'index'])->name('homepage');
// });







// //Posts
// Route::prefix('posts')->group(function(){
//     Route::get('/',[PostsController::class,'index'])->name('posts');
// });

// Route::get('/{slug}', [MenuController::class, 'show'])->where('slug', '[a-zA-Z0-9-_]+')->name('page.show');

// mới
// removed the duplicate view-based login route in favor of controller-based login

// Form login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');

// Xử lý login
Route::post('/login', [LoginController::class, 'login']);

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes
// Route::prefix('admin')->group(function () {
//     Route::get('/dashboard', [AdminController::class, 'dashboard']);
//     Route::get('/baiviet', [AdminController::class, 'baiviet']);
//     Route::get('/tatcabaiviet', [AdminController::class, 'tatcabaiviet']);
//     Route::post('/store', [AdminController::class, 'store'])->name('baiviet.store');
//     Route::get('/menus', [AdminController::class, 'getMenus']);
//     Route::get('/danhmuc', [AdminController::class, 'getDanhmuc']);
//     Route::get('/tacgia', [AdminController::class, 'getTacgia']);
//     Route::get('baiviet/{id}', [AdminController::class, 'getBaiviet']);
//     Route::delete('baiviet/{id}', [AdminController::class, 'destroy']);
//     // Route::post('/suggest', [AdminController::class, 'suggest'])->name('admin.suggest');
// });

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/baiviet', [AdminController::class, 'baiviet']);
    Route::get('/tatcabaiviet', [AdminController::class, 'tatcabaiviet']);
    Route::post('/store', [AdminController::class, 'store'])->name('baiviet.store');
    Route::get('/menus', [AdminController::class, 'getMenus']);
    Route::get('/danhmuc', [AdminController::class, 'getDanhmuc']);
    Route::get('/tacgia', [AdminController::class, 'getTacgia']);
    Route::get('baiviet/{id}', [AdminController::class, 'getBaiviet']);
    Route::delete('baiviet/{id}', [AdminController::class, 'destroy']);
});


Route::get('/tacgia', [AdminController::class, 'tacgia'])->name('tacgia');
Route::get('/tacgia_danhsach', [AdminController::class, 'tacgia_danhsach']);
Route::get('tacgia_them', [AdminController::class, 'tacgia_them']);
Route::get('/tacgia_load', [AdminController::class, 'tacgia_load']);
Route::get('/tacgia_capnhat', [AdminController::class, 'tacgia_capnhat']);
Route::get('/tacgia_xoa', [AdminController::class, 'tacgia_xoa']);

// Index routes
Route::get('/index', [Trangchu::class, 'index']);
Route::get('/banners', [Trangchu::class, 'banners']);
Route::get('/tintucnoibat', [Trangchu::class, 'tintucnoibat']);
Route::get('/tintucnho', [Trangchu::class, 'tintucnho']);
Route::get('/sukiennho', [Trangchu::class, 'sukiennho']);
Route::get('/thongbaonho', [Trangchu::class, 'thongbaonho']);
Route::get('/nganhnho', [Trangchu::class, 'nganhnho']);

Route::post('/admin/suggest', [AdminController::class, 'suggest'])->name('admin.suggest');




// User routes
Route::get('/{slug}', [MenuController::class, 'show'])
    ->where('slug', '.*')  // chấp nhận nhiều đoạn có dấu gạch chéo
    ->name('page.show');
