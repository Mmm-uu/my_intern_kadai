<?php
class Model_Actions extends \Model_Crud
{
	//actionsテーブル用
	protected static $_table_name = 'actions';

	//削除されていない行動を取得する
	public static function get_all_actions($user_id)
	{
		$result = \DB::select('*')
									->from(self::$_table_name)
									->where('user_id', '=', $user_id)
									->and_where('deleted', '=', 0)
									->order_by('id', 'ASC')
									->execute()
									->as_array();
		return $result;
	}

	//行動が追加されたときに、データを作る
	public static function create_new_action($data)
	{
		$colorList = [
			'#ed5565',
			'#fc6e51',
			'#ffce54',
			'#a0d468',
			'#48cfad',
			'#4fc1e9',
			'#5d9cec',
			'#ac92ec',
			'#ec87c0'
		];

		$color = $colorList[array_rand($colorList)];

		list($insert_id, $rows) = \DB::insert(self::$_table_name)->set([
																		'user_id'   => $data['user_id'],
																		'name'      => $data['name'],
																		'frequency' => $data['frequency'],
																		'color'     => $color,
																])->execute();

		$added_action = \DB::select('*')
												->from(self::$_table_name)
												->where('id', '=', $insert_id)
												->execute()
												->current();
		//作ったデータを返す
		return $added_action;
	}


	//行動を編集したときに、値を変更する
	public static function edit_action($data)  //$dataはname,frequency,id(action),before_frequency
	{
		\DB::update(self::$_table_name)
				->value('name', $data['name'])
				->value('frequency', $data['frequency'])
				->where('id', '=', $data['id'])
				->execute();

		$edited_action = \DB::select('name', 'frequency')
												->from(self::$_table_name)
												->where('id', '=', $data['id'])
												->execute()
												->current();

		//変更したデータを返す
		return $edited_action;
	}


	//行動を消したときに、deletedの値を1にする
	public static function delete_action($data) //$data = actions_dataの形
	{
		\DB::update(self::$_table_name)
				->value('deleted', 1)
				->where('id', '=', $data['id'])
				->execute();
	}

}