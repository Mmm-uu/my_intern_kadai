<h2>新規登録</h2>
<?php if (isset($error)) echo '<p style="color:red;">'.$error.'</p>'; ?>

<form action="<?php echo Uri::create('auth/signup'); ?>" method="post">
	<?php echo \Form::csrf(); ?>
	<label>ユーザー名：<input type="text" name="username" required></label><br/>
	<label>パスワード：<input type="password" name="password" required></label><br/>
	<button type="submit">登録</button>
</form>

<style>
	/* フォーム全体を中央揃え */
	form {
		max-width: 350px;
		margin: 30px auto;
		padding: 20px;
		border: 1px solid #ddd;
		border-radius: 8px;
		background-color: #fafafa;
		box-shadow: 0 2px 8px rgba(0,0,0,0.1);
		font-family: "Helvetica Neue", Arial, sans-serif;
	}

	/* 見出し */
	h2 {
		text-align: center;
		font-size: 1.4rem;
		margin-bottom: 20px;
	}

	/* エラー表示 */
	p[style*="color:red"] {
		text-align: center;
		margin-bottom: 15px;
		font-weight: bold;
	}

	/* ラベルと入力欄 */
	label {
		display: block;
		margin-bottom: 15px;
		font-weight: bold;
		font-size: 0.95rem;
	}

	input[type="text"],
	input[type="password"] {
		width: 100%;
		padding: 8px 10px;
		font-size: 0.95rem;
		border: 1px solid #ccc;
		border-radius: 4px;
		margin-top: 5px;
		box-sizing: border-box;
	}

	input[type="text"]:focus,
	input[type="password"]:focus {
		border-color: #4a90e2;
		outline: none;
	}

	/* 登録ボタン */
	button[type="submit"] {
		width: 100%;
		padding: 10px;
		font-size: 1rem;
		background-color: #4a90e2;
		color: white;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		font-weight: bold;
		transition: background-color 0.2s ease-in-out;
	}

	button[type="submit"]:hover {
		background-color: #357ab8;
	}
</style>