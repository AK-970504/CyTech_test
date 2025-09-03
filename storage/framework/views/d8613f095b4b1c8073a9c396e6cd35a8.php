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
			}
			li {
				list-style: none;
			}
			a {
				color: initial;
				text-decoration: none;
			}
			h2 {
				user-select: none;
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
				text-align: center
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
	</head>
	<body>
		<div class="main">
			<h2>
				商品一覧画面
			</h2>
			<div class="text">
				<form id="searchForm">
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
			<div class="table" id="productTableContainer">
				 <?php echo $__env->make('03-99_product_list_items', ['products' => $products], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			</div>
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
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				//画像ポップアップ
				window.showSampleImage = function(event, imagePath) {
					event.preventDefault();
					const popupWidth = screen.availWidth;
					const popupHeight = screen.availHeight;
					window.open(imagePath, '画像表示', `width=${popupWidth},height=${popupHeight},top=0,left=0,resizable=yes,scrollbars=yes`);
				};
				//共通:Ajaxでテーブル更新
				function fetchAndReplace(url, method = 'GET', data = null) {
					const options = {
						method: method,
						headers: { 'X-Requested-With': 'XMLHttpRequest' }
					};
					if (method === 'POST' || method === 'DELETE') {
						options.headers['X-CSRF-TOKEN'] = '<?php echo e(csrf_token()); ?>';
						options.body = data;
					}
					fetch(url, options)
						.then(res => res.text())
						.then(html => {
							document.getElementById('productTableContainer').innerHTML = html;
							//sort hidden inputを更新
							const urlObj = new URL(url, location.origin);
							const sortParam = urlObj.searchParams.get('sort');
							if (sortParam) document.getElementById('sortInput').value = sortParam;
						})
						.catch(err => console.error(err));
				}
				//hiddenでsortを取得
				const sortInput = document.createElement('input');
				sortInput.type = 'hidden';
				sortInput.name = 'sort';
				sortInput.id = 'sortInput';
				sortInput.value = '<?php echo e($sort); ?>';
				document.getElementById('searchForm').appendChild(sortInput);
				//検索フォーム_Ajax
				const searchForm = document.getElementById('searchForm');
				searchForm.addEventListener('submit', function(e) {
					e.preventDefault();
					const formData = new FormData(searchForm);
					const keyword = formData.get('keyword')?.trim();
					const companyId = formData.get('company_id');
					//条件1:ﾄﾞﾁﾗﾓからの場合は初期画面へリロード
					if (!keyword && !companyId) {
						window.location.href = "<?php echo e(route('product_list_01')); ?>";
						return;
					}
					//検索条件ありの場合はAjax検索
					formData.set('sort', '');//ソートをリセット
					sortInput.value = '';
					const params = new URLSearchParams(formData).toString();
					fetchAndReplace("<?php echo e(route('product_list_01')); ?>?" + params);
				});
				//削除ボタン_Ajax
				document.getElementById('productTableBody').addEventListener('click', function(e) {
					if (!e.target.classList.contains('delete_btn')) return;
					const id = e.target.dataset.id;
					if (!confirm('本当に削除しますか？')) return;
					fetch(`/product/${id}/delete`, {
						method: 'DELETE',
						headers: {
							'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
							'X-Requested-With': 'XMLHttpRequest'
						}
					})
					.then(res => res.json())
					.then(data => {
						if (data.status === 'success') {
							e.target.closest('tr').remove();
							alert(data.message);
						} else {
							alert(data.message || '削除に失敗しました');
						}
					})
					.catch(err => {
						console.error(err);
						alert('削除中にエラーが発生しました');
					});
				});
				//ページネーション・ソートリンク_Ajax
				document.querySelector('.table').addEventListener('click', function(e){
					let link = e.target;
					while (link && link.tagName !== 'A') {
						link = link.parentElement;
					}
					if (!link) return;
					e.preventDefault();
					fetchAndReplace(link.href);
				});
			});
		</script>
	</body>
</html>
<?php /**PATH C:\xampp\htdocs\CyTech_test\resources\views/03_product_list.blade.php ENDPATH**/ ?>