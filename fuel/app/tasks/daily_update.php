<?php

namespace Fuel\Tasks;

class Daily_update
{
	public function run()
	{
		$users = \DB::select('id')
								->from('users')
								->execute()
								->as_array();
		
		foreach ($users as $user) {
			$user_id = $user['id'];
			$tomorrow_actions = \Model_Records::get_tomorrow_actions($user_id);
	

			if (empty($tomorrow_actions)) {
				echo "No actions found for user_id: {$user_id}\n";
				continue;
			}

			foreach ($tomorrow_actions as $action) {
				\DB::insert('records')->set([
						'action_id' => $action['action_id'],
						'date'      => \DB::expr('CURRENT_TIMESTAMP'),
						'status'    => 0,
						'next_at'   => \DB::expr("DATE_ADD(CURRENT_TIMESTAMP, INTERVAL {$action['frequency']} DAY)")
				])->execute();

				$display_exist = \DB::select('*')
														->from('display')
														->where('action_id', '=', $action['action_id'])
														->and_where(\DB::expr('DATE(next_target_at)'), '=',  \DB::expr('CURDATE()'))
														->execute()
														->current();

				if (!$display_exist) {
					\DB::insert('display')->set([
							'action_id' => $action['action_id']
					])->execute();
				}
			}
		}
		echo "New records inserted.\n";
	}
}