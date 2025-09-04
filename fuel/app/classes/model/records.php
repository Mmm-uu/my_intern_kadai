<?php
class Model_Records extends \Model_Crud
{
	//recordsテーブル用
	protected static $_table_name = 'records';

	//行動を追加したときに、データを作る
	public static function create_new_record($data)  //$dataはnameとfrequencyとuser_id
	{

		$id = \DB::select('id')
						->from('actions')
						->where('user_id', '=', $data['user_id'])
						->order_by('id', 'DESC')
						->execute()
						->get('id');

		list($insert_id, $rows) = \DB::insert(self::$_table_name)->set([
																		'action_id' => $id,
																		'date'      => \DB::expr('CURRENT_TIMESTAMP'),
																		'status'    => 0,
																		'next_at'   => \DB::expr("DATE_ADD(CURRENT_TIMESTAMP, INTERVAL {$data['frequency']} DAY)"),
																])->execute();

		//追加した内容をget_today_actionsと同じ形で返す
		$added_record = \DB::select('actions.*', self::$_table_name .'.*')
												->from('actions')
												->join(self::$_table_name, 'LEFT')
												->on('actions.id', '=', self::$_table_name .'.action_id')
												->where(self::$_table_name.'.id', '=', $insert_id)
												->execute()
												->current();
		$current_streak = \DB::select('current_streak')
														->from('display')
														->where('action_id', '=', $id)
														->execute()
														->get('current_streak');
		$added_record['current_streak'] = $current_streak;        
		return $added_record;
	}


	//今日の行動を取得する
	public static function get_today_actions($user_id)
	{
		$today_actions =  \DB::select('actions.*', self::$_table_name . '.*')
														->from('actions')
														->join(self::$_table_name, 'LEFT')
														->on('actions.id', '=', self::$_table_name . '.action_id')
														->where('actions.user_id', '=', $user_id)
														->and_where('actions.deleted', '=', 0)
														->where(\DB::expr('DATE(' . self::$_table_name . '.date)'), '=', \DB::expr('CURDATE()'))
														->execute()
														->as_array();
		
		//連続記録のcurrent_streakを加える
		foreach ($today_actions as &$action) {
			$current_streak = \DB::select('current_streak')
													->from('display')
													->where('action_id', '=', $action['action_id'])
													->order_by('id', 'DESC')
													->limit(1)
													->execute()
													->get('current_streak');

			$action['current_streak'] = $current_streak ?? 0; 
		}
		unset($action);

		return $today_actions;
	}


	//明日の行動を取得する(daily_updateで使う)
	public static function get_tomorrow_actions($user_id)
	{
		return \DB::select('actions.*', self::$_table_name . '.*')
								->from('actions')
								->join(self::$_table_name, 'LEFT')
								->on('actions.id', '=', self::$_table_name . '.action_id')
								->where('actions.user_id', '=', $user_id)
								->where('actions.deleted', '=', 0)
								->where(\DB::expr('DATE(' . self::$_table_name . '.next_at)'), '=', \DB::expr('CURDATE()'))
								->execute()
								->as_array();
	}


	//チェックボタンの状態を反映する
	public static function change_status_record($data) //$dataは、action_idとstatus
	{
		\DB::update(self::$_table_name)
						->value('status', $data['status'])
						->where('action_id', '=', $data['action_id'])
						->and_where(\DB::expr('DATE(records.date)'), '=', \DB::expr('CURDATE()'))
						->execute();
	}


	//行動を編集したとき
	public static function edit_record($data) //$dataはnameとfrequencyとid(action)とbefore_frequency
	{
		//今日の予定は変えられない、今日の予定に入ってるやつは次回から変更可能(新しく今日の予定にインサートした場合も変えられない)
		//すでに今日の予定にある->next_at変更
		//もともと今日の予定じゃない->next_at変更->next_at今日ならレコードインサート

		//今日の予定のレコードがあるか
		$exist = \DB::select('*')
								->from(self::$_table_name)
								->where('action_id', '=', $data['id'])
								->and_where(\DB::expr('DATE(date)'), '=', \DB::expr('CURDATE()'))
								->execute()
								->current();

		$gap = $data['frequency'] - $data['before_frequency'];

		//今日の予定のレコードがある場合
		if ($exist) {
			$gap = $data['frequency'] - $data['before_frequency'];
			\DB::update(self::$_table_name)
			->value('next_at',  \DB::expr("DATE_ADD(DATE(next_at), INTERVAL {$gap} DAY)"))
			->where('id', '=', $exist['id'])
			->execute();
			$record_result = null;
		}
		else {
			\DB::update(self::$_table_name)
			->value('next_at',  \DB::expr("DATE_ADD(DATE(next_at), INTERVAL {$gap} DAY)"))
			->where('action_id', '=', $data['id'])
			->where(\DB::expr('DATE(next_at)'), '>', \DB::expr('CURDATE()'))
			->execute();

			//編集の結果、次の日が今日になるかどうか
			$edit_result = \DB::select('actions.*', self::$_table_name . '.*')
													->from('actions')
													->join(self::$_table_name, 'LEFT')
													->on('actions.id', '=', self::$_table_name . '.action_id')
													->where('action_id', '=', $data['id'])
													->and_where(\DB::expr('DATE(next_at)'), '=', \DB::expr('CURDATE()'))
													->execute()
													->current();

			if ($edit_result) {
				list($insert_id, $rows) = \DB::insert(self::$_table_name)->set([
																				'action_id' => $data['id'],
																				'date'      => \DB::expr('CURRENT_TIMESTAMP'),
																				'status'    => 0,
																				'next_at'   => \DB::expr("DATE_ADD(CURRENT_TIMESTAMP, INTERVAL {$data['frequency']} DAY)")
																		])->execute();
				$current_streak = \DB::select('current_streak')
												->from('display')
												->where('action_id', '=', $id)
												->order_by('id', 'DESC')
												->limit(1)
												->execute()
												->get('current_streak');
				//追加した内容をget_today_actionsと同じ形で返したい
				$record_result = \DB::select('actions.*', self::$_table_name .'.*')
														->from('actions')
														->join(self::$_table_name, 'LEFT')
														->on('actions.id', '=', self::$_table_name .'.action_id')
														->where(self::$_table_name.'.id', '=', $insert_id)
														->execute()
														->current();
				$record_result['current_streak'] = $current_streak;
			}
		}
		return $record_result;
	}
	

}

?>