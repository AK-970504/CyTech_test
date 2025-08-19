<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

//ログイン処理やログアウト処理のメゾットを定義
class AuthenticatedSessionController extends Controller {

	//ログイン画面などにビューを表示するための関数
	public function create(): View {
		//01_user_login.blade.phpを返す
		return view('01_user_login');
	}

	//ログインフォームから送信されたデータ(メアド･パスワード)を処理する関数
	public function store(LoginRequest $request): RedirectResponse {
		/*LoginRequestクラスに定義されたメソッドを呼び出して
		  ユーザーの認証(ログイン)を実行する処理*/
		$request->authenticate();
		//セッションIDを再生成する処理
		$request->session()->regenerate();
		//ルート名[product_list_01]へリダイレクトする処理
		return redirect()->Route('product_list_01');
	}

	/*ログアウト処理*/
	public function destroy(Request $request): RedirectResponse {
		//特定のガード(web)を使ってログアウト処理を実行
		Auth::guard('web')->logout();
		//現在のセッションを完全に無効化(破棄)する処理
		$request->session()->invalidate();
		//CSRFトークン(クロスサイトリクエストフォージェリ対策用のトークン)を新しく生成し直す処理
		$request->session()->regenerateToken();
		////URL[user_login_02]へリダイレクトする処理
		return redirect('user_login_02');
	}
}
