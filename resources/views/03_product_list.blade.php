<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
			商品一覧画面
		</title>
		<style>
			body {
				font-family: 'Arial', sans-serif;
				display: flex;
				justify-content: center;
				height: 100vh;
				margin: 0 auto;
				user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			li {
				list-style: none;
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
			.main h2 {
				text-align: left;
				font-size: 50px;
			}
			.text {
				display: flex;
				margin-bottom: 40px;
			}
			.text input, .text select {
				display: inline-block;
				width: 25ch;
				font-size: 20px;
				margin-top: 0;
				margin-right: 20px;
				border: 1px solid rgba(205, 205, 205, 1);
				background-color: rgba(255, 255, 255, 1);
			}
			#maker_select {
				color: rgba(205, 205, 205, 1);
			}
			.seach_btn {
				font-size: 20px;
				border: 1px solid rgba(205, 205, 205, 1);
				border-radius: 5px;
				background-color: rgba(255, 255, 255, 1);
				cursor: pointer;
			}
			table {
				width: 100%;
				border-collapse: collapse;
				margin-bottom: 10px;
			}
			thead {
				border: 1px solid rgba(205, 205, 205, 1);
			}
			tbody:first-of-type {
				border-top: none;
			}
			tr {
				border: 1px solid rgba(205, 205, 205, 1);
				height: 45px;
			}
			tbody tr:nth-child(2n+2) {
				background-color: rgba(150, 150, 150, 1);
			}
			th, td {
				padding: 10px;
				text-align: center;
				user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			.id {
				width: 5ch;
			}
			.picture {
				width: 10ch;
			}
			.show_img_btn {
				border: 0;
				background-color: transparent;
			}
			.picture img {
				width: 80px;
				height: auto;
				object-fit: contain;
				transition: transform 0.2s ease;
			}
			.picture img:hover{
				transform: scale(1.05);
				cursor: pointer;
			}
			.product_name {
				width: 20ch;
			}
			.price {
				width: 10ch;
			}
			.stock {
				width: 8ch;
			}
			.company_id {
				width: 18ch;
			}
			.title_create {
				width: 15ch;
			}
			.title_create button {
				border: none;
				background-color: rgba(255, 165, 0, 0.7);
				padding: 5px 10px;
				border-radius: 5px;
				margin: 0 auto;
				cursor: pointer;
			}
			.title_create button:hover {
				border: none;
				background-color: rgba(255, 165, 0, 1);
				padding: 5px 10px;
				border-radius: 5px;
				margin: 0 auto;
				cursor: pointer;
			}
			.detal_btn {
				border: none;
				background-color: rgba(0, 255, 255, 0.5);
				border-radius: 5px;
				margin: 0 5px;
				cursor: pointer;
			}
			.detal_btn:hover {
				border: none;
				background-color: rgba(0, 255, 255, 1);
				border-radius: 5px;
				margin: 0 5px;
				cursor: pointer;
			}
			.delete {
				display: inline;
			}
			.delete_btn {
				border: none;
				background-color: rgba(255, 100, 0, 0.7);
				border-radius: 5px;
				margin: 0 5px;
				cursor: pointer;
			}
			.delete_btn:hover {
				border: none;
				background-color: rgba(255, 100, 0, 1);
				border-radius: 5px;
				margin: 0 5px;
				cursor: pointer;
			}
			.pagination {
				text-align: center;
				margin-top: 10px;
			}
			.pagination button {
				margin: 0 5px;
				padding: 5px 10px;
			}
			.pagination .active {
				font-weight: bold;
			}
		</style>
		<script>
			function showSampleImage(event, imagePath) {
				event.preventDefault();
				const popupWidth = screen.availWidth;
				const popupHeight = screen.availHeight;
				const left = 0;
				const top = 0;
				window.open(imagePath, '画像表示', `width=${popupWidth},height=${popupHeight},top=${top},left=${left},resizable=yes,scrollbars=yes`);
			}
			document.getElementById('searchForm').addEventListener('submit', function(e) {
				e.preventDefault();
				const form = e.target;
				const formData = new FormData(form);
				const params = new URLSearchParams(formData).toString();
				fetch("{{ route('product_list_01') }}?" + params, {
					headers: { 'X-Requested-With': 'XMLHttpRequest' }
				})
				.then(response => response.text())
				.then(html => {
					document.getElementById('productTableBody').innerHTML = html;
				})
				.catch(err => console.error(err));
			})
			document.addEventListener('DOMContentLoaded', function() {
				const tableBody = document.getElementById('productTableBody');
				tableBody.addEventListener('click', function(e) {
					if (e.target.classList.contains('delete_btn')) {
						const id = e.target.dataset.id;
						if (!confirm('本当に削除しますか？')) return;
						fetch(`/product/${id}/delete`, {
							method: 'DELETE',
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}',
								'X-Requested-With': 'XMLHttpRequest'
							}
						})
						.then(response => response.json())
						.then(data => {
							if (data.status === 'success') {
								const row = e.target.closest('tr');
								row.remove();
								alert(data.message);
							} else {
								alert(data.message || '削除に失敗しました');
							}
						})
						.catch(err => {
							console.error(err);
							alert('削除中にエラーが発生しました');
						});
					}
				});
			});
		</script>
	</head>
	<body>
		<div class="main">
			<h2>
				商品一覧画面
			</h2>
			<div class="text">
				<form id="searchForm">
					<input type="text" name="keyword" autocomplete="off" placeholder="検索キーワード" value="{{ $request->keyword ?? '' }}">
					<select name="company_id">
						<option id="maker_select" value="" disabled hidden {{ old('company_id') === null ? 'selected' : '' }}>
							メーカー名
						</option>
						@foreach ($companies as $company)
							<option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
								{{ $company->company_name }}
							</option>
						@endforeach
					</select>
					<button class=seach_btn>
						検索
					</button>
				</form>
			</div>
			<div class="table">
				<table>
					<thead>
						<tr>
							<th class="title_id">
								<a href="{{ route('product_list_01', array_merge(request()->query(), ['sort' => request('sort') === 'id_asc' ? 'id_desc' : 'id_asc'])) }}">
									ID
									@if(request('sort') === 'id_asc')▲
									@elseif(request('sort') === 'id_desc')▼
									@endif
								</a>
							</th>
							<th class="title_picture">
								商品画像
							</th>
							<th class="title_product_name">
								商品名
							</th>
							<th class="title_price">
								<a href="{{ route('product_list_01', array_merge(request()->query(), ['sort' => request('sort') === 'price_asc' ? 'price_desc' : 'price_asc'])) }}">
									価格
									@if(request('sort') === 'price_asc')▲
									@elseif(request('sort') === 'price_desc')▼
									@endif
								</a>
							</th>
							<th class="title_stock">
								<a href="{{ route('product_list_01', array_merge(request()->query(), ['sort' => request('sort') === 'stock_asc' ? 'stock_desc' : 'stock_asc'])) }}">
									在庫数
									@if(request('sort') === 'stock_asc')▲
									@elseif(request('sort') === 'stock_desc')▼
									@endif
								</a>
							</th>
							<th class="title_company_id">
								メーカー名
							</th>
							<th class="title_create">
								<button class="create_btn" onclick="location.href='{{ route('product_new_registration_01') }}'">
									新規作成
								</button>
							</th>
						</tr>
					</thead>
					<tbody id="productTableBody">
						@include('03-99_product_list_items', ['products' => $products])
					</tbody>
				</table>
				<div class="pagination">
					<button>
						&lt;
					</button>
					<button class="active">
						1
					</button>
					<button>
						2
					</button>
					<button>
						&gt;
					</button>
				</div>
			</div>
		</div>
	</body>
</html>
