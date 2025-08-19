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
					<button class="detal_btn" onclick="location.href='{{ route('product_detail_01', ['id' => $product->id]) }}'">
						 詳細
					</button>
					<form class="delete" method="POST" action="{{ route('product_delete', ['id' => $product->id]) }}">
						@csrf
						@method('DELETE')
						<button class="delete_btn" data-id="{{ $product->id }}">
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
                					<td></td>
		</tr>
	@endif
@endforeach