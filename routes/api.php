<?php

use App\Http\Controllers\API\SalesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->group(function() {
	
	Route::get('/sales',[SalesController::class, 'show.Sales'])->name('show.sales');

	Route::post('/sales',[SalesController::class, 'purchaseSales'])->name('purchase.sales');

});