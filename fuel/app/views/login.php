<div class="login-container">
	<h2>ログイン</h2>
  <?php if (!empty($error)) echo '<p style="color:red;">'.$error.'</p>'; ?>

  <form action="<?php echo Uri::create('auth/login'); ?>" method="post">
    <?php echo \Form::csrf(); ?>
    <label>ユーザー名: <input type="text" name="username" /></label><br/>
    <label>パスワード: <input type="password" name="password" /></label><br/>
    <input type="submit" value="ログイン" />
  </form>
  <div class="signup-link">
    <a href="<?php echo Uri::create('auth/signup'); ?>">新規登録はこちら</a>
  </div>
</div>


<style>
	body {
		font-family: Arial, sans-serif;
		background-color: #f5f5f5;
		margin: 0;
		padding: 0;
	}

	.login-container {
		max-width: 400px;
		margin: 80px auto;
		padding: 30px;
		background: #fff;
		border-radius: 10px;
		box-shadow: 0 0 10px rgba(0,0,0,0.1);
	}

	.login-container h2 {
		text-align: center;
		margin-bottom: 20px;
		color: #333;
	}

	.login-container label {
		display: block;
		margin-bottom: 15px;
		font-size: 14px;
		color: #555;
	}

	.login-container input[type="text"],
	.login-container input[type="password"] {
		width: 100%;
		padding: 10px;
		margin-top: 5px;
		border: 1px solid #ccc;
		border-radius: 5px;
		font-size: 14px;
	}

	.login-container input[type="submit"] {
		width: 100%;
		padding: 12px;
		background: #007BFF;
		border: none;
		color: #fff;
		font-size: 16px;
		border-radius: 5px;
		cursor: pointer;
		transition: background 0.3s;
	}

	.login-container input[type="submit"]:hover {
		background: #0056b3;
	}

	.login-container .error {
		color: red;
		margin-bottom: 15px;
		text-align: center;
	}

	.login-container .signup-link {
		display: block;
		text-align: center;
		margin-top: 20px;
		font-size: 14px;
	}

	.login-container .signup-link a {
		color: #007BFF;
		text-decoration: none;
	}

	.login-container .signup-link a:hover {
		text-decoration: underline;
	}
</style>