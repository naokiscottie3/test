<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLoginController;

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

Route::middleware(['guest'])->group(function () {
    //ログインホーム
    Route::get('/', [AuthController::class,'showLogin'])->name('login.show');
    //ログイン処理
    Route::post('login', [AuthController::class,'login'])->name('login');

});

Route::middleware(['auth'])->group(function () {
    //ホーム画面
    Route::get('home', function(){
        return view('home');
    })->name('home');
    //ログアウト
    Route::post('logout', [AuthController::class,'logout'])->name('logout');


});



Route::get('/admin/login', [AdminLoginController::class,'index'])->name('admin_login');

Route::post('/admin/login', [AdminLoginController::class,'signInAdmin'])->name('admin_sign_in');


Route::middleware(['admin'])->group(function () {

Route::get('/admin', [AdminController::class,'index'])->name('admin');

Route::get('/admin/logout', function () {

    Auth::guard('admin')->logout();

    return redirect('/admin/login');
});

});


Route::get('/admin_register_show', [AdminLoginController::class,'admin_registar_show'])->name('admin_register_show');

Route::post('/admin_register', [AdminLoginController::class,'admin_registar'])->name('admin_register');
