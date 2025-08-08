<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
			商品情報編集画面
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
			form {
				border: 1px solid;
				padding: 30px;
			}
			.success {
				color: rgba(0, 255, 25, 1);
				font-size: 20px;
				user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			.error {
				color: rgba(205, 0, 0, 1);
				font-size: 20px;
				user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			.id, .product, .company, .price, .stock, .comment {
				font-size: 25px;
				margin-bottom: 25px;
				align-items: flex-start;
			}
			.id_item, .product_item, .company_item, .price_item, .stock_item, .comment_item, .picture_item {
				display: inline-block;
				width: 15ch;
				line-height: 1.6;
			}
			span {
				color: rgba(255, 0, 0, 1);
			}
			.id_wrap, .product_wrap, .company_wrap, .price_wrap, .stock_wrap, .img_wrap {
				display: inline-block;
				vertical-align: top;
			}
			.id_wrap {
				user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			.product_wrap input, .company_wrap select, .price_wrap input .stock_wrap input,.img_wrap input {
				display: block;
			}
			input[type="text"], input[type="number"] {
				font-size: 25px;
				width: 45ch;
				padding-left: 0.5ch;
				border: 1px solid rgba(205, 205, 205, 1);
				background-color: rgba(255, 255, 255, 1);
				box-sizing: border-box;
			}
			select {
				font-size: 25px;
				width: 45ch;
				padding-left: 0.5ch;
				border: 1px solid rgba(205, 205, 205, 1);
				background-color: rgba(255, 255, 255, 1);
				box-sizing: border-box;
			}
			option {
				font-size: 15px;
				width: 45ch;
				padding-left: 0.5ch;
				border: 1px solid rgba(205, 205, 205, 1);
				background-color: rgba(255, 255, 255, 1);
			}
			.picture {
				font-size: 25px;
				margin-bottom: 25px;
				display: flex;
				align-items: center;
			}
			.picture-name {
				display: inline-block;
				width: 15ch;
			}
			.picture_attached {
				display: flex;
				flex-direction: column;
			}
			#new_company_name {
				display: none;
			}
			.file_line {
				display: flex;
				align-items: center;
				gap: 10px;
			}
			.file_name_text {
				font-size: 14px;
				color: rgba(255, 0, 0, 1);
				user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			input[type="file"] {
				display: none;
			}
			img {
				display:none;
				margin-top:10px;
				max-width:200px;
				border:1px solid #ccc;
			}
			.custom_file_label {
				display: inline-block;
				background-color: rgba(240, 240, 240, 1);
				border: 1px solid rgba(205, 205, 205, 1);
				border-radius: 4px;
				cursor: pointer;
				font-size: 25px;
				margin: 10px;
				line-height: 1.2;
				vertical-align: middle;
			}
			.update_btn {
				margin-right: 25px;
				font-size: 16px;
				width: 100px;
				background-color: rgba(255, 165, 0, 0.7);
				padding: 10px 10px;
				border-radius: 5px;
				border: none;
				cursor: pointer;
			}
			.update_btn:hover {
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
			document.addEventListener('DOMContentLoaded', function () {
				const select = document.getElementById('company_select');
				const input = document.getElementById('new_company_name');
				select.addEventListener('change', function () {
					if (select.value === 'new') {
						input.style.display = 'block';
					} else {
						input.style.display = 'none';
					}
				});
				// ページリロード時に "new" が選ばれていたら input を表示
				if (select.value === 'new') {
					input.style.display = 'block';
				}
			});
			function updateFileName(input) {
				const fileName = input.files.length ? input.files[0].name : '未選択';
				const fileNameElement = document.getElementById('file_name');
				const preview = document.createElement('img');
				if (fileNameElement) {
					fileNameElement.textContent = fileName;
					fileNameElement.style.color = '#000';
				}
			}
		</script>
	</head>
	<body>
		<div class="main">
			<h2>
				商品情報編集画面
			</h2>
			<!-- セッションに'success'キーがあればその内容を表示 -->
			<?php if(session('success')): ?>
				<div class="success">
					<?php echo e(session('success')); ?>

				</div>
			<?php endif; ?>
			<form class="input" method="POST" action="<?php echo e(route('product_edit_02', ['id' => $product->id])); ?>" enctype="multipart/form-data">
				<?php echo csrf_field(); ?>
				<div class="id">
					<label class="id_item">
						ID
					</label>
					<div class="id_wrap">
						<?php echo e($product->id); ?>

					</div>
				</div>
				<div class="product">
					<label class="product_item">
						商品名
						<span>
							*
						</span>
					</label>
					<div class="product_wrap">
						<input type="text" name="product_name" autocomplete="off" value="<?php echo e(old('product_name')); ?>">
						<?php if($errors->has('product_name')): ?>
							<div class="error">
								<?php echo e($errors->first('product_name')); ?>

							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="company">
					<label class="company_item">
						メーカー名
						<span>
							*
						</span>
					</label>
					<div class="company_wrap">
						<select name="company_id" id="company_select">
							<option value="" disabled <?php echo e(old('company_id') === null ? 'selected' : ''); ?>>
								<br>
							</option>
							<?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($company->id); ?>" <?php echo e(old('company_id') == $company->id ? 'selected' : ''); ?>>
									<?php echo e($company->company_name); ?>

								</option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<option value="new" <?php echo e(old('company_id') == 'new' ? 'selected' : ''); ?>>
								--新しいメーカーを入力--
							</option>
						</select>
						<?php if($errors->has('company_id')): ?>
							<div class="error">
								<?php echo e($errors->first('company_id')); ?>

							</div>
						<?php endif; ?>
						<input type="text" name="new_company_name" id="new_company_name" autocomplete="off" placeholder="新しいメーカー名を入力" value="<?php echo e(old('new_company_name')); ?>">
						<?php if($errors->has('new_company_name')): ?>
							<div class="error">
								<?php echo e($errors->first('new_company_name')); ?>

							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="price">
					<label class="price_item">
						価格
						<span>
							*
						</span>
					</label>
					<div class="price_wrap">
						<input type="number" name="price" autocomplete="off" value="<?php echo e(old('price')); ?>">
						<?php if($errors->has('price')): ?>
							<div class="error">
								<?php echo e($errors->first('price')); ?>

							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="stock">
					<label class="stock_item">
						在庫数
						<span>
							*
						</span>
					</label>
					<div class="stock_wrap">
						<input type="number" name="stock" autocomplete="off" value="<?php echo e(old('stock')); ?>">
						<?php if($errors->has('stock')): ?>
							<div class="error">
								<?php echo e($errors->first('stock')); ?>

							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="comment">
					<label class="comment_item">
						コメント
					</label>
					<input type="text" name="comment" autocomplete="off" value="<?php echo e(old('comment')); ?>">
				</div>
				<div class="picture">
  					<label class="picture_item">
						商品画像
						<span>
							*
						</span>
					</label>
					<div class="picture_attached">
						<div class="file_line">
							<label for="file" class="custom_file_label">
								ファイルを選択
							</label>
							<span id="file_name" class="file_name_text">
								未選択
							</span>
						</div>
						<div class="img_wrap">
							<input type="file" id="file" name="img_path" accept="image/*" onchange="updateFileName(this)">
							<?php if($errors->has('img_path')): ?>
								<div class="error">
									<?php echo e($errors->first('img_path')); ?>

								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="input_btn">
					<button class="update_btn" type="submit">
						更新
					</button>
					<button class="return_btn" type="button" onclick="location.href='<?php echo e(route('product_detail_01', ['id' => $product->id])); ?>'">
						戻る
					</button>
				</div>
			</form>
		</div>
	</body>
</html>
<?php /**PATH C:\xampp\htdocs\CyTech_test\resources\views/06_product_edit.blade.php ENDPATH**/ ?>