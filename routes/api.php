<?php

use App\Http\Controllers\API\SalesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->group(function() {
	
	Route::get('/sales',[SalesController::class, 'sales_01'])->name('sales_01');

	Route::post('/sales',[SalesController::class, 'sales_02'])->name('sales_02');

});