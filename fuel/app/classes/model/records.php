<?php
class Model_Records extends \Model_Crud
{
    protected static $_table_name = 'records';

    public static function create_new_record($data)  //$dataはnameとfrequency
    {

        $id = \DB::select('id')
                ->from('actions')
                ->order_by('id', 'DESC')  //追加した項目だから1番下に存在する
                ->execute()
                ->get('id');

        list($insert_id, $rows) = \DB::insert(self::$_table_name)->set([
                                        'action_id' => $id,
                                        'date'      => \DB::expr('CURRENT_TIMESTAMP'),
                                        'status'    => 0,
                                        'next_at'   => \DB::expr("DATE_ADD(CURRENT_TIMESTAMP, INTERVAL {$data['frequency']} DAY)"),
                                    ])->execute();

        //追加した内容をtoday_actionと同じ形で返したい
        $added_record = \DB::select('actions.*', self::$_table_name .'.*')
                            ->from('actions')
                            ->join(self::$_table_name, 'LEFT')
                            ->on('actions.id', '=', self::$_table_name .'.action_id')
                            ->where(self::$_table_name.'.id', '=', $insert_id)
                            ->execute()
                            ->current();
                
        return $added_record;
    }

    public static function get_today_actions()
    {
        return \DB::select('actions.*', self::$_table_name . '.*')
                    ->from('actions')
                    ->join(self::$_table_name, 'LEFT')
                    ->on('actions.id', '=', self::$_table_name . '.action_id')
                    ->where('actions.deleted', '=', 0)
                    ->where(\DB::expr('DATE(' . self::$_table_name . '.date)'), '=', \DB::expr('CURDATE()'))
                    //->and_where_open()
                    //    ->where(\DB::expr('DATE(self::$_table_name . '.next_at')'), '=', \DB::expr('CURDATE()'))
                    //    ->or_where(\DB::expr('DATE(self::$_table_name . '.date')'), '=', \DB::expr('CURDATE()'))
                    //->and_where_close()
                    //->and_where_open()
                    //    ->where(\DB::expr('DATE(records.date)'), '=', \DB::expr('DATE_SUB(CURDATE(), INTERVAL actions.frequency DAY)'))
                    //    ->or_where(\DB::expr('DATE(actions.created_at)'), '=', \DB::expr('DATE_SUB(CURDATE(), INTERVAL actions.frequency DAY)'))
                    //    ->or_where(\DB::expr('DATE(actions.created_at)'), '=', \DB::expr('CURDATE()'))
                    //->and_where_close()
                    ->execute()
                    ->as_array();
    }

    public static function get_tomorrow_actions()
    {
        return \DB::select('actions.*', self::$_table_name . '.*')
                    ->from('actions')
                    ->join(self::$_table_name, 'LEFT')
                    ->on('actions.id', '=', self::$_table_name . '.action_id')
                    ->where('actions.deleted', '=', 0)
                    ->where(\DB::expr('DATE(' . self::$_table_name . '.next_at)'), '=', \DB::expr('CURDATE()'))
                    //->and_where_open()
                    //    ->where(\DB::expr('DATE(self::$_table_name . '.next_at')'), '=', \DB::expr('CURDATE()'))
                    //    ->or_where(\DB::expr('DATE(self::$_table_name . '.date')'), '=', \DB::expr('CURDATE()'))
                    //->and_where_close()
                    //->and_where_open()
                    //    ->where(\DB::expr('DATE(records.date)'), '=', \DB::expr('DATE_SUB(CURDATE(), INTERVAL actions.frequency DAY)'))
                    //    ->or_where(\DB::expr('DATE(actions.created_at)'), '=', \DB::expr('DATE_SUB(CURDATE(), INTERVAL actions.frequency DAY)'))
                    //    ->or_where(\DB::expr('DATE(actions.created_at)'), '=', \DB::expr('CURDATE()'))
                    //->and_where_close()
                    ->execute()
                    ->as_array();
    }

      
    //public static function add_new_record($today_actions_id)
    //{
     //   foreach ($today_actions as $action) {
            
      //      \DB::insert(self::$_table_name)->set([
       //         'action_id' => $action['id'],
       //         'date'      => \DB::expr('CURRENT_TIMESTAMP'),
        //        'status' => 0,
       //     ])->execute();
       // }
   // }

   public static function change_status_record($data)
   {
        \DB::update(self::$_table_name)
                ->value('status', $data['status'])
                ->where('action_id', '=', $data['action_id'])
                ->and_where(\DB::expr('DATE(records.date)'), '=', \DB::expr('CURDATE()'))
                ->execute();
   }

}

?>