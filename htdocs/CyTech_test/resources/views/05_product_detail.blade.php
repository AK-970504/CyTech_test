<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
			商品情報詳細画面
		</title>
		<style>
			body {
				font-family: 'Arial', sans-serif;
				display: flex;
				justify-content: center;
				margin: 0 auto 50px auto;
				user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			ul {
				margin: 0;
				padding: 0;
			}
			li {
				list-style: none;
				text-decoration: none;
				user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			a {
				color: initial;
				text-decoration: none;
				user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			h2 {
				user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			label {
				user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			.main {
				margin-bottom: 50px;
			}
			.main h2 {
				text-align: left;
				font-size: 50px;
			}
			.edit {
				border: 1px solid;
				padding: 30px;
			}
			.id, .picture, .product, .company, .price, .stock, .comment {
				font-size: 25px;
				margin-bottom: 25px;
				align-items: flex-start;
			}
			.id_item, .picture_item, .product_item, .company_item, .price_item, .stock_item, .comment_item {
				display: inline-block;
				width: 15ch;
				line-height: 1.6;
			}
			.id_wrap, .picture_wrap, .product_wrap, .company_wrap, .price_wrap, .stock_wrap, .comment_wrap {
				display: inline-block;
				width: 45ch;
				vertical-align: top;
				user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			.picture_wrap, .comment_wrap {
				border: 1px solid rgba(205, 205, 205, 1);
				background-color: rgba(255, 255, 255, 1);
			}
			.picture_wrap img{
				width: 100%;
				display: block;
			}
			.productImage {
				display: none;
			}
			.edit_btn {
				margin-right: 25px;
				font-size: 16px;
				width: 100px;
				background-color: rgba(255, 165, 0, 0.7);
				padding: 10px 10px;
				border-radius: 5px;
				border: none;
				cursor: pointer;
			}
			.edit_btn:hover {
				background-color: rgba(255, 165, 0, 1);
				padding: 10px 10px;
				border-radius: 5px;
				border: none;
			}
			.return_btn {		
				margin-left: 25px;
				width: 100px;
				font-size: 16px;
				background-color: rgba(0, 225, 255, 0.5);
				padding: 10px 10px;
				border-radius: 5px;
				border: none;
			}
			.return_btn:hover {		
				background-color: rgba(0, 225, 255, 1);
				padding: 10px 10px;
				border-radius: 5px;
				border: none;
				cursor: pointer;
			}
		</style>
		<script>
			function showSampleImage(event, imagePath) {
				event.preventDefault();
				const popupWidth = screen.availWidth;
				const popupHeight = screen.availHeight;
				const left = 0;
				const top = 0;
				window.open(
					imagePath,
					'画像表示',
					`width=${popupWidth},height=${popupHeight},top=${top},left=${left},resizable=yes,scrollbars=yes`
				);
			}
		</script>
	</head>
	<body>
		<div class="main">
			<h2>
				商品情報詳細画面
			</h2>
			@if ($errors->any())
		 	   <div class="alert alert-danger">
					{{ $errors->first() }}
				</div>
			@endif
			@if (session('error'))
		    <div class="alert alert-danger">
					{{ session('error') }}
				</div>
			@endif
			<div class="edit">
				<div class="id">
					<label class="id_item">
						ID
					</label>
					<div class="id_wrap">
						{{ $product->id }}
					</div>
				</div>
				<div class="picture">
					<label class="picture_item">
						商品画像
					</label>
					<div class="picture_wrap">
						@if($product && $product->img_path)
							<a href="#" onclick="showSampleImage(event, '{{ asset('storage/images/' . $product->img_path) }}')">
								<img src="{{ asset('storage/images/' . $product->img_path) }}" alt="商品画像">
							</a>
						@endif
					</div>
				</div>
				<div class="product">
					<label class="product_item">
						商品名
					</label>
					<div class="product_wrap">
						{{ $product->product_name }}
					</div>
				</div>
				<div class="company">
					<label class="company_item">
						メーカー
					</label>
					<div class="company_wrap">
						{{ $product->company->company_name }}
					</div>
				</div>
				<div class="price">
					<label class="price_item">
						価格
					</label>
					<div class="price_wrap">
						¥{{ number_format($product->price) }}
					</div>
				</div>
				<div class="stock">
					<label class="stock_item">
						在庫数
					</label>
					<div class="stock_wrap">
						{{ $product->stock }}
					</div>
				</div>
				<div class="comment">
 						<label class="comment_item">
						コメント
					</label>
					<div class="comment_wrap">
						{{ $product->comment }}
					</div>
				</div>
				<div class="input_btn">
					<button class="edit_btn" type="button" onclick="location.href='{{ route('product_edit_01', ['id' => $product->id]) }}'">
						編集
					</button>
					<button class="return_btn" type="button" onclick="location.href='{{ route('product_list_01') }}'">
						戻る
					</button>
				</div>
			</div>
		</div>
	</body>
</html>
