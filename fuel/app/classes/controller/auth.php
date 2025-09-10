<?php

Class Controller_Auth extends Controller_Base
{

	//ログイン
	public function action_login()
	{
		$data = [];

		if (\Input::method() === 'POST') {
			if (!\Security::check_token()) {
					throw new \HttpInvalidInputException('不正なリクエストです');
			}

			$val = \Validation::forge();
			$val->add_field('username', 'ユーザー名', 'required|min_length[1]|max_length[10]');
			$val->add_field('password', 'パスワード', 'required|min_length[10]');

			if ($val->run()) {
				$username = $val->validated('username');
				$password = $val->validated('password');

				if (\Auth::login($username, $password)) {
					$user_id = \Auth::get_user_id()[1];
					\Cookie::set('user_id', $user_id, 0, '/', '', true, true);
					\Response::redirect('dashboard');
				}
				else {
					$data['error'] = 'ユーザー名またはパスワードが違います';
				}
			}
			else {
				$data['error'] = 'バリデーションエラー';
			}
		}

		return \View::forge('login', isset($data) ? $data : []);
	}
	


	//ログアウト
	public function action_logout()
	{
		\Auth::logout();
		\Cookie::delete('user_id', '/', '');
		\Session::instance()->destroy();
		\Response::redirect('login');
	}


	//新規登録 ユーザー名とパスワードで登録する
	public function action_signup()
	{
		$data = [];

		if (\Input::method() === 'POST') {
			if (!\Security::check_token()) {
				throw new \HttpInvalidInputException('不正なリクエストです');
			}

			$val = \Validation::forge();
			$val->add_field('username', 'ユーザー名', 'required');
			$val->add_field('password', 'パスワード', 'required|min_length[10]');

			if ($val->run()) {
				$username = $val->validated('username');
				$password = $val->validated('password');

				$existing_user = \Model_Users::find_username($username);
				if ($existing_user) {
					$data['error'] = 'このユーザー名は既に使われています';
				}
				else {
					//ユニークなメールアドレス用
					$random = \Str::random('alnum', 8);

					$user_id = \Auth::create_user(
						$username,
						$password,
						$random . '@example.com',
						1 
					);

					if ($user_id) {
						// 登録成功 → 自動ログイン
						\Auth::login($username, $password);
						\Response::redirect('dashboard');
					} else {
						$data['error'] = '登録に失敗しました';
					}
				}
			}
			else {
				$data['error'] = 'バリデーションエラー';
			}
		}

		return \View::forge('signup', $data);
	}


}			
?>