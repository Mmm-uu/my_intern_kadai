<h2>新規登録</h2>
<?php if (isset($error)) echo '<p style="color:red;">'.$error.'</p>'; ?>

<form action="<?php echo Uri::create('auth/signup'); ?>" method="post">
    <label>ユーザー名：
        <input type="text" name="username" required>
    </label><br>
    <label>パスワード：
        <input type="password" name="password" required>
    </label><br>
    <button type="submit">登録</button>
</form>

