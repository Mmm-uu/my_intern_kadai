<?php
class Model_Display extends \Model_Crud
{
    protected static $_table_name = 'display';

    public static function create_continuous_display($data) //$dataはaction_idとstatus
    {
        $frequency = \DB::select('frequency')
                            ->from('actions')
                            ->where('id', '=', $data['action_id'])
                            ->execute()
                            ->get('frequency');

        //前の継続記録を取得
        $previous_continuation = \DB::select('*')
                                    ->from(self::$_table_name)
                                    ->where('action_id', '=', $data['action_id'])
                                    ->and_where(\DB::expr('DATE(next_target_at)'), '=', \DB::expr('CURDATE()'))
                                    ->order_by('id', 'DESC')
                                    ->limit(1)
                                    ->execute()
                                    ->current();
        
        //チェックされたとき
        if ((int)$data['status'] === 1) {
        
            //前の継続記録がある場合
            if ($previous_continuation && isset($previous_continuation['id'])) {
                \DB::update(self::$_table_name)
                    ->value('current_streak', \DB::expr('current_streak + 1'))
                    ->value('last_completed_at', \DB::expr('CURRENT_TIMESTAMP'))
                    ->value('next_target_at', \DB::expr("DATE_ADD(CURRENT_TIMESTAMP, INTERVAL {$frequency} DAY)"))
                    ->where('id', '=', $previous_continuation['id'])
                    ->execute();
            }

            //前の継続記録がない場合
            else {
                \DB::insert(self::$_table_name)->set([
                    'action_id' => $data['action_id'],
                    'next_target_at'   => \DB::expr("DATE_ADD(CURRENT_TIMESTAMP, INTERVAL {$frequency} DAY)"),
                ])->execute();
            }
        }

        //チェックが外されたとき
        else {
            $item = \DB::select('id', 'current_streak')
                        ->from(self::$_table_name)
                        ->where('action_id', '=', $data['action_id'])
                        ->and_where(\DB::expr('DATE(last_completed_at)'), '=', \DB::expr('CURDATE()'))
                        ->order_by('id', 'DESC')
                        ->limit(1)
                        ->execute()
                        ->current();

            //継続記録が1の場合(今日継続記録が始まった)                    
            if ((int)$item['current_streak'] === 1) {
                \DB::delete(self::$_table_name)
                    ->where('id', '=', $item['id'])
                    ->execute();
            }
            else {
                \DB::update(self::$_table_name)
                    ->value('current_streak', \DB::expr('current_streak - 1'))
                    ->value('last_completed_at', \DB::expr("DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$frequency} DAY)"))
                    ->value('next_target_at', \DB::expr('CURRENT_TIMESTAMP'))
                    ->where('id', '=', $item['id'])
                    ->execute();
            }
        }
    }

    public static function get_continuous_display($today_actions) 
    {
        $action_ids = array_column($today_actions, 'action_id');

        if (empty($action_ids)) {
            return [];
        }

        $result = \DB::select(
                        'actions.name',
                        [\DB::expr('DATE('.self::$_table_name.'.start_at)'), 'start_at'],
                        [\DB::expr('DATE('.self::$_table_name.'.last_completed_at)'), 'last_completed_at']
                        )
                    ->from(self::$_table_name)
                    ->join('actions', 'INNER')
                    ->on('display.action_id', '=', "actions.id")
                    ->where('display.action_id', 'IN', $action_ids)
                    ->execute()
                    ->as_array();
        return $result;

    }
}



