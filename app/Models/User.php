<?php

//モデル(データベースの表とつながるクラス)を[App\Models]に入れる
namespace App\Models;

//[Illuminate\Database\Eloquent\Factories\]にある[HasFactory]を使用可能にする
//[HasFactory]はそのファクトリ機能を使えるようにするマーク(機能追加)のこと
use Illuminate\Database\Eloquent\Factories\HasFactory;
//Larabelでユーザーモデル(User)にログイン･認証機能を使えるようする
use Illuminate\Foundation\Auth\User as Authenticatable;
//Larabelの通知機能(メール･LINE･Slackなど)をモデルに追加する
use Illuminate\Notifications\Notifiable;
//Laravel Sanctum の API トークン管理機能をモデルに追加する
use Laravel\Sanctum\HasApiTokens;

//Userクラス(ユーザーモデル)をつくり[Authenticatable]クラスを継承する
//[Authenticatable]クラスとはLarabelの用意した｢認証可能なユーザー｣を表す基本クラス
//ユーザー認証に必要な機能(パスワードの検証、IDの取得など)が備わっている
class User extends Authenticatable {
	//Larabelのクラス内で使う｢トレイと(Trait)｣のリスト
	//トレイと(Trait)はPHP機能で｢共有の機能を複数のクラスで簡単に共有できる｣仕組み
	//クラスに「機能のかたまり」を追加するときに使用
	use HasApiTokens, HasFactory, Notifiable;
	/**
	*一括代入を許可するカラムの一覧
	*このリストに含まれているカラムだけが一括でデータ登録や更新が可能になる
	*例: User::create($request->all()) のような操作時に悪意のある値の代入を防ぐ役割がある
	*@var string[]で文字列の配列でカラム名を指定する
	*/
	//LaravelのEloquentモデルで「一括代入(mass assignment)」を許可するカラムを指定するためのもの
	//今回の場合は[email]と[password]カラムだけが[User::create()]や[$user->update()]のような一括代入で安全にセットできる
	protected $fillable = [
		'email',
		'password',
	];
	/**
	*LaravelのEloquentモデルで使われるコメント（DocBlock）で、この下に続く[$hidden]プロパティの説明
	*[serialization(シリアライズ)]とは、データを[JSON]や配列などに変換する処理のこと
	*このコメントは、「シリアライズしたときに隠すべきカラムのリスト」を示す
	*つまり[$hidden]配列に書いたカラムはユーザー情報をAPIで返したり[JSON]に変換したときに表示されない
	*@var array<int, string>
	*/
	//LaravelのEloquentモデルにおけるプロパティ
	//この中にしていしたカラム(テーブルの列名)は[JSON]や配列に変換されたときに｢表示されない(非公開にされる)｣ようになる
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	*プロパティは、LaravelのEloquentモデルで**属性の型変換（キャスト）**を指定するためのもの
	*@var array<string, string>
	*/
	//[email_verified_at]カラムに入っている値を自動的に[日時オブジェクト(Carbon)に変換してくれる]
	//文字列['2025-07-22 13:30:00']を[$user->email_verified_at]で取り出すと文字列ではなく日時操作ができるCarbonオブジェクトになる
	protected $casts = [
		'email_verified_at' => 'datetime',
	];
}
