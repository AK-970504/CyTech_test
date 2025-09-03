<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller {
	//[http://localhost:301/product_list]にリダイレクト
	public function product_list_01(Request $request) {
		//ルーティングされているかLogで確認
		Log::debug('00006が呼び出されました');
		//商品一覧を取るときに、その商品の会社情報も一緒にまとめて取得する
		$query = Product::with('company');
		//キーワード検索(商品名)
		if ($request->filled('keyword')) {
			$keyword = $request->input('keyword');
			$query->where(function ($q) use ($keyword) {
				$q->where('product_name', 'like', '%' . $keyword . '%')
				  ->orWhere('price', 'like', '%' . $keyword . '%')
				  ->orWhere('stock', 'like', '%' . $keyword . '%');
			});
		}
		//⬆⬇ソート機能
		$sort = $request->input('sort', null);
		switch ($sort) {
			case 'id_asc':
				$query->orderBy('id', 'asc');
				break;
			case 'id_desc':
				$query->orderBy('id', 'desc');
				break;
			case 'price_asc':
				$query->orderBy('price', 'asc');
				break;
			case 'price_desc':
				$query->orderBy('price', 'desc');
				break;
			case 'stock_asc':
				$query->orderBy('stock', 'asc');
				break;
			case 'stock_desc':
				$query->orderBy('stock', 'desc');
				break;
			default:
				$query->orderBy('id', 'asc');
				break;
		}
		//商品を取得(新しい順)して最大20件に調整（空行含めるために必要な数を追加
		$products = $query->take(20)->get();
		//空の行を追加(20件未満だったら)
		$emptyCount = 20 - $products->count();
		for ($i =0; $i <$emptyCount; $i++) {
			$products->push(null);
		}
		//メーカー一覧取得
		$companies = company::all();
		if ($request->ajax()) {
			return view('03-99_product_list_items', [
				'products' => $products,
				'sort' => $sort,
			]);
		} else {
			return view('03_product_list', compact('products', 'companies', 'request', 'sort'));
		}
	}

	//[http://localhost:301/product_new_registration]にアクセスした場合
	public function product_new_registration_01() {
		Log::debug('00007が呼び出されました');
		$companies = Company::all();
		//[http://localhost:301/user_login]にアクセス
		return view('04_product_new_registration', compact('companies'));
	}

	public function product_new_registration_02(Request $request) {
		//ルーティングされているかLogで確認
		Log::debug('00008が呼び出されました');
		//フォームから送られてきた値をチェックしてOKなら以下の値を取得する
		//ルールに違反していれば自動的にリダイレクトしてエラー表示
		$validated = $request->validate([
			//productフィールドに入力が必須(空だとエラー)
			'product_name' => 'required',
			//makerフィールドに入力が必須(空だとエラー)
			'company_id' => 'required',
			//priceフィールドに入力が必須(空だとエラー)
			'price' => 'required',
			//stockフィールドに入力が必須(空だとエラー)
			'stock' => 'required',
			//stockフィールドに入力が必須(空だとエラー)
			'comment' => 'nullable|string',
			//img_pathフィールドに入力が必須(空だとエラー)
			'img_path' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
		], [
			//エラーの内容を表示
			'product_name.required' => '商品名が未入力です',
			'company_id.required' => 'メーカー名が未入力です',
			'price.required' => '価格が未入力です',
			'stock.required' => '在庫数が未入力です',
			'img_path.required' => '商品画像を選択してください',
			'img_path.image' => '有効な画像ファイルを指定してください',
			'img_path.mimes' => '画像はjpg, jpeg, png, gif形式でアップロードしてください',
			'img_path.max' => '画像サイズは2MB以下にしてください',
		]);
		//新しいメーカー名が入力された場合の処理
		if ($request->input('company_id') === 'new') {
			//新しい会社名を取得
			$newCompanyName = $request->input('new_company_name');
			//全角★半角統一処理
			$convertedCompanyName = mb_convert_kana($newCompanyName, 'KVas');
			//変換済みの名前をリクエストに再代入(バリデーション用)
			$request->merge(['new_company_name' => $convertedCompanyName]);
			//バリデーション
			$request->validate([
				'new_company_name' => 'required',
			], [
				'new_company_name.required' => '新しいメーカー名を入力してください',
			]);
			//既存チェック
			$exists = Company::whereRaw('LOWER(company_name) = ?', [strtolower($convertedCompanyName)])->exists();
			if ($exists) {
				return back()->withErrors(['new_company_name' => '既に登録されているメーカー名です。リストから選択してください。'])->withInput();
			}
			//登録
			$newCompany = Company::create([
				'company_name' => $request->input('new_company_name')
			]);
			$validated['company_id'] = $newCompany->id;
		}
		//商品登録時にエラーが発生した場合に、例外をキャッチして適切に処理する
		try {
			$extension = $request->file('img_path')->getClientOriginalExtension();
			$imageName = time() . '_' . uniqid() . '.' . $extension;
			$path = $request->file('img_path')->storeAs('images', $imageName, 'public');
			Log::debug('アップロードされたファイル名: ' . $request->file('img_path')->getClientOriginalName());
			Log::debug('保存先パス: ' . $path);
			Log::debug('保存先絶対パス: ' . storage_path('app/public/images/' . $imageName));
			Log::debug('ファイルの存在確認: ' . (file_exists(storage_path('app/public/images/' . $imageName)) ? 'あり' : 'なし'));
			$imageName = basename($path);
			//最小IDを取得して次のIDを決定
			$minId = Product::min('id');
			$newId = is_null($minId) ? 1 : $minId + 1;
			//DBへの新規商品登録処理
			Product::create([
				//IDを明確に指定
				'id' => $newId,
				//入力された商品名をそのまま保存
				'product_name' => $validated['product_name'],
				//入力されたメーカー名をそのまま保存
				'company_id' => $validated['company_id'],
				//入力された価格をそのまま保存
				'price' => $validated['price'],
				//入力された在庫数をそのまま保存
				'stock' => $validated['stock'],
				//コメントがnullなら空文字にしておく
				'comment' => $validated['comment'] ?? '',
				'img_path' => $imageName,
			]);
		//もしtry内で何らかの例外が発生したらこのブロックで受け取り処理する
		} catch (\Exception $e) {
			//エラーメッセージをログに出力
			Log::error('商品登録失敗: ' . $e->getMessage());
			//商品にエラーメッセージを返す
			return back()->withErrors(['error' => '商品登録に失敗しました。']);
		};
		Log::debug('validated data: ' . json_encode($validated));
		Log::debug('imageName: ' . $imageName);
		//登録が成功した場合は[http://localhost:301/product_new_registration]にリダイレクトしてコメント表示
		return redirect('product_new_registration')->with('success', '商品登録が完了しました。');
	}

	public function product_delete($id, Request $request) {
		try {
			Log::debug('00009:削除処理開始 - ID: $id');
			$product = Product::findOrFail($id);
			$product->delete();
			if ($request->ajax()) {
				return response()->json([
					'status' => 'success',
					'message' => '商品を削除しました',
				]);
			}
			return redirect()->route('product_list_01')->with('success', '商品を削除しました');
		} catch (\Exception $e) {
			Log::error('商品削除失敗: ' . $e->getMessage());
			if ($request->ajax()) {
				return response()->json([
					'status' => 'error',
					'message' => '商品の削除に失敗しました',
				], 500);
			}
			return back()->withErrors(['error' => '商品の削除に失敗しました']);
		}
	}

	public function product_detail_01($id = null) {
		Log::debug("product_detail 呼び出し - ID: $id");

		// IDがない場合はログインページにリダイレクト
		if (is_null($id)) {
			Log::warning("product_detail: IDが指定されていません。ログインページにリダイレクトします。");
			return redirect('/user_login');
		}
		$product = Product::with('company')->findOrFail($id);
		return view('05_product_detail', compact('product'));	
	}

	public function product_edit_01($id = null) {
		Log::debug("product_edit 呼び出し - ID: $id");

		// IDがない場合はログインページにリダイレクト
		if (is_null($id)) {
			Log::warning("product_edit: IDが指定されていません。ログインページにリダイレクトします。");
			return redirect('/user_login');
		}
		$product = Product::with('company')->findOrFail($id);
		$companies = Company::all();
		return view('06_product_edit', compact('product', 'companies'));	
	}

	public function product_edit_02(Request $request, $id) {
		Log::debug('00013: 商品更新処理開始 - ID:'. $id);
		$validated = $request->validate([
			'product_name' => 'required',
			'company_id' => 'required',
			'price' => 'required|integer',
			'stock' => 'required|integer',
			'comment' => 'nullable|string',
			'img_path' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
		], [
			'product_name.required' => '商品名が未入力です',
			'company_id.required' => 'メーカー名が未入力です',
			'price.required' => '価格が未入力です',
			'stock.required' => '在庫数が未入力です',
			'img_path.required' => '商品画像を選択してください',
			'img_path.image' => '有効な画像ファイルを指定してください',
			'img_path.mimes' => '画像はjpg, jpeg, png, gif形式でアップロードしてください',
			'img_path.max' => '画像サイズは2MB以下にしてください',
		]);
		$product = Product::findOrFail($id);
		// 新しいメーカーの処理（オプション）
		if ($request->input('company_id') === 'new') {
			$newCompanyName = mb_convert_kana($request->input('new_company_name'), 'KVas');
			$request->merge(['new_company_name' => $newCompanyName]);
			$request->validate([
				'new_company_name' => 'required',
			], [
				'new_company_name.required' => '新しいメーカー名を入力してください',
			]);
			$exists = Company::whereRaw('LOWER(company_name) = ?', [strtolower($newCompanyName)])->exists();
			if ($exists) {
				return back()->withErrors(['new_company_name' => '既に登録されているメーカー名です'])->withInput();
			}
			$newCompany = Company::create(['company_name' => $newCompanyName]);
			$validated['company_id'] = $newCompany->id;
		}
		// 画像が新しくアップロードされた場合
		if ($request->hasFile('img_path')) {
			$originalName = $request->file('img_path')->getClientOriginalName();
			$path = $request->file('img_path')->storeAs('images', $originalName, 'public');
			$validated['img_path'] = basename($path);
		}
		$product->fill($validated);
		$product->save();
		// 商品情報を更新
		$product->update($validated);
		return redirect()->route('product_edit_01', ['id' => $product->id])->with('success', '商品情報を更新しました');
	}
	
}
