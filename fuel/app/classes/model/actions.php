<?php
class Model_Actions extends \Model_Crud
{
    protected static $_table_name = 'actions';

    public static function get_all_actions()
    {
        $result = \DB::select('*')
                        ->from(self::$_table_name)
                        ->where('deleted', '=', 0)
                        ->order_by('id', 'ASC')
                        ->execute()
                        ->as_array();
        return $result;
    }

    public static function get_today_actions()
    {
        return \DB::select(self::$_table_name . '.*', 'records.*')
                    ->from(self::$_table_name)
                    ->join('records', 'LEFT')
                    ->on('actions.id', '=', 'records.action_id')
                    ->where('actions.deleted', '=', 0)
                    ->and_where_open()
                        ->where(\DB::expr('DATE(records.next_at)'), '=', \DB::expr('CURDATE()'))
                        ->or_where(\DB::expr('DATE(records.date)'), '=', \DB::expr('CURDATE()'))
                    ->and_where_close()
                    //->and_where_open()
                    //    ->where(\DB::expr('DATE(records.date)'), '=', \DB::expr('DATE_SUB(CURDATE(), INTERVAL actions.frequency DAY)'))
                    //    ->or_where(\DB::expr('DATE(actions.created_at)'), '=', \DB::expr('DATE_SUB(CURDATE(), INTERVAL actions.frequency DAY)'))
                    //    ->or_where(\DB::expr('DATE(actions.created_at)'), '=', \DB::expr('CURDATE()'))
                    //->and_where_close()
                    ->execute()
                    ->as_array();
    }

    public static function create_new_action($data)
    {
        list($insert_id, $rows) = \DB::insert(self::$_table_name)->set([
                                        'name'      => $data['name'],
                                        'frequency' => $data['frequency'],
                                        'color'     => '#000000', // 仮の色
                                    ])->execute();

        $added_action = \DB::select('*')
                            ->from(self::$_table_name)
                            ->where('id', '=', $insert_id)
                            ->execute()
                            ->current();
        return $added_action;
    }


    public static function delete_action($data) //$data = actions_dataの形
    {
        \DB::delete(self::$_table_name)
            ->where('id', '=', $data['id'])
            ->execute();
    }

}