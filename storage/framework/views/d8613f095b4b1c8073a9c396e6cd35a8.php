<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
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
				商品一覧画面
			</h2>
			<div class="text">
				<form method="GET" action="<?php echo e(route('product_list_01')); ?>">
					<input type="text" name="keyword" autocomplete="off" placeholder="検索キーワード" value="<?php echo e($request->keyword ?? ''); ?>">
					<select name="company_id">
						<option id="maker_select" value="" disabled hidden <?php echo e(old('company_id') === null ? 'selected' : ''); ?>>
							メーカー名
						</option>
						<?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($company->id); ?>" <?php echo e(old('company_id') == $company->id ? 'selected' : ''); ?>>
								<?php echo e($company->company_name); ?>

							</option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
								ID
							</th>
							<th class="title_picture">
								商品画像
							</th>
							<th class="title_product_name">
								商品名
							</th>
							<th class="title_price">
								価格
							</th>
							<th class="title_stock">
								在庫数
							</th>
							<th class="title_company_id">
								メーカー名
							</th>
							<th class="title_create">
								<button class="create_btn" onclick="location.href='<?php echo e(route('product_new_registration_01')); ?>'">
									新規作成
								</button>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php if($product): ?>
								<tr>
									<td class="id">
										<?php echo e($product->id ?? ''); ?>

									</td>
									<td class="picture">
										<?php if($product && $product->img_path): ?>
											<a href="#" onclick="showSampleImage(event, '<?php echo e(asset('storage/images/' . $product->img_path)); ?>')">
												<img src="<?php echo e(asset('storage/images/' . $product->img_path)); ?>" alt="商品画像">
											</a>
										<?php endif; ?>
									</td>
									<td class="product_name">
										<?php echo e($product->product_name ?? ''); ?>

									</td>
									<td class="price">
										<?php if(!empty($product->price)): ?>
											¥<?php echo e($product->price); ?>

										<?php endif; ?>
									</td>
									<td class="stock">
										<?php echo e($product->stock ?? ''); ?>

									</td>
									<td class="company_id">
										<?php echo e($product->company->company_name ?? ''); ?>

									</td>
									<td>
										<?php if($product): ?>
											<button class="detal_btn" onclick="location.href='<?php echo e(route('product_detail_01', ['id' => $product->id])); ?>'">
 												詳細
											</button>
											<form class="delete" method="POST" action="<?php echo e(route('product_delete', ['id' => $product->id])); ?>">
												<?php echo csrf_field(); ?>
												<?php echo method_field('DELETE'); ?>
												<button type="submit" class="delete_btn" onclick="return confirm('本当に削除しますか？')">
													削除
												</button>
											</form>
										<?php endif; ?>
									</td>
								</tr>
							<?php else: ?>
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
							<?php endif; ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH C:\xampp\htdocs\CyTech_test\resources\views/03_product_list.blade.php ENDPATH**/ ?>