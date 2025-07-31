<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller {

	//[http://localhost:301/]にアクセスした場合
	public function user_login_01() {
		//ルーティングされているかLogで確認
		Log::debug('00001が呼び出されました');
		Log::debug('Auth check: ' . (Auth::check() ? 'true' : 'false'));
		//[http://localhost:301/user_login]にアクセス
		return view('01_user_login');
	}

	//[http://localhost:301/user_login]にアクセスした場合
	public function user_login_02() {
		//ルーティングされているかLogで確認
		Log::debug('00002が呼び出されました');
		//[http://localhost:301/user_login]にアクセス
		return view('01_user_login');
	}
	
	//[http://localhost:301/user_new_registration]にアクセスした場合
	public function user_new_registration_01() {
		//ルーティングされているかLogで確認
		Log::debug('00003が呼び出されました');
		////[http://localhost:301/user_new_registration]にアクセス
		return view('02_user_new_registration');
	}

	//新規登録画面でフォームに入力して送信した場合
	public function user_new_registration_02(Request $request) {
		//ルーティングされているかLogで確認
		Log::debug('00004が呼び出されました');
		//フォームから送られてきた値をチェックしてOKなら以下の値を取得する
		//ルールに違反していれば自動的にリダイレクトしてエラー表示
		$validated = $request->validate ([
			//passwordフィールドに8文字以上の入力が必須(空だとエラー)
			'password' => 'required|min:8',
			//emailに正しいメール形式での入力が必須
			//userテーブルのemailカラムに既に登録がないか確認
            'email' => 'required|email|unique:users,email',
		], [
			//エラーの内容を表示
			'password.required' => 'パスワードは必須です。',
			'password.min' => 'パスワードは8文字以上で入力してください。',
			'email.required' => 'メールアドレスは必須です。',
			'email.email' => '有効なメールアドレスを入力してください。',
        	'email.unique' => 'このメールアドレスは既に登録されています。',
		]);
		//ユーザー登録時にエラーが発生した場合に、例外をキャッチして適切に処理する
		try {
			//DBへの新規ユーザー登録処理
			User::create([
				//入力されたパスワードを[ハッシュ化(暗号化)]する
				'password' => Hash::make($validated['password']),
				//入力されたメールアドレスをそのまま保存
				'email' => $validated['email'],
			]);
		//もしtry内で何らかの例外が発生したらこのブロックで受け取り処理する
		} catch (\Exception $e) {
			//エラーメッセージをログに出力
			Log::error('ユーザー登録失敗: ' . $e->getMessage());
			//ユーザーにエラーメッセージを返す
			return back()->withErrors(['error' => 'ユーザー登録に失敗しました。']);
		};
		//登録が成功した場合は[http://localhost:301/user_login]にリダイレクトしてコメント表示
		return redirect('user_login')->with('success', 'ユーザー登録が完了しました。ログインしてください。');
	}

	//ログイン画面でフォームに入力して送信した場合
	public function user_login_03(Request $request) {
		//ルーティングされているかLogで確認
		Log::debug('00005が呼び出されました');
		//フォームから送られてきた値をチェックしてOKなら以下の値を取得する
		//ルールに違反していれば自動的にリダイレクトしてエラー表示
		$credentials = $request->validate([
			//passwordフィールドに入力が必須(空だとエラー)
			'password' => 'required',
			//emailに正しいメール形式での入力が必須
			'email' => 'required|email',
		], [
			//エラーの内容を表示
			'password.required' => 'パスワードが未入力です。',
			'email.required' => 'メールアドレスが未入力です。',
			'email.email' => '有効なメールアドレス形式で入力してください。',
		]);
		//ユーザーが存在するか確認
		if (Auth::attempt($credentials)) {
			//Logにログイン成功を表示
			Log::debug('ログインに成功しました');
			//存在する場合はログイン後にセッションIDの再生成
			$request->session()->regenerate();
			//次に[http://localhost:301/product_list]にリダイレクト
			return redirect()->route('product_list_01');
		} else {
			//Logにログイン失敗を表示
			Log::debug('ログインに失敗しました');
			//ログイン失敗の場合はエラーの表示
			return back()->withErrors([
				'Login_error' => 'メールアドレスかパスワードが違います',
			]);
		}
	}
}