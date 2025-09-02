<?php if (!empty($error)) echo '<p style="color:red;">'.$error.'</p>'; ?>

<form method="post" action="">
    <?php echo \Form::csrf(); ?>
    <label>ユーザー名: <input type="text" name="username" /></label><br/>
    <label>パスワード: <input type="password" name="password" /></label><br/>
    <input type="submit" value="ログイン" />
</form>

<a href="<?php echo Uri::create('auth/signup'); ?>">新規登録</a>