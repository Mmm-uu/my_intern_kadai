<?php

Class Controller_Auth extends Controller_Base
{

	//ログイン
	public function action_login()
	{
		if (\Input::method() === 'POST') {
			if (!\Security::check_token()) {
				throw new \HttpInvalidInputException('不正なリクエストです');
			}

			$username = \Input::post('username');
			$password = \Input::post('password');

			if (\Auth::login($username, $password)) {
				$user_id = \Auth::get_user_id()[1];
				\Cookie::set('user_id', $user_id, 60*60*24*365, '/', '', true, true);
				\Response::redirect('dashboard');
			}
			else {
				$data['error'] = 'ユーザー名またはパスワードが違います';
			}
		}
		return \View::forge('login', isset($data) ? $data : []);
	}


	//ログアウト
	public function action_logout()
	{
		\Auth::logout();
		\Cookie::delete('user_id', '/');
		\Session::instance()->destroy();
		\Response::redirect('login');
	}


	//新規登録 ユーザー名とパスワードで登録する
	public function action_signup()
	{
		if (\Input::method() === 'POST') {
			if (!\Security::check_token()) {
				throw new \HttpInvalidInputException('不正なリクエストです');
			}

			$username = \Input::post('username');
			$password = \Input::post('password');

			if (!$username || !$password) {
				$data['error'] = 'ユーザー名とパスワードを入力してください';
				return \View::forge('signup', $data);
			}

			if (strlen($password) < 10) {
				$data['error'] = 'パスワードを長くしてください';
				return \View::forge('signup', $data);
			}

			$existing_user = \Model_Users::find_username($username);
			if ($existing_user) {
				$data['error'] = 'このユーザー名は既に使われています';
				return \View::forge('signup', $data);
			}

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
				return \View::forge('signup', $data);
			}
		}

		// 初回表示
		return \View::forge('signup');
	}


}			
?>