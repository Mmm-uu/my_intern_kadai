<?php

namespace Fuel\Tasks;
use Fuel\Core\DB;

class Daily_update
{
    public function run()
    {

        $today_actions = \Model_Actions::get_today_actions();

        if (empty($today_actions)) {
            echo "No actions found for today.\n";
            return;
        }

        foreach ($today_actions as $action) {
            \DB::insert('records')->set([
                'action_id' => $action['id'],
                'date'      => \DB::expr('CURRENT_TIMESTAMP'),
                'status'    => 0,
                'next_at'   => \DB::expr("DATE_ADD(CURRENT_TIMESTAMP, INTERVAL {$action['frequency']} DAY)")
            ])->execute();
        }
        echo "New records inserted.\n";

        
    }
}