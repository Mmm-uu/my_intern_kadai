<?php

class Controller_Dashboard extends Controller_Base
{
	//ログイン状態を確認する
	public function before()
	{
		parent::before();
		if (!\Auth::check()){
				\Response::redirect('login');
		}
	}

	//モデルからデータをとってきて、ビューのdashboard.phpに、データと部分ビューを渡す
	public function action_index()
	{
		list(, $user_id) = \Auth::get_user_id();
		$user_name = \Auth::get_screen_name();

		//行動一覧用のデータとビュー
		$actions = \Model_Actions::get_all_actions($user_id);               
		$actions_view = \View::forge('index');

		//今日の行動一覧用のデータとビュー
		$today_actions = \Model_Records::get_today_actions($user_id);
		$today_view = \View::forge('today');

		//28日間の記録用のデータとビュー
		$display = \Model_Display::get_continuous_display($today_actions);
		$display_view = \View::forge('display');

		//設定画面のビュー
		$setting_view = \View::forge('setting');

		$data = [
				'user_name' => $user_name,
				'actions' => $actions,
				'today_actions' => $today_actions,
				'display' => $display,
				'actions_view' => $actions_view,
				'today_view' => $today_view,
				'display_view' => $display_view,
				'setting_view' => $setting_view,
		];
		
		return \View::forge('dashboard', $data, false);
	}


	//チェックボックスの状態を受け取る
	public function action_completed()
	{
		$data = \Input::json();	//$dataは、action_idとstatus
		
		if (\Input::method() !== 'POST') {
			return \Response::forge(
			json_encode(['status' => 'error']), 
			400, 
			['Content-Type' => 'application/json']
			);
		}

		$val = \Validation::forge();
    $val->add_field('action_id', 'Action ID', 'required|valid_string[numeric]');
    $val->add_field('status', 'Status', 'required|match_pattern[/^[01]$/]');

		if ($val->run($data)) {

			\Model_Records::change_status_record($data);
			$change_display = \Model_Display::change_display($data);

			return \Response::forge(
				json_encode([
					'status' => 'success',
					'change_display' => $change_display
				]), 
				200, 
				['Content-Type' => 'application/json']
			);
		}
		else {
			return \Response::forge(
				json_encode(['status' => 'error']), 
				400, 
				['Content-Type' => 'application/json']
			);
		}
	}
	

	//行動を追加する
	public function action_setting()
	{
		$data = \Input::json();  //$dataはnameとfrequency

		if (\Input::method() !== 'POST') {
			return \Response::forge(
				json_encode(['status' => 'error']), 
				400, 
				['Content-Type' => 'application/json']
			);
		}

		$val = \Validation::forge();
    $val->add_field('name', 'Name', 'required|max_length[10]');
		$val->add_field('frequency', 'Frequency', 'required|valid_string[numeric]|min[1]');
		
		if ($val->run($data)) {

			list(, $user_id) = \Auth::get_user_id();
			$data['user_id'] = $user_id;

			$added_action = \Model_Actions::create_new_action($data); 
			$added_display = \Model_Display::create_display($added_action);
			$added_record = \Model_Records::create_new_record($data);//順番
			
			return \Response::forge(
				json_encode([
					'status' => 'success',
					'added_action' => $added_action,
					'added_record' => $added_record,
					'added_display' => $added_display
				]), 
				200, 
				['Content-Type' => 'application/json']
			);
		}
		else {
			return \Response::forge(
				json_encode(['status' => 'error', 'errors' => $val->error()]), 
				400, 
				['Content-Type' => 'application/json']
			);
		}
	}


	//行動の設定を編集する
	public function action_edit() 
	{
		$data = \Input::json(); //$dataはname,frequency,id(action),before_frequency

		if (\Input::method() !== 'POST') {
			return \Response::forge(
				json_encode(['status' => 'error']), 
				400, 
				['Content-Type' => 'application/json']
			);
		}

		$val = \Validation::forge();
    $val->add_field('name', 'Name', 'required|max_length[10]');
		$val->add_field('frequency', 'Frequency', 'required|valid_string[numeric]|min[1]');
		$val->add_field('id', 'Action ID', 'required|valid_string[numeric]');
		$val->add_field('before_frequency', 'Before Frequency', 'required|valid_string[numeric]|min[1]');

		if ($val->run($data)) {

			$edited_action = \Model_Actions::edit_action($data); //$dataはname,frequency,id(action),before_frequency
			$record_result = \Model_Records::edit_record($data); //$record_resultがnullなら今日の行動に変化はない
			if ($record_result) {
				$display_result = \Model_Display::edit_display($record_result);
			}
			else {
				$record_result = null;
				$display_result = null;
			}
			return \Response::forge(
				json_encode([
					'status' => 'success',
					'edited_action' => $edited_action,    //$edited_actionはnameとfrequency
					'record_result' => $record_result,    //$record_resultは null か インサートした今日のレコード
					'display_result' => $display_result   //$display_resultは null か インサートした今日のグラフのデータ
				]),
				200,
				['Content-Type' => 'application/json']
			);
		}
		else {
			return \Response::forge(
				json_encode(['status' => 'error']), 
				400, 
				['Content-Type' => 'application/json']
			);
		}
	}


	//行動を削除する
	public function action_delete()
	{
		$raw = file_get_contents('php://input');
		$data = json_decode($raw, true);

		if (\Input::method() == 'POST' && $data) {

			\Model_Actions::delete_action($data);
			
			return \Response::forge(
				json_encode([
					'status' => 'success'
				]), 
				200, 
				['Content-Type' => 'application/json']
			);
		}
		return \Response::forge(
			json_encode(['status' => 'error']), 
			400, 
			['Content-Type' => 'application/json']
		);
	}
}
       
    

?>