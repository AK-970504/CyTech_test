<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller {
	//最新の購入情報を取得
	public function sales_01() {
		try {
			$sale = Sale::latest()->first();
			if (!$sale) {
				throw new \Exception('Salesデータが見つかりません', 404);
			}
			$result = [
				'result' => true,
				'sale_id' => $sale->id,
				'product_id' => $sale->product_id,
				'quantity' => $sale->quantity ?? null,
				'created_at' => $sale->created_at,
				'updated_at' => $sale->updated_at,
			];
		} catch (\Exception $e) {
			// ステータスコードを安全に設定
			$status = is_int($e->getCode()) && $e->getCode() >= 100 && $e->getCode() < 600
				? $e->getCode()
				: 500;
			$result = [
				'result' => false,
				'error' => [
					'messages' => [$e->getMessage()]
				],
			];
			return response()->json($result, $status, ['Content-Type' => 'application/json'], JSON_UNESCAPED_SLASHES);
		}
		return response()->json($result, 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_SLASHES);
	}

	//商品購入処理
	public function sales_02(Request $request) {
		$request->validate([
			'product_id' => 'required|integer|exists:products,id',
			'quantity' => 'required|integer|min:1'
		]);
		try {
			DB::beginTransaction();
			//01_商品を取得
			$product = Product::find($request->product_id);
			//02_在庫チェック
			if ($product->stock <= 0) {
				throw new \Exception('この商品は在庫切れです', 423);
			}
			if ($product->stock < $request->quantity) {
				throw new \Exception('在庫が不足しています', 422);
			}
			//03_salesテーブルに購入情報を追加
			$sale= new Sale();
			$sale->product_id = $product->id;
			$sale->quantity = $request->quantity;
			$sale->save();
			//04_在庫を減算
			$product->stock -= $request->quantity;
			$product->save();
			DB::commit();
			//05_正常レスポンス
			return response()->json([
				'result' => true,
				'message' => '購入が完了しました',
				'sale' => [
					'id' => $sale->id,
					'product_id' => $sale->product_id,
					'created_at' => $sale->created_at,
					'updated_at' => $sale->updated_at,
					'quantity'=> $request->quantity,
				],
				'product' => $product,
			], 200);
		} catch (\Exception $e) {
			DB::rollBack();
			$status = ($e->getCode() >= 100 && $e->getCode() < 600) ? $e->getCode() : 500;
			return response()->json([
				'result' => false,
				'error' => [
					'messages' => [$e->getMessage()],
				],
			], $status);
		}
	}
}
