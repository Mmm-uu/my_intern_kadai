<?php
class Model_Display extends \Model_Crud
{
    protected static $_table_name = 'display';

    public static function create_display($added_record) //$added_recordはtoday_data形式
    {
        
        list($insert_id, $rows) = \DB::insert(self::$_table_name) -> set([
                                        'action_id' => $added_record['action_id'],
                                        'start_at' => $added_record['date']
                                    ])->execute();

        $added_display = \DB::select(
                                self::$_table_name . '.id',
                                self::$_table_name .'.action_id',
                                'actions.name', 
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

            //最新で何回継続中か
            $item = \DB::select('id', 'current_streak')
                        ->from(self::$_table_name)
                        ->where('action_id', '=', $data['action_id'])
                        ->and_where(\DB::expr('DATE(last_completed_at)'), '=', \DB::expr('CURDATE()'))
                        ->order_by('id', 'DESC')
                        ->limit(1)
                        ->execute()
                        ->current();

            if ($item['current_streak'] == 1) { //今日登録した行動 → 初期状態に戻す
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
                            ->value('last_completed_at', \DB::expr("DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$frequency} DAY)"))
                            ->value('next_target_at', \DB::expr('CURRENT_TIMESTAMP'))
                            ->where('action_id', '=', $data['action_id'])
                            ->and_where(\DB::expr('DATE(next_target_at)'), '=', \DB::expr('CURDATE()'))
                            ->execute();
            }
        }

        $change_display = \DB::select(
                                self::$_table_name . '.id',
                                self::$_table_name .'.action_id',
                                'actions.name', 
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

        $added_display = \DB::select(
                                'actions.name', 
                                [\DB::expr('DATE('.self::$_table_name.'.start_at)'), 'start_at'],
                                [\DB::expr('DATE('.self::$_table_name.'.last_completed_at)'), 'last_completed_at']
                                )
                            ->from(self::$_table_name)
                            ->join('actions', 'INNER')
                            ->on('display.action_id', '=', 'actions.id')
                            ->where('display.action_id', '=', $data['action_id'])
                            ->execute()
                            ->current();
        return $added_display;
    }

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
}



