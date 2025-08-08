<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
			ユーザーログイン画面
		</title>
		<style>
			body {
				font-family: 'Arial', sans-serif;
				display: flex;
				justify-content: center;
				align-items: center;
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
			.main{
				text-align: center;
				box-sizing: border-box;
			}
			.message {
				margin-bottom: 20px;
				font-weight: bold;
			}
			.error {
				color: red;
			}
			.success {
				color: green;
			}
			.main h2 {
				font-size: 50px;
			}
			form {
				display: inline-block;
				margin: 0 auto;
			}
			input {
				display: block;
				width: 50ch;
				font-size: 25px;
				margin-bottom: 40px;
			}
			.new_btn {
				margin-right: 200px;
				font-size: 16px;
				width: 100px;
				background-color: rgba(255, 165, 0, 0.7);
				padding: 10px 10px;
				border-radius: 20px;
				border: none;
				cursor: pointer;
			}
			.new_btn:hover {
				background-color: rgba(255, 165, 0, 1);
				padding: 10px 10px;
				border-radius: 20px;
				border: none;
			}
			.login_btn {		
				margin-left: 200px;
				width: 100px;
				font-size: 16px;
				background-color: rgba(0, 225, 255, 0.5);
				padding: 10px 10px;
				border-radius: 20px;
				border: none;
			}
			.login_btn:hover {		
				background-color: rgba(0, 225, 255, 1);
				padding: 10px 10px;
				border-radius: 20px;
				border: none;
				cursor: pointer;
			}
		</style>
	</head>
	<body>
		<div class="main">
			<h2>
				ユーザーログイン画面
			</h2>
			<!-- セッションに'success'キーがあればその内容を表示 -->
			<?php if(session('success')): ?>
				<div class="success">
					<?php echo e(session('success')); ?>

				</div>
			<?php endif; ?>
			<!-- バリデーションエラー中に'login_error'があればその最初のメッセージを表示 -->
			<?php if($errors->any()): ?>
				<div class="message error">
					<ul>
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<li>
								<?php echo e($error); ?>

							</li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</ul>
				</div>
			<?php endif; ?>
			<form method="post" action="<?php echo e(route('user_login_03')); ?>">
				<?php echo csrf_field(); ?>
				<div class="input_text">
					<input type="password" name="password" placeholder="パスワード">
					<input type="email" name="email" placeholder="メールアドレス" autocomplete="new_email" />
				</div>
				<div class="input_btn">
					<button class="new_btn" type="button" onclick="location.href='<?php echo e(route('user_new_registration_01')); ?>'">
						新規登録
					</button>
					<button class="login_btn" type="submit">
						ログイン
					</button>
				</div>
			</form>
		</div>
	</body>
</html>
<?php /**PATH C:\xampp\htdocs\CyTech_test\resources\views/01_user_login.blade.php ENDPATH**/ ?>