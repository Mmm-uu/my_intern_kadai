<?php
class Model_Display extends \Model_Crud
{
	//ディスプレイレコード用
	protected static $_table_name = 'display';


	//行動が追加されたときに、データを作る
	public static function create_display($added_action) //$added_actionはactions_data形式
	{
			
		list($insert_id, $rows) = \DB::insert(self::$_table_name) -> set([
																		'action_id' => $added_action['id'],
																		'start_at' => $added_action['created_at']
																])->execute();

		$added_display = \DB::select(
														self::$_table_name . '.id',
														self::$_table_name .'.action_id',
														'actions.name', 
														'actions.color',
														'current_streak',
														[\DB::expr('DATE('.self::$_table_name.'.start_at)'), 'start_at'],
														[\DB::expr('DATE('.self::$_table_name.'.last_completed_at)'), 'last_completed_at']
														) 
												->from(self::$_table_name)
												->join('actions', 'INNER')
												->on('display.action_id', '=', 'actions.id')
												->where('display.id', '=', $insert_id)
												->execute()
												->current();
		return $added_display;
	}


	//チェックボタンの状態を反映する
	public static function change_display($data) //$dataはaction_idとstatus
	{
		$frequency = \DB::select('frequency')
												->from('actions')
												->where('id', '=', $data['action_id'])
												->execute()
												->get('frequency');

		if ($data['status'] == 1) { //チェックされたとき
				\DB::update(self::$_table_name)
										->value('current_streak', \DB::expr('current_streak + 1'))
										->value('last_completed_at', \DB::expr('CURRENT_TIMESTAMP'))
										->value('next_target_at', \DB::expr("DATE_ADD(CURRENT_TIMESTAMP, INTERVAL {$frequency} DAY)"))
										->where('action_id', '=', $data['action_id'])
										->and_where_open()
												->where(\DB::expr('DATE(next_target_at)'), '=', \DB::expr('CURDATE()'))
												->or_where(\DB::expr('DATE(start_at)'), '=', \DB::expr('CURDATE()'))
										->and_where_close()
										->execute();
				
		}
		else { //チェックが外された

			//最新で何回継続中か知りたい
			$item = \DB::select('id', 'current_streak')
									->from(self::$_table_name)
									->where('action_id', '=', $data['action_id'])
									->and_where(\DB::expr('DATE(last_completed_at)'), '=', \DB::expr('CURDATE()'))
									->order_by('id', 'DESC')
									->limit(1)
									->execute()
									->current();

			if ($item['current_streak'] == 1) { //今日が連続1日目の行動 → 初期状態に戻す
					\DB::update(self::$_table_name)
									->value('current_streak', 0)
									->value('last_completed_at', null)
									->value('next_target_at', null)
									->where('action_id', '=', $data['action_id'])
									->and_where(\DB::expr('DATE(last_completed_at)'), '=', \DB::expr('CURDATE()'))
									->execute();
			}
			else {
					\DB::update(self::$_table_name)
											->value('current_streak', \DB::expr('current_streak - 1'))
											->value('last_completed_at', \DB::expr("DATE_SUB(last_completed_at, INTERVAL {$frequency} DAY)"))
											->value('next_target_at', \DB::expr('CURRENT_TIMESTAMP'))
											->where('action_id', '=', $data['action_id'])
											->and_where(\DB::expr('DATE(next_target_at)'), '=', \DB::expr("DATE_ADD(CURDATE(), INTERVAL {$frequency} DAY)"))
											->execute();
			}
		}

		$change_display = \DB::select(
														self::$_table_name . '.id',
														self::$_table_name .'.action_id',
														'actions.name', 
														'actions.color',
														'current_streak',
														'start_at',
														'last_completed_at'
												)
												->from(self::$_table_name)
												->join('actions', 'INNER')
												->on('display.action_id', '=', 'actions.id')
												->where('action_id', '=', $data['action_id'])
												->order_by(self::$_table_name . '.id', 'DESC')
												->limit(1)
												->execute()
												->current();
		return $change_display;
	}
    

	//今日の行動の記録を取得する
	public static function get_continuous_display($today_actions) 
	{
		$action_ids = array_column($today_actions, 'action_id');

		if (empty($action_ids)) {
			return [];
		}

		$result = \DB::select(
										self::$_table_name . '.id',
										self::$_table_name .'.action_id',
										'actions.name',
										'actions.color',
										'current_streak',
										[\DB::expr('DATE('.self::$_table_name.'.start_at)'), 'start_at'],
										[\DB::expr('DATE('.self::$_table_name.'.last_completed_at)'), 'last_completed_at']
										)
								->from(self::$_table_name)
								->join('actions', 'INNER')
								->on('display.action_id', '=', 'actions.id')
								->where('display.action_id', 'IN', $action_ids)
								->execute()
								->as_array();
		return $result;

	}


	//編集の結果、編集した行動が、今日の行動になった場合のときに呼ばれる
	//編集した行動の、28日間の記録に使うデータを取得する
	public static function edit_display($record_result) //$record_resultはget_today_actions形式
	{
		$edit_id = \DB::select('id')
										->from(self::$_table_name)
										->where('action_id', '=', $record_result['action_id'])
										->order_by('start_at', 'desc')
										->limit(1)
										->execute()
										->get('id');

		\DB::update(self::$_table_name)
								->value('next_target_at', \DB::expr('CURRENT_TIMESTAMP'))
								->where('id', '=', $edit_id)
								->execute();

		$display_result = \DB::select(
										self::$_table_name . '.id',
										self::$_table_name .'.action_id',
										'actions.name',
										'actions.color',
										'current_streak',
										[\DB::expr('DATE('.self::$_table_name.'.start_at)'), 'start_at'],
										[\DB::expr('DATE('.self::$_table_name.'.last_completed_at)'), 'last_completed_at']
										)
								->from(self::$_table_name)
								->join('actions', 'INNER')
								->on('display.action_id', '=', 'actions.id')
								->where('display.id', '=', $edit_id)
								->execute()
								->current();
		return $display_result;

	}
}



