<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function() {
	
	//[http://localhost:301/]にアクセルしたら
	//[UserController.php/user_login]を引用
	Route::get('/', [UserController::class, 'user_login_01'])->name('user_login_01');

	//[http://localhost:301/user_login]にアクセスしたら
	//[UserController.php/user_login]を引用]
	Route::get('/user_login', [UserController::class, 'user_login_01'])->name('user_login_01');

	Route::get('/user_login', [UserController::class, 'user_login_02'])->name('login');

	//[http://localhost:301/user_login]でログインを実行を行った場合
	Route::Post('/user_login', [UserController::class, 'user_login_03'])->name('user_login_03');

	//[http://localhost:301/user_new_registration]にアクセスしたら
	//[UserController.php/user_new_registration]を引用]
	Route::get('/user_new_registration', [UserController::class, 'user_new_registration_01'])->name('user_new_registration_01');

	//[http://localhost:301/user_new_registration]にアクセスしたら
	//[UserController.php/user_new_registration]を引用]
	Route::post('/user_new_registration', [UserController::class, 'user_new_registration_02'])->name('user_new_registration_02');

});

//Laravelのルート定義にミドルウェアをグループで適用
Route::middleware(['web', 'auth'])->group(function() {

	//[http://localhost:301/product_list]にアクセルしたら
	//[UserController.php/product_list]を引用]
	Route::get('/product_list', [ProductController::class, 'product_list_01'])->name('product_list_01');

	//[http://localhost:301/product_new_registration]にアクセルしたら
	//[UserController.php/product_new_registration]を引用]
	Route::get('/product_new_registration', [ProductController::class, 'product_new_registration_01'])->name('product_new_registration_01');

	//[http://localhost:301/product_new_registration]で新規登録を実行を行った場合
	Route::post('/product_new_registration', [ProductController::class, 'product_new_registration_02'])->name('product_new_registration_02');

	//deleteを使って特定の項目を削除する場合
	Route::delete('/product/{id}/delete', [ProductController::class, 'product_delete'])->name('product_delete');

	//IDを受け付けるパスパラメータを追加
	Route::get('/product_detail/{id?}', [ProductController::class, 'product_detail_01'])->name('product_detail_01');

	//IDを受け付けるパスパラメータを追加
	Route::get('/product_edit/{id?}', [ProductController::class, 'product_edit_01'])->name('product_edit_01');

	//商品更新用
	Route::post('/product_edit/{id}', [ProductController::class, 'product_edit_02'])->name('product_edit_02');

});