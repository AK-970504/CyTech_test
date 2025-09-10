<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function() {
	
	Route::get('/', [UserController::class, 'sowLoginPage'])->name('show.login.page');

	Route::get('/user_login', [UserController::class, 'sowLoginPage'])->name('show.login.page');

	Route::get('/user_login', [UserController::class, 'showLoginPageAlt'])->name('login');

	//[http://localhost:301/user_login]でログインを実行を行った場合
	Route::Post('/user_login', [UserController::class, 'loginUser'])->name('login.user');

	//[http://localhost:301/user_new_registration]にアクセスしたら
	//[UserController.php/user_new_registration]を引用]
	Route::get('/user_new_registration', [UserController::class, 'showRegistrationPage'])->name('show.registration.page');

	//[http://localhost:301/user_new_registration]にアクセスしたら
	//[UserController.php/user_new_registration]を引用]
	Route::post('/user_new_registration', [UserController::class, 'registerUse'])->name('register.use');

});

//Laravelのルート定義にミドルウェアをグループで適用
Route::middleware(['web', 'auth'])->group(function() {

	//[http://localhost:301/product_list]にアクセルしたら
	//[UserController.php/product_list]を引用]
	Route::get('/product_list', [ProductController::class, 'showProductList'])->name('show.product.list');

	//[http://localhost:301/product_new_registration]にアクセルしたら
	//[UserController.php/product_new_registration]を引用]
	Route::get('/product_new_registration', [ProductController::class, 'showNewProductPage'])->name('show.new.product.page');

	//[http://localhost:301/product_new_registration]で新規登録を実行を行った場合
	Route::post('/product_new_registration', [ProductController::class, 'registerProduct'])->name('register.product');

	//deleteを使って特定の項目を削除する場合
	Route::delete('/product/{id}/delete', [ProductController::class, 'deleteProduct'])->name('delete.product');

	//IDを受け付けるパスパラメータを追加
	Route::get('/product_detail/{id?}', [ProductController::class, 'showProductDetail'])->name('show.product.detail');

	//IDを受け付けるパスパラメータを追加
	Route::get('/product_edit/{id?}', [ProductController::class, 'showEditPage'])->name('show.edit.page');

	//商品更新用
	Route::post('/product_edit/{id}', [ProductController::class, 'updateProduct'])->name('update.product');

});