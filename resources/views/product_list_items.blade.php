<table>
	<thead>
		<tr>
			@php
				$idSort = $sort === 'id_asc' ? 'id_desc' : 'id_asc';
				$priceSort = $sort === 'price_asc' ? 'price_desc' : 'price_asc';
				$stockSort = $sort === 'stock_asc' ? 'stock_desc' : 'stock_asc';
			@endphp
			<th class="title_id">
				<a href="{{ route('show.product.list', array_merge(request()->query(), ['sort' => $idSort])) }}">
					ID
					@if($sort === 'id_asc') ▲
					@elseif($sort === 'id_desc') ▼
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
				<a href="{{ route('show.product.list', array_merge(request()->query(), ['sort' => $priceSort])) }}">
					価格
					@if($sort === 'price_asc') ▲
					@elseif($sort === 'price_desc') ▼
					@endif
				</a>
			</th>
			<th class="title_stock">
				<a href="{{ route('show.product.list', array_merge(request()->query(), ['sort' => $stockSort])) }}">
					在庫数
					@if($sort === 'stock_asc') ▲
					@elseif($sort === 'stock_desc') ▼
					@endif
				</a>
			</th>
			<th class="title_company_id">
				メーカー名
			</th>
			<th class="title_create">
				<button class="create_btn" onclick="location.href='{{ route('show.new.product.page') }}'">
					新規作成
				</button>
			</th>
		</tr>
	</thead>
	<tbody id="productTableBody">
		@foreach($products as $product)
			@if($product)
				<tr>
					<td class="id">
						{{ $product->id ?? '' }}
					</td>
					<td class="picture">
						@if($product && $product->img_path)
							<a href="#" onclick="showSampleImage(event, '{{ asset('storage/images/' . $product->img_path) }}')">
								<img src="{{ asset('storage/images/' . $product->img_path) }}" alt="商品画像">
							</a>
						@endif
					</td>
					<td class="product_name">
						{{ $product->product_name ?? '' }}
					</td>
					<td class="price">
						@if (!empty($product->price))
							¥{{ $product->price }}
						@endif
					</td>
					<td class="stock">
						{{ $product->stock ?? '' }}
					</td>
					<td class="company_id">
						{{ $product->company->company_name ?? '' }}
					</td>
					<td>
						@if($product)
							<button class="detal_btn" onclick="location.href='{{ route('show.product.detail', ['id' => $product->id]) }}'">
								 詳細
							</button>
							<form class="delete" method="POST" action="{{ route('delete.product', ['id' => $product->id]) }}">
								@csrf
								@method('DELETE')
								<button class="delete_btn" type="submit" data-id="{{ $product->id }}">
									削除
								</button>
							</form>
						@endif
					</td>
				</tr>
			@else
				<tr class="empty">
					<td class="id">
						&nbsp;
					</td>
                	<td class="picture">
						&nbsp;
					</td>
                	<td class="product_name">
						&nbsp;
					</td>
                	<td class="price">
						&nbsp;
					</td>
                	<td class="stock">
						&nbsp;
					</td>
                	<td class="company_id">
						&nbsp;
					</td>
        			<td>	
					</td>
				</tr>
			@endif
		@endforeach
	</tbody>
</table>