<?php

//モデル(データベースの表とつながるクラス)を[App\Models]に入れる
namespace App\Models;

//[Illuminate\Database\Eloquent\Factories\]にある[HasFactory]を使用可能にする
//[HasFactory]はそのファクトリ機能を使えるようにするマーク(機能追加)のこと
use Illuminate\Database\Eloquent\Factories\HasFactory;
//Laravelでデータベースとやり取りする「モデル」を作るために使用
use Illuminate\Database\Eloquent\Model;

//Productクラス(プロダクトモデル)を作成してLaravelのModel機能を使えるようにする
class Product extends Model {
	//Larabelのクラス内で使う｢トレイと(Trait)｣のリスト
	//トレイと(Trait)はPHP機能で｢共有の機能を複数のクラスで簡単に共有できる｣仕組み
	//クラスに「機能のかたまり」を追加するときに使用
	use HasFactory;
	/**
	*一括代入を許可するカラムの一覧
	*例:Product::create($request->all())のような操作時に悪意のある値の代入を防ぐ役割がある
	*不正な値の注入を防ぐために必要なカラムだけ記述する
	*/
	//LaravelのEloquentモデルで「一括代入(mass assignment)」を許可するカラムを指定するためのもの
	//今回の場合は下記カラムだけが[product::create()]や[$product->update()]のような一括代入で安全にセットできる
	protected $fillable = [
		'product_name',
		'company_id',
		'price',
		'stock',
		'comment',
		'img_path',
	];
	//型変換の設定(例：price を整数として扱いたい場合など)
	protected $casts = [
		'price' => 'integer',
		'stock' => 'integer',
	];

	public function company() {
		return $this->belongsTo(Company::class);
	}
}
